<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 +---------------------------------------------------------------------+
 | REVISION: 2016-08-26 17:10:46                                       |
 +---------------------------------------------------------------------+
 | This program is free software: you can redistribute it and/or       |
 | modify it under the terms of the GNU General Public License as      |
 | published by the Free Software Foundation, either version 3 of      |
 | the License, or (at your option) any later version.                 |
 |                                                                     |
 | This program is distributed in the hope that it will be useful,     |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of      |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       |
 | GNU General Public License for more details.                        |
 +---------------------------------------------------------------------+ i18n+ / sa
*/

if (! defined( 'NFW_ENGINE_VERSION' ) ) { die( 'Forbidden' ); }

/* ------------------------------------------------------------------ */

function nfw_select_ip() {
	// Ensure we have a proper and single IP (a user may use the .htninja file
	// to redirect HTTP_X_FORWARDED_FOR, which may contain more than one IP,
	// to REMOTE_ADDR):
	if (strpos($_SERVER['REMOTE_ADDR'], ',') !== false) {
		$nfw_match = array_map('trim', @explode(',', $_SERVER['REMOTE_ADDR']));
		foreach($nfw_match as $nfw_m) {
			if ( filter_var($nfw_m, FILTER_VALIDATE_IP) )  {
				define( 'NFW_REMOTE_ADDR', $nfw_m);
				break;
			}
		}
	}
	if (! defined('NFW_REMOTE_ADDR') ) {
		define('NFW_REMOTE_ADDR', $_SERVER['REMOTE_ADDR']);
	}
}
/* ------------------------------------------------------------------ */

function nfw_admin_notice(){

	if (nf_not_allowed( 0, __LINE__ ) ) { return; }

	if (! defined('NF_DISABLED') ) {
		is_nfw_enabled();
	}

	if (! file_exists(NFW_LOG_DIR . '/nfwlog') ) {
		@mkdir( NFW_LOG_DIR . '/nfwlog', 0755);
		@touch( NFW_LOG_DIR . '/nfwlog/index.html' );
		@file_put_contents(NFW_LOG_DIR . '/nfwlog/.htaccess', "Order Deny,Allow\nDeny from all", LOCK_EX);
		if (! file_exists(NFW_LOG_DIR . '/nfwlog/cache') ) {
			@mkdir( NFW_LOG_DIR . '/nfwlog/cache', 0755);
			@touch( NFW_LOG_DIR . '/nfwlog/cache/index.html' );
			@file_put_contents(NFW_LOG_DIR . '/nfwlog/cache/.htaccess', "Order Deny,Allow\nDeny from all", LOCK_EX);
		}
	}
	if (! file_exists(NFW_LOG_DIR . '/nfwlog') ) {
		echo '<div class="error notice is-dismissible"><p><strong>' . __('NinjaFirewall error', 'ninjafirewall') . ' :</strong> ' .
			sprintf( __('%s directory cannot be created. Please review your installation and ensure that %s is writable.', 'ninjafirewall'), '<code>'. htmlspecialchars(NFW_LOG_DIR) .'/nfwlog/</code>',  '<code>/wp-content/</code>') . '</p></div>';
	}
	if (! is_writable(NFW_LOG_DIR . '/nfwlog') ) {
		echo '<div class="error notice is-dismissible"><p><strong>' . __('NinjaFirewall error', 'ninjafirewall') . ' :</strong> ' .
			sprintf( __('%s directory is read-only. Please review your installation and ensure that %s is writable.', 'ninjafirewall'), '<code>'. htmlspecialchars(NFW_LOG_DIR) .'/nfwlog/</code>', '<code>/nfwlog/</code>') . '</p></div>';
	}

	if (! NF_DISABLED) {
		return;
	}

	if (isset($_GET['page']) && preg_match('/^(?:NinjaFirewall|nfsubopt)$/', $_GET['page']) ) {
		return;
	}

	$nfw_options = nfw_get_option('nfw_options');
	if ( empty($nfw_options['ret_code']) && NF_DISABLED != 11 ) {
		return;
	}

	if (! empty($GLOBALS['err_fw'][NF_DISABLED]) ) {
		$msg = $GLOBALS['err_fw'][NF_DISABLED];
	} else {
		$msg = __('unknown error', 'ninjafirewall') . ' #' . NF_DISABLED;
	}
	echo '<div class="error notice is-dismissible"><p><strong>' . __('NinjaFirewall fatal error:', 'ninjafirewall') . '</strong> ' . $msg .
		'. ' . __('Review your installation, your site is not protected.', 'ninjafirewall') . '</p></div>';
}

add_action('all_admin_notices', 'nfw_admin_notice');

/* ------------------------------------------------------------------ */

function nfw_query( $query ) { // i18n

	$nfw_options = nfw_get_option( 'nfw_options' );
	if ( empty($nfw_options['enum_archives']) || empty($nfw_options['enabled']) || is_admin() ) {
		return;
	}
	if ( $query->is_main_query() && $query->is_author() ) {
		if ( $query->get('author_name') ) {
			$tmp = 'author_name=' . $query->get('author_name');
		} elseif ( $query->get('author') ) {
			$tmp = 'author=' . $query->get('author');
		} else {
			$tmp = 'author';
		}
		@session_destroy();
		$query->set('author_name', '0');
		nfw_log2('User enumeration scan (author archives)', $tmp, 2, 0);
		wp_redirect( home_url('/') );
		exit;
	}
}

if (! isset($_SESSION['nfw_goodguy']) ) {
	add_action('pre_get_posts','nfw_query');
}

/* ------------------------------------------------------------------ */

function nfw_authenticate( $user ) { // i18n

	$nfw_options = nfw_get_option( 'nfw_options' );

	if ( empty( $nfw_options['enum_login']) || empty($nfw_options['enabled']) ) {
		return $user;
	}

	if ( is_wp_error( $user ) ) {
		if ( preg_match( '/^(?:in(?:correct_password|valid_username)|authentication_failed)$/', $user->get_error_code() ) ) {
			$user = new WP_Error( 'denied', sprintf( __( '<strong>ERROR</strong>: Invalid username or password.<br /><a href="%s">Lost your password</a>?', 'ninjafirewall' ), wp_lostpassword_url() ) );
			add_filter('shake_error_codes', 'nfw_err_shake');
		}
	}
	return $user;
}

add_filter( 'authenticate', 'nfw_authenticate', 90, 3 );

function nfw_err_shake( $shake_codes ) {
	$shake_codes[] = 'denied';
	return $shake_codes;
}

/* ------------------------------------------------------------------ */

function nf_check_dbdata() {

	$nfw_options = nfw_get_option( 'nfw_options' );

	if ( empty( $nfw_options['enabled'] ) || empty($nfw_options['a_51']) ) { return; }

	if ( is_multisite() ) {
		global $current_blog;
		$nfdbhash = NFW_LOG_DIR .'/nfwlog/cache/nfdbhash.'. $current_blog->site_id .'-'. $current_blog->blog_id .'.php';
	} else {
		global $blog_id;
		$nfdbhash = NFW_LOG_DIR .'/nfwlog/cache/nfdbhash.'. $blog_id .'.php';
	}

	$adm_users = nf_get_dbdata();
	if (! $adm_users) { return; }

	if (! file_exists($nfdbhash) ) {
		@file_put_contents( $nfdbhash, md5( serialize( $adm_users) ), LOCK_EX );
		return;
	}

	$old_hash = trim (file_get_contents($nfdbhash) );

	if ( $old_hash == md5( serialize($adm_users)) ) {
		return;
	} else {
		$fstat = stat($nfdbhash);
		if ( ( time() - $fstat['mtime']) < 60 ) {
			return;
		}

		$tmp = @file_put_contents( $nfdbhash, md5( serialize( $adm_users) ), LOCK_EX );
		if ( $tmp === FALSE ) {
			return;
		}

		nfw_get_blogtimezone();

		if ( ( is_multisite() ) && ( $nfw_options['alert_sa_only'] == 2 ) ) {
			$recipient = get_option('admin_email');
		} else {
			$recipient = $nfw_options['alert_email'];
		}

		$subject = __('[NinjaFirewall] Alert: Database changes detected', 'ninjafirewall');
		$message = __('NinjaFirewall has detected that one or more administrator accounts were modified in the database:', 'ninjafirewall') . "\n\n";
		if ( is_multisite() ) {
			$message.= __('Blog:', 'ninjafirewall') .' '. network_home_url('/') . "\n";
		} else {
			$message.= __('Blog:', 'ninjafirewall') .' '. home_url('/') . "\n";
		}
		$message.= __('User IP:', 'ninjafirewall') .' '. NFW_REMOTE_ADDR . "\n";
		$message.= __('Date:', 'ninjafirewall') .' '. date_i18n('F j, Y @ H:i:s') . ' (UTC '. date('O') . ")\n\n";
		$message.= sprintf(__('Total administrators : %s', 'ninjafirewall'), count($adm_users) ) . "\n\n";
		foreach( $adm_users as $obj => $adm ) {
			$message.= 'Admin ID : ' . $adm->ID . "\n";
			$message.= '-user_login : ' . $adm->user_login . "\n";
			$message.= '-user_nicename : ' . $adm->user_nicename . "\n";
			$message.= '-user_email : ' . $adm->user_email . "\n";
			$message.= '-user_registered : ' . $adm->user_registered . "\n";
			$message.= '-display_name : ' . $adm->display_name . "\n\n";
		}
		$message.= "\n" . __('If you cannot see any modifications in the above fields, it is likely that the administrator password was changed.', 'ninjafirewall'). "\n\n";
		$message.= 	'NinjaFirewall (WP Edition) - http://ninjafirewall.com/' . "\n" .
						'Support forum: http://wordpress.org/support/plugin/ninjafirewall' . "\n";
		wp_mail( $recipient, $subject, $message );

		if (! empty($nfw_options['a_41']) ) {
			nfw_log2('Database changes detected', __('administrator account', 'ninjafirewall'), 4, 0);
		}
	}

}

/* ------------------------------------------------------------------ */

function nf_get_dbdata() {

	return get_users(
		array( 'role' => 'administrator',
			'fields' => array(
				'ID', 'user_login', 'user_pass', 'user_nicename',
				'user_email', 'user_registered', 'display_name'
			)
		)
	);

}

/* ------------------------------------------------------------------ */

function nfw_get_option( $option ) {

	if ( is_multisite() ) {
		return get_site_option($option);
	} else {
		return get_option($option);
	}
}

/* ------------------------------------------------------------------ */

function nfw_update_option( $option, $new_value ) {

	update_option( $option, $new_value );
	if ( is_multisite() ) {
		update_site_option( $option, $new_value );
	}
	return;
}

/* ------------------------------------------------------------------ */

function nfw_delete_option( $option ) {

	delete_option( $option );
	if ( is_multisite() ) {
		delete_site_option( $option );
	}
	return;
}

/* ------------------------------------------------------------------ */

function nfwhook_update_option( $option, $new_value = '' ) {

	nfwhook_option( $option, $new_value, 'update' );

}
add_filter('update_option', 'nfwhook_update_option', 1, 2);

/* ------------------------------------------------------------------ */

function nfwhook_delete_option( $option ) {

	nfwhook_option( $option, null, 'delete' );

}
add_filter('delete_option', 'nfwhook_update_option', 1, 1);

/* ------------------------------------------------------------------ */

function nfwhook_option( $option, $new_value = '', $hook ) {

	if (! defined('NF_DISABLED') ) {
		is_nfw_enabled();
	}
	if ( NF_DISABLED || defined('NFW_DISABLE_PRVESC1') ) { return; }

	global $wpdb;

	if ( ( ($option == 'users_can_register' || $option == 'default_role' ||
		$option == 'admin_email' ) &&	! current_user_can( 'manage_options' ) )
		||
		( $option ==  "{$wpdb->base_prefix}user_roles" && ! current_user_can( 'edit_users' ) ) ) {

		$msg = sprintf( 'Unauthorized attempt to %s WordPress options', $hook );
		if ( $new_value != '' ) {
			if ( is_array( $new_value ) ) {
				$new_value = ': ' . serialize( $new_value );
			} else {
				$new_value = ': ' . $new_value;
			}
		}
		nfw_log2($msg, "$option$new_value", 3, 0);

		$nfw_options = nfw_get_option( 'nfw_options' );

		@session_destroy();

		wp_die( "NinjaFirewall: $msg.", 'NinjaFirewall',
			array( 'response' => $nfw_options['ret_code'] ) );
	}
}

/* ------------------------------------------------------------------ */

function nfwhook_update_user_meta( $user_id, $meta_key, $meta_value, $prev_value ) {

	nfwhook_user_meta( $meta_value, $prev_value );

}
add_filter('update_user_meta', 'nfwhook_update_user_meta', 1, 4);

/* ------------------------------------------------------------------ */

function nfwhook_add_user_meta( $user_id, $meta_key, $meta_value ) {

	nfwhook_user_meta( $meta_key, $meta_value );

}
add_filter('add_user_meta', 'nfwhook_add_user_meta', 1, 3);

/* ------------------------------------------------------------------ */

function nfwhook_user_meta( $key, $value ) {

	if (! defined('NF_DISABLED') ) {
		is_nfw_enabled();
	}
	if ( NF_DISABLED || defined('NFW_DISABLE_PRVESC2') ) { return; }

	global $wpdb;

	if ( is_array( $key ) ) {
		$key = serialize( $key );
	}
	if ( strpos( $key, "{$wpdb->base_prefix}capabilities") !== FALSE && ! current_user_can('edit_users') ) {
		if ( is_array( $value ) ) {
			$value = serialize( $value );
		}
		if ( strpos( $value, "administrator") === FALSE ) { return; }

		$msg = sprintf('WordPress %s %s attempt',	'privilege',
			'escalation');
		nfw_log2( $msg, "$key: $value", 3, 0);

		$nfw_options = nfw_get_option( 'nfw_options' );

		@session_destroy();

		wp_die( "NinjaFirewall: $msg.", 'NinjaFirewall',
			array( 'response' => $nfw_options['ret_code'] ) );
	}
}
/* ------------------------------------------------------------------ */
// EOF
