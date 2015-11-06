<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 +---------------------------------------------------------------------+
 | REVISION: 2015-10-30 19:31:07                                       |
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

// Force errors display during the installation:
@error_reporting(-1);
@ini_set('display_errors', '1');

if ( empty( $_REQUEST['nfw_act'] ) ) {
	nfw_welcome();

} elseif ( $_REQUEST['nfw_act'] == 'logdir' ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'logdir') ) {
		wp_nonce_ays('logdir');
	}
	nfw_logdir();

} elseif ( $_REQUEST['nfw_act'] == 'presave' ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'presave') ) {
		wp_nonce_ays('presave');
	}
	nfw_presave(0);

} elseif ( $_REQUEST['nfw_act'] == 'integration' ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'integration') ) {
		wp_nonce_ays('integration');
	}
	nfw_integration('');

} elseif ( $_REQUEST['nfw_act'] == 'postsave' ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'postsave') ) {
		wp_nonce_ays('postsave');
	}
	nfw_postsave();

}

return;

/* ------------------------------------------------------------------ */ // i18n+

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

?>
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url(<?php echo plugins_url() ?>/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>NinjaFirewall (WP Edition)</h2>
	<br />
	<?php
	if (file_exists( dirname(plugin_dir_path(__FILE__)) . '/nfwplus') ) {
		echo '<br /><div class="error settings-error"><p>' . sprintf( __('Error: You have a copy of NinjaFirewall (%s) installed.<br />Please uninstall it completely before attempting to install NinjaFirewall (WP Edition).', 'ninjafirewall'), '<font color=#21759B>WP+</font> Edition' ) . '</p></div></div></div></div></div></div></body></html>';
		exit;
	}
	?>
	<p><?php _e('Thank you for using NinjaFirewall', 'ninjafirewall') ?> (WP Edition).</p>
	<p><?php _e('This installer will help you to make the setup process as quick and easy as possible. But before doing so, please read carefully the following lines:', 'ninjafirewall') ?></p>
	<p><?php _e('Although NinjaFirewall looks like a regular plugin, it is not. It can be installed and configured from WordPress admin console, but it is a stand-alone Web Application Firewall that sits in front of WordPress. That means that it will hook, scan, reject and/or sanitise any HTTP/HTTPS request sent to a PHP script before it reaches WordPress and any of its plugins. All scripts located inside the blog installation directories and sub-directories will be protected, including those that aren\'t part of the WordPress package. Even encoded PHP scripts (e.g., ionCube) or any potential backdoor/shell script (e.g., c99, r57) will be filtered by NinjaFirewall.', 'ninjafirewall') ?></p>
	<p><?php _e('That\'s cool and makes NinjaFirewall a true firewall. And probably the most powerful security applications for WordPress. But just like any firewall, if you misuse it, you can get into serious problems and crash your site.', 'ninjafirewall') ?></p>
	<div class="updated settings-error">
	<br />
	1 - <?php _e('Do NOT rename, edit or delete its files or folders, even if it is disabled from the Plugins page.', 'ninjafirewall') ?>
	<br />
	2 - <?php _e('Do NOT migrate your site with NinjaFirewall installed. Export its configuration, uninstall it, migrate your site, reinstall NinjaFirewall and reimport its configuration.', 'ninjafirewall') ?>
	<br />
	<br />
	<center><img src="<?php echo plugins_url( '/images/icon_warn_16.png', __FILE__ ) ?>" border="0" height="16" width="16">&nbsp;<strong><?php _e('Failure to do so will almost always cause you to be locked out of your own site and/or to crash it.', 'ninjafirewall') ?></strong><br />&nbsp;</center>
	</div>
	<h3><?php _e('Privacy Policy', 'ninjafirewall') ?></h3>
	<?php printf( __('<a href="%s">NinTechNet</a> strictly follows the WordPress <a href="%s">Plugin Developer Guidelines</a>: our software, NinjaFirewall (WP Edition), is free, open source and fully functional, no "trialware", no "obfuscated code", no "crippleware", no "phoning home". It does not require a registration process or an activation key to be installed or used.', 'ninjafirewall'), 'http://nintechnet.com/', 'http://wordpress.org/plugins/about/guidelines/') ?>
	<br />
	<?php _e('Because we do not collect any user data, we do not even know that you are using (and hopefully enjoying!) our product.', 'ninjafirewall') ?>
	<br />
	<h3><?php _e('License', 'ninjafirewall') ?></h3>
	<?php _e('This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.', 'ninjafirewall') ?>
	<br />
	<?php _e('This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details (LICENSE.TXT).', 'ninjafirewall') ?>
	<br />
	<h3><?php _e('Installation help and troubleshooting', 'ninjafirewall') ?></h3>
	<?php printf( __('If you need technical support, please use our <a href="%s">support forum</a> at WordPress.org site.', 'ninjafirewall'), 'http://wordpress.org/support/plugin/ninjafirewall') ?>
	<br />
	<?php _e('Doc, FAQ and How-To are also available from our site:', 'ninjafirewall') ?> <a href="http://ninjafirewall.com/">http://ninjafirewall.com/</a>
	<br />
	<?php _e('Updates info are available via Twitter:', 'ninjafirewall') ?><br /><a href="https://twitter.com/nintechnet"><img border="0" src="<?php echo plugins_url( '/images/twitter_ntn.png', __FILE__ ) ?>" width="116" height="28" target="_blank"></a>
	<p style="color:red"><?php _e('Ensure that you have an FTP access to your website so that, if there was a problem during the installation of the firewall, you could undo the changes.', 'ninjafirewall') ?></p>
	<form method="post">
		<p><input class="button-primary" type="submit" name="Save" value="<?php _e('Enough chitchat, let\'s go!', 'ninjafirewall') ?> &#187;" /></p>
		<input type="hidden" name="nfw_act" value="logdir" />
		<?php wp_nonce_field('logdir', 'nfwnonce', 0); ?>
	</form>
</div>
<?php

}

/* ------------------------------------------------------------------ */ // i18n+

function nfw_logdir() {

	// We need to create our log & cache folder in the wp-content
	// directory or return an error right away if we cannot :
	if (! is_writable(NFW_LOG_DIR) ) {
		$err = sprintf( __('NinjaFirewall cannot create its <code>nfwlog/</code>log and cache folder; please make sure that the <code>%s</code> directory is writable', 'ninjafirewall'), htmlspecialchars(NFW_LOG_DIR) );
	} else {
		if (! file_exists(NFW_LOG_DIR . '/nfwlog') ) {
			mkdir( NFW_LOG_DIR . '/nfwlog', 0755);
		}
		if (! file_exists(NFW_LOG_DIR . '/nfwlog/cache') ) {
			mkdir( NFW_LOG_DIR . '/nfwlog/cache', 0755);
		}

		$deny_rules = <<<'DENY'
<Files "*">
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
</Files>
DENY;

		touch( NFW_LOG_DIR . '/nfwlog/index.html' );
		touch( NFW_LOG_DIR . '/nfwlog/cache/index.html' );
		@file_put_contents(NFW_LOG_DIR . '/nfwlog/.htaccess', $deny_rules, LOCK_EX);
		@file_put_contents(NFW_LOG_DIR . '/nfwlog/cache/.htaccess', $deny_rules, LOCK_EX);
		@file_put_contents(NFW_LOG_DIR . '/nfwlog/readme.txt', __("This is NinjaFirewall's logs and cache directory.", 'ninjafirewall'), LOCK_EX);
	}
	if ( empty($err) ) {
		nfw_chk_docroot( 0 );
		return;
	}
	echo '
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url(' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>' . __('NinjaFirewall (WP Edition)', 'ninjafirewall') . '</h2>
	<br />
	<br />
	 <div class="error settings-error"><p>' . $err . '</p></div>

	<br />
	<br />
	<form method="post">
		<p><input class="button-primary" type="submit" name="Save" value="' . __('Try again', 'ninjafirewall') . ' &#187;" /></p>
		<input type="hidden" name="nfw_act" value="logdir" />' .  wp_nonce_field('logdir', 'nfwnonce', 0) . '
	</form>
</div>';

}

/* ------------------------------------------------------------------ */ // i18n+

function nfw_chk_docroot($err) {

	// If the document_root is identical to ABSPATH, we jump to the next step :
	if ( $_SERVER['DOCUMENT_ROOT'] . '/' == ABSPATH ) {
		$_POST['abspath'] = ABSPATH;
		nfw_presave(0);
		return;
	}
	// Otherwise, ask the user for the full path to index.php :
	echo '
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url(' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>NinjaFirewall (WP Edition)</h2>
	<br />';
	// error ?
	if ( $err ) {
		echo '<div class="error settings-error"><p>' . __('Error:', 'ninjafirewall') . $err . '</p></div>';
	}
	echo '
	<form method="post">
	<p>' . sprintf(__('Your WordPress directory (%s) is different from your website document root (%s). Because it is possible to install WordPress into a subdirectory, but have the blog exist in the site root, NinjaFirewall needs to know the exact location of the site root.', 'ninjafirewall'), '<code>' . ABSPATH . '</code>', '<code>' . htmlspecialchars( $_SERVER['DOCUMENT_ROOT'] ) . '/</code>') . '</p>
	<p>' . sprintf( __('Please edit the path below only if you have manually modified your WordPress root directory as described in the <a href="%s">Giving WordPress Its Own Directory</a> article.', 'ninjafirewall'), 'http://codex.wordpress.org/Giving_WordPress_Its_Own_Directory') .'</p>
	<p><strong style="color:red">'. __('Most users should not change this value.', 'ninjafirewall') .'</strong></p>
	<p>'. __('Path to WordPress site root directory:', 'ninjafirewall') .' <input class="regular-text code" type="text" name="abspath" value="' . ABSPATH . '"></p>
	<br />
	<br />
		<input class="button-primary" type="submit" name="Save" value="'. __('Next Step', 'ninjafirewall') .' &#187;" />
		<input type="hidden" name="nfw_act" value="presave" />' . wp_nonce_field('presave', 'nfwnonce', 0) . '
	</form>
</div>';

}
/* ------------------------------------------------------------------ */ // i18n+

function nfw_presave($err) {

	if (empty ($_POST['abspath']) ) {
		nfw_chk_docroot( __('please enter the full path to WordPress folder.', 'ninjafirewall') );
		return;
	}
	$abspath = htmlspecialchars( rtrim( $_POST['abspath'], '/' ) );
	if (! file_exists( $abspath . '/index.php' ) ) {
		nfw_chk_docroot( sprintf( __('cannot find the %s directory! Please correct the full path to WordPress site root directory.', 'ninjafirewall'), '<code>' . $abspath . '/index.php</code>') );
		return;
	}

	$_SESSION['abspath'] = $abspath . '/';

	// Save the configuration to the DB :
	nfw_default_conf();

	// Send an welcome e-mail to the admin :
	welcome_email();

	// Let's try to detect the system configuration :
	$s1 = $s2 = $s3 = $s4 = $s5 = $s7 = '';
	$recommended = ' ' . __('(recommended)', 'ninjafirewall');
	if ( defined('HHVM_VERSION') ) {
		// HHVM
		$http_server = 7;
		$s7 = $recommended;
		$htaccess = 0;
		$php_ini = 0;
	} elseif ( preg_match('/apache/i', PHP_SAPI) ) {
		// Apache running php as a module :
		$http_server = 1;
		$s1 = $recommended;
		$htaccess = 1;
		$php_ini = 0;
	} elseif ( preg_match( '/litespeed/i', PHP_SAPI ) ) {
		// Because Litespeed can handle PHP INI and mod_php-like .htaccess,
		// we will create both of them as we have no idea which one should be used:
		$http_server = 4;
		$php_ini = 1;
		$htaccess = 1;
		$s4 = $recommended;
	} else {
		// PHP CGI: we will only require a PHP INI file:
		$php_ini = 1;
		$htaccess = 0;
		// Try to find out the HTTP server :
		if ( preg_match('/apache/i', $_SERVER['SERVER_SOFTWARE']) ) {
			$http_server = 2;
			$s2 = $recommended;
		} elseif ( preg_match('/nginx/i', $_SERVER['SERVER_SOFTWARE']) ) {
			$http_server = 3;
			$s3 = $recommended;
		} else {
			// Mark it as unknown, that is not important :
			$http_server = 5;
			$s5 = $recommended;
		}
	}

	?>
	<script>
	function popup(url,width,height,scroll_bar) {height=height+20;width=width+20;var str = "height=" + height + ",innerHeight=" + height;str += ",width=" + width + ",innerWidth=" + width;if (window.screen){var ah = screen.availHeight - 30;var aw = screen.availWidth -10;var xc = (aw - width) / 2;var yc = (ah - height) / 2;str += ",left=" + xc + ",screenX=" + xc;str += ",top=" + yc + ",screenY=" + yc;if (scroll_bar) {str += ",scrollbars=no";}else {str += ",scrollbars=yes";}str += ",status=no,location=no,resizable=yes";}win = open(url, "nfpop", str);setTimeout("win.window.focus()",1300);}
	function check_fields() {
		var ischecked = 0;
		for (var i = 0; i < document.presave_form.php_ini_type.length; i++) {
			if(document.presave_form.php_ini_type[i].checked) {
				ischecked = 1;
				break;
			}
		}
		// Dont warn if user selected Apache/mod_php5 or HHVM
		if (! ischecked && document.presave_form.http_server.value != 1 && document.presave_form.http_server.value != 7) {
			alert('<?php
			// translators: quotes (') must be escaped
			_e('Please select the PHP initialization file supported by your server.', 'ninjafirewall') ?>');
			return false;
		}
		return true;
	}
	function ini_toogle(what) {
		if (what == 1) {
			document.getElementById('trini').style.display = 'none';
			document.getElementById('hhvm').style.display = 'none';
		} else if(what == 7) {
			document.getElementById('trini').style.display = 'none';
			document.getElementById('hhvm').style.display = '';
		} else {
			document.getElementById('trini').style.display = '';
			document.getElementById('hhvm').style.display = 'none';
		}
	}
	</script>

	<?php

	echo '
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url(' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>NinjaFirewall (WP Edition)</h2>
	<br />';

	// Ensure the log directory is writable :
	if (! is_writable( NFW_LOG_DIR . '/nfwlog' ) ) {
		echo '<div class="error settings-error"><p>'. sprintf( __('Error: NinjaFirewall log directory is not writable (%s). Please chmod it to 0777 and reload this page.', 'ninjafirewall'), '<code>' . htmlspecialchars(NFW_LOG_DIR) . '/nfwlog/</code>') .'</p></div></div>';
		return;
	}

	// Error ?
	if ( $err ) {
		echo '<div class="error settings-error"><p>'. __('Error:', 'ninjafirewall') . ' ' . $err . '</p></div>';
	}

	?>
	<h3><?php _e('System configuration', 'ninjafirewall') ?></h3>
	<?php
	// Multisite ?
	if ( is_multisite() ) {
		echo '<p><img src="' . plugins_url( '/images/icon_warn_16.png', __FILE__ ) .'" border="0" height="16" width="16">&nbsp;<strong>'. __('Multisite network detected:', 'ninjafirewall') . '</strong> '. __('NinjaFirewall will protect all sites from your network and its configuration interface will be accessible only to the Super Admin from the network main site.', 'ninjafirewall') . '</p>';
	}
	?>
	<form method="post" name="presave_form" onSubmit="return check_fields();">
	<table class="form-table">

		<tr>
			<th scope="row"><?php _e('Select your HTTP server and your PHP server API', 'ninjafirewall') ?> (<code>SAPI</code>)</th>
			<td width="20">&nbsp;</td>
			<td>
				<select class="input" name="http_server" onchange="ini_toogle(this.value);">
					<option value="1"<?php selected($http_server, 1) ?>>Apache + PHP5 module<?php echo $s1 ?></option>
					<option value="2"<?php selected($http_server, 2) ?>>Apache + CGI/FastCGI<?php echo $s2 ?></option>
					<option value="6"<?php selected($http_server, 6) ?>>Apache + suPHP</option>
					<option value="3"<?php selected($http_server, 3) ?>>Nginx + CGI/FastCGI<?php echo $s3 ?></option>
					<option value="4"<?php selected($http_server, 4) ?>>Litespeed + LSAPI<?php echo $s4 ?></option>
					<option value="5"<?php selected($http_server, 5) ?>><?php _e('Other webserver + CGI/FastCGI', 'ninjafirewall') ?><?php echo $s5 ?></option>
					<option value="7"<?php selected($http_server, 7) ?>><?php _e('Other webserver + HHVM', 'ninjafirewall') ?><?php echo $s7 ?></option>
				</select>&nbsp;&nbsp;&nbsp;<span class="description"><a class="links" href="javascript:popup('<?php echo wp_nonce_url( '?page=NinjaFirewall&nfw_act=99', 'show_phpinfo', 'nfwnonce' ); ?>',700,500,0);"><?php _e('view PHPINFO', 'ninjafirewall') ?></a></span>
				<?php
				if ($http_server == 7) {
					echo '<p id="hhvm">';
				} else {
					echo '<p id="hhvm" style="display:none;">';
				}
				?>
				<?php sprintf( __('Please <a href="%s">check our blog</a> if you want to install NinjaFirewall on HHVM.', 'ninjafirewall'), '<a href="http://blog.nintechnet.com/installing-ninjafirewall-with-hhvm-hiphop-virtual-machine/">') ?></p>
			</td>
		</tr>

		<?php
		// We check in the document root if there is already a PHP INI file :
		$f1 = $f2 = $f3 = $php_ini_type = '';
		if ( file_exists( $_SESSION['abspath'] . 'php.ini') ) {
			if (empty($_SESSION['php_ini_type']) ) {
				$f1 = $recommended;
			}
			$php_ini_type = 1;
		} elseif ( file_exists( $_SESSION['abspath'] . '.user.ini') ) {
			if (empty($_SESSION['php_ini_type']) ) {
				$f2 = $recommended;
			}
			$php_ini_type = 2;
		} elseif ( file_exists( $_SESSION['abspath'] . 'php5.ini') ) {
			if (empty($_SESSION['php_ini_type']) ) {
				$f3 = $recommended;
			}
			$php_ini_type = 3;
		}

		if ($http_server == 1 || $http_server == 7) {
			// We don't need PHP INI if the server is running Apache/mod_php5 or HHVM :
			echo '<tr id="trini" style="display:none;">';
		} else {
			echo '<tr id="trini">';
		}
		?>
			<th scope="row"><?php _e('Select the PHP initialization file supported by your server', 'ninjafirewall') ?></th>
			<td width="20">&nbsp;</td>
			<td>
				<p><label><input type="radio" name="php_ini_type" value="1"<?php checked($php_ini_type, 1) ?>><code>php.ini</code></label><?php echo $f1 ?><br /><span class="description"><?php _e('Used by most shared hosting accounts.', 'ninjafirewall') ?></span></p>

				<p><label><input type="radio" name="php_ini_type" value="2"<?php checked($php_ini_type, 2) ?>><code>.user.ini</code></label><?php echo $f2 ?><br /><span class="description"><?php _e('Used by most dedicated/VPS servers, as well as shared hosting accounts that do not support php.ini', 'ninjafirewall') ?> (<a href="http://php.net/manual/en/configuration.file.per-user.php"><?php _e('more info', 'ninjafirewall') ?></a>).</span></p>

				<p><label><input type="radio" name="php_ini_type" value="3"<?php checked($php_ini_type, 3) ?>><code>php5.ini</code></label><?php echo $f3 ?><br /><span class="description"><?php printf( __('A few shared hosting accounts (some <a href="%s">Godaddy hosting plans</a>). Seldom used.', 'ninjafirewall'), 'https://support.godaddy.com/help/article/8913/what-filename-does-my-php-initialization-file-need-to-use' ) ?></span></p>
			</td>
		</tr>

	</table>
	<input type="submit" class="button-primary" name="next" value="<?php _e('Next Step', 'ninjafirewall') ?> &#187;">
	<input type="hidden" name="nfw_act" value="integration">
	<input type="hidden" name="abspath" value="<?php echo $_SESSION['abspath'] ?>">
	<?php wp_nonce_field('integration', 'nfwnonce', 0); ?>
	</form>
</div>
<?php
}

/* ------------------------------------------------------------------ */ // i18n+
function nfw_integration($err) {

	if ( empty($_SESSION['abspath']) ) {
		nfw_chk_docroot( __('please enter the full path to WordPress folder.', 'ninjafirewall') );
		return;
	}

	// HTTP server type:
	// 1: Apache + PHP5 module
	// 2: Apache + CGI/FastCGI
	// 3: Nginx
	// 4: Litespeed (either LSAPI or Apache-style configuration directives (php_value)
	// 5: Other + CGI/FastCGI
	// 6: Apache + suPHP
	// 7: Other + HHVM
	if ( empty($_POST['http_server']) || ! preg_match('/^[1-7]$/', $_POST['http_server']) ) {
		nfw_presave( __('select your HTTP server and PHP SAPI.', 'ninjafirewall') );
		return;
	}

	// We must have a PHP INI type, except if the server is running Apache/mod_php5 or HHVM:
	if ( preg_match('/^[2-6]$/', $_POST['http_server']) ) {
		if ( empty($_POST['php_ini_type']) || ! preg_match('/^[1-3]$/', $_POST['php_ini_type']) ) {
			nfw_presave( __('select the PHP initialization file supported by your server.', 'ninjafirewall') );
			return;
		}
	} else {
		$_POST['php_ini_type'] = 0;
	}

	nfw_ini_data();

	$_SESSION['http_server'] = $_POST['http_server'];
	$_SESSION['php_ini_type'] = @$_POST['php_ini_type'];

	$_SESSION['ini_write'] = $_SESSION['htaccess_write'] = 1;

	if ($_SESSION['php_ini_type'] == 1) {
		$php_file = 'php.ini';
	} elseif ($_SESSION['php_ini_type'] == 2) {
		$php_file = '.user.ini';
	} elseif ($_SESSION['php_ini_type'] == 3) {
		$php_file = 'php5.ini';
	} else {
		$php_file = 0;
	}
	// Ensure WP directory is writable :
	if ( is_writable($_SESSION['abspath']) ) {
		$_SESSION['abspath_writable'] = 1;
	} else {
		$_SESSION['abspath_writable'] = 0;
	}

	if ($_SESSION['http_server'] == 1) {
		$directives = __('In order to hook and protect all PHP files, NinjaFirewall needs to add some specific directives to your <code>.htaccess</code> located inside WordPress root directory. That file will have to be created or, if it exists, to be edited.', 'ninjafirewall');
	} elseif ($_SESSION['http_server'] == 4 || $_SESSION['http_server'] == 6) {
		$directives =  sprintf( __('In order to hook and protect all PHP files, NinjaFirewall needs to add some specific directives to your <code>.htaccess</code> and <code>%s</code> files located inside WordPress root directory. Those files will have to be created or, if they exist, to be edited.', 'ninjafirewall'), $php_file);
	} else {
		$directives =  sprintf( __('In order to hook and protect all PHP files, NinjaFirewall needs to add some specific directives to your %s file located inside WordPress root directory. That file will have to be created or, if it exists, to be edited.', 'ninjafirewall'), '<code>' . $php_file . '</code>');
	}
?>
<script>
	function diy_chg(what) {
		if (what == 'nfw') {
			document.getElementById('diy').style.display = 'none';
			document.getElementById('lnfw').style.display = '';
		} else {
			document.getElementById('diy').style.display = '';
			document.getElementById('lnfw').style.display = 'none';
		}
	}
</script>
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url(<?php echo plugins_url() ?>/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>NinjaFirewall (WP Edition)</h2>
	<br />
	<?php
	// Error ?
	if ( $err ) {
		echo '<div class="error settings-error"><p>' . __('Error:', 'ninjafirewall') . $err . '</p></div>';
	}
	?>
	<h3><?php _e('Firewall Integration', 'ninjafirewall') ?></h3>
	<?php
	// Skip that section if we are running with HHVM:
	if ($_SESSION['http_server'] != 7) {
		?>
		<p><?php echo $directives; ?> <?php _e('If your WordPress root directory is writable, the installer can make those changes for you.', 'ninjafirewall') ?></p>

		<li><?php _e('Checking if WordPress root directory is writable:', 'ninjafirewall') ?> <strong><?php
		if ( $_SESSION['abspath_writable']) {
			echo '<font color="green">' . __('YES', 'ninjafirewall') .'</font>';
		} else {
			echo '<font color="red">' . __('NO', 'ninjafirewall') .'</font>';
		}
		echo '</strong></li><br />';
	}

	$fdata = $height = '';

	$createfile = __('The <code>%s</code> file must be created, and the following lines of code added to it:', 'ninjafirewall');
	$add2file = __('The following <font color="red">red lines</font> of code must be added to your <code>%s</code> file.', 'ninjafirewall') .
					'<br />' .
					__('All other lines, if any, are the actual content of the file:', 'ninjafirewall');
	$not_writable = __('The file is not writable, I cannot make those changes for you.', 'ninjafirewall');

	// Apache mod_php5 : only .htaccess changes are required :
	if ($_SESSION['http_server'] == 1) {
		if ( file_exists($_SESSION['abspath'] . '.htaccess') ) {
			if (! is_writable($_SESSION['abspath'] . '.htaccess') ) {
				$_SESSION['htaccess_write'] = $_SESSION['abspath_writable'] = 0;
			}
			// Edit it :
			printf('<li>'. $add2file .'</li>', $_SESSION['abspath'] . '.htaccess');
			$fdata = file_get_contents($_SESSION['abspath'] . '.htaccess');
			$fdata = preg_replace( '/\s?'. HTACCESS_BEGIN .'.+?'. HTACCESS_END .'[^\r\n]*\s?/s' , "\n", $fdata);
			$fdata = "\n<font color='#444'>" . htmlentities($fdata) . '</font>';
			$height = 'height:150px;';
		} else {
			// Create it :
			printf('<li>'. $createfile .'</li>', $_SESSION['abspath'] . '.htaccess');
		}
		echo '<pre style="background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;' .
			$height . '">' . "\n" .
			'<font color="red">' . HTACCESS_BEGIN . "\n" . htmlentities(HTACCESS_DATA) . "\n" . HTACCESS_END . "\n" .
			'</font>' . $fdata . "\n" .
			'</pre><br />';
		if (empty($_SESSION['htaccess_write']) ) {
			echo '<img src="' . plugins_url( '/images/icon_warn_16.png', __FILE__ ) .'" border="0" height="16" width="16">&nbsp;' . $not_writable .'<br />';
		}
	// Litespeed : we create both INI and .htaccess files as we have
	// no way to know which one will be used :
	} elseif ($_SESSION['http_server'] == 4) {
		if ( file_exists($_SESSION['abspath'] . '.htaccess') ) {
			// Edit it :
			if (! is_writable($_SESSION['abspath'] . '.htaccess') ) {
				$_SESSION['htaccess_write'] = $_SESSION['abspath_writable'] = 0;
			}
			printf('<li>'. $add2file .'</li>', $_SESSION['abspath'] . '.htaccess');
			$fdata = file_get_contents($_SESSION['abspath'] . '.htaccess');
			$fdata = preg_replace( '/\s?'. HTACCESS_BEGIN .'.+?'. HTACCESS_END .'[^\r\n]*\s?/s' , "\n", $fdata);
			$fdata = "\n<font color='#444'>" . htmlentities($fdata) . '</font>';
			$height = 'height:150px;';
		} else {
			// Create it :
			printf('<li>'. $createfile .'</li>', $_SESSION['abspath'] . '.htaccess');
		}
		echo '<pre style="background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;' .
			$height . '">' . "\n" .
			'<font color="red">' . HTACCESS_BEGIN . "\n" . LITESPEED_DATA . "\n" . HTACCESS_END . "\n" .
			'</font>' . $fdata . "\n" .
			'</pre><br />';
		if (empty($_SESSION['htaccess_write']) ) {
			echo '<img src="' . plugins_url( '/images/icon_warn_16.png', __FILE__ ) .'" border="0" height="16" width="16">&nbsp;' . $not_writable .'<br />';
		}
		echo '<br /><br />';

		$fdata = $height = '';
		if ( file_exists($_SESSION['abspath'] . $php_file) ) {
			if (! is_writable($_SESSION['abspath'] . $php_file) ) {
				$_SESSION['ini_write'] = $_SESSION['abspath_writable'] = 0;
			}
			// Edit it :
			printf('<li>'. $add2file .'</li>', $_SESSION['abspath'] . $php_file);
			$fdata = file_get_contents($_SESSION['abspath'] . $php_file);
			$fdata = preg_replace( '/\s?'. PHPINI_BEGIN .'.+?'. PHPINI_END .'[^\r\n]*\s?/s' , "\n", $fdata);
			$fdata = "\n<font color='#444'>" . htmlentities($fdata) . '</font>';
			$height = 'height:150px;';
		} else {
			// Create it :
			printf('<li>'. $createfile .'</li>', $_SESSION['abspath'] . $php_file);
		}

		echo '<pre style="background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;' .
			$height . '">' . "\n" .
			'<font color="red">' . PHPINI_BEGIN . "\n" . PHPINI_DATA . "\n" . PHPINI_END . "\n" .
			'</font>' . $fdata . "\n" .
			'</pre><br />';
		if (empty($_SESSION['ini_write']) ) {
			echo '<img src="' . plugins_url( '/images/icon_warn_16.png', __FILE__ ) .'" border="0" height="16" width="16">&nbsp;' . $not_writable .'<br />';
		}

	// HHVM
	} elseif ($_SESSION['http_server'] == 7) {
		?>
		<li><?php _e('Add the following code to your <code>/etc/hhvm/php.ini</code> file, and restart HHVM afterwards:', 'ninjafirewall') ?></li>
		<pre style="background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;height:70px;"><font color="red"><?php echo PHPINI_DATA ?></font></pre>
		<br />
		<?php

	// Other servers (nginx etc) :
	} else {

		// Apache + suPHP : we create both INI and .htaccess files as we need
		// to add the suPHP_ConfigPath directive (otherwise the INI will not
		// apply recursively) :
		if ($_SESSION['http_server'] == 6) {
			if ( file_exists($_SESSION['abspath'] . '.htaccess') ) {
				// Edit it :
				if (! is_writable($_SESSION['abspath'] . '.htaccess') ) {
					$_SESSION['htaccess_write'] = $_SESSION['abspath_writable'] = 0;
				}
				printf('<li>'. $add2file .'</li>', $_SESSION['abspath'] . '.htaccess');
				$fdata = file_get_contents($_SESSION['abspath'] . '.htaccess');
				$fdata = preg_replace( '/\s?'. HTACCESS_BEGIN .'.+?'. HTACCESS_END .'[^\r\n]*\s?/s' , "\n", $fdata);
				$fdata = "\n<font color='#444'>" . htmlentities($fdata) . '</font>';
				$height = 'height:150px;';
			} else {
				// Create it :
				printf('<li>'. $createfile .'</li>', $_SESSION['abspath'] . '.htaccess');
			}
			echo '<pre style="background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;' .
				$height . '">' . "\n" .
				'<font color="red">' . HTACCESS_BEGIN . "\n" . htmlentities(SUPHP_DATA) . "\n" . HTACCESS_END . "\n" .
				'</font>' . $fdata . "\n" .
				'</pre><br />';
			if (empty($_SESSION['htaccess_write']) ) {
				echo '<img src="' . plugins_url( '/images/icon_warn_16.png', __FILE__ ) .'" border="0" height="16" width="16">&nbsp;' . $not_writable .'<br />';
			}
			echo '<br /><br />';
			$fdata = $height = '';
		} // Apache + suPHP


		if ( file_exists($_SESSION['abspath'] . $php_file) ) {
			if (! is_writable($_SESSION['abspath'] . $php_file) ) {
				$_SESSION['ini_write'] = $_SESSION['abspath_writable'] = 0;
			}
			// Edit it :
			printf('<li>'. $add2file .'</li>', $_SESSION['abspath'] . $php_file);
			$fdata = file_get_contents($_SESSION['abspath'] . $php_file);
			$fdata = preg_replace( '/\s?'. PHPINI_BEGIN .'.+?'. PHPINI_END .'[^\r\n]*\s?/s' , "\n", $fdata);
			$fdata = "\n<font color='#444'>" . htmlentities($fdata) . '</font>';
			$height = 'height:150px;';
		} else {
			// Create it :
			printf('<li>'. $createfile .'</li>', $_SESSION['abspath'] . $php_file);
		}

		echo '<pre style="background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;' .
			$height . '">' . "\n" .
			'<font color="red">' . PHPINI_BEGIN . "\n" . PHPINI_DATA . "\n" . PHPINI_END . "\n" .
			'</font>' . $fdata . "\n" .
			'</pre><br />';
		if (empty($_SESSION['ini_write']) ) {
			echo '<img src="' . plugins_url( '/images/icon_warn_16.png', __FILE__ ) .'" border="0" height="16" width="16">&nbsp;' . $not_writable .'<br />';
		}
	}

	echo '<br /><form method="post" name="integration_form">';

	// Skip that section if we are running with HHVM:
	if ($_SESSION['http_server'] != 7) {
		$chg_str = __('Please make those changes, then click on button below.', 'ninjafirewall');
		if (! empty($_SESSION['abspath_writable']) ) {
			// We offer to make the changes, or to let the user handle that (could be
			// useful if the admin wants to use a PHP INI or .htaccess in another folder) :
			echo '<p><label><input type="radio" name="makechange" onClick="diy_chg(this.value)" value="nfw" checked="checked">'.
			__('Let NinjaFirewall make the above changes (recommended).', 'ninjafirewall') .'</label></p>
			<p><font color="red" id="lnfw">'.
			__('Ensure that you have an FTP access to your website so that, if there was a problem, you could undo the above changes.', 'ninjafirewall') .'</font>&nbsp;</p>
			<p><label><input type="radio" name="makechange" onClick="diy_chg(this.value)" value="usr">'.
			__('I want to make the changes myself.', 'ninjafirewall') .'</label></p>
			<p id="diy" style="display:none;">' . $chg_str . '</p>';
		} else {
			echo '<p style="font-weight:bold">'. $chg_str .'</p>';
		}
	} else {
		// Unused but usefull...:
		$_SESSION['php_ini_type'] = 1;
		echo '<input type="hidden" name="makechange" value="usr">
		<a href="http://blog.nintechnet.com/installing-ninjafirewall-with-hhvm-hiphop-virtual-machine/">' . __('Please check our blog if you want to install NinjaFirewall on HHVM.', 'ninjafirewall') . '</a>
		<br />';
	}
	?>
	<br />
	<input type="submit" class="button-primary" name="next" value="<?php _e('Next Step', 'ninjafirewall') ?> &#187;">
	<input type="hidden" name="nfw_act" value="postsave">
	<input type="hidden" name="nfw_firstrun" value="1" />
	<?php wp_nonce_field('postsave', 'nfwnonce', 0); ?>
	</form>
</div>

<?php
}

/* ------------------------------------------------------------------ */ //i18n+

function nfw_postsave() {

	if ( @$_POST['makechange'] != 'usr' && @$_POST['makechange'] != 'nfw' ) {
		$err =  __('you must select how to make changes to your files.', 'ninjafirewall');
NFW_INTEGRATION:
		$_POST['abspath']      = $_SESSION['abspath'];
		$_POST['http_server']  = $_SESSION['http_server'];
		$_POST['php_ini_type'] = $_SESSION['php_ini_type'];
		nfw_integration($err);
		return;
	}
	if ( empty($_SESSION['http_server']) || ! preg_match('/^[1-7]$/', $_SESSION['http_server']) ) {
		$_POST['abspath'] = $_SESSION['abspath'];
		nfw_presave( __('select your HTTP server and PHP SAPI.', 'ninjafirewall') );
		return;
	}
	if ($_SESSION['http_server'] != 1) {
		if ( empty($_SESSION['php_ini_type']) || ! preg_match('/^[1-3]$/', $_SESSION['php_ini_type']) ) {
			$_POST['abspath'] = $_SESSION['abspath'];
			nfw_presave( __('select the PHP initialization file supported by your server.', 'ninjafirewall') );
			return;
		}
	}

	// The user decided to make the changes :
	if ( $_POST['makechange'] == 'usr' ) {
		goto DOITYOURSELF;
	}

	if ( empty($_SESSION['abspath_writable']) ) {
		$err = __('your WordPress root directory is not writable, I cannot make those changes for you.', 'ninjafirewall');
		goto NFW_INTEGRATION;
		exit;
	}

	nfw_ini_data();

	$bakup_file = time();

	$nfw_install['htaccess'] = $nfw_install['phpini'] = 0;

	// Apache module or Litespeed or Apache/suPHP : create/modify .htaccess
	if ($_SESSION['http_server'] == 1 || $_SESSION['http_server'] == 4 || $_SESSION['http_server'] == 6 ) {
		$fdata = '';
		if ( file_exists($_SESSION['abspath'] . '.htaccess') ) {
			if (! is_writable($_SESSION['abspath'] . '.htaccess') ) {
				$err = sprintf(__('cannot write to <code>%s</code>, it is read-only.', 'ninjafirewall'), $_SESSION['abspath'] . '.htaccess');
				goto NFW_INTEGRATION;
				exit;
			}
			$fdata = file_get_contents($_SESSION['abspath'] . '.htaccess');
			$fdata = preg_replace( '/\s?'. HTACCESS_BEGIN .'.+?'. HTACCESS_END .'[^\r\n]*\s?/s' , "\n", $fdata);
			// Backup the current .htaccess :
			copy( $_SESSION['abspath'] . '.htaccess',	$_SESSION['abspath'] . '.htaccess.ninja' . $bakup_file );
		}
		if ($_SESSION['http_server'] == 6) {
			@file_put_contents($_SESSION['abspath'] . '.htaccess',
				HTACCESS_BEGIN . "\n" . SUPHP_DATA . "\n" . HTACCESS_END . "\n\n" . $fdata, LOCK_EX );
		} else {
			if ($_SESSION['http_server'] == 4) {
				@file_put_contents($_SESSION['abspath'] . '.htaccess',
					HTACCESS_BEGIN . "\n" . LITESPEED_DATA . "\n" . HTACCESS_END . "\n\n" . $fdata, LOCK_EX );

			} else {
				@file_put_contents($_SESSION['abspath'] . '.htaccess',
					HTACCESS_BEGIN . "\n" . HTACCESS_DATA . "\n" . HTACCESS_END . "\n\n" . $fdata, LOCK_EX );
			}
		}
		@chmod( $_SESSION['abspath'] . '.htaccess', 0644 );
		// Save the htaccess path for the uninstaller :
		$nfw_install['htaccess'] = $_SESSION['abspath'] . '.htaccess';
	}

	// Non-Apache HTTP servers: create/modify PHP INI
	if ($_SESSION['http_server'] != 1) {
		$fdata = '';
		$ini_array = array('php.ini', '.user.ini','php5.ini');

		if ($_SESSION['php_ini_type'] == 1) {
			$php_file = 'php.ini';
		} elseif ($_SESSION['php_ini_type'] == 2) {
			$php_file = '.user.ini';
		} else {
			$php_file = 'php5.ini';
		}

		if ( file_exists($_SESSION['abspath'] . $php_file) ) {
			if (! is_writable($_SESSION['abspath'] . $php_file) ) {
				$err = sprintf(__('cannot write to <code>%s</code>, it is read-only.', 'ninjafirewall'), $_SESSION['abspath'] . $php_file);
				goto NFW_INTEGRATION;
				exit;
			}
			$fdata = file_get_contents($_SESSION['abspath'] . $php_file);
			$fdata = preg_replace( '/auto_prepend_file/' , ";auto_prepend_file", $fdata);
			$fdata = preg_replace( '/\s?'. PHPINI_BEGIN .'.+?'. PHPINI_END .'[^\r\n]*\s?/s' , "\n", $fdata);
			// Backup the current .htaccess :
			copy( $_SESSION['abspath'] . $php_file,	$_SESSION['abspath'] . $php_file . '.ninja' . $bakup_file );
		}
		@file_put_contents($_SESSION['abspath'] . $php_file,
			PHPINI_BEGIN . "\n" . PHPINI_DATA . "\n" . PHPINI_END . "\n\n" . $fdata, LOCK_EX );
		@chmod( $_SESSION['abspath'] . $php_file, 0644 );
		// Save the htaccess path for the uninstaller :
		$nfw_install['phpini'] = $_SESSION['abspath'] . $php_file;

		// Look for other INI files, edit them to remove any NinjaFirewall instructions:
		foreach ( $ini_array as $ini_file ) {
			if ($ini_file == $php_file) { continue; }
			if ( file_exists($_SESSION['abspath'] . $ini_file) ) {
				if ( is_writable($_SESSION['abspath'] . $ini_file) ) {
					$ini_data = file_get_contents($_SESSION['abspath'] . $ini_file);
					$ini_data = preg_replace( '/auto_prepend_file/' , ";auto_prepend_file", $ini_data);
					$ini_data = preg_replace( '/\s?'. PHPINI_BEGIN .'.+?'. PHPINI_END .'[^\r\n]*\s?/s' , "\n", $ini_data);
					@file_put_contents($_SESSION['abspath'] . $ini_file, $ini_data, LOCK_EX );
				}
			}
		}
	}
	update_option( 'nfw_install', $nfw_install);

	?>
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url(<?php echo plugins_url() ?>/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>NinjaFirewall (WP Edition)</h2>
	<br />
	<br />
	<div class="updated settings-error"><p><?php _e('Your configuration was saved.', 'ninjafirewall') ?>
	<?php
	if (! empty($_SESSION['email_install']) ) {
	?>
		<br />
		<?php
		// translators: ...was sent to [admin_email_address]
		_e('A "Quick Start, FAQ & Troubleshooting Guide" email was sent to', 'ninjafirewall') ?> <code><?php echo htmlspecialchars( $_SESSION['email_install'] ) ?></code>.
	<?php
		unset($_SESSION['email_install']);
	}
	?>
	</p></div>
	<?php _e('Please click the button below to test if the firewall integration was successful.', 'ninjafirewall') ?>
	<form method="POST">
		<p><input type="submit" class="button-primary" value="<?php _e('Test Firewall', 'ninjafirewall') ?> &#187;" /></p>
		<input type="hidden" name="abspath" value="<?php echo $_SESSION['abspath'] ?>" />
		<input type="hidden" name="nfw_act" value="postsave" />
		<input type="hidden" name="nfw_firstrun" value="1" />
		<input type="hidden" name="makechange" value="usr" />
		<?php wp_nonce_field('postsave', 'nfwnonce', 0); ?>
	</form>
</div>
<?php
	return;

DOITYOURSELF:
	nfw_firewalltest();
	return;
}

/* ------------------------------------------------------------------ */ // i18n+

function welcome_email() {

	if ( empty($_SESSION['email_install']) ) {
		// We send an email to the admin (or super admin) with some details
		// about how to undo the changes if the site crashed after applying
		// those changes :
		if ( $recipient = get_option('admin_email') ) {
			$subject = '[NinjaFirewall] ' . __('Quick Start, FAQ & Troubleshooting Guide', 'ninjafirewall');
			$message = __('Hi,', 'ninjafirewall') . "\n\n";

			$message.= __('This is NinjaFirewall\'s installer. Below are some helpful info and links you may consider reading before using NinjaFirewall.', 'ninjafirewall') . "\n\n";

			$message.= '1) ' . __('Troubleshooting:', 'ninjafirewall') . "\n";
			$message.= 'http://nintechnet.com/ninjafirewall/wp-edition/help/?troubleshooting ' . "\n\n";

			$message.= __('-Locked out of your site / Fatal error / WordPress crash?', 'ninjafirewall') . "\n";
			$message.= __('-Failed installation ("Error: the firewall is not loaded")?', 'ninjafirewall') . "\n";
			$message.= __('-Blank page after INSTALLING NinjaFirewall?', 'ninjafirewall') . "\n";
			$message.= __('-Blank page after UNINSTALLING NinjaFirewall?', 'ninjafirewall') . "\n";
			$message.= __('-500 Internal Server Error?', 'ninjafirewall') . "\n";
			$message.= __('-"Cannot connect to WordPress database" error message?', 'ninjafirewall') . "\n";
			$message.= __('-How to disable NinjaFirewall?', 'ninjafirewall') . "\n";
			$message.= __('-Lost password (brute-force protection)?', 'ninjafirewall') . "\n";
			$message.= __('-Blocked visitors (see below)?', 'ninjafirewall') . "\n\n";

			$message.= '2) ' . __('-NinjaFirewall (WP Edition) troubleshooter script', 'ninjafirewall') . "\n";
			$message.= 'http://nintechnet.com/share/wp-check.txt ' . "\n\n";
			$message.=  __('-Rename this file to "wp-check.php".', 'ninjafirewall') . "\n";
			$message.=  __('-Upload it into your WordPress root folder.', 'ninjafirewall') . "\n";
			$message.=  __('-Goto http://YOUR WEBSITE/wp-check.php.', 'ninjafirewall') . "\n";
			$message.=  __('-Delete it afterwards.', 'ninjafirewall') . "\n\n";


			$message.= '3) '. __('FAQ:', 'ninjafirewall') . "\n";
			$message.= 'http://nintechnet.com/ninjafirewall/wp-edition/help/?faq ' . "\n\n";

			$message.= __('-Why is NinjaFirewall different from other security plugins for WordPress?', 'ninjafirewall') . "\n";
			$message.= __('-Do I need root privileges to install NinjaFirewall?', 'ninjafirewall') . "\n";
			$message.= __('-Does it work with Nginx?', 'ninjafirewall') . "\n";
			$message.= __('-Do I need to alter my PHP scripts?', 'ninjafirewall') . "\n";
			$message.= __('-Will NinjaFirewall detect the correct IP of my visitors if I am behind a CDN service like Cloudflare or Incapsula?', 'ninjafirewall') . "\n";
			$message.= __('-I moved my wp-config.php file to another directory. Will it work with NinjaFirewall?', 'ninjafirewall') . "\n";
			$message.= __('-Will it slow down my site?', 'ninjafirewall') . "\n";
			$message.= __('-Is there any Windows version?', 'ninjafirewall') . "\n";
			$message.= __('-Can I add/write my own security rules?', 'ninjafirewall') . "\n";
			$message.= __('-Can I migrate my site(s) with NinjaFirewall installed?', 'ninjafirewall') . "\n\n";


			$message.= '4) '. __('Must Read:', 'ninjafirewall') . "\n\n";

			$message.= __('-Testing NinjaFirewall without blocking your visitors.', 'ninjafirewall') . "\n";
			$message.= 'http://blog.nintechnet.com/testing-ninjafirewall-without-blocking-your-visitors/ ' . "\n";

			$message.= __('-Add your own code to the firewall: the ".htninja" file.', 'ninjafirewall') . "\n";
			$message.= 'http://nintechnet.com/ninjafirewall/wp-edition/help/?htninja ' . "\n";

			$message.= __('-Restricting access to NinjaFirewall settings.', 'ninjafirewall') . "\n";
			$message.= 'http://blog.nintechnet.com/restricting-access-to-ninjafirewall-wp-edition-settings/ ' . "\n";

			$message.= __('-Keep your blog protected against the latest vulnerabilities.', 'ninjafirewall') . "\n";
			$message.= 'http://blog.nintechnet.com/ninjafirewall-wpwp-introduces-automatic-updates-for-security-rules ' . "\n\n";


			$message.= '5) '. __('Help & Support Links:', 'ninjafirewall') . "\n\n";

			$message.= __('-Each page of NinjaFirewall includes a contextual help: click on the "Help" menu tab located in the upper right corner of the corresponding page.', 'ninjafirewall') . "\n";
			$message.= __('-Online documentation is also available here:', 'ninjafirewall'). ' http://nintechnet.com/ninjafirewall/wp-edition/doc/ ' . "\n";
			$message.= __('-The WordPress support forum:', 'ninjafirewall') .' http://wordpress.org/support/plugin/ninjafirewall ' . "\n";
			$message.= __('-Updates info are available via Twitter:', 'ninjafirewall') .' https://twitter.com/nintechnet ' . "\n\n";

			$message.= 'NinjaFirewall (WP Edition) - http://ninjafirewall.com/ ' . "\n\n";

			if (! DONOTEMAIL ) {
				wp_mail( $recipient, $subject, $message );
				$_SESSION['email_install'] = $recipient;
			}
		}
	}
}

/* ------------------------------------------------------------------ */ // i18n+


function nfw_firewalltest() {
	?>
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url(<?php echo plugins_url() ?>/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h2>NinjaFirewall (WP Edition)</h2>
	<br />
	<br />
	<?php
	if (! defined('NFW_STATUS') || NFW_STATUS != 20 ) {
		// The firewall is not loaded :
		echo '<div class="error settings-error"><p>'. __('Error: the firewall is not loaded.', 'ninjafirewall'). '</p></div>
		<h3>'. __('Suggestions:', 'ninjafirewall'). '</h3>
		<ol>';
		if ($_SESSION['http_server'] == 1) {
			// User choosed Apache/mod_php instead of CGI/FCGI:
			echo '<li>'. __('You selected <code>Apache + PHP5 module</code> as your HTTP server and PHP SAPI. Maybe your HTTP server is <code>Apache + CGI/FastCGI</code>?', 'ninjafirewall'). '
			<br />
			'. __('You can click the "Go Back" button and try to select another HTTP server type.', 'ninjafirewall'). '</li><br />';
		} else {
			// Very likely a PHP INI issue :
			if ($_SESSION['php_ini_type'] == 2) {
				echo '<li>'. __('You have selected <code>.user.ini</code> as your PHP initialization file. Unlike <code>php.ini</code>, <code>.user.ini</code> files are not reloaded immediately by PHP, but every five minutes. If this is your own server, restart Apache (or PHP-FPM if applicable) to force PHP to reload it, otherwise please <strong>wait up to five minutes</strong> and then, click the "Test Again" button below.', 'ninjafirewall'). '</li>
				<form method="POST">
					<input type="submit" class="button-secondary" value="'. __('Test Again', 'ninjafirewall'). '" />
					<input type="hidden" name="nfw_act" value="postsave" />
					<input type="hidden" name="makechange" value="usr" />
					<input type="hidden" name="nfw_firstrun" value="1" />'. wp_nonce_field('postsave', 'nfwnonce', 0) .'
				</form><br />';
			}
			if ($_SESSION['http_server'] == 2) {
				if ( preg_match('/apache/i', PHP_SAPI) ) {
					// User choosed Apache/CGI instead of mod_php:
					echo '<li>'. __('You selected <code>Apache + CGI/FastCGI</code> as your HTTP server and PHP SAPI. Maybe your HTTP server is <code>Apache + mod_php5</code>?', 'ninjafirewall'). '
					<br />
					'. __('You can click the "Go Back" button and try to select another HTTP server type.', 'ninjafirewall'). '</li><br />';
				}
			}
			echo '<li>'. __('Maybe you did not select the correct PHP INI ?', 'ninjafirewall'). '
			<br />
			'. __('You can click the "Go Back" button and try to select another one.', 'ninjafirewall'). '</li>';
		}
		// Reload the page ?
		echo '<form method="POST">
		<p><input type="submit" class="button-primary" value="&#171; '. __('Go Back', 'ninjafirewall'). '" /></p>
		<input type="hidden" name="abspath" value="' . $_SESSION['abspath'] . '" />
		<input type="hidden" name="nfw_act" value="presave" />
		<input type="hidden" name="nfw_firstrun" value="1" />'. wp_nonce_field('presave', 'nfwnonce', 0) .'
		</form>
		</ol>
		<h3>'. __('Need help ? Check our blog:', 'ninjafirewall'). ' <a href="http://blog.nintechnet.com/troubleshoot-ninjafirewall-installation-problems/" target="_blank">Troubleshoot NinjaFirewall installation problems</a>.</h3>
</div>';
	}
}

/* ------------------------------------------------------------------ */ // i18n+

function nfw_ini_data() {

	if (! defined('HTACCESS_BEGIN') ) {
		define( 'HTACCESS_BEGIN', '# BEGIN NinjaFirewall' );
		define( 'HTACCESS_DATA', '<IfModule mod_php5.c>' . "\n" .
									'   php_value auto_prepend_file ' . plugin_dir_path(__FILE__) . 'lib/firewall.php' . "\n" .
									'</IfModule>');
		define( 'LITESPEED_DATA', 'php_value auto_prepend_file ' . plugin_dir_path(__FILE__) . 'lib/firewall.php');
		define( 'SUPHP_DATA', '<IfModule mod_suphp.c>' . "\n" .
									'   suPHP_ConfigPath ' . rtrim($_SESSION['abspath'], '/') . "\n" .
									'</IfModule>');
		define( 'HTACCESS_END', '# END NinjaFirewall' );
		define( 'PHPINI_BEGIN', '; BEGIN NinjaFirewall' );
		define( 'PHPINI_DATA', 'auto_prepend_file = ' . plugin_dir_path(__FILE__) . 'lib/firewall.php' );
		define( 'PHPINI_END', '; END NinjaFirewall' );
	}
	// set the admin goodguy flag :
	$_SESSION['nfw_goodguy'] = true;
}

/* ------------------------------------------------------------------ */ // i18n+

function nfw_default_conf() {

	$nfw_rules = array();

	// Populate our options :
	$nfw_options = array(
		'logo'				=> plugins_url() . '/ninjafirewall/images/ninjafirewall_75.png',
		'enabled'			=> 1,
		'ret_code'			=> 403,
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

		'alert_email'	 	=> get_option('admin_email'),
		// v1.1.0 :
		'alert_sa_only'	=> 2,
		'nt_show_status'	=> 1,
		'post_b64'			=> 1,
		// v1.1.2 :
		'no_xmlrpc'			=> 0,
		// v1.7 :
		'no_xmlrpc_multi'	=> 0,

		// v1.1.3 :
		'enum_archives'	=> 1,
		'enum_login'		=> 0,
		// v1.1.6 :
		'request_sanitise'=> 0,
		// v1.2.1 :
		'fg_enable'			=>	0,
		'fg_mtime'			=>	10,
		'fg_exclude'		=>	'',
	);
	// v1.3.1 :
	// Some compatibility checks:
	// 1. header_register_callback(): requires PHP >=5.4
	// 2. headers_list() and header_remove(): some hosts may disable them.
	if ( function_exists('header_register_callback') && function_exists('headers_list') && function_exists('header_remove') ) {
		$nfw_options['response_headers'] = '000000';
	}

	// Fetch the latest rules from the WordPress repo :
	define('NFUPDATESDO', 2);
	@nf_sub_updates();

	if (! $nfw_rules = @unserialize(NFW_RULES) ) {
		die( __('Error: I cannot download the security rules from wordpress.org. Please try again in a few minutes.', 'ninjafirewall') );
	}

	// Save engine and rules versions :
	$nfw_options['engine_version'] = NFW_ENGINE_VERSION;
	$nfw_options['rules_version']  = NFW_NEWRULES_VERSION; // downloaded rules

	// Add the correct DOCUMENT_ROOT :
	if ( strlen( $_SERVER['DOCUMENT_ROOT'] ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['what'] = $_SERVER['DOCUMENT_ROOT'];
	} elseif ( strlen( getenv( 'DOCUMENT_ROOT' ) ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['what'] = getenv( 'DOCUMENT_ROOT' );
	} else {
		$nfw_rules[NFW_DOC_ROOT]['on']  = 0;
	}

	// Save to the DB :
	update_option( 'nfw_options', $nfw_options);
	update_option( 'nfw_rules', $nfw_rules);

	// Remove any potential scheduled cron job (in case of a re-installation) :
	if ( wp_next_scheduled('nfscanevent') ) {
		wp_clear_scheduled_hook('nfscanevent');
	}
	if ( wp_next_scheduled('nfsecupdates') ) {
		wp_clear_scheduled_hook('nfsecupdates');
	}
	// Clear old daily report...
	if ( wp_next_scheduled('nfdailyreport') ) {
		wp_clear_scheduled_hook('nfdailyreport');
	}
	// and recreare a new one by default :
	nfw_get_blogtimezone();
	wp_schedule_event( strtotime( date('Y-m-d 00:00:05', strtotime("+1 day")) ), 'daily', 'nfdailyreport');
}

/* ------------------------------------------------------------------ */
// EOF //
