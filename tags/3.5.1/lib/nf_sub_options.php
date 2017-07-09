<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 +---------------------------------------------------------------------+
 | REVISION: 2016-01-28 22:21:46                                       |
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

// Block immediately if user is not allowed :
nf_not_allowed( 'block', __LINE__ );

$nfw_options = nfw_get_option( 'nfw_options' );

echo '
<script>
function preview_msg() {
	var t1 = document.option_form.elements[\'nfw_options[blocked_msg]\'].value.replace(\'%%REM_ADDRESS%%\',\'' . htmlspecialchars(NFW_REMOTE_ADDR) . '\');
	var t2 = t1.replace(\'%%NUM_INCIDENT%%\',\'1234567\');
	var t3 = t2.replace(\'%%NINJA_LOGO%%\',\'<img src="' . plugins_url() . '/ninjafirewall/images/ninjafirewall_75.png" width="75" height="75" title="NinjaFirewall">\');
	var ns;
	if ( t3.match(/<style/i) ) {
		ns = "'. esc_js( __('CSS style sheets', 'ninjafirewall') ) .'";
	}
	if ( t3.match(/<script/i) ) {
		ns = "'. esc_js( __('Javascript code', 'ninjafirewall') ) .'";
	}
	if ( ns ) {
		alert("'. sprintf( esc_js( __('Your message seems to contain %s. For security reasons, it cannot be previewed from the admin dashboard.', 'ninjafirewall') ), '"+ ns +"'). '");
		return false;
	}
	document.getElementById(\'out_msg\').innerHTML = t3;
	jQuery("#td_msg").slideDown();
	document.getElementById(\'btn_msg\').value = \'' . esc_js( __('Refresh preview', 'ninjafirewall') ) . '\';
}
function default_msg() {
	document.option_form.elements[\'nfw_options[blocked_msg]\'].value = "' . preg_replace( '/[\r\n]/', '\n', NFW_DEFAULT_MSG) .'";
}
</script>

<div class="wrap">
	<div style="width:33px;height:33px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h1>' . __('Firewall Options', 'ninjafirewall') . '</h1>';

// Saved options ?
if ( isset( $_POST['nfw_options']) ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'options_save') ) {
		wp_nonce_ays('options_save');
	}
	$res = nf_sub_options_save();
	$nfw_options = nfw_get_option( 'nfw_options' );
	if ($res) {
		echo '<div class="error notice is-dismissible"><p>' . $res . '.</p></div>';
	} else {
		echo '<div class="updated notice is-dismissible"><p>' . __('Your changes have been saved.', 'ninjafirewall') . '</p></div>';
	}
}

?><br />
	<form method="post" name="option_form" enctype="multipart/form-data">
	<?php wp_nonce_field('options_save', 'nfwnonce', 0); ?>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Firewall protection', 'ninjafirewall') ?></th>
<?php
// Enabled :
if (! empty( $nfw_options['enabled']) ) {
	echo '
			<td width="20" align="left">&nbsp;</td>
			<td align="left">
				<select name="nfw_options[enabled]" style="width:200px">
					<option value="1" selected>' . __('Enabled', 'ninjafirewall') . '</option>
					<option value="0">' . __('Disabled', 'ninjafirewall') . '</option>
				</select>';
// Disabled :
} else {
	echo '
			<td width="20" align="left"><img src="' . plugins_url() . '/ninjafirewall/images/icon_error_16.png" border="0" height="16" width="16"></td>
			<td align="left">
				<select name="nfw_options[enabled]" style="width:200px">
					<option value="1">' . __('Enabled', 'ninjafirewall') . '</option>
					<option value="0" selected>' . __('Disabled', 'ninjafirewall') . '</option>
				</select>&nbsp;<span class="description">&nbsp;' . __('Warning: your site is not protected!', 'ninjafirewall') . '</span>';
}
echo '
			</td>
		</tr>
		<tr>
			<th scope="row">' . __('Debugging mode', 'ninjafirewall') . '</th>';

// Debugging enabled ?
if (! empty( $nfw_options['debug']) ) {
echo '<td width="20" align="left"><img src="' . plugins_url() . '/ninjafirewall/images/icon_error_16.png" border="0" height="16" width="16"></td>
			<td align="left">
				<select name="nfw_options[debug]" style="width:200px">
				<option value="1" selected>' . __('Enabled', 'ninjafirewall') . '</option>
					<option value="0">' . __('Disabled (default)', 'ninjafirewall') . '</option>
				</select>&nbsp;<span class="description">&nbsp;' . __('Warning: your site is not protected!', 'ninjafirewall') . '</span>
			</td>';

} else {
// Debugging disabled ?
echo '<td width="20">&nbsp;</td>
			<td align="left">
				<select name="nfw_options[debug]" style="width:200px">
				<option value="1">' . __('Enabled', 'ninjafirewall') . '</option>
					<option value="0" selected>' . __('Disabled (default)', 'ninjafirewall') . '</option>
				</select>
			</td>';
}

// Get (if any) the HTTP error code to return :
if (! @preg_match( '/^(?:40[0346]|50[03])$/', $nfw_options['ret_code']) ) {
	$nfw_options['ret_code'] = '403';
}
?>
		</tr>
		<tr>
			<th scope="row"><?php _e('HTTP error code to return', 'ninjafirewall') ?></th>
			<td width="20">&nbsp;</td>
			<td align="left">
			<select name="nfw_options[ret_code]" style="width:200px">
			<option value="400"<?php selected($nfw_options['ret_code'], 400) ?>><?php _e('400 Bad Request', 'ninjafirewall') ?></option>
			<option value="403"<?php selected($nfw_options['ret_code'], 403) ?>><?php _e('403 Forbidden (default)', 'ninjafirewall') ?></option>
			<option value="404"<?php selected($nfw_options['ret_code'], 404) ?>><?php _e('404 Not Found', 'ninjafirewall') ?></option>
			<option value="406"<?php selected($nfw_options['ret_code'], 406) ?>><?php _e('406 Not Acceptable', 'ninjafirewall') ?></option>
			<option value="500"<?php selected($nfw_options['ret_code'], 500) ?>><?php _e('500 Internal Server Error', 'ninjafirewall') ?></option>
			<option value="503"<?php selected($nfw_options['ret_code'], 503) ?>><?php _e('503 Service Unavailable', 'ninjafirewall') ?></option>
			</select>
			</td>
		</tr>
<?php
echo '
		<tr>
			<th scope="row">' . __('Blocked user message', 'ninjafirewall') . '</th>
			<td width="20">&nbsp;</td>
			<td align="left">
				<textarea name="nfw_options[blocked_msg]" class="small-text code" cols="60" rows="5" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">';

if (! empty( $nfw_options['blocked_msg']) ) {
	echo htmlentities(base64_decode($nfw_options['blocked_msg']));
} else {
	echo NFW_DEFAULT_MSG;
}
?></textarea>
				<p><input class="button-secondary" type="button" id="btn_msg" value="<?php _e('Preview message', 'ninjafirewall') ?>" onclick="javascript:preview_msg();" />&nbsp;&nbsp;<input class="button-secondary" type="button" id="btn_msg" value="<?php _e('Default message', 'ninjafirewall') ?>" onclick="javascript:default_msg();" /></p>
			</td>
		</tr>
	</table>

	<div id="td_msg" style="display:none">
		<table class="form-table" border="1">
			<tr><td id="out_msg" style="border:1px solid #DFDFDF;background-color:#ffffff;" width="100%"></td></tr>
		</table>
	</div>

	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Export configuration', 'ninjafirewall') ?></th>
			<td width="20">&nbsp;</td>
			<td align="left"><input class="button-secondary" type="submit" name="nf_export" value="<?php _e('Download', 'ninjafirewall') ?>" /><br /><span class="description"><?php _e( 'File Check configuration will not be exported/imported.', 'ninjafirewall') ?></span></td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Import configuration', 'ninjafirewall') ?></th>
			<td width="20">&nbsp;</td>
			<td align="left"><input type="file" name="nf_imp" /><br /><span class="description"><?php
				printf( __( 'Imported configuration must match plugin version %s.', 'ninjafirewall'), NFW_ENGINE_VERSION);
				echo '<br />'. __('It will override all your current firewall options and rules.', 'ninjafirewall')
			?></span></td>
		</tr>
	</table>

	<br />
	<input class="button-primary" type="submit" name="Save" value="<?php _e('Save Firewall Options', 'ninjafirewall') ?>" />
	</form>
</div>

<?php
return;

/* ------------------------------------------------------------------ */

function nf_sub_options_save() {

	// Save options :

	// Check if we are uploading/importing the configuration :
	if (! empty($_FILES['nf_imp']['size']) ) {
		return nf_sub_options_import();
	}

	$nfw_options = nfw_get_option( 'nfw_options' );

	if ( empty( $_POST['nfw_options']['enabled']) ) {
		if (! empty($nfw_options['enabled']) ) {
			// Alert the admin :
			nf_sub_options_alert(1);
		}
		$nfw_options['enabled'] = 0;

		// Disable cron jobs:
		if ( wp_next_scheduled('nfscanevent') ) {
			wp_clear_scheduled_hook('nfscanevent');
		}
		if ( wp_next_scheduled('nfsecupdates') ) {
			wp_clear_scheduled_hook('nfsecupdates');
		}
		if ( wp_next_scheduled('nfdailyreport') ) {
			wp_clear_scheduled_hook('nfdailyreport');
		}
		// Disable brute-force protection :
		if ( file_exists( NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php' ) ) {
			rename(NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php', NFW_LOG_DIR . '/nfwlog/cache/bf_conf_off.php');
		}

	} else {
		$nfw_options['enabled'] = 1;

		// Re-enable cron jobs, if needed :
		if (! empty($nfw_options['sched_scan']) ) {
			if ($nfw_options['sched_scan'] == 1) {
				$schedtype = 'hourly';
			} elseif ($nfw_options['sched_scan'] == 2) {
				$schedtype = 'twicedaily';
			} else {
				$schedtype = 'daily';
			}
			if ( wp_next_scheduled('nfscanevent') ) {
				wp_clear_scheduled_hook('nfscanevent');
			}
			wp_schedule_event( time() + 3600, $schedtype, 'nfscanevent');
		}
		if (! empty($nfw_options['enable_updates']) ) {
			if ($nfw_options['sched_updates'] == 1) {
				$schedtype = 'hourly';
			} elseif ($nfw_options['sched_updates'] == 2) {
				$schedtype = 'twicedaily';
			} else {
				$schedtype = 'daily';
			}
			if ( wp_next_scheduled('nfsecupdates') ) {
				wp_clear_scheduled_hook('nfsecupdates');
			}
			wp_schedule_event( time() + 15, $schedtype, 'nfsecupdates');
		}
		// Re-enable daily report, if needed :
		if (! empty($nfw_options['a_52']) ) {
			if ( wp_next_scheduled('nfdailyreport') ) {
				wp_clear_scheduled_hook('nfdailyreport');
			}
			nfw_get_blogtimezone();
			wp_schedule_event( strtotime( date('Y-m-d 00:00:05', strtotime("+1 day")) ), 'daily', 'nfdailyreport');
		}
		// Reenable brute-force protection :
		if ( file_exists( NFW_LOG_DIR . '/nfwlog/cache/bf_conf_off.php' ) ) {
			rename(NFW_LOG_DIR . '/nfwlog/cache/bf_conf_off.php', NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php');
		}
	}

	if ( (isset( $_POST['nfw_options']['ret_code'])) &&
		(preg_match( '/^(?:40[0346]|50[03])$/', $_POST['nfw_options']['ret_code'])) ) {
		$nfw_options['ret_code'] = $_POST['nfw_options']['ret_code'];
	} else {
		$nfw_options['ret_code'] = '403';
	}

	if ( empty( $_POST['nfw_options']['blocked_msg']) ) {
		$nfw_options['blocked_msg'] = base64_encode(NFW_DEFAULT_MSG);
	} else {
		$nfw_options['blocked_msg'] = base64_encode(stripslashes($_POST['nfw_options']['blocked_msg']));
	}

	if ( empty( $_POST['nfw_options']['debug']) ) {
		$nfw_options['debug'] = 0;
	} else {
		if ( empty($nfw_options['debug']) ) {
			// Alert the admin :
			nf_sub_options_alert(2);
		}
		$nfw_options['debug'] = 1;
	}

	// Save them :
	nfw_update_option( 'nfw_options', $nfw_options);

	// Make sure the garbage collector cron job is scheduled:
	if (! wp_next_scheduled( 'nfwgccron' ) ) {
		wp_schedule_event( time() + 60, 'hourly', 'nfwgccron' );
	}

}
/* ------------------------------------------------------------------ */

function nf_sub_options_import() {

	// Import NF configuration from file :

	$data = file_get_contents($_FILES['nf_imp']['tmp_name']);
	$err_msg = __('Uploaded file is either corrupted or its format is not supported (#%s)', 'ninjafirewall');
	if (! $data) {
		return sprintf($err_msg, 1);
	}
	@list ($options, $rules, $bf) = @explode("\n:-:\n", $data . "\n:-:\n");
	if (! $options || ! $rules) {
		return sprintf($err_msg, 2);
	}
	$nfw_options = unserialize($options);
	$nfw_rules = unserialize($rules);
	if (! empty($bf) ) {
		$bf_conf = unserialize($bf);
	}

	if ( empty($nfw_options['engine_version']) ) {
		return sprintf($err_msg, 3);
	}
	if ( $nfw_options['engine_version'] != NFW_ENGINE_VERSION  ) {
		return __('The imported file is not compatible with that version of NinjaFirewall', 'ninjafirewall');
	}

	// We cannot import WP+ config :
	if ( isset($nfw_options['shmop']) ) {
		return sprintf($err_msg, 4);
	}

	if ( empty($nfw_rules[1]) ) {
		return sprintf($err_msg, 5);
	}

	// Fix paths and directories :
	$nfw_options['logo'] = plugins_url() . '/ninjafirewall/images/ninjafirewall_75.png';
	$nfw_options['wp_dir'] = '/wp-admin/(?:css|images|includes|js)/|' .
									 '/wp-includes/(?:(?:css|images|js(?!/tinymce/wp-tinymce\.php)|theme-compat)/|[^/]+\.php)|' .
									 '/'. basename(WP_CONTENT_DIR) .'/uploads/|/cache/';
	// $nfw_options['alert_email'] = get_option('admin_email');

	// Anti-Malware: if the path doest not exist on this server,
	// set it to ABSPATH:
	if (! is_dir( $nfw_options['malware_dir'] ) ) {
		$nfw_options['malware_dir'] = rtrim( ABSPATH, '/\\ ' );
	}

	// We don't import the File Check 'snapshot directory' path:
	$nfw_options['snapdir'] = '';
	// We delete any File Check cron jobs :
	if ( wp_next_scheduled('nfscanevent') ) {
		wp_clear_scheduled_hook('nfscanevent');
	}

	// Re-enable auto updates, if needed :
	if ( wp_next_scheduled('nfsecupdates') ) {
		// Clear old one :
		wp_clear_scheduled_hook('nfsecupdates');
	}
	if (! empty($nfw_options['enable_updates']) ) {
		if ($nfw_options['sched_updates'] == 1) {
			$schedtype = 'hourly';
		} elseif ($nfw_options['sched_updates'] == 2) {
			$schedtype = 'twicedaily';
		} else {
			$schedtype = 'daily';
		}
		wp_schedule_event( time() + 15, $schedtype, 'nfsecupdates');
	}
	// Re-enable daily report, if needed :
	if ( wp_next_scheduled('nfdailyreport') ) {
		// Clear old one :
		wp_clear_scheduled_hook('nfdailyreport');
	}
	if (! empty($nfw_options['a_52']) ) {
		nfw_get_blogtimezone();
		wp_schedule_event( strtotime( date('Y-m-d 00:00:05', strtotime("+1 day")) ), 'daily', 'nfdailyreport');
	}

	// Re-enable the garbage collector, if needed:
	if ( wp_next_scheduled('nfwgccron') ) {
		// Clear old one:
		wp_clear_scheduled_hook('nfwgccron');
	}
	wp_schedule_event( time() + 60, 'hourly', 'nfwgccron' );

	// Check compatibility before importing HSTS headers configration
	// or unset the option :
	if (! function_exists('header_register_callback') || ! function_exists('headers_list') || ! function_exists('header_remove') ) {
		if ( isset($nfw_options['response_headers']) ) {
			unset($nfw_options['response_headers']);
		}
	}

	// If brute force protection is enabled, we need to create a new config file :
	$nfwbfd_log = NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php';
	if (! empty($bf_conf) ) {
		$fh = fopen($nfwbfd_log, 'w');
		fwrite($fh, $bf_conf);
		fclose($fh);
	} else {
	// ...or delete the current one, if any :
		if ( file_exists($nfwbfd_log) ) {
			unlink($nfwbfd_log);
		}
	}
	// Save options :
	nfw_update_option( 'nfw_options', $nfw_options);

	// Add the correct DOCUMENT_ROOT :
	if ( strlen( $_SERVER['DOCUMENT_ROOT'] ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['cha'][1]['wha'] = str_replace( '/', '/[./]*', $_SERVER['DOCUMENT_ROOT'] );
	} elseif ( strlen( getenv( 'DOCUMENT_ROOT' ) ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['cha'][1]['wha'] = str_replace( '/', '/[./]*', getenv( 'DOCUMENT_ROOT' ) );
	} else {
		$nfw_rules[NFW_DOC_ROOT]['ena']  = 0;
	}
	// Save rules :
	nfw_update_option( 'nfw_rules', $nfw_rules);

	// Alert the admin :
	nf_sub_options_alert(3);

	return;
}

/* ------------------------------------------------------------------ */

function nf_sub_options_alert( $what ) {

	$nfw_options = nfw_get_option( 'nfw_options' );

	if ( ( is_multisite() ) && ( $nfw_options['alert_sa_only'] == 2 ) ) {
		$recipient = get_option('admin_email');
	} else {
		$recipient = $nfw_options['alert_email'];
	}

	global $current_user;
	$current_user = wp_get_current_user();

	// Get timezone :
	nfw_get_blogtimezone();

	$subject = __('[NinjaFirewall] Alert: Firewall is disabled', 'ninjafirewall');
	if ( is_multisite() ) {
		$url = __('-Blog :', 'ninjafirewall') .' '. network_home_url('/') . "\n\n";
	} else {
		$url = __('-Blog :', 'ninjafirewall') .' '. home_url('/') . "\n\n";
	}
	// Disabled ?
	if ($what == 1) {
		$message = __('Someone disabled NinjaFirewall from your WordPress admin dashboard:', 'ninjafirewall') . "\n\n";
	// Debugging mode :
	} elseif ($what == 2) {
		$message = __('NinjaFirewall is disabled because someone enabled debugging mode from your WordPress admin dashboard:', 'ninjafirewall') . "\n\n";
	// Imported configuration ?
	} elseif ($what == 3) {
		$subject = __('[NinjaFirewall] Alert: Firewall override settings', 'ninjafirewall');
		$message = __('Someone imported a new configuration which overrode the firewall settings:', 'ninjafirewall') . "\n\n";
	} else {
		// Should never reach this line!
		return;
	}

	$message .= __('-User :', 'ninjafirewall') .' '. $current_user->user_login . ' (' . $current_user->roles[0] . ")\n" .
		__('-IP   :', 'ninjafirewall') .' '. NFW_REMOTE_ADDR . "\n" .
		__('-Date :', 'ninjafirewall') .' '. ucfirst( date_i18n('F j, Y @ H:i:s O') ) ."\n" .
		$url .
		'NinjaFirewall (WP Edition) - http://ninjafirewall.com/' . "\n" .
		__('Support forum:', 'ninjafirewall') . ' http://wordpress.org/support/plugin/ninjafirewall' . "\n";
	wp_mail( $recipient, $subject, $message );
}

/* ------------------------------------------------------------------ */
// EOF
