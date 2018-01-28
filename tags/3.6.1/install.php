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

if ( ( is_multisite() ) && (! current_user_can( 'manage_network' ) ) ) {
	return;
}

// Set this to 1 if you don't want to receive a welcome email:
if (! defined('DONOTEMAIL') ) {
	define('DONOTEMAIL', 0);
}

@error_reporting(-1);
@ini_set('display_errors', '1');


if ( isset( $_POST["select_mode"] ) ) {
	if ( $_POST["select_mode"] == "wpwaf" ) {
		$_SESSION['waf_mode'] = "wpwaf";
	} elseif ( $_POST["select_mode"] == "fullwaf" ) {
		$_SESSION['waf_mode'] = "fullwaf";
	}
}
require( __DIR__ . '/lib/install_wpwaf.php' );
require( __DIR__ . '/lib/install_fullwaf.php' );


if ( empty( $_REQUEST['nfw_act'] ) ) {
	nfw_welcome();
	return;
}

if ( $_REQUEST['nfw_act'] == 'create_log_dir' ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'create_log_dir') ) {
		wp_nonce_ays('create_log_dir');
	}
	nfw_create_log_dir();
	return;
}

/* ------------------------------------------------------------------ */
// WordPress WAF mode:

if ( $_REQUEST['nfw_act'] == 'save_changes_wpwaf' ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'save_changes_wpwaf') ) {
		wp_nonce_ays('save_changes_wpwaf');
	}
	nfw_save_changes_wpwaf();
	return;
}

/* ------------------------------------------------------------------ */
// Full WAF mode:

if ( $_REQUEST['nfw_act'] == 'presave' ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'presave') ) {
		wp_nonce_ays('presave');
	}
	nfw_presave();

} elseif ( $_REQUEST['nfw_act'] == 'integration' ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'integration') ) {
		wp_nonce_ays('integration');
	}
	nfw_integration();

} elseif ( $_REQUEST['nfw_act'] == 'postsave' ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'postsave') ) {
		wp_nonce_ays('postsave');
	}
	nfw_postsave();

}

return;

/* ------------------------------------------------------------------ */

function nfw_welcome() {

	if ( isset($_SESSION['abspath']) ) {
		unset($_SESSION['abspath']);
	}
	if ( isset($_SESSION['http_server']) ) {
		unset($_SESSION['http_server']);
	}
	if ( isset($_SESSION['php_ini_type']) ) {
		unset($_SESSION['php_ini_type']);
	}
	if (isset($_SESSION['email_install']) ) {
		unset($_SESSION['email_install']);
	}
	if (isset($_SESSION['default_conf']) ) {
		unset($_SESSION['default_conf']);
	}
	if (isset($_SESSION['waf_mode']) ) {
		unset($_SESSION['waf_mode']);
	}
	if (isset($_SESSION['wp_config']) ) {
		unset($_SESSION['wp_config']);
	}

	$_SESSION['nfw_goodguy'] = true;

?>
<div class="wrap">
	<div style="width:33px;height:33px;background-image:url(<?php echo plugins_url() ?>/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h1>NinjaFirewall (WP Edition)</h1>
	<?php
	if (file_exists( dirname(plugin_dir_path(__FILE__)) . '/nfwplus') ) {
		echo '<br /><div class="error settings-error"><p>' . sprintf( __('Error: You have a copy of NinjaFirewall (%s) installed.<br />Please uninstall it completely before attempting to install NinjaFirewall (WP Edition).', 'ninjafirewall'), '<font color=#21759B>WP+</font> Edition' ) . '</p></div></div></div></div></div></div></body></html>';
		exit;
	}
	?>
	<p><?php _e('Thank you for using NinjaFirewall', 'ninjafirewall') ?> (WP Edition).  <?php _e('This installer will help you to make the setup process as quick and easy as possible.', 'ninjafirewall') ?></p>

	<p><?php _e('Although NinjaFirewall looks like a regular security plugin, it is not. It can be installed and configured from the WordPress admin console, but it is a stand-alone Web Application Firewall that sits in front of WordPress.', 'ninjafirewall') ?> <?php _e('It can run in two different modes: <b>Full WAF</b> or <b>WordPress WAF</b> modes.', 'ninjafirewall') ?></p>

	<h3><?php _e('Full WAF mode', 'ninjafirewall') ?></h3>

	<p><?php _e('In <b>Full WAF</b> mode, NinjaFirewall will hook, scan, reject or sanitise any HTTP and HTTPS request sent to a PHP script before it reaches WordPress, its plugins or even the database. All scripts located inside the blog installation directories and sub-directories will be protected, including those that aren\'t part of the WordPress package. Even encoded PHP scripts (e.g., ionCube), potential backdoors and shell scripts (e.g., c99, r57) will be filtered by NinjaFirewall.', 'ninjafirewall') ?>
	<br />
	<?php printf( __('That makes it a true firewall and gives you the highest possible level of protection: <a href="%s" title="%s">security without compromise</a>.', 'ninjafirewall'), 'https://blog.nintechnet.com/introduction-to-ninjafirewall-filtering-engine/', 'An introduction to NinjaFirewall filtering engine.') ?>
	<br />
	<?php printf( __('To run NinjaFirewall in <b>Full WAF</b> mode, your server must allow the use of the <code>auto_prepend_file</code> PHP directive. It is required to instruct the PHP interpreter to load the firewall before WordPress or any other script. Most of the time it works right out of the box, or may require <a href="%s" title="%s">some very little tweaks</a>. But in a few cases, mostly because of some shared hosting plans restrictions, it may simply not work at all.','ninjafirewall'), 'https://blog.nintechnet.com/troubleshoot-ninjafirewall-installation-problems/', 'Troubleshoot NinjaFirewall installation problems.') ?></p>

	<h3><?php _e('WordPress WAF mode', 'ninjafirewall') ?></h3>

	<p><?php _e('The <b>WordPress WAF</b> mode requires to load NinjaFirewall via the WordPress wp-config.php script. This process makes it easy to setup and the installation will always be successful, regardless of your hosting plan restrictions.', 'ninjafirewall') ?> <?php _e('NinjaFirewall will still load before WordPress, its plugins and the database and will run as fast as the <b>Full WAF</b> mode.', 'ninjafirewall') ?>
	<br />
	<?php _e('However, the downside of this mode is that NinjaFirewall will be able to hook and filter HTTP requests sent to WordPress only. A few features such as File Guard, the URL Access Control and Web Filter (WP+ Edition only) will be limited.', 'ninjafirewall') ?>
	<br />
	<?php _e('Despite being less powerful than the <b>Full WAF</b> mode, it still offers a level of protection and performance higher than any other security plugin.', 'ninjafirewall') ?></p>


	<h3><?php _e('Installation', 'ninjafirewall') ?></h3>

	<p><?php _e('We recommend to select the <b>Full WAF</b> mode option first. If it fails, this installer will let you switch to the <b>WordPress WAF</b> mode easily.', 'ninjafirewall' ) ?></p>

	<form method="post">

		<p><label><input type="radio" name="select_mode" value="fullwaf" checked="checked" /><strong><?php _e('Full WAF mode (recommended)', 'ninjafirewall') ?></strong></label></p>

		<p><label><input type="radio" name="select_mode" value="wpwaf" /><strong><?php _e('WordPress WAF mode', 'ninjafirewall') ?></strong></label></p>

		<p><input class="button-primary" type="submit" name="nextstep" value="<?php _e('Next Step', 'ninjafirewall') ?> &#187;" /></p>

		<input type="hidden" name="nfw_act" value="create_log_dir" />
		<?php wp_nonce_field('create_log_dir', 'nfwnonce', 0); ?>

	</form>
</div>
<?php

}

/* ------------------------------------------------------------------ */

function nfw_create_log_dir() {

	if (! is_writable(NFW_LOG_DIR) ) {
		$err = sprintf( __('NinjaFirewall cannot create its <code>nfwlog/</code>log and cache folder; please make sure that the <code>%s</code> directory is writable', 'ninjafirewall'), htmlspecialchars(NFW_LOG_DIR) );
	} else {
		if (! file_exists(NFW_LOG_DIR . '/nfwlog') ) {
			mkdir( NFW_LOG_DIR . '/nfwlog', 0755);
		}
		if (! file_exists(NFW_LOG_DIR . '/nfwlog/cache') ) {
			mkdir( NFW_LOG_DIR . '/nfwlog/cache', 0755);
		}

		$deny_rules = "<Files \"*\">
	<IfModule mod_version.c>
		<IfVersion < 2.4>
			Order Deny,Allow
			Deny from All
		</IfVersion>
		<IfVersion >= 2.4>
			Require all denied
		</IfVersion>
	</IfModule>
	<IfModule !mod_version.c>
		<IfModule !mod_authz_core.c>
			Order Deny,Allow
			Deny from All
		</IfModule>
		<IfModule mod_authz_core.c>
			Require all denied
		</IfModule>
	</IfModule>
</Files>";

		touch( NFW_LOG_DIR . '/nfwlog/index.html' );
		touch( NFW_LOG_DIR . '/nfwlog/cache/index.html' );
		@file_put_contents(NFW_LOG_DIR . '/nfwlog/.htaccess', $deny_rules, LOCK_EX);
		@file_put_contents(NFW_LOG_DIR . '/nfwlog/cache/.htaccess', $deny_rules, LOCK_EX);
		@file_put_contents(NFW_LOG_DIR . '/nfwlog/readme.txt', __("This is NinjaFirewall's logs, loader and cache directory. DO NOT alter or remove it as long as NinjaFirewall is running!", 'ninjafirewall'), LOCK_EX);

		// Return if we are going to run in "WordPress WAF" mode:
		if ( $_SESSION['waf_mode'] == "wpwaf" ) {
			nfw_integration_wpwaf();
			return;
		}

		$loader = "<?php
// ===============================================================//
// NinjaFirewall's loader.                                        //
// DO NOT alter or remove it as long as NinjaFirewall is running! //
// ===============================================================//
if ( file_exists('" . plugin_dir_path(__FILE__) . 'lib/firewall.php' . "') ) {
	@include('" . plugin_dir_path(__FILE__) . 'lib/firewall.php' . "');
}
// EOF
";
		file_put_contents(NFW_LOG_DIR . '/nfwlog/ninjafirewall.php', $loader, LOCK_EX);
	}
	if ( empty($err) ) {
		nfw_get_abspath();
		return;
	}
	echo '
<div class="wrap">
	<div style="width:33px;height:33px;background-image:url(' . plugins_url() . '/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h1>' . __('NinjaFirewall (WP Edition)', 'ninjafirewall') . '</h1>
	<br />
	 <div class="error settings-error"><p>' . $err . '</p></div>

	<br />
	<br />
	<form method="post">
		<p><input class="button-primary" type="submit" name="Save" value="' . __('Try again', 'ninjafirewall') . ' &#187;" /></p>
		<input type="hidden" name="nfw_act" value="create_log_dir" />' .  wp_nonce_field('create_log_dir', 'nfwnonce', 0) . '
	</form>
</div>';

}

/* ------------------------------------------------------------------ */

function welcome_email() {

	if ( empty($_SESSION['email_install']) ) {
		if ( $recipient = get_option('admin_email') ) {
			$subject = '[NinjaFirewall] ' . __('Quick Start, FAQ & Troubleshooting Guide', 'ninjafirewall');
			$message = __('Hi,', 'ninjafirewall') . "\n\n";

			$message.= __('This is NinjaFirewall\'s installer. Below are some helpful info and links you may consider reading before using NinjaFirewall.', 'ninjafirewall') . "\n\n";

			$message.= '1) ' . __('Troubleshooting:', 'ninjafirewall') . "\n";
			$message.= 'https://nintechnet.com/ninjafirewall/wp-edition/help/?troubleshooting ' . "\n\n";

			$message.= __('-Locked out of your site / Fatal error / WordPress crash?', 'ninjafirewall') . "\n";
			$message.= __('-Failed installation ("Error: The firewall is not loaded")?', 'ninjafirewall') . "\n";
			$message.= __('-Blank page after INSTALLING NinjaFirewall?', 'ninjafirewall') . "\n";
			$message.= __('-Blank page after UNINSTALLING NinjaFirewall?', 'ninjafirewall') . "\n";
			$message.= __('-500 Internal Server Error?', 'ninjafirewall') . "\n";
			$message.= __('-"Cannot connect to WordPress database" error message?', 'ninjafirewall') . "\n";
			$message.= __('-How to disable NinjaFirewall?', 'ninjafirewall') . "\n";
			$message.= __('-Lost password (brute-force protection)?', 'ninjafirewall') . "\n";
			$message.= __('-Blocked visitors (see below)?', 'ninjafirewall') . "\n";
			$message.= __('-Exporting NinjaFirewall\'s configuration', 'ninjafirewall') . "\n\n";

			$message.= '2) ' . __('-NinjaFirewall (WP Edition) troubleshooter script', 'ninjafirewall') . "\n";
			$message.= 'https://nintechnet.com/share/wp-check.txt ' . "\n\n";
			$message.=  __('-Rename this file to "wp-check.php".', 'ninjafirewall') . "\n";
			$message.=  __('-Upload it into your WordPress root folder.', 'ninjafirewall') . "\n";
			$message.=  __('-Goto http://YOUR WEBSITE/wp-check.php.', 'ninjafirewall') . "\n";
			$message.=  __('-Delete it afterwards.', 'ninjafirewall') . "\n\n";

			$message.= '3) '. __('FAQ:', 'ninjafirewall') . "\n";
			$message.= 'https://nintechnet.com/ninjafirewall/wp-edition/help/?faq ' . "\n\n";

			$message.= __('-Why is NinjaFirewall different from other security plugins for WordPress?', 'ninjafirewall') . "\n";
			$message.= __('-Do I need root privileges to install NinjaFirewall?', 'ninjafirewall') . "\n";
			$message.= __('-Does it work with Nginx?', 'ninjafirewall') . "\n";
			$message.= __('-Do I need to alter my PHP scripts?', 'ninjafirewall') . "\n";
			$message.= __('-Will NinjaFirewall detect the correct IP of my visitors if I am behind a CDN service like Cloudflare or Incapsula?', 'ninjafirewall') . "\n";
			$message.= __('-I moved my wp-config.php file to another directory. Will it work with NinjaFirewall?', 'ninjafirewall') . "\n";
			$message.= __('-Will it slow down my site?', 'ninjafirewall') . "\n";
			$message.= __('-Is there a Microsoft Windows version?', 'ninjafirewall') . "\n";
			$message.= __('-Can I add/write my own security rules?', 'ninjafirewall') . "\n";
			$message.= __('-Can I migrate my site(s) with NinjaFirewall installed?', 'ninjafirewall') . "\n\n";

			$message.= '4) '. __('Must Read:', 'ninjafirewall') . "\n\n";

			$message.= __('-An introduction to NinjaFirewall filtering engine:', 'ninjafirewall') . "\n";
			$message.= 'https://blog.nintechnet.com/introduction-to-ninjafirewall-filtering-engine/ ' . "\n\n";

			$message.= __('-Testing NinjaFirewall without blocking your visitors:', 'ninjafirewall') . "\n";
			$message.= 'https://blog.nintechnet.com/testing-ninjafirewall-without-blocking-your-visitors/ ' . "\n\n";

			$message.= __('-Add your own code to the firewall: the ".htninja" file:', 'ninjafirewall') . "\n";
			$message.= 'https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja ' . "\n\n";

			$message.= __('-Restricting access to NinjaFirewall settings:', 'ninjafirewall') . "\n";
			$message.= 'https://blog.nintechnet.com/restricting-access-to-ninjafirewall-wp-edition-settings/ ' . "\n\n";

			$message.= __('-Upgrading to PHP 7 with NinjaFirewall installed:', 'ninjafirewall') . "\n";
			$message.= 'https://blog.nintechnet.com/upgrading-to-php-7-with-ninjafirewall-installed/ ' . "\n\n";

			$message.= __('-Keep your blog protected against the latest vulnerabilities:', 'ninjafirewall') . "\n";
			$message.= 'https://blog.nintechnet.com/ninjafirewall-wpwp-introduces-automatic-updates-for-security-rules ' . "\n\n";

			$message.= __('-NinjaFirewall Referral Program:', 'ninjafirewall') . "\n";
			$message.= 'https://nintechnet.com/referral/ ' . "\n\n";

			$message.= '5) '. __('Help & Support Links:', 'ninjafirewall') . "\n\n";

			$message.= __('-Each page of NinjaFirewall includes a contextual help: click on the "Help" menu tab located in the upper right corner of the corresponding page.', 'ninjafirewall') . "\n";
			$message.= __('-Online documentation is also available here:', 'ninjafirewall'). ' https://nintechnet.com/ninjafirewall/wp-edition/doc/ ' . "\n";
			$message.= __('-The WordPress support forum:', 'ninjafirewall') .' http://wordpress.org/support/plugin/ninjafirewall ' . "\n";
			$message.= __('-Updates info are available via Twitter:', 'ninjafirewall') .' https://twitter.com/nintechnet ' . "\n\n";

			$message.= 'NinjaFirewall (WP Edition) - https://nintechnet.com/ ' . "\n\n";

			if (! DONOTEMAIL ) {
				wp_mail( $recipient, $subject, $message );
				$_SESSION['email_install'] = $recipient;
			}
		}
	}
}

/* ------------------------------------------------------------------ */

function nfw_firewalltest() {
	?>
<div class="wrap">
	<div style="width:33px;height:33px;background-image:url(<?php echo plugins_url() ?>/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h1>NinjaFirewall (WP Edition)</h1>

	<?php
	if (! defined('NFW_STATUS') || NFW_STATUS != 20 ) {

		echo '<div class="error settings-error"><p>'. __('Error: The firewall is not loaded.', 'ninjafirewall'). '</p></div>
		<h3>'. __('Suggestions:', 'ninjafirewall'). '</h3>
		<ul>';
		if ($_SESSION['http_server'] == 1) {

			echo '<li>&#8729; '. __('You selected <code>Apache + PHP module</code> as your HTTP server and PHP SAPI. Maybe your HTTP server is <code>Apache + CGI/FastCGI</code>?', 'ninjafirewall'). '
			<br />
			'. __('You can click the "Go Back" button and try to select another HTTP server type.', 'ninjafirewall'). '</li><br /><br />';


		} elseif( $_SESSION['http_server'] == 4 ) {
			echo '<li>&#8729; '. __('You have selected LiteSpeed as your HTTP server. Did you enable the "AllowOverride" directive from its admin panel? Make sure it is enabled, restart LiteSpeed and then, click the "Test Again" button below.', 'ninjafirewall'). '</li>
				<form method="POST">
					<input type="submit" class="button-secondary" value="'. __('Test Again', 'ninjafirewall'). '" />
					<input type="hidden" name="nfw_act" value="postsave" />
					<input type="hidden" name="makechange" value="usr" />
					<input type="hidden" name="nfw_firstrun" value="1" />'. wp_nonce_field('postsave', 'nfwnonce', 0) .'
				</form><br />';

		} else {

			if ($_SESSION['php_ini_type'] == 2) {
				echo '<li>&#8729; '. __('You have selected <code>.user.ini</code> as your PHP initialization file. Unlike <code>php.ini</code>, <code>.user.ini</code> files are not reloaded immediately by PHP, but every five minutes. If this is your own server, restart Apache (or PHP-FPM if applicable) to force PHP to reload it, otherwise please <strong>wait up to five minutes</strong> and then, click the "Test Again" button below.', 'ninjafirewall'). '</li>
				<form method="POST">
					<input type="submit" class="button-secondary" value="'. __('Test Again', 'ninjafirewall'). '" />
					<input type="hidden" name="nfw_act" value="postsave" />
					<input type="hidden" name="makechange" value="usr" />
					<input type="hidden" name="nfw_firstrun" value="1" />'. wp_nonce_field('postsave', 'nfwnonce', 0) .'
				</form><br /><br />';
			}
			if ($_SESSION['http_server'] == 2) {
				if ( preg_match('/apache/i', PHP_SAPI) ) {

					echo '<li>&#8729; '. __('You selected <code>Apache + CGI/FastCGI</code> as your HTTP server and PHP SAPI. Maybe your HTTP server is <code>Apache + PHP module</code>?', 'ninjafirewall'). '
					<br />
					'. __('You can click the "Go Back" button and try to select another HTTP server type.', 'ninjafirewall'). '</li><br />';
				}
			}
			echo '<li>&#8729; '. __('Maybe you did not select the correct PHP INI ?', 'ninjafirewall'). '
			<br />
			'. __('You can click the "Go Back" button and try to select another one.', 'ninjafirewall'). '</li>';
		}

		echo '<form method="POST">
		<p><input type="submit" class="button-secondary" value="&#171; '. __('Go Back', 'ninjafirewall'). '" /></p>
		<input type="hidden" name="abspath" value="' . $_SESSION['abspath'] . '" />
		<input type="hidden" name="nfw_act" value="presave" />
		<input type="hidden" name="nfw_firstrun" value="1" />'. wp_nonce_field('presave', 'nfwnonce', 0) .'
		</form>
		<br />
			<li>&#8729; '. sprintf( __('If none of the above suggestions work, you can still install NinjaFirewall in %s mode by clicking the button below. Setup is easy and will always work.', 'ninjafirewall'), '<a href="https://blog.nintechnet.com/full_waf-vs-wordpress_waf/">WordPress WAF</a>' ) . '</li>
				<form method="post">
					<input type="hidden" name="select_mode" value="wpwaf" />
					<input type="hidden" name="nfw_act" value="create_log_dir" />
					' . wp_nonce_field('create_log_dir', 'nfwnonce', 0) . '
					<p><input class="button-secondary" type="submit" name="nextstep" value="' . __('Switch to the WordPress WAF mode installer &#187;', 'ninjafirewall') . '" /></p>
				</form>
		</ul>
		<br />
		<h3>'. __('Need help? Check our blog:', 'ninjafirewall'). ' <a href="https://blog.nintechnet.com/troubleshoot-ninjafirewall-installation-problems/" target="_blank">Troubleshoot NinjaFirewall installation problems</a>.</h3>
</div>';
	}
}

/* ------------------------------------------------------------------ */

function nfw_ini_data() {

	if (! defined('HTACCESS_BEGIN') ) {
		define( 'HTACCESS_BEGIN', '# BEGIN NinjaFirewall' );
		define( 'HTACCESS_DATA',  '<IfModule mod_php' . PHP_MAJOR_VERSION . '.c>' . "\n" .
									     '   php_value auto_prepend_file ' . NFW_LOG_DIR . '/nfwlog/ninjafirewall.php' . "\n" .
									     '</IfModule>');
		define( 'LITESPEED_DATA', 'php_value auto_prepend_file ' . NFW_LOG_DIR . '/nfwlog/ninjafirewall.php');
		define( 'SUPHP_DATA',     '<IfModule mod_suphp.c>' . "\n" .
									     '   suPHP_ConfigPath ' . rtrim($_SESSION['abspath'], '/') . "\n" .
									     '</IfModule>');
		define( 'HTACCESS_END',   '# END NinjaFirewall' );
		define( 'PHPINI_BEGIN',   '; BEGIN NinjaFirewall' );
		define( 'PHPINI_DATA',    'auto_prepend_file = ' . NFW_LOG_DIR . '/nfwlog/ninjafirewall.php' );
		define( 'PHPINI_END',     '; END NinjaFirewall' );
	}
}

/* ------------------------------------------------------------------ */

function nfw_wpconfig_data() {

	if (! defined('WP_CONFIG_BEGIN') ) {
		define( 'WP_CONFIG_BEGIN', '// BEGIN NinjaFirewall' );
		define( 'WP_CONFIG_DATA',
			'if ( file_exists("' . plugin_dir_path( __FILE__ ) . 'lib/firewall.php' . '") && ! defined("NFW_STATUS") ) {' . "\n" .
			'   @include_once("' . plugin_dir_path( __FILE__ ) . 'lib/firewall.php' . '");' . "\n" .
			'   define("NFW_WPWAF", 1);' . "\n" .
			'}' );
		define( 'WP_CONFIG_END', '// END NinjaFirewall' );
	}

}

/* ------------------------------------------------------------------ */

function nfw_default_conf() {

	$nfw_rules = array();

	$nfw_options = array(
		'logo'				=> plugins_url() . '/ninjafirewall/images/ninjafirewall_75.png',
		'enabled'			=> 1,
		'ret_code'			=> 403,
		'anon_ip'			=> 0,
		'blocked_msg'		=> base64_encode(NFW_DEFAULT_MSG),
		'debug'				=> 0,
		'scan_protocol'	=> 3,
		'uploads'			=> 0,
		'sanitise_fn'		=> 0,
		'get_scan'			=> 1,
		'get_sanitise'		=> 0,
		'post_scan'			=> 1,
		'post_sanitise'	=> 0,
		'cookies_scan'		=> 1,
		'cookies_sanitise'=> 0,
		'ua_scan'			=> 1,
		'ua_sanitise'		=> 1,
		'referer_scan'		=> 0,
		'referer_sanitise'=> 1,
		'referer_post'		=> 0,
		'no_host_ip'		=> 0,
		'allow_local_ip'	=> 0,
		'php_errors'		=> 1,
		'php_self'			=> 1,
		'php_path_t'		=> 1,
		'php_path_i'		=> 1,
		'wp_dir'				=> '/wp-admin/(?:css|images|includes|js)/|' .
									'/wp-includes/(?:(?:css|images|js(?!/tinymce/wp-tinymce\.php)|theme-compat)/|[^/]+\.php)|' .
									'/'. basename(WP_CONTENT_DIR) .'/(?:uploads|blogs\.dir)/',
		'no_post_themes'	=> 0,
		'force_ssl'			=> 0,
		'disallow_edit'	=> 0,
		'disallow_mods'	=> 0,
		'wl_admin'			=> 1,
		// v1.0.4
		'a_0' 				=> 1,
		'a_11' 				=> 1,
		'a_12' 				=> 1,
		'a_13' 				=> 0,
		'a_14' 				=> 0,
		'a_15' 				=> 1,
		'a_16' 				=> 0,
		'a_21' 				=> 1,
		'a_22' 				=> 1,
		'a_23' 				=> 0,
		'a_24' 				=> 0,
		'a_31' 				=> 1,
		// v1.3.3 :
		'a_41' 				=> 1,
		// v1.3.4 :
		'a_51' 				=> 1,
		'sched_scan'		=> 0,
		'report_scan'		=> 0,
		// v1.7 (daily report cronjob) :
		'a_52' 				=> 1,
		// v3.4:
		'a_53' 				=> 1,

		'alert_email'	 	=> get_option('admin_email'),
		// v1.1.0 :
		'alert_sa_only'	=> 1,
		'nt_show_status'	=> 1,
		'post_b64'			=> 1,
		// v1.1.2 :
		'no_xmlrpc'			=> 0,
		// v1.7 :
		'no_xmlrpc_multi'	=> 0,
		// v3.3.2
		'no_xmlrpc_pingback'=> 0,

		// v1.1.3 :
		'enum_archives'	=> 0,
		'enum_login'		=> 0,
		// v1.1.6 :
		'request_sanitise'=> 0,
		// v1.2.1 :
		'fg_enable'			=>	0,
		'fg_mtime'			=>	10,
		'fg_exclude'		=>	'',
		// Updates :
		'enable_updates'	=>	1,
		'sched_updates'	=>	1,
		'notify_updates'	=>	1,
		// Centralized Logging:
		'clogs_enable'		=>	0,
		'clogs_pubkey'		=>	'',
	);
	// v1.3.1 :
	// Some compatibility checks:
	// 1. header_register_callback(): requires PHP >=5.4
	// 2. headers_list() and header_remove(): some hosts may disable them.
	if ( function_exists('header_register_callback') && function_exists('headers_list') && function_exists('header_remove') ) {
		$nfw_options['response_headers'] = '00010000';
	}

	define('NFUPDATESDO', 2);
	@nf_sub_updates();

	if (! $nfw_rules = @unserialize(NFW_RULES) ) {
		$err_msg = '<p><strong>'. __('Error: The installer cannot download the security rules from wordpress.org website.', 'ninjafirewall') . '</strong></p>';
		$err_msg.= '<ol><li>'. __('The server may be temporarily down or you may have network connectivity problems? Please try again in a few minutes.', 'ninjafirewall') . '</li>';
		$err_msg.= '<li>'. __('NinjaFirewall downloads its rules over an HTTPS secure connection. Maybe your server does not support SSL? You can force NinjaFirewall to use a non-secure HTTP connection by adding the following directive to your <strong>wp-config.php</strong> file:', 'ninjafirewall') . '<p><code>define("NFW_DONT_USE_SSL", 1);</code></p></li></ol>';
		exit("<br /><div class='error notice is-dismissible'>{$err_msg}</div></div></div></div></div></body></html>");
	}

	$nfw_options['engine_version'] = NFW_ENGINE_VERSION;
	$nfw_options['rules_version']  = NFW_NEWRULES_VERSION; // downloaded rules

	if ( strlen( $_SERVER['DOCUMENT_ROOT'] ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['cha'][1]['wha'] = str_replace( '/', '/[./]*', $_SERVER['DOCUMENT_ROOT'] );
	} elseif ( strlen( getenv( 'DOCUMENT_ROOT' ) ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['cha'][1]['wha'] = str_replace( '/', '/[./]*', getenv( 'DOCUMENT_ROOT' ) );
	} else {
		$nfw_rules[NFW_DOC_ROOT]['ena'] = 0;
	}

	// Enable PHP object injection rules (since v3.5.3):
	$nfw_rules[NFW_OBJECTS]['ena'] = 1;

	nfw_update_option( 'nfw_options', $nfw_options);
	nfw_update_option( 'nfw_rules', $nfw_rules);

	if ( wp_next_scheduled( 'nfwgccron' ) ) {
		wp_clear_scheduled_hook( 'nfwgccron' );
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
	nfw_get_blogtimezone();
	wp_schedule_event( strtotime( date('Y-m-d 00:00:05', strtotime("+1 day")) ), 'daily', 'nfdailyreport');
	wp_schedule_event( time() + 3600, 'hourly', 'nfsecupdates');
	wp_schedule_event( time() + 1800, 'hourly', 'nfwgccron' );

	$_SESSION['default_conf'] = 1;
}

/* ------------------------------------------------------------------ */
// EOF //
