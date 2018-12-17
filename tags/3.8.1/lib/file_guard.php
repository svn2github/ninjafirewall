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

nf_not_allowed( 'block', __LINE__ );

$nfw_options = nfw_get_option( 'nfw_options' );

?>
<script>
function toggle_table(off) {
	if ( off == 1 ) {
		jQuery("#fg_table").slideDown();
	} else if ( off == 2 ) {
		jQuery("#fg_table").slideUp();
	}
	return;
}
function is_number(id) {
	var e = document.getElementById(id);
	if (! e.value ) { return }
	if (! /^[1-9][0-9]?$/.test(e.value) ) {
		alert("<?php echo esc_js( __('Please enter a number from 1 to 99.', 'ninjafirewall') ) ?>");
		e.value = e.value.substring(0, e.value.length-1);
	}
}
function check_fields() {
	if (! document.nfwfilefuard.elements["nfw_options[fg_mtime]"]){
		alert("<?php echo esc_js( __('Please enter a number from 1 to 99.', 'ninjafirewall') ) ?>");
		return false;
	}
	return true;
}
</script>

<div class="wrap">
	<h1><img style="vertical-align:top;width:33px;height:33px;" src="<?php echo plugins_url( '/ninjafirewall/images/ninjafirewall_32.png' ) ?>">&nbsp;<?php _e('File Guard', 'ninjafirewall') ?></h1>
<?php
if ( defined('NFW_WPWAF') ) {
	?>
	<div class="notice-warning notice is-dismissible"><p><?php printf( __('You are running NinjaFirewall in <i>WordPress WAF</i> mode. The %s feature will be limited to a few WordPress files only (e.g., index.php, wp-login.php, xmlrpc.php, admin-ajax.php, wp-load.php etc). If you want it to apply to any PHP script, you will need to run NinjaFirewall in %s mode.', 'ninjafirewall'), 'File Guard', '<a href="https://blog.nintechnet.com/full_waf-vs-wordpress_waf/">Full WAF</a>') ?></p></div>
	<?php
}

if (! is_writable( NFW_LOG_DIR . '/nfwlog/cache/') ) {
	echo '<div class="error notice is-dismissible"><p>' .
		sprintf( __('The cache directory %s is not writable. Please change its permissions (0777 or equivalent).', 'ninjafirewall'), '('. htmlspecialchars(NFW_LOG_DIR) . '/nfwlog/cache/)' ) . '</p></div>';
}

if ( isset( $_POST['nfw_options']) ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'fileguard_save') ) {
		wp_nonce_ays('fileguard_save');
	}
	nf_sub_fileguard_save();
	$nfw_options = nfw_get_option( 'nfw_options' );
	echo '<div class="updated notice is-dismissible"><p>' . __('Your changes have been saved.', 'ninjafirewall') .'</p></div>';
}

if ( empty($nfw_options['fg_enable']) ) {
	$nfw_options['fg_enable'] = 0;
} else {
	$nfw_options['fg_enable'] = 1;
}
if ( empty($nfw_options['fg_mtime']) || ! preg_match('/^[1-9][0-9]?$/', $nfw_options['fg_mtime']) ) {
	$nfw_options['fg_mtime'] = 10;
}
if ( empty($nfw_options['fg_exclude']) ) {
	$fg_exclude = '';
} else {
	$tmp = str_replace('|', ',', $nfw_options['fg_exclude']);
	$fg_exclude = preg_replace( '/\\\([`.\\/\\\+*?\[^\]$(){}=!<>:-])/', '$1', $tmp );
}
?>
<br />
<form method="post" name="nfwfilefuard" onSubmit="return check_fields();">
	<?php wp_nonce_field('fileguard_save', 'nfwnonce', 0); ?>
	<table class="form-table">
		<tr style="background-color:#F9F9F9;border: solid 1px #DFDFDF;">
			<th scope="row"><?php _e('Enable File Guard', 'ninjafirewall') ?></th>
			<td>
			<label><input type="radio" id="fgenable" name="nfw_options[fg_enable]" value="1"<?php checked($nfw_options['fg_enable'], 1) ?> onclick="toggle_table(1);">&nbsp;<?php _e('Yes (recommended)', 'ninjafirewall') ?></label>
			</td>
			<td>
			<label><input type="radio" name="nfw_options[fg_enable]" value="0"<?php checked($nfw_options['fg_enable'], 0) ?> onclick="toggle_table(2);">&nbsp;<?php _e('No', 'ninjafirewall') ?></label>
			</td>
		</tr>
	</table>

	<br />

	<div id="fg_table"<?php echo $nfw_options['fg_enable'] == 1 ? '' : ' style="display:none"' ?>>
		<table class="form-table" border="0">
			<tr valign="top">
				<th scope="row"><?php _e('Real-time detection', 'ninjafirewall') ?></th>
				<td>
				<?php
					printf( __('Monitor file activity and send an alert when someone is accessing a PHP script that was modified or created less than %s hour(s) ago.', 'ninjafirewall'), '<input maxlength="2" size="2" value="'. $nfw_options['fg_mtime'] .'" name="nfw_options[fg_mtime]" id="mtime" onkeyup="is_number(\'mtime\')" class="small-text" type="number" />');
				?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Exclude the following files/folders (optional)', 'ninjafirewall') ?></th>
				<td><input class="large-text" type="text" maxlength="255" name="nfw_options[fg_exclude]" value="<?php echo htmlspecialchars( $fg_exclude ); ?>" placeholder="<?php _e('e.g.,', 'ninjafirewall') ?> /foo/bar/cache/ <?php _e('or', 'ninjafirewall') ?> /cache/" /><br /><span class="description"><?php _e('Full or partial case-sensitive string(s), max. 255 characters. Multiple values must be comma-separated', 'ninjafirewall') ?> (<code>,</code>).</span></td>
			</tr>
		</table>
	</div>
	<br />
	<input class="button-primary" type="submit" name="Save" value="<?php _e('Save File Guard options', 'ninjafirewall') ?>" />
</form>
</div>
<?php

// ---------------------------------------------------------------------

function nf_sub_fileguard_save() {

	nf_not_allowed( 'block', __LINE__ );

	$nfw_options = nfw_get_option( 'nfw_options' );

	if ( empty($_POST['nfw_options']['fg_enable']) ) {
		$nfw_options['fg_enable'] = 0;
	} else {
		$nfw_options['fg_enable'] = $_POST['nfw_options']['fg_enable'];
	}

	if ( empty($_POST['nfw_options']['fg_mtime']) || ! preg_match('/^[1-9][0-9]?$/', $_POST['nfw_options']['fg_mtime']) ) {
		$nfw_options['fg_mtime'] = 10;
	} else {
		$nfw_options['fg_mtime'] = $_POST['nfw_options']['fg_mtime'];
	}

	if ( empty($_POST['nfw_options']['fg_exclude']) || strlen($_POST['nfw_options']['fg_exclude']) > 255 ) {
		$nfw_options['fg_exclude'] = '';
	} else {
		$exclude = '';
		$fg_exclude =  explode(',', $_POST['nfw_options']['fg_exclude'] );
		foreach ($fg_exclude as $path) {
			if ( $path ) {
				$path = str_replace( array(' ', '\\', '|'), '', $path);
				$exclude .= preg_quote( rtrim($path, ','), '`') . '|';
			}
		}
		$nfw_options['fg_exclude'] = rtrim($exclude, '|');
	}

	nfw_update_option( 'nfw_options', $nfw_options );

}

// ---------------------------------------------------------------------
// EOF
