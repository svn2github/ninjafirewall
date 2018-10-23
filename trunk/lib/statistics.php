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

// Block immediately if user is not allowed :
nf_not_allowed( 'block', __LINE__ );

echo '
<div class="wrap">
	<h1><img style="vertical-align:top;width:33px;height:33px;" src="'. plugins_url( '/ninjafirewall/images/ninjafirewall_32.png' ) .'">&nbsp;' . __('Statistics', 'ninjafirewall') . '</h1>';

// Display a one-time notice after two weeks of use:
$nfw_options = nfw_get_option( 'nfw_options' );
nfw_rate_notice( $nfw_options );

$slow = 0; $tot_bench = 0; $speed = 0; $fast = 1000;

// Which monthly log should we read ?
if ( empty( $_GET['statx'] ) || ! preg_match('/^\d{4}-\d{2}$/D', $_GET['statx'] ) ) {
	$statx = date('Y-m');
} else {
	$statx = $_GET['statx'];
}
// Make sure the stat file exists:
$stat_file = NFW_LOG_DIR . "/nfwlog/stats_{$statx}.php";
// Parse it:
if ( file_exists( $stat_file ) ) {
	$nfw_stat = file_get_contents( $stat_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
} else {
	$nfw_stat = '0:0:0:0:0:0:0:0:0:0';
	goto NO_STATS;
}
// Look for the corresponding firewall log:
$log_file = NFW_LOG_DIR . "/nfwlog/firewall_{$statx}.php";
if ( file_exists( $log_file ) ) {
	$fh = @fopen( $log_file, 'r', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
	// Fetch processing times to output benchmarks:
	while (! feof( $fh ) ) {
		$line = fgets( $fh );
		if ( preg_match( '/^\[.+?\]\s+\[(.+?)\]/', $line, $match ) ) {
			if ( $match[1] == 0 ) { continue; }
			if ( $match[1] > $slow ) {
				$slow = $match[1];
			}
			if ( $match[1] < $fast ) {
				$fast = $match[1];
			}
			$speed += $match[1];
			++$tot_bench;
		}
	}
	fclose( $fh );
}

NO_STATS:
list( $tmp, $medium, $high, $critical ) = explode( ':', $nfw_stat );
$medium = (int) $medium;
$high = (int) $high;
$critical = (int) $critical;
$total = $critical + $high + $medium;
if ( $total == 1 ) { $fast = $slow; }

if (! $total ) {
	echo '<div class="notice-warning notice is-dismissible"><p>' . __('You do not have any stats for the selected month yet.', 'ninjafirewall') . '</p></div>';
	$fast = 0;
} else {
	$coef = 100 / $total;
	$critical = round( $critical * $coef, 2 );
	$high = round( $high * $coef, 2 );
	$medium = round( $medium * $coef, 2 );
	// Avoid divide error :
	if ($tot_bench) {
		$speed = round( $speed / $tot_bench, 4 );
	} else {
		$fast = 0;
	}
}

echo '
<script>
	function stat_redir(where) {
		if (where == "") { return false;}
		document.location.href="?page=nfsubstat&statx=" + where;
	}
</script>
	<table class="form-table">
		<tr>
			<th scope="row"><h3>' . __('Monthly stats', 'ninjafirewall') . '</h3></th>
			<td>' . summary_stats_combo( $statx ) . '</td>
		</tr>
		<tr>
			<th scope="row">' . __('Blocked threats', 'ninjafirewall') . '</th>
			<td>' . $total . '</td>
		</tr>
		<tr>
			<th scope="row">' . __('Threats level', 'ninjafirewall') . '</th>
			<td>
				' . __('Critical', 'ninjafirewall') . ' : ' . $critical . '%<br />
				<table bgcolor="#DFDFDF" border="0" cellpadding="0" cellspacing="0" height="14" width="250" style="height:14px;">
					<tr>
						<td width="' . round( $critical) . '%" background="' . plugins_url() . '/ninjafirewall/images/bar-critical.png" style="padding:0px"></td><td width="' . round(100 - $critical) . '%" style="padding:0px"></td>
					</tr>
				</table>
				<br /><br />' . __('High', 'ninjafirewall') . ' : ' . $high . '%<br />
				<table bgcolor="#DFDFDF" border="0" cellpadding="0" cellspacing="0" height="14" width="250" style="height:14px;">
					<tr>
						<td width="' . round( $high) . '%" background="' . plugins_url() . '/ninjafirewall/images/bar-high.png" style="padding:0px"></td><td width="' . round(100 - $high) . '%" style="padding:0px"></td>
					</tr>
				</table>
				<br /><br />' . __('Medium', 'ninjafirewall') . ' : ' . $medium . '%<br />
				<table bgcolor="#DFDFDF" border="0" cellpadding="0" cellspacing="0" height="14" width="250" style="height:14px;">
					<tr>
						<td width="' . round( $medium) . '%" background="' . plugins_url() . '/ninjafirewall/images/bar-medium.png" style="padding:0px;"></td><td width="' . round(100 - $medium) . '%" style="padding:0px;"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th scope="row"><h3>' . __('Benchmarks', 'ninjafirewall') . '</h3></th><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr>
			<th scope="row">' . __('Average time per request', 'ninjafirewall') . '</th>
			<td>' . $speed . 's</td>
		</tr>
		<tr>
			<th scope="row">' . __('Fastest request', 'ninjafirewall') . '</th>
			<td>' . round( $fast, 4) . 's</td>
		</tr>
		<tr>
			<th scope="row">' . __('Slowest request', 'ninjafirewall') . '</th>
			<td>' . round( $slow, 4) . 's</td>
		</tr>
	</table>
</div>';

/* ------------------------------------------------------------------ */
function summary_stats_combo( $statx ) {

	// Find all stat files:
	$avail_logs = array();
	if ( is_dir( NFW_LOG_DIR . '/nfwlog/' ) ) {
		if ( $dh = opendir( NFW_LOG_DIR . '/nfwlog/' ) ) {
			while ( ( $file = readdir( $dh ) ) !== false ) {
				if (preg_match( '/^stats_(\d{4})-(\d\d)\.php$/', $file, $match ) ) {
					$month = ucfirst( date_i18n('F', mktime(0, 0, 0, $match[2], 1, 2000) ) );
					$avail_logs["{$match[1]}-{$match[2]}" ] = "{$month} {$match[1]}";
				}
			}
			closedir( $dh );
		}
	}
	krsort( $avail_logs );

	$ret = '<form>
			<select class="input" name="statx" onChange="return stat_redir(this.value);">
				<option value="">' . __('Select monthly stats to view...', 'ninjafirewall') . '</option>';
   foreach ( $avail_logs as $file => $text ) {
      $ret .= '<option value="'. $file .'"';
      if ($file === $statx ) {
         $ret .= ' selected';
      }
      $ret .= ">{$text}</option>";
   }
   $ret .= '</select>
		</form>';
	return $ret;
}

/* ------------------------------------------------------------------ */
// EOF
