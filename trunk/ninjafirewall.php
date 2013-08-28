<?php
/*
Plugin Name: NinjaFirewall (WP edition)
Plugin URI: http://NinjaFirewall.com/
Description: A true web application firewall for WordPress.
Version: 1.0.4
Author: The Ninja Technologies Network
Author URI: http://NinTechNet.com/
License: GPLv2 or later
*/

/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WordPress edition)                                   |
 |                                                                     |
 | (c)2012-2013 NinTechNet                                             |
 | <wordpress@nintechnet.com>                                          |
 +---------------------------------------------------------------------+
 | http://nintechnet.com/                                              |
 +---------------------------------------------------------------------+
 | REVISION: 2013-08-28 01:40:04                                       |
 +---------------------------------------------------------------------+
*/
define( 'NFW_ENGINE_VERSION', '1.0.4' );
define( 'NFW_RULES_VERSION',  '20130621' );
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

if (! session_id() ) { session_start(); }

/* ================================================================== */

function nfw_activate() {

	// Install/activate NinjaFirewall :

	// We need at least WP 3.3 :
	global $wp_version;
	if ( version_compare( $wp_version, '3.3', '<' ) ) {
		exit( "NinjaFirewall requires <strong>WordPress 3.3 or " . "
		greater</strong> but your current version is " . $wp_version );
	}

	// We need at least PHP 5.3 :
	if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
		exit( "NinjaFirewall requires <strong>PHP 5.3 or greater</strong> " . "
		but your current version is " . PHP_VERSION );
	}

	// Yes, there are still some people who have SAFE_MODE enabled with
	// PHP 5.3 ! We must check that right away otherwise the user may lock
	// himself/herself out of the site as soon as NinjaFirewall will be
	// activated :
	if ( ini_get( 'safe_mode' ) ) {
		exit( "You have SAFE_MODE enabled. Please <strong>disable it</strong>, " .
		"it is deprecated as of PHP 5.3.0 (see http://php.net/safe-mode)" );
	}

	// No support for multisite installation (not yet) :
	if ( is_multisite() ) {
		exit( "NinjaFirewall is not compatible with WordPress Multisite installations." );
	}

	// We don't do Windows :
	if ( PATH_SEPARATOR == ';' ) {
		exit( "NinjaFirewall is not compatible with Windows." );
	}

	// If already installed/setup, just enable the firewall... :
	if ( $nfw_options = get_option( 'nfw_options' ) ) {
		$nfw_options['enabled'] = 1;
		update_option( 'nfw_options', $nfw_options);

		// ...and whitelist the admin if needed :
		if (! empty( $nfw_options['wl_admin']) ) {
			$_SESSION['nfw_goodguy'] = true;
		}
	}
}

register_activation_hook( __FILE__, 'nfw_activate' );

/* ================================================================== */

function nfw_deactivate() {

	// Disable the firewall (NinjaFirewall will keep running
	// in the background but will not do anything) :
	$nfw_options = get_option( 'nfw_options' );
	$nfw_options['enabled'] = 0;
	update_option( 'nfw_options', $nfw_options);

}

register_deactivation_hook( __FILE__, 'nfw_deactivate' );

/* ================================================================== */

function nfw_upgrade() {

	// Only used when upgrading NinjaFirewall and sending alerts:

	global $nfw_options;
	global $nfw_rules;
	$is_update = 0;

	if (! isset( $nfw_options ) ) {
		$nfw_options = get_option( 'nfw_options' );
	}
	if (! isset( $nfw_rules) ) {
		$nfw_rules = get_option( 'nfw_rules' );
	}

	// update engine version number if needed :
	if ( ( $nfw_options ) && ( $nfw_options['engine_version'] != NFW_ENGINE_VERSION ) ) {
		$nfw_options['engine_version'] = NFW_ENGINE_VERSION;
		$is_update = 1;

		// v1.0.4 update :
		if ( empty( $nfw_options['alert_email']) ) {
			$nfw_options['a_0']  = 1; $nfw_options['a_11'] = 1;
			$nfw_options['a_12'] = 1; $nfw_options['a_13'] = 0;
			$nfw_options['a_14'] = 0; $nfw_options['a_15'] = 1;
			$nfw_options['a_16'] = 0; $nfw_options['a_21'] = 1;
			$nfw_options['a_22'] = 1; $nfw_options['a_23'] = 0;
			$nfw_options['a_24'] = 0; $nfw_options['a_31'] = 1;
			$nfw_options['alert_email'] = get_option('admin_email');
		}
	}

	// do we need to update rules as well ?
	if ( ( $nfw_options ) && ( $nfw_options['rules_version'] < NFW_RULES_VERSION ) ) {
		// fetch new set of rules :
		$_POST['nfw_act'] = 'x';
		require_once( plugin_dir_path(__FILE__) . 'install.php' );
		$nfw_rules_new = unserialize( nfw_default_rules() );

		foreach ( $nfw_rules_new as $new_key => $new_value ) {
			foreach ( $new_value as $key => $value ) {
				// if that rule exists already, we keep its 'on' flag value :
				if ( ( isset( $nfw_rules[$new_key]['on'] ) ) && ( $key == 'on' ) ) {
					$nfw_rules_new[$new_key]['on'] = $nfw_rules[$new_key]['on'];
				}
			}
		}
		$nfw_rules_new[NFW_DOC_ROOT]['what']= $nfw_rules[NFW_DOC_ROOT]['what'];
		$nfw_rules_new[NFW_DOC_ROOT]['on']	= $nfw_rules[NFW_DOC_ROOT]['on'];

		// update rules... :
		update_option( 'nfw_rules', $nfw_rules_new);
		// ...and rules version number :
		$nfw_options['rules_version'] = NFW_RULES_VERSION;
		$is_update = 1;
	}

	// update options ?
	if ( $is_update ) {
		update_option( 'nfw_options', $nfw_options);
	}

	// E-mail alert ?
	if ( defined( 'NFW_ALERT' ) ) {
		check_email_alert();
	}
}

add_action('admin_init', 'nfw_upgrade' );

/* ================================================================== */

function nfw_login_hook( $user_login, $user ) {

	// Check if the user is an admin and if we must whitelist him/her :

	global $nfw_options;

	// Are we supposed to send an alert ?
	if (! empty($nfw_options['a_0']) ) {
		// User login:
		if ( ( ( $nfw_options['a_0'] == 1) && ( $user->roles[0] == 'administrator' )  ) ||
			( $nfw_options['a_0'] == 2 ) ) {
			send_login_email( $user_login, $user );
		}
	}

	if ( $user->roles[0] == 'administrator' ) {
		if (! isset( $nfw_options ) ) {
			$nfw_options = get_option( 'nfw_options' );
		}
		if (! empty( $nfw_options['wl_admin']) ) {
			// Set the goodguy flag :
			$_SESSION['nfw_goodguy'] = true;
			return;
		}
	}
	if ( isset( $_SESSION['nfw_goodguy'] ) ) {
		unset( $_SESSION['nfw_goodguy'] );
	}
}

add_action( 'wp_login', 'nfw_login_hook', 10, 2 );

/* ================================================================== */

function send_login_email( $user_login, $user ) {

	global $nfw_options;

	// Get timezone :
	get_blog_timezone();

	$subject = '[NinjaFirewall] Alert: WordPress console login';
	$message = 'Someone just logged in to your WordPress admin console:' . "\n\n".
				'- User : ' . $user_login . ' (' . $user->roles[0] . ")\n" .
				'- IP   : ' . $_SERVER['REMOTE_ADDR'] . "\n" .
				'- Date : ' . date('F j, Y @ H:i:s') . ' (UTC '. date('O') . ")\n" .
				'- URL  : ';
	if ( is_multisite() ) {
		$message .= network_home_url() . "\n";
	} else {
		$message .= home_url() . "\n";
	}

	wp_mail( $nfw_options['alert_email'], $subject, $message );

}
/* ================================================================== */

function nfw_logout_hook() {

	// Whoever it was, we clear the goodguy flag :

	if ( isset( $_SESSION['nfw_goodguy'] ) ) {
		unset( $_SESSION['nfw_goodguy'] );
	}
}

add_action( 'wp_logout', 'nfw_logout_hook' );

/* ================================================================== */

function is_nfw_enabled() {

	// Checks whether NF is enabled and/or active and/or debugging mode :

	$user_enabled = $hook_enabled = $debug_enabled = 0;
	global $nfw_options;

	if (! isset( $nfw_options) ) {
		$nfw_options = get_option( 'nfw_options' );
	}

	if (! empty( $nfw_options['enabled']) ) {
		$user_enabled = 1;
	}
	if ( plugin_dir_path(__FILE__) . 'lib/firewall.php' === ini_get( 'auto_prepend_file' ) ) {
		$hook_enabled = 1;
	}
	if (! empty( $nfw_options['debug']) ) {
		$debug_enabled = 1;
	}

	return array( $user_enabled, $hook_enabled, $debug_enabled );

}

/* ================================================================== */

function ninjafirewall_admin_menu() {

	// Some constants first :
	define( 'NFW_NULL_BYTE', 2);
	define( 'NFW_SCAN_BOTS', 310);
	define( 'NFW_ASCII_CTRL', 500);
	define( 'NFW_DOC_ROOT', 510);
	define( 'NFW_WRAPPERS', 520);
	define( 'NFW_LOOPBACK', 540);
	define( 'NFW_DEFAULT_MSG', '<br /><br /><br /><br /><center>Sorry <b>%%REM_ADDRESS%%</b>, ' .
		'your request cannot be proceeded.<br />For security reason it was blocked and logged.' .
		'<br /><br />%%NINJA_LOGO%%<br /><br />If you think that was a mistake, please contact the<br />' .
		'webmaster and enclose the following incident ID:<br /><br />[ <b>#%%NUM_INCIDENT%%</b> ]</center>'
	);

	// Setup our admin menus :

	list ( $user_enabled, $hook_enabled, $debug_enabled ) = is_nfw_enabled();

	// Run the install process if not installed yet :
	if (! $hook_enabled ) {
		add_menu_page( 'NinjaFirewall', 'NinjaFirewall', 'manage_options',
			'NinjaFirewall', 'nf_menu_install',	plugins_url( '/images/nf_icon.png', __FILE__ )
		);
		add_submenu_page( 'NinjaFirewall', 'Installation', 'Installation', 'manage_options',
			'NinjaFirewall', 'nf_menu_install' );
		return;
	}

	// Our main menu :
	add_menu_page( 'NinjaFirewall', 'NinjaFirewall', 'manage_options',
		'NinjaFirewall', 'nf_menu_main',	plugins_url( '/images/nf_icon.png', __FILE__ )
	);

	// All our submenus :
	global $menu_hook;

	// Admin menus contextual help :
	require_once( plugin_dir_path(__FILE__) . 'help.php' );

	// Overview menu :
	$menu_hook = add_submenu_page( 'NinjaFirewall', 'NinjaFirewall: Overview', 'Overview', 'manage_options',
		'NinjaFirewall', 'nf_menu_main' );
	add_action( 'load-' . $menu_hook, 'help_nfsubmain' );

	// Stats menu :
	$menu_hook = add_submenu_page( 'NinjaFirewall', 'NinjaFirewall: Statistics', 'Statistics', 'manage_options',
		'nfsubstat', 'nf_sub_statistics' );
	add_action( 'load-' . $menu_hook, 'help_nfsubstat' );

	// Firewall options menu :
	$menu_hook = add_submenu_page( 'NinjaFirewall', 'NinjaFirewall: Firewall Options', 'Firewall Options', 'manage_options',
		'nfsubopt', 'nf_sub_options' );
	add_action( 'load-' . $menu_hook, 'help_nfsubopt' );

	// Firewall policies menu :
	$menu_hook = add_submenu_page( 'NinjaFirewall', 'NinjaFirewall: Firewall Policies', 'Firewall Policies', 'manage_options',
		'nfsubpolicies', 'nf_sub_policies' );
	add_action( 'load-' . $menu_hook, 'help_nfsubpolicies' );

	// Alerts menu :
	$menu_hook = add_submenu_page( 'NinjaFirewall', 'NinjaFirewall: E-mail alerts', 'E-mail alerts', 'manage_options',
		'nfsubalerts', 'nf_sub_alerts' );
	add_action( 'load-' . $menu_hook, 'help_nfsubalerts' );

	// Firewall log menu :
	$menu_hook = add_submenu_page( 'NinjaFirewall', 'NinjaFirewall: Firewall Log', 'Firewall Log', 'manage_options',
		'nfsublog', 'nf_sub_log' );
	add_action( 'load-' . $menu_hook, 'help_nfsublog' );

	// Rules Editor menu :
	$menu_hook = add_submenu_page( 'NinjaFirewall', 'NinjaFirewall: Rules Editor', 'Rules Editor', 'manage_options',
		'nfsubedit', 'nf_sub_edit' );
	add_action( 'load-' . $menu_hook, 'help_nfsubedit' );

	// About menu :
	$menu_hook = add_submenu_page( 'NinjaFirewall', 'NinjaFirewall: About', 'About...', 'manage_options',
		'nfsubabout', 'nf_sub_about' );
	add_action( 'load-' . $menu_hook, 'help_nfsubabout' );

}

add_action( 'admin_menu', 'ninjafirewall_admin_menu' );

/* ================================================================== */

function nf_menu_install() {

	// Installer :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	require_once( plugin_dir_path(__FILE__) . 'install.php' );
}

/* ================================================================== */

function nf_menu_main() {

	// Main menu (Overview) :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	list ( $user_enabled, $hook_enabled, $debug_enabled ) = is_nfw_enabled();

	$warn_msg = '';
	if ( $user_enabled ) {
		$img = 'icon_ok_16.png';
		$txt = 'Enabled';
	} else {
		$img = 'icon_error_16.png';
		$txt = 'Disabled';
		$warn_msg = 1;
	}
	if ( $hook_enabled ) {
		$img2 = 'icon_ok_16.png';
		$txt2 = 'Enabled';
	} else {
		$img2 = 'icon_error_16.png';
		$txt2 = 'Disabled';
		$warn_msg = 2;
	}

?>

<div class="wrap">
	<div style="width:54px;height:52px;background-image:url(<?php echo plugins_url() ?>/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>NinjaFirewall (<font color=#21759B>WP</font> edition)</h2>
	<br />
	<?php
	if ( $warn_msg ) {
		echo '<div class="error settings-error"><p><strong>Warning :</strong> you are at risk ! Your site is not protected as long as the problems below aren\'t solved.</p></div>';
	}
	// first run ?
	if (  ( defined( 'NFW_IT_WORKS' )) || (! empty( $_GET['nfw_firstrun']) ) ) {
		echo '<br><div class="updated settings-error"><p><strong>Congratulations&nbsp;!</strong> NinjaFirewall is up and running. Use the menus in the left frame to configure it according to your needs.<br />If you need help, click on the contextual <strong>Help</strong> menu tab located in the upper right corner of each page.</p></div>';
	}
	?>
	<br />
	<h3>Firewall status</h3>
	<table class="form-table">
		<tr>
			<td width="200">Firewall</td>
			<td width="20" align="center"><img src="<?php echo plugins_url( '/images/' . $img, __FILE__ ) ?>" border="0" height="16" width="16"></td>
			<td><?php echo $txt; if ( $warn_msg == 1) {echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="?page=nfsubopt">Click here to enable NinjaFirewall</a>';} ?></td>
		</tr>
		<tr>
			<td width="200">PHP hook</td>
			<td width="20" align="center"><img src="<?php echo plugins_url( '/images/' . $img2, __FILE__ ) ?>" border="0" height="16" width="16"></td>
			<td><?php echo $txt2 ?></td>
		</tr>
		<tr>
			<td width="200">PHP SAPI</td>
			<td width="20" align="center">-</td>
			<td><?php echo strtoupper(PHP_SAPI) ?></td>
		</tr>
		<tr>
			<td width="200">Engine version</td>
			<td width="20" align="center">-</td>
			<td><?php echo NFW_ENGINE_VERSION ?></td>
		</tr>
		<tr>
			<td width="200">Rules version</td>
			<td width="20" align="center">-</td>
			<td><?php echo NFW_RULES_VERSION ?></td>
		</tr>
	<?php
	if ( $debug_enabled ) {
	?>
		<tr>
			<td width="200">Debugging mode</td>
			<td width="20" align="center"><img src="<?php echo plugins_url( '/images/icon_error_16.png', __FILE__ ) ?>" border="0" height="16" width="16"></td>
			<td>On&nbsp;&nbsp;&nbsp;&nbsp;<a href="?page=nfsubopt">Click here to turn off Debugging mode</a></td>
		</tr>
	<?php
	}
	echo '</table>';

	$ro_msg = '<h3>File System</h3>
	<table class="form-table">';
	// If the user files (.htaccess & PHP INI) are read-only, we display a warning,
	// otherwise, if (s)he wanted to uninstall NinjaFirewall, the uninstall process
	// could not restore them to their initial state and the site would crash :/
	$ro = 0;
	if ( ( file_exists( ABSPATH . '.htaccess' ) ) && (! is_writable( ABSPATH . '.htaccess' ) ) ) {
		$ro_msg .= '<tr>
		<td width="200">.htaccess</td>
		<td width="20" align="center"><img src="' . plugins_url( '/images/icon_warn_16.png', __FILE__ ) . '" border="0" height="16" width="16"></td>
		<td><code>' . ABSPATH . '.htaccess</code> is read-only</td>
		</tr>';
		$ro++;
	}
	$phpini = '';
	if ( file_exists( ABSPATH . 'php.ini' ) ) {
		$phpini = ABSPATH . 'php.ini';
	} elseif ( file_exists( ABSPATH . 'php5.ini' ) ) {
		$phpini = ABSPATH . 'php5.ini';
	} elseif ( file_exists( ABSPATH . '.user.ini' ) ) {
		$phpini = ABSPATH . '.user.ini';
	}
	if ( $phpini ) {
		if (! is_writable( $phpini ) ) {
			$ro_msg .= '<tr>
			<td width="200">PHP INI</td>
			<td width="20" align="center"><img src="' . plugins_url( '/images/icon_warn_16.png', __FILE__ ) . '" border="0" height="16" width="16"></td>
			<td><code>' . $phpini . '</code> is read-only</td>
			</tr>';
			$ro++;
		}
	}
	if ( $ro++ ) {
		echo $ro_msg . '<tr>
			<td width="200">&nbsp;</td>
			<td width="20">&nbsp;</td>
			<td><span class="description">&nbsp;Warning: you have some read-only system files; please <a href="http://ninjafirewall.com/ninjafirewall_wp_readonly.html" target="_blank">read this</a> if you want to uninstall NinjaFirewall.</span></td>
			</tr></table>';
	}
	?>
</div>

<?php
}

/* ================================================================== */

function nf_sub_statistics() {

	// Stats / benchmarks menu :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	echo '
<div class="wrap">
		<div style="width:54px;height:52px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>Statistics</h2>
	<br />';

	$critical = $high = $medium = $slow = $benchmark = $tot_bench = $speed = $upload = $total = 0;

	// Do we have any log for this month ?
	if (! file_exists( plugin_dir_path(__FILE__) . 'log/firewall_' . date( 'Y-m' ) . '.log' ) ) {
		echo '<div class="updated settings-error"><p>You do not have any stats for the current month yet.</p></div>';
		$fast = 0;
	} else {
		$fast = 1000;

		if (! $fh = @fopen( plugin_dir_path(__FILE__) . 'log/firewall_' . date( 'Y-m' ) . '.log', 'r') ) {
			echo '<div class="error settings-error"><p><strong>Cannot open logfile :</strong> ' .
				plugin_dir_path(__FILE__) . 'log/firewall_' . date( 'Y-m' ) . '.log</p></div></div>';
			return;
		}
		// Retrieve all lines :
		while (! feof( $fh) ) {
			$line = fgets( $fh);
			if (preg_match( '/^\[.+?\]\s+\[(.+?)\]\s+(?:\[.+?\]\s+){3}\[(1|2|3|4|5|6)\]/', $line, $match) ) {
				if ( $match[2] == 1) {
					$medium++;
				} elseif ( $match[2] == 2) {
					$high++;
				} elseif ( $match[2] == 3) {
					$critical++;
				} elseif ( $match[2] == 5) {
					$upload++;
				}
				if ( $match[1] > $slow) {
					$slow = $match[1];
				}
				if ( $match[1] < $fast) {
					$fast = $match[1];
				}
				$speed += $match[1];
				$tot_bench++;
			}
		}
		fclose( $fh);

		$total = $critical + $high + $medium;
		if ( $total == 1) {$fast = $slow;}
		$coef = 100 / $total;
		$critical = round( $critical * $coef, 2);
		$high = round( $high * $coef, 2);
		$medium = round( $medium * $coef, 2);
		$speed = round( $speed / $tot_bench, 4);
	}

	echo '
	<table class="form-table">
		<tr>
			<td width="200"><h3>Monthly stats</h3></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>' . date("F Y") . '</td>
		</tr>
		<tr>
			<td width="200">Total blocked hacking attempts</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>' . $total . '</td>
		</tr>
		<tr>
			<td valign="center" width="200">Hacking attempts severity</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
				Critical : ' . $critical . '%<br />
				<table bgcolor="#DFDFDF" border="0" cellpadding="0" cellspacing="0" height="14" width="250" align="left" style="height:14px;">
					<tr>
						<td width="' . round( $critical) . '%" background="' . plugins_url( '/images/bar-critical.png', __FILE__ ) . '" style="padding:0px"></td><td width="' . round(100 - $critical) . '%" style="padding:0px"></td>
					</tr>
				</table>
				<br />High : ' . $high . '%<br />
				<table bgcolor="#DFDFDF" border="0" cellpadding="0" cellspacing="0" height="14" width="250" align="left" style="height:14px;">
					<tr>
						<td width="' . round( $high) . '%" background="' . plugins_url( '/images/bar-high.png', __FILE__ ) . '" style="padding:0px"></td><td width="' . round(100 - $high) . '%" style="padding:0px"></td>
					</tr>
				</table>
				<br />Medium : ' . $medium . '%<br />
				<table bgcolor="#DFDFDF" border="0" cellpadding="0" cellspacing="0" height="14" width="250" align="left" style="height:14px;">
					<tr>
						<td width="' . round( $medium) . '%" background="' . plugins_url( '/images/bar-medium.png', __FILE__ ) . '" style="padding:0px;"></td><td width="' . round(100 - $medium) . '%" style="padding:0px;"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="200">Total uploaded files</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>' . $upload . '</td>
		</tr>

		<tr><td><h3>Benchmarks</h3></td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr>
			<td width="200">Average time per request</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>' . $speed . 's</td>
		</tr>
		<tr>
			<td width="200">Fastest request</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>' . round( $fast, 4) . 's</td>
		</tr>
		<tr>
			<td width="200">Slowest request</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>' . round( $slow, 4) . 's</td>
		</tr>
	</table>
</div>';

}

/* ================================================================== */

function nf_sub_options() {

	// Firewall Options menu :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	global $nfw_options;
	if (! isset( $nfw_options) ) {
		$nfw_options = get_option( 'nfw_options' );
	}

	echo '
<script>
function preview_msg() {
	var t1 = document.option_form.elements[\'nfw_options[blocked_msg]\'].value.replace(\'%%REM_ADDRESS%%\',\'' . $_SERVER['REMOTE_ADDR'] . '\');
	var t2 = t1.replace(\'%%NUM_INCIDENT%%\',\'1234567\');
	var t3 = t2.replace(\'%%NINJA_LOGO%%\',\'<img src="' . plugins_url( '/images/ninjafirewall_75.png', __FILE__ ) . '" width="75" height="75" title="NinjaFirewall">\');
	document.getElementById(\'out_msg\').innerHTML = t3;
	document.getElementById(\'td_msg\').style.display = \'\';
	document.getElementById(\'btn_msg\').value = \'Refresh preview\';
}
function default_msg() {
	document.option_form.elements[\'nfw_options[blocked_msg]\'].value = "' . preg_replace( '/[\r\n]/', '\n', NFW_DEFAULT_MSG) .'";
}
</script>

<div class="wrap">
	<div style="width:54px;height:52px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>Firewall Options</h2>

	<br />';

	// Saved options ?
	if ( isset( $_POST['nfw_options']) ) {
		nf_sub_options_save();
		$nfw_options = get_option( 'nfw_options' );
		echo '<div class="updated settings-error"><p><strong>Your changes have been saved.</strong></p></div>';
	}

	echo '
	<form method="post" name="option_form">
	<table class="form-table">
		<tr>
			<td width="200">Firewall protection</td>';

	// Enabled :
	if (! empty( $nfw_options['enabled']) ) {
		echo '
			<td width="20" align="center"><img src="' . plugins_url( '/images/icon_ok_16.png', __FILE__ ) . '" border="0" height="16" width="16"></td>
			<td align=left>
				<select name="nfw_options[enabled]" style="width:200px">
					<option value="1" selected>Enabled</option>
					<option value="0">Disabled</option>
				</select>';
	// Disabled :
	} else {
		echo '
			<td width="20" align="center"><img src="' . plugins_url( '/images/icon_error_16.png', __FILE__ ) . '" border="0" height="16" width="16"></td>
			<td align=left>
				<select name="nfw_options[enabled]" style="width:200px">
					<option value="1">Enabled</option>
					<option value="0" selected>Disabled</option>
				</select>&nbsp;<span class="description">&nbsp;Warning: your site is not protected !</span>';
	}

	echo '
			</td>
		</tr>
		<tr>
			<td valign="center" width="200">Debugging mode</td>';

	// Debugging enabled ?
	if (! empty( $nfw_options['debug']) ) {
	echo '<td width="20" align="center"><img src="' . plugins_url( '/images/icon_error_16.png', __FILE__ ) . '" border="0" height="16" width="16"></td>
			<td align=left>
				<select name="nfw_options[debug]" style="width:200px">
				<option value="1" selected>Enabled</option>
					<option value="0">Disabled (default)</option>
				</select>&nbsp;<span class="description">&nbsp;Warning: your site is not protected !</span>
			</td>';

	} else {
	// Debugging disabled ?
	echo '<td width="20" align="center">&nbsp;</td>
			<td align=left>
				<select name="nfw_options[debug]" style="width:200">
				<option value="1">Enabled</option>
					<option value="0" selected>Disabled (default)</option>
				</select>
			</td>';
	}

	// Get (if any) the HTTP error code to return :
	if (! @preg_match( '/^(?:40[0346]|50[03])$/', $nfw_options['ret_code']) ) {
		$nfw_options['ret_code'] = '403';
	}
	echo '
		<tr>
			<td width="200">HTTP error code to return</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
				<select name="nfw_options[ret_code]" style="width:200px">';

	echo '<option value="400"';
	if ( $nfw_options['ret_code'] == 400 ) { echo ' selected'; }
	echo '>400 Bad Request</option><option value="403"';
	if ( $nfw_options['ret_code'] == 403 ) { echo ' selected'; }
	echo '>403 Forbidden (default)</option><option value="404"';
	if ( $nfw_options['ret_code'] == 404 ) { echo ' selected'; }
	echo '>404 Not Found</option><option value="406"';
	if ( $nfw_options['ret_code'] == 406 ) { echo ' selected'; }
	echo '>406 Not Acceptable</option><option value="500"';
	if ( $nfw_options['ret_code'] == 500 ) { echo ' selected'; }
	echo '>500 Internal Server Error</option><option value="503"';
	if ( $nfw_options['ret_code'] == 503 ) { echo ' selected'; }
	echo '>503 Service Unavailable</option>';

	echo '	</select>
			</td>
		</tr>

		<tr>
			<td valign="center" width="200">Blocked user message</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
				<textarea name="nfw_options[blocked_msg]" class="large-text code" cols="60" rows="5">';
	if (! empty( $nfw_options['blocked_msg']) ) {
		echo $nfw_options['blocked_msg'];
	} else {
		echo NFW_DEFAULT_MSG;
	}
	echo '</textarea>
				<br />
				<input class="button-secondary" type="button" id="btn_msg" value="Preview message" onclick="javascript:preview_msg();" />&nbsp;&nbsp;<input class="button-secondary" type="button" id="btn_msg" value="Default message" onclick="javascript:default_msg();" />&nbsp;&nbsp;
			</td>
		</tr>
	</table>

	<table class="form-table" border=1>
		<tr id="td_msg" style="display:none"><td id="out_msg" style="border:1px solid #DFDFDF" width="100%"></td></tr>
	</table>

	<br />
	<input class="button-primary" type="submit" name="Save" value="Save Firewall Options" />
	</form>
</div>';

}

/* ================================================================== */

function nf_sub_options_save() {

	// Save options :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	global $nfw_options;

	if ( empty( $_POST['nfw_options']['enabled']) ) {
		$nfw_options['enabled'] = 0;
	} else {
		$nfw_options['enabled'] = 1;
	}

	if ( (isset( $_POST['nfw_options']['ret_code'])) &&
		(preg_match( '/^(?:40[0346]|50[03])$/', $_POST['nfw_options']['ret_code'])) ) {
		$nfw_options['ret_code'] = $_POST['nfw_options']['ret_code'];
	} else {
		$nfw_options['ret_code'] = '403';
	}

	if ( empty( $_POST['nfw_options']['blocked_msg']) ) {
		$nfw_options['blocked_msg'] = NFW_DEFAULT_MSG;
	} else {
		$nfw_options['blocked_msg'] = stripslashes( $_POST['nfw_options']['blocked_msg'] );
	}

	if ( empty( $_POST['nfw_options']['debug']) ) {
		$nfw_options['debug'] = 0;
	} else {
		$nfw_options['debug'] = 1;
	}

	// Save them :
	update_option( 'nfw_options', $nfw_options);

}

/* ================================================================== */

function nf_sub_policies() {

	// Firewall Policies menu :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	global $nfw_options;
	if (! isset( $nfw_options) ) {
		$nfw_options = get_option( 'nfw_options' );
	}
	global $nfw_rules;
	if (! isset( $nfw_rules) ) {
		$nfw_rules = get_option( 'nfw_rules' );
	}

	echo '
<script>
function escalert() {
	if (document.fwrules.escpost.checked){
		if (confirm("Warning : if you needed to edit comments or articles, enabling this options for POST requests could corrupt them with excessive backslashes.\nGo ahead ?")){
			return true;
		}
	}
	document.fwrules.escpost.checked=false;
   return true;
}
function restore() {
   if (confirm("All fields will be restored to their default values.\nGo ahead ?")){
      return true;
   }else{
		return false;
   }
}
function chksubmenu() {
	if (document.fwrules.elements[\'nfw_options[uploads]\'].value > 0) {
      document.fwrules.san.disabled = false;
      document.getElementById("santxt").style.color = "#000000";
   } else {
      document.fwrules.san.disabled = true;
      document.getElementById("santxt").style.color = "#bbbbbb";
   }
}
</script>

<div class="wrap">
	<div style="width:54px;height:52px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>Firewall Policies</h2>
	<br />';

	// Saved options ?
	if ( isset( $_POST['nfw_options']) ) {
		if ( $_POST['Save'] == 'Save Firewall Policies' ) {
			nf_sub_policies_save();
			echo '<div class="updated settings-error"><p><strong>Your changes have been saved.</strong></p></div>';
		} elseif ( $_POST['Save'] == 'Restore Default Values' ) {
			nf_sub_policies_default();
			echo '<div class="updated settings-error"><p><strong>Default values were restored.</strong></p></div>';
		} else {
			echo '<div class="error settings-error"><p><strong>No action taken.</strong></p></div>';
		}
		$nfw_options = get_option( 'nfw_options' );
	}

	echo '<form method="post" name="fwrules">';

	if ( ( isset( $nfw_options['scan_protocol']) ) &&
		( preg_match( '/^[123]$/', $nfw_options['scan_protocol']) ) ) {
		$scan_protocol = $nfw_options['scan_protocol'];
	} else {
		$scan_protocol = 3;
	}

	?>
	<h3>HTTP / HTTPS</h3>
	<table class="form-table">
		<tr>
			<td width="300" valign="top">Enable NinjaFirewall for...</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
			<label><input type="radio" name="nfw_options[scan_protocol]" value="3"<?php if ( $scan_protocol == 3 ) {	echo ' checked';	}?>>&nbsp;<code>HTTP</code> and <code>HTTPS/SSL</code> traffic (default)</label>
			<br />
			<label><input type="radio" name="nfw_options[scan_protocol]" value="1"<?php if ( $scan_protocol == 1 ) {	echo ' checked';	}?>>&nbsp;<code>HTTP</code> traffic only</label>
			<br />
			<label><input type="radio" name="nfw_options[scan_protocol]" value="2"<?php if ( $scan_protocol == 2 ) {	echo ' checked';	}?>>&nbsp;<code>HTTPS/SSL</code> traffic only</label>
			</td>
		</tr>
	</table>

	<?php
	if ( empty( $nfw_options['sanitise_fn']) ) {
		$sanitise_fn = 0;
	} else {
		$sanitise_fn = 1;
	}
	if ( empty( $nfw_options['uploads']) ) {
		$uploads = 0;
		$sanitise_fn = 0;
	} else {
		$uploads = 1;
	}
	?>
	<h3>Uploads</h3>
	<table class="form-table">
		<tr>
			<td width="300">File Uploads</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
				<select name="nfw_options[uploads]" onchange="chksubmenu();">
					<option value="1"<?php if ( $uploads == 1 ) {	echo ' selected';	}?>>Allow uploads</option>
					<option value="0"<?php if ( $uploads == 0 ) {	echo ' selected';	}?>>Disallow uploads (default)</option>
				</select>&nbsp;&nbsp;&nbsp;&nbsp;<label id="santxt"<?php if (! $uploads) { echo ' style="color:#bbbbbb;"'; }?>><input type="checkbox" name="nfw_options[sanitise_fn]"<?php if ( $sanitise_fn == 1 ) { echo ' checked'; }if (! $uploads) { echo ' disabled'; }?> id="san">&nbsp;Sanitise filenames</label>
			</td>
		</tr>
	</table>

	<?php
	if ( empty( $nfw_options['get_scan']) ) {
		$get_scan = 0;
	} else {
		$get_scan = 1;
	}
	if ( empty( $nfw_options['get_sanitise']) ) {
		$get_sanitise = 0;
	} else {
		$get_sanitise = 1;
	}
	?>
	<h3>GET requests</h3>
	<table class="form-table">
		<tr>
			<td width="300">Scan <code>GET</code> requests</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[get_scan]" value="1"<?php if ( $get_scan == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[get_scan]" value="0"<?php if ( $get_scan == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Sanitise <code>GET</code> requests</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[get_sanitise]" value="1"<?php if ( $get_sanitise == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[get_sanitise]" value="0"<?php if ( $get_sanitise == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
	</table>

	<?php
	if ( empty( $nfw_options['post_scan']) ) {
		$post_scan = 0;
	} else {
		$post_scan = 1;
	}
	if ( empty( $nfw_options['post_sanitise']) ) {
		$post_sanitise = 0;
	} else {
		$post_sanitise = 1;
	}
	?>
	<h3>POST requests</h3>
	<table class="form-table">
		<tr>
			<td width="300">Scan <code>POST</code> requests</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[post_scan]" value="1"<?php if ( $post_scan == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[post_scan]" value="0"<?php if ( $post_scan == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr valign="top">
			<td width="300">Sanitise <code>POST</code> requests</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[post_sanitise]" value="1"<?php if ( $post_sanitise == 1 ) { echo ' checked'; }?>>&nbsp;Yes</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[post_sanitise]" value="0"<?php if ( $post_sanitise == 0 ) { echo ' checked'; }?>>&nbsp;No (default)</label><br /><span class="description">&nbsp;Warning : do not enable this option if you do not know what you are doing&nbsp;!</span>
			</td>
		</tr>
	</table>

	<?php
	if ( empty( $nfw_options['cookies_scan']) ) {
		$cookies_scan = 0;
	} else {
		$cookies_scan = 1;
	}
	if ( empty( $nfw_options['cookies_sanitise']) ) {
		$cookies_sanitise = 0;
	} else {
		$cookies_sanitise = 1;
	}
	?>
	<h3>Cookies</h3>
	<table class="form-table">
		<tr>
			<td width="300">Scan cookies</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[cookies_scan]" value="1"<?php if ( $cookies_scan == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[cookies_scan]" value="0"<?php if ( $cookies_scan == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Sanitise cookies</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[cookies_sanitise]" value="1"<?php if ( $cookies_sanitise == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[cookies_sanitise]" value="0"<?php if ( $cookies_sanitise == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
	</table>

	<?php
	if ( empty( $nfw_options['ua_scan']) ) {
		$ua_scan = 0;
	} else {
		$ua_scan = 1;
	}
	if ( empty( $nfw_options['ua_sanitise']) ) {
		$ua_sanitise = 0;
	} else {
		$ua_sanitise = 1;
	}


	if ( empty( $nfw_rules[NFW_SCAN_BOTS]['on']) ) {
		$block_bots = 0;
	} else {
		$block_bots = 1;
	}
	?>
	<h3>HTTP_USER_AGENT server variable</h3>
	<table class="form-table">
		<tr>
			<td width="300">Scan <code>HTTP_USER_AGENT</code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[ua_scan]" value="1"<?php if ( $ua_scan == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[ua_scan]" value="0"<?php if ( $ua_scan == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Sanitise <code>HTTP_USER_AGENT</code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[ua_sanitise]" value="1"<?php if ( $ua_sanitise == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[ua_sanitise]" value="0"<?php if ( $ua_sanitise == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Block suspicious bots/scanners</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_rules[block_bots]" value="1"<?php if ( $block_bots == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_rules[block_bots]" value="0"<?php if ( $block_bots == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
	</table>

	<?php
	if ( empty( $nfw_options['referer_scan']) ) {
		$referer_scan = 0;
	} else {
		$referer_scan = 1;
	}
	if ( empty( $nfw_options['referer_sanitise']) ) {
		$referer_sanitise = 0;
	} else {
		$referer_sanitise = 1;
	}
	if ( empty( $nfw_options['referer_post']) ) {
		$referer_post = 0;
	} else {
		$referer_post = 1;
	}
	?>
	<h3>HTTP_REFERER server variable</h3>
	<table class="form-table">
		<tr>
			<td width="300">Scan <code>HTTP_REFERER</code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[referer_scan]" value="1"<?php if ( $referer_scan == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[referer_scan]" value="0"<?php if ( $referer_scan == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Sanitise <code>HTTP_REFERER</code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[referer_sanitise]" value="1"<?php if ( $referer_sanitise == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[referer_sanitise]" value="0"<?php if ( $referer_sanitise == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr valign="top">
			<td width="300">Block <code>POST</code> requests that do not have an <code>HTTP_REFERER</code> header</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[referer_post]" value="1"<?php if ( $referer_post == 1 ) { echo ' checked'; }?>>&nbsp;Yes</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[referer_post]" value="0"<?php if ( $referer_post == 0 ) { echo ' checked'; }?>>&nbsp;No (default)</label><br /><span class="description">&nbsp;Warning : keep this option disabled if you are using scripts like Paypal IPN etc.</span>
			</td>
		</tr>
	</table>

	<?php
	if ( empty( $nfw_rules[NFW_LOOPBACK]['on']) ) {
		$no_localhost_ip = 0;
	} else {
		$no_localhost_ip = 1;
	}
	if ( empty( $nfw_options['no_host_ip']) ) {
		$no_host_ip = 0;
	} else {
		$no_host_ip = 1;
	}
	if ( empty( $nfw_options['allow_local_ip']) ) {
		$allow_local_ip = 0;
	} else {
		$allow_local_ip = 1;
	}
	?>
	<h3>IPs</h3>
	<table class="form-table" border=0>
		<tr>
			<td width="300">Block localhost IP in <code>GET/POST</code> requests</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_rules[no_localhost_ip]" value="1"<?php if ( $no_localhost_ip == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_rules[no_localhost_ip]" value="0"<?php if ( $no_localhost_ip == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Block HTTP requests with an IP in the <code>Host</code> header</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[no_host_ip]" value="1"<?php if ( $no_host_ip == 1 ) { echo ' checked'; }?>>&nbsp;Yes</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[no_host_ip]" value="0"<?php if ( $no_host_ip == 0 ) { echo ' checked'; }?>>&nbsp;No (default)</label>
			</td>
		</tr>
		<tr>
			<td width="300">Do not scan traffic coming from localhost (127.0.0.1) and private IP address spaces</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[allow_local_ip]" value="1"<?php if ( $allow_local_ip == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[allow_local_ip]" value="0"<?php if ( $allow_local_ip == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
	</table>

	<?php
	if ( empty( $nfw_rules[NFW_WRAPPERS]['on']) ) {
		$php_wrappers = 0;
	} else {
		$php_wrappers = 1;
	}
	if ( empty( $nfw_options['php_errors']) ) {
		$php_errors = 0;
	} else {
		$php_errors = 1;
	}
	if ( empty( $nfw_options['php_self']) ) {
		$php_self = 0;
	} else {
		$php_self = 1;
	}
	if ( empty( $nfw_options['php_path_t']) ) {
		$php_path_t = 0;
	} else {
		$php_path_t = 1;
	}
	if ( empty( $nfw_options['php_path_i']) ) {
		$php_path_i = 0;
	} else {
		$php_path_i = 1;
	}
	?>
	<h3>PHP</h3>
	<table class="form-table">
		<tr>
			<td width="300">Block PHP built-in wrappers</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_rules[php_wrappers]" value="1"<?php if ( $php_wrappers == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_rules[php_wrappers]" value="0"<?php if ( $php_wrappers == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Hide PHP notice &amp; error messages</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[php_errors]" value="1"<?php if ( $php_errors == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[php_errors]" value="0"<?php if ( $php_errors == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Sanitise <code>PHP_SELF</code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[php_self]" value="1"<?php if ( $php_self == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[php_self]" value="0"<?php if ( $php_self == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Sanitise <code>PATH_TRANSLATED</code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[php_path_t]" value="1"<?php if ( $php_path_t == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[php_path_t]" value="0"<?php if ( $php_path_t == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Sanitise <code>PATH_INFO</code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[php_path_i]" value="1"<?php if ( $php_path_i == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[php_path_i]" value="0"<?php if ( $php_path_i == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
	</table>

	<br />

	<?php

	// If the document root is < 5 characters, grey out that option:
	if ( strlen( getenv( 'DOCUMENT_ROOT' ) ) < 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['on'] = 0;
		$greyed = 'style="color:#bbbbbb"';
		$disabled = 'disabled ';
		$disabled_msg = '<br /><span class="description">&nbsp;This option is not compatible with your actual configuration.</span>';
	} else {
		$greyed = '';
		$disabled = '';
		$disabled_msg = '';
	}

	if ( empty( $nfw_rules[NFW_DOC_ROOT]['on']) ) {
		$block_doc_root = 0;
	} else {
		$block_doc_root = 1;
	}
	if ( empty( $nfw_rules[NFW_NULL_BYTE]['on']) ) {
		$block_null_byte = 0;
	} else {
		$block_null_byte = 1;
	}
	if ( empty( $nfw_rules[NFW_ASCII_CTRL]['on']) ) {
		$block_ctrl_chars = 0;
	} else {
		$block_ctrl_chars = 1;
	}
	?>
	<h3>Various</h3>
	<table class="form-table">
		<tr valign="top">
			<td width="300">Block the <code>DOCUMENT_ROOT</code> server variable <?php echo '(<code>' . getenv( 'DOCUMENT_ROOT' ) . '</code>)' ?> in HTTP requests</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label <?php echo $greyed ?>><input type="radio" name="nfw_rules[block_doc_root]" value="1"<?php if ( $block_doc_root == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label <?php echo $greyed ?>><input <?php echo $disabled ?>type="radio" name="nfw_rules[block_doc_root]" value="0"<?php if ( $block_doc_root == 0 ) { echo ' checked'; }?>>&nbsp;No</label><?php echo $disabled_msg ?>
			</td>
		</tr>
		<tr>
			<td width="300">Block ASCII character 0x00 (NULL byte)</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_rules[block_null_byte]" value="1"<?php if ( $block_null_byte == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_rules[block_null_byte]" value="0"<?php if ( $block_null_byte == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Block ASCII control characters 1 to 8 and 14 to 31</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
				<label><input type="radio" name="nfw_rules[block_ctrl_chars]" value="1"<?php if ( $block_ctrl_chars == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_rules[block_ctrl_chars]" value="0"<?php if ( $block_ctrl_chars == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
	</table>

	<br />

	<?php

	if ( @strpos( $nfw_options['wp_dir'], 'wp-admin' ) !== FALSE ) {
		$wp_admin = 1;
	} else {
		$wp_admin = 0;
	}
	if ( @strpos( $nfw_options['wp_dir'], 'wp-includes' ) !== FALSE ) {
		$wp_inc = 1;
	} else {
		$wp_inc = 0;
	}
	if ( @strpos( $nfw_options['wp_dir'], 'uploads' ) !== FALSE ) {
		$wp_upl = 1;
	} else {
		$wp_upl = 0;
	}
	if ( @strpos( $nfw_options['wp_dir'], 'cache' ) !== FALSE ) {
		$wp_cache = 1;
	} else {
		$wp_cache = 0;
	}
	if ( empty( $nfw_options['no_post_themes']) ) {
		$no_post_themes = 0;
	} else {
		$no_post_themes = 1;
	}

	if ( empty( $nfw_options['force_ssl']) ) {
		$force_ssl = 0;
	} else {
		$force_ssl = 1;
	}
	if ( empty( $nfw_options['disallow_edit']) ) {
		$disallow_edit = 0;
	} else {
		$disallow_edit = 1;
	}
	if ( empty( $nfw_options['disallow_mods']) ) {
		$disallow_mods = 0;
	} else {
		$disallow_mods = 1;
	}

	?>
	<h3>WordPress</h3>
	<table class="form-table">
		<tr>
			<td width="300">Block direct access to any PHP file located in one of these directories</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
				<table class="form-table">
					<tr style="border: solid 1px #DFDFDF;">
						<td align="center" width="10"><input type="checkbox" name="nfw_options[wp_admin]" id="wp_01"<?php if ( $wp_admin == 1 ) { echo ' checked'; }?>></td>
						<td><label for="wp_01"><code>/wp-admin/css/*</code><br /><code>/wp-admin/images/*</code><br /><code>/wp-admin/includes/*</code><br /><code>/wp-admin/js/*</code></label></td>
					</tr>
					<tr style="border: solid 1px #DFDFDF;">
						<td align="center" width="10"><input type="checkbox" name="nfw_options[wp_inc]" id="wp_02"<?php if ( $wp_inc == 1 ) { echo ' checked'; }?>></td>
						<td><label for="wp_02"><code>/wp-includes/*.php</code><br /><code>/wp-includes/css/*</code><br /><code>/wp-includes/images/*</code><br /><code>/wp-includes/js/*</code><br /><code>/wp-includes/theme-compat/*</code></label></td>
					</tr>
					<tr style="border: solid 1px #DFDFDF;">
						<td align="center" width="10"><input type="checkbox" name="nfw_options[wp_upl]" id="wp_03"<?php if ( $wp_upl == 1 ) { echo ' checked'; }?>></td>
						<td><label for="wp_03"><code>/wp-content/upload/*</code></label></td>
					</tr>
					<tr style="border: solid 1px #DFDFDF;">
						<td align="center" width="10"><input type="checkbox" name="nfw_options[wp_cache]" id="wp_04"<?php if ( $wp_cache == 1 ) { echo ' checked'; }?>></td>
						<td><label for="wp_04"><code>*/cache/*</code></label></td>
					</tr>
				</table>
				<br />&nbsp;
			</td>
		</tr>
	</table>
	<table class="form-table">
		<tr>
			<td width="300">Block <code>POST</code> requests in the themes folder <code>/wp-content/themes</code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[no_post_themes]" value="1"<?php if ( $no_post_themes == 1 ) { echo ' checked'; }?>>&nbsp;Yes</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[no_post_themes]" value="0"<?php if ( $no_post_themes == 0 ) { echo ' checked'; }?>>&nbsp;No (default)</label>
			</td>
		</tr>
		<tr>
			<td width="300">Force SSL for admin and logins <code><a href="http://codex.wordpress.org/Editing_wp-config.php#Require_SSL_for_Admin_and_Logins" target="_blank">FORCE_SSL_ADMIN</a></code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[force_ssl]" value="1"<?php if ( $force_ssl == 1 ) { echo ' checked'; }?>>&nbsp;Yes</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[force_ssl]" value="0"<?php if ( $force_ssl == 0 ) { echo ' checked'; }?>>&nbsp;No (default)</label>
			</td>
		</tr>
		<tr>
			<td width="300">Disable the plugin and theme editor <code><a href="http://codex.wordpress.org/Editing_wp-config.php#Disable_the_Plugin_and_Theme_Editor" target="_blank">DISALLOW_FILE_EDIT</a></code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[disallow_edit]" value="1"<?php if ( $disallow_edit == 1 ) { echo ' checked'; }?>>&nbsp;Yes (default)</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[disallow_edit]" value="0"<?php if ( $disallow_edit == 0 ) { echo ' checked'; }?>>&nbsp;No</label>
			</td>
		</tr>
		<tr>
			<td width="300">Disable plugin and theme update/installation <code><a href="http://codex.wordpress.org/Editing_wp-config.php#Disable_Plugin_and_Theme_Update_and_Installation" target="_blank">DISALLOW_FILE_MODS</a></code></td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left width="120">
				<label><input type="radio" name="nfw_options[disallow_mods]" value="1"<?php if ( $disallow_mods == 1 ) { echo ' checked'; }?>>&nbsp;Yes</label>
			</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[disallow_mods]" value="0"<?php if ( $disallow_mods == 0 ) { echo ' checked'; }?>>&nbsp;No (default)</label>
			</td>
		</tr>

	</table>

	<br />

	<?php
	if ( empty( $nfw_options['wl_admin']) ) {
		$wl_admin = 0;
	} else {
		$wl_admin = 1;
	}
	?>
	<table class="form-table">
		<tr style="background-color:#F9F9F9;border: solid 1px #DFDFDF;">
			<td width="300">Do not block WordPress admin (must be logged in)</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
				<label><input type="radio" name="nfw_options[wl_admin]" value="1"<?php if ( $wl_admin == 1 ) { echo ' checked'; }?>>&nbsp;Yes, do not block the Administrator (default)</label>
				<br />
				<label><input type="radio" name="nfw_options[wl_admin]" value="0"<?php if ( $wl_admin == 0 ) { echo ' checked'; }?>>&nbsp;No, block everyone, including the Admin if needed !</label>
				<br />

				<span class="description">Note: does not apply to </span><code>FORCE_SSL_ADMIN</code><span class="description">, </span><code>DISALLOW_FILE_EDIT</code><span class="description"> and </span><code>DISALLOW_FILE_MODS</code><span class="description"> options which, if enabled, are always enforced.</span>
			</td>
		</tr>
	</table>
	<br />
	<br />
	<input class="button-primary" type="submit" name="Save" value="Save Firewall Policies" />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input class="button-secondary" type="submit" name="Save" value="Restore Default Values" onclick="return restore();" />
	</form>
</div>

<?php
}

/* ================================================================== */

function nf_sub_policies_save() {

	// Save policies :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	global $nfw_options;
	global $nfw_rules;

	// Options

	// HTTP/S traffic to scan :
	if ( (isset( $_POST['nfw_options']['scan_protocol'])) &&
		( preg_match( '/^[123]$/', $_POST['nfw_options']['scan_protocol'])) ) {
			$nfw_options['scan_protocol'] = $_POST['nfw_options']['scan_protocol'];
	} else {
		// Default : HTTP + HTTPS
		$nfw_options['scan_protocol'] = 3;
	}

	// Allow uploads ?
	if ( empty( $_POST['nfw_options']['uploads']) ) {
		// Default: no
		$nfw_options['uploads'] = 0;
	} else {
		$nfw_options['uploads'] = 1;
	}

	// Sanitise filenames (if uploads are allowed) ?
	if ( (isset( $_POST['nfw_options']['sanitise_fn']) ) && ( $nfw_options['uploads'] == 1) ) {
		$nfw_options['sanitise_fn'] = 1;
	} else {
		$nfw_options['sanitise_fn'] = 0;
	}

	// Scan GET requests ?
	if ( empty( $_POST['nfw_options']['get_scan']) ) {
		$nfw_options['get_scan'] = 0;
	} else {
		// Default: yes
		$nfw_options['get_scan'] = 1;
	}
	// Sanitise GET requests ?
	if ( empty( $_POST['nfw_options']['get_sanitise']) ) {
		$nfw_options['get_sanitise'] = 0;
	} else {
		// Default: yes
		$nfw_options['get_sanitise'] = 1;
	}


	// Scan POST requests ?
	if ( empty( $_POST['nfw_options']['post_scan']) ) {
		$nfw_options['post_scan'] = 0;
	} else {
		// Default: yes
		$nfw_options['post_scan'] = 1;
	}
	// Sanitise POST requests ?
	if ( empty( $_POST['nfw_options']['post_sanitise']) ) {
		$nfw_options['post_sanitise'] = 0;
	} else {
		// Default: no
		$nfw_options['post_sanitise'] = 1;
	}


	// Scan COOKIES requests ?
	if ( empty( $_POST['nfw_options']['cookies_scan']) ) {
		$nfw_options['cookies_scan'] = 0;
	} else {
		// Default: yes
		$nfw_options['cookies_scan'] = 1;
	}
	// Sanitise COOKIES requests ?
	if ( empty( $_POST['nfw_options']['cookies_sanitise']) ) {
		$nfw_options['cookies_sanitise'] = 0;
	} else {
		// Default: yes
		$nfw_options['cookies_sanitise'] = 1;
	}


	// Scan HTTP_USER_AGENT requests ?
	if ( empty( $_POST['nfw_options']['ua_scan']) ) {
		$nfw_options['ua_scan'] = 0;
	} else {
		// Default: yes
		$nfw_options['ua_scan'] = 1;
	}
	// Sanitise HTTP_USER_AGENT requests ?
	if ( empty( $_POST['nfw_options']['ua_sanitise']) ) {
		$nfw_options['ua_sanitise'] = 0;
	} else {
		// Default: yes
		$nfw_options['ua_sanitise'] = 1;
	}


	// Scan HTTP_REFERER requests ?
	if ( empty( $_POST['nfw_options']['referer_scan']) ) {
		$nfw_options['referer_scan'] = 0;
	} else {
		// Default: yes
		$nfw_options['referer_scan'] = 1;
	}
	// Sanitise HTTP_REFERER requests ?
	if ( empty( $_POST['nfw_options']['referer_sanitise']) ) {
		$nfw_options['referer_sanitise'] = 0;
	} else {
		// Default: yes
		$nfw_options['referer_sanitise'] = 1;
	}
	// Block POST requests without HTTP_REFERER ?
	if ( empty( $_POST['nfw_options']['referer_post']) ) {
		// Default: NO
		$nfw_options['referer_post'] = 0;
	} else {
		$nfw_options['referer_post'] = 1;
	}


	// Block HTTP requests with an IP in the Host header ?
	if ( empty( $_POST['nfw_options']['no_host_ip']) ) {
		// Default: NO
		$nfw_options['no_host_ip'] = 0;
	} else {
		$nfw_options['no_host_ip'] = 1;
	}
	// Do not scan server/local IPs ?
	if ( empty( $_POST['nfw_options']['allow_local_ip']) ) {
		$nfw_options['allow_local_ip'] = 0;
	} else {
		// Default: yes
		$nfw_options['allow_local_ip'] = 1;
	}


	// Hide PHP notice & error messages :
	if ( empty( $_POST['nfw_options']['php_errors']) ) {
		$nfw_options['php_errors'] = 0;
	} else {
		// Default: yes
		$nfw_options['php_errors'] = 1;
	}

	// Sanitise PHP_SELF ?
	if ( empty( $_POST['nfw_options']['php_self']) ) {
		$nfw_options['php_self'] = 0;
	} else {
		// Default: yes
		$nfw_options['php_self'] = 1;
	}
	// Sanitise PATH_TRANSLATED ?
	if ( empty( $_POST['nfw_options']['php_path_t']) ) {
		$nfw_options['php_path_t'] = 0;
	} else {
		// Default: yes
		$nfw_options['php_path_t'] = 1;
	}
	// Sanitise PATH_INFO ?
	if ( empty( $_POST['nfw_options']['php_path_i']) ) {
		$nfw_options['php_path_i'] = 0;
	} else {
		// Default: yes
		$nfw_options['php_path_i'] = 1;
	}

	// WordPress directories PHP restrictions :
	$nfw_options['wp_dir'] = $tmp = '';
	if ( isset( $_POST['nfw_options']['wp_admin']) ) {
		$tmp .= '/wp-admin/(?:css|images|includes|js)/|';
	}
	if ( isset( $_POST['nfw_options']['wp_inc']) ) {
		$tmp .= '/wp-includes/(?:(?:css|images|js|theme-compat)/|[^/]+\.php)|';
	}
	if ( isset( $_POST['nfw_options']['wp_upl']) ) {
		$tmp .= '/wp-content/uploads/|';
	}
	if ( isset( $_POST['nfw_options']['wp_cache']) ) {
		$tmp .= '/cache/|';
	}
	if ( $tmp ) {
		$nfw_options['wp_dir'] = rtrim( $tmp, '|' );
	}


	// Block POST requests in the themes folder ?
	if ( empty( $_POST['nfw_options']['no_post_themes']) ) {
		// Default : no
		$nfw_options['no_post_themes'] = 0;
	} else {
		$nfw_options['no_post_themes'] = '/wp-content/themes/';
	}

	// Force SSL for admin and logins ?
	if ( empty( $_POST['nfw_options']['force_ssl']) ) {
		// Default : no
		$nfw_options['force_ssl'] = 0;
	} else {
		$nfw_options['force_ssl'] = 1;
	}

	// Disable the plugin and theme editor
	if ( empty( $_POST['nfw_options']['disallow_edit']) ) {
		$nfw_options['disallow_edit'] = 0;
	} else {
		// Default : yes
		$nfw_options['disallow_edit'] = 1;
	}

	// Disable plugin and theme update/installation
	if ( empty( $_POST['nfw_options']['disallow_mods']) ) {
		// Default : no
		$nfw_options['disallow_mods'] = 0;
	} else {
		$nfw_options['disallow_mods'] = 1;
	}


	// Whitelist WP admin :
	if ( empty( $_POST['nfw_options']['wl_admin']) ) {
		$nfw_options['wl_admin'] = 0;
		// Clear the goodguy flag :
		if ( isset( $_SESSION['nfw_goodguy']) ) {
			unset( $_SESSION['nfw_goodguy']);
		}
	} else {
		// Default: don't block admin...
		$nfw_options['wl_admin'] = 1;
		// ...and set the goodguy flag :
		$_SESSION['nfw_goodguy'] = true;
	}


	// Rules

	// Block NULL byte 0x00 (#ID 2) :
	if ( empty( $_POST['nfw_rules']['block_null_byte']) ) {
		$nfw_rules[NFW_NULL_BYTE]['on'] = 0;
	} else {
		// Default: yes
		$nfw_rules[NFW_NULL_BYTE]['on'] = 1;
	}
	// Block bots & script kiddies' scanners (#ID 310) :
	if ( empty( $_POST['nfw_rules']['block_bots']) ) {
		$nfw_rules[NFW_SCAN_BOTS]['on'] = 0;
	} else {
		// Default: yes
		$nfw_rules[NFW_SCAN_BOTS]['on'] = 1;
	}
	// Block ASCII control characters 1 to 8 and 14 to 31 (#ID 500) :
	if ( empty( $_POST['nfw_rules']['block_ctrl_chars']) ) {
		$nfw_rules[NFW_ASCII_CTRL]['on'] = 0;
	} else {
		// Default: yes
		$nfw_rules[NFW_ASCII_CTRL]['on'] = 1;
	}


	// Block the DOCUMENT_ROOT server variable in GET/POST requests (#ID 510) :
	if ( empty( $_POST['nfw_rules']['block_doc_root']) ) {
		$nfw_rules[NFW_DOC_ROOT]['on'] = 0;
	} else {
		// Default: yes

		// We need to ensure that the document root is at least
		// 5 characters, otherwise this option could block a lot
		// of legitimate requests:
		if ( strlen( getenv( 'DOCUMENT_ROOT' ) ) > 5 ) {
			$nfw_rules[NFW_DOC_ROOT]['what'] = getenv( 'DOCUMENT_ROOT' );
			$nfw_rules[NFW_DOC_ROOT]['on']	= 1;
		} elseif ( strlen( $_SERVER['DOCUMENT_ROOT'] ) > 5 ) {
			$nfw_rules[NFW_DOC_ROOT]['what'] = $_SERVER['DOCUMENT_ROOT'];
			$nfw_rules[NFW_DOC_ROOT]['on']	= 1;
		// we must disable that option:
		} else {
			$nfw_rules[NFW_DOC_ROOT]['on']	= 0;
		}
	}


	// Block PHP built-in wrappers (#ID 520) :
	if ( empty( $_POST['nfw_rules']['php_wrappers']) ) {
		$nfw_rules[NFW_WRAPPERS]['on'] = 0;
	} else {
		// Default: yes
		$nfw_rules[NFW_WRAPPERS]['on'] = 1;
	}
	// Block localhost IP in GET/POST requests (#ID 540) :
	if ( empty( $_POST['nfw_rules']['no_localhost_ip']) ) {
		$nfw_rules[NFW_LOOPBACK]['on'] = 0;
	} else {
		// Default: yes
		$nfw_rules[NFW_LOOPBACK]['on'] = 1;
	}


	// Save option + rules :
	update_option( 'nfw_options', $nfw_options );
	update_option( 'nfw_rules', $nfw_rules );

}

/* ================================================================== */

function nf_sub_policies_default() {

	// Restore default firewall policies :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	global $nfw_options;
	global $nfw_rules;

	$nfw_options['scan_protocol']		= 3;
	$nfw_options['uploads']				= 0;
	$nfw_options['sanitise_fn']		= 1;
	$nfw_options['get_scan']			= 1;
	$nfw_options['get_sanitise']		= 1;
	$nfw_options['post_scan']			= 1;
	$nfw_options['post_sanitise']		= 0;
	$nfw_options['cookies_scan']		= 1;
	$nfw_options['cookies_sanitise']	= 1;
	$nfw_options['ua_scan']				= 1;
	$nfw_options['ua_sanitise']		= 1;
	$nfw_options['referer_scan']		= 1;
	$nfw_options['referer_sanitise']	= 1;
	$nfw_options['referer_post']		= 0;
	$nfw_options['no_host_ip']			= 0;
	$nfw_options['allow_local_ip']	= 1;
	$nfw_options['php_errors']			= 1;
	$nfw_options['php_self']			= 1;
	$nfw_options['php_path_t']			= 1;
	$nfw_options['php_path_i']			= 1;
	$nfw_options['wp_dir'] 				= '/wp-admin/(?:css|images|includes|js)/|' .
		'/wp-includes/(?:(?:css|images|js|theme-compat)/|[^/]+\.php)|' .
		'/wp-content/uploads/|/cache/';
	$nfw_options['no_post_themes']	= 0;
	$nfw_options['force_ssl'] 			= 0;
	$nfw_options['disallow_edit'] 	= 1;
	$nfw_options['disallow_mods'] 	= 0;

	$nfw_options['wl_admin']			= 1;
	$_SESSION['nfw_goodguy'] 			= true;

	$nfw_rules[NFW_SCAN_BOTS]['on']	= 1;
	$nfw_rules[NFW_LOOPBACK]['on']	= 1;
	$nfw_rules[NFW_WRAPPERS]['on']	= 1;

	if ( strlen( getenv( 'DOCUMENT_ROOT' ) ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['what'] = getenv( 'DOCUMENT_ROOT' );
		$nfw_rules[NFW_DOC_ROOT]['on'] = 1;
	} elseif ( strlen( $_SERVER['DOCUMENT_ROOT'] ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['what'] = $_SERVER['DOCUMENT_ROOT'];
		$nfw_rules[NFW_DOC_ROOT]['on'] = 1;
	} else {
		$nfw_rules[NFW_DOC_ROOT]['on']  = 0;
	}

	$nfw_rules[NFW_NULL_BYTE]['on']  = 1;
	$nfw_rules[NFW_ASCII_CTRL]['on'] = 1;

	update_option( 'nfw_options', $nfw_options);
	update_option( 'nfw_rules', $nfw_rules);

}

/* ================================================================== */

function nf_sub_alerts() {

	// Alerts menu :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	global $nfw_options;
	if (! isset( $nfw_options) ) {
		$nfw_options = get_option( 'nfw_options' );
	}

	echo '<div class="wrap">
	<div style="width:54px;height:52px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>E-mail alerts</h2>
	<br />';

	// Saved ?
	if ( isset( $_POST['nfw_options']) ) {
		nf_sub_alerts_save();
		echo '<div class="updated settings-error"><p><strong>Your changes have been saved.</strong></p></div>';
		$nfw_options = get_option( 'nfw_options' );
	}

	if (! isset( $nfw_options['a_0'] ) ) {
		$nfw_options['a_0'] = 1;
	}
	?>
	<form method="post" name="nfwalerts">

	<h3>WordPress admin console</h3>
	<table class="form-table">
		<tr>
			<td width="300" valign="top">Send me an alert whenever...</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
			<label><input type="radio" name="nfw_options[a_0]" value="1"<?php echo $nfw_options['a_0'] == 1 ? ' checked' : '' ?>>&nbsp;an administrator logs in (default)</label>
			<br />
			<label><input type="radio" name="nfw_options[a_0]" value="2"<?php echo $nfw_options['a_0'] == 2 ? ' checked' : '' ?>>&nbsp;someone (user, admin, editor...) logs in</label>
			<br />
			<label><input type="radio" name="nfw_options[a_0]" value="0"<?php echo $nfw_options['a_0'] == 0 ? ' checked' : '' ?>>&nbsp;no, thanks</label>
			</td>
		</tr>
	</table>

	<br />

	<h3>Plugins</h3>
	<table class="form-table">
		<tr>
			<td width="300" valign="top">Send me an alert whenever someone...</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
			<label><input type="checkbox" name="nfw_options[a_11]" value="1"<?php echo empty($nfw_options['a_11']) ? '' : ' checked' ?>>&nbsp;uploads a plugin (default)</label>
			<br />
			<label><input type="checkbox" name="nfw_options[a_12]" value="1"<?php echo empty($nfw_options['a_12']) ? '' : ' checked' ?>>&nbsp;installs a plugin (default)</label>
			<br />
			<label><input type="checkbox" name="nfw_options[a_13]" value="1"<?php echo empty($nfw_options['a_13']) ? '' : ' checked' ?>>&nbsp;activates a plugin</label>
			<br />
			<label><input type="checkbox" name="nfw_options[a_14]" value="1"<?php echo empty($nfw_options['a_14']) ? '' : ' checked' ?>>&nbsp;updates a plugin</label>
			<br />
			<label><input type="checkbox" name="nfw_options[a_15]" value="1"<?php echo empty($nfw_options['a_15']) ? '' : ' checked' ?>>&nbsp;deactivates a plugin (default)</label>
			<br />
			<label><input type="checkbox" name="nfw_options[a_16]" value="1"<?php echo empty($nfw_options['a_16']) ? '' : ' checked' ?>>&nbsp;deletes a plugin</label>
			</td>
		</tr>
	</table>

	<br />

	<h3>Themes</h3>
	<table class="form-table">
		<tr>
			<td width="300" valign="top">Send me an alert whenever someone...</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
			<label><input type="checkbox" name="nfw_options[a_21]" value="1"<?php echo empty($nfw_options['a_21']) ? '' : ' checked' ?>>&nbsp;uploads a theme (default)</label>
			<br />
			<label><input type="checkbox" name="nfw_options[a_22]" value="1"<?php echo empty($nfw_options['a_22']) ? '' : ' checked' ?>>&nbsp;installs a theme (default)</label>
			<br />
			<label><input type="checkbox" name="nfw_options[a_23]" value="1"<?php echo empty($nfw_options['a_23']) ? '' : ' checked' ?>>&nbsp;activates a theme</label>
			<br />
			<label><input type="checkbox" name="nfw_options[a_24]" value="1"<?php echo empty($nfw_options['a_24']) ? '' : ' checked' ?>>&nbsp;deletes a theme</label>
			</td>
		</tr>
	</table>

	<br />

	<h3>Core</h3>
	<table class="form-table">
		<tr>
			<td width="300" valign="top">Send me an alert whenever someone...</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
			<label><input type="checkbox" name="nfw_options[a_31]" value="1"<?php echo empty($nfw_options['a_31']) ? '' : ' checked' ?>>&nbsp;updates WordPress (default)</label>
			</td>
		</tr>
	</table>

	<br />

	<h3>Contact email</h3>
	<table class="form-table">
		<tr style="background-color:#F9F9F9;border: solid 1px #DFDFDF;">
			<td width="300" valign="top">Email address where alerts should be sent to</td>
			<td width="20" align="center">&nbsp;</td>
			<td align=left>
			<input type="text" name="nfw_options[alert_email]" size="45" maxlength="250" value="<?php echo empty( $nfw_options['alert_email']) ? get_option('admin_email') : $nfw_options['alert_email'] ?>">
			</td>
		</tr>
	</table>

	<br />
	<br />
	<input class="button-primary" type="submit" name="Save" value="Save E-mail Alerts" />

	</form>

</div>
<?php

}
/* ================================================================== */

function nf_sub_alerts_save() {

	// Save e-mail alerts :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	global $nfw_options;

	if (! preg_match('/^[012]$/', $_POST['nfw_options']['a_0']) ) {
		$nfw_options['a_0'] = 1;
	} else {
		$nfw_options['a_0'] = $_POST['nfw_options']['a_0'];
	}

	if ( empty( $_POST['nfw_options']['a_11']) ) {
		$nfw_options['a_11'] = 0;
	} else {
		$nfw_options['a_11'] = $_POST['nfw_options']['a_11'];
	}
	if ( empty( $_POST['nfw_options']['a_12']) ) {
		$nfw_options['a_12'] = 0;
	} else {
		$nfw_options['a_12'] = $_POST['nfw_options']['a_12'];
	}
	if ( empty( $_POST['nfw_options']['a_13']) ) {
		$nfw_options['a_13'] = 0;
	} else {
		$nfw_options['a_13'] = $_POST['nfw_options']['a_13'];
	}
	if ( empty( $_POST['nfw_options']['a_14']) ) {
		$nfw_options['a_14'] = 0;
	} else {
		$nfw_options['a_14'] = $_POST['nfw_options']['a_14'];
	}
	if ( empty( $_POST['nfw_options']['a_15']) ) {
		$nfw_options['a_15'] = 0;
	} else {
		$nfw_options['a_15'] = $_POST['nfw_options']['a_15'];
	}
	if ( empty( $_POST['nfw_options']['a_16']) ) {
		$nfw_options['a_16'] = 0;
	} else {
		$nfw_options['a_16'] = $_POST['nfw_options']['a_16'];
	}

	if ( empty( $_POST['nfw_options']['a_21']) ) {
		$nfw_options['a_21'] = 0;
	} else {
		$nfw_options['a_21'] = $_POST['nfw_options']['a_21'];
	}
	if ( empty( $_POST['nfw_options']['a_22']) ) {
		$nfw_options['a_22'] = 0;
	} else {
		$nfw_options['a_22'] = $_POST['nfw_options']['a_22'];
	}
	if ( empty( $_POST['nfw_options']['a_23']) ) {
		$nfw_options['a_23'] = 0;
	} else {
		$nfw_options['a_23'] = $_POST['nfw_options']['a_23'];
	}
	if ( empty( $_POST['nfw_options']['a_24']) ) {
		$nfw_options['a_24'] = 0;
	} else {
		$nfw_options['a_24'] = $_POST['nfw_options']['a_24'];
	}

	if ( empty( $_POST['nfw_options']['a_31']) ) {
		$nfw_options['a_31'] = 0;
	} else {
		$nfw_options['a_31'] = $_POST['nfw_options']['a_31'];
	}

	if (! empty( $_POST['nfw_options']['alert_email']) ) {
		$nfw_options['alert_email'] = sanitize_email( $_POST['nfw_options']['alert_email'] );
	}
	if ( empty( $nfw_options['alert_email'] ) ) {
		$nfw_options['alert_email'] = get_option('admin_email');
	}

	// Update options :
	update_option( 'nfw_options', $nfw_options );

}

/* ================================================================== */

function nf_sub_log() {

	// Firewall Log menu :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}
	$log = plugin_dir_path(__FILE__) . 'log/firewall_' . date( 'Y-m' ) . '.log';

	$err = '';
	if ( file_exists( $log ) ) {
		if (! is_writable( $log ) ) {
			$err = 'logfile is not writable. Please chmod it and its parent directory to 0777';
		}
	} else {
		if (! is_writable( plugin_dir_path(__FILE__) . 'log' ) ) {
			$err = 'log directory is not writable. Please chmod it to 0777';
		}
	}

	echo '
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>Firewall Log</h2>
	<br />';

	if ( $err ) {
		echo '<div class="error settings-error"><p><strong>Error : </strong>' . $err . '</p></div>';
	}

	// Do we have any log for this month ?
	if (! file_exists( $log ) ) {
		echo '<div class="updated settings-error"><p>You do not have any log for the current month yet.</p></div></div>';
		return;
	}

	if (! $fh = @fopen( $log, 'r' ) ) {
		echo '<div class="error settings-error"><p><strong>Fatal error :</strong> cannot open the log ( ' . $log .' )</p></div></div>';
		return;
	}
	// We will only display the last $max_lines lines, and will warn
	// about it if the log is bigger :
	$count = 0;
	$max_lines = 500;
	while (! feof( $fh ) ) {
		fgets( $fh );
		$count++;
	}
	// Skip last empty line :
	$count--;
	fclose( $fh );
	if ( $count < $max_lines ) {
		$skip = 0;
	} else  {
		echo '<div class="updated settings-error"><p><strong>Warning :</strong> your logfile has ' .
			$count . ' lines. I will display the last 500 lines only.</p></div>';
		$skip = $count - $max_lines;
	}

	// Get timezone :
	get_blog_timezone();

	$levels = array( '', 'medium', 'high', 'critical', 'error', 'upload', 'info', 'DEBUG_ON' );
	echo '
	<table class="form-table">
		<tr>
			<td width="100%">
				<textarea style="line-height:15px;width:100%;height:320px;font-family:\'Courier New\',Courier,monospace;font-size:12px;padding:4px;" wrap="off">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INCIDENT&nbsp;&nbsp;LEVEL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RULE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REQUEST' . "\n";

	$fh = fopen( $log, 'r' );
	while (! feof( $fh ) ) {
		$line = fgets( $fh );
		if ( $skip <= 0 ) {
			if ( preg_match( '/^\[(\d{10})\]\s+.+?\[(#\d{7})\]\s+\[(\d+)\]\s+\[(\d)\]\s+\[([\d.]+?)\]\s+\[.+?\]\s+\[(.+?)\]\s+\[(.+?)\]\s+\[(.+?)\]\s+\[(.+)\]$/', $line, $match ) ) {
				if ( empty( $match[3]) ) { $match[3] = '-'; }
				$res = date( 'd/M/y H:i:s', $match[1] ) . '  ' . $match[2] . '  ' . str_pad( $levels[$match[4]], 8 , ' ', STR_PAD_RIGHT) .'  ' .
				str_pad( $match[3], 4 , ' ', STR_PAD_LEFT) . '  ' . str_pad( $match[5], 15, ' ', STR_PAD_RIGHT) . '  ' .
				$match[6] . ' ' . $match[7] . ' - ' .	$match[8] . ' - [' . $match[9] . "]\n";
				echo htmlentities( $res );
			}
		}
		$skip--;
	}
	fclose( $fh );

	$log_stat = stat( $log );
	echo '</textarea>
				<br />
				<center><span class="description">The log is rotated monthly - Current size: ' . number_format( $log_stat['size'] ) .' bytes, '. $count . ' lines.</span></center>
			</td>
		</tr>
	</table>
</div>';

}
/* ================================================================== */

function nf_sub_edit() {

	// Rules Editor menu :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}

	echo '
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>Rules Editor</h2>
	<br />';

	global $nfw_rules;
	if (! isset( $nfw_rules) ) {
		$nfw_rules = get_option( 'nfw_rules' );
	}
	$is_update = 0;

	if ( isset($_POST['sel_e_r']) ) {
		if ( $_POST['sel_e_r'] < 1 ) {
			echo '<div class="error settings-error"><p><strong>Error : you did not select a rule to disable</strong></p></div>';
		} else if ( ( $_POST['sel_e_r'] == 2 ) || ( $_POST['sel_e_r'] > 499 ) && ( $_POST['sel_e_r'] < 600 ) ) {
			echo '<div class="error settings-error"><p><strong>Error : to change this rule, use the "Firewall Policies" menu.</strong></p></div>';
		} else if (! isset( $nfw_rules[$_POST['sel_e_r']] ) ) {
			echo '<div class="error settings-error"><p><strong>Error : this rule does not exist&nbsp;!</strong></p></div>';
		} else {
			$nfw_rules[$_POST['sel_e_r']]['on'] = 0;
			$is_update = 1;
			echo '<div class="updated settings-error"><p><strong>Rule ID ' . $_POST['sel_e_r'] . ' has been disabled.</strong></p></div>';
		}
	} else if ( isset($_POST['sel_d_r']) ) {
		if ( $_POST['sel_d_r'] < 1 ) {
			echo '<div class="error settings-error"><p><strong>Error : you did not select a rule to enable</strong></p></div>';
		} else if ( ( $_POST['sel_d_r'] == 2 ) || ( $_POST['sel_d_r'] > 499 ) && ( $_POST['sel_d_r'] < 600 ) ) {
			echo '<div class="error settings-error"><p><strong>Error : to change this rule, use the "Firewall Policies" menu.</strong></p></div>';
		} else if (! isset( $nfw_rules[$_POST['sel_d_r']] ) ) {
			echo '<div class="error settings-error"><p><strong>Error : this rule does not exist&nbsp;!</strong></p></div>';
		} else {
			$nfw_rules[$_POST['sel_d_r']]['on'] = 1;
			$is_update = 1;
			echo '<div class="updated settings-error"><p><strong>Rule ID ' . $_POST['sel_d_r'] . ' has been enabled.</strong></p></div>';
		}
	}
	if ( $is_update ) {
		update_option( 'nfw_rules', $nfw_rules);
	}

	$disabled_rules = $enabled_rules = array();
	foreach ( $nfw_rules as $rule_key => $rule_value ) {
		if (! empty( $nfw_rules[$rule_key]['on'] ) ) {
			$enabled_rules[] =  $rule_key;
		} else {
			$disabled_rules[] = $rule_key;
		}
	}

	echo '<br /><h3>NinjaFirewall built-in security rules</h3>
	<table class="form-table">
		<tr>
			<td width="300">
			<p>Select the rule you want to disable or enable</p>
			</td>
			<td width="20">&nbsp;</td>
			<td align="left">
			<form method="post">
			<select name="sel_e_r" style="width:220px;font-family:\'Courier New\',Courier,monospace;">
				<option value="0">Total rules enabled : ' . count( $enabled_rules ) . '</option>';
	ksort( $enabled_rules );

	foreach ( $enabled_rules as $key ) {
		// skip those ones, they can be changed in the Firewall Policies section:
		if ( ( $key == 2 ) || ( $key > 499 ) && ( $key < 600 ) ) { continue; }
		echo '<option value="' . $key . '">Rule ID : ' . $key . '</option>';
	}
	echo '</select>&nbsp;&nbsp;<input class="button-secondary" type="submit" name="disable" value="Disable it">
		</form>
		<br />

		<form method="post">
		<select name="sel_d_r" style="width:220px;font-family:\'Courier New\',Courier,monospace;">
		<option value="0">Total rules disabled : ' . count( $disabled_rules ) . '</option>';
	ksort( $disabled_rules );
	foreach ( $disabled_rules as $key ) {
		echo '<option value="' . $key . '">Rule ID : ' . $key . '</option>';
	}

	echo '</select>&nbsp;&nbsp;<input class="button-secondary" type="submit" name="disable" value="Enable it">
				</form>
			</td>
		</tr>
	</table>
</div>';

}

/* ================================================================== */

function nf_sub_about() {

	// About menu :

	if (! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.',
			'', array( 'response' => 403 ) );
	}


	if ( $data = @file_get_contents( plugin_dir_path(__FILE__) . 'readme.txt' ) ) {
		$what = '== Changelog ==';
		$pos_start = strpos( $data, $what );
		$changelog = substr( $data, $pos_start + strlen( $what ) + 1 );
	} else {
		$changelog = 'Error : cannot find changelog :(';
	}

	echo '
<script>
function show_table(table_id) {
	var av_table = [11, 12, 13, 14];
	for (var i = 0; i < av_table.length; i++) {
		if ( table_id == av_table[i] ) {
			document.getElementById(table_id).style.display = "";
		} else {
			document.getElementById(av_table[i]).style.display = "none";
		}
	};
}
</script>
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;" title="NinTechNet"></div>
	<h2>About</h2>
	<br />
	<br />
	<center>
		<table border="0" width="500" style="border: 1px solid #DFDFDF;padding:10px;-moz-box-shadow:-3px 5px 5px #999;-webkit-box-shadow:-3px 5px 5px #999;box-shadow:-3px 5px 5px #999;background-color:#FCFCFC;">
			<tr style="text-align:center">
				<td>
					<font style="font-size: 1.2em; font-weight: bold;">NinjaFirewall (<font color="#21759">WP</font> edition) v' . NFW_ENGINE_VERSION . '</font>
					<br />
					<br />
					<a href="http://nintechnet.com/" target="_blank" title="The Ninja Technologies Network"><img src="' . plugins_url() . '/ninjafirewall/images/nintechnet.png" border="0" width="190" height="60" title="The Ninja Technologies Network"></a>
					<br />
					&copy; 2012-' . date( 'Y' ) . ' <a href="http://nintechnet.com/" target="_blank" title="The Ninja Technologies Network"><strong>NinTechNet</strong></a>
					<br />
					The Ninja Technologies Network
					<br />
					<br />

					<table border="0" class="smallblack" cellspacing="2" cellpadding="10" width="100%">
						<tr valign=top>
							<td align=center style="border-right:dotted 0px #FDCD25;" width="33%">
								<img src="' . plugins_url( '/images/logo_nm_65.png', __FILE__ ) . '" width="65" height="65" border=0>
								<br />
								<a href="http://ninjamonitoring.com/" title="NinjaMonitoring: monitor your website for suspicious activities"><b>NinjaMonitoring</b></a>
								<br />
								Monitor your website for suspicious activities.
							</td>
							<td align=center style="border-right:dotted 0px #FDCD25;" width="34%">
								<img src="' . plugins_url( '/images/logo_pro_65.png', __FILE__ ) . '" width="65" height="65" border=0>
								<br />
								<a href="http://ninjafirewall.com/" title="NinjaFirewall: advanced firewall software for all your PHP applications"><b>NinjaFirewall</b></a>
								<br />
								Advanced firewall software for all your PHP applications.
							</td>
							<td align=center width="33%">
								<img src="' . plugins_url( '/images/logo_nr_65.png', __FILE__ ) . '" width="65" height="65" border=0>
								<br />
								<a href="http://ninjarecovery.com/" title="NinjaRecovery: Incident response, malware removal &amp; hacking recovery"><b>NinjaRecovery</b></a>
								<br />
								Incident response, malware removal &amp; hacking recovery.
							</td>
						</tr>
					</table>

				</td>
			</tr>
		</table>
		<br />
		<br />
		<input class="button-secondary" type="button" value="Changelog" onclick="show_table(12);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="button-primary" type="button" value="Spread the word about the Ninja !" onclick="show_table(11);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="button-secondary" type="button" value="System Info" onclick="show_table(13);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="button-secondary" type="button" value="Privacy Policy" onclick="show_table(14);">
		<br />
		<br />

		<table id="11" border="0" style="display:none;" width="500">
			<tr style="text-align:center">
				<td style="border: solid 1px #DFDFDF;width:25%;"><img src="' . plugins_url( '/images/ninjafirewall_32.png', __FILE__ ) . '" width="32" height="32"></td>
				<td style="border: solid 1px #DFDFDF;width:25%;"><img src="' . plugins_url( '/images/ninjafirewall_50.png', __FILE__ ) . '" width="50" height="50"></td>
				<td style="border: solid 1px #DFDFDF;width:25%;"><img src="' . plugins_url( '/images/ninjafirewall_75.png', __FILE__ ) . '" width="75" height="75"></td>
				<td style="border: solid 1px #DFDFDF;width:25%;"><img src="' . plugins_url( '/images/ninjafirewall_100.png', __FILE__ ) . '" width="100" height="100"></td>
			</tr>
			<tr style="text-align:center" valign="top">
				<td><a href="' . plugins_url( '/images/ninjafirewall_32.png', __FILE__ ) . '">ninjafirewall_32.png</a><br />32x32</td>
				<td><a href="' . plugins_url( '/images/ninjafirewall_50.png', __FILE__ ) . '">ninjafirewall_50.png</a><br />50x50</td>
				<td><a href="' . plugins_url( '/images/ninjafirewall_75.png', __FILE__ ) . '">ninjafirewall_75.png</a><br />75x75</td>
				<td><a href="' . plugins_url( '/images/ninjafirewall_100.png', __FILE__ ) . '">ninjafirewall_100.png</a><br />100x100</td>
			</tr>
		</table>

		<table id="12" style="display:none;" width="500">
			<tr>
				<td>
					<textarea class="large-text code" cols="60" rows="8">' . $changelog . '</textarea>
				</td>
			</tr>
		</table>

		<table id="13" border="0" style="display:none;" width="500">';
	if ( PHP_VERSION ) {
		echo '<tr><td width="47%;" align="right">PHP version</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . PHP_VERSION . ' (' . strtoupper( PHP_SAPI ) . ')</td></tr>';
	}
	if ( $_SERVER['SERVER_SOFTWARE'] ) {
		echo '<tr><td width="47%;" align="right">HTTP server</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . $_SERVER['SERVER_SOFTWARE'] . '</td></tr>';
	}
	if ( PHP_OS ) {
		echo '<tr><td width="47%;" align="right">Operating System</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . PHP_OS . '</td></tr>';
	}
	if ( $load = sys_getloadavg() ) {
		echo '<tr><td width="47%;" align="right">Load Average</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . $load[0] . ', '. $load[1] . ', '. $load[2] . '</td></tr>';
	}
	if (! preg_match( '/^win/i', PHP_OS ) ) {
		$MemTotal = $MemFree = $Buffers = $Cached = 0;
		$data = @explode( "\n", `cat /proc/meminfo` );
		foreach ( $data as $line ) {
			if ( preg_match( '/^MemTotal:\s+?(\d+)\s/', $line, $match ) ) {
				$MemTotal = $match[1] / 1024;
			} elseif ( preg_match( '/^MemFree:\s+?(\d+)\s/', $line, $match ) ) {
				$MemFree = $match[1];
			} elseif ( preg_match( '/^Buffers:\s+?(\d+)\s/', $line, $match ) ) {
				$Buffers = $match[1];
			} elseif ( preg_match( '/^Cached:\s+?(\d+)\s/', $line, $match ) ) {
				$Cached = $match[1];
			}
		}
		$free = ( $MemFree + $Buffers + $Cached ) / 1024;
		if ( $free ) {
			echo '<tr><td width="47%;" align="right">RAM</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . number_format( $free ) . ' MB free / '. number_format( $MemTotal ) . ' MB total</td></tr>';
		}

		$cpu = @explode( "\n", `grep 'model name' /proc/cpuinfo` );
		if (! empty( $cpu[0] ) ) {
			array_pop( $cpu );
			echo '<tr><td width="47%;" align="right">Processor(s)</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . count( $cpu ) . '</td></tr>';
			echo '<tr><td width="47%;" align="right">CPU model</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . str_replace ("model name\t:", '', $cpu[0]) . '</td></tr>';
		}
	}

	echo '
		</table>
		<table id="14" style="display:none;" width="500">
			<tr>
				<td>
					<textarea class="large-text code" cols="60" rows="8">NinTechNet strictly follows the WordPress Plugin Developer guidelines &lt;http://wordpress.org/plugins/about/guidelines/&gt;: NinjaFirewall (WP edition) is 100% free, 100% open source and 100% fully functional, no "trialware", no "obfuscated code", no "crippleware", no "phoning home". It does not require a registration process or an activation key to be used or installed.' . "\n" . 'Because we do not collect any user data, we do not even know that you are using (and hopefully enjoying!) our product.</textarea>
				</td>
			</tr>
		</table>

	</center>
</div>';

}

/* ================================================================== */

function ninjafirewall_settings_link( $links, $file ) {

	// Settings link :

	static $this_plugin;
	if (! $this_plugin ) {
		$this_plugin = plugin_basename( __FILE__ );
	}

	if ( $file == $this_plugin ) {
		$settings_link = '<a href="admin.php?page=NinjaFirewall">Settings</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}

add_filter( 'plugin_action_links', 'ninjafirewall_settings_link', 10, 2);

/* ================================================================== */

function get_blog_timezone() {

	// Try to get the user timezone from WP configuration...
	$tzstring = get_option( 'timezone_string' );
	// ...or from PHP...
	if (! $tzstring ) {
		$tzstring = ini_get( 'date.timezone' );
		// ...or use UTC :
		if (! $tzstring ) {
			$tzstring = 'UTC';
		}
	}
	// Set the timezone :
	date_default_timezone_set( $tzstring );

}
/* ================================================================== */

function check_email_alert() {

	global $nfw_options;
	global $current_user;
	$current_user = wp_get_current_user();

	if (! isset( $nfw_options) ) {
		$nfw_options = get_option( 'nfw_options' );
	}

	// Check what it is :
	list( $a_1, $a_2, $a_3 ) = explode( ':', NFW_ALERT . ':' );

	// Shall we alert the admin ?
	if (! empty($nfw_options['a_' . $a_1 . $a_2]) ) {
		$alert_array = array(
			'1' => array (
				'0' => 'Plugin', '1' => 'uploaded',	'2' => 'installed', '3' => 'activated',
				'4' => 'updated', '5' => 'deactivated', '6' => 'deleted', 'label' => 'Name'
			),
			'2' => array (
				'0' => 'Theme', '1' => 'uploaded', '2' => 'installed', '3' => 'activated',
				'4' => 'deleted', 'label' => 'Name'
			),
			'3' => array (
				'0' => 'WordPress', '1' => 'upgraded',	'label' => 'Version'
			)
		);

		// Get timezone :
		get_blog_timezone();

		if ( substr_count($a_3, ',') ) {
			$alert_array[$a_1][0] .= 's';
			$alert_array[$a_1]['label'] .= 's';
		}
		$subject = '[NinjaFirewall] Alert: ' . $alert_array[$a_1][0] . ' ' . $alert_array[$a_1][$a_2];
		$message = 'NinjaFirewall has detected the following activity on your account:' . "\n\n".
			'- ' . $alert_array[$a_1][0] . ' ' . $alert_array[$a_1][$a_2] . "\n" .
			'- ' . $alert_array[$a_1]['label'] . ' : ' . $a_3 . "\n\n" .
			'- User : ' . $current_user->user_login . ' (' . $current_user->roles[0] . ")\n" .
			'- IP   : ' . $_SERVER['REMOTE_ADDR'] . "\n" .
			'- Date : ' . date('F j, Y @ H:i:s') . ' (UTC '. date('O') . ")\n" .
			'- URL  : ';
		if ( is_multisite() ) {
			$message .= network_home_url() . "\n";
		} else {
			$message .= home_url() . "\n";
		}
		wp_mail( $nfw_options['alert_email'], $subject, $message );
	}
}
/* ================================================================== */

// EOF //
?>