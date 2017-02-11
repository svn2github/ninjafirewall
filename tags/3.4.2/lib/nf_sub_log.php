<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
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

$log_dir = NFW_LOG_DIR . '/nfwlog/';
$monthly_log = 'firewall_' . date( 'Y-m' ) . '.php';

if ( ! file_exists( $log_dir . $monthly_log ) ) {
	nf_sub_log_create( $log_dir . $monthly_log );
}

if (! is_writable( $log_dir . $monthly_log ) ) {
	$write_err = sprintf( __('the current month log (%s) is not writable. Please chmod it and its parent directory to 0777', 'ninjafirewall'), htmlspecialchars( $log_dir . $monthly_log ) );
} elseif (! is_writable( $log_dir ) ) {
	$write_err = sprintf( __('the log directory (%s) is not writable. Please chmod it to 0777', 'ninjafirewall'), htmlspecialchars($log_dir ) );
}

global $available_logs;
$available_logs = nf_sub_log_find_local( $log_dir );

if (! empty( $_POST['nfw_act']) && $_POST['nfw_act'] == 'pubkey' ) {
	if ( empty($_POST['nfwnonce']) || ! wp_verify_nonce($_POST['nfwnonce'], 'clogs_pubkey') ) {
		wp_nonce_ays('clogs_pubkey');
	}
	if (isset( $_POST['delete_pubkey'] ) ) {
		$_POST['nfw_options']['clogs_pubkey'] = '';
		$ok_msg = __('Your public key has been deleted', 'ninjafirewall');
	} else {
		$ok_msg = __('Your public key has been saved', 'ninjafirewall');
	}
	nf_sub_log_save_pubkey( $nfw_options );

	$nfw_options = nfw_get_option( 'nfw_options' );
}

$max_lines = 1500;

if ( isset( $_GET['nfw_logname'] ) ) {
	if ( empty( $_GET['nfwnonce'] ) || ! wp_verify_nonce($_GET['nfwnonce'], 'log_select') ) {
		wp_nonce_ays('log_select');
	}
	$data = nf_sub_log_read_local( $_GET['nfw_logname'], $log_dir, $max_lines-1 );
}

if ( isset( $_GET['nfw_logname'] ) && ! empty( $available_logs[$_GET['nfw_logname']] ) ) {
	$selected_log = $_GET['nfw_logname'];
} else {
	$selected_log = $monthly_log;
	$data = nf_sub_log_read_local( $monthly_log, $log_dir, $max_lines-1 );
}

nf_sub_log_js_header();

?>
<div class="wrap">
	<div style="width:33px;height:33px;background-image:url(<?php echo plugins_url(); ?>/ninjafirewall/images/ninjafirewall_32.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;"></div>
	<h1><?php _e('Firewall Log', 'ninjafirewall') ?></h1>
<?php

if ( ! empty( $write_err ) ) {
	echo '<div class="error notice is-dismissible"><p>' . __('Error', 'ninjafirewall') . ': ' . $write_err . '</p></div>';
}

if ( ! empty( $ok_msg ) ) {
	echo '<div class="updated notice is-dismissible"><p>' . $ok_msg . '.</p></div>';
}
if ( isset( $data['lines'] ) && $data['lines'] > $max_lines ) {
	echo '<div class="error notice is-dismissible"><p>' . __('Note', 'ninjafirewall') . ': ' . sprintf( __('your log has %s lines. I will display the last %s lines only.', 'ninjafirewall'), $data['lines'], $max_lines ) . '</p></div>';
}


echo '<center>' . __('Viewing:', 'ninjafirewall') . ' <select onChange=\'window.location="?page=nfsublog&nfwnonce='. wp_create_nonce('log_select') .'&nfw_logname=" + this.value;\'>';
foreach ($available_logs as $log_name => $tmp) {
	echo '<option value="' . $log_name . '"';
	if ( $selected_log == $log_name ) {
		echo ' selected';
	}
	$log_stat = stat($log_dir . $log_name);
	echo '>' . str_replace('.php', '', $log_name) . ' (' . number_format($log_stat['size']) .' '. __('bytes', 'ninjafirewall') . ')</option>';
}
echo '</select></center>';

$levels = array( '', 'medium', 'high', 'critical', 'error', 'upload', 'info', 'DEBUG_ON' );

nfw_get_blogtimezone();

$logline = '';
if ( isset( $data['log'] ) && is_array( $data['log'] ) ) {
	foreach ( $data['log'] as $line ) {
		if ( preg_match( '/^\[(\d{10})\]\s+\[.+?\]\s+\[(.+?)\]\s+\[(#\d{7})\]\s+\[(\d+)\]\s+\[(\d)\]\s+\[([\d.:a-fA-F, ]+?)\]\s+\[.+?\]\s+\[(.+?)\]\s+\[(.+?)\]\s+\[(.+?)\]\s+\[(hex:)?(.+)\]$/', $line, $match ) ) {
			if ( empty( $match[4]) ) { $match[4] = '-'; }
			if ( $match[10] == 'hex:' ) { $match[11] = pack('H*', $match[11]); }
			$res = date( 'd/M/y H:i:s', $match[1] ) . '  ' . $match[3] . '  ' .
			str_pad( $levels[$match[5]], 8 , ' ', STR_PAD_RIGHT) .'  ' .
			str_pad( $match[4], 4 , ' ', STR_PAD_LEFT) . '  ' . str_pad( $match[6], 15, ' ', STR_PAD_RIGHT) . '  ' .
			$match[7] . ' ' . $match[8] . ' - ' .	$match[9] . ' - [' . $match[11] . '] - ' . $match[2];
			$logline .= htmlentities( $res ."\n" );
		}
	}
}

?>
<form name="frmlog">
	<table class="form-table">
		<tr>
			<td width="100%">
				<textarea name="txtlog" class="small-text code" style="width:100%;height:300px;" wrap="off"><?php
				if ( ! empty( $logline ) ) {
					echo '       DATE         INCIDENT  LEVEL     RULE     IP            REQUEST' . "\n";
					echo $logline;
				} else {
					if (! empty( $data['err_msg'] ) ) {
						echo "\n\n > {$data['err_msg']}";
					} else {
						echo "\n\n > " . __('The selected log is empty.', 'ninjafirewall');
					}
				}
				?></textarea>
				<br />
				<center>
					<span class="description"><?php _e('The log is rotated monthly', 'ninjafirewall') ?></span>
				</center>
			</td>
		</tr>
	</table>
</form>

<a name="clogs"></a>
<form name="frmlog2" method="post" action="?page=nfsublog" onsubmit="return check_key();">
	<?php

	wp_nonce_field('clogs_pubkey', 'nfwnonce', 0);
	if ( empty( $nfw_options['clogs_pubkey'] ) || ! preg_match( '/^[a-f0-9]{40}:(?:[a-f0-9:.]{3,39}|\*)$/', $nfw_options['clogs_pubkey'] ) ) {
		$nfw_options['clogs_pubkey'] = '';
	}

	?>
	<br />

	<a name="clogs"></a>
	<h3><?php _e('Centralized Logging', 'ninjafirewall') ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Enter your public key (optional)', 'ninjafirewall') ?></th>
			<td align="left">
				<input class="large-text" type="text" maxlength="80" name="nfw_options[clogs_pubkey]" value="<?php echo htmlspecialchars( $nfw_options['clogs_pubkey'] ) ?>" autocomplete="off" />
				<p><span class="description"><?php printf( __('<a href="%s">Consult our blog</a> if you want to enable centralized logging.', 'ninjafirewall'), 'https://blog.nintechnet.com/centralized-logging-with-ninjafirewall/' ) ?></span></p>
			</td>
		</tr>
	</table>

	<br />
	<input type="hidden" name="nfw_act" value="pubkey" />
	<input class="button-primary" name="save_pubkey" onclick="what=0" value="<?php _e('Save Public Key', 'ninjafirewall') ?>" type="submit" />
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input class="button-secondary" name="delete_pubkey" onclick="what=1" value="<?php _e('Delete Public Key', 'ninjafirewall') ?>" type="submit"<?php disabled($nfw_options['clogs_pubkey'], '' ) ?> />

</form>

<?php
echo '
</div>';

/* ------------------------------------------------------------------ */

function nf_sub_log_js_header() {

	echo '<script>
var what;
function check_key() {
	if (what == 1) { return true; }
	var pubkey = document.frmlog2.elements["nfw_options[clogs_pubkey]"];
	if (! pubkey.value.match( /^[a-f0-9]{40}:(?:[a-f0-9:.]{3,39}|\*)$/) ) {
		pubkey.focus();
		alert("'.
		// translators: quotes (') must be escaped
		__('Your public key is not valid.', 'ninjafirewall') . '");
		return false;
	}
}
</script>';

}

/* ------------------------------------------------------------------ */

function nf_sub_log_create( $log ) {

	file_put_contents( $log, "<?php exit; ?>\n" );

}

/* ------------------------------------------------------------------ */

function nf_sub_log_find_local( $log_dir ) {

	$available_logs = array();
	if ( is_dir( $log_dir ) ) {
		if ( $dh = opendir( $log_dir ) ) {
			while ( ($file = readdir($dh) ) !== false ) {
				if (preg_match( '/^(firewall_(\d{4})-(\d\d)(?:\.\d+)?\.php)$/', $file, $match ) ) {
					$available_logs[$match[1]] = 1;
				}
			}
			closedir($dh);
		}
	}
	krsort($available_logs);

	return $available_logs;
}

/* ------------------------------------------------------------------ */

function nf_sub_log_save_pubkey( $nfw_options ) {

	if ( empty( $_POST['nfw_options']['clogs_pubkey'] ) ||
		! preg_match( '/^[a-f0-9]{40}:(?:[a-f0-9:.]{3,39}|\*)$/', $_POST['nfw_options']['clogs_pubkey'] ) ) {
		$nfw_options['clogs_pubkey'] = '';
	} else {
		$nfw_options['clogs_pubkey'] = $_POST['nfw_options']['clogs_pubkey'];
	}

	nfw_update_option( 'nfw_options', $nfw_options);

}

/* ------------------------------------------------------------------ */

function nf_sub_log_read_local( $log, $log_dir, $max_lines ) {

	if (! preg_match( '/^(firewall_\d{4}-\d\d(?:\.\d+)?\.)php$/', trim( $log ) ) ) {
		wp_nonce_ays('log_select');
	}

	$data = array();
	$data['type'] = 'local';

	if (! file_exists( $log_dir . $log ) ) {
		$data['err_msg'] = __('The requested log does not exist.', 'ninjafirewall');
		return $data;
	}

	$data['log'] = file( $log_dir . $log, FILE_SKIP_EMPTY_LINES );

	if ( $data['log'] === false ) {
		$data['err_msg'] = __('Unable to open the log for read operation.', 'ninjafirewall');
		return $data;
	}
	if ( strpos( $data['log'][0], '<?php' ) !== FALSE ) {
		unset( $data['log'][0] );
	}
	$data['lines'] = count( $data['log'] );
	if ( $max_lines < $data['lines'] ) {
		for ($i = 0; $i < ( $data['lines'] - $max_lines); $i++ ) {
			unset( $data['log'][$i] ) ;
		}
	}

	if ( $data['lines'] == 0 ) {
		$data['err_msg'] = __('The selected log is empty.', 'ninjafirewall');
	}

	return $data;

}

/* ------------------------------------------------------------------ */
// EOF
