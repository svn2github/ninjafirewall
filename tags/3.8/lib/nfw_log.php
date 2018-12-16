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

$nfw_options = nfw_get_option( 'nfw_options' );

if (! empty($nfw_options['debug']) ) {
	$num_incident = '0000000';
	$loglevel = 7;
	$http_ret_code = '200';
} else {
	$num_incident = mt_rand(1000000, 9000000);
	$http_ret_code = $nfw_options['ret_code'];
}
  if (strlen($logdata) > 200) { $logdata = mb_substr($logdata, 0, 200, 'utf-8') . '...'; }
$res = '';
$string = str_split($logdata);
foreach ( $string as $char ) {
	if ( ( ord($char) < 32 ) || ( ord($char) > 126 ) ) {
		$res .= '%' . bin2hex($char);
	} else {
		$res .= $char;
	}
}
nfw_get_blogtimezone();

$cur_month = date('Y-m');
$stat_file = NFW_LOG_DIR . '/nfwlog/stats_' . $cur_month . '.php';
$log_file  = NFW_LOG_DIR . '/nfwlog/firewall_' . $cur_month . '.php';

if ( file_exists( $stat_file ) ) {
	$nfw_stat = file_get_contents( $stat_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
} else {
	$nfw_stat = '0:0:0:0:0:0:0:0:0:0';
}
$nfw_stat_arr = explode(':', $nfw_stat . ':');
++$nfw_stat_arr[$loglevel];
@file_put_contents( $stat_file, $nfw_stat_arr[0] . ':' . $nfw_stat_arr[1] . ':' .
	$nfw_stat_arr[2] . ':' . $nfw_stat_arr[3] . ':' . $nfw_stat_arr[4] . ':' .
	$nfw_stat_arr[5] . ':' . $nfw_stat_arr[6] . ':' . $nfw_stat_arr[7] . ':' .
	$nfw_stat_arr[8] . ':' . $nfw_stat_arr[9], LOCK_EX );

if ( $loglevel == 4 ) {
	$SCRIPT_NAME = '-';
	$REQUEST_METHOD = 'N/A';
	$REMOTE_ADDR = '0.0.0.0';
	$loglevel = 6;
} else {
	$SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
	$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
	$REMOTE_ADDR = NFW_REMOTE_ADDR;
}

if (! file_exists($log_file) ) {
	$tmp = '<?php exit; ?>' . "\n";
} else {
	$tmp = '';
}

// Which encoding to use?
if ( defined('NFW_LOG_ENCODING') ) {
	if ( NFW_LOG_ENCODING == 'b64' ) {
		$encoding = '[b64:' . base64_encode( $res ) . ']';
	} elseif ( NFW_LOG_ENCODING == 'none' ) {
		$encoding = '[' . $res . ']';
	} else {
		$unp = unpack('H*', $res);
		$encoding = '[hex:' . array_shift( $unp )  . ']';
	}
} else {
	$unp = unpack('H*', $res);
	$encoding = '[hex:' . array_shift( $unp )  . ']';
}

@file_put_contents( $log_file,
	$tmp . '[' . time() . '] ' . '[0] ' .
	'[' . $_SERVER['SERVER_NAME'] . '] ' . '[#' . $num_incident . '] ' .
	'[' . $ruleid . '] ' .
	'[' . $loglevel . '] ' . '[' . nfw_anonymize_ip2( $REMOTE_ADDR ) . '] ' .
	'[' . $http_ret_code . '] ' . '[' . $REQUEST_METHOD . '] ' .
	'[' . $SCRIPT_NAME . '] ' . '[' . $loginfo . '] ' .
	$encoding . "\n", FILE_APPEND | LOCK_EX);

// ---------------------------------------------------------------------

function nfw_anonymize_ip2( $ip ) {

	$nfw_options = nfw_get_option( 'nfw_options' );

	if (! empty( $nfw_options['anon_ip'] ) && filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
		return substr( $ip, 0, -3 ) .'xxx';
	}

	return $ip;
}

// ---------------------------------------------------------------------
// EOF
