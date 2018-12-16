<?php
/*
Plugin Name: NinjaFirewall (WP Edition)
Plugin URI: https://nintechnet.com/
Description: A true Web Application Firewall to protect and secure WordPress.
Version: 3.8
Author: The Ninja Technologies Network
Author URI: https://nintechnet.com/
License: GPLv3 or later
Network: true
Text Domain: ninjafirewall
Domain Path: /languages
*/

/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - https://nintechnet.com/                            |
 +---------------------------------------------------------------------+
*/
define( 'NFW_ENGINE_VERSION', '3.8' );
/*
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

if (! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

/* ------------------------------------------------------------------ */

// Load (force) our translation files.
$nf_locale = array( 'fr_FR' );
$this_locale = get_locale();
if ( in_array( $this_locale, $nf_locale ) ) {
	if ( file_exists( __DIR__ . "/languages/ninjafirewall-{$this_locale}.mo" ) ) {
		unload_textdomain( 'ninjafirewall' );
		load_textdomain( 'ninjafirewall', __DIR__ . "/languages/ninjafirewall-{$this_locale}.mo" );
	}
}
/* ------------------------------------------------------------------ */

$null = __('A true Web Application Firewall to protect and secure WordPress.', 'ninjafirewall');
define('NFW_NULL_BYTE', 2);
define('NFW_SCAN_BOTS', 531);
define('NFW_ASCII_CTRL', 500);
define('NFW_DOC_ROOT', 510);
define('NFW_WRAPPERS', 520);
define('NFW_OBJECTS', 525);
define('NFW_LOOPBACK', 540);
$err_fw = array(
	1	=> __('Cannot find WordPress configuration file', 'ninjafirewall'),
	2	=>	__('Cannot read WordPress configuration file', 'ninjafirewall'),
	3	=>	__('Cannot retrieve WordPress database credentials', 'ninjafirewall'),
	4	=>	__('Cannot connect to WordPress database', 'ninjafirewall'),
	5	=>	__('Cannot retrieve user options from database (#2)', 'ninjafirewall'),
	6	=>	__('Cannot retrieve user options from database (#3)', 'ninjafirewall'),
	7	=>	__('Cannot retrieve user rules from database (#2)', 'ninjafirewall'),
	8	=>	__('Cannot retrieve user rules from database (#3)', 'ninjafirewall'),
	9	=>	__('The firewall has been disabled from the <a href="admin.php?page=nfsubopt">administration console</a>', 'ninjafirewall'),
	10	=> __('Unable to communicate with the firewall. Please check your settings', 'ninjafirewall'),
	11	=>	__('Cannot retrieve user options from database (#1)', 'ninjafirewall'),
	12	=>	__('Cannot retrieve user rules from database (#1)', 'ninjafirewall'),
	13 => sprintf( __("The firewall cannot access its log and cache folders. If you changed the name of WordPress %s or %s folders, you must define NinjaFirewall's built-in %s constant (see %s for more info)", 'ninjafirewall'), '<code>/wp-content/</code>', '<code>/plugins/</code>', '<code>NFW_LOG_DIR</code>', "<a href='https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja' target='_blank'>Path to NinjaFirewall's log and cache directory</a>"),
);

if (! defined('NFW_LOG_DIR') ) {
	define('NFW_LOG_DIR', WP_CONTENT_DIR);
}
if (! empty($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] != '/' ) {
	$_SERVER['DOCUMENT_ROOT'] = rtrim( $_SERVER['DOCUMENT_ROOT'] , '/' );
}
/* ------------------------------------------------------------------ */

require plugin_dir_path(__FILE__) . 'lib/utils.php';

if (! defined( 'NFW_REMOTE_ADDR') ) {
	nfw_select_ip();
}

add_action( 'nfwgccron', 'nfw_garbage_collector' );

/* ------------------------------------------------------------------ */			//s1:h0

function nfw_activate() {

	// Warn if the user does not have the 'unfiltered_html' capability:
	if (! current_user_can( 'unfiltered_html' ) ) {
		exit( __('You do not have "unfiltered_html" capability. Please enable it in order to run NinjaFirewall (or make sure you do not have "DISALLOW_UNFILTERED_HTML" in your wp-config.php script).', 'ninjafirewall'));
	}

	nf_not_allowed( 'block', __LINE__ );

	global $wp_version;
	if ( version_compare( $wp_version, '3.3', '<' ) ) {
		exit( sprintf( __('NinjaFirewall requires WordPress 3.3 or greater but your current version is %s.', 'ninjafirewall'), $wp_version) );
	}

	if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
		exit( sprintf( __('NinjaFirewall requires PHP 5.3 or greater but your current version is %s.', 'ninjafirewall'), PHP_VERSION) );
	}

	if (! function_exists('mysqli_connect') ) {
		exit( sprintf( __('NinjaFirewall requires the PHP %s extension.', 'ninjafirewall'), '<code>mysqli</code>') );
	}

	if ( ini_get( 'safe_mode' ) ) {
		exit( __('You have SAFE_MODE enabled. Please disable it, it is deprecated as of PHP 5.3.0 (see http://php.net/safe-mode).', 'ninjafirewall'));
	}

	if ( ( is_multisite() ) && (! current_user_can( 'manage_network' ) ) ) {
		exit( __('You are not allowed to activate NinjaFirewall.', 'ninjafirewall') );
	}

	if ( PATH_SEPARATOR == ';' ) {
		exit( __('NinjaFirewall is not compatible with Microsoft Windows.', 'ninjafirewall') );
	}

	if ( $nfw_options = nfw_get_option( 'nfw_options' ) ) {
		$nfw_options['enabled'] = 1;
		nfw_update_option( 'nfw_options', $nfw_options);

		if (! empty($nfw_options['sched_scan']) ) {
			if ($nfw_options['sched_scan'] == 1) {
				$schedtype = 'hourly';
			} elseif ($nfw_options['sched_scan'] == 2) {
				$schedtype = 'twicedaily';
			} else {
				$schedtype = 'daily';
			}
			if ( wp_next_scheduled('nfscanevent') ) {
				wp_clear_scheduled_hook('nfscanevent');
			}
			wp_schedule_event( time() + 3600, $schedtype, 'nfscanevent');
		}
		if (! empty($nfw_options['enable_updates']) ) {
			if ($nfw_options['sched_updates'] == 1) {
				$schedtype = 'hourly';
			} elseif ($nfw_options['sched_updates'] == 2) {
				$schedtype = 'twicedaily';
			} else {
				$schedtype = 'daily';
			}
			if ( wp_next_scheduled('nfsecupdates') ) {
				wp_clear_scheduled_hook('nfsecupdates');
			}
			wp_schedule_event( time() + 15, $schedtype, 'nfsecupdates');
		}
		if (! empty($nfw_options['a_52']) ) {
			if ( wp_next_scheduled('nfdailyreport') ) {
				wp_clear_scheduled_hook('nfdailyreport');
			}
			nfw_get_blogtimezone();
			wp_schedule_event( strtotime( date('Y-m-d 00:00:05', strtotime("+1 day")) ), 'daily', 'nfdailyreport');
		}
		// Re-enable the garbage collector:
		wp_schedule_event( time() + 1800, 'hourly', 'nfwgccron' );

		if ( file_exists( NFW_LOG_DIR . '/nfwlog/cache/bf_conf_off.php' ) ) {
			rename(NFW_LOG_DIR . '/nfwlog/cache/bf_conf_off.php', NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php');
		}
	}
}

register_activation_hook( __FILE__, 'nfw_activate' );

/* ------------------------------------------------------------------ */

function nfw_deactivate() {

	nf_not_allowed( 'block', __LINE__ );

	$nfw_options = nfw_get_option( 'nfw_options' );
	$nfw_options['enabled'] = 0;

	if ( wp_next_scheduled('nfwgccron') ) {
		wp_clear_scheduled_hook('nfwgccron');
	}
	if ( wp_next_scheduled('nfscanevent') ) {
		wp_clear_scheduled_hook('nfscanevent');
	}
	if ( wp_next_scheduled('nfsecupdates') ) {
		wp_clear_scheduled_hook('nfsecupdates');
	}
	if ( wp_next_scheduled('nfdailyreport') ) {
		wp_clear_scheduled_hook('nfdailyreport');
	}
	if ( file_exists( NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php' ) ) {
		rename(NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php', NFW_LOG_DIR . '/nfwlog/cache/bf_conf_off.php');
	}

	nfw_update_option( 'nfw_options', $nfw_options);

}

register_deactivation_hook( __FILE__, 'nfw_deactivate' );

/* ------------------------------------------------------------------ */

function nfw_admin_init() {

	// We must make sure that the current PHP session is always
	// updated even for whitelisted non-admin users:
	nfw_session_start();

	$nfw_options = nfw_get_option( 'nfw_options' );
	$nfw_rules = nfw_get_option( 'nfw_rules' );

	// Post-update adjustment:
	require plugin_dir_path(__FILE__) . 'lib/init_update.php';

	// --------------------------------------------
	// Anything below requires admin authentication
	// --------------------------------------------

	if ( nf_not_allowed(0, __LINE__) ) { return; }

	// Export configuration:
	if ( isset($_POST['nf_export']) ) {
		if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'options_save') ) {
			wp_nonce_ays('options_save');
		}
		$nfwbfd_log = NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php';
		if ( file_exists($nfwbfd_log) ) {
			$bd_data = json_encode( file_get_contents($nfwbfd_log) );
		} else {
			$bd_data = '';
		}
		$data = json_encode($nfw_options) . "\n:-:\n" . json_encode($nfw_rules) . "\n:-:\n" . $bd_data;
		header('Content-Type: text/plain');
		header('Content-Length: '. strlen( $data ) );
		header('Content-Disposition: attachment; filename="nfwp.' . NFW_ENGINE_VERSION . '.dat"');
		echo $data;
		exit;
	}

	// Download File Check modified files list:
	if ( isset($_POST['dlmods']) ) {
		if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'filecheck_save') ) {
			wp_nonce_ays('filecheck_save');
		}
		if (file_exists(NFW_LOG_DIR . '/nfwlog/cache/nfilecheck_diff.php') ) {
			$download_file = NFW_LOG_DIR . '/nfwlog/cache/nfilecheck_diff.php';
		} elseif (file_exists(NFW_LOG_DIR . '/nfwlog/cache/nfilecheck_diff.php.php') ) {
			$download_file = NFW_LOG_DIR . '/nfwlog/cache/nfilecheck_diff.php.php';
		} else {
			wp_nonce_ays('filecheck_save');
		}
		$stat = stat($download_file);
		$data = '== NinjaFirewall File Check (diff)'. "\n";
		$data.= '== ' . site_url() . "\n";
		$data.= '== ' . date_i18n('M d, Y @ H:i:s O', $stat['ctime']) . "\n\n";
		$data.= '[+] = ' . __('New file', 'ninjafirewall') .
					'      [!] = ' . __('Modified file', 'ninjafirewall') .
					'      [-] = ' . __('Deleted file', 'ninjafirewall') .
					"\n\n";
		$fh = fopen($download_file, 'r');
		while (! feof($fh) ) {
			$res = explode('::', fgets($fh) );
			if ( empty($res[1]) ) { continue; }
			if ($res[1] == 'N') {
				$data .= '[+] ' . $res[0] . "\n";
			} elseif ($res[1] == 'D') {
				$data .= '[-] ' . $res[0] . "\n";
			} elseif ($res[1] == 'M') {
				$data .= '[!] ' . $res[0] . "\n";
			}
		}
		fclose($fh);
		$data .= "\n== EOF\n";

		header('Content-Type: text/plain');
		header('Content-Length: '. strlen( $data ) );
		header('Content-Disposition: attachment; filename="'. $_SERVER['SERVER_NAME'] .'_diff.txt"');
		echo $data;
		exit;
	}

	// Download File Check snapshot:
	if ( isset($_POST['dlsnap']) ) {
		if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'filecheck_save') ) {
			wp_nonce_ays('filecheck_save');
		}
		if (file_exists(NFW_LOG_DIR . '/nfwlog/cache/nfilecheck_snapshot.php') ) {
			$stat = stat(NFW_LOG_DIR . '/nfwlog/cache/nfilecheck_snapshot.php');
			$data = '== NinjaFirewall File Check (snapshot)'. "\n";
			$data.= '== ' . site_url() . "\n";
			$data.= '== ' . date_i18n('M d, Y @ H:i:s O', $stat['ctime']) . "\n\n";
			$fh = fopen(NFW_LOG_DIR . '/nfwlog/cache/nfilecheck_snapshot.php', 'r');
			while (! feof($fh) ) {
				$res = explode('::', fgets($fh) );
				if (! empty($res[0][0]) && $res[0][0] == '/') {
					$data .= $res[0] . "\n";
				}
			}
			fclose($fh);
			$data .= "\n== EOF\n";
			header('Content-Type: text/plain');
			header('Content-Length: '. strlen( $data ) );
			header('Content-Disposition: attachment; filename="'. $_SERVER['SERVER_NAME'] .'_snapshot.txt"');
			echo $data;
			exit;
		} else {
			wp_nonce_ays('filecheck_save');
		}
	}

	// Updates e-mail alert?
	if ( defined( 'NFW_ALERT' ) ) {
		nfw_check_emailalert();
	}

	// Run the garbage collector if needed (note that there's already
	// a hourly cron job for that purpose, but this call is only used
	// in the event where the admin disabled WP-Cron):
	nfw_garbage_collector();

	// Applies to admin only (unlike the WP+ Edition):
	if (! empty( $nfw_options['wl_admin'] ) ) {
		$_SESSION['nfw_goodguy'] = true;
		if (! empty( $nfw_options['bf_enable'] ) && ! empty( $nfw_options['bf_rand'] ) ) {
			$_SESSION['nfw_bfd'] = $nfw_options['bf_rand'];
		}
		return;
	}
	if ( isset( $_SESSION['nfw_goodguy'] ) ) {
		unset( $_SESSION['nfw_goodguy'] );
	}
}

add_action('admin_init', 'nfw_admin_init' );

/* ------------------------------------------------------------------ */

function nfw_login_hook( $user_login, $user ) {

	// Check if the user is an admin and if we must whitelist them:

	nfw_session_start();

	$nfw_options = nfw_get_option( 'nfw_options' );

	if ( empty( $nfw_options['enabled'] ) ) { return; }

	if ( empty( $user->roles[0] ) ) {
		$whoami = '';
		$admin_flag = 1;
	} elseif ( $user->roles[0] == 'administrator' ) {
		$whoami = 'administrator';
		$admin_flag = 2;
	} else {
		$whoami = $user->roles[0];
		$admin_flag = 0;
	}

	if (! empty($nfw_options['a_0']) ) {
		if ( ( ( $nfw_options['a_0'] == 1) && ( $admin_flag )  ) ||	( $nfw_options['a_0'] == 2 ) ) {
			nfw_send_loginemail( $user_login, $whoami );
			if (! empty($nfw_options['a_41']) ) {
				nfw_log2('Logged in user', $user_login .' ('. $whoami .')', 6, 0);
			}
		}
	}

	if (! empty( $nfw_options['wl_admin']) ) {
		if ( ( $nfw_options['wl_admin'] == 1 && $admin_flag == 2 ) || ( $nfw_options['wl_admin'] == 2 ) ) {
			$_SESSION['nfw_goodguy'] = $nfw_options['wl_admin'];
			return;
		}
	}

	if ( isset( $_SESSION['nfw_goodguy'] ) ) {
		unset( $_SESSION['nfw_goodguy'] );
	}
}

add_action( 'wp_login', 'nfw_login_hook', 10, 2 );

/* ------------------------------------------------------------------ */

function nfw_send_loginemail( $user_login, $whoami ) {

	$nfw_options = nfw_get_option( 'nfw_options' );

	if ( ( is_multisite() ) && ( $nfw_options['alert_sa_only'] == 2 ) ) {
		$recipient = get_option('admin_email');
	} else {
		$recipient = $nfw_options['alert_email'];
	}

	$subject = '[NinjaFirewall] ' . __('Alert: WordPress console login', 'ninjafirewall');
	// Show current blog, not main site (multisite):
	$url = __('-Blog:', 'ninjafirewall') .' '. home_url('/') . "\n\n";
	if (! empty( $whoami ) ) {
		$whoami = " ($whoami)";
	}
	$message = __('Someone just logged in to your WordPress admin console:', 'ninjafirewall') . "\n\n".
				__('-User:', 'ninjafirewall') .' '. $user_login . $whoami . "\n" .
				__('-IP:', 'ninjafirewall') .' '. NFW_REMOTE_ADDR . "\n" .
				__('-Date:', 'ninjafirewall') .' '. ucfirst(date_i18n('F j, Y @ H:i:s')) . ' (UTC '. date('O') . ")\n" .
				$url .
				'NinjaFirewall (WP Edition) - https://nintechnet.com/' . "\n" .
				__('Support forum', 'ninjafirewall') . ': http://wordpress.org/support/plugin/ninjafirewall' . "\n";
	wp_mail( $recipient, $subject, $message );

}
/* ------------------------------------------------------------------ */

function nfw_logout_hook() {

	nfw_session_start();

	if ( isset( $_SESSION['nfw_goodguy'] ) ) {
		unset( $_SESSION['nfw_goodguy'] );
	}
	if (isset( $_SESSION['nfw_livelog'] ) ) {
		unset( $_SESSION['nfw_livelog'] );
	}
}

add_action( 'wp_logout', 'nfw_logout_hook' );

/* ------------------------------------------------------------------ */

function is_nfw_enabled() {

	$nfw_options = nfw_get_option( 'nfw_options' );

	if (! defined('NFW_STATUS') ) {
		define('NF_DISABLED', 10);
		return;
	}

	if ( isset($nfw_options['enabled']) && $nfw_options['enabled'] == '0' ) {
		define('NF_DISABLED', 9);
		return;
	}

	if (NFW_STATUS == 21 || NFW_STATUS == 22 || NFW_STATUS == 23) {
		define('NF_DISABLED', 10);
		return;
	}

	if (NFW_STATUS == 20) {
		define('NF_DISABLED', 0);
		return;
	}

	define('NF_DISABLED', NFW_STATUS);
	return;

}

/* ------------------------------------------------------------------ */

function ninjafirewall_admin_menu() {

	if ( nf_not_allowed( 0, __LINE__ ) ) { return; }

	if (! empty($_REQUEST['nfw_act']) && $_REQUEST['nfw_act'] == 99) {
		if ( empty($_GET['nfwnonce']) || ! wp_verify_nonce($_GET['nfwnonce'], 'show_phpinfo') ) {
			wp_nonce_ays('show_phpinfo');
		}
		phpinfo(33);
		exit;
	}

	$message = '<br /><br /><br /><br /><center>' .
				sprintf( __('Sorry %s, your request cannot be processed.', 'ninjafirewall'), '<b>%%REM_ADDRESS%%</b>') .
				'<br />' . __('For security reasons, it was blocked and logged.', 'ninjafirewall') .
				'<br /><br />%%NINJA_LOGO%%<br /><br />' .
				__('If you believe this was an error please contact the<br />webmaster and enclose the following incident ID:', 'ninjafirewall') .
				'<br /><br />[ <b>#%%NUM_INCIDENT%%</b> ]</center>';

	define( 'NFW_DEFAULT_MSG', $message );

	if (! defined('NF_DISABLED') ) {
		is_nfw_enabled();
	}

	if (NF_DISABLED == 10) {
		add_menu_page( 'NinjaFirewall', 'NinjaFirewall', 'manage_options',
			'NinjaFirewall', 'nf_menu_install',	plugins_url( '/images/nf_icon.png', __FILE__ )
		);
		add_submenu_page( 'NinjaFirewall', __('Installation', 'ninjafirewall'), __('Installation', 'ninjafirewall'), 'manage_options',
			'NinjaFirewall', 'nf_menu_install' );
		return;
	}

	add_menu_page( 'NinjaFirewall', 'NinjaFirewall', 'manage_options',
		'NinjaFirewall', 'nf_sub_main',	plugins_url( '/images/nf_icon.png', __FILE__ )
	);

	global $menu_hook;

	require_once plugin_dir_path(__FILE__) . 'lib/help.php';

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Overview', 'ninjafirewall'), __('Overview', 'ninjafirewall'), 'manage_options',
		'NinjaFirewall', 'nf_sub_main' );
	add_action( 'load-' . $menu_hook, 'help_nfsubmain' );

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Statistics', 'ninjafirewall'), __('Statistics', 'ninjafirewall'), 'manage_options',
		'nfsubstat', 'nf_sub_statistics' );
	add_action( 'load-' . $menu_hook, 'help_nfsubstat' );

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Firewall Options', 'ninjafirewall'), __('Firewall Options', 'ninjafirewall'), 'manage_options',
		'nfsubopt', 'nf_sub_options' );
	add_action( 'load-' . $menu_hook, 'help_nfsubopt' );

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Firewall Policies', 'ninjafirewall'), __('Firewall Policies', 'ninjafirewall'), 'manage_options',
		'nfsubpolicies', 'nf_sub_policies' );
	add_action( 'load-' . $menu_hook, 'help_nfsubpolicies' );

	$menu_hook = add_submenu_page( 'NinjaFirewall',  __('NinjaFirewall: File Guard', 'ninjafirewall'), __( 'File Guard', 'ninjafirewall'), 'manage_options',
		'nfsubfileguard', 'nf_sub_fileguard' );
	add_action( 'load-' . $menu_hook, 'help_nfsubfileguard' );

	$menu_hook = add_submenu_page( 'NinjaFirewall',  __('NinjaFirewall: File Check', 'ninjafirewall'),  __('File Check', 'ninjafirewall'), 'manage_options',
		'nfsubfilecheck', 'nf_sub_filecheck' );
	add_action( 'load-' . $menu_hook, 'help_nfsubfilecheck' );

	$nscan_options = get_option( 'nscan_options' );
	if ( defined('NSCAN_NAME') && defined('NSCAN_SLUG') && ! empty( $nscan_options['scan_nfwpintegration'] ) ) {
		$menu_hook = add_submenu_page( 'NinjaFirewall', NSCAN_NAME, NSCAN_NAME, 'manage_options', NSCAN_NAME, 'nscan_main_menu' );
		require_once dirname( __DIR__ ).'/'. NSCAN_SLUG .'/lib/help.php';
		add_action( 'load-' . $menu_hook, 'nscan_help' );
	} else {
		$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Anti-Malware', 'ninjafirewall'), __('Anti-Malware', 'ninjafirewall'), 'manage_options',
		'nfsubmalwarescan', 'nf_sub_malwarescan' );
	}

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Network', 'ninjafirewall'), __('Network', 'ninjafirewall'), 'manage_network',
		'nfsubnetwork', 'nf_sub_network' );
	add_action( 'load-' . $menu_hook, 'help_nfsubnetwork' );

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Event Notifications', 'ninjafirewall'), __('Event Notifications', 'ninjafirewall'), 'manage_options',
		'nfsubevent', 'nf_sub_event' );
	add_action( 'load-' . $menu_hook, 'help_nfsubevent' );

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Log-in Protection', 'ninjafirewall'), __('Login Protection', 'ninjafirewall'), 'manage_options',
		'nfsubloginprot', 'nf_sub_loginprot' );
	add_action( 'load-' . $menu_hook, 'help_nfsublogin' );

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Firewall Log', 'ninjafirewall'), __('Firewall Log', 'ninjafirewall'), 'manage_options',
		'nfsublog', 'nf_sub_log' );
	add_action( 'load-' . $menu_hook, 'help_nfsublog' );

	$menu_hook = add_submenu_page( 'NinjaFirewall',  __('NinjaFirewall: Live Log', 'ninjafirewall'),  __('Live Log', 'ninjafirewall'), 'manage_options',
		'nfsublive', 'nf_sub_live' );
	add_action( 'load-' . $menu_hook, 'help_nfsublivelog' );

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Rules Editor', 'ninjafirewall'), __('Rules Editor', 'ninjafirewall'), 'manage_options',
		'nfsubedit', 'nf_sub_editor' );
	add_action( 'load-' . $menu_hook, 'help_nfsubedit' );

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: Rules Update', 'ninjafirewall'), __('Rules Update', 'ninjafirewall'), 'manage_options',
		'nfsubupdates', 'nf_sub_updates' );
	add_action( 'load-' . $menu_hook, 'help_nfsubupdates' );

	$menu_hook = add_submenu_page( 'NinjaFirewall', 'NinjaFirewall: WP+ Edition', '<b style="color:#fcdc25">WP+ Edition</b>', 'manage_options',
		'nfsubwplus', 'nf_sub_wplus' );

	$menu_hook = add_submenu_page( 'NinjaFirewall', __('NinjaFirewall: About', 'ninjafirewall'), __('About...', 'ninjafirewall'), 'manage_options',
		'nfsubabout', 'nf_sub_about' );

}
// Must load before NinjaScanner (11):
if (! is_multisite() )  {
	add_action( 'admin_menu', 'ninjafirewall_admin_menu', 10 );
} else {
	add_action( 'network_admin_menu', 'ninjafirewall_admin_menu', 10 );
}

/* ------------------------------------------------------------------ */

function nf_admin_bar_status() {

	if (! current_user_can( 'manage_options' ) ) {
		return;
	}

	$nfw_options = nfw_get_option( 'nfw_options' );
	if ( @$nfw_options['nt_show_status'] != 1 && ! current_user_can('manage_network') ) {
		return;
	}

	if (! defined('NF_DISABLED') ) {
		is_nfw_enabled();
	}
	if (NF_DISABLED) { return; }

	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array(
		'id'    => 'nfw_ntw1',
		'title' => '<img src="' . plugins_url() . '/ninjafirewall/images/ninjafirewall_20.png" ' .
				'style="vertical-align:middle;margin-right:5px" />',
	) );

	if ( current_user_can( 'manage_network' ) ) {
		$wp_admin_bar->add_menu( array(
			'parent' => 'nfw_ntw1',
			'id'     => 'nfw_ntw2',
			'title'  => __( 'NinjaFirewall Settings', 'ninjafirewall'),
			'href'   => network_admin_url() . 'admin.php?page=NinjaFirewall',
		) );
	} else {
		if ( defined('NFW_STATUS') ) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'nfw_ntw1',
				'id'     => 'nfw_ntw2',
				'title'  => __( 'NinjaFirewall is enabled', 'ninjafirewall'),
			) );
		}
	}
}

if ( is_multisite() )  {
	add_action('admin_bar_menu', 'nf_admin_bar_status', 95);
}

/* ------------------------------------------------------------------ */

function nf_menu_install() {

	nf_not_allowed( 'block', __LINE__ );

	require_once plugin_dir_path(__FILE__) . 'install.php';
}

/* ------------------------------------------------------------------ */

function nf_sub_main() {

	// Main menu (Overview) :
	require plugin_dir_path(__FILE__) . 'lib/overview.php';

}

/* ------------------------------------------------------------------ */

function nf_sub_statistics() {

	require plugin_dir_path(__FILE__) . 'lib/statistics.php';

}

/* ------------------------------------------------------------------ */

function nf_sub_options() { // i18n

	require plugin_dir_path(__FILE__) . 'lib/firewall_options.php';

}

/* ------------------------------------------------------------------ */

function nf_sub_policies() {

	// Firewall Policies menu :
	require plugin_dir_path(__FILE__) . 'lib/firewall_policies.php';

}

/* ------------------------------------------------------------------ */

function nf_sub_fileguard() {

	// File Guard menu:
	require plugin_dir_path(__FILE__) . 'lib/file_guard.php';

}

/* ------------------------------------------------------------------ */

function nf_sub_network() {

	// Network menu (multi-site only) :
	require plugin_dir_path(__FILE__) . 'lib/network.php';

}

/* ------------------------------------------------------------------ */

function nf_sub_filecheck() {

	require plugin_dir_path(__FILE__) . 'lib/file_check.php';

}

add_action('nfscanevent', 'nfscando');

function nfscando() {

	define('NFSCANDO', 1);
	nf_sub_filecheck();
}

/* ------------------------------------------------------------------ */

function nf_sub_malwarescan() {

	require plugin_dir_path(__FILE__) . 'lib/anti_malware.php';

}

/* ------------------------------------------------------------------ */

function nf_sub_event() {

	require plugin_dir_path(__FILE__) . 'lib/event_notifications.php';

}

add_action('shutdown', 'nf_check_dbdata', 1);

add_action('nfdailyreport', 'nfdailyreportdo');

function nfdailyreportdo() {
	define('NFREPORTDO', 1);
	nf_sub_event();
}

/* ------------------------------------------------------------------ */

function nf_sub_log() {

	require plugin_dir_path(__FILE__) . 'lib/firewall_log.php';

}
/* ------------------------------------------------------------------ */

function nf_sub_live() {

	require plugin_dir_path(__FILE__) . 'lib/live_log.php';

}
/* ------------------------------------------------------------------ */

function nf_sub_loginprot() {

	require plugin_dir_path(__FILE__) . 'lib/login_protection.php';

}

/* ------------------------------------------------------------------ */

function nfw_log2($loginfo, $logdata, $loglevel, $ruleid) { // i18n

	// Write incident to the firewall log :
	require plugin_dir_path(__FILE__) . 'lib/nfw_log.php';

}

/* ------------------------------------------------------------------ */

function nf_sub_editor() {

	// Rules Editor menu :
	require plugin_dir_path(__FILE__) . 'lib/rules_editor.php';

}

/* ------------------------------------------------------------------ */

function nf_sub_updates() {

	require plugin_dir_path(__FILE__) . 'lib/rules_update.php';

}

add_action('nfsecupdates', 'nfupdatesdo');

function nfupdatesdo() {
	define('NFUPDATESDO', 1);
	nf_sub_updates();
}

/* ------------------------------------------------------------------ */

function nf_sub_wplus() {

	require plugin_dir_path(__FILE__) . 'lib/wpplus.php';
}

/* ------------------------------------------------------------------ */

function nf_sub_about() {

	require plugin_dir_path(__FILE__) . 'lib/about.php';

}
/* ------------------------------------------------------------------ */

function ninjafirewall_settings_link( $links ) {

	// Check if access is restricted to one or more specific admins
	// See: https://blog.nintechnet.com/restricting-access-to-ninjafirewall-wp-edition-settings/
	if ( nf_not_allowed( 0, __LINE__ ) ) {
		unset( $links );
		$links[] = __('Access Restricted', 'ninjafirewall');
		return $links;
	}

	if ( is_multisite() ) {	$net = 'network/'; } else { $net = '';	}

	$links[] = '<a href="'. get_admin_url(null, $net .'admin.php?page=NinjaFirewall') .'">'. __('Settings', 'ninjafirewall') .'</a>';
	$links[] = '<a href="https://nintechnet.com/ninjafirewall/wp-edition/?pricing" target="_blank">'. __('Upgrade to Premium', 'ninjafirewall'). '</a>';
	$links[] = '<a href="https://wordpress.org/support/view/plugin-reviews/ninjafirewall?rate=5#postform" target="_blank">'. __('Rate it!', 'ninjafirewall'). '</a>';
	unset( $links['edit'] );
   return $links;

}

if ( is_multisite() ) {
	add_filter( 'network_admin_plugin_action_links_' . plugin_basename(__FILE__), 'ninjafirewall_settings_link' );
} else {
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'ninjafirewall_settings_link' );
}

/* ------------------------------------------------------------------ */

function nfw_get_blogtimezone() {

	$tzstring = get_option( 'timezone_string' );
	if (! $tzstring ) {
		$tzstring = ini_get( 'date.timezone' );
		if (! $tzstring ) {
			$tzstring = 'UTC';
		}
	}
	date_default_timezone_set( $tzstring );
}
/* ------------------------------------------------------------------ */

function nfw_dashboard_widgets() {

	require plugin_dir_path(__FILE__) . 'lib/dashboard_widget.php';

}

if ( is_multisite() ) {
	add_action( 'wp_network_dashboard_setup', 'nfw_dashboard_widgets' );
} else {
	add_action( 'wp_dashboard_setup', 'nfw_dashboard_widgets' );
}

/* ------------------------------------------------------------------ */

function nf_not_allowed($block, $line = 0) {

	if ( is_multisite() ) {
		if ( current_user_can('manage_network') ) {
			return false;
		}
	} else {
		if ( current_user_can('manage_options') &&
		     current_user_can('unfiltered_html') ) {
			// Check if that admin is allowed to use NinjaFirewall
			// (see NFW_ALLOWED_ADMIN at http://nin.link/nfwaa ):
			if ( defined('NFW_ALLOWED_ADMIN') ) {
				$current_user = wp_get_current_user();
				$admins = explode(',', NFW_ALLOWED_ADMIN);
				foreach ($admins as $admin) {
					if ( trim($admin) == $current_user->user_login ) {
						return false;
					}
				}
			} else {
				return false;
			}
		}
	}

	if ( $block ) {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			// Format text for WP-CLI:
			WP_CLI::error(
				sprintf( __('You are not allowed to perform this task (%s).', 'ninjafirewall'), $line)
			);
		} else {
			die( '<br /><br /><br /><div class="error notice is-dismissible"><p>' .
				sprintf( __('You are not allowed to perform this task (%s).', 'ninjafirewall'), $line) .
				'</p></div>' );
		}
	}
	return true;
}

/* ------------------------------------------------------------------ */
// EOF //
