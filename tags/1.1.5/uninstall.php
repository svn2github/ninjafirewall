<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WordPress edition)                                   |
 |                                                                     |
 | (c)2012-2013 NinTechNet                                             |
 | <wordpress@nintechnet.com>                                          |
 +---------------------------------------------------------------------+
 | http://nintechnet.com/                                              |
 +---------------------------------------------------------------------+
 | REVISION: 2013-11-09 23:31:43                                       |
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
 +---------------------------------------------------------------------+
*/

if (! defined('WP_UNINSTALL_PLUGIN') ) { die('Forbidden'); }

if (! session_id() ) { session_start(); }

nfw_uninstall();

/* ================================================================== */

function nfw_uninstall() {

	// Unset the goodguy flag :
	if ( isset( $_SESSION['nfw_goodguy'] ) ) {
		unset( $_SESSION['nfw_goodguy'] );
	}

	define( 'HTACCESS_BEGIN', '# BEGIN NinjaFirewall' );
	define( 'HTACCESS_END', '# END NinjaFirewall' );
	define( 'PHPINI_BEGIN', '; BEGIN NinjaFirewall' );
	define( 'PHPINI_END', '; END NinjaFirewall' );

	// Retrieve installation info :
	global $nfw_install;
	if (! isset( $nfw_install) ) {
		$nfw_install = get_option( 'nfw_install' );
	}

	// clean-up .htaccess :
	if ( file_exists( @$nfw_install['htaccess'] ) ) {
		$htaccess_file = $nfw_install['htaccess'];
	} elseif ( file_exists( ABSPATH . '.htaccess' ) ) {
		$htaccess_file = ABSPATH . '.htaccess';
	} else {
		$htaccess_file = '';
	}

	// Ensure it is writable :
	if (! empty($htaccess_file) && is_writable( $htaccess_file ) ) {
		$data = file_get_contents( $htaccess_file );
		// Find / delete instructions :
		$pos_start = strpos( $data, HTACCESS_BEGIN );
		$pos_end   = strpos( $data, HTACCESS_END );
		if ( ( $pos_start !== FALSE ) && ( $pos_end !== FALSE ) && ( $pos_end > $pos_start ) ) {
			$data = substr( $data, $pos_end + strlen( HTACCESS_END ) );
			file_put_contents( $htaccess_file,  $data );
		}
	}

	// Clean up PHP INI file :
	if ( file_exists( @$nfw_install['phpini'] ) ) {
		if ( is_writable( $nfw_install['phpini'] ) ) {
			$phpini[] = $nfw_install['phpini'];
		}
	}
	if ( file_exists( ABSPATH . 'php.ini' ) ) {
		if ( is_writable( ABSPATH . 'php.ini' ) ) {
			$phpini[] = ABSPATH . 'php.ini';
		}
	}
	if ( file_exists( ABSPATH . 'php5.ini' ) ) {
		if ( is_writable( ABSPATH . 'php5.ini' ) ) {
			$phpini[] = ABSPATH . 'php5.ini';
		}
	}
	if ( file_exists( ABSPATH . '.user.ini' ) ) {
		if ( is_writable( ABSPATH . '.user.ini' ) ) {
			$phpini[] = ABSPATH . '.user.ini';
		}
	}
	foreach( $phpini as $ini ) {
		$data = file_get_contents( $ini );
		$pos_start = strpos( $data, PHPINI_BEGIN );
		$pos_end   = strpos( $data, PHPINI_END );

		if ( ( $pos_start !== FALSE ) && ( $pos_end !== FALSE ) && ( $pos_end > $pos_start ) ) {
			$data = substr( $data, $pos_end + strlen( PHPINI_END ) );
			file_put_contents( $ini,  $data );
		}
	}

	// Delete DB rows :
	delete_option( 'nfw_options' );
	delete_option( 'nfw_rules' );
	delete_option( 'nfw_install' );

}

/* ================================================================== */

// EOF
?>