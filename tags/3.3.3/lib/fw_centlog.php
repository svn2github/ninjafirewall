<?php
// +---------------------------------------------------------------------+
// | NinjaFirewall (WP Edition)                                          |
// |                                                                     |
// | (c) NinTechNet - http://nintechnet.com/                             |
// +---------------------------------------------------------------------+
// | This program is free software: you can redistribute it and/or       |
// | modify it under the terms of the GNU General Public License as      |
// | published by the Free Software Foundation, either version 3 of      |
// | the License, or (at your option) any later version.                 |
// |                                                                     |
// | This program is distributed in the hope that it will be useful,     |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of      |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       |
// | GNU General Public License for more details.                        |
// +---------------------------------------------------------------------+ sa

if (! isset( $nfw_['nfw_options']['enabled']) ) {
	header('HTTP/1.1 404 Not Found');
	header('Status: 404 Not Found');
}

/* ------------------------------------------------------------------ */
function fw_centlog() {

	global $nfw_;

	$pubkey = explode( ':', $nfw_['nfw_options']['clogs_pubkey'], 2 );

	if ( isset( $pubkey[1]) &&  $pubkey[1] != '*' ) {
		nfw_check_ip();

		if ( NFW_REMOTE_ADDR != $pubkey[1] ) {
			nfw_log('Centralized logging: IP not allowed', NFW_REMOTE_ADDR, 6, 0);
			fw_centlog_die();
		}
	}

	if ( empty( $pubkey[0] ) || sha1( $_POST['clogs_req'] ) !== $pubkey[0] ) {
		nfw_log('Centralized logging: public key rejected', NFW_REMOTE_ADDR, 6, 0);
		fw_centlog_die();
	}

	if (! $tzstring = ini_get('date.timezone') ) {
		$tzstring = 'UTC';
	}
	date_default_timezone_set($tzstring);
	$cur_month = date('Y-m');
	$log_file = $nfw_['log_dir']. '/firewall_' . $cur_month . '.php';

	if (! file_exists( $log_file ) ) {
		exit('1:');
	}

	$data = file( $log_file, FILE_SKIP_EMPTY_LINES );
	if ( $data === false ) {
		exit('2:');
	}

	echo '0:~*~:' . base64_encode( json_encode( $data ) );
	exit;
}

/* ------------------------------------------------------------------ */

function fw_centlog_die() {

	header('HTTP/1.1 406 Not Acceptable');
	header('Status: 406 Not Acceptable');

}

/* ------------------------------------------------------------------ */
// EOF
