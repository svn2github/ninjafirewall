<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 +---------------------------------------------------------------------+
 | REVISION: 2016-03-27 10:58:57                                       |
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

$log_dir = NFW_LOG_DIR . '/nfwlog/';
$monthly_log = 'firewall_' . date( 'Y-m' ) . '.php';

if ( isset($_GET['nfw_sort']) ) {
	if ( empty($_GET['nfwnonce']) || ! wp_verify_nonce($_GET['nfwnonce'], 'log_select') ) {
		wp_nonce_ays('log_select');
	}
}

// Find all available logs :
$avail_logs = array();
if ( is_dir( $log_dir ) ) {
	if ( $dh = opendir( $log_dir ) ) {
		while ( ($file = readdir($dh) ) !== false ) {
			if (preg_match( '/^(firewall_(\d{4})-(\d\d)(?:\.\d+)?\.php)$/', $file, $match ) ) {
				$avail_logs[$match[1]] = 1;
			}
		}
		closedir($dh);
	}
}
krsort($avail_logs);

if (! empty($_GET['nfw_sort']) && isset( $avail_logs[$_GET['nfw_sort']] ) ) {
	$selected_log = $_GET['nfw_sort'];
} else {
	$selected_log = $monthly_log;
	// If there is no current log, we try to display the one
	// from the previous month (if any) :
	if (! file_exists( $log_dir . $selected_log ) && ! empty($avail_logs) ) {
		$selected_log = key($avail_logs);
	}
}
// If there isn't any old logs, add the current one to the array :
if (empty( $avail_logs) ) {
	$avail_logs[$selected_log] = 1;
}

// Ensure it exists :
$err = '';
if ( file_exists( $log_dir . $selected_log ) ) {
	if (! is_writable( $log_dir . $selected_log ) ) {
		$err = __('logfile is not writable. Please chmod it and its parent directory to 0777', 'ninjafirewall');
	}
} else {
	if (! is_writable( $log_dir ) ) {
		$err = __('log directory is not writable. Please chmod it to 0777', 'ninjafirewall');
	}
}

echo '<div class="wrap">
	<div style="width:33px;height:33px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h1>' . __('Firewall Log', 'ninjafirewall') . '</h1>';

if ( $err ) {
	echo '<div class="error notice is-dismissible"><p>' . __('Error', 'ninjafirewall') . ' : ' . $err . '</p></div>';
}

// Do we have any log for this month ?
if (! file_exists( $log_dir . $selected_log ) ) {
	echo '<div class="error notice is-dismissible"><p>' . __('You do not have any log for the current month yet.', 'ninjafirewall') . '</p></div></div>';
	return;
}

if (! $fh = @fopen( $log_dir . $selected_log, 'r' ) ) {
	echo '<div class="error notice is-dismissible"><p>' . __('Fatal error', 'ninjafirewall') . ' : ' . __('cannot open the log', 'ninjafirewall') . ' ( ' . $selected_log .' )</p></div></div>';
	return;
}
// We will only display the last $max_lines lines, and will warn about it
// if the log is bigger :
$count = 0;
$max_lines = 1500;
while (! feof( $fh ) ) {
	fgets( $fh );
	$count++;
}
// Skip the first (PHP code) and the last (empty) line :
$count -= 2;
fclose( $fh );
if ( $count <= $max_lines ) {
	$skip = 0;
} else  {
	echo '<div class="error notice is-dismissible"><p>' . __('Warning', 'ninjafirewall') . ' : ';
	printf( __('your log has %s lines. I will display the last %s lines only.', 'ninjafirewall'), $count, $max_lines );
	echo '</p></div>';
	$skip = $count - $max_lines + 1;
}

// Add select box:
echo '<center>' . __('Viewing:', 'ninjafirewall') . ' <select name="nfw_sort" onChange=\'window.location="?page=nfsublog&nfwnonce='. wp_create_nonce('log_select') .'&nfw_sort=" + this.value;\'>';
foreach ($avail_logs as $log_name => $tmp) {
	echo '<option value="' . $log_name . '"';
	if ( $selected_log == $log_name ) {
		echo ' selected';
	}
	$log_stat = stat($log_dir . $log_name);
	echo '>' . str_replace('.php', '', $log_name) . ' (' . number_format($log_stat['size']) .' '. __('bytes', 'ninjafirewall') . ')</option>';
}
echo '</select></center>';

$levels = array( '', 'medium', 'high', 'critical', 'error', 'upload', 'info', 'DEBUG_ON' );

// Get timezone :
nfw_get_blogtimezone();
?>

<script>
<?php
$fh = fopen( $log_dir . $selected_log, 'r' );
$logline = '';
while (! feof( $fh ) ) {
	$line = fgets( $fh );
	if ( $skip <= 0 ) {
		if ( preg_match( '/^\[(\d{10})\]\s+\[.+?\]\s+\[(.+?)\]\s+\[(#\d{7})\]\s+\[(\d+)\]\s+\[(\d)\]\s+\[([\d.:a-fA-F, ]+?)\]\s+\[.+?\]\s+\[(.+?)\]\s+\[(.+?)\]\s+\[(.+?)\]\s+\[(hex:)?(.+)\]$/', $line, $match ) ) {
			if ( empty( $match[4]) ) { $match[4] = '-'; }
			if ( $match[10] == 'hex:' ) { $match[11] = pack('H*', $match[11]); }
			$res = date( 'd/M/y H:i:s', $match[1] ) . '  ' . $match[3] . '  ' .
			str_pad( $levels[$match[5]], 8 , ' ', STR_PAD_RIGHT) .'  ' .
			str_pad( $match[4], 4 , ' ', STR_PAD_LEFT) . '  ' . str_pad( $match[6], 15, ' ', STR_PAD_RIGHT) . '  ' .
			$match[7] . ' ' . $match[8] . ' - ' .	$match[9] . ' - [' . $match[11] . ']';
			// If multi-site mode, append the domain name :
			if ( is_multisite() ) {
				$res .= ' - ' . $match[2];
			}
			$logline .= htmlentities( $res ."\n" );
		}
	}
	$skip--;
}
fclose( $fh );
?>
</script>
<form name="frmlog">
	<table class="form-table">
		<tr>
			<td width="100%">
				<textarea name="txtlog" class="small-text code" style="width:100%;height:300px;" wrap="off"><?php
					echo '       DATE         INCIDENT  LEVEL     RULE     IP            REQUEST' . "\n";
				echo $logline; ?></textarea>
				<br />
				<center><span class="description"><?php _e('The log is rotated monthly', 'ninjafirewall') ?></span>
				</center>
			</td>
		</tr>
	</table>
</form>
</div>
<?php
/* ------------------------------------------------------------------ */
// EOF
