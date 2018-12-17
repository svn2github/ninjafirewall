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

if (! isset( $nfw_['nfw_options']['enabled']) ) {
	header('HTTP/1.1 404 Not Found');
	header('Status: 404 Not Found');
	exit;
}

/* ------------------------------------------------------------------ */

function fw_fileguard() {

	global $nfw_;

	// Look for exclusion :
	if ( empty($nfw_['nfw_options']['fg_exclude']) || ! @preg_match( "`{$nfw_['nfw_options']['fg_exclude']}`", $_SERVER['SCRIPT_FILENAME'] ) ) {
		// Stat() the requested script :
		if ( $nfw_['nfw_options']['fg_stat'] = stat( $_SERVER['SCRIPT_FILENAME'] ) ) {
			// Was it created/modified lately ?
			if ( time() - $nfw_['nfw_options']['fg_mtime'] * 3660 < $nfw_['nfw_options']['fg_stat']['ctime'] ) {
				// Did we check it already ?
				if (! file_exists( $nfw_['log_dir'] . '/cache/fg_' . $nfw_['nfw_options']['fg_stat']['ino'] . '.php' ) ) {
					// Log it :
					nfw_log('Access to a script modified/created less than ' . $nfw_['nfw_options']['fg_mtime'] . ' hour(s) ago', $_SERVER['SCRIPT_FILENAME'], 6, 0);
					// We need to alert the admin :
					if (! $nfw_['nfw_options']['tzstring'] = ini_get('date.timezone') ) {
						$nfw_['nfw_options']['tzstring'] = 'UTC';
					}
					date_default_timezone_set($nfw_['nfw_options']['tzstring']);
					$nfw_['nfw_options']['m_headers'] = 'From: "NinjaFirewall" <postmaster@'. $_SERVER['SERVER_NAME'] . ">\r\n";
					$nfw_['nfw_options']['m_headers'] .= "Content-Transfer-Encoding: 7bit\r\n";
					$nfw_['nfw_options']['m_headers'] .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
					$nfw_['nfw_options']['m_headers'] .= "MIME-Version: 1.0\r\n";
					$nfw_['nfw_options']['m_subject'] = '[NinjaFirewall] Alert: File Guard detection';
					$nfw_['nfw_options']['m_msg'] = 	'Someone accessed a script that was modified or created less than ' .
						$nfw_['nfw_options']['fg_mtime'] . ' hour(s) ago:' . "\n\n".
						'SERVER_NAME: ' . $_SERVER['SERVER_NAME'] . "\n" .
						'USER IP: ' . NFW_REMOTE_ADDR . "\n" .
						'SCRIPT_FILENAME: ' . $_SERVER['SCRIPT_FILENAME'] . "\n" .
						'REQUEST_URI: ' . $_SERVER['REQUEST_URI'] . "\n" .
						'Last changed on: ' . date('F j, Y @ H:i:s', $nfw_['nfw_options']['fg_stat']['ctime'] ) . ' (UTC '. date('O') . ")\n\n" .
						'NinjaFirewall (WP Edition) - https://nintechnet.com/' . "\n" .
						'Support forum: http://wordpress.org/support/plugin/ninjafirewall' . "\n";
					mail( $nfw_['nfw_options']['alert_email'], $nfw_['nfw_options']['m_subject'], $nfw_['nfw_options']['m_msg'], $nfw_['nfw_options']['m_headers']);
					// Remember it so that we don't spam the admin each time the script is requested :
					touch($nfw_['log_dir'] . '/cache/fg_' . $nfw_['nfw_options']['fg_stat']['ino'] . '.php');
				}
				// Undocumented: if 'NFW_FG_BLOCK' is defined
				// in the .htninja, we block the request:
				if ( defined('NFW_FG_BLOCK') ) {
					nfw_log('File Guard: blocked request', $_SERVER['SCRIPT_FILENAME'], 6, 0);
					nfw_block();
				}
			}
		}
	}
}

/* ------------------------------------------------------------------ */
// EOF
