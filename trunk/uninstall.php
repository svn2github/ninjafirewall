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
 +---------------------------------------------------------------------+ sa
*/

if (! defined('WP_UNINSTALL_PLUGIN') ) {
	exit;
}

if (version_compare(PHP_VERSION, '5.4', '<') ) {
	if (! session_id() ) {
		session_start();
		$_SESSION['nfw_st'] = 1;
	}
} else {
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
		$_SESSION['nfw_st'] = 2;
	}
}

nfw_uninstall();

/* ------------------------------------------------------------------ */

function nfw_uninstall() {

	// Unset the goodguy flag :
	if ( isset( $_SESSION['nfw_goodguy'] ) ) {
		unset( $_SESSION['nfw_goodguy'] );
	}

	define( 'HTACCESS_BEGIN', '# BEGIN NinjaFirewall' );
	define( 'HTACCESS_END', '# END NinjaFirewall' );
	define( 'PHPINI_BEGIN', '; BEGIN NinjaFirewall' );
	define( 'PHPINI_END', '; END NinjaFirewall' );
	define( 'WP_CONFIG_BEGIN', '// BEGIN NinjaFirewall' );
	define( 'WP_CONFIG_END', '// END NinjaFirewall' );

	// Retrieve installation info :
	if ( is_multisite() ) {
		$nfw_install = get_site_option('nfw_install');
	} else {
		$nfw_install = get_option('nfw_install');
	}


	// Clean-up wp-config.php:
	if (! empty( $nfw_install['wp_config'] ) && file_exists( $nfw_install['wp_config'] ) && is_writable( $nfw_install['wp_config'] ) ) {
		$wp_config_content = @file_get_contents( $nfw_install['wp_config'] );
		$wp_config_content = preg_replace( '`\s?'. WP_CONFIG_BEGIN .'.+?'. WP_CONFIG_END .'[^\r\n]*\s?`s' , "\n", $wp_config_content);
		@file_put_contents( $nfw_install['wp_config'], $wp_config_content, LOCK_EX );
	}


	// Clean-up .htaccess :
	if (! empty($nfw_install['htaccess']) && file_exists($nfw_install['htaccess']) ) {
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
		$data = preg_replace( '`\s?'. HTACCESS_BEGIN .'.+?'. HTACCESS_END .'[^\r\n]*\s?`s' , "\n", $data);
		@file_put_contents( $htaccess_file,  $data, LOCK_EX );
	}

	// Clean up PHP INI file :
	if (! empty($nfw_install['phpini']) && file_exists($nfw_install['phpini']) ) {
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
		$data = preg_replace( '`\s?'. PHPINI_BEGIN .'.+?'. PHPINI_END .'[^\r\n]*\s?`s' , "\n", $data);
		@file_put_contents( $ini, $data, LOCK_EX );
	}

	// Remove any scheduled cron job :
	if ( wp_next_scheduled('nfscanevent') ) {
		wp_clear_scheduled_hook('nfscanevent');
	}
	if ( wp_next_scheduled('nfsecupdates') ) {
		wp_clear_scheduled_hook('nfsecupdates');
	}
	if ( wp_next_scheduled('nfdailyreport') ) {
		wp_clear_scheduled_hook('nfdailyreport');
	}
	if ( wp_next_scheduled( 'nfwgccron' ) ) {
		wp_clear_scheduled_hook( 'nfwgccron' );
	}

	// Delete DB rows :
	delete_option('nfw_options');
	delete_option('nfw_rules');
	delete_option('nfw_install');
	delete_option('nfw_tmp');
	if ( is_multisite() ) {
		// Delete those ones too :
		delete_site_option('nfw_options');
		delete_site_option('nfw_rules');
		delete_site_option('nfw_install');
		delete_site_option('nfw_tmp');
	}

	// Clear session flag:
	if ( isset( $_SESSION['nfw_goodguy'] ) ) {
		unset( $_SESSION['nfw_goodguy'] );
	}

}

/* ------------------------------------------------------------------ */
// EOF
