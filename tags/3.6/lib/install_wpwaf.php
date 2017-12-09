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

function nfw_integration_wpwaf( $err_msg = null ) {

	// Look for the wp-config.php file:
	$wp_config = '';
	if ( file_exists( ABSPATH . 'wp-config.php') ) {
		$wp_config = ABSPATH . 'wp-config.php';

	} elseif ( @file_exists( dirname( ABSPATH ) . '/wp-config.php' ) ) {
		$wp_config = dirname( ABSPATH ) . '/wp-config.php';
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
		<div style="width:33px;height:33px;background-image:url(<?php echo plugins_url() ?>/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
		<h1>NinjaFirewall (WP Edition)</h1>
	<?php

	$wp_config_content = @file_get_contents( $wp_config );
	if ( empty( $wp_config_content ) ) {
		$err = __('Error:', 'ninjafirewall') . ' ' .
				sprintf(	__('Unable to read the wp-config.php file (%s). Make sure it is readable and try again.', 'ninjafirewall' ),
				'<code>'. htmlspecialchars( $wp_config ) .'</code>' );
		?>
		<div class="error settings-error"><p> <?php echo $err ?></p></div>
		<?php
		return;
	}

	if (! empty( $err_msg) ) {
		$err = __('Error:', 'ninjafirewall') . ' ' . $err_msg;
		?>
		<div class="error settings-error"><p> <?php echo $err; ?></p></div>
		<?php
	}

	if (! $wp_config ) {
		$err = __('Error:', 'ninjafirewall') . ' ' . sprintf(
					__('Unable to find the wp-config.php file in the %s or %s directories.', 'ninjafirewall' ),
					'<code>'. htmlspecialchars( ABSPATH ) .'</code>',
					'<code>'. htmlspecialchars( dirname( ABSPATH ) ) .'</code>' );
		?>
			<div class="error settings-error"><p> <?php echo $err ?></p></div>
		</div>
		<?php
		return;
	}

	// Fetch rules, options and send welcome email:
	if ( empty($_SESSION['default_conf']) ) {
		nfw_default_conf();
		welcome_email();
	}
	$nfw_install['wp_config'] = $wp_config;
	nfw_update_option( 'nfw_install', $nfw_install);

	?><h3><?php _e('Firewall Integration', 'ninjafirewall') ?> (WordPress WAF)</h3>

	<?php
	if ( is_multisite() ) {
		?>
			<div style="background:#fff;border-left:4px solid #fff;-webkit-box-shadow:0 1px 1px 0 rgba(0,0,0,.1);box-shadow:0 1px 1px 0 rgba(0,0,0,.1);margin:5px 0 15px;padding:1px 12px;border-left-color:green;">
				<p><?php _e('Multisite network detected:', 'ninjafirewall'); echo ' '; _e('NinjaFirewall will protect all sites from your network and its configuration interface will be accessible only to the Super Admin from the network main site.', 'ninjafirewall') ?></p>
			</div>
		<?php
	}
	?>

	<p><?php printf( __('The following <font color="green">green lines</font> of code must be added to your %s file.', 'ninjafirewall'), '<code>'. htmlentities( $wp_config ) . '</code>' ) ?> <?php _e('All other lines, if any, are the actual content of the file:', 'ninjafirewall') ?>
	</p>

	<?php
	nfw_wpconfig_data();
	$wp_config_content = preg_replace( '`<\?php(.+)`s', '$1', $wp_config_content );
	$wp_config_content = preg_replace( '`\s?'. WP_CONFIG_BEGIN .'.+?'. WP_CONFIG_END .'[^\r\n]*\s?`s' , "\n", $wp_config_content);

	echo '<pre style="cursor:text;background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;height:180px;">' . "\n" .
			"<font color='#777'>&lt;?php\n" .
			'<font color="green">' . WP_CONFIG_BEGIN . "\n" . htmlentities(WP_CONFIG_DATA) . "\n" . WP_CONFIG_END . "\n" .
			'</font>' . htmlspecialchars( $wp_config_content ) . "\n" .
			'</font></pre><br />';

	echo '<form method="post" name="integration_form">';

	if (! is_writable( $wp_config ) ) {
	?>
		<div style="background:#fff;border-left:4px solid #fff;-webkit-box-shadow:0 1px 1px 0 rgba(0,0,0,.1);box-shadow:0 1px 1px 0 rgba(0,0,0,.1);margin:5px 0 15px;padding:1px 12px;border-left-color:orange;">
			<p><?php _e('The file is not writable, I cannot edit it for you. Please make those changes, then click on button below.', 'ninjafirewall'); ?></p>
		</div>
	<?php

	} else {

	?>
		<p><label><input type="radio" name="makechange" onClick="diy_chg(this.value)" value="nfw" checked="checked"><strong><?php _e('Let NinjaFirewall make the above changes (recommended).', 'ninjafirewall'); ?></strong></label></p>
		<div id="lmd">
			<label><input type="checkbox" name="conf_backup" checked="checked" /><?php _e('Back up the file (wp-config.bak.php) before editing it.', 'ninjafirewall') ?></label>
			<div style="background:#fff;border-left:4px solid #fff;-webkit-box-shadow:0 1px 1px 0 rgba(0,0,0,.1);box-shadow:0 1px 1px 0 rgba(0,0,0,.1);margin:5px 0 15px;padding:1px 12px;border-left-color:orange;">
				<p><?php _e('Ensure that you have FTP access to your website so that, if there were a problem during the installation of the firewall, you could easily undo the changes.', 'ninjafirewall') ?></p>
			</div>
		</div>
		<p><label><input type="radio" name="makechange" onClick="diy_chg(this.value)" value="usr"><strong><?php _e('I want to make the changes myself.', 'ninjafirewall'); ?></strong></label></p>
		<p id="diy" style="display:none;"><?php _e('Please make those changes, then click on button below.', 'ninjafirewall') ?></p>
	<?php
	}
	?>
	<br />
	<input type="submit" class="button-primary" name="next" value="<?php _e('Next Step', 'nfwplus') ?> &#187;">
	<input type="hidden" name="nfw_act" value="save_changes_wpwaf">
	<?php wp_nonce_field('save_changes_wpwaf', 'nfwnonce', 0); ?>
	</form>
</div>

<?php
	$_SESSION['wp_config'] = $wp_config;
}

/* ------------------------------------------------------------------ */

function nfw_save_changes_wpwaf() {

	if ( empty( $_SESSION['wp_config'] ) || ! file_exists( $_SESSION['wp_config'] ) ) {
		$err = sprintf( __('Unable to find the wp-config.php file (#%s).', 'ninjafirewall' ), __LINE__ );
		nfw_integration_wpwaf( $err );
		return;
	}

	$wp_config = $_SESSION['wp_config'];

	// Let NinjaFirewall do the changes:
	if ( isset( $_POST['makechange'] ) && $_POST['makechange'] == 'nfw' ) {

		// Back up the wp-config.php?
		if ( isset( $_POST['conf_backup'] ) ) {
			$dirname = dirname( $_SESSION['wp_config'] );
			if (! file_exists( $dirname . '/wp-config.bak.php' ) ) {
				@copy( $_SESSION['wp_config'], $dirname . '/wp-config.bak.php' );
			}
		}

		// Clean-up any PHP INI:
		$_SESSION['abspath'] = ABSPATH;
		nfw_ini_data();
		$php_ini = array( ABSPATH . 'php.ini', ABSPATH . 'php5.ini', ABSPATH . '.user.ini' );
		foreach ( $php_ini as $file ) {
			if ( file_exists( $file ) ) {
				$data = file_get_contents( $file );
				$data = preg_replace( '`\s?'. PHPINI_BEGIN .'.+?'. PHPINI_END .'[^\r\n]*\s?`s' , "\n", $data);
				@file_put_contents( $file, $data, LOCK_EX );
			}
		}
		// Clean-up .htaccess:
		$htaccess_file = ABSPATH . '.htaccess';
		if ( file_exists( $htaccess_file ) ) {
			$data = file_get_contents( $htaccess_file );
			$data = preg_replace( '`\s?'. HTACCESS_BEGIN .'.+?'. HTACCESS_END .'[^\r\n]*\s?`s' , "\n", $data);
			@file_put_contents( $htaccess_file,  $data, LOCK_EX );
		}

		$wp_config_content = @file_get_contents( $wp_config );

		nfw_wpconfig_data();
		$wp_config_content = preg_replace( '`<\?php(.+)`s', '$1', $wp_config_content );
		$wp_config_content = preg_replace( '`\s?'. WP_CONFIG_BEGIN .'.+?'. WP_CONFIG_END .'[^\r\n]*\s?`s' , "\n", $wp_config_content);
		@file_put_contents( $wp_config, "<?php\n". WP_CONFIG_BEGIN ."\n".	WP_CONFIG_DATA ."\n". WP_CONFIG_END ."\n$wp_config_content", LOCK_EX );

		?>
	<div class="wrap">
		<div style="width:33px;height:33px;background-image:url(<?php echo plugins_url() ?>/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
		<h1>NinjaFirewall (WP Edition)</h1>
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


<input type="hidden" name="nfw_act" value="save_changes_wpwaf" />
<input type="hidden" name="makechange" value="usr" />
<?php wp_nonce_field('save_changes_wpwaf', 'nfwnonce', 0); ?>


		</form>
	</div>
	<?php
		return;
	}

	nfw_test_wpwaf();
	return;
}

/* ------------------------------------------------------------------ */

function nfw_test_wpwaf() {

	if (! defined('NFW_STATUS') || NFW_STATUS != 20 ) {
		$err = __('The firewall is not loaded. Make sure that the required lines of code were added to your wp-config.php file.', 'ninjafirewall' );
		nfw_integration_wpwaf( $err );
	}
	return;
}

/* ------------------------------------------------------------------ */
// EOF //
