<?php
/*
 +=====================================================================+
 | NinjaFirewall (WP+ Edition)                                         |
 |                                                                     |
 | (c) NinTechNet - https://nintechnet.com/                            |
 +=====================================================================+ i18n+ / sa
*/

if (! defined( 'NFW_ENGINE_VERSION' ) ) { die( 'Forbidden' ); }

// Block immediately if user is not allowed :
nf_not_allowed( 'block', __LINE__ );

echo '
<div class="wrap">
	<h1><img style="vertical-align:top;width:33px;height:33px;" src="'. plugins_url( '/ninjafirewall/images/ninjafirewall_32.png' ) .'">&nbsp;' . __('Rules Editor', 'ninjafirewall') . '</h1>';

$nfw_rules = nfw_get_option( 'nfw_rules' );
$is_update = 0;

if ( isset($_POST['sel_e_r']) ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'editor_save') ) {
		wp_nonce_ays('editor_save');
	}
	if ( $_POST['sel_e_r'] < 1 ) {
		echo '<div class="error notice is-dismissible"><p>' . __('Error: you did not select a rule to disable.', 'ninjafirewall') .'</p></div>';
	} else if ( ( $_POST['sel_e_r'] == 2 ) || ( $_POST['sel_e_r'] > 499 ) && ( $_POST['sel_e_r'] < 600 ) ) {
		echo '<div class="error notice is-dismissible"><p>' . __('Error: to change this rule, use the "Firewall Policies" menu.', 'ninjafirewall') .'</p></div>';
	} else if (! isset( $nfw_rules[$_POST['sel_e_r']] ) ) {
		echo '<div class="error notice is-dismissible"><p>' . __('Error: this rule does not exist.', 'ninjafirewall') .'</p></div>';
	} elseif ($_POST['sel_e_r'] != 999) {
		$nfw_rules[$_POST['sel_e_r']]['ena'] = 0;
		$is_update = 1;
		echo '<div class="updated notice is-dismissible"><p>' . sprintf( __('Rule ID %s has been disabled.', 'ninjafirewall'), htmlentities($_POST['sel_e_r']) ) .'</p></div>';
	}
} else if ( isset($_POST['sel_d_r']) ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'editor_save') ) {
		wp_nonce_ays('editor_save');
	}
	if ( $_POST['sel_d_r'] < 1 ) {
		echo '<div class="error notice is-dismissible"><p>' . __('Error: you did not select a rule to enable.', 'ninjafirewall') .'</p></div>';
	} else if ( ( $_POST['sel_d_r'] == 2 ) || ( $_POST['sel_d_r'] > 499 ) && ( $_POST['sel_d_r'] < 600 ) ) {
		echo '<div class="error notice is-dismissible"><p>' . __('Error: to change this rule, use the "Firewall Policies" menu.', 'ninjafirewall') .'</p></div>';
	} else if (! isset( $nfw_rules[$_POST['sel_d_r']] ) ) {
		echo '<div class="error notice is-dismissible"><p>' . __('Error: this rule does not exist.', 'ninjafirewall') .'</p></div>';
	} elseif ($_POST['sel_d_r'] != 999) {
		$nfw_rules[$_POST['sel_d_r']]['ena'] = 1;
		$is_update = 1;
		echo '<div class="updated notice is-dismissible"><p>' . sprintf( __('Rule ID %s has been enabled.', 'ninjafirewall'), htmlentities($_POST['sel_d_r']) ) .'</p></div>';
	}
}
if ( $is_update ) {
	nfw_update_option( 'nfw_rules', $nfw_rules);
}

$disabled_rules = $enabled_rules = array();

if ( empty( $nfw_rules ) ) {
	echo '<div class="error notice is-dismissible"><p>' . __('Error: no rules found.', 'ninjafirewall') .'</p></div></div>';
	return;
}

foreach ( $nfw_rules as $rule_key => $rule_value ) {
	if ( $rule_key == 999 ) { continue; }

	// Ingore firewall policies:
	if ( $rule_key == 2 || $rule_key > 499 && $rule_key < 600 ) {
		continue;
	}


	if (! empty( $nfw_rules[$rule_key]['ena'] ) ) {
		$enabled_rules[] =  $rule_key;
	} else {
		$disabled_rules[] = $rule_key;
	}
}

$nonce = wp_nonce_field('editor_save', 'nfwnonce', 0, 0);

echo '<br /><h3>' . __('NinjaFirewall built-in security rules', 'ninjafirewall') .'</h3>
	<table class="form-table">
		<tr>
			<th scope="row">' . __('Select the rule you want to disable or enable', 'ninjafirewall') .'</th>
			<td>
			<form method="post">'. $nonce . '
			<select name="sel_e_r" style="font-family:Consolas,Monaco,monospace;">
				<option value="0">' . __('Total rules enabled', 'ninjafirewall') .' : ' . count( $enabled_rules ) . '</option>';
sort( $enabled_rules );
$count = 0;
$desr = '';
foreach ( $enabled_rules as $key ) {
	if ( $key < 100 ) {
		$desc = ' ' . __('Remote/local file inclusion', 'ninjafirewall');
	} elseif ( $key < 150 ) {
		$desc = ' ' . __('Cross-site scripting', 'ninjafirewall');
	} elseif ( $key < 200 ) {
		$desc = ' ' . __('Code injection', 'ninjafirewall');
	} elseif (  $key > 249 && $key < 300 ) {
		$desc = ' ' . __('SQL injection', 'ninjafirewall');
	} elseif ( $key < 350 ) {
		$desc = ' ' . __('Various vulnerability', 'ninjafirewall');
	} elseif ( $key < 400 ) {
		$desc = ' ' . __('Backdoor/shell', 'ninjafirewall');
	} elseif ( $key > 999 && $key < 1300 ) {
		$desc = ' ' . __('Application specific', 'ninjafirewall');
	} elseif ( $key > 1349 ) {
		$desc = ' ' . __('WordPress vulnerability', 'ninjafirewall');
	}
	echo '<option value="' . htmlspecialchars($key) . '">' . __('Rule ID', 'ninjafirewall') .' : ' . htmlspecialchars($key) . $desc . '</option>';
	++$count;
}
echo '</select>&nbsp;&nbsp;<input class="button-secondary" type="submit" name="disable" value="' . __('Disable it', 'ninjafirewall') .'"' . disabled( $count, 0) .'>
		</form>
		<br />
		<form method="post">'. $nonce . '
		<select name="sel_d_r" style="font-family:Consolas,Monaco,monospace;">
		<option value="0">' . __('Total rules disabled', 'ninjafirewall') .' : ' . count( $disabled_rules ) . '</option>';
sort( $disabled_rules );
$count = 0;
foreach ( $disabled_rules as $key ) {
	if ( $key < 100 ) {
		$desc = ' ' . __('Remote/local file inclusion', 'ninjafirewall');
	} elseif ( $key < 150 ) {
		$desc = ' ' . __('Cross-site scripting', 'ninjafirewall');
	} elseif ( $key < 200 ) {
		$desc = ' ' . __('Code injection', 'ninjafirewall');
	} elseif (  $key > 249 && $key < 300 ) {
		$desc = ' ' . __('SQL injection', 'ninjafirewall');
	} elseif ( $key < 350 ) {
		$desc = ' ' . __('Various vulnerability', 'ninjafirewall');
	} elseif ( $key < 400 ) {
		$desc = ' ' . __('Backdoor/shell', 'ninjafirewall');
	} elseif ( $key > 999 && $key < 1300 ) {
		$desc = ' ' . __('Application specific', 'ninjafirewall');
	} elseif ( $key > 1349 ) {
		$desc = ' ' . __('WordPress vulnerability', 'ninjafirewall');
	}
	echo '<option value="' . htmlspecialchars($key) . '">' . __('Rule ID', 'ninjafirewall') .' #' . htmlspecialchars($key) . $desc . '</option>';
	++$count;
}

echo '</select>&nbsp;&nbsp;<input class="button-secondary" type="submit" name="disable" value="' . __('Enable it', 'ninjafirewall') .'"' . disabled( $count, 0) .'>
			</form>
			</td>
		</tr>
	</table>
</div>';
/* ================================================================== */
// EOF
