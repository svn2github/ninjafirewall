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

$log_dir = NFW_LOG_DIR . '/nfwlog/cache/';
$nfmon_snapshot = $log_dir . 'nfilecheck_snapshot.php';
$nfmon_diff = $log_dir . 'nfilecheck_diff.php';
$err = $success = '';

// Scheduled scan ?
if (defined('NFSCANDO') ) {

	$snapproc = microtime(true);
	$err = nf_sub_monitoring_scan($nfmon_snapshot, $nfmon_diff);
	$nfw_options = nfw_get_option('nfw_options');
	if (empty($nfw_options['enabled']) ) { return; }
	$nfw_options['snapproc'] = round( microtime(true) - $snapproc, 2 );
	nfw_update_option('nfw_options', $nfw_options);

	// Changes detected :
	if (! $err && file_exists($nfmon_diff) ) {
		nf_scan_email($nfmon_diff, $log_dir);
	// No changes detected :
	} else {
		// Always send a report after a scan ?
		if (! empty($nfw_options['report_scan']) ) {
			nf_scan_email(0, 0);
		}
	}
	return;
}

// Block immediately if user is not allowed :
nf_not_allowed( 'block', __LINE__ );

// Check if we have a snapshot or not:
if (! file_exists($nfmon_snapshot) ) {
	$err = __('You did not create any snapshot yet.', 'ninjafirewall');
}

if (! empty($_REQUEST['nfw_act'])) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'filecheck_save') ) {
		wp_nonce_ays('filecheck_save');
	}
	if ( $_REQUEST['nfw_act'] == 'create') {
		if (! $err = nf_sub_monitoring_create($nfmon_snapshot) ) {
			$success = __('Snapshot successfully created.', 'ninjafirewall');
			if (file_exists($nfmon_diff) ) {
				unlink($nfmon_diff);
			}
		}
	} elseif ( $_REQUEST['nfw_act'] == 'delete') {
		// Delete de current snapshot file :
		if (file_exists($nfmon_snapshot) ) {
			unlink ($nfmon_snapshot);
			$success = __('Snapshot file successfully deleted.', 'ninjafirewall');
			// Remove old diff file as well :
			if ( file_exists($nfmon_diff . '.php') ) {
				unlink($nfmon_diff . '.php');
			}
			// Clear scheduled scan (if any) and its options :
			if ( wp_next_scheduled('nfscanevent') ) {
				wp_clear_scheduled_hook('nfscanevent');
			}
			$nfw_options = nfw_get_option('nfw_options');
			$nfw_options['report_scan'] = 0;
			$nfw_options['sched_scan'] = 0;
			nfw_update_option('nfw_options', $nfw_options);

		} else {
			$err = __('You did not create any snapshot yet.', 'ninjafirewall');
		}
	} elseif ( $_REQUEST['nfw_act'] == 'scan') {
		// Scan disk for changes :
		if (! file_exists($nfmon_snapshot) ) {
			$err = __('You must create a snapshot first.', 'ninjafirewall');
		} else {

			$snapproc = microtime(true);
			$err = nf_sub_monitoring_scan($nfmon_snapshot, $nfmon_diff);
			$nfw_options = nfw_get_option('nfw_options');
			$nfw_options['snapproc'] = round( microtime(true) - $snapproc, 2);
			nfw_update_option('nfw_options', $nfw_options);

			if (! $err) {
				if (file_exists($nfmon_diff) ) {
					$err =  __('NinjaFirewall detected that changes were made to your files.', 'ninjafirewall');
					$changes = 1;
				} else {
					$success =  __('No changes detected.', 'ninjafirewall');
				}
			}
		}
	} elseif ( $_REQUEST['nfw_act'] == 'scheduled') {
		nf_scheduled_scan();
		$success = __('Your changes have been saved.', 'ninjafirewall');
	}
}

$nfw_options = nfw_get_option('nfw_options');
if ( empty($nfw_options['snapdir']) ) {
	$nfw_options['snapdir'] = '';
	if ( file_exists($nfmon_snapshot) ) {
		unlink($nfmon_snapshot);
	}
}
if (! isset($nfw_options['snapexclude']) ) {
	$nfw_options['snapexclude'] = '/'. basename(WP_CONTENT_DIR) .'/nfwlog/';
}

echo '<div class="wrap">
	<div style="width:33px;height:33px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h1>' . __('File Check', 'ninjafirewall') . '</h1>';

if ( $err ) {
	echo '<div class="error notice is-dismissible"><p>' . $err . '</p></div>';
} elseif ( $success ) {
	echo '<div class="updated notice is-dismissible"><p>' . $success . '</p></div>';
}

// If we don't have a snapshopt, offer to create one :
if (! file_exists($nfmon_snapshot) ) {
	?>
	<br />
	<form method="post" name="monitor_form">
		<?php wp_nonce_field('filecheck_save', 'nfwnonce', 0); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Create a snapshot of all files stored in that directory', 'ninjafirewall') ?></th>
				<td align="left"><input class="large-text" type="text" name="snapdir" value="<?php
				if (! empty($nfw_options['snapdir']) ) {
					echo htmlspecialchars($nfw_options['snapdir']);
				} else {
					echo htmlspecialchars(ABSPATH);
				}
				?>" required /></td>
			</tr>

			<tr>
				<th scope="row"><?php _e('Exclude the following files/folders (optional)', 'ninjafirewall') ?></th>
				<td align="left"><input class="large-text" type="text" name="snapexclude" value="<?php echo htmlentities($nfw_options['snapexclude']); ?>" placeholder="<?php _e('e.g.,', 'ninjafirewall') ?> /wp-content/nfwlog/" maxlength="255"><br /><span class="description"><?php _e('Full or partial case-sensitive string(s). Multiple values must be comma-separated', 'ninjafirewall') ?> (<code>,</code>).</span></td>
			</tr>

			<tr>
				<th scope="row">&nbsp;</th>
				<td align="left"><label><input type="checkbox" name="snapnoslink" value="1" checked="checked" /><?php _e('Do not follow symbolic links (default)', 'ninjafirewall') ?></label></td>
			</tr>

		</table>
		<input type="hidden" name="nfw_act" value="create" />
		<p><input type="submit" class="button-primary" value="<?php _e('Create Snapshot', 'ninjafirewall') ?>" /></p>
	</form>
</div>
	<?php
	return;
}

// We have a snapshot :
$stat = stat($nfmon_snapshot);
$count = -2;
$fh = fopen($nfmon_snapshot, 'r');
while (! feof($fh) ) {
	fgets($fh);
	$count++;
}
fclose($fh);
nfw_get_blogtimezone();
// Look for new/mod/del files :
$res = $new_file = $del_file = $mod_file = array();
// If no changes were detected, we display the last ones (if any) :
if (! file_exists($nfmon_diff) && file_exists($nfmon_diff . '.php') ) {
	$nfmon_diff = $nfmon_diff . '.php';
}
if (file_exists($nfmon_diff) ) {
	$fh = fopen($nfmon_diff, 'r');
	while (! feof($fh) ) {
		$res = explode('::', fgets($fh) );
		if ( empty($res[1]) ) { continue; }
		// New file :
		if ($res[1] == 'N') {
			$s_tmp = explode(':', rtrim($res[2]));
			$new_file[$res[0]] = $s_tmp[0] .':'.
				$s_tmp[1] .':'.
				$s_tmp[2] .':'.
				$s_tmp[3] .':'.
				date('Y-m-d H~i~s O', $s_tmp[4]) .':'.
				date('Y-m-d H~i~s O', $s_tmp[5]);
		// Deleted file :
		} elseif ($res[1] == 'D') {
			$del_file[$res[0]] = 1;
		// Modified file:
		} elseif ($res[1] == 'M') {
			$s_tmp = explode(':', $res[2]);
			$mod_file[$res[0]] = $s_tmp[0] .':'.
				$s_tmp[1] .':'.
				$s_tmp[2] .':'.
				$s_tmp[3] .':'.
				date('Y-m-d H~i~s O', $s_tmp[4]) .':'.
				date('Y-m-d H~i~s O', $s_tmp[5]) .'::';
				$s_tmp = explode(':', rtrim($res[3]));
			$mod_file[$res[0]] .= $s_tmp[0] .':'.
				$s_tmp[1] .':'.
				$s_tmp[2] .':'.
				$s_tmp[3] .':'.
				date('Y-m-d H~i~s O', $s_tmp[4]) .':'.
				date('Y-m-d H~i~s O', $s_tmp[5]);
		}
	}
	fclose($fh);
	$mod = 1;
} else {
	$mod = 0;
}
	?>
	<script>
	<?php if ($mod) { ?>
	function file_info(what, where) {
		if ( what == '' ) { return false; }
		// New file :
		if (where == 1) {
			<?php if ($new_file) { ?>
			var nfo = what.split(':');
			document.getElementById('new_size').innerHTML = nfo[3];
			document.getElementById('new_chmod').innerHTML = nfo[0];
			document.getElementById('new_uidgid').innerHTML = nfo[1] + ' / ' + nfo[2];
			document.getElementById('new_mtime').innerHTML = nfo[4].replace(/~/g, ':');
			document.getElementById('new_ctime').innerHTML = nfo[5].replace(/~/g, ':');
			document.getElementById('table_new').style.display = '';
			<?php } ?>
		// Modified file :
		} else if (where == 2) {
			<?php if ($mod_file) { ?>
			var all = what.split('::');
			var nfo = all[0].split(':');
			var nfo2 = all[1].split(':');
			document.getElementById('mod_size').innerHTML = nfo[3];
			if (nfo[3] != nfo2[3]) {
				document.getElementById('mod_size2').innerHTML = '<font color="red">'+ nfo2[3] +'</font>';
			} else {
				document.getElementById('mod_size2').innerHTML = nfo2[3];
			}
			document.getElementById('mod_chmod').innerHTML = nfo[0];
			if (nfo[0] != nfo2[0]) {
				document.getElementById('mod_chmod2').innerHTML = '<font color="red">'+ nfo2[0] +'</font>';
			} else {
				document.getElementById('mod_chmod2').innerHTML = nfo2[0];
			}
			document.getElementById('mod_uidgid').innerHTML = nfo[1] + ' / ' + nfo[2];
			if ( (nfo[1] != nfo2[1]) || (nfo[2] != nfo2[2]) ) {
				document.getElementById('mod_uidgid2').innerHTML = '<font color="red">'+ nfo2[1] + '/' + nfo2[2] +'</font>';
			} else {
				document.getElementById('mod_uidgid2').innerHTML = nfo2[1] + ' / ' + nfo2[2];
			}
			document.getElementById('mod_mtime').innerHTML = nfo[4].replace(/~/g, ':');
			if (nfo[4] != nfo2[4]) {
				document.getElementById('mod_mtime2').innerHTML = '<font color="red">'+ nfo2[4].replace(/~/g, ':') +'</font>';
			} else {
				document.getElementById('mod_mtime2').innerHTML = nfo2[4].replace(/~/g, ':');
			}
			document.getElementById('mod_ctime').innerHTML = nfo[5].replace(/~/g, ':');
			if (nfo[5] != nfo2[5]) {
				document.getElementById('mod_ctime2').innerHTML = '<font color="red">'+ nfo2[5].replace(/~/g, ':') +'</font>';
			} else {
				document.getElementById('mod_ctime2').innerHTML = nfo2[5].replace(/~/g, ':');
			}
			document.getElementById('table_mod').style.display = '';
			<?php } ?>
		}
	}
	<?php } ?>
	function delit() {
		if (confirm("<?php echo esc_js( __('Delete the current snapshot?', 'ninjafirewall') ) ?>") ) {
			return true;
		}
		return false;
	}
	function nftoogle() {
		jQuery("#changes_table").slideDown();
		document.getElementById('vcbtn').disabled = true;
	}
	</script>
	<br />

	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Last snapshot', 'ninjafirewall') ?></th>
			<td align="left">
				<p><?php printf( __('Created on: %s', 'ninjafirewall'), date_i18n('M d, Y @ H:i:s O', $stat['ctime'])); ?></p>
				<p><?php printf( __('Total files: %s ', 'ninjafirewall'), number_format($count) ); ?></p>

				<p><?php _e('Directory:', 'ninjafirewall') ?> <code><?php echo htmlspecialchars($nfw_options['snapdir']) ?></code></p>
				<?php
				if (! empty($nfw_options['snapexclude']) ) {
					$res = @explode(',', $nfw_options['snapexclude']);
					echo '<p>' .  __('Exclusion:', 'ninjafirewall') . ' ';
					foreach ($res as $exc) {
						echo '<code>' . htmlspecialchars($exc) . '</code>&nbsp;';
					}
					echo '</p>';
				}
				echo	'<p>' .  __('Symlinks:', 'ninjafirewall') . ' ';
				if ( empty($nfw_options['snapnoslink']) ) {
					echo __('follow', 'ninjafirewall');
				} else {
					echo __('do not follow', 'ninjafirewall');
				}
				echo '</p>';
				if (! empty($nfw_options['snapproc']) ) {
					echo '<p>' . sprintf( __('Processing time: %s seconds', 'ninjafirewall'), $nfw_options['snapproc']) . '</p>';
				}
				?>
				<form method="post">
					<?php wp_nonce_field('filecheck_save', 'nfwnonce', 0); ?>
					<p><input type="submit" name="dlsnap" value="<?php _e('Download Snapshot', 'ninjafirewall') ?>" class="button-secondary" />&nbsp;&nbsp;&nbsp;<input type="submit" class="button-secondary" onClick="return delit();" value="<?php _e('Delete Snapshot', 'ninjafirewall') ?>" /><input type="hidden" name="nfw_act" value="delete" /></p>
				</form>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Last changes', 'ninjafirewall') ?></th>
			<td align="left">

			<?php
			// Show info about last changes, if any :
			if ($mod) {
			?>
				<p><?php printf( __('New files: %s', 'ninjafirewall'), count($new_file) ) ?></p>
				<p><?php printf( __('Deleted files: %s', 'ninjafirewall'), count($del_file) ) ?></p>
				<p><?php printf( __('Modified files: %s', 'ninjafirewall'), count($mod_file) ) ?></p>

				<form method="post">
					<?php wp_nonce_field('filecheck_save', 'nfwnonce', 0); ?>
					<p><input type="button" value="<?php _e('View Changes', 'ninjafirewall') ?>" onClick="nftoogle();" class="button-secondary" id="vcbtn" <?php
					if (! empty($changes)) {
						echo 'disabled="disabled" ';
					}
					?>/>&nbsp;&nbsp;&nbsp;<input type="submit" name="dlmods" value="<?php _e('Download Changes', 'ninjafirewall') ?>" class="button-secondary" /></p>
				</form>
				<br />
			<?php
				if (empty($changes)) {
					echo '<div id="changes_table" style="display:none">';
				} else {
					echo '<div id="changes_table">';
				}

				echo '<table border="0" width="100%">';

				$more_info = __('Click a file to get more info about it.', 'ninjafirewall');
				if ($new_file) {
					echo '<tr><td>';
					echo __('New files:', 'ninjafirewall') . ' ' . count($new_file). '<br />';
					echo '<select name="sometext" multiple="multiple" style="width:100%;height:150px" onClick="file_info(this.value, 1);">';
					foreach($new_file as $k => $v) {
						echo '<option value="' . htmlspecialchars($v) . '" title="' . htmlspecialchars($k) . '">' . htmlspecialchars($k) . '</option>';
					}
					echo'</select>
					<p style="text-align:center"><span class="description">' . $more_info . '</span></p>
					<table id="table_new" style="width:100%;background-color:#F7F7F7;border:solid 1px #DFDFDF;display:none;">
						<tr>
							<th style="padding:0;width:25%;">' . __('Size', 'ninjafirewall') .'</th>
							<td style="padding:0" id="new_size"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . __('Access', 'ninjafirewall') .'</th>
							<td style="padding:0" id="new_chmod"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . __('Uid / Gid', 'ninjafirewall') .'</th>
							<td style="padding:0" id="new_uidgid"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . __('Modify', 'ninjafirewall') .'</th>
							<td style="padding:0" id="new_mtime"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . __('Change', 'ninjafirewall') .'</th>
							<td style="padding:0" id="new_ctime"></td>
						</tr>
					</table>
				</td></tr>';

				}
				if ($del_file) {
					echo '
			<tr>
				<td>' . __('Deleted files:', 'ninjafirewall') .' '. count($del_file). '<br />' .
					'<select name="sometext" multiple="multiple" style="width:100%;height:150px">';
					foreach($del_file as $k => $v) {
						echo '<option title="' . htmlspecialchars($k) . '">' . htmlspecialchars($k) . '</option>';
					}
					echo'</select>
				</td>
			</tr>';

				}
				if ($mod_file) {
					echo '
			<tr>
				<td>' . __('Modified files:', 'ninjafirewall') .' '. count($mod_file). '<br />' .
					'<select name="sometext" multiple="multiple" style="width:100%;height:150px" onClick="file_info(this.value, 2);">';
					foreach($mod_file as $k => $v) {
						echo '<option value="' . htmlspecialchars($v) . '" title="' . htmlspecialchars($k) . '">' . htmlspecialchars($k) . '</option>';
					}
					echo'</select>
					<p style="text-align:center"><span class="description">' . $more_info . '</span></p>
					<table id="table_mod" style="width:100%;background-color:#F7F7F7;border:solid 1px #DFDFDF;display:none;">
						<tr>
							<th style="padding:0;width:25%;">&nbsp;</th>
							<td style="padding:0"><b>' . __('Old', 'ninjafirewall') .'</b></td>
							<td style="padding:0"><b>' . __('New', 'ninjafirewall') .'</b></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . __('Size', 'ninjafirewall') .'</th>
							<td style="padding:0" id="mod_size"></td>
							<td style="padding:0" id="mod_size2"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . __('Access', 'ninjafirewall') .'</th>
							<td style="padding:0" id="mod_chmod"></td>
							<td style="padding:0" id="mod_chmod2"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . __('Uid / Gid', 'ninjafirewall') .'</th>
							<td style="padding:0" id="mod_uidgid"></td>
							<td style="padding:0" id="mod_uidgid2"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . __('Modify', 'ninjafirewall') .'</th>
							<td style="padding:0" id="mod_mtime"></td>
							<td style="padding:0" id="mod_mtime2"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . __('Change', 'ninjafirewall') .'</th>
							<td style="padding:0" id="mod_ctime"></td>
							<td style="padding:0" id="mod_ctime2"></td>
						</tr>
					</table>
				</td>
			</tr>';
				}
				echo '
		</table>
		</div>

		</td>
		</tr>
		</table>';
			} else {
				echo __('None', 'ninjafirewall') . '
			</td>
		</tr>
	</table>
	<br />';
			}
		?>
	<form method="post">
		<?php wp_nonce_field('filecheck_save', 'nfwnonce', 0); ?>
		<input type="hidden" name="nfw_act" value="scan" />
		<p><input type="submit" class="button-primary" value="<?php _e('Scan System For File Changes', 'ninjafirewall') ?> &#187;" /></p>
	</form>

	<br />
	<br />
	<?php
	if (! isset($nfw_options['sched_scan']) ) {
		$sched_scan = 0;
	} else {
		$sched_scan = $nfw_options['sched_scan'];
	}
	if ( empty($nfw_options['report_scan']) ) {
		$report_scan = 0;
	} else {
		$report_scan = 1;
	}
	?>
	<h3><?php _e('Options', 'ninjafirewall') ?></h3>
	<form method="post">
		<?php
		wp_nonce_field('filecheck_save', 'nfwnonce', 0);
		// If WP cron is disabled, we simply warn the user :
		if ( defined('DISABLE_WP_CRON') ) {
		?>
			<p><img src="<?php echo plugins_url() ?>/ninjafirewall/images/icon_warn_16.png" height="16" border="0" width="16">&nbsp;<span class="description"><?php printf( __('It seems that %s is enabled. Ensure you have another way to run WP-Cron, otherwise NinjaFirewall scheduled scans will not work.', 'ninjafirewall'), '<code>DISABLE_WP_CRON</code>' ) ?></span></p>
		<?php
		}
		?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Enable scheduled scans', 'ninjafirewall') ?></th>
				<td align="left">
					<p><label><input type="radio" name="sched_scan" value="0"<?php checked($sched_scan, 0) ?> /><?php _e('No (default)', 'ninjafirewall') ?></label></p>
					<p><label><input type="radio" name="sched_scan" value="1"<?php checked($sched_scan, 1) ?> /><?php _e('Hourly', 'ninjafirewall') ?></label></p>
					<p><label><input type="radio" name="sched_scan" value="2"<?php checked($sched_scan, 2) ?> /><?php _e('Twicedaily', 'ninjafirewall') ?></label></p>
					<p><label><input type="radio" name="sched_scan" value="3"<?php checked($sched_scan, 3) ?> /><?php _e('Daily', 'ninjafirewall') ?></label></p>
					<?php
					if ( $nextscan = wp_next_scheduled('nfscanevent') ) {
						$sched = new DateTime( date('M d, Y H:i:s', $nextscan) );
						$now = new DateTime( date('M d, Y H:i:s', time() ) );
						$diff = $now->diff($sched);
					?>
						<p><span class="description"><?php printf( __('Next scan will start in approximately %s day(s), %s hour(s), %s minute(s) and %s second(s).', 'ninjafirewall'), $diff->format('%a') % 7, $diff->format('%h'), $diff->format('%i'), $diff->format('%s') ) ?></span></p>
					<?php
						// Ensure that the scheduled scan time is in the future,
						// not in the past, otherwise send a warning because wp-cron
						// is obviously not working as expected :
						if ( $nextscan < time() ) {
						?>
							<p><img src="<?php echo plugins_url() ?>/ninjafirewall/images/icon_warn_16.png" height="16" border="0" width="16">&nbsp;<span class="description"><?php _e('The next scheduled scan date is in the past! WordPress wp-cron may not be working or may have been disabled.', 'ninjafirewall'); ?></span>
						<?php
						}
					}
					?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Scheduled scan report', 'ninjafirewall') ?></th>
				<td align="left">
					<p><label><input type="radio" name="report_scan" value="0"<?php checked($report_scan, 0) ?> /><?php _e('Send me a report by email only if changes are detected (default)', 'ninjafirewall') ?></label></p>
					<p><label><input type="radio" name="report_scan" value="1"<?php checked($report_scan, 1) ?> /><?php _e('Always send me a report by email after a scheduled scan', 'ninjafirewall') ?></label></p>
				</td>
			</tr>
		</table>
		<input type="hidden" name="nfw_act" value="scheduled" />
		<p><input type="submit" class="button-primary" value="<?php _e('Save Scan Options', 'ninjafirewall') ?>" /></p>
	</form>

</div>
<?php

/* ------------------------------------------------------------------ */

function nf_sub_monitoring_create($nfmon_snapshot) {

	// Check POST data:
	if ( empty($_POST['snapdir']) ) {
		return __('Enter the full path to the directory to be scanned.', 'ninjafirewall');
	}
	if ( strlen($_POST['snapdir']) > 1 ) {
		$_POST['snapdir'] = rtrim($_POST['snapdir'], '/');
	}
	if (! file_exists($_POST['snapdir']) ) {
		return sprintf( __('The directory %s does not exist.', 'ninjafirewall'), '<code>'. htmlspecialchars($_POST['snapdir']) .'</code>');
	}
	if (! is_readable($_POST['snapdir']) ) {
		return sprintf( __('The directory %s is not readable.', 'ninjafirewall'), '<code>'. htmlspecialchars($_POST['snapdir']) .'</code>');
	}
	if ( isset($_POST['snapnoslink']) ) {
		$snapnoslink = 1;
	} else {
		$snapnoslink = 0;
	}

	$snapexclude = '';
	if (! empty($_POST['snapexclude']) ) {
		$_POST['snapexclude'] = trim($_POST['snapexclude']);
		$tmp = preg_quote($_POST['snapexclude'], '/');
		$snapexclude = str_replace(',', '|', $tmp);
	}

	@ini_set('max_execution_time', 0);
	$snapproc = microtime(true);

	if ($fh = fopen($nfmon_snapshot, 'w') ) {
		fwrite($fh, '<?php die("Forbidden"); ?>' . "\n");
		$res = scd($_POST['snapdir'], $snapexclude, $fh, $snapnoslink);
		fclose($fh);

		// Error ?
		if ($res) {
			if (file_exists($nfmon_snapshot) ) {
				unlink($nfmon_snapshot);
			}
			return $res;
		}
		$stat = stat($nfmon_snapshot);
		if ($stat['size'] < 30 ) {
			unlink($nfmon_snapshot);
			return sprintf( __('Unable to create %s.', 'ninjafirewall'), '<code>'. $nfmon_snapshot .'</code>');
		}

		// Save scan dir :
		$nfw_options = nfw_get_option('nfw_options');
		$nfw_options['snapproc'] = round( microtime(true) - $snapproc, 2);
		$nfw_options['snapexclude'] = $_POST['snapexclude'];
		$nfw_options['snapdir'] = $_POST['snapdir'];
		$nfw_options['snapnoslink'] = $snapnoslink;
		nfw_update_option('nfw_options', $nfw_options);

	} else {
		return sprintf( __('Cannot write to %s.', 'ninjafirewall'), '<code>'. $nfmon_snapshot .'</code>');
	}
}

/* ------------------------------------------------------------------ */

function scd($snapdir, $snapexclude, $fh, $snapnoslink) {

	if (is_readable($snapdir) ) {
		if ($dh = opendir($snapdir) ) {
			while ( FALSE !== ($file = readdir($dh)) ) {
				if ( $file == '.' || $file == '..') { continue; }
				$full_path = $snapdir . '/' . $file;
				if ( $snapexclude ) {
					if ( preg_match("/$snapexclude/", $full_path) ) { continue; }
				}
				if (is_readable($full_path)) {
					if ( $snapnoslink && is_link($full_path)) { continue; }
					if ( is_dir($full_path) ) {
						scd($full_path, $snapexclude, $fh, $snapnoslink);
					} elseif (is_file($full_path) ) {
						$file_stat = stat($full_path);
						fwrite($fh, $full_path . '::' . sprintf ("%04o", $file_stat['mode'] & 0777) . ':' . $file_stat['uid'] . ':' .
							$file_stat['gid'] . ':' . $file_stat['size'] . ':' . $file_stat['mtime'] . ':' .
							$file_stat['ctime'] . "\n");
					}
				}
			}
			closedir($dh);
		} else {
			return sprintf(__('Error : cannot open %s directory.', 'ninjafirewall'), '<code>'. htmlspecialchars($snapdir) .'</code>');
		}
	} else {
		return sprintf(__('Error : %s directory is not readable.', 'ninjafirewall'), '<code>'. htmlspecialchars($snapdir) .'</code>');
	}
}

/* ------------------------------------------------------------------ */

function nf_sub_monitoring_scan($nfmon_snapshot, $nfmon_diff) {

	$nfw_options = nfw_get_option('nfw_options');

	if (empty($nfw_options['enabled']) ) { return; }

	@ini_set('max_execution_time', 0);

	if (! isset($nfw_options['snapexclude']) || ! isset($nfw_options['snapdir']) || ! isset($nfw_options['snapnoslink']) ) {
		return sprintf( __('Missing options line %s, please try again.', 'ninjafirewall'), __LINE__ );
	}
	$tmp = preg_quote($nfw_options['snapexclude'], '/');
	$snapexclude = str_replace(',', '|', $tmp);

	if ($fh = fopen($nfmon_snapshot . '_tmp', 'w') ) {
		fwrite($fh, '<?php die("Forbidden"); ?>' . "\n");
		$res = scd($nfw_options['snapdir'], $snapexclude, $fh, $nfw_options['snapnoslink']);
		fclose($fh);
	} else {
		return sprintf( __('Cannot create %s.', 'ninjafirewall'), '<code>'. $nfmon_snapshot . '_tmp</code>');
	}

	// Error ?
	if ($res) {
		if (file_exists($nfmon_snapshot . '_tmp') ) {
			unlink($nfmon_snapshot . '_tmp');
		}
		return $res;
	}

	// Compare both snapshots :

	$old_files = $file = $new_files =  array();
	$modified_files = $match = array();

	if (! $fh = fopen($nfmon_snapshot, 'r') ) {
		return sprintf( __('Error reading old snapshot file.', 'ninjafirewall'), __LINE__ );
	}
	while (! feof($fh) ) {
		$match = explode('::', rtrim(fgets($fh)) . '::' );
		if (! empty($match[1]) ) {
			$old_files[$match[0]] = $match[1];
		}
	}
	fclose($fh);

	if (! $fh = fopen($nfmon_snapshot . '_tmp', 'r') ) {
		return sprintf( __('Error reading new snapshot file.', 'ninjafirewall'), __LINE__ );
	}
	while (! feof($fh) ) {
		$match = explode('::', rtrim(fgets($fh)) . '::' );

		if ( empty($match[1]) ) {
			continue;
		}

		// New file ?
		if ( empty( $old_files[$match[0]] ) ) {
			$new_files[$match[0]] = $match[1];
			continue;
		}

		// Modified file ?
		if ( $old_files[$match[0]] !=	$match[1] ) {
			 $modified_files[$match[0]] = $old_files[$match[0]] . '::' . $match[1];
		}

		// Delete it from old files list :
		unset( $old_files[$match[0]] );
	}
	fclose ($fh);

	// Write changes to file, if any :
	if ($new_files || $modified_files || $old_files) {

		$fh = fopen($nfmon_diff, 'w');
		fwrite($fh, '<?php die("Forbidden"); ?>' . "\n");

		if ( $new_files ) {
			foreach ( $new_files as $fkey => $fvalue ) {
				fwrite($fh, $fkey . '::N::' . $fvalue . "\n");
			}
		}

		if ( $modified_files ) {
			foreach ( $modified_files as $fkey => $fvalue ) {
				fwrite($fh, $fkey . '::M::' . $fvalue . "\n");
			}
		}

		if ( $old_files ) {
			foreach ( $old_files as $fkey => $fvalue ) {
				fwrite($fh, $fkey . '::D::' . $fvalue . "\n");
			}
		}
		fclose($fh);
		rename( $nfmon_snapshot . '_tmp', $nfmon_snapshot);

	} else {
		if (file_exists($nfmon_diff) ) {
			// Keep last changes :
			rename($nfmon_diff, $nfmon_diff. '.php');
		}
		unlink( $nfmon_snapshot . '_tmp');
	}
}

/* ------------------------------------------------------------------ */

function nf_scheduled_scan() {

	$nfw_options = nfw_get_option('nfw_options');

	if (! isset($_POST['sched_scan']) || ! preg_match('/^[1-3]$/', $_POST['sched_scan']) ) {
		$nfw_options['sched_scan'] = 0;
		// Clear scheduled scan, if any :
		if ( wp_next_scheduled('nfscanevent') ) {
			wp_clear_scheduled_hook('nfscanevent');
		}
	} else {
		if ($_POST['sched_scan'] == 1) {
			$schedtype = 'hourly';
		} elseif ($_POST['sched_scan'] == 2) {
			$schedtype = 'twicedaily';
		} else {
			$schedtype = 'daily';
		}
		$nfw_options['sched_scan'] = $_POST['sched_scan'];
		// Create a new scheduled scan :
		if ( wp_next_scheduled('nfscanevent') ) {
			wp_clear_scheduled_hook('nfscanevent');
		}
		wp_schedule_event( time() + 3600, $schedtype, 'nfscanevent');
	}

	if ( empty($_POST['report_scan']) ) {
		$nfw_options['report_scan'] = 0;
	} else {
		$nfw_options['report_scan'] = 1;
	}
	nfw_update_option('nfw_options', $nfw_options);

}

/* ------------------------------------------------------------------ */

function nf_scan_email($nfmon_diff, $log_dir) {

	$nfw_options = nfw_get_option('nfw_options');
	if ( ( is_multisite() ) && ( $nfw_options['alert_sa_only'] == 2 ) ) {
		$recipient = get_option('admin_email');
	} else {
		$recipient = $nfw_options['alert_email'];
	}

	nfw_get_blogtimezone();

	// Changes were detected :
	if ( $nfmon_diff ) {
		$stat = stat($nfmon_diff);
		$data = '== NinjaFirewall File Check (diff)'. "\n";
		$data.= '== ' . site_url() . "\n";
		$data.= '== ' . date_i18n('M d, Y @ H:i:s O', $stat['ctime']) . "\n\n";
		$data.= '[+] = ' . __('New file', 'ninjafirewall') .
					'      [-] = ' . __('Deleted file', 'ninjafirewall') .
					'      [!] = ' . __('Modified file', 'ninjafirewall') .
					"\n\n";
		$fh = fopen($nfmon_diff, 'r');
		while (! feof($fh) ) {
			$res = explode('::', fgets($fh) );
			if ( empty($res[1]) ) { continue; }
			// New file :
			if ($res[1] == 'N') {
				$data .= '[+] ' . $res[0] . "\n";
			// Deleted file :
			} elseif ($res[1] == 'D') {
				$data .= '[-] ' . $res[0] . "\n";
			// Modified file:
			} elseif ($res[1] == 'M') {
				$data .= '[!] ' . $res[0] . "\n";
			}
		}
		fclose($fh);
		$data .= "\n== EOF\n";
		@file_put_contents($log_dir . 'nf_filecheck.txt', $data, LOCK_EX);
		$subject = __('[NinjaFirewall] Alert: File Check detection', 'ninjafirewall');
		$msg = __('NinjaFirewall detected that changes were made to your files.', 'ninjafirewall') . "\n\n";
		if ( is_multisite() ) {
			$msg .=__('Blog:', 'ninjafirewall') .' '. network_home_url('/') . "\n";
		} else {
			$msg .=__('Blog:', 'ninjafirewall') .' '. home_url('/') . "\n";
		}
		$msg .= sprintf( __('Date: %s', 'ninjafirewall'), ucfirst(date_i18n('M d, Y @ H:i:s O')) )."\n\n";
		$msg .= __('See attached file for details.', 'ninjafirewall') . "\n\n" .
			'NinjaFirewall (WP Edition) - http://ninjafirewall.com/' . "\n" .
			__('Support forum:', 'ninjafirewall') .' http://wordpress.org/support/plugin/ninjafirewall' . "\n";

		wp_mail( $recipient, $subject, $msg, '', $log_dir . 'nf_filecheck.txt' );
		unlink($log_dir . 'nf_filecheck.txt');

	} else {

		// User asked to always receive a report after a scheduled scan :
		$subject = __('[NinjaFirewall] File Check report', 'ninjafirewall');
		$msg = __('NinjaFirewall did not detect changes in your files.', 'ninjafirewall') . "\n\n";
		if ( is_multisite() ) {
			$msg .=__('Blog:', 'ninjafirewall') .' '. network_home_url('/') . "\n";
		} else {
			$msg .=__('Blog:', 'ninjafirewall') .' '. home_url('/') . "\n";
		}
		$msg .= sprintf( __('Date: %s', 'ninjafirewall'), ucfirst(date_i18n('M d, Y @ H:i:s O')) ) . "\n\n" .
			'NinjaFirewall (WP Edition) - http://ninjafirewall.com/' . "\n" .
			__('Support forum:', 'ninjafirewall') .' http://wordpress.org/support/plugin/ninjafirewall' . "\n";
		wp_mail( $recipient, $subject, $msg );
	}
}

/* ------------------------------------------------------------------ */
// EOF
