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

// Daily report cronjob?
if ( defined('NFREPORTDO') ) {
	nf_daily_report();
	return;
}

// Block immediately if user is not allowed :
nf_not_allowed( 'block', __LINE__ );

$nfw_options = nfw_get_option( 'nfw_options' );

echo '<script>
function ac_radio_toogle(on_off, rbutton) {
	var what = "nfw_options["+rbutton+"]";
	if (on_off) {
		document.nfwalerts.elements[what].disabled = false;
		document.nfwalerts.elements[what].focus();
	} else {
		document.nfwalerts.elements[what].disabled = true;
	}
}
</script>
<div class="wrap">
	<div style="width:33px;height:33px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h1>' . __('Event Notifications', 'ninjafirewall') . '</h1>';

// Saved ?
if ( isset( $_POST['nfw_options']) ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'events_save') ) {
		wp_nonce_ays('events_save');
	}
	nf_sub_event_save();
	echo '<div class="updated notice is-dismissible"><p>' . __('Your changes have been saved.', 'ninjafirewall') . '</p></div>';
	$nfw_options = nfw_get_option( 'nfw_options' );
}

if (! isset( $nfw_options['a_0'] ) ) {
	$nfw_options['a_0'] = 1;
}
?><br />
	<form method="post" name="nfwalerts">
	<?php wp_nonce_field('events_save', 'nfwnonce', 0); ?>
	<h3><?php _e('WordPress admin dashboard', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Send me an alert whenever', 'ninjafirewall') ?></th>
			<td align="left">
			<p><label><input type="radio" name="nfw_options[a_0]" value="1"<?php checked( $nfw_options['a_0'], 1) ?>>&nbsp;<?php _e('An administrator logs in (default)', 'ninjafirewall') ?></label></p>
			<p><label><input type="radio" name="nfw_options[a_0]" value="2"<?php checked( $nfw_options['a_0'], 2) ?>>&nbsp;<?php _e('Someone - user, admin, editor, etc - logs in', 'ninjafirewall') ?></label></p>
			<p><label><input type="radio" name="nfw_options[a_0]" value="0"<?php checked( $nfw_options['a_0'], 0) ?>>&nbsp;<?php _e('No, thanks (not recommended)', 'ninjafirewall') ?></label></p>
			</td>
		</tr>
	</table>

	<br />

	<h3><?php _e('Plugins', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Send me an alert whenever someone', 'ninjafirewall') ?></th>
			<td align="left">
			<p><label><input type="checkbox" name="nfw_options[a_11]" value="1"<?php checked( $nfw_options['a_11'], 1) ?>>&nbsp;<?php _e('Uploads a plugin (default)', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[a_12]" value="1"<?php checked( $nfw_options['a_12'], 1) ?>>&nbsp;<?php _e('Installs a plugin (default)', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[a_13]" value="1"<?php checked( $nfw_options['a_13'], 1) ?>>&nbsp;<?php _e('Activates a plugin', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[a_14]" value="1"<?php checked( $nfw_options['a_14'], 1) ?>>&nbsp;<?php _e('Updates a plugin', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[a_15]" value="1"<?php checked( $nfw_options['a_15'], 1) ?>>&nbsp;<?php _e('Deactivates a plugin (default)', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[a_16]" value="1"<?php checked( $nfw_options['a_16'], 1) ?>>&nbsp;<?php _e('Deletes a plugin', 'ninjafirewall') ?></label></p>
			</td>
		</tr>
	</table>

	<br />

	<h3><?php _e('Themes', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Send me an alert whenever someone', 'ninjafirewall') ?></th>
			<td align="left">
			<p><label><input type="checkbox" name="nfw_options[a_21]" value="1"<?php checked( $nfw_options['a_21'], 1) ?>>&nbsp;<?php _e('Uploads a theme (default)', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[a_22]" value="1"<?php checked( $nfw_options['a_22'], 1) ?>>&nbsp;<?php _e('Installs a theme (default)', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[a_23]" value="1"<?php checked( $nfw_options['a_23'], 1) ?>>&nbsp;<?php _e('Activates a theme', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[a_24]" value="1"<?php checked( $nfw_options['a_24'], 1) ?>>&nbsp;<?php _e('Deletes a theme', 'ninjafirewall') ?></label></p>
			</td>
		</tr>
	</table>

	<br />

	<h3><?php _e('Core', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Send me an alert whenever someone', 'ninjafirewall') ?></th>
			<td align="left">
			<p><label><input type="checkbox" name="nfw_options[a_31]" value="1"<?php checked( $nfw_options['a_31'], 1) ?>>&nbsp;<?php _e('Updates WordPress (default)', 'ninjafirewall') ?></label></p>
			</td>
		</tr>
	</table>

	<br />

	<?php
	if (! isset( $nfw_options['a_51']) ) {
		$nfw_options['a_51'] = 1;
	}
	if (! isset( $nfw_options['a_52']) ) {
		$nfw_options['a_52'] = 1;
	}
	if (! isset( $nfw_options['a_53']) ) {
		$nfw_options['a_53'] = 1;
	}
	?>
	<h3><?php _e('Administrator account', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Send me an alert whenever', 'ninjafirewall') ?></th>
			<td align="left">
				<p><label><input type="checkbox" name="nfw_options[a_51]" value="1"<?php checked( $nfw_options['a_51'], 1) ?>>&nbsp;<?php _e('An administrator account is created, modified or deleted in the database (default)', 'ninjafirewall') ?></label></p>
				<p><label><input type="checkbox" name="nfw_options[a_53]" value="1"<?php checked( $nfw_options['a_53'], 1) ?>>&nbsp;<?php _e('A user attempts to gain administrative privileges (default)', 'ninjafirewall') ?></label></p>
			</td>
		</tr>
	</table>

	<br />

	<h3><?php _e('Daily report', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Send me a daily activity report', 'ninjafirewall') ?></th>
			<td align="left">
			<p><label><input type="checkbox" name="nfw_options[a_52]" value="1"<?php checked( $nfw_options['a_52'], 1) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label></p>
			</td>
		</tr>
	</table>

	<br />

	<h3><?php _e('Log', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Write all events to the firewall log', 'ninjafirewall') ?></th>
			<td align="left">
			<p><label><input type="checkbox" name="nfw_options[a_41]" value="1"<?php checked( $nfw_options['a_41'], 1) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label></p>
			</td>
		</tr>
	</table>

	<br />

<?php
if (! is_multisite() ) {
?>
	<h3><?php _e('Contact email', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr style="background-color:#F9F9F9;border: solid 1px #DFDFDF;">
			<th scope="row"><?php _e('Alerts should be sent to', 'ninjafirewall') ?></th>
			<td align="left">
			<input class="regular-text" type="text" name="nfw_options[alert_email]" size="45" maxlength="250" value="<?php
			if ( empty( $nfw_options['alert_email'])) {
				echo htmlspecialchars( get_option('admin_email') );
			} else {
				echo htmlspecialchars( $nfw_options['alert_email'] );
			}
			?>">
			<br /><span class="description"><?php _e('Multiple recipients must be comma-separated (e.g., <code>joe@example.org,alice@example.org</code>).', 'ninjafirewall') ?></span>
			<input type="hidden" name="nfw_options[alert_sa_only]" value="2">
			</td>
		</tr>
	</table>

<?php
} else {
	// Select which admin(s) will recevied alerts in multi-site mode :
	if (! isset( $nfw_options['alert_sa_only'] ) ) {
		$nfw_options['alert_sa_only'] = 1;
	}
	if ($nfw_options['alert_sa_only'] == 3) {
		$tmp_email = htmlspecialchars( $nfw_options['alert_email'] );
	} else {
		$tmp_email = '';
	}
?>
	<h3><?php _e('Contact email', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr style="background-color:#F9F9F9;border: solid 1px #DFDFDF;">
			<th scope="row"><?php _e('Alerts should be sent to', 'ninjafirewall') ?></th>
			<td align="left">
			<p><label><input type="radio" name="nfw_options[alert_sa_only]" value="1"<?php checked( $nfw_options['alert_sa_only'], 1 ) ?> onclick="ac_radio_toogle(0,'alert_multirec');" />&nbsp;<?php _e('Only to me, the Super Admin', 'ninjafirewall') ?>, <?php echo '<code>'. htmlspecialchars(get_option('admin_email')) . '</code>'; ?> (<?php _e('default', 'ninjafirewall') ?>)</label></p>
			<p><label><input type="radio" name="nfw_options[alert_sa_only]" value="2"<?php checked( $nfw_options['alert_sa_only'], 2) ?> onclick="ac_radio_toogle(0,'alert_multirec');" />&nbsp;<?php _e('To the administrator of the site where originated the alert', 'ninjafirewall') ?></label></p>
			<p><label><input type="radio" name="nfw_options[alert_sa_only]" value="3"<?php checked( $nfw_options['alert_sa_only'], 3) ?> onclick="ac_radio_toogle(1,'alert_multirec');" />&nbsp;<?php _e('Other(s):', 'ninjafirewall') ?> </label><input class="regular-text" type="text" name="nfw_options[alert_multirec]" size="45" maxlength="250" value="<?php echo $tmp_email ?>" <?php disabled($tmp_email, '') ?>></p>
			<span class="description"><?php _e('Multiple recipients must be comma-separated (e.g., <code>joe@example.org,alice@example.org</code>).', 'ninjafirewall') ?></span>
			<input type="hidden" name="nfw_options[alert_email]" value="<?php echo htmlspecialchars(get_option('admin_email')); ?>">
			</td>
		</tr>
	</table>
<?php
}
?>

	<br />
	<br />
	<input class="button-primary" type="submit" name="Save" value="<?php _e('Save Event Notifications', 'ninjafirewall') ?>" />

	</form>

</div>
<?php

/* ------------------------------------------------------------------ */

function nf_sub_event_save() {

	// Save Event Notifications :

	// Block immediately if user is not allowed :
	nf_not_allowed( 'block', __LINE__ );

	$nfw_options = nfw_get_option( 'nfw_options' );

	if (! preg_match('/^[012]$/', $_POST['nfw_options']['a_0']) ) {
		$nfw_options['a_0'] = 1;
	} else {
		$nfw_options['a_0'] = $_POST['nfw_options']['a_0'];
	}

	if (! preg_match('/^[123]$/', $_POST['nfw_options']['alert_sa_only']) ) {
		$nfw_options['alert_sa_only'] = 1;
	} else {
		$nfw_options['alert_sa_only'] = $_POST['nfw_options']['alert_sa_only'];
	}

	if ( empty( $_POST['nfw_options']['a_11']) ) {
		$nfw_options['a_11'] = 0;
	} else {
		$nfw_options['a_11'] = 1;
	}
	if ( empty( $_POST['nfw_options']['a_12']) ) {
		$nfw_options['a_12'] = 0;
	} else {
		$nfw_options['a_12'] = 1;
	}
	if ( empty( $_POST['nfw_options']['a_13']) ) {
		$nfw_options['a_13'] = 0;
	} else {
		$nfw_options['a_13'] = 1;
	}
	if ( empty( $_POST['nfw_options']['a_14']) ) {
		$nfw_options['a_14'] = 0;
	} else {
		$nfw_options['a_14'] = 1;
	}
	if ( empty( $_POST['nfw_options']['a_15']) ) {
		$nfw_options['a_15'] = 0;
	} else {
		$nfw_options['a_15'] = 1;
	}
	if ( empty( $_POST['nfw_options']['a_16']) ) {
		$nfw_options['a_16'] = 0;
	} else {
		$nfw_options['a_16'] = 1;
	}

	if ( empty( $_POST['nfw_options']['a_21']) ) {
		$nfw_options['a_21'] = 0;
	} else {
		$nfw_options['a_21'] = 1;
	}
	if ( empty( $_POST['nfw_options']['a_22']) ) {
		$nfw_options['a_22'] = 0;
	} else {
		$nfw_options['a_22'] = 1;
	}
	if ( empty( $_POST['nfw_options']['a_23']) ) {
		$nfw_options['a_23'] = 0;
	} else {
		$nfw_options['a_23'] = 1;
	}
	if ( empty( $_POST['nfw_options']['a_24']) ) {
		$nfw_options['a_24'] = 0;
	} else {
		$nfw_options['a_24'] = 1;
	}

	if ( empty( $_POST['nfw_options']['a_31']) ) {
		$nfw_options['a_31'] = 0;
	} else {
		$nfw_options['a_31'] = 1;
	}

	if ( empty( $_POST['nfw_options']['a_41']) ) {
		$nfw_options['a_41'] = 0;
	} else {
		$nfw_options['a_41'] = 1;
	}

	if ( empty( $_POST['nfw_options']['a_51']) ) {
		$nfw_options['a_51'] = 0;
	} else {
		$nfw_options['a_51'] = 1;
	}
	if ( empty( $_POST['nfw_options']['a_52']) ) {
		$nfw_options['a_52'] = 0;
		// Clear the daily report cronjob, if any:
		if ( wp_next_scheduled('nfdailyreport') ) {
			wp_clear_scheduled_hook('nfdailyreport');
		}
	} else {
		$nfw_options['a_52'] = 1;
		// Create the cronjob that will send the daily report:
		if (! wp_next_scheduled('nfdailyreport') ) {
			nfw_get_blogtimezone();
			wp_schedule_event( strtotime( date('Y-m-d 00:00:05', strtotime("+1 day")) ), 'daily', 'nfdailyreport');
		}
	}
	if ( empty( $_POST['nfw_options']['a_53']) ) {
		$nfw_options['a_53'] = 0;
	} else {
		$nfw_options['a_53'] = 1;
	}

	// Multiple recipients (WPMU only) ?
	if (! empty( $_POST['nfw_options']['alert_multirec']) ) {
		$_POST['nfw_options']['alert_email'] = $_POST['nfw_options']['alert_multirec'];
	}

	if (! empty( $_POST['nfw_options']['alert_email']) ) {
		$nfw_options['alert_email'] = '';
		$tmp_email = explode(',', $_POST['nfw_options']['alert_email'] );
		foreach ($tmp_email as $notif_email) {
			$nfw_options['alert_email'] .= sanitize_email($notif_email) . ', ';
		}
		$nfw_options['alert_email'] = rtrim($nfw_options['alert_email'], ', ' );
	}
	if ( empty( $nfw_options['alert_email'] ) ) {
		$nfw_options['alert_email'] = get_option('admin_email');
	}

	// Update options :
	nfw_update_option( 'nfw_options', $nfw_options );

}

/* ------------------------------------------------------------------ */

function nf_daily_report() {

	// Send a daily report to the admin(s):
	$nfw_options = nfw_get_option( 'nfw_options' );

	if ( ( is_multisite() ) && ( @$nfw_options['alert_sa_only'] == 2 ) ) {
		$recipient = get_option('admin_email');
	} else {
		$recipient = $nfw_options['alert_email'];
	}

	$logstats = array();
	$logstats = nf_daily_report_log();

	nf_daily_report_email($recipient, $logstats);

}
/* ------------------------------------------------------------------ */
function nf_daily_report_log() {

	nfw_get_blogtimezone();

	if (date('j') == 1) {
		$cur_month_log = date('Y-m', strtotime(date('Y-m')." -1 month"));
	} else {
		$cur_month_log = date('Y-m');
	}
	$previous_day = strtotime( date('Y-m-d 00:00:01', strtotime("-1 day")) );
	$log_file  = NFW_LOG_DIR . '/nfwlog/firewall_' . $cur_month_log;
	$logstats = array( 0 => 0, 1 => 0, 2 => 0, 3 => 0, 5 => 0);

	$glob = glob($log_file . "*.php");
	if ( is_array($glob)) {
		// Parse each log :
		foreach($glob as $file) {
			// Stat the file; if it's older than 24 hours,
			// we don't waste our time to parse it:
			$log_stat = stat($file);
			if ( $log_stat['mtime'] < $previous_day ) {
				continue;
			}

			$log_lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach ($log_lines as $line) {
				if ( preg_match( '/^\[(\d{10})\]\s+\[.+?\]\s+\[.+?\]\s+\[#\d{7}\]\s+\[\d+\]\s+\[([1235])\]\s+\[/', $line, $match) ) {
					// Fetch last 24 hours only :
					if ( $match[1] > $previous_day && $match[1] < $previous_day + 86400 ) {
						++$logstats[$match[2]];
						if ( strpos($line, 'Brute-force attack detected') !== FALSE ) {
							++$logstats[0];
						}
					}
				}
			}
		}

	}
	return $logstats;
}

/* ------------------------------------------------------------------ */

function nf_daily_report_email($recipient, $logstats) {

	$subject = __('[NinjaFirewall] Daily Activity Report', 'ninjafirewall');
	if ( is_multisite() ) {
		$url = network_home_url('/');
	} else {
		$url = home_url('/');
	}

	$message = "\n". sprintf( __('Daily activity report for: %s', 'ninjafirewall'), $url) . "\n";
	$message .= __('Date Range Processed: Yesterday', 'ninjafirewall') .", ". ucfirst( date_i18n('F j, Y',strtotime("-1 days")) ) ."\n\n";

	$message.= __('Blocked hacking attempts:', 'ninjafirewall') .' '.
		($logstats[1] + $logstats[2] + $logstats[3]) .
		' ('. __('critical:', 'ninjafirewall') .' '. $logstats[3] .', '.
		__('high:', 'ninjafirewall') .' '. $logstats[2] .', '.
		__('medium:', 'ninjafirewall') .' '. $logstats[1] . ")\n";

	$message.= __('Blocked brute-force attacks:', 'ninjafirewall') .' '. $logstats[0] ."\n\n";
	$message.= __('This notification can be turned off from NinjaFirewall "Event Notifications" page.', 'ninjafirewall') ."\n\n";

	$message .=
			'NinjaFirewall (WP Edition) - https://nintechnet.com/' . "\n" .
			__('Support forum:', 'ninjafirewall') . ' http://wordpress.org/support/plugin/ninjafirewall' . "\n";
		wp_mail( $recipient, $subject, $message );

}

/* ------------------------------------------------------------------ */
// EOF
