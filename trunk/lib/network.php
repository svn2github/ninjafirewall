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

if (! current_user_can( 'manage_network' ) ) {
	die( '<br /><br /><br /><div class="error notice is-dismissible"><p>' .
		sprintf( __('You are not allowed to perform this task (%s).', 'ninjafirewall'), __LINE__) .
		'</p></div>' );
}

$nfw_options = nfw_get_option( 'nfw_options' );

echo '
<div class="wrap">
	<h1><img style="vertical-align:top;width:33px;height:33px;" src="'. plugins_url( '/ninjafirewall/images/ninjafirewall_32.png' ) .'">&nbsp;' . __('Network', 'ninjafirewall') . '</h1>';

if (! is_multisite() ) {
	echo '<div class="updated notice is-dismissible"><p>' . __('You do not have a multisite network.', 'ninjafirewall') . '</p></div></div>';
	return;
}

if ( isset( $_POST['nfw_options']) ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'network_save') ) {
		wp_nonce_ays('network_save');
	}
	if ( $_POST['nfw_options']['nt_show_status'] == 2 ) {
		$nfw_options['nt_show_status'] = 2;
	} else {
		$nfw_options['nt_show_status'] = 1;
	}
	nfw_update_option( 'nfw_options', $nfw_options );
	echo '<div class="updated notice is-dismissible"><p>' . __('Your changes have been saved.', 'ninjafirewall') . '</p></div>';
	$nfw_options = nfw_get_option( 'nfw_options' );
}

if ( empty($nfw_options['nt_show_status']) ) {
	$nfw_options['nt_show_status'] = 1;
}
?>
<form method="post" name="nfwnetwork">
<?php wp_nonce_field('network_save', 'nfwnonce', 0); ?>
<h3><?php _e('NinjaFirewall Status', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Display NinjaFirewall status icon in the admin bar of all sites in the network', 'ninjafirewall') ?></th>
			<td width="200"><label><input type="radio" name="nfw_options[nt_show_status]" value="1"<?php echo $nfw_options['nt_show_status'] != 2 ? ' checked' : '' ?>>&nbsp;<?php _e('Yes (default)', 'ninjafirewall') ?></label></td>
			<td><label><input type="radio" name="nfw_options[nt_show_status]" value="2"<?php echo $nfw_options['nt_show_status'] == 2 ? ' checked' : '' ?>>&nbsp;<?php _e('No', 'ninjafirewall') ?></label></td>
		</tr>
	</table>

	<br />
	<br />
	<input class="button-primary" type="submit" name="Save" value="<?php _e('Save Network options', 'ninjafirewall') ?>" />
</form>
</div>
<?php
// ---------------------------------------------------------------------
// EOF
