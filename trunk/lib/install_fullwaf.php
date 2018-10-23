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

/* ------------------------------------------------------------------ */

function nfw_get_abspath( $err = 0 ) {

	if ( $_SERVER['DOCUMENT_ROOT'] . '/' == ABSPATH ) {
		$_POST['abspath'] = htmlspecialchars(ABSPATH);
		nfw_presave();
		return;
	}
	echo '
<div class="wrap">
	<h1><img style="vertical-align:top;width:33px;height:33px;" src="'. plugins_url( '/ninjafirewall/images/ninjafirewall_32.png' ) .'">&nbsp;' . __('Firewall Policies', 'ninjafirewall') . '</h1>';
	if ( $err ) {
		echo '<div class="error settings-error"><p>' . __('Error:', 'ninjafirewall') .' '. $err . '</p></div>';
	}
	echo '
	<form method="post">
	<p>' . sprintf(__('Your WordPress directory (%s) is different from your website document root (%s). Because it is possible to install WordPress into a subdirectory, but have the blog exist in the site root, NinjaFirewall needs to know the exact location of the site root.', 'ninjafirewall'), '<code>' . htmlspecialchars(ABSPATH) . '</code>', '<code>' . htmlspecialchars( $_SERVER['DOCUMENT_ROOT'] ) . '/</code>') . '</p>
	<p>' . sprintf( __('Please edit the path below only if you have manually modified your WordPress root directory as described in the <a href="%s">Giving WordPress Its Own Directory</a> article.', 'ninjafirewall'), 'http://codex.wordpress.org/Giving_WordPress_Its_Own_Directory') .'</p>
	<p><strong style="color:red">'. __('Most users should not change this value.', 'ninjafirewall') .'</strong></p>
	<p>'. __('Path to WordPress site root directory:', 'ninjafirewall') .' <input class="regular-text code" type="text" name="abspath" value="' . htmlspecialchars(ABSPATH) . '"></p>
	<br />
	<br />
		<input class="button-primary" type="submit" name="Save" value="'. __('Next Step', 'ninjafirewall') .' &#187;" />
		<input type="hidden" name="nfw_act" value="presave" />' . wp_nonce_field('presave', 'nfwnonce', 0) . '
	</form>
</div>';

}

/* ------------------------------------------------------------------ */

function nfw_presave( $err = '' ) {

	if (empty ($_POST['abspath']) ) {
		nfw_get_abspath( __('please enter the full path to WordPress folder.', 'ninjafirewall') );
		return;
	}
	$abspath = htmlspecialchars( rtrim( $_POST['abspath'], '/' ) );
	if (! file_exists( $abspath . '/index.php' ) ) {
		nfw_get_abspath( sprintf( __('cannot find the %s directory! Please correct the full path to WordPress site root directory.', 'ninjafirewall'), '<code>' . $abspath . '/index.php</code>') );
		return;
	}

	$_SESSION['abspath'] = $abspath . '/';

	if ( empty($_SESSION['default_conf']) ) {
		nfw_default_conf();

		welcome_email();
	}

	$s1 = $s2 = $s3 = $s4 = $s5 = $s7 = '';
	$recommended = ' ' . __('(recommended)', 'ninjafirewall');
	if ( defined('HHVM_VERSION') ) {
		$http_server = 7;
		$s7 = $recommended;
		$htaccess = 0;
		$php_ini = 0;
	} elseif ( preg_match('/apache/i', PHP_SAPI) ) {
		$http_server = 1;
		$s1 = $recommended;
		$htaccess = 1;
		$php_ini = 0;
	} elseif ( preg_match( '/litespeed/i', PHP_SAPI ) ) {
		$http_server = 4;
		$php_ini = 1;
		$htaccess = 1;
		$s4 = $recommended;
	} else {
		$php_ini = 1;
		$htaccess = 0;
		if ( preg_match('/apache/i', $_SERVER['SERVER_SOFTWARE']) ) {
			$http_server = 2;
			$s2 = $recommended;
		} elseif ( preg_match('/nginx/i', $_SERVER['SERVER_SOFTWARE']) ) {
			$http_server = 3;
			$s3 = $recommended;
		} else {
			$http_server = 5;
			$s5 = $recommended;
		}
	}

	?>
	<script>
	function popup(url,width,height,scroll_bar) {height=height+20;width=width+20;var str = "height=" + height + ",innerHeight=" + height;str += ",width=" + width + ",innerWidth=" + width;if (window.screen){var ah = screen.availHeight - 30;var aw = screen.availWidth -10;var xc = (aw - width) / 2;var yc = (ah - height) / 2;str += ",left=" + xc + ",screenX=" + xc;str += ",top=" + yc + ",screenY=" + yc;if (scroll_bar) {str += ",scrollbars=no";}else {str += ",scrollbars=yes";}str += ",status=no,location=no,resizable=yes";}win = open(url, "nfpop", str);setTimeout("win.window.focus()",1300);}
	function check_fields() {
		var ischecked = 0;
		for (var i = 0; i < document.presave_form.php_ini_type.length; ++i) {
			if(document.presave_form.php_ini_type[i].checked) {
				ischecked = 1;
				break;
			}
		}
		if (! ischecked && document.presave_form.http_server.value != 1 && document.presave_form.http_server.value != 7) {
			alert('<?php echo esc_js( __('Please select the PHP initialization file supported by your server.', 'ninjafirewall') ) ?>');
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
	<h1><img style="vertical-align:top;width:33px;height:33px;" src="'. plugins_url( '/ninjafirewall/images/ninjafirewall_32.png' ) .'">&nbsp;' . __('Firewall Policies', 'ninjafirewall') . '</h1>';

	if (! is_writable( NFW_LOG_DIR . '/nfwlog' ) ) {
		echo '<div class="error settings-error"><p>'. sprintf( __('Error: NinjaFirewall log directory is not writable (%s). Please chmod it to 0777 and reload this page.', 'ninjafirewall'), '<code>' . htmlspecialchars(NFW_LOG_DIR) . '/nfwlog/</code>') .'</p></div></div>';
		return;
	}

	if ( $err ) {
		echo '<div class="error settings-error"><p>'. __('Error:', 'ninjafirewall') . ' ' . $err . '</p></div>';
	}

	?>
	<h3><?php _e('System configuration', 'ninjafirewall') ?></h3>
	<?php
	// auto_prepend_file already being used?
	if ( $apf = ini_get('auto_prepend_file') ) {
		?>
			<div style="background:#fff;border-left:4px solid #fff;-webkit-box-shadow:0 1px 1px 0 rgba(0,0,0,.1);box-shadow:0 1px 1px 0 rgba(0,0,0,.1);margin:5px 0 15px;padding:1px 12px;border-left-color:orange;">
				<p><?php printf( __('NinjaFirewall detected that the PHP <code>auto_prepend_file</code> directive seems to be used by another application: %s.', 'ninjafirewall'), '<code>'. htmlspecialchars($apf) .'</code>' ); echo ' '; _e('Because NinjaFirewall needs to use that directive, it will orverride your current one.', 'ninjafirewall') ?></p>
			</div>
		<?php
	}
	if ( is_multisite() ) {
		?>
			<div style="background:#fff;border-left:4px solid #fff;-webkit-box-shadow:0 1px 1px 0 rgba(0,0,0,.1);box-shadow:0 1px 1px 0 rgba(0,0,0,.1);margin:5px 0 15px;padding:1px 12px;border-left-color:green;">
				<p><?php _e('Multisite network detected:', 'ninjafirewall'); echo ' '; _e('NinjaFirewall will protect all sites from your network and its configuration interface will be accessible only to the Super Admin from the network main site.', 'ninjafirewall') ?></p>
			</div>
		<?php
	}
	?>
	<form method="post" name="presave_form" onSubmit="return check_fields();">
	<table class="form-table">

		<tr>
			<th scope="row"><?php _e('Select your HTTP server and your PHP server API', 'ninjafirewall') ?> (<code>SAPI</code>)</th>
			<td width="20">&nbsp;</td>
			<td>
				<select class="input" name="http_server" onchange="ini_toogle(this.value);">
					<option value="1"<?php selected($http_server, 1) ?>>Apache + PHP<?php echo PHP_MAJOR_VERSION ?> module<?php echo $s1 ?></option>
					<option value="2"<?php selected($http_server, 2) ?>>Apache + CGI/FastCGI<?php echo $s2 ?></option>
					<option value="6"<?php selected($http_server, 6) ?>>Apache + suPHP</option>
					<option value="3"<?php selected($http_server, 3) ?>>Nginx + <?php _e('CGI or PHP-FPM', 'ninjafirewall') ?><?php echo $s3 ?></option>
					<option value="4"<?php selected($http_server, 4) ?>>Litespeed<?php echo $s4 ?></option>
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
				<?php sprintf( __('Please <a href="%s">check our blog</a> if you want to install NinjaFirewall on HHVM.', 'ninjafirewall'), '<a href="https://blog.nintechnet.com/installing-ninjafirewall-with-hhvm-hiphop-virtual-machine/">') ?></p>
			</td>
		</tr>

		<?php
		$f1 = ''; $f2 = ''; $f3 = ''; $php_ini_type = '';
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
			echo '<tr id="trini" style="display:none;">';
		} else {
			echo '<tr id="trini">';
		}
		?>
			<th scope="row"><?php _e('Select the PHP initialization file supported by your server', 'ninjafirewall') ?></th>
			<td width="20">&nbsp;</td>
			<td>
				<p><label><input type="radio" name="php_ini_type" value="1"<?php checked($php_ini_type, 1) ?>><code>php.ini</code></label><?php echo $f1 ?><br /><span class="description"><?php _e('Used by most shared hosting accounts.', 'ninjafirewall') ?></span></p>

				<p><label><input type="radio" name="php_ini_type" value="2"<?php checked($php_ini_type, 2) ?>><code>.user.ini</code></label><?php echo $f2 ?><br /><span class="description"><?php _e('The default PHP INI file since PHP 5.3.0', 'ninjafirewall') ?> (<a href="http://php.net/manual/en/configuration.file.per-user.php"><?php _e('more info', 'ninjafirewall') ?></a>).</span></p>

				<p><label><input type="radio" name="php_ini_type" value="3"<?php checked($php_ini_type, 3) ?>><code>php5.ini</code></label><?php echo $f3 ?><br /><span class="description"><?php _e('A few shared hosting accounts. Seldom used.', 'ninjafirewall') ?></span></p>
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

/* ------------------------------------------------------------------ */

function nfw_integration( $err = '' ) {

	if ( empty($_SESSION['abspath']) ) {
		nfw_get_abspath( __('please enter the full path to WordPress folder.', 'ninjafirewall') );
		return;
	}

	if ( empty($_POST['http_server']) || ! preg_match('/^[1-7]$/', $_POST['http_server']) ) {
		nfw_presave( __('select your HTTP server and PHP SAPI.', 'ninjafirewall') );
		return;
	}

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
	if ( is_writable($_SESSION['abspath']) ) {
		$_SESSION['abspath_writable'] = 1;
	} else {
		$_SESSION['abspath_writable'] = 0;
	}
?>
<script>
	function diy_chg(what) {
		if (what == 'nfw') {
			jQuery('#lmd').slideDown();
			jQuery('#diy').slideUp();
		} else {
			jQuery('#lmd').slideUp();
			jQuery('#diy').slideDown();
		}
	}
</script>
<div class="wrap">
	<h1><img style="vertical-align:top;width:33px;height:33px;" src="<?php echo plugins_url( '/ninjafirewall/images/ninjafirewall_32.png' ) ?>">&nbsp;<?php _e('NinjaFirewall (WP Edition)', 'ninjafirewall') ?></h1>
	<?php
	if ( $err ) {
		echo '<div class="error settings-error"><p>' . __('Error:', 'ninjafirewall') .' '. $err . '</p></div>';
	}
	?>
	<h3><?php _e('Firewall Integration', 'ninjafirewall') ?> (Full WAF)</h3>
	<?php

	$fdata = $height = '';

	$createfile = '<p>'. __('The <code>%s</code> file must be created, and the following lines of code added to it:', 'ninjafirewall') . '</p>';
	$add2file = '<p>'. __('The following <font color="green">green lines</font> of code must be added to your <code>%s</code> file.', 'ninjafirewall') .' '. __('All other lines, if any, are the actual content of the file:', 'ninjafirewall') .'</p>';
	$not_writable = '<div style="background:#fff;border-left:4px solid #fff;-webkit-box-shadow:0 1px 1px 0 rgba(0,0,0,.1);box-shadow:0 1px 1px 0 rgba(0,0,0,.1);margin:5px 0 15px;padding:1px 12px;border-left-color:orange;">
			<p>' . __('The file is not writable, I cannot edit it for you.', 'ninjafirewall') . '</p>
		</div>';


	if ($_SESSION['http_server'] == 1) {
		if ( file_exists($_SESSION['abspath'] . '.htaccess') ) {
			if (! is_writable($_SESSION['abspath'] . '.htaccess') ) {
				$_SESSION['htaccess_write'] = $_SESSION['abspath_writable'] = 0;
			}

			printf( $add2file, $_SESSION['abspath'] . '.htaccess');
			$fdata = file_get_contents($_SESSION['abspath'] . '.htaccess');
			$fdata = preg_replace( '`\s?'. HTACCESS_BEGIN .'.+?'. HTACCESS_END .'[^\r\n]*\s?`s' , "\n", $fdata);
			$fdata = "\n<font color='#444'>" . htmlentities($fdata) . '</font>';
			$height = 'height:150px;';
		} else {

			printf( $createfile, $_SESSION['abspath'] . '.htaccess');
		}
		echo '<pre style="cursor:text;background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;' .
			$height . '">' . "\n" .
			'<font color="green">' . HTACCESS_BEGIN . "\n" . htmlentities(HTACCESS_DATA) . "\n" . HTACCESS_END . "\n" .
			'</font>' . $fdata . "\n" .
			'</pre><br />';
		if (empty($_SESSION['htaccess_write']) ) {
			echo $not_writable;
		}


	} elseif ($_SESSION['http_server'] == 4) {
		if ( file_exists($_SESSION['abspath'] . '.htaccess') ) {

			if (! is_writable($_SESSION['abspath'] . '.htaccess') ) {
				$_SESSION['htaccess_write'] = $_SESSION['abspath_writable'] = 0;
			}
			printf( $add2file, $_SESSION['abspath'] . '.htaccess');
			$fdata = file_get_contents($_SESSION['abspath'] . '.htaccess');
			$fdata = preg_replace( '`\s?'. HTACCESS_BEGIN .'.+?'. HTACCESS_END .'[^\r\n]*\s?`s' , "\n", $fdata);
			$fdata = "\n<font color='#444'>" . htmlentities($fdata) . '</font>';
			$height = 'height:150px;';
		} else {

			printf( $createfile, $_SESSION['abspath'] . '.htaccess');
		}
		echo '<pre style="cursor:text;background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;' .
			$height . '">' . "\n" .
			'<font color="green">' . HTACCESS_BEGIN . "\n" . LITESPEED_DATA . "\n" . HTACCESS_END . "\n" .
			'</font>' . $fdata . "\n" .
			'</pre><br />';
		if (empty($_SESSION['htaccess_write']) ) {
			echo $not_writable;
		}
		echo '<br /><br />';

		$fdata = $height = '';
		if ( file_exists($_SESSION['abspath'] . $php_file) ) {
			if (! is_writable($_SESSION['abspath'] . $php_file) ) {
				$_SESSION['ini_write'] = $_SESSION['abspath_writable'] = 0;
			}

			printf( $add2file, $_SESSION['abspath'] . $php_file);
			$fdata = file_get_contents($_SESSION['abspath'] . $php_file);
			$fdata = preg_replace( '`\s?'. PHPINI_BEGIN .'.+?'. PHPINI_END .'[^\r\n]*\s?`s' , "\n", $fdata);
			$fdata = "\n<font color='#444'>" . htmlentities($fdata) . '</font>';
			$height = 'height:150px;';
		} else {

			printf( $createfile, $_SESSION['abspath'] . $php_file);
		}

		echo '<pre style="cursor:text;background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;' .
			$height . '">' . "\n" .
			'<font color="green">' . PHPINI_BEGIN . "\n" . PHPINI_DATA . "\n" . PHPINI_END . "\n" .
			'</font>' . $fdata . "\n" .
			'</pre><br />';
		if (empty($_SESSION['ini_write']) ) {
			echo $not_writable;
		}


	} elseif ($_SESSION['http_server'] == 7) {
		?>
		<li><?php _e('Add the following code to your <code>/etc/hhvm/php.ini</code> file, and restart HHVM afterwards:', 'ninjafirewall') ?></li>
		<pre style="background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;height:70px;"><font color="green"><?php echo PHPINI_DATA ?></font></pre>
		<br />
		<?php


	} else {

		if ($_SESSION['http_server'] == 6) {
			if ( file_exists($_SESSION['abspath'] . '.htaccess') ) {

				if (! is_writable($_SESSION['abspath'] . '.htaccess') ) {
					$_SESSION['htaccess_write'] = $_SESSION['abspath_writable'] = 0;
				}
				printf( $add2file, $_SESSION['abspath'] . '.htaccess');
				$fdata = file_get_contents($_SESSION['abspath'] . '.htaccess');
				$fdata = preg_replace( '`\s?'. HTACCESS_BEGIN .'.+?'. HTACCESS_END .'[^\r\n]*\s?`s' , "\n", $fdata);
				$fdata = "\n<font color='#444'>" . htmlentities($fdata) . '</font>';
				$height = 'height:150px;';
			} else {

				printf( $createfile, $_SESSION['abspath'] . '.htaccess');
			}
			echo '<pre style="cursor:text;background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;' .
				$height . '">' . "\n" .
				'<font color="green">' . HTACCESS_BEGIN . "\n" . htmlentities(SUPHP_DATA) . "\n" . HTACCESS_END . "\n" .
				'</font>' . $fdata . "\n" .
				'</pre><br />';
			if (empty($_SESSION['htaccess_write']) ) {
				echo $not_writable;
			}
			echo '<br /><br />';
			$fdata = $height = '';
		}


		if ( file_exists($_SESSION['abspath'] . $php_file) ) {
			if (! is_writable($_SESSION['abspath'] . $php_file) ) {
				$_SESSION['ini_write'] = $_SESSION['abspath_writable'] = 0;
			}

			printf( $add2file, $_SESSION['abspath'] . $php_file);
			$fdata = file_get_contents($_SESSION['abspath'] . $php_file);
			$fdata = preg_replace( '`\s?'. PHPINI_BEGIN .'.+?'. PHPINI_END .'[^\r\n]*\s?`s' , "\n", $fdata);
			$fdata = "\n<font color='#444'>" . htmlentities($fdata) . '</font>';
			$height = 'height:150px;';
		} else {

			printf( $createfile, $_SESSION['abspath'] . $php_file);
		}

		echo '<pre style="cursor:text;background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;' .
			$height . '">' . "\n" .
			'<font color="green">' . PHPINI_BEGIN . "\n" . PHPINI_DATA . "\n" . PHPINI_END . "\n" .
			'</font>' . $fdata . "\n" .
			'</pre><br />';
		if (empty($_SESSION['ini_write']) ) {
			echo $not_writable;
		}
	}

	echo '<form method="post" name="integration_form">';


	if ($_SESSION['http_server'] != 7) {
		$chg_str = __('Please make those changes, then click on button below.', 'ninjafirewall');
		if (! empty($_SESSION['abspath_writable']) ) {


			echo '<p><label><input type="radio" name="makechange" onClick="diy_chg(this.value)" value="nfw" checked="checked">'.
			__('Let NinjaFirewall make the above changes (recommended).', 'ninjafirewall') .'</label></p>
			<div id="lmd">
				<div style="background:#fff;border-left:4px solid #fff;-webkit-box-shadow:0 1px 1px 0 rgba(0,0,0,.1);box-shadow:0 1px 1px 0 rgba(0,0,0,.1);margin:5px 0 15px;padding:1px 12px;border-left-color:orange;">
					<p>' . __('Ensure that you have FTP access to your website so that, if there were a problem during the installation of the firewall, you could easily undo the changes.', 'ninjafirewall') .'</p>
				</div>
			</div>

			<p><label><input type="radio" name="makechange" onClick="diy_chg(this.value)" value="usr">'.
			__('I want to make the changes myself.', 'ninjafirewall') .'</label></p>
			<p id="diy" style="display:none;">' . $chg_str . '</p>';
		} else {
			echo '<p>'. $chg_str .'</p>';
		}
	} else {

		$_SESSION['php_ini_type'] = 1;
		echo '<input type="hidden" name="makechange" value="usr">
		<a href="https://blog.nintechnet.com/installing-ninjafirewall-with-hhvm-hiphop-virtual-machine/">' . __('Please check our blog if you want to install NinjaFirewall on HHVM.', 'ninjafirewall') . '</a>
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

/* ------------------------------------------------------------------ */

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

	if ($_SESSION['http_server'] == 1 || $_SESSION['http_server'] == 4 || $_SESSION['http_server'] == 6 ) {
		$fdata = '';
		if ( file_exists($_SESSION['abspath'] . '.htaccess') ) {
			if (! is_writable($_SESSION['abspath'] . '.htaccess') ) {
				$err = sprintf(__('cannot write to <code>%s</code>, it is read-only.', 'ninjafirewall'), $_SESSION['abspath'] . '.htaccess');
				goto NFW_INTEGRATION;
				exit;
			}
			$fdata = file_get_contents($_SESSION['abspath'] . '.htaccess');
			$fdata = preg_replace( '`\s?'. HTACCESS_BEGIN .'.+?'. HTACCESS_END .'[^\r\n]*\s?`s' , "\n", $fdata);
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
		$nfw_install['htaccess'] = $_SESSION['abspath'] . '.htaccess';
	}

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
			$fdata = preg_replace( '`auto_prepend_file`' , ";auto_prepend_file", $fdata);
			$fdata = preg_replace( '`\s?'. PHPINI_BEGIN .'.+?'. PHPINI_END .'[^\r\n]*\s?`s' , "\n", $fdata);
			copy( $_SESSION['abspath'] . $php_file,	$_SESSION['abspath'] . $php_file . '.ninja' . $bakup_file );
		}
		@file_put_contents($_SESSION['abspath'] . $php_file,
			PHPINI_BEGIN . "\n" . PHPINI_DATA . "\n" . PHPINI_END . "\n\n" . $fdata, LOCK_EX );
		@chmod( $_SESSION['abspath'] . $php_file, 0644 );
		$nfw_install['phpini'] = $_SESSION['abspath'] . $php_file;

		foreach ( $ini_array as $ini_file ) {
			if ($ini_file == $php_file) { continue; }
			if ( file_exists($_SESSION['abspath'] . $ini_file) ) {
				if ( is_writable($_SESSION['abspath'] . $ini_file) ) {
					$ini_data = file_get_contents($_SESSION['abspath'] . $ini_file);
					$ini_data = preg_replace( '`auto_prepend_file`' , ";auto_prepend_file", $ini_data);
					$ini_data = preg_replace( '`\s?'. PHPINI_BEGIN .'.+?'. PHPINI_END .'[^\r\n]*\s?`s' , "\n", $ini_data);
					@file_put_contents($_SESSION['abspath'] . $ini_file, $ini_data, LOCK_EX );
				}
			}
		}
	}
	nfw_update_option( 'nfw_install', $nfw_install);

	?>
<div class="wrap">
	<h1><img style="vertical-align:top;width:33px;height:33px;" src="<?php echo plugins_url( '/ninjafirewall/images/ninjafirewall_32.png' ) ?>">&nbsp;<?php _e('NinjaFirewall (WP Edition)', 'ninjafirewall') ?></h1>
	<br />
	<div class="updated settings-error"><p><?php _e('Your configuration was saved.', 'ninjafirewall') ?>
	<?php
	if (! empty($_SESSION['email_install']) ) {

		echo '<br />';
		printf( __('A "Quick Start, FAQ & Troubleshooting Guide" email was sent to %s.', 'ninjafirewall'), '<code>' . htmlspecialchars( $_SESSION['email_install'] ) . '</code>' );
		unset($_SESSION['email_install']);
	}
	?>
	</p></div>
	<?php _e('Please click the button below to test if the firewall integration was successful.', 'ninjafirewall') ?>
	<form method="POST" action="?page=NinjaFirewall&nfw_firstrun=1&rnd=<?php echo time() ?>">
		<p><input type="submit" class="button-primary" value="<?php _e('Test Firewall', 'ninjafirewall') ?> &#187;" /></p>
		<input type="hidden" name="nfw_act" value="postsave" />
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

/* ------------------------------------------------------------------ */
// EOF //
