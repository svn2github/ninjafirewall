<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - https://nintechnet.com/                            |
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

nf_not_allowed( 'block', __LINE__ );

$nfw_options = nfw_get_option( 'nfw_options' );

if (! defined('NF_DISABLED') ) {
	is_nfw_enabled();
}

?>
<div class="wrap">
	<h1><img style="vertical-align:top;width:33px;height:33px;" src="<?php echo plugins_url( '/ninjafirewall/images/ninjafirewall_32.png') ?>">&nbsp;<?php _e('NinjaFirewall (WP Edition)', 'ninjafirewall') ?></h1>

	<?php
	if ( @NFW_STATUS == 20 && ! empty( $_REQUEST['nfw_firstrun']) ) {
		echo '<br><div class="updated notice is-dismissible"><p>' .
			__('Congratulations, NinjaFirewall is up and running!', 'ninjafirewall') .	'<br />' .
			__('If you need help, click on the contextual "Help" menu tab located in the upper right corner of each page.', 'ninjafirewall');
		if (! empty($_SESSION['email_install']) ) {
			echo '<p>' . __('A "Quick Start, FAQ & Troubleshooting Guide" email was sent to', 'ninjafirewall') .' <code>' .htmlspecialchars( $_SESSION['email_install'] ) .'</code>.</p>';
			unset($_SESSION['email_install']);
		}
		echo '</p></div>';
		unset( $_SESSION['abspath'] ); unset( $_SESSION['http_server'] );
		unset( $_SESSION['php_ini_type'] ); unset( $_SESSION['abspath_writable'] );
		unset( $_SESSION['ini_write'] ); unset( $_SESSION['htaccess_write'] );
		unset( $_SESSION['waf_mode'] );
	}

	// Display a one-time notice after two weeks of use:
	nfw_rate_notice( $nfw_options );

	?>
	<br />
	<table class="form-table">

	<?php
	if (NF_DISABLED) {
		if (! empty($GLOBALS['err_fw'][NF_DISABLED]) ) {
			$msg = $GLOBALS['err_fw'][NF_DISABLED];
		} else {
			$msg = __('unknown error', 'ninjafirewall') . ' #' . NF_DISABLED;
		}
	?>
		<tr>
			<th scope="row"><?php _e('Firewall', 'ninjafirewall') ?></th>
			<td width="20" align="left"><img src="<?php echo plugins_url( '/ninjafirewall/images/glyphicons-error.png' ) ?>"></td>
			<td><?php echo $msg ?></td>
		</tr>

	<?php
	} else {
	?>

		<tr>
			<th scope="row"><?php _e('Firewall', 'ninjafirewall') ?></th>
			<td width="20" align="left">&nbsp;</td>
			<td><?php _e('Enabled', 'ninjafirewall') ?></td>
		</tr>

	<?php
	}

	if ( defined('NFW_WPWAF') ) {
		$mode = __('WordPress WAF', 'ninjafirewall');
	} else {
		$mode = __('Full WAF', 'ninjafirewall');
	}
	?>
		<tr>
			<th scope="row"><?php _e('Mode', 'ninjafirewall') ?></th>
			<td width="20" align="left">&nbsp;</td>
			<td><?php printf( __('NinjaFirewall is running in %s mode.', 'ninjafirewall'), '<a href="https://blog.nintechnet.com/full_waf-vs-wordpress_waf/">'. $mode .'</a>'); ?></td>
		</tr>
	<?php

	if (! empty( $nfw_options['debug']) ) {
	?>
		<tr>
			<th scope="row"><?php _e('Debugging mode', 'ninjafirewall') ?></th>
			<td width="20" align="left"><img src="<?php echo plugins_url( '/ninjafirewall/images/glyphicons-error.png' ) ?>"></td>
			<td><?php _e('Enabled.', 'ninjafirewall') ?>&nbsp;<a href="?page=nfsubopt"><?php _e('Click here to turn Debugging Mode off', 'ninjafirewall') ?></a></td>
		</tr>
	<?php
	}
	?>
		<tr>
			<th scope="row"><?php _e('PHP SAPI', 'ninjafirewall') ?></th>
			<td width="20" align="left">&nbsp;</td>
			<td>
				<?php
				if ( defined('HHVM_VERSION') ) {
					echo 'HHVM';
				} else {
					echo strtoupper(PHP_SAPI);
				}
				echo ' ~ '. PHP_MAJOR_VERSION .'.'. PHP_MINOR_VERSION .'.'. PHP_RELEASE_VERSION;
				?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Version', 'ninjafirewall') ?></th>
			<td width="20" align="left">&nbsp;</td>
			<td><?php echo NFW_ENGINE_VERSION . ' ~ ' . __('Security rules:', 'ninjafirewall' ) . ' ' . preg_replace('/(\d{4})(\d\d)(\d\d)/', '$1-$2-$3', $nfw_options['rules_version']) ?></td>
		</tr>
	<?php

	// If security rules updates are disabled, warn the user:
	if ( empty( $nfw_options['enable_updates'] ) ) {
		?>
		<tr>
			<th scope="row"><?php _e('Updates', 'ninjafirewall') ?></th>
			<td width="20" align="left"><img src="<?php echo plugins_url() ?>/ninjafirewall/images/glyphicons-warning.png"></td>
			<td><a href="?page=nfsubupdates"><?php _e( 'Security rules updates are disabled.', 'ninjafirewall' ) ?></a> <?php _e( 'If you want your blog to be protected against the latest threats, enable automatic security rules updates.', 'ninjafirewall' ) ?></td>
		</tr>
		<?php
	}

	if ( empty($_SESSION['nfw_goodguy']) ) {
		?>
		<tr>
			<th scope="row"><?php _e('Admin user', 'ninjafirewall') ?></th>
			<td width="20" align="left"><img src="<?php echo plugins_url() ?>/ninjafirewall/images/glyphicons-warning.png"></td>
			<td><?php printf( __('You are not whitelisted. Ensure that the "Do not block WordPress administrator" option is enabled in the <a href="%s">Firewall Policies</a> menu, otherwise you could get blocked by the firewall while working from your administration dashboard.', 'ninjafirewall'), '?page=nfsubpolicies') ?></td>
		</tr>
	<?php
	} else {
		$current_user = wp_get_current_user();
		?>
		<tr>
			<th scope="row"><?php _e('Admin user', 'ninjafirewall') ?></th>
			<td width="20" align="left">&nbsp;</td>
			<td><code><?php echo htmlspecialchars($current_user->user_login) ?></code>: <?php _e('You are whitelisted by the firewall.', 'ninjafirewall') ?></td>
		</tr>
	<?php
	}
	if ( defined('NFW_ALLOWED_ADMIN') && ! is_multisite() ) {
	?>
		<tr>
			<th scope="row"><?php _e('Restrictions', 'ninjafirewall') ?></th>
			<td width="20" align="left">&nbsp;</td>
			<td><?php _e('Access to NinjaFirewall is restricted to:', 'ninjafirewall') ?> <code><?php echo htmlspecialchars(NFW_ALLOWED_ADMIN) ?></code></td>
		</tr>
	<?php
	}

	// Try to find out if there is any "lost" session between the firewall
	// and the plugin part of NinjaFirewall (could be a buggy plugin killing
	// the session etc), unless we just installed it:
	if ( defined( 'NFW_SWL' ) && ! empty( $_SESSION['nfw_goodguy'] ) && empty( $_REQUEST['nfw_firstrun'] ) ) {
		?>
		<tr>
			<th scope="row"><?php _e('User session', 'ninjafirewall') ?></th>
			<td width="20" align="left"><img src="<?php echo plugins_url() . '/ninjafirewall/images/glyphicons-warning.png' ?>"></td>
			<td><?php _e('It seems that the user session set by NinjaFirewall was not found by the firewall script.', 'ninjafirewall') ?></td>
		</tr>
		<?php
	}

	if ( ! empty( $nfw_options['clogs_pubkey'] ) ) {
		$err_msg = $ok_msg = '';
		if (! preg_match( '/^[a-f0-9]{40}:([a-f0-9:.]{3,39}|\*)$/', $nfw_options['clogs_pubkey'], $match ) ) {
			$err_msg = sprintf( __('the public key is invalid. Please <a href="%s">check your configuration</a>.', 'ninjafirewall'), '?page=nfsublog#clogs');

		} else {
			if ( $match[1] == '*' ) {
				$ok_msg = __( "No IP address restriction.", 'ninjafirewall');

			} elseif ( filter_var( $match[1], FILTER_VALIDATE_IP ) ) {
				$ok_msg = sprintf( __("IP address %s is allowed to access NinjaFirewall's log on this server.", 'ninjafirewall'), htmlspecialchars( $match[1]) );

			} else {
				$err_msg = sprintf( __('the whitelisted IP is not valid. Please <a href="%s">check your configuration</a>.', 'ninjafirewall'), '?page=nfsublog#clogs');
			}
		}
		?>
		<tr>
			<th scope="row"><?php _e('Centralized Logging', 'ninjafirewall') ?></th>
		<?php
		if ( $err_msg ) {
			?>
				<td width="20" align="left"><img src="<?php echo plugins_url() . '/ninjafirewall/images/glyphicons-error.png' ?>"></td>
				<td><?php printf( __('Error: %s', 'ninjafirewall'), $err_msg) ?></td>
			</tr>
			<?php
			$err_msg = '';
		} else {
			?>
				<td width="20" align="left">&nbsp;</td>
				<td><a href="?page=nfsublog#clogs"><?php _e('Enabled', 'ninjafirewall'); echo "</a>. $ok_msg"; ?></td>
			</tr>
		<?php
		}
	}



	if (! filter_var(NFW_REMOTE_ADDR, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ) {
		?>
		<tr>
			<th scope="row"><?php _e('Source IP', 'ninjafirewall') ?></th>
			<td width="20" align="left"><img src="<?php echo plugins_url( '/ninjafirewall/images/glyphicons-warning.png' )?>"></td>
			<td><?php printf( __('You have a private IP : %s', 'ninjafirewall') .'<br />'. __('If your site is behind a reverse proxy or a load balancer, ensure that you have setup your HTTP server or PHP to forward the correct visitor IP, otherwise use the NinjaFirewall %s configuration file.', 'ninjafirewall'), htmlentities(NFW_REMOTE_ADDR), '<code><a href="https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja">.htninja</a></code>') ?></td>
		</tr>
		<?php
	}
	if (! empty($_SERVER["HTTP_CF_CONNECTING_IP"]) ) {
		if ( NFW_REMOTE_ADDR != $_SERVER["HTTP_CF_CONNECTING_IP"] ) {
		?>
		<tr>
			<th scope="row"><?php _e('CDN detection', 'ninjafirewall') ?></th>
			<td width="20" align="left"><img src="<?php echo plugins_url( '/ninjafirewall/images/glyphicons-warning.png' )?>"></td>
			<td><?php printf( __('%s detected: you seem to be using Cloudflare CDN services. Ensure that you have setup your HTTP server or PHP to forward the correct visitor IP, otherwise use the NinjaFirewall %s configuration file.', 'ninjafirewall'), '<code>HTTP_CF_CONNECTING_IP</code>', '<code><a href="https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja">.htninja</a></code>') ?></td>
		</tr>
		<?php
		}
	}
	if (! empty($_SERVER["HTTP_INCAP_CLIENT_IP"]) ) {
		if ( NFW_REMOTE_ADDR != $_SERVER["HTTP_INCAP_CLIENT_IP"] ) {
		?>
		<tr>
			<th scope="row"><?php _e('CDN detection', 'ninjafirewall') ?></th>
			<td width="20" align="left"><img src="<?php echo plugins_url( '/ninjafirewall/images/glyphicons-warning.png' )?>"></td>
			<td><?php printf( __('%s detected: you seem to be using Incapsula CDN services. Ensure that you have setup your HTTP server or PHP to forward the correct visitor IP, otherwise use the NinjaFirewall %s configuration file.', 'ninjafirewall'), '<code>HTTP_INCAP_CLIENT_IP</code>', '<code><a href="https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja">.htninja</a></code>') ?></td>
		</tr>
		<?php
		}
	}

	if (! is_writable( NFW_LOG_DIR . '/nfwlog') ) {
		?>
			<tr>
			<th scope="row"><?php _e('Log dir', 'ninjafirewall') ?></th>
			<td width="20" align="left"><img src="<?php echo plugins_url( '/ninjafirewall/images/glyphicons-error.png' )?>"></td>
			<td><?php printf( __('%s directory is not writable! Please chmod it to 0777 or equivalent.', 'ninjafirewall'), '<code>'. htmlspecialchars(NFW_LOG_DIR) .'/nfwlog/</code>') ?></td>
		</tr>
	<?php
	}

	if (! is_writable( NFW_LOG_DIR . '/nfwlog/cache') ) {
		?>
			<tr>
			<th scope="row"><?php _e('Log dir', 'ninjafirewall') ?></th>
			<td width="20" align="left"><img src="<?php echo plugins_url( '/ninjafirewall/images/glyphicons-error.png' )?>"></td>
			<td><?php printf(__('%s directory is not writable! Please chmod it to 0777 or equivalent.', 'ninjafirewall'), '<code>'. htmlspecialchars(NFW_LOG_DIR) . '/nfwlog/cache/</code>') ?></td>
		</tr>
	<?php
	}

	$doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
	if ( @file_exists( $file = dirname( $doc_root ) . '/.htninja') ||
		@file_exists( $file = $doc_root . '/.htninja') ) {
		echo '<tr><th scope="row">' . __('Optional configuration file', 'ninjafirewall') . '</th>
		<td width="20">&nbsp;</td>
		<td><code>' .  htmlentities($file) . '</code></td>
		</tr>';

		// Check if we have a MySQLi link identifier defined in the .htninja:
		if (! empty( $GLOBALS['nfw_mysqli'] ) && ! empty( $GLOBALS['nfw_table_prefix'] ) ) {
			echo '<tr>
			<th scope="row">' . __('MySQLi link identifier', 'ninjafirewall') . '</th>
			<td width="20">&nbsp;</td>
			<td>' . __('A MySQLi link identifier was detected in your <code>.htninja</code>.', 'ninjafirewall') . '</td>
			</tr>';
		}

	}

	echo '</table>';
	?>
</div>

<?php
// ---------------------------------------------------------------------
// EOF
