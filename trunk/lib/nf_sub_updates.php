<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
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

// If your server can't remotely connect to a SSL port, add this
// to your wp-config.php script: define('NFW_DONT_USE_SSL', 1);
if ( defined( 'NFW_DONT_USE_SSL' ) ) {
	$proto = "http";
} else {
	$proto = "https";
}
$update_log = NFW_LOG_DIR . '/nfwlog/updates.php';

// Check which rules should be returned:
if ( defined('NFW_WPWAF') ) {
	$rules_type = 0;
} else {
	$rules_type = 1;
}

$nfw_options = nfw_get_option('nfw_options');

if ( empty( $nfw_options['sched_updates'] ) || empty( $nfw_options['enable_updates'] ) ) {
	$sched_updates = 0;
} else {
	$sched_updates = (int) $nfw_options['sched_updates'];
}

if ( defined( 'NFUPDATESDO' ) && NFUPDATESDO == 2 ) {
	// Installation:
	$update_url = array(
		$proto . '://plugins.svn.wordpress.org/ninjafirewall/trunk/updates/',
		'version3.txt',
		'rules3.txt'
	);
} else {
	// Scheduled updates:
	$caching_id = sha1( home_url() );
	$update_url = array(
		$proto . '://updates.nintechnet.com/index.php',
		"?version=3&cid={$caching_id}&edn=wp&rt={$rules_type}&su={$sched_updates}",
		"?rules=3&cid={$caching_id}&edn=wp&rt={$rules_type}&su={$sched_updates}"
	);
}

// Scheduled updates or NinjaFirewall installation:
if (defined('NFUPDATESDO') ) {
	define('NFW_RULES', nf_sub_do_updates($update_url, $update_log, NFUPDATESDO));
	return;
}

// Block immediately if user is not allowed :
nf_not_allowed( 'block', __LINE__ );

echo '<div class="wrap">
	<div style="width:33px;height:33px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h1>' . __('Updates', 'ninjafirewall') . '</h1>';

// We stop and warn the user if the firewall is disabled:
if (! defined('NF_DISABLED') ) {
	is_nfw_enabled();
}
if (NF_DISABLED) {
	echo '<div class="error notice is-dismissible"><p>' . __('Security rules cannot be updated when NinjaFirewall is disabled.', 'ninjafirewall') . '</p></div></div>';
	return;
}

//Saved options ?
if (! empty($_POST['nfw_act']) ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'updates_save') ) {
		wp_nonce_ays('updates_save');
	}
	// Check updates now :
	if  ($_POST['nfw_act'] == 3) {
		if ( $res = nf_sub_do_updates($update_url, $update_log, 0) ) {
			echo '<div class="updated notice is-dismissible"><p>' . __('Security rules have been updated.', 'ninjafirewall') . '</p></div>';
		} else {
			echo '<div class="updated notice is-dismissible"><p>' . __('No update available.', 'ninjafirewall') . '</p></div>';
		}
		// Enable flag to display log :
		$tmp_showlog = 1;
	} else {
		if ($_POST['nfw_act'] == 1) {
			nf_sub_updates_save();
		} elseif ($_POST['nfw_act'] == 2) {
			nf_sub_updates_clearlog($update_log);
		}
		echo '<div class="updated notice is-dismissible"><p>' . __('Your changes have been saved.', 'ninjafirewall') . '</p></div>';
	}
	// Reload options:
	$nfw_options = nfw_get_option('nfw_options');
}

if ( empty($nfw_options['enable_updates']) ) {
	$enable_updates = 0;
} else {
	$enable_updates = 1;
}
if ( empty($nfw_options['sched_updates']) || ! preg_match('/^[2-3]$/', $nfw_options['sched_updates']) ) {
	$sched_updates = 1;
} else {
	$sched_updates = $nfw_options['sched_updates'];
}
if ( empty($nfw_options['notify_updates']) && isset($nfw_options['notify_updates']) ) {
	$notify_updates = 0;
} else {
	// Defaut if not set yet:
	$notify_updates = 1;
}
?>

<script type="text/javascript">
function toogle_table(off) {
	if ( off == 1 ) {
		jQuery("#upd_table").slideDown();
	} else if ( off == 2 ) {
		jQuery("#upd_table").slideUp();
	}
	return;
}
</script>
<br />
<form method="post" name="fupdates">
	<?php wp_nonce_field('updates_save', 'nfwnonce', 0); ?>
	<table class="form-table">
		<tr style="background-color:#F9F9F9;border: solid 1px #DFDFDF;">
			<th scope="row"><?php _e('Automatically update NinjaFirewall security rules', 'ninjafirewall') ?></th>
			<td align="left">
			<label><input type="radio" name="enable_updates" value="1"<?php checked($enable_updates, 1) ?> onclick="toogle_table(1);">&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
			</td>
			<td align="left">
			<label><input type="radio" name="enable_updates" value="0"<?php checked($enable_updates, 0) ?> onclick="toogle_table(2);">&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
			</td>
		</tr>
	</table>

	<?php
	// If WP cron is disabled, we simply warn the user :
	if ( defined('DISABLE_WP_CRON') ) {
	?>
		<p><img src="<?php echo plugins_url() ?>/ninjafirewall/images/icon_warn_16.png" height="16" border="0" width="16">&nbsp;<span class="description"><?php printf( __('It seems that %s is enabled. Ensure you have another way to run WP-Cron, otherwise NinjaFirewall automatic updates will not work.', 'ninjafirewall'), '<code>DISABLE_WP_CRON</code>' ) ?></span></p>
	<?php
	}
	?>
	<div id="upd_table"<?php echo $enable_updates == 1 ? '' : ' style="display:none"' ?>>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Check for updates', 'ninjafirewall') ?></th>
					<td align="left">
						<p><label><input type="radio" name="sched_updates" value="1"<?php checked($sched_updates, 1) ?> /><?php _e('Hourly', 'ninjafirewall') ?></label></p>
						<p><label><input type="radio" name="sched_updates" value="2"<?php checked($sched_updates, 2) ?> /><?php _e('Twicedaily', 'ninjafirewall') ?></label></p>
						<p><label><input type="radio" name="sched_updates" value="3"<?php checked($sched_updates, 3) ?> /><?php _e('Daily', 'ninjafirewall') ?></label></p>
						<?php
						if ( $nextcron = wp_next_scheduled('nfsecupdates') ) {
							$sched = new DateTime( date('M d, Y H:i:s', $nextcron) );
							$now = new DateTime( date('M d, Y H:i:s', time() ) );
							$diff = $now->diff($sched);
						?>
							<p><span class="description"><?php printf( __('Next scheduled update will start in approximately %s day, %s hour(s), %s minute(s) and %s seconds.', 'ninjafirewall'), $diff->format('%a') % 7, $diff->format('%h'), $diff->format('%i'), $diff->format('%s') ) ?></span></p>
						<?php
							// Ensure that the scheduled scan time is in the future,
							// not in the past, otherwise send a warning because wp-cron
							// is obviously not working as expected :
							if ( $nextcron < time() ) {
							?>
								<p><img src="<?php echo plugins_url() ?>/ninjafirewall/images/icon_warn_16.png" height="16" border="0" width="16">&nbsp;<span class="description"><?php _e('The next scheduled date is in the past! WordPress wp-cron may not be working or may have been disabled.', 'ninjafirewall'); ?></span>
							<?php
							}
						}
						?>
					</td>
				</tr>
			<tr>
				<th scope="row"><?php _e('Notification', 'ninjafirewall') ?></th>
				<td align="left">
					<p><label><input type="checkbox" name="notify_updates" value="1"<?php checked($notify_updates, 1) ?> /><?php _e('Send me a report by email when security rules have been updated.', 'ninjafirewall') ?></label></p>
					<span class="description"><?php _e('Reports will be sent to the contact email address defined in the Event Notifications menu.', 'ninjafirewall') ?></span>
				</td>
			</tr>
		</table>
	</div>

	<input type="hidden" name="nfw_act" value="1" />
	<p><input type="submit" class="button-primary" value="<?php _e('Save Updates Options', 'ninjafirewall') ?>" />&nbsp;&nbsp;<input type="submit" class="button-secondary" onClick="document.fupdates.nfw_act.value=3" value="<?php _e('Check For Updates Now!', 'ninjafirewall') ?>" /></p>
	</form>

	<?php
	if (! empty($nfw_options['enable_updates']) || ! empty($tmp_showlog) ) {
		$log_data = array();
		if ( file_exists($update_log) ) {
			$log_data = file($update_log);
		} else {
			$log_data[] = __('The updates log is currently empty.', 'ninjafirewall');
		}
	?>
	<br />
	<form method="post">
		<?php wp_nonce_field('updates_save', 'nfwnonce', 0); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Updates Log', 'ninjafirewall') ?></th>
				<td align="left">
					<textarea class="small-text code" style="width:100%;height:150px;" wrap="off" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"><?php
						$reversed = array_reverse($log_data);
						foreach ($reversed as $key) {
							echo htmlentities($key);
						}?></textarea>
						<p>
						<?php
						echo '<input type="submit" name="clear_updates_log" value="' . __('Delete Log', 'ninjafirewall') . '" class="button-secondary"';
						if (file_exists($update_log) ) {
							echo ' />';
						} else {
							echo ' disabled="disabled" />';
						}
						echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="description">' . __('Log is flushed automatically.', 'ninjafirewall') . '</span>';
						?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="nfw_act" value="2" />
	</form>
	<?php
	}
	?>
</div>
<?php

/* ------------------------------------------------------------------ */

function nf_sub_updates_save() {

	$nfw_options = nfw_get_option('nfw_options');

	if ( empty($_POST['sched_updates']) || ! preg_match('/^[2-3]$/', $_POST['sched_updates']) ) {
		$nfw_options['sched_updates'] = 1;
		$schedtype = 'hourly';
	} else {
		$nfw_options['sched_updates'] = $_POST['sched_updates'];
		if ($nfw_options['sched_updates'] == 2) {
			$schedtype = 'twicedaily';
		} else {
			$schedtype = 'daily';
		}
	}

	if ( empty($_POST['enable_updates']) ) {
		$nfw_options['enable_updates'] = 0;
		// Clear scheduled scan (if any) and its options :
		if ( wp_next_scheduled('nfsecupdates') ) {
			wp_clear_scheduled_hook('nfsecupdates');
		}
	} else {
		$nfw_options['enable_updates'] = 1;
		// Create a new scheduled scan :
		if ( wp_next_scheduled('nfsecupdates') ) {
			wp_clear_scheduled_hook('nfsecupdates');
		}
		// Start next cron in 15 seconds:
		wp_schedule_event( time() + 15, $schedtype, 'nfsecupdates');
	}

	if ( empty($_POST['notify_updates']) ) {
		$nfw_options['notify_updates'] = 0;
	} else {
		$nfw_options['notify_updates'] = 1;
	}

	nfw_update_option('nfw_options', $nfw_options);

}

/* ------------------------------------------------------------------ */

function nf_sub_updates_clearlog($update_log) {

	if (file_exists($update_log) ) {
		unlink($update_log);
	}

}

/* ------------------------------------------------------------------ */

function nf_sub_do_updates($update_url, $update_log, $NFUPDATESDO = 1) {

	// Are we installing NinjaFirewall ?
	if ( $NFUPDATESDO == 2 ) {
		 return nf_sub_updates_download($update_url, $update_log, 0);
	}

	$nfw_options = nfw_get_option('nfw_options');

	// Don't do anything if NinjaFirewall is disabled :
	if ( empty( $nfw_options['enabled'] ) ) { return 0; }

	if (! $new_rules_version = nf_sub_updates_getversion($update_url, $nfw_options['rules_version'], $update_log) ) {
		// Error or nothing to update :
		return;
	}

	// There is a new version, let's fetch it:
	if (! $data = nf_sub_updates_download($update_url, $update_log, $new_rules_version) ) {
		// Error :
		return;
	}

	// Make sure we received the right format:
	if (! preg_match('/^a:\d+:{i:\d/', $data ) ) {
		nf_sub_updates_log(
			$update_log,
			__('Error: Wrong rules format.', 'ninjafirewall')
		);
		return 0;
	}

	// Unserialize the new rules :
	if (! $new_rules = @unserialize($data) ) {
		nf_sub_updates_log(
			$update_log,
			__('Error: Unable to unserialize the new rules.', 'ninjafirewall')
		);
		return 0;
	}
	// One more check...:
	if (! is_array($new_rules) || empty($new_rules[1]['cha'][1]['whe']) ) {
		nf_sub_updates_log(
			$update_log,
			__('Error: Unserialized rules seem corrupted.', 'ninjafirewall')
		);
		return 0;
	}

	$nfw_rules = nfw_get_option('nfw_rules');

	foreach ( $new_rules as $new_key => $new_value ) {
		foreach ( $new_value as $key => $value ) {
			// If that rule exists already, we keep its 'ena' flag value
			// as it may have been changed by the user with the rules editor:
			// v3.x:
			if ( ( isset( $nfw_rules[$new_key]['ena'] ) ) && ( $key == 'ena' ) ) {
				$new_rules[$new_key]['ena'] = $nfw_rules[$new_key]['ena'];
			}
			// v1.x:
			if ( ( isset( $nfw_rules[$new_key]['on'] ) ) && ( $key == 'ena' ) ) {
				$new_rules[$new_key]['ena'] = $nfw_rules[$new_key]['on'];
			}
		}
	}
	// v1.x:
	if ( isset( $nfw_rules[NFW_DOC_ROOT]['what'] ) ) {
		$new_rules[NFW_DOC_ROOT]['cha'][1]['wha']= str_replace( '/', '/[./]*', $nfw_rules[NFW_DOC_ROOT]['what'] );
		$new_rules[NFW_DOC_ROOT]['ena']	= $nfw_rules[NFW_DOC_ROOT]['on'];
	// v3.x:
	} else {
		$new_rules[NFW_DOC_ROOT]['cha'][1]['wha']= $nfw_rules[NFW_DOC_ROOT]['cha'][1]['wha'];
		$new_rules[NFW_DOC_ROOT]['ena']	= $nfw_rules[NFW_DOC_ROOT]['ena'];
	}

	// Update rules in the DB :
	nfw_update_option('nfw_rules', $new_rules);

	// Update rules version in the options table :
	$nfw_options['rules_version'] = $new_rules_version;
	nfw_update_option('nfw_options', $nfw_options);

	nf_sub_updates_log(
		$update_log,
		sprintf( __('Security rules updated to version %s.', 'ninjafirewall'),
		preg_replace('/(\d{4})(\d\d)(\d\d)/', '$1-$2-$3', $new_rules_version) )
	);

	// Email the admin ?
	if (! empty($nfw_options['notify_updates']) ) {
		nf_sub_updates_notification($new_rules_version);
	}
	return 1;
}

/* ------------------------------------------------------------------ */

function nf_sub_updates_getversion($update_url, $rules_version, $update_log) {

	global $wp_version;
	$res = wp_remote_get(
		$update_url[0] . $update_url[1],
		array(
			'timeout' => 20,
			'httpversion' => '1.1' ,
			'user-agent' => 'Mozilla/5.0 (compatible; NinjaFirewall/'.
									NFW_ENGINE_VERSION .'; WordPress/'. $wp_version . ')',
			'sslverify' => true
		)
	);
	if (! is_wp_error($res) ) {
		if ( $res['response']['code'] == 200 ) {
			// Get the rules version :
			$new_version =  explode('|', rtrim($res['body']), 2);

			// Ensure that the rules are compatible :
			if ( $new_version[0] != 3 ) {
				// This version of NinjaFirewall may be too old :
				nf_sub_updates_log(
					$update_log,
					__('Error: Your version of NinjaFirewall is too old and is not compatible with those rules. Please upgrade it.', 'ninjafirewall')
				);
				return 0;
			}

			if (! preg_match('/^\d{8}\.\d+$/', $new_version[1]) ) {
				// Not what we were expecting:
				nf_sub_updates_log(
					$update_log,
					__('Error: Unable to retrieve the new rules version.', 'ninjafirewall')
				);
				return 0;
			}
			// Compare versions:
			if ( version_compare($rules_version, $new_version[1], '<') ) {
				return $new_version[1];

			} else {
				nf_sub_updates_log(
				$update_log,
				__('No update available.', 'ninjafirewall')
				);
			}
		// Not a 200 OK ret code :
		} else {
			nf_sub_updates_log(
				$update_log,
				sprintf( __('Error: Server returned a %s HTTP error code (#1).', 'ninjafirewall'), htmlspecialchars($res['response']['code']))
			);
		}
	// Connection error :
	} else {
		nf_sub_updates_log(
			$update_log,
			__('Error: Unable to connect to the remote server', 'ninjafirewall') . htmlspecialchars(" ({$res->get_error_message()})")
		);
	}
	return 0;
}

/* ------------------------------------------------------------------ */

function nf_sub_updates_download($update_url, $update_log, $new_rules_version) {

	global $wp_version;
	$res = wp_remote_get(
		$update_url[0] . $update_url[2],
		array(
			'timeout' => 20,
			'httpversion' => '1.1' ,
			'user-agent' => 'Mozilla/5.0 (compatible; NinjaFirewall/'.
									NFW_ENGINE_VERSION .'; WordPress/'. $wp_version . ')',
			'sslverify' => true
		)
	);
	if (! is_wp_error($res) ) {
		if ( $res['response']['code'] == 200 ) {
			$data = explode('|', rtrim($res['body']), 2);

			// Rules version should match the one we just fetched
			// unless we are intalling NinjaFirewall ($new_rules_version==0) :
			if ( $new_rules_version & $new_rules_version != $data[0]) {
				nf_sub_updates_log(
					$update_log,
					sprintf( __('Error: The new rules versions do not match (%s != %s).', 'nfwplus'), $new_rules_version, htmlspecialchars($data[0]))
				);
				return 0;
			}
			// Save new rules version for install/upgrade:
			define('NFW_NEWRULES_VERSION', $data[0]);
			// Return the rules:
			return $data[1];

		// Not a 200 OK ret code :
		} else {
			nf_sub_updates_log(
				$update_log,
				sprintf( __('Error: Server returned a %s HTTP error code (#2).', 'ninjafirewall'), htmlspecialchars($res['response']['code']))
			);
		}
	// Connection error :
	} else {
		nf_sub_updates_log(
			$update_log,
			__('Error: Unable to connect to the remote server', 'ninjafirewall') . htmlspecialchars(" ({$res->get_error_message()})")
		);
	}
	return 0;
}

/* ------------------------------------------------------------------ */

function nf_sub_updates_log($update_log, $msg) {

	// If the log is bigger than 50Kb (+/- one month old), we flush it :
	if ( file_exists($update_log) ) {
		$log_stat = stat($update_log);
		if ( $log_stat['size'] > 51200 ) {
			@unlink($update_log);
		}
	}
	@file_put_contents($update_log, date_i18n('[d/M/y:H:i:s O]') . " $msg\n", FILE_APPEND | LOCK_EX);

}

/* ------------------------------------------------------------------ */

function nf_sub_updates_notification($new_rules_version) {

	$nfw_options = nfw_get_option('nfw_options');

	if ( ( is_multisite() ) && ( $nfw_options['alert_sa_only'] == 2 ) ) {
		$recipient = get_option('admin_email');
	} else {
		$recipient = $nfw_options['alert_email'];
	}

	$subject = __('[NinjaFirewall] Security rules update', 'ninjafirewall');
	$msg = __('NinjaFirewall security rules have been updated:', 'ninjafirewall') . "\n\n";
	if ( is_multisite() ) {
		$msg .=__('Blog:', 'ninjafirewall') .' '. network_home_url('/') . "\n";
	} else {
		$msg .=__('Blog:', 'ninjafirewall') .' '. home_url('/') . "\n";
	}
	$msg .=__('Rules version:', 'ninjafirewall') .' '. preg_replace('/(\d{4})(\d\d)(\d\d)/', '$1-$2-$3', $new_rules_version) . "\n";
	$msg .= sprintf( __('Date: %s', 'ninjafirewall'), ucfirst(date_i18n('M d, Y @ H:i:s O')) ) . "\n\n" .
			__('This notification can be turned off from NinjaFirewall "Updates" page.', 'ninjafirewall') ."\n\n" .
			'NinjaFirewall (WP Edition) - http://ninjafirewall.com/' . "\n" .
			__('Support forum:', 'ninjafirewall') .' http://wordpress.org/support/plugin/ninjafirewall' . "\n";
	wp_mail( $recipient, $subject, $msg );

}

/* ------------------------------------------------------------------ */
// EOF
