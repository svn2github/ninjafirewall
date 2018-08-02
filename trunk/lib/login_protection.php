<?php
/*
 +=====================================================================+
 | NinjaFirewall (WP+ Edition)                                         |
 |                                                                     |
 | (c) NinTechNet - https://nintechnet.com/                            |
 +=====================================================================+ sa
*/

if (! defined( 'NFW_ENGINE_VERSION' ) ) {
	header('HTTP/1.1 404 Not Found');
	header('Status: 404 Not Found');
	exit;
}

// Block immediately if user is not allowed :
nf_not_allowed( 'block', __LINE__ );

echo '
<div class="wrap">
	<div style="width:33px;height:33px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h1>' . __('Login Protection', 'ninjafirewall') . '</h1>';

// Saved ?
if ( isset( $_POST['nfw_options']) ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'bfd_save') ) {
		wp_nonce_ays('bfd_save');
	}
	$res = nf_sub_loginprot_save();
	if (! $res ) {
		echo '<div class="updated notice is-dismissible"><p>' . __('Your changes have been saved.', 'ninjafirewall') . '</p></div>';
	} else {
		echo '<div class="error notice is-dismissible"><p>' . $res . '</p></div>';
	}
}

// Fetch the current configuration, if any :
if ( file_exists( NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php' ) ) {

	$bfconfig = nfw_read_bf_config( NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php' );

	if (! @preg_match('/^[1-2]$/', $bfconfig['bf_enable']) ) {
		$bfconfig['bf_enable'] = 0;
	}
	if (! @preg_match('/^(GET|POST|GETPOST)$/', $bfconfig['bf_request'] ) ) {
		$bfconfig['bf_request'] = 'POST';
	}
	if ( $bfconfig['bf_request'] == 'GETPOST' ) {
		$get_post = 'GET/POST';
	} else {
		$get_post = $bfconfig['bf_request'];
	}
	if (! @preg_match('/^[1-9][0-9]?$/', $bfconfig['bf_bantime'] ) ) {
		$bfconfig['bf_bantime'] = 5;
	}
	if (! @preg_match('/^[1-9][0-9]?$/', $bfconfig['bf_attempt'] ) ) {
		$bfconfig['bf_attempt'] = 8;
	}
	if (! @preg_match('/^[1-9][0-9]?$/', $bfconfig['bf_maxtime'] ) ) {
		$bfconfig['bf_maxtime'] = 15;
	}
	if ( empty($bfconfig['auth_name']) || @strlen( $bfconfig['auth_pass'] ) != 40 ) {
		$bfconfig['auth_name']= '';
	}
	if ( empty( $bfconfig['auth_msgtxt'] ) ) {
		// NinjaFirewall <= 3.4.2
		if (! empty( $bfconfig['auth_msg'] ) ) {
			$bfconfig['auth_msgtxt'] = $bfconfig['auth_msg'];
		} else {
			$bfconfig['auth_msgtxt'] = __('Access restricted', 'ninjafirewall');
		}
	} else {
		$bfconfig['auth_msgtxt'] = base64_decode( $bfconfig['auth_msgtxt'] );
	}
	if ( strlen( $bfconfig['auth_msgtxt'] ) > 1024 ) {
		$bfconfig['auth_msgtxt'] = mb_substr( $bfconfig['auth_msgtxt'], 0, 1024, 'utf-8' );
	}

	if ( empty( $bfconfig['captcha_text'] ) ) {
		$bfconfig['captcha_text'] = __( 'Type the characters you see in the picture below:', 'ninjafirewall' );
	} else {
		$bfconfig['captcha_text'] = html_entity_decode( base64_decode( $bfconfig['captcha_text'] ) );
		if ( strlen( $bfconfig['captcha_text'] ) > 255 ) {
			$bfconfig['captcha_text'] = mb_substr( $bfconfig['captcha_text'], 0, 255, 'utf-8' );
		}
	}

	if (empty($bfconfig['bf_xmlrpc']) ) {
		$bfconfig['bf_xmlrpc'] = 0;
	} else {
		$bfconfig['bf_xmlrpc'] = 1;
	}
	if (empty($bfconfig['bf_authlog']) ) {
		$bfconfig['bf_authlog'] = 0;
	} else {
		$bfconfig['bf_authlog'] = 1;
	}
	if ( empty( $bfconfig['bf_type'] ) ) {
		// Password
		$bfconfig['bf_type'] = 0;
	} else {
		// Captcha
		$bfconfig['bf_type'] = 1;
	}
	if ( empty( $bfconfig['bf_allow_bot'] ) ) {
		$bfconfig['bf_allow_bot'] = 0;
	} else {
		$bfconfig['bf_allow_bot'] = 1;
	}
	if ( empty( $bfconfig['bf_nosig'] ) ) {
		$bfconfig['bf_nosig'] = 0;
	} else {
		$bfconfig['bf_nosig'] = 1;
	}

} else {
	// Default values :
	$bfconfig['bf_type'] = 0;
	$bfconfig['bf_enable']   = 0;
	$bfconfig['bf_request'] = 'POST';
	$bfconfig['bf_bantime']  = 5;
	$bfconfig['bf_attempt']  = 8;
	$bfconfig['bf_maxtime']  = 15;
	$bfconfig['auth_name'] = '';
	$bfconfig['auth_msgtxt'] = __('Access restricted', 'ninjafirewall');
	$bfconfig['bf_xmlrpc'] = 0;
	$bfconfig['bf_authlog'] = 0;
	$bfconfig['bf_allow_bot'] = 0;
	$bfconfig['captcha_text'] = __( 'Type the characters you see in the picture below:', 'ninjafirewall' );
	$bfconfig['bf_nosig'] = 0;
	$get_post = 'POST';
}
?>
	<script type="text/javascript">
	function is_number(id) {
		var e = document.getElementById(id);
		if (! e.value ) { return }
		if (! /^[1-9][0-9]?$/.test(e.value) ) {
			alert("<?php echo esc_js( __('Please enter a number from 1 to 99 in \'Password-protect\' field.', 'ninjafirewall') ) ?>");
			e.value = e.value.substring(0, e.value.length-1);
		}
	}
	function auth_user_valid() {
		var e = document.bp_form.elements['nfw_options[auth_name]'];
		if ( e.value.match(/[^-\/\\_.a-zA-Z0-9]/) ) {
			alert('<?php echo esc_js( __('Invalid character.', 'ninjafirewall') ) ?>');
			e.value = e.value.replace(/[^-\/\\_.a-zA-Z0-9]/g,'');
			return false;
		}
		if (e.value == 'admin') {
			alert('<?php echo esc_js( __('"admin" is not acceptable, please choose another user name.', 'ninjafirewall') ) ?>');
			e.value = '';
			return false;
		}
	}
	function realm_valid() {
		var e = document.getElementById("realm").value;
		if ( e.length >= 1024 ) {
			alert('<?php echo esc_js( __('Please enter max 1024 character only.', 'ninjafirewall') ) ?>');
			return false;
		}
	}

	var bf_type = <?php echo $bfconfig['bf_type'] ?>;
	var bf_enable = <?php echo $bfconfig['bf_enable'] ?>;
	function toggle_submenu( enable ) {
		if ( enable == 0 ) {
			// Disable protection
			bf_enable = 0;
			jQuery("#submenu_table").slideUp();
			jQuery("#bf_table").slideUp();
			jQuery("#bf_table_extra").slideUp();
			jQuery("#bf_table_password").slideUp();
			jQuery("#bf_table_captcha").slideUp();
		} else {
			bf_enable = enable;
			jQuery("#submenu_table").slideDown();
			// Display the right table (captcha or password protection)
			toggle_table( enable, bf_type );
			jQuery("#bf_table_extra").slideDown();
		}
	}
	function toggle_table( enable, type ) {
		if ( type == 1 ) {
			// Captcha
			bf_type = 1;
			if ( enable == 1 ) {
				// Yes, if under attack
				jQuery("#bf_table").slideDown();
			} else {
				// Always ON
				jQuery("#bf_table").slideUp();
			}
			jQuery("#bf_table_password").slideUp();
			jQuery("#bf_table_captcha").slideDown();
		} else { // type == 2
			//  Password
			bf_type = 0;
			if ( enable == 1 ) {
				// Yes, if under attack
				jQuery("#bf_table").slideDown();
			} else {
				// Always ON
				jQuery("#bf_table").slideUp();
			}
			jQuery("#bf_table_password").slideDown();
			jQuery("#bf_table_captcha").slideUp();
		}
	}
	function xmlrpc_warn( what ) {
		if ( bf_enable == 2 && what.checked == true ) {
			alert("<?php echo esc_js( __("Note: Access to the XML-RPC API will be completely disabled when the brute-force attack protection is set to 'Always ON'.", 'ninjafirewall') ) ?>");
		}
	}

	function getpost(request){
		if ( request == 'GETPOST' ) {
			request = 'GET/POST';
		}
		document.getElementById('get_post').innerHTML = request;
	}
	</script>
<br />

	<?php
	// Protection is disabled:
	if ( empty( $bfconfig['bf_enable'] ) ) {
		$show_submenu_table = 0;
		$show_bf_table = 0;
		$show_bf_table_password = 0;
		$show_bf_table_extra = 0;
		$show_bf_table_captcha = 0;

	// Protection set to "Yes, if under attack":
	} elseif ( $bfconfig['bf_enable'] == 1 ) {
		$show_submenu_table = 1;
		$show_bf_table = 1;
		$show_bf_table_extra = 1;
		// Password?
		if ( empty( $bfconfig['bf_type'] ) ) {
			$show_bf_table_password = 1;
			$show_bf_table_captcha = 0;
		// Captcha?
		} else {
			$show_bf_table_password = 0;
			$show_bf_table_captcha = 1;
		}

	// Protection set to "Always ON" (2):
	} else {
		$show_submenu_table = 1;
		$show_bf_table = 0;
		$show_bf_table_extra = 1;
				// Password?
		if ( empty( $bfconfig['bf_type'] ) ) {
			$show_bf_table_password = 1;
			$show_bf_table_captcha = 0;
		// Captcha?
		} else {
			$show_bf_table_password = 0;
			$show_bf_table_captcha = 1;

		}
	}

	// Make sure we can display the captcha with the GD extension:
	if ( function_exists( 'gd_info' ) ) {
		$missing_gd = '';
		$gd_disabled = '';
	} else {
		$missing_gd = '<p><span class="description">' .
			__( 'GD Support is not available on your server.', 'ninjafirewall' ) . '</span></p>';
		$gd_disabled = ' disabled="disabled"';
	}

	if ( $gd_disabled && $bfconfig['bf_type'] == 1 ) {
		echo '<div class="error notice is-dismissible"><p>' .
			__('Error: GD Support is not available on your server, the captcha protection will not work!', 'ninjafirewall') .'</p></div>';
	}

	?>

<form method="post" name="bp_form">
	<?php wp_nonce_field('bfd_save', 'nfwnonce', 0); ?>
	<table class="form-table">
		<tr style="background-color:#F9F9F9;border: solid 1px #DFDFDF;">
			<th scope="row"><?php _e('Enable brute force attack protection', 'ninjafirewall') ?></th>
			<td>&nbsp;</td>
			<td align="left">
			<label><input type="radio" name="nfw_options[bf_enable]" value="1"<?php checked($bfconfig['bf_enable'], 1) ?> onclick="toggle_submenu(1);">&nbsp;<?php _e('Yes, if under attack', 'ninjafirewall') ?></label>
			</td>
			<td align="left">
			<label><input type="radio" name="nfw_options[bf_enable]" value="2"<?php checked($bfconfig['bf_enable'], 2) ?> onclick="toggle_submenu(2);">&nbsp;<?php _e('Always ON', 'ninjafirewall') ?></label>
			</td>
			<td align="left">
			<label><input type="radio" name="nfw_options[bf_enable]" value="0"<?php checked($bfconfig['bf_enable'], 0) ?> onclick="toggle_submenu(0);">&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label>
			</td>
		</tr>
	</table>
	<br />

	<div id="submenu_table"<?php echo $show_submenu_table == 1 ? '' : ' style="display:none"' ?>>
		<table class="form-table">

			<tr style="background-color:#F9F9F9;border: solid 1px #DFDFDF;">
				<th scope="row"><?php _e('Type of protection', 'ninjafirewall') ?></th>
				<td>&nbsp;</td>
				<td align="left" style="vertical-align:top">
				<label><input type="radio" name="nfw_options[bf_type]" value="0"<?php checked($bfconfig['bf_type'], 0) ?> onclick="toggle_table(bf_enable, 0);">&nbsp;<?php _e('Password', 'ninjafirewall') ?></label>
				</td>
				<td align="left" style="vertical-align:top">
				<label><input type="radio" name="nfw_options[bf_type]" value="1"<?php checked($bfconfig['bf_type'], 1) ?> onclick="toggle_table(bf_enable, 1);"<?php echo $gd_disabled ?> />&nbsp;<?php _e('Captcha', 'ninjafirewall') ?></label><?php echo $missing_gd ?>
				</td>
			</tr>
		</table>
	</div>

	<div id="bf_table"<?php echo $show_bf_table == 1 ? '' : ' style="display:none"' ?>>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Protect the login page against', 'ninjafirewall') ?></th>
				<td align="left">
				<p><label><input onclick="getpost(this.value);" type="radio" name="nfw_options[bf_request]" value="GET"<?php checked($bfconfig['bf_request'], 'GET') ?>>&nbsp;<?php _e('<code>GET</code> request attacks', 'ninjafirewall') ?></label></p>
				<p><label><input onclick="getpost(this.value);" type="radio" name="nfw_options[bf_request]" value="POST"<?php checked($bfconfig['bf_request'], 'POST') ?>>&nbsp;<?php _e('<code>POST</code> request attacks (default)', 'ninjafirewall') ?></label></p>
				<p><label><input onclick="getpost(this.value);" type="radio" name="nfw_options[bf_request]" value="GETPOST"<?php checked($bfconfig['bf_request'], 'GETPOST') ?>>&nbsp;<?php _e('<code>GET</code> and <code>POST</code> requests attacks', 'ninjafirewall') ?></label></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Enable protection', 'ninjafirewall') ?></th>
				<td align="left">
				<?php
					printf( __('For %1$s minutes, if more than %2$s %3$s requests within %4$s seconds.', 'ninjafirewall'),
						'<input maxlength="2" size="2" value="'. $bfconfig['bf_bantime'] .'" name="nfw_options[bf_bantime]" id="ban1" onkeyup="is_number(\'ban1\')" class="small-text" type="number" />',
						'<input maxlength="2" size="2" value="'. $bfconfig['bf_attempt'] .'" name="nfw_options[bf_attempt]" id="ban2" onkeyup="is_number(\'ban2\')" class="small-text" type="number" />', '<code id="get_post">'. $get_post .'</code>',
						'<input maxlength="2" size="2" value="'. $bfconfig['bf_maxtime'] .'" name="nfw_options[bf_maxtime]" id="ban3" onkeyup="is_number(\'ban3\')" class="small-text" type="number" />'
					);
				?>
				</td>
			</tr>
		</table>
	</div>

	<?php
	if ( empty($bfconfig['auth_pass']) ) {
		$placeholder = '';
	} else {
		$placeholder = '&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;';
	}
	?>
	<div id="bf_table_password"<?php echo $show_bf_table_password ? '' : ' style="display:none"' ?>>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('HTTP authentication', 'ninjafirewall') ?></th>
				<td align="left">
					<?php _e('User:', 'ninjafirewall') ?>&nbsp;<input maxlength="32" type="text" autocomplete="off" value="<?php echo htmlspecialchars( $bfconfig['auth_name'] ) ?>" size="12" name="nfw_options[auth_name]" onkeyup="auth_user_valid();" />&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Password:', 'ninjafirewall') ?>&nbsp;<input maxlength="32" placeholder="<?php echo $placeholder ?>" type="password" autocomplete="off" value="" size="12" name="nfw_options[auth_pass]" />
					<br /><span class="description">&nbsp;<?php _e('User and Password must be from 6 to 32 characters.', 'ninjafirewall') ?></span>
					<br /><br /><?php _e('Message (max. 1024 characters, HTML tags allowed)', 'ninjafirewall') ?>:<br />
					<textarea id="realm" name="nfw_options[auth_msgtxt]" class="small-text code" cols="60" rows="5" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" oninput="realm_valid();"><?php echo htmlspecialchars( $bfconfig['auth_msgtxt'] ) ?></textarea>
				</td>
			</tr>
		</table>
	</div>


	<div id="bf_table_captcha"<?php echo $show_bf_table_captcha ? '' : ' style="display:none"' ?>>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Message', 'ninjafirewall') ?></th>
				<td align="left">
					<input maxlength="255" class="large-text" type="text" autocomplete="off" value="<?php echo htmlspecialchars( $bfconfig['captcha_text'] ) ?>" name="nfw_options[captcha_text]" />
					<p><span class="description"><?php _e('This message will be displayed above the captcha. Max. 255 characters.', 'ninjafirewall') ?></span></p>
				</td>
			</tr>
		</table>
	</div>


	<div id="bf_table_extra"<?php echo $show_bf_table_extra ? '' : ' style="display:none"' ?>>
		<br />
		<h3><?php _e('Various options', 'ninjafirewall') ?></h3>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('XML-RPC API', 'ninjafirewall') ?></th>
				<td align="left">
				<label><input type="checkbox" onClick="xmlrpc_warn(this);" name="nfw_options[bf_xmlrpc]" value="1"<?php checked($bfconfig['bf_xmlrpc'], 1) ?>>&nbsp;<?php _e('Apply the protection to the <code>xmlrpc.php</code> script as well.', 'ninjafirewall') ?></label>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php _e('Bot protection', 'ninjafirewall') ?></th>
				<td align="left">
				<label><input type="checkbox" name="nfw_options[bf_allow_bot]" value="1"<?php checked($bfconfig['bf_allow_bot'], 0) ?>>&nbsp;<?php _e('Enable bot protection (applies to <code>wp-login.php</code> only.)', 'ninjafirewall') ?></label>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e('Authentication log', 'ninjafirewall') ?></th>
				<td align="left">
					<?php
					// Ensure that openlog() and syslog() are not disabled:
					if (! function_exists('syslog') || ! function_exists('openlog') ) {
						$bfconfig['bf_authlog'] = 0;
						$bf_msg = __('Your server configuration is not compatible with that option.', 'ninjafirewall');
						$enabled = 0;
					} else {
						$bf_msg = __('See contextual help before enabling this option.', 'ninjafirewall');
						$enabled = 1;
					}
					?>
					<label><input type="checkbox" name="nfw_options[bf_authlog]" value="1"<?php checked($bfconfig['bf_authlog'], 1) ?><?php disabled($enabled, 0)?>>&nbsp;<?php _e('Write the incident to the server Authentication log.', 'ninjafirewall') ?></label>
					<br />
					<span class="description"><?php echo $bf_msg ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Signature', 'ninjafirewall') ?></th>
				<td align="left">
				<label><input type="checkbox" name="nfw_options[bf_nosig]" value="1"<?php checked($bfconfig['bf_nosig'], 1) ?>>&nbsp;<?php
				// translators: "Brute-force protection by NinjaFirewall" should not be translated.
				_e('Disable the <i>Brute-force protection by NinjaFirewall</i> signature on the protection page.', 'ninjafirewall') ?></label>
				</td>
			</tr>

		</table>
	</div>

	<br />
	<br />
	<input id="save_login" class="button-primary" type="submit" name="Save" value="<?php _e('Save Login Protection', 'ninjafirewall') ?>" />
	<div align="right"><?php _e('See our benchmark and stress-test:', 'ninjafirewall') ?>
	<br />
	<a href="https://blog.nintechnet.com/wordpress-brute-force-attack-detection-plugins-comparison-2015/">Brute-force attack detection plugins comparison</a>
	</div>
</form>
</div>

<?php

/* ================================================================== */

function nf_sub_loginprot_save() {

	// Block immediately if user is not allowed :
	nf_not_allowed( 'block', __LINE__ );

	// The directory must be writable :
	if (! is_writable( NFW_LOG_DIR . '/nfwlog/cache' ) ) {
		return( sprintf( __('Error: %s directory is not writable. Please chmod it to 0777.', 'ninjafirewall'), '<code>'. htmlspecialchars(NFW_LOG_DIR) .'/nfwlog/cache</code>') );
	}

	$nfw_options = nfw_get_option( 'nfw_options' );

	$bf_rand = '';
	if ( file_exists( NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php' ) ) {
		require( NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php' );
	}

	if ( preg_match( '/^[012]$/', $_POST['nfw_options']['bf_enable'] ) ) {
		$bf_enable = $_POST['nfw_options']['bf_enable'];
	} else {
		$bf_enable = 1;
	}

	if ( preg_match( '/^[01]$/', $_POST['nfw_options']['bf_type'] ) ) {
		$bf_type = $_POST['nfw_options']['bf_type'];
	} else {
		$bf_type = 0;
	}

	// Ensure we have all values, otherwise set the default ones :
	if ( @preg_match('/^(GET|POST|GETPOST)$/', $_POST['nfw_options']['bf_request'] ) ) {
		$bf_request = $_POST['nfw_options']['bf_request'];
	} else {
		// Default value :
		$bf_request = 'POST';
	}

	if ( @preg_match('/^[1-9][0-9]?$/', $_POST['nfw_options']['bf_bantime'] ) ) {
		$bf_bantime = $_POST['nfw_options']['bf_bantime'];
	} else {
		// Default value :
		$bf_bantime = 5;
	}
	if ( @preg_match('/^[1-9][0-9]?$/', $_POST['nfw_options']['bf_attempt'] ) ) {
		$bf_attempt = $_POST['nfw_options']['bf_attempt'];
	} else {
		// Default value :
		$bf_attempt = 8;
	}
	if ( @preg_match('/^[1-9][0-9]?$/', $_POST['nfw_options']['bf_maxtime'] ) ) {
		$bf_maxtime = $_POST['nfw_options']['bf_maxtime'];
	} else {
		// Default value :
		$bf_maxtime = 15;
	}

	if ( empty($_POST['nfw_options']['bf_xmlrpc']) ) {
		$bf_xmlrpc = 0;
	} else {
		$bf_xmlrpc = 1;
	}

	if ( empty($_POST['nfw_options']['bf_authlog']) ) {
		$bf_authlog = 0;
	} else {
		$bf_authlog = 1;
	}

	if ( empty($_POST['nfw_options']['bf_allow_bot']) ) {
		$bf_allow_bot = 1;
	} else {
		$bf_allow_bot = 0;
	}

	if ( empty($_POST['nfw_options']['bf_nosig']) ) {
		$bf_nosig = 0;
	} else {
		$bf_nosig = 1;
	}

	if ( empty($_POST['nfw_options']['auth_name']) && ! empty( $bf_enable ) && empty( $bf_type ) ) {
		return( __('Error: please enter a user name for HTTP authentication.', 'ninjafirewall') );
	} elseif (! preg_match('`^[-/\\_.a-zA-Z0-9]{6,32}$`', $_POST['nfw_options']['auth_name']) && ! empty( $bf_enable ) && empty( $bf_type ) ) {
		return( __('Error: HTTP authentication user name is not valid.', 'ninjafirewall') );
	}
	$auth_name = $_POST['nfw_options']['auth_name'];

	if ( empty($_POST['nfw_options']['auth_pass']) && ! empty( $bf_enable ) && empty( $bf_type ) ) {
		if ( empty($auth_name) || empty($auth_pass) ) {
			return( __('Error: please enter a user name and password for HTTP authentication.', 'ninjafirewall') );
		}
	} elseif ( (strlen($_POST['nfw_options']['auth_pass']) < 6 || strlen($_POST['nfw_options']['auth_pass']) > 32 ) && ! empty( $bf_enable ) && empty( $bf_type ) ) {
		return( __('Error: password must be from 6 to 32 characters.', 'ninjafirewall') );
	} else {
		// Use stripslashes() to prevent WordPress from escaping the password:
		$auth_pass = sha1( stripslashes( $_POST['nfw_options']['auth_pass'] ) );
	}

	if ( empty( $_POST['nfw_options']['auth_msgtxt'] ) ) {
		$auth_msgtxt =  base64_encode( __('Access restricted', 'ninjafirewall') );
	} else {
		$auth_msgtxt = stripslashes( $_POST['nfw_options']['auth_msgtxt'] );
		if ( strlen( $auth_msgtxt ) > 1024 ) {
			$auth_msgtxt = mb_substr( $auth_msgtxt, 0, 1024, 'utf-8' );
		}
		$auth_msgtxt = base64_encode( $auth_msgtxt );
	}

	if ( empty( $_POST['nfw_options']['captcha_text'] ) ) {
		$captcha_text =  base64_encode( __('Type the characters you see in the picture below:', 'ninjafirewall') );
	} else {
		$captcha_text = stripslashes( $_POST['nfw_options']['captcha_text'] );
		if ( strlen( $captcha_text ) > 255 ) {
			$captcha_text = mb_substr( $captcha_text, 0, 255, 'utf-8' );
		}
		$captcha_text = base64_encode( htmlentities( $captcha_text ) );
	}

	// Generate a new rand value:
	$bf_rand = mt_rand(100000, 999999);

	// Save config:
	$data = "<?php \$bf_enable={$bf_enable};\$bf_type={$bf_type};\$bf_request='{$bf_request}';\$bf_bantime={$bf_bantime};\$bf_attempt={$bf_attempt};\$bf_maxtime={$bf_maxtime};\$bf_xmlrpc={$bf_xmlrpc};\$bf_allow_bot={$bf_allow_bot};\$auth_name='{$auth_name}';\$auth_pass='{$auth_pass}';\$auth_msgtxt='{$auth_msgtxt}';\$bf_rand='{$bf_rand}';\$bf_authlog={$bf_authlog};\$captcha_text='{$captcha_text}';\$bf_nosig={$bf_nosig}; ?>";


	$fh = fopen( NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php', 'w' );
	if (! $fh) {
		return( sprintf( __('Error: unable to write to the %s configuration file', 'ninjafirewall'), '<code>' .
				htmlspecialchars(NFW_LOG_DIR) . '/nfwlog/cache/bf_conf.php</code>') );
	}
	fwrite( $fh, $data );
	fclose( $fh );
	// Refresh the opcode cache so that the firewall will load the new content:
	if ( function_exists( 'opcache_invalidate' ) ) {
		@opcache_invalidate( NFW_LOG_DIR . '/nfwlog/cache/bf_conf.php', true );
	}

	// Whitelist the admin:
	$_SESSION['nfw_bfd'] = $bf_rand;

	// Delete cached files:
	$path = NFW_LOG_DIR . '/nfwlog/cache/';
	$glob = glob( $path . "bf_*" );
	if ( is_array( $glob ) ) {
		foreach( $glob as $file ) {
			// Keep the current config:
			if ( preg_match( '`/bf_conf.php`', $file ) ) { continue; }
			unlink( $file );
		}
	}

}

/* ================================================================== */

function nfw_read_bf_config( $file ) {

	// Rather then including the file with include() or require(), we open
	// and read it, because if the user had an opcode cache running, changes
	// would not appear right away.

	$conf = file_get_contents( $file );

	$bfconfig = array();

	if ( preg_match( '/\$bf_enable=[\'"]?(\d*)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_enable'] = $match[1];
	}
	if ( preg_match( '/\$bf_type=[\'"]?(\d*)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_type'] = $match[1];
	}
	if ( preg_match( '/\$bf_request=[\'"]?(.*?)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_request'] = $match[1];
	}
	if ( preg_match( '/\$bf_bantime=[\'"]?(\d*)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_bantime'] = $match[1];
	}
	if ( preg_match( '/\$bf_attempt=[\'"]?(\d*)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_attempt'] = $match[1];
	}
	if ( preg_match( '/\$bf_maxtime=[\'"]?(\d*)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_maxtime'] = $match[1];
	}
	if ( preg_match( '/\$bf_xmlrpc=[\'"]?(\d*)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_xmlrpc'] = $match[1];
	}
	if ( preg_match( '/\$bf_allow_bot=[\'"]?(\d*)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_allow_bot'] = $match[1];
	}
	if ( preg_match( '/\$auth_name=[\'"]?(.*?)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['auth_name'] = $match[1];
	}
	if ( preg_match( '/\$auth_pass=[\'"]?(.*?)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['auth_pass'] = $match[1];
	}
	if ( preg_match( '/\$auth_msgtxt=[\'"]?(.*?)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['auth_msgtxt'] = $match[1];
	}
	if ( preg_match( '/\$bf_rand=[\'"]?(.*?)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_rand'] = $match[1];
	}
	if ( preg_match( '/\$bf_authlog=[\'"]?(.*?)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_authlog'] = $match[1];
	}
	if ( preg_match( '/\$captcha_text=[\'"]?(.*?)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['captcha_text'] = $match[1];
	}
	if ( preg_match( '/\$bf_nosig=[\'"]?(.*?)[\'"]?;/', $conf, $match ) ) {
		$bfconfig['bf_nosig'] = $match[1];
	}

	return $bfconfig;

}

/* ================================================================== */
// EOF
