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

// Block immediately if user is not allowed :
nf_not_allowed( 'block', __LINE__ );


$yes = __('Yes', 'ninjafirewall');
$no =  __('No', 'ninjafirewall');
$default =  ' ' . __('(default)', 'ninjafirewall');
$full_waf_msg = '<br /><img src="' . plugins_url() . '/ninjafirewall/images/glyphicons-warning.png">&nbsp;<span class="description">' . sprintf( __('This feature is only available when NinjaFirewall is running in %s mode.', 'ninjafirewall'), '<a href="https://blog.nintechnet.com/full_waf-vs-wordpress_waf/">Full WAF</a>') . '</span>';
if ( defined('NFW_WPWAF') ) {
	$option_disabled = 1;
} else {
	$option_disabled = 0;
}

$nfw_options = nfw_get_option( 'nfw_options' );
$nfw_rules = nfw_get_option( 'nfw_rules' );

echo '
<script>
function restore() {
   if (confirm("' . esc_js( __('All fields will be restored to their default values. Go ahead?', 'ninjafirewall') ) . '")){
      return true;
   }else{
		return false;
   }
}
function chksubmenu() {
	if (document.fwrules.elements[\'nfw_options[uploads]\'].value > 0) {
      document.fwrules.san.disabled = false;
      document.fwrules.subs.disabled = false;
      document.getElementById("sanitize-fn").style.color = "#444";
   } else {
      document.fwrules.san.disabled = true;
      document.fwrules.subs.disabled = true;
      document.getElementById("sanitize-fn").style.color = "#bbbbbb";
   }
}
function csp_onoff(what, csp) {
	if (what == 0) {
		document.getElementById(csp).readOnly = true;
	} else {
		document.getElementById(csp).readOnly = false;
		document.getElementById(csp).focus();
	}
}
function ssl_warn() {';
if ($_SERVER['SERVER_PORT'] == 443 ) {
	echo 'return true;';
} else {
	echo '
		if (confirm("' . esc_js( __('WARNING: ensure that you can access your admin console over HTTPS before enabling this option, otherwise you will lock yourself out of your site. Go ahead?', 'ninjafirewall') ) . '")){
			return true;
		}
		return false;';
}
echo '
}
function sanitise_fn(cbox) {
	if(cbox.checked) {
		if (confirm("' . esc_js( __('Any character that is not a letter [a-zA-Z], a digit [0-9], a dot [.], a hyphen [-] or an underscore [_] will be removed from the filename and replaced with the substitution character. Continue?', 'ninjafirewall') ) . '")){
			return true;
		}
		return false;
	}
}
function nfw_switch_tabs(tab) {
	if ( tab == 1 ) {
		jQuery("#basic-options").show(); jQuery("#tab-1").addClass("nav-tab-active");
		jQuery("#intermediate-options").hide(); jQuery("#tab-2").removeClass("nav-tab-active");
		jQuery("#advanced-options").hide(); jQuery("#tab-3").removeClass("nav-tab-active");

	} else if ( tab == 2 ) {
		jQuery("#basic-options").hide(); jQuery("#tab-1").removeClass("nav-tab-active");
		jQuery("#intermediate-options").show(); jQuery("#tab-2").addClass("nav-tab-active");
		jQuery("#advanced-options").hide(); jQuery("#tab-3").removeClass("nav-tab-active");

	} else if ( tab ==3 ) {
		jQuery("#basic-options").hide(); jQuery("#tab-1").removeClass("nav-tab-active");
		jQuery("#intermediate-options").hide(); jQuery("#tab-2").removeClass("nav-tab-active");
		jQuery("#advanced-options").show(); jQuery("#tab-3").addClass("nav-tab-active");
	}
}
</script>

<div class="wrap">
	<h1><img style="vertical-align:top;width:33px;height:33px;" src="'. plugins_url( '/ninjafirewall/images/ninjafirewall_32.png' ) .'">&nbsp;' . __('Firewall Policies', 'ninjafirewall') . '</h1>';


if ( isset( $_POST['nfw_options']) ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'policies_save') ) {
		wp_nonce_ays('policies_save');
	}
	if (! empty($_POST['Save']) ) {
		nf_sub_policies_save();
		echo '<div class="updated notice is-dismissible"><p>' . __('Your changes have been saved.', 'ninjafirewall') . '</p></div>';
	} elseif (! empty($_POST['Default']) ) {
		nf_sub_policies_default();
		echo '<div class="updated notice is-dismissible"><p>' . __('Default values were restored.', 'ninjafirewall') . '</p></div>';
	} else {
		echo '<div class="error notice is-dismissible"><p>' . __('No action taken.', 'ninjafirewall') . '</p></div>';
	}
	$nfw_options = nfw_get_option( 'nfw_options' );
	$nfw_rules = nfw_get_option( 'nfw_rules' );
}

?>
<br />
<h2 class="nav-tab-wrapper wp-clearfix" style="cursor:pointer">
	<a id="tab-1" class="nav-tab nav-tab-active" onClick="nfw_switch_tabs(1)"><?php _e( 'Basic Policies', 'ninjafirewall' ) ?></a>
	<a id="tab-2" class="nav-tab" onClick="nfw_switch_tabs(2)"><?php _e( 'Intermediate Policies', 'ninjafirewall' ) ?></a>
	<a id="tab-3" class="nav-tab" onClick="nfw_switch_tabs(3)"><?php _e( 'Advanced Policies', 'ninjafirewall' ) ?></a>
</h2>
<br />
<?php

echo '<form method="post" name="fwrules">';
wp_nonce_field('policies_save', 'nfwnonce', 0);

// ==========================================================================
// Basic options:
?>
<div id="basic-options">
<?php
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
		<th scope="row"><?php _e('Enable NinjaFirewall for', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td>
		<p><label><input type="radio" name="nfw_options[scan_protocol]" value="3"<?php checked($scan_protocol, 3 ) ?>>&nbsp;<?php _e('HTTP and HTTPS traffic (default)', 'ninjafirewall') ?></label></p>
		<p><label><input type="radio" name="nfw_options[scan_protocol]" value="1"<?php checked($scan_protocol, 1 ) ?>>&nbsp;<?php _e('HTTP traffic only', 'ninjafirewall') ?></label></p>
		<p><label><input type="radio" name="nfw_options[scan_protocol]" value="2"<?php checked($scan_protocol, 2 ) ?>>&nbsp;<?php _e('HTTPS traffic only', 'ninjafirewall') ?></label></p>
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
if ( empty( $nfw_options['substitute'] ) || strlen( $nfw_options['substitute'] ) > 1 || $nfw_options['substitute'] == '/' ) {
	$substitute = 'X';
} else {
	$substitute = htmlspecialchars( $nfw_options['substitute'] );
}
?>
<br />
<h3><?php _e('Uploads', 'ninjafirewall') ?></h3>
<table class="form-table">
	<tr>
		<th scope="row"><?php _e('File Uploads', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td>
			<select name="nfw_options[uploads]" onchange="chksubmenu();">
				<option value="1"<?php selected( $uploads, 1 ) ?>><?php echo __('Allow uploads', 'ninjafirewall') .' '. __('(default)', 'ninjafirewall') ?></option>
				<option value="0"<?php selected( $uploads, 0 ) ?>><?php _e('Disallow uploads', 'ninjafirewall') ?></option>
			</select>
			<p id="sanitize-fn"<?php if (! $uploads) { echo ' style="color:#bbbbbb;"'; }?>>
				<label><input type="checkbox" onclick='return sanitise_fn(this);' name="nfw_options[sanitise_fn]"<?php checked( $sanitise_fn, 1 ); disabled( $uploads, 0 ) ?> id="san">&nbsp;<?php _e('Sanitise filenames', 'ninjafirewall') ?> (<?php _e('substitution character:', 'ninjafirewall') ?></label> <input id="subs" maxlength="1" size="1" value="<?php echo $substitute ?>" name="nfw_options[substitute]" type="text" <?php disabled( $uploads, 0 ) ?>/> )
				</p>
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
if ( empty( $nfw_options['disallow_creation']) ) {
	$disallow_creation = 0;
} else {
	$disallow_creation = 1;
}
if ( empty( $nfw_options['disallow_settings']) ) {
	$disallow_settings = 0;
} else {
	$disallow_settings = 1;
}
if ( empty( $nfw_options['enum_archives']) ) {
	$enum_archives = 0;
} else {
	$enum_archives = 1;
}
if ( empty( $nfw_options['enum_login']) ) {
	$enum_login = 0;
} else {
	$enum_login = 1;
}
if ( empty( $nfw_options['enum_restapi']) ) {
	$enum_restapi = 0;
} else {
	$enum_restapi = 1;
}
if ( empty( $nfw_options['no_restapi']) ) {
	$no_restapi = 0;
} else {
	$no_restapi = 1;
}
if ( empty( $nfw_options['no_xmlrpc']) ) {
	$no_xmlrpc = 0;
} else {
	$no_xmlrpc = 1;
}
if ( empty( $nfw_options['no_xmlrpc_multi']) ) {
	$no_xmlrpc_multi = 0;
} else {
	$no_xmlrpc_multi = 1;
}
if ( empty( $nfw_options['no_xmlrpc_pingback']) ) {
	$no_xmlrpc_pingback = 0;
} else {
	$no_xmlrpc_pingback = 1;
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

$force_ssl_already_enabled = 0; $disallow_edit_already_enabled = 0;
$disallow_mods_already_enabled = 0;
if ( defined('DISALLOW_FILE_EDIT') && ! $disallow_edit ) {
	$disallow_edit_already_enabled = 1;
}
if ( defined('DISALLOW_FILE_MODS') && ! $disallow_mods ) {
	$disallow_mods_already_enabled = 1;
}
if ( defined('FORCE_SSL_ADMIN') && FORCE_SSL_ADMIN == true && ! $force_ssl ) {
	$force_ssl_already_enabled = 1;
}
?>
<h3>WordPress</h3>
<table class="form-table">
	<tr>
		<th scope="row"><?php
			_e('Block direct access to any PHP file located in one of these directories', 'ninjafirewall');
			if ( defined('NFW_WPWAF') ) {
				echo '<br /><font style="font-weight:400">' . $full_waf_msg . '</font>';
			}
		?></th>
		<td width="20">&nbsp;</td>
		<td>
			<table class="form-table">
				<tr style="border: solid 1px #DFDFDF;">
					<td align="center" width="10"><input type="checkbox" name="nfw_options[wp_admin]" id="wp_01"<?php checked( $wp_admin, 1 ); disabled( $option_disabled, 1) ?>></td>
					<td>
					<label for="wp_01">
					<p><code>/wp-admin/css/*</code></p>
					<p><code>/wp-admin/images/*</code></p>
					<p><code>/wp-admin/includes/*</code></p>
					<p><code>/wp-admin/js/*</code></p>
					</label>
					</td>
				</tr>
				<tr style="border: solid 1px #DFDFDF;">
					<td align="center" width="10"><input type="checkbox" name="nfw_options[wp_inc]" id="wp_02"<?php checked( $wp_inc, 1 ); disabled( $option_disabled, 1) ?>></td>
					<td>
					<label for="wp_02">
					<p><code>/wp-includes/*.php</code></p>
					<p><code>/wp-includes/css/*</code></p>
					<p><code>/wp-includes/images/*</code></p>
					<p><code>/wp-includes/js/*</code></p>
					<p><code>/wp-includes/theme-compat/*</code></p>
					</label>
					<br />
					<span class="description"><?php _e('NinjaFirewall will not block access to the TinyMCE WYSIWYG editor even if this option is enabled.', 'ninjafirewall') ?></span>
					</td>
				</tr>
				<tr style="border: solid 1px #DFDFDF;">
					<td align="center" width="10"><input type="checkbox" name="nfw_options[wp_upl]" id="wp_03"<?php checked( $wp_upl, 1 ); disabled( $option_disabled, 1) ?>></td>
					<td><label for="wp_03">
						<p><code>/<?php echo basename(WP_CONTENT_DIR); ?>/uploads/*</code></p>
						<p><code>/<?php echo basename(WP_CONTENT_DIR); ?>/blogs.dir/*</code></p>
					</label></td>
				</tr>
				<tr style="border: solid 1px #DFDFDF;">
					<td align="center" style="vertical-align:top" width="10"><input type="checkbox" name="nfw_options[wp_cache]" id="wp_04"<?php checked( $wp_cache, 1 ); disabled( $option_disabled, 1) ?>></td>
					<td style="vertical-align:top"><label for="wp_04"><code>*/cache/*</code></label>
					<br />
					<br />
					<span class="description"><?php _e('Unless you have PHP scripts in a "/cache/" folder that need to be accessed by your visitors, we recommend to enable this option.', 'ninjafirewall') ?></span>
					</td>
				</tr>
			</table>
			<br />&nbsp;
		</td>
	</tr>
</table>

<?php
if ( is_dir( WP_PLUGIN_DIR . '/jetpack' ) ) {
	$is_JetPack = '<p><img src="' . plugins_url() . '/ninjafirewall/images/glyphicons-warning.png">&nbsp;<span class="description">' . sprintf( __('If you are using the %s plugin, blocking <code>system.multicall</code> may prevent it from working correctly.', 'ninjafirewall'), 'Jetpack') . '</span></p>';
} else {
	$is_JetPack = '';
}
if ( is_dir( WP_PLUGIN_DIR . '/contact-form-7' ) ) {
	$is_CF7 = '<p><img src="' . plugins_url() . '/ninjafirewall/images/glyphicons-warning.png">&nbsp;<span class="description">' . sprintf( __('If you are using the %s plugin, blocking <code>system.multicall</code> may prevent it from working correctly.', 'ninjafirewall'), 'Contact Form 7') . '</span></p>';
} else {
	$is_CF7 = '';
}
?>

<table class="form-table">
	<tr>
		<th scope="row"><?php _e('General', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td>
			<p><label><input type="checkbox" name="nfw_options[disallow_settings]" value="1"<?php checked( $disallow_settings, 1 ) ?>>&nbsp;<?php echo __('Block attempts to modify important WordPress settings', 'ninjafirewall') .' '. __('(default)', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[disallow_creation]" value="1"<?php checked( $disallow_creation, 1 ) ?>>&nbsp;<?php _e('Block user accounts creation', 'ninjafirewall') ?></label></p>
			<span class="description"><?php _e('Do not enable this policy if you allow user registration.', 'ninjafirewall') ?></span>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e('Protect against username enumeration', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td>
			<p><label><input type="checkbox" name="nfw_options[enum_archives]" value="1"<?php checked( $enum_archives, 1 ) ?>>&nbsp;<?php _e('Through the author archives', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[enum_login]" value="1"<?php checked( $enum_login, 1 ) ?>>&nbsp;<?php _e('Through the login page', 'ninjafirewall') ?></label></p>
			<p><label><input type="checkbox" name="nfw_options[enum_restapi]" value="1"<?php checked( $enum_restapi, 1 ) ?>>&nbsp;<?php _e('Through the WordPress REST API', 'ninjafirewall') ?></label></p>
		</td>
	</tr>

	<?php
	global $wp_version;
	if ( version_compare( $wp_version, '4.7', '<' ) ) {
		$restapi_error = '1';
		$restapi_msg = '<p><img src="' . plugins_url() . '/ninjafirewall/images/glyphicons-warning.png">&nbsp;<span class="description">' . __('This feature is only available when running WordPress 4.7 or above.', 'ninjafirewall') . '</span></p>';
	} else {
		$restapi_msg = '';
		$restapi_error = 0;
	}
	?>
	<tr>
		<th scope="row"><?php _e('WordPress REST API', 'ninjafirewall') ?>*</th>
		<td width="20">&nbsp;</td>
		<td>
			<p><label><input type="checkbox" name="nfw_options[no_restapi]" value="1"<?php checked( $no_restapi, 1 );disabled( $restapi_error, 1) ?>>&nbsp;<?php _e('Block any access to the API', 'ninjafirewall') ?></label></p>
			<?php echo $restapi_msg; ?>
		</td>
	</tr>
		<tr>
		<th scope="row"><?php _e('WordPress XML-RPC API', 'ninjafirewall') ?>*</th>
		<td width="20">&nbsp;</td>
		<td>
			<p><label><input type="checkbox" name="nfw_options[no_xmlrpc]" value="1"<?php checked( $no_xmlrpc, 1 ) ?>>&nbsp;<?php _e('Block any access to the API', 'ninjafirewall') ?></label></p>

			<p><label><input type="checkbox" name="nfw_options[no_xmlrpc_multi]" value="1"<?php checked( $no_xmlrpc_multi, 1 ) ?>>&nbsp;<?php _e('Block <code>system.multicall</code> method', 'ninjafirewall') ?></label></p>

			<p><label><input type="checkbox" name="nfw_options[no_xmlrpc_pingback]" value="1"<?php checked( $no_xmlrpc_pingback, 1 ) ?>>&nbsp;<?php _e('Block Pingbacks', 'ninjafirewall') ?></label></p>

			<?php echo $is_JetPack; echo $is_CF7; ?>
		</td>
	</tr>
</table>

<span class="description">*<?php _e('Disabling access to the REST or XML-RPC API may break some functionality on your blog, its themes or plugins.', 'ninjafirewall') ?></span>

<table class="form-table">
	<tr valign="top">
		<th scope="row" style="vertical-align:top"><?php _e('Block <code>POST</code> requests in the themes folder', 'ninjafirewall') ?> <code>/<?php echo basename(WP_CONTENT_DIR); ?>/themes</code></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top">
			<label><input type="radio" name="nfw_options[no_post_themes]" value="1"<?php checked( $no_post_themes, 1 ); disabled( $option_disabled, 1) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td style="vertical-align:top">
			<label><input type="radio" name="nfw_options[no_post_themes]" value="0"<?php checked( $no_post_themes, 0 ); disabled( $option_disabled, 1) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label>
			<?php
			if ( defined('NFW_WPWAF') ) {
				echo '<br />'. $full_waf_msg;
			}
		?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><a name="builtinconstants"></a><?php _e('Force SSL for admin and logins', 'ninjafirewall') ?> <code><a href="http://codex.wordpress.org/Editing_wp-config.php#Require_SSL_for_Admin_and_Logins" target="_blank">FORCE_SSL_ADMIN</a></code></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[force_ssl]" value="1"<?php checked( $force_ssl, 1 ) ?> onclick="return ssl_warn();" <?php disabled( $force_ssl_already_enabled, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" id="ssl_0" name="nfw_options[force_ssl]" value="0"<?php checked( $force_ssl, 0 ) ?> <?php disabled( $force_ssl_already_enabled, 1 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Disable the plugin and theme editor', 'ninjafirewall') ?> <code><a href="http://codex.wordpress.org/Editing_wp-config.php#Disable_the_Plugin_and_Theme_Editor" target="_blank">DISALLOW_FILE_EDIT</a></code></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[disallow_edit]" value="1"<?php checked( $disallow_edit, 1 ) ?> <?php disabled( $disallow_edit_already_enabled, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[disallow_edit]" value="0"<?php checked( $disallow_edit, 0 ) ?> <?php disabled( $disallow_edit_already_enabled, 1 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Disable plugin and theme update/installation', 'ninjafirewall') ?> <code><a href="http://codex.wordpress.org/Editing_wp-config.php#Disable_Plugin_and_Theme_Update_and_Installation" target="_blank">DISALLOW_FILE_MODS</a></code></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[disallow_mods]" value="1"<?php checked( $disallow_mods, 1 ) ?> <?php disabled( $disallow_mods_already_enabled, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[disallow_mods]" value="0"<?php checked( $disallow_mods, 0 ) ?> <?php disabled( $disallow_mods_already_enabled, 1 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label>
		</td>
	</tr>

</table>
<a name="donotblockadmin"></a>
<br />
<br />

<?php
if ( empty( $nfw_options['wl_admin']) ) {
	$wl_admin = 0;
} elseif ( $nfw_options['wl_admin'] == 2 ) {
	$wl_admin = 2;
} else {
	$wl_admin = 1;
}
?>
<table class="form-table">
	<tr style="background-color:#F9F9F9;border: solid 1px #DFDFDF;">
		<th scope="row"><?php _e('Users Whitelist', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td>
		<p><label><input type="radio" name="nfw_options[wl_admin]" value="1"<?php checked( $wl_admin, 1 ) ?>>&nbsp;<?php _e('Add the Administrator to the whitelist (default).', 'ninjafirewall') ?></label></p>
		<p><label><input type="radio" name="nfw_options[wl_admin]" value="2"<?php checked( $wl_admin, 2 ) ?>>&nbsp;<?php _e('Add all logged in users to the whitelist.', 'ninjafirewall') ?></label></p>
		<p><label><input type="radio" name="nfw_options[wl_admin]" value="0"<?php checked( $wl_admin, 0 ) ?>>&nbsp;<?php _e('Disable users whitelist.', 'ninjafirewall') ?></label></p>
		<p><span class="description"><?php _e('Note: This feature  does not apply to <code>FORCE_SSL_ADMIN</code>, <code>DISALLOW_FILE_EDIT</code> and <code>DISALLOW_FILE_MODS</code> options which, if enabled, are always enforced.', 'ninjafirewall') ?></span></p>
		</td>
	</tr>
</table>

</div>


<?php
// ==========================================================================
// Intermediate options:
?>
<div id="intermediate-options" style="display:none">
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
<h3><?php _e('HTTP GET variable', 'ninjafirewall') ?></h3>
<table class="form-table">
	<tr>
		<th scope="row"><?php _e('Scan <code>GET</code> variable', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[get_scan]" value="1"<?php checked( $get_scan, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[get_scan]" value="0"<?php checked( $get_scan, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Sanitise <code>GET</code> variable', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[get_sanitise]" value="1"<?php checked( $get_sanitise, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[get_sanitise]" value="0"<?php checked( $get_sanitise, 0 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label>
		</td>
	</tr>
</table>

<br /><br />

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
if ( empty( $nfw_options['post_b64']) ) {
	$post_b64 = 0;
} else {
	$post_b64 = 1;
}
?>
<h3><?php _e('HTTP POST variable', 'ninjafirewall') ?></h3>
<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e('Scan <code>POST</code> variable', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[post_scan]" value="1"<?php checked( $post_scan, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[post_scan]" value="0"<?php checked( $post_scan, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Sanitise <code>POST</code> variable', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top;">
			<label><input type="radio" name="nfw_options[post_sanitise]" value="1"<?php checked( $post_sanitise, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[post_sanitise]" value="0"<?php checked( $post_sanitise, 0 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label><br /><span class="description">&nbsp;<?php _e('Do not enable this option unless you know what you are doing!', 'ninjafirewall') ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Decode Base64-encoded <code>POST</code> variable', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[post_b64]" value="1"<?php checked( $post_b64, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[post_b64]" value="0"<?php checked( $post_b64, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
</table>
<br /><br />

<?php
if ( empty( $nfw_options['request_sanitise']) ) {
	$request_sanitise = 0;
} else {
	$request_sanitise = 1;
}
?>
<h3><?php _e('HTTP REQUEST variable', 'ninjafirewall') ?></h3>
<table class="form-table">
	<tr>
		<th scope="row"><?php _e('Sanitise <code>REQUEST</code> variable', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top;">
			<label><input type="radio" name="nfw_options[request_sanitise]" value="1"<?php checked( $request_sanitise, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[request_sanitise]" value="0"<?php checked( $request_sanitise, 0 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label><br /><span class="description">&nbsp;<?php _e('Do not enable this option unless you know what you are doing!', 'ninjafirewall') ?></span>
		</td>
	</tr>
</table>

<br /><br />

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
<h3><?php _e('Cookies', 'ninjafirewall') ?></h3>
<table class="form-table">
	<tr>
		<th scope="row"><?php _e('Scan cookies', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[cookies_scan]" value="1"<?php checked( $cookies_scan, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[cookies_scan]" value="0"<?php checked( $cookies_scan, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Sanitise cookies', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[cookies_sanitise]" value="1"<?php checked( $cookies_sanitise, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[cookies_sanitise]" value="0"<?php checked( $cookies_sanitise, 0 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label>
		</td>
	</tr>
</table>

<br /><br />

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


if ( empty( $nfw_rules[NFW_SCAN_BOTS]['ena']) ) {
	$block_bots = 0;
} else {
	$block_bots = 1;
}
?>
<h3><?php _e('HTTP_USER_AGENT server variable', 'ninjafirewall') ?></h3>
<table class="form-table">
	<tr>
		<th scope="row"><?php _e('Scan <code>HTTP_USER_AGENT</code>', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[ua_scan]" value="1"<?php checked( $ua_scan, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[ua_scan]" value="0"<?php checked( $ua_scan, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Sanitise <code>HTTP_USER_AGENT</code>', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[ua_sanitise]" value="1"<?php checked( $ua_sanitise, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[ua_sanitise]" value="0"<?php checked( $ua_sanitise, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Block suspicious bots/scanners', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_rules[block_bots]" value="1"<?php checked( $block_bots, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_rules[block_bots]" value="0"<?php checked( $block_bots, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
</table>

<br /><br />

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
<h3><?php _e('HTTP_REFERER server variable', 'ninjafirewall') ?></h3>
<table class="form-table">
	<tr>
		<th scope="row"><?php _e('Scan <code>HTTP_REFERER</code>', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[referer_scan]" value="1"<?php checked( $referer_scan, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[referer_scan]" value="0"<?php checked( $referer_scan, 0 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Sanitise <code>HTTP_REFERER</code>', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[referer_sanitise]" value="1"<?php checked( $referer_sanitise, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[referer_sanitise]" value="0"<?php checked( $referer_sanitise, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Block <code>POST</code> requests that do not have an <code>HTTP_REFERER</code> header', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top;">
			<label><input type="radio" name="nfw_options[referer_post]" value="1"<?php checked( $referer_post, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td style="vertical-align:top;">
			<label><input type="radio" name="nfw_options[referer_post]" value="0"<?php checked( $referer_post, 0 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label><br /><span class="description">&nbsp;<?php _e('Keep this option disabled if you are using scripts like Paypal IPN, WordPress WP-Cron etc', 'ninjafirewall') ?>.</span>
		</td>
	</tr>
</table>

<br /><br />

<?php
if ( empty( $nfw_rules[NFW_LOOPBACK]['ena']) ) {
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
<h3>IP</h3>
<table class="form-table" border=0>
	<tr>
		<th scope="row"><?php _e('Block localhost IP in <code>GET/POST</code> request', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top">
			<label><input type="radio" name="nfw_rules[no_localhost_ip]" value="1"<?php checked( $no_localhost_ip, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td style="vertical-align:top">
			<label><input type="radio" name="nfw_rules[no_localhost_ip]" value="0"<?php checked( $no_localhost_ip, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Block HTTP requests with an IP in the <code>HTTP_HOST</code> header', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top">
			<label><input type="radio" name="nfw_options[no_host_ip]" value="1"<?php checked( $no_host_ip, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td style="vertical-align:top">
			<label><input type="radio" name="nfw_options[no_host_ip]" value="0"<?php checked( $no_host_ip, 0 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Scan traffic coming from localhost and private IP address spaces', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top">
			<label><input type="radio" name="nfw_options[allow_local_ip]" value="0"<?php checked( $allow_local_ip, 0 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
			</td>
		<td style="vertical-align:top">
			<label><input type="radio" name="nfw_options[allow_local_ip]" value="1"<?php checked( $allow_local_ip, 1 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
</table>

</div>

<?php
// ==========================================================================
// Advanced options:
?>
<div id="advanced-options" style="display:none">

<?php
$err_msg = $err = '';
$err_img = '<p><span class="description"><img src="' . plugins_url() . '/ninjafirewall/images/glyphicons-warning.png">&nbsp;';
$msg = __('This option is disabled because the %s PHP function is not available on your server.', 'ninjafirewall');
if (! function_exists('header_register_callback') ) {
	$err_msg = $err_img . sprintf($msg, '<code>header_register_callback()</code>') . '</span></p>';
	$err = 1;
} elseif (! function_exists('headers_list') ) {
	$err_msg = $err_img . sprintf($msg, '<code>headers_list()</code>') . '</span></p>';
	$err = 1;
} elseif (! function_exists('header_remove') ) {
	$err_msg = $err_img . sprintf($msg, '<code>header_remove()</code>') . '</span></p>';
	$err = 1;
}
if ( empty($nfw_options['response_headers']) || ! preg_match( '/^\d+$/', $nfw_options['response_headers'] ) || $err_msg ) {
	$nfw_options['response_headers'] = '000000000';
}
?>
<h3><?php _e('HTTP response headers', 'ninjafirewall')  ?></h3>
<table class="form-table">
	<tr>
		<th scope="row"><?php printf( __('Set %s to protect against MIME type confusion attacks', 'ninjafirewall'), '<a href="https://nintechnet.com/ninjafirewall/wp-edition/doc/#responseheaders" target="_blank">X-Content-Type-Options</a>') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[x_content_type_options]" value="1"<?php checked( $nfw_options['response_headers'][1], 1 ); disabled($err, 1); ?>><?php echo $yes; ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[x_content_type_options]" value="0"<?php checked( $nfw_options['response_headers'][1], 0 ); disabled($err, 1); ?>><?php echo $no . $default; ?></label><?php echo $err_msg ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php printf( __('Set %s to protect against clickjacking attempts', 'ninjafirewall'), '<a href="https://nintechnet.com/ninjafirewall/wp-edition/doc/#responseheaders" target="_blank">X-Frame-Options</a>') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top;">
			<p><label><input type="radio" name="nfw_options[x_frame_options]" value="1"<?php checked( $nfw_options['response_headers'][2], 1 ); disabled($err, 1); ?>><code>SAMEORIGIN</code></label></p>
			<p><label><input type="radio" name="nfw_options[x_frame_options]" value="2"<?php checked( $nfw_options['response_headers'][2], 2 ); disabled($err, 1); ?>><code>DENY</code></label></p>
		</td>
		<td style="vertical-align:top;"><p><label><input type="radio" name="nfw_options[x_frame_options]" value="0"<?php checked( $nfw_options['response_headers'][2], 0 ); disabled($err, 1); ?>><?php echo $no . $default; ?></label><?php echo $err_msg ?></p></td>
	</tr>
	<tr>
	<th scope="row"><?php printf( __("Set %s (IE/Edge, Chrome, Opera and Safari browsers)", 'ninjafirewall'), '<a href="https://nintechnet.com/ninjafirewall/wp-edition/doc/#responseheaders" target="_blank">X-XSS-Protection</a>') ?></th>
		<td width="20"></td>

		<td width="120">
			<p><label><input type="radio" name="nfw_options[x_xss_protection]" value="0"<?php checked( $nfw_options['response_headers'][3], 0 ); disabled($err, 1); ?> /><?php printf( __("Set to %s", "ninjafirewall"), '<code>0</code>' ) ?></label></p>
			<p><label><input type="radio" name="nfw_options[x_xss_protection]" value="2"<?php checked( $nfw_options['response_headers'][3], 2 ); disabled($err, 1); ?> /><?php printf( __("Set to %s", "ninjafirewall"), '<code>1</code>' ) ?></label></p>
		</td>
		<td>
			<p><label><input type="radio" name="nfw_options[x_xss_protection]" value="1"<?php checked( $nfw_options['response_headers'][3], 1 ); disabled($err, 1); ?> /><?php printf( __("Set to %s", "ninjafirewall"), '<code>1; mode=block</code>' ); ?></label></p>
			<p><label><input type="radio" name="nfw_options[x_xss_protection]" value="3"<?php checked( $nfw_options['response_headers'][3], 3 ); disabled($err, 1); ?> /><?php _e('No', 'ninjafirewall'); echo $default; ?></label></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php printf( __('Force %s flag on all cookies to mitigate XSS attacks', 'ninjafirewall'), '<a href="https://nintechnet.com/ninjafirewall/wp-edition/doc/#responseheaders" target="_blank">HttpOnly</a>') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top;">
			<label><input type="radio" name="nfw_options[cookies_httponly]" value="1"<?php checked( $nfw_options['response_headers'][0], 1 ); disabled($err, 1); ?> >&nbsp;<?php echo $yes ?></label>
		</td>
		<td style="vertical-align:top;">
			<label><input type="radio" name="nfw_options[cookies_httponly]" value="0"<?php checked( $nfw_options['response_headers'][0], 0 ); disabled($err, 1); ?>>&nbsp;<?php echo $no . $default; ?></label><br /><span class="description"><?php _e('If your PHP scripts use cookies that need to be accessed from JavaScript, you should disable this option.', 'ninjafirewall') ?></span><?php echo $err_msg ?>
		</td>
	</tr>
	<?php
	if ($_SERVER['SERVER_PORT'] != 443 && ! $err && (! isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') ) {
		$hsts_err = 1;
		$hsts_msg = '<br /><img src="' . plugins_url() . '/ninjafirewall/images/glyphicons-warning.png">&nbsp;<span class="description">' . __('HSTS headers can only be set when you are accessing your site over HTTPS.', 'ninjafirewall') . '</span>';
	} else {
		$hsts_msg = '';
		$hsts_err = 0;
	}
	?>
	<tr>
		<th scope="row"><?php printf( __('Set %s (HSTS) to enforce secure connections to the server', 'ninjafirewall'), '<a href="https://nintechnet.com/ninjafirewall/wp-edition/doc/#responseheaders" target="_blank">Strict-Transport-Security</a>') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top;">
			<p><label><input type="radio" name="nfw_options[strict_transport]" value="1"<?php checked( $nfw_options['response_headers'][4], 1 ); disabled($hsts_err, 1); ?>><?php _e('1 month', 'ninjafirewall') ?></label></p>
			<p><label><input type="radio" name="nfw_options[strict_transport]" value="2"<?php checked( $nfw_options['response_headers'][4], 2 ); disabled($hsts_err, 1); ?>><?php _e('6 months', 'ninjafirewall') ?></label></p>
			<p><label><input type="radio" name="nfw_options[strict_transport]" value="3"<?php checked( $nfw_options['response_headers'][4], 3 ); disabled($hsts_err, 1); ?>><?php _e('1 year', 'ninjafirewall') ?></label></p>
			<br />
			<label><input type="checkbox" name="nfw_options[strict_transport_sub]" value="1"<?php checked( $nfw_options['response_headers'][5], 1 ); disabled($hsts_err, 1); ?>><?php _e('Apply to subdomains', 'ninjafirewall') ?></label>
		</td>
		<td style="vertical-align:top;">
			<p><label><input type="radio" name="nfw_options[strict_transport]" value="0"<?php checked( $nfw_options['response_headers'][4], 0 ); disabled($hsts_err, 1); ?>><?php echo $no . $default; ?></label></p>
			<p><label><input type="radio" name="nfw_options[strict_transport]" value="4"<?php checked( $nfw_options['response_headers'][4], 4 ); disabled($hsts_err, 1); ?>><?php _e('Set <code>max-age</code> to 0', 'ninjafirewall'); ?></label><?php echo $err_msg ?></p>
			<?php echo $hsts_msg; ?>
		</td>
	</tr>

	<?php
		if (! isset( $nfw_options['csp_frontend_data'] ) ) {
			$nfw_options['csp_frontend_data'] = '';
		}
		if (! isset( $nfw_options['csp_backend_data'] ) ) {
			$nfw_options['csp_backend_data'] = nf_sub_policies_csp();
		}
		if (! isset( $nfw_options['response_headers'][6] ) ) {
			$nfw_options['response_headers'][6] = 0;
		}
		if (! isset( $nfw_options['response_headers'][7] ) ) {
			$nfw_options['response_headers'][7] = 0;
		}
	?>
	<tr>
		<th scope="row"><?php printf( __('Set %s for the website frontend', 'ninjafirewall'), '<a href="https://nintechnet.com/ninjafirewall/wp-edition/doc/#responseheaders" target="_blank">Content-Security-Policy</a>') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top;">
			<p><label><input type="radio" onclick="csp_onoff(1, 'csp_frontend')" name="nfw_options[csp_frontend]" value="1"<?php checked( $nfw_options['response_headers'][6], 1 ); disabled($err, 1); ?>><?php _e('Yes', 'ninjafirewall') ?></label></p>
			<p><label><input type="radio" onclick="csp_onoff(0, 'csp_frontend')" name="nfw_options[csp_frontend]" value="0"<?php checked( $nfw_options['response_headers'][6], 0 ); disabled($err, 1); ?>><?php _e('No (default)', 'ninjafirewall') ?></label></p>
		</td>
		<td style="vertical-align:top;">
			<textarea autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" name="nfw_options[csp_frontend_data]" id="csp_frontend" class="large-text code" rows="4"<?php __checked_selected_helper($err, 1, true, 'readonly'); __checked_selected_helper($nfw_options['response_headers'][6], 0, true, 'readonly') ?>><?php echo htmlspecialchars( $nfw_options['csp_frontend_data'] ) ?></textarea>
			<span class="description"><?php _e('This CSP header will apply to the website frontend only.', 'ninjafirewall') ?></span>
			<?php echo $err_msg ?>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php printf( __('Set %s for the WordPress admin dashboard', 'ninjafirewall'), '<a href="https://nintechnet.com/ninjafirewall/wp-edition/doc/#responseheaders" target="_blank">Content-Security-Policy</a>') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top;">
			<p><label><input type="radio" onclick="csp_onoff(1, 'csp_backend')" name="nfw_options[csp_backend]" value="1"<?php checked( $nfw_options['response_headers'][7], 1 ); disabled($err, 1); ?>><?php _e('Yes', 'ninjafirewall') ?></label></p>
			<p><label><input type="radio" onclick="csp_onoff(0, 'csp_backend')" name="nfw_options[csp_backend]" value="0"<?php checked( $nfw_options['response_headers'][7], 0 ); disabled($err, 1); ?>><?php _e('No (default)', 'ninjafirewall') ?></label></p>
		</td>
		<td style="vertical-align:top;">
			<textarea autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" name="nfw_options[csp_backend_data]" id="csp_backend" class="large-text code" rows="4"<?php __checked_selected_helper($err, 1, true, 'readonly'); __checked_selected_helper($nfw_options['response_headers'][7], 0, true, 'readonly') ?>><?php echo htmlspecialchars( $nfw_options['csp_backend_data'] ) ?></textarea>
			<span class="description"><?php _e('This CSP header will apply to the WordPress admin dashboard only.', 'ninjafirewall') ?></span>
			<?php echo $err_msg ?>
		</td>
	</tr>

	<?php
	if (! isset( $nfw_options['response_headers'][8] ) ) {
		$nfw_options['response_headers'][8] = 0;
	}
	if ( empty( $nfw_options['referrer_policy_enabled'] ) ) {
		$nfw_options['referrer_policy_enabled'] = 0;
	} else {
		$nfw_options['referrer_policy_enabled'] = 1;
	}
	?>
	<tr>
		<th scope="row"><?php printf( __("Set %s (Chrome, Opera and Firefox browsers)", 'ninjafirewall'), '<a href="https://nintechnet.com/ninjafirewall/wp-edition/doc/#responseheaders" target="_blank">Referrer-Policy</a>') ?></th>
		<td width="20"></td>
		<td width="120" style="vertical-align:top;">

			<p><label><input onclick="document.getElementById('rp_select').disabled=false" type="radio" name="nfw_options[referrer_policy_enabled]" value="1"<?php checked($nfw_options['referrer_policy_enabled'], 1) ?> /><?php _e('Yes', 'ninjafirewall') ?></label></p>
			<p><label><input onclick="document.getElementById('rp_select').disabled=true" type="radio" name="nfw_options[referrer_policy_enabled]" value="0"<?php checked($nfw_options['referrer_policy_enabled'], 0) ?> /><?php _e('No (default)', 'ninjafirewall') ?></label></p>
		</td>
		<td style="vertical-align:top;">
			<select id="rp_select" name="nfw_options[referrer_policy]"<?php disabled($nfw_options['referrer_policy_enabled'], 0) ?>>
				<option value="1"<?php selected($nfw_options['response_headers'][8], 1) ?>>no-referrer</option>
				<option value="2"<?php selected($nfw_options['response_headers'][8], 2) ?>>no-referrer-when-downgrade</option>
				<option value="3"<?php selected($nfw_options['response_headers'][8], 3) ?>>origin</option>
				<option value="4"<?php selected($nfw_options['response_headers'][8], 4) ?>>origin-when-cross-origin</option>
				<option value="5"<?php selected($nfw_options['response_headers'][8], 5) ?>>strict-origin</option>
				<option value="6"<?php selected($nfw_options['response_headers'][8], 6) ?>>strict-origin-when-cross-origin</option>
				<option value="7"<?php selected($nfw_options['response_headers'][8], 7) ?>>same-origin</option>
				<option value="8"<?php selected($nfw_options['response_headers'][8], 8) ?>>unsafe-url</option>
			</select>
		</td>
	</tr>
</table>

<br /><br />

<?php
if ( empty( $nfw_rules[NFW_WRAPPERS]['ena']) ) {
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
		<th scope="row"><?php _e('Block PHP built-in wrappers in <code>GET</code>, <code>POST</code>, <code>HTTP_USER_AGENT</code>, <code>HTTP_REFERER</code> and cookies', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top">
			<label><input type="radio" name="nfw_rules[php_wrappers]" value="1"<?php checked( $php_wrappers, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td style="vertical-align:top">
			<label><input type="radio" name="nfw_rules[php_wrappers]" value="0"<?php checked( $php_wrappers, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>

	<?php
	if (! empty( $nfw_rules[NFW_OBJECTS]['ena'] ) ) {
		if ( strpos( $nfw_rules[NFW_OBJECTS]['cha'][1]['whe'], 'GET' )  !== FALSE) {
			$NFW_OBJECTS_GET = ' checked="checked"';
		} else {
			$NFW_OBJECTS_GET = '';
		}
		if ( strpos( $nfw_rules[NFW_OBJECTS]['cha'][1]['whe'], 'POST' )  !== FALSE) {
			$NFW_OBJECTS_POST = ' checked="checked"';
		} else {
			$NFW_OBJECTS_POST = '';
		}
		if ( strpos( $nfw_rules[NFW_OBJECTS]['cha'][1]['whe'], 'COOKIE' )  !== FALSE) {
			$NFW_OBJECTS_COOKIE = ' checked="checked"';
		} else {
			$NFW_OBJECTS_COOKIE = '';
		}
		if ( strpos( $nfw_rules[NFW_OBJECTS]['cha'][1]['whe'], 'HTTP_USER_AGENT' )  !== FALSE) {
			$NFW_OBJECTS_HTTP_USER_AGENT = ' checked="checked"';
		} else {
			$NFW_OBJECTS_HTTP_USER_AGENT = '';
		}
		if ( strpos( $nfw_rules[NFW_OBJECTS]['cha'][1]['whe'], 'HTTP_REFERER' )  !== FALSE) {
			$NFW_OBJECTS_HTTP_REFERER = ' checked="checked"';
		} else {
			$NFW_OBJECTS_HTTP_REFERER = '';
		}
	} else {
		$NFW_OBJECTS_GET = ''; $NFW_OBJECTS_POST = ''; $NFW_OBJECTS_COOKIE = '';
		$NFW_OBJECTS_HTTP_USER_AGENT = ''; $NFW_OBJECTS_HTTP_REFERER = '';
	}
	?>
	<tr>
		<th scope="row"><?php _e('Block serialized PHP objects in the following global variables', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120" style="vertical-align:top">
			<p><label><input type="checkbox" name="nfw_rules[php_objects_get]" value="1"<?php echo $NFW_OBJECTS_GET ?>><code>GET</code><?php echo $default ?></label><p>
			<p><label><input type="checkbox" name="nfw_rules[php_objects_post]" value="1"<?php echo $NFW_OBJECTS_POST ?>><code>POST</code><?php echo $default ?></label><p>
			<p><label><input type="checkbox" name="nfw_rules[php_objects_cookie]" value="1"<?php echo $NFW_OBJECTS_COOKIE ?>><code>COOKIE</code></label><p>
		</td>
		<td style="vertical-align:top">
			<p><label><input type="checkbox" name="nfw_rules[php_objects_http_user_agent]" value="1"<?php echo $NFW_OBJECTS_HTTP_USER_AGENT ?>><code>HTTP_USER_AGENT</code><?php echo $default ?></label><p>
			<p><label><input type="checkbox" name="nfw_rules[php_objects_http_referer]" value="1"<?php echo $NFW_OBJECTS_HTTP_REFERER ?>><code>HTTP_REFERER</code><?php echo $default ?></label><p>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Hide PHP notice and error messages', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[php_errors]" value="1"<?php checked( $php_errors, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[php_errors]" value="0"<?php checked( $php_errors, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Sanitise <code>PHP_SELF</code>', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[php_self]" value="1"<?php checked( $php_self, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[php_self]" value="0"<?php checked( $php_self, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Sanitise <code>PATH_TRANSLATED</code>', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[php_path_t]" value="1"<?php checked( $php_path_t, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[php_path_t]" value="0"<?php checked( $php_path_t, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Sanitise <code>PATH_INFO</code>', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_options[php_path_i]" value="1"<?php checked( $php_path_i, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_options[php_path_i]" value="0"<?php checked( $php_path_i, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
</table>

<br /><br />

<?php

if ( strlen( $_SERVER['DOCUMENT_ROOT'] ) < 5 ) {
	$nfw_rules[NFW_DOC_ROOT]['ena'] = 0;
	$greyed = 'style="color:#bbbbbb"';
	$disabled = 'disabled ';
	$disabled_msg = '<br /><span class="description">&nbsp;' .
						__('This option is not compatible with your actual configuration.', 'ninjafirewall') .
						'</span>';
} else {
	$greyed = '';
	$disabled = '';
	$disabled_msg = '';
}

if ( empty( $nfw_rules[NFW_DOC_ROOT]['ena']) ) {
	$block_doc_root = 0;
} else {
	$block_doc_root = 1;
}
if ( empty( $nfw_rules[NFW_NULL_BYTE]['ena']) ) {
	$block_null_byte = 0;
} else {
	$block_null_byte = 1;
}
if ( empty( $nfw_rules[NFW_ASCII_CTRL]['ena']) ) {
	$block_ctrl_chars = 0;
} else {
	$block_ctrl_chars = 1;
}
?>
<h3><?php _e('Various', 'ninjafirewall') ?></h3>
<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e('Block the <code>DOCUMENT_ROOT</code> server variable in HTTP request', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label <?php echo $greyed ?>><input type="radio" name="nfw_rules[block_doc_root]" value="1"<?php checked( $block_doc_root, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label <?php echo $greyed ?>><input <?php echo $disabled ?>type="radio" name="nfw_rules[block_doc_root]" value="0"<?php checked( $block_doc_root, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label><?php echo $disabled_msg ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Block ASCII character 0x00 (NULL byte)', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td width="120">
			<label><input type="radio" name="nfw_rules[block_null_byte]" value="1"<?php checked( $block_null_byte, 1 ) ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_rules[block_null_byte]" value="0"<?php checked( $block_null_byte, 0 ) ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Block ASCII control characters 1 to 8 and 14 to 31', 'ninjafirewall') ?></th>
		<td width="20">&nbsp;</td>
		<td>
			<label><input type="radio" name="nfw_rules[block_ctrl_chars]" value="1"<?php checked( $block_ctrl_chars, 1 ) ?>>&nbsp;<?php _e('Yes', 'ninjafirewall') ?></label>
		</td>
		<td>
			<label><input type="radio" name="nfw_rules[block_ctrl_chars]" value="0"<?php checked( $block_ctrl_chars, 0 ) ?>>&nbsp;<?php _e('No (default)', 'ninjafirewall') ?></label>
		</td>
	</tr>
</table>

</div>

<br />
<br />

<input class="button-primary" type="submit" name="Save" value="<?php _e('Save Firewall Policies', 'ninjafirewall') ?>" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input class="button-secondary" type="submit" name="Default" value="<?php _e('Restore Default Values', 'ninjafirewall') ?>" onclick="return restore();" />
</form>
</div>

<?php

// ---------------------------------------------------------------------
// Save firewall policies.

function nf_sub_policies_save() {

	nf_not_allowed( 'block', __LINE__ );

	$nfw_options = nfw_get_option( 'nfw_options' );
	$nfw_rules = nfw_get_option( 'nfw_rules' );

	if ( (isset( $_POST['nfw_options']['scan_protocol'])) &&
		( preg_match( '/^[123]$/', $_POST['nfw_options']['scan_protocol'])) ) {
			$nfw_options['scan_protocol'] = $_POST['nfw_options']['scan_protocol'];
	} else {
		$nfw_options['scan_protocol'] = 3;
	}

	if ( empty( $_POST['nfw_options']['uploads']) ) {
		$nfw_options['uploads'] = 0;
	} else {
		$nfw_options['uploads'] = 1;
	}

	if ( (isset( $_POST['nfw_options']['sanitise_fn']) ) && ( $nfw_options['uploads'] == 1) ) {
		$nfw_options['sanitise_fn'] = 1;
	} else {
		$nfw_options['sanitise_fn'] = 0;
	}
	// Substitution character:
	// Don't allow the '/' character:
	if ( empty( $_POST['nfw_options']['substitute'] ) || strlen( $_POST['nfw_options']['substitute'] ) > 1 || $_POST['nfw_options']['substitute'] == '/' ) {
		$nfw_options['substitute'] = 'X';
	} else {
		$nfw_options['substitute'] = $_POST['nfw_options']['substitute'];
	}


	if ( empty( $_POST['nfw_options']['get_scan']) ) {
		$nfw_options['get_scan'] = 0;
	} else {
		$nfw_options['get_scan'] = 1;
	}
	if ( empty( $_POST['nfw_options']['get_sanitise']) ) {
		$nfw_options['get_sanitise'] = 0;
	} else {
		$nfw_options['get_sanitise'] = 1;
	}


	if ( empty( $_POST['nfw_options']['post_scan']) ) {
		$nfw_options['post_scan'] = 0;
	} else {
		$nfw_options['post_scan'] = 1;
	}
	if ( empty( $_POST['nfw_options']['post_sanitise']) ) {
		$nfw_options['post_sanitise'] = 0;
	} else {
		$nfw_options['post_sanitise'] = 1;
	}
	if ( empty( $_POST['nfw_options']['post_b64']) ) {
		$nfw_options['post_b64'] = 0;
	} else {
		$nfw_options['post_b64'] = 1;
	}


	if ( empty( $_POST['nfw_options']['request_sanitise']) ) {
		$nfw_options['request_sanitise'] = 0;
	} else {
		$nfw_options['request_sanitise'] = 1;
	}


	if ( function_exists('header_register_callback') && function_exists('headers_list') && function_exists('header_remove') ) {
		$nfw_options['response_headers'] = '000000000';
		$nfw_options['csp_frontend_data'] = '';
		$nfw_options['csp_backend_data'] = '';
		if ( empty( $_POST['nfw_options']['x_content_type_options']) ) {
			$nfw_options['response_headers'][1] = 0;
		} else {
			$nfw_options['response_headers'][1] = 1;
		}
		if ( empty( $_POST['nfw_options']['x_frame_options']) ) {
			$nfw_options['response_headers'][2] = 0;
		} elseif ( $_POST['nfw_options']['x_frame_options'] == 1) {
			$nfw_options['response_headers'][2] = 1;
		} else {
			$nfw_options['response_headers'][2] = 2;
		}
		// XSS filter:
		// 	0 = 0
		// 	1 = 1; mode=block
		// 	2 = 1
		// 	3 = unset
		if ( empty( $_POST['nfw_options']['x_xss_protection'] ) ) {
			$nfw_options['response_headers'][3] = 0;
		} elseif ( $_POST['nfw_options']['x_xss_protection'] == 1 ) {
			$nfw_options['response_headers'][3] = 1;
		} elseif ( $_POST['nfw_options']['x_xss_protection'] == 2 ) {
			$nfw_options['response_headers'][3] = 2;
		} else {
			$nfw_options['response_headers'][3] = 3;
		}

		if ( empty( $_POST['nfw_options']['cookies_httponly']) ) {
			$nfw_options['response_headers'][0] = 0;
		} else {
			$nfw_options['response_headers'][0] = 1;
		}
		if (! isset( $_POST['nfw_options']['strict_transport_sub']) ) {
			$nfw_options['response_headers'][5] = 0;
		} else {
			$nfw_options['response_headers'][5] = 1;
		}
		if ( empty( $_POST['nfw_options']['strict_transport']) ) {
			$nfw_options['response_headers'][4] = 0;
			$nfw_options['response_headers'][5] = 0;
		} elseif ( $_POST['nfw_options']['strict_transport'] == 1) {
			$nfw_options['response_headers'][4] = 1;
		} elseif ( $_POST['nfw_options']['strict_transport'] == 2) {
			$nfw_options['response_headers'][4] = 2;
		} elseif ( $_POST['nfw_options']['strict_transport'] == 3) {
			$nfw_options['response_headers'][4] = 3;
		} else {
			$nfw_options['response_headers'][4] = 4;
		}
		$nfw_options['csp_frontend_data'] = stripslashes( str_replace( array( '<', '>', "\x0a", "\x0d", '%', '$', '&') , '', $_POST['nfw_options']['csp_frontend_data'] ) );
		if ( empty( $_POST['nfw_options']['csp_frontend']) || empty( $nfw_options['csp_frontend_data'] ) ) {
			$nfw_options['response_headers'][6] = 0;
		} else {
			$nfw_options['response_headers'][6] = 1;
		}
		$nfw_options['csp_backend_data'] = stripslashes( str_replace( array( '<', '>', "\x0a", "\x0d", '%', '$', '&') , '', $_POST['nfw_options']['csp_backend_data'] ) );
		if ( empty( $_POST['nfw_options']['csp_backend']) || empty( $nfw_options['csp_backend_data'] ) ) {
			$nfw_options['response_headers'][7] = 0;
		} else {
			$nfw_options['response_headers'][7] = 1;
		}
		if ( empty( $_POST['nfw_options']['referrer_policy_enabled'] ) ) {
			$nfw_options['referrer_policy_enabled'] = 0;
			$_POST['nfw_options']['referrer_policy'] = 0;
		} else {
			$nfw_options['referrer_policy_enabled'] = 1;
		}

		if ( empty( $_POST['nfw_options']['referrer_policy'] ) || ! preg_match('/^[1-8]$/', $_POST['nfw_options']['referrer_policy'] ) ) {
			$nfw_options['response_headers'][8] = 0;
			$nfw_options['referrer_policy_enabled'] = 0;
		} else {
			$nfw_options['response_headers'][8] = (int)$_POST['nfw_options']['referrer_policy'];
		}
	}


	if ( empty( $_POST['nfw_options']['cookies_scan']) ) {
		$nfw_options['cookies_scan'] = 0;
	} else {
		$nfw_options['cookies_scan'] = 1;
	}
	if ( empty( $_POST['nfw_options']['cookies_sanitise']) ) {
		$nfw_options['cookies_sanitise'] = 0;
	} else {
		$nfw_options['cookies_sanitise'] = 1;
	}


	if ( empty( $_POST['nfw_options']['ua_scan']) ) {
		$nfw_options['ua_scan'] = 0;
	} else {
		$nfw_options['ua_scan'] = 1;
	}
	if ( empty( $_POST['nfw_options']['ua_sanitise']) ) {
		$nfw_options['ua_sanitise'] = 0;
	} else {
		$nfw_options['ua_sanitise'] = 1;
	}


	if ( empty( $_POST['nfw_options']['referer_scan']) ) {
		$nfw_options['referer_scan'] = 0;
	} else {
		$nfw_options['referer_scan'] = 1;
	}
	if ( empty( $_POST['nfw_options']['referer_sanitise']) ) {
		$nfw_options['referer_sanitise'] = 0;
	} else {
		$nfw_options['referer_sanitise'] = 1;
	}
	if ( empty( $_POST['nfw_options']['referer_post']) ) {
		$nfw_options['referer_post'] = 0;
	} else {
		$nfw_options['referer_post'] = 1;
	}


	if ( empty( $_POST['nfw_options']['no_host_ip']) ) {
		$nfw_options['no_host_ip'] = 0;
	} else {
		$nfw_options['no_host_ip'] = 1;
	}
	if ( empty( $_POST['nfw_options']['allow_local_ip']) ) {
		$nfw_options['allow_local_ip'] = 0;
	} else {
		$nfw_options['allow_local_ip'] = 1;
	}


	if ( empty( $_POST['nfw_options']['php_errors']) ) {
		$nfw_options['php_errors'] = 0;
	} else {
		$nfw_options['php_errors'] = 1;
	}

	if ( empty( $_POST['nfw_options']['php_self']) ) {
		$nfw_options['php_self'] = 0;
	} else {
		$nfw_options['php_self'] = 1;
	}
	if ( empty( $_POST['nfw_options']['php_path_t']) ) {
		$nfw_options['php_path_t'] = 0;
	} else {
		$nfw_options['php_path_t'] = 1;
	}
	if ( empty( $_POST['nfw_options']['php_path_i']) ) {
		$nfw_options['php_path_i'] = 0;
	} else {
		$nfw_options['php_path_i'] = 1;
	}

	$nfw_options['wp_dir'] = $tmp = '';
	if ( isset( $_POST['nfw_options']['wp_admin']) ) {
		$tmp .= '/wp-admin/(?:css|images|includes|js)/|';
	}
	if ( isset( $_POST['nfw_options']['wp_inc']) ) {
		$tmp .= '/wp-includes/(?:(?:css|images|js(?!/tinymce/wp-tinymce\.php)|theme-compat)/|[^/]+\.php)|';
	}
	if ( isset( $_POST['nfw_options']['wp_upl']) ) {
		$tmp .= '/' . basename(WP_CONTENT_DIR) .'/(?:uploads|blogs\.dir)/|';
	}
	if ( isset( $_POST['nfw_options']['wp_cache']) ) {
		$tmp .= '/cache/|';
	}
	if ( $tmp ) {
		$nfw_options['wp_dir'] = rtrim( $tmp, '|' );
	}

	if (! isset( $_POST['nfw_options']['disallow_creation']) ) {
		$nfw_options['disallow_creation'] = 0;
	} else {
		$nfw_options['disallow_creation'] = 1;
	}
	if (! isset( $_POST['nfw_options']['disallow_settings']) ) {
		$nfw_options['disallow_settings'] = 0;
	} else {
		$nfw_options['disallow_settings'] = 1;
	}

	if (! isset( $_POST['nfw_options']['enum_archives']) ) {
		$nfw_options['enum_archives'] = 0;
	} else {
		$nfw_options['enum_archives'] = 1;
	}
	if (! isset( $_POST['nfw_options']['enum_login']) ) {
		$nfw_options['enum_login'] = 0;
	} else {
		$nfw_options['enum_login'] = 1;
	}
	if (! isset( $_POST['nfw_options']['enum_restapi']) ) {
		$nfw_options['enum_restapi'] = 0;
	} else {
		$nfw_options['enum_restapi'] = 1;
	}
	if (! isset( $_POST['nfw_options']['no_restapi']) ) {
		$nfw_options['no_restapi'] = 0;
	} else {
		$nfw_options['no_restapi'] = 1;
	}


	if ( empty( $_POST['nfw_options']['no_xmlrpc']) ) {
		$nfw_options['no_xmlrpc'] = 0;
	} else {
		$nfw_options['no_xmlrpc'] = 1;
		$_POST['nfw_options']['no_xmlrpc_multi'] = 0;
		$_POST['nfw_options']['no_xmlrpc_pingback'] = 0;
	}
	if ( empty( $_POST['nfw_options']['no_xmlrpc_multi']) ) {
		$nfw_options['no_xmlrpc_multi'] = 0;
	} else {
		$nfw_options['no_xmlrpc_multi'] = 1;
	}
	if ( empty( $_POST['nfw_options']['no_xmlrpc_pingback']) ) {
		$nfw_options['no_xmlrpc_pingback'] = 0;
	} else {
		$nfw_options['no_xmlrpc_pingback'] = 1;
	}

	if ( empty( $_POST['nfw_options']['no_post_themes']) ) {
		$nfw_options['no_post_themes'] = 0;
	} else {
		$nfw_options['no_post_themes'] = '/'. basename(WP_CONTENT_DIR) .'/themes/';
	}

	if ( empty( $_POST['nfw_options']['force_ssl']) ) {
		$nfw_options['force_ssl'] = 0;
	} else {
		$nfw_options['force_ssl'] = 1;
	}

	if ( empty( $_POST['nfw_options']['disallow_edit']) ) {
		$nfw_options['disallow_edit'] = 0;
	} else {
		$nfw_options['disallow_edit'] = 1;
	}

	if ( empty( $_POST['nfw_options']['disallow_mods']) ) {
		$nfw_options['disallow_mods'] = 0;
	} else {
		$nfw_options['disallow_mods'] = 1;
	}


	if ( empty( $_POST['nfw_options']['wl_admin']) ) {
		$nfw_options['wl_admin'] = 0;
		if ( isset( $_SESSION['nfw_goodguy']) ) {
			unset( $_SESSION['nfw_goodguy']);
		}
	} else {
		if ( $_POST['nfw_options']['wl_admin'] == 2 ) {
			$nfw_options['wl_admin'] = 2;
		} else {
			$nfw_options['wl_admin'] = 1;
		}
		$_SESSION['nfw_goodguy'] = $nfw_options['wl_admin'];
	}


	if ( empty( $_POST['nfw_rules']['block_null_byte']) ) {
		$nfw_rules[NFW_NULL_BYTE]['ena'] = 0;
	} else {
		$nfw_rules[NFW_NULL_BYTE]['ena'] = 1;
	}
	if ( empty( $_POST['nfw_rules']['block_bots']) ) {
		$nfw_rules[NFW_SCAN_BOTS]['ena'] = 0;
	} else {
		$nfw_rules[NFW_SCAN_BOTS]['ena'] = 1;
	}
	if ( empty( $_POST['nfw_rules']['block_ctrl_chars']) ) {
		$nfw_rules[NFW_ASCII_CTRL]['ena'] = 0;
	} else {
		$nfw_rules[NFW_ASCII_CTRL]['ena'] = 1;
	}


	if ( empty( $_POST['nfw_rules']['block_doc_root']) ) {
		$nfw_rules[NFW_DOC_ROOT]['ena'] = 0;
	} else {

		if ( strlen( $_SERVER['DOCUMENT_ROOT'] ) > 5 ) {
			$nfw_rules[NFW_DOC_ROOT]['cha'][1]['wha'] = str_replace( '/', '/[./]*', $_SERVER['DOCUMENT_ROOT'] );
			$nfw_rules[NFW_DOC_ROOT]['ena']	= 1;
		} elseif ( strlen( getenv( 'DOCUMENT_ROOT' ) ) > 5 ) {
			$nfw_rules[NFW_DOC_ROOT]['cha'][1]['wha'] = str_replace( '/', '/[./]*', getenv( 'DOCUMENT_ROOT' ) );
			$nfw_rules[NFW_DOC_ROOT]['ena']	= 1;
		} else {
			$nfw_rules[NFW_DOC_ROOT]['ena']	= 0;
		}
	}


	if ( empty( $_POST['nfw_rules']['php_wrappers']) ) {
		$nfw_rules[NFW_WRAPPERS]['ena'] = 0;
	} else {
		$nfw_rules[NFW_WRAPPERS]['ena'] = 1;
	}


	$nfw_objects = '';
	if (! empty( $_POST['nfw_rules']['php_objects_get'] ) ) {
		$nfw_objects .= "GET|";
	}
	if (! empty( $_POST['nfw_rules']['php_objects_post'] ) ) {
		$nfw_objects .= "POST|";
	}
	if (! empty( $_POST['nfw_rules']['php_objects_cookie'] ) ) {
		$nfw_objects .= "COOKIE|";
	}
	if (! empty( $_POST['nfw_rules']['php_objects_http_user_agent'] ) ) {
		$nfw_objects .= "SERVER:HTTP_USER_AGENT|";
	}
	if (! empty( $_POST['nfw_rules']['php_objects_http_referer'] ) ) {
		$nfw_objects .= "SERVER:HTTP_REFERER|";
	}
	if (! empty( $nfw_objects ) ) {
		$nfw_objects = rtrim( $nfw_objects, '|' );
		$nfw_rules[NFW_OBJECTS]['ena'] = 1;
	} else {
		// Disable rule:
		$nfw_rules[NFW_OBJECTS]['ena'] = 0;
	}
	$nfw_rules[NFW_OBJECTS]['cha'][1]['whe'] = $nfw_objects;


	if ( empty( $_POST['nfw_rules']['no_localhost_ip']) ) {
		$nfw_rules[NFW_LOOPBACK]['ena'] = 0;
	} else {
		$nfw_rules[NFW_LOOPBACK]['ena'] = 1;
	}

	nfw_update_option( 'nfw_options', $nfw_options );
	nfw_update_option( 'nfw_rules', $nfw_rules );

}

// ---------------------------------------------------------------------

function nf_sub_policies_csp() {
	return "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.videopress.com *.google.com *.wp.com; style-src 'self' 'unsafe-inline' *.googleapis.com *.google.com *.jquery.com; connect-src 'self'; media-src 'self' *.youtube.com *.w.org; child-src 'self' *.videopress.com *.google.com; object-src 'self'; form-action 'self'; img-src 'self' *.gravatar.com *.wp.com *.w.org *.cldup.com woocommerce.com data:;";
}

// ---------------------------------------------------------------------
// Restore default firewall policies.

function nf_sub_policies_default() {

	nf_not_allowed( 'block', __LINE__ );

	$nfw_options = nfw_get_option( 'nfw_options' );
	$nfw_rules = nfw_get_option( 'nfw_rules' );

	$nfw_options['scan_protocol']		= 3;
	$nfw_options['uploads']				= 1;
	$nfw_options['sanitise_fn']		= 0;
	$nfw_options['substitute'] 		= 'X';
	$nfw_options['get_scan']			= 1;
	$nfw_options['get_sanitise']		= 0;
	$nfw_options['post_scan']			= 1;
	$nfw_options['post_sanitise']		= 0;
	$nfw_options['request_sanitise'] = 0;
	if ( function_exists('header_register_callback') && function_exists('headers_list') && function_exists('header_remove') ) {
		$nfw_options['response_headers'] = '000300000';
		$nfw_options['referrer_policy_enabled'] = 0;
		$nfw_options['csp_backend_data'] = nf_sub_policies_csp();
		$nfw_options['csp_frontend_data'] = '';
	}
	$nfw_options['cookies_scan']		= 1;
	$nfw_options['cookies_sanitise']	= 0;
	$nfw_options['ua_scan']				= 1;
	$nfw_options['ua_sanitise']		= 1;
	$nfw_options['referer_scan']		= 0;
	$nfw_options['referer_sanitise']	= 1;
	$nfw_options['referer_post']		= 0;
	$nfw_options['no_host_ip']			= 0;
	$nfw_options['allow_local_ip']	= 0;
	$nfw_options['php_errors']			= 1;
	$nfw_options['php_self']			= 1;
	$nfw_options['php_path_t']			= 1;
	$nfw_options['php_path_i']			= 1;
	$nfw_options['wp_dir'] 				= '/wp-admin/(?:css|images|includes|js)/|' .
		'/wp-includes/(?:(?:css|images|js(?!/tinymce/wp-tinymce\.php)|theme-compat)/|[^/]+\.php)|' .
		'/'. basename(WP_CONTENT_DIR) .'/(?:uploads|blogs\.dir)/';
	$nfw_options['disallow_creation']= 0;
	$nfw_options['disallow_settings']= 1;
	$nfw_options['enum_archives']		= 0;
	$nfw_options['enum_login']			= 0;
	$nfw_options['enum_restapi']		= 0;
	$nfw_options['no_restapi']			= 0;
	$nfw_options['no_xmlrpc']			= 0;
	$nfw_options['no_xmlrpc_multi']	= 0;
	$nfw_options['no_xmlrpc_pingback']= 0;
	$nfw_options['no_post_themes']	= 0;
	$nfw_options['force_ssl'] 			= 0;
	$nfw_options['disallow_edit'] 	= 0;
	$nfw_options['disallow_mods'] 	= 0;
	$nfw_options['post_b64']			= 1;
	$nfw_options['wl_admin']			= 1;
	$_SESSION['nfw_goodguy'] 			= true;

	$nfw_rules[NFW_SCAN_BOTS]['ena']	= 1;
	$nfw_rules[NFW_LOOPBACK]['ena']	= 1;
	$nfw_rules[NFW_WRAPPERS]['ena']	= 1;

	$nfw_rules[NFW_OBJECTS]['ena'] = 1;
	$nfw_rules[NFW_OBJECTS]['cha'][1]['whe'] = 'GET|POST|SERVER:HTTP_USER_AGENT|SERVER:HTTP_REFERER';

	if ( strlen( $_SERVER['DOCUMENT_ROOT'] ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['cha'][1]['wha'] = str_replace( '/', '/[./]*', $_SERVER['DOCUMENT_ROOT'] );
		$nfw_rules[NFW_DOC_ROOT]['ena'] = 1;
	} elseif ( strlen( getenv( 'DOCUMENT_ROOT' ) ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['cha'][1]['wha'] = str_replace( '/', '/[./]*', getenv( 'DOCUMENT_ROOT' ) );
		$nfw_rules[NFW_DOC_ROOT]['ena'] = 1;
	} else {
		$nfw_rules[NFW_DOC_ROOT]['ena']  = 0;
	}

	$nfw_rules[NFW_NULL_BYTE]['ena']  = 1;
	$nfw_rules[NFW_ASCII_CTRL]['ena'] = 0;

	nfw_update_option( 'nfw_options', $nfw_options);
	nfw_update_option( 'nfw_rules', $nfw_rules);

}

// ---------------------------------------------------------------------
// EOF
