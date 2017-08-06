<?php
// +---------------------------------------------------------------------+
// | NinjaFirewall (WP Edition)                                          |
// |                                                                     |
// | (c) NinTechNet - https://nintechnet.com/                            |
// +---------------------------------------------------------------------+
// | This program is free software: you can redistribute it and/or       |
// | modify it under the terms of the GNU General Public License as      |
// | published by the Free Software Foundation, either version 3 of      |
// | the License, or (at your option) any later version.                 |
// |                                                                     |
// | This program is distributed in the hope that it will be useful,     |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of      |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       |
// | GNU General Public License for more details.                        |
// +---------------------------------------------------------------------+ sa
if ( strpos($_SERVER['SCRIPT_NAME'], '/nfwlog/') !== FALSE ||
	strpos($_SERVER['SCRIPT_NAME'], '/ninjafirewall/') !== FALSE ) { die('Forbidden'); }
if (defined('NFW_STATUS')) { return; }

$nfw_['fw_starttime'] = microtime(true);

// Optional NinjaFirewall configuration file
// ( see https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja ) :
if ( @file_exists($nfw_['file'] = dirname($_SERVER['DOCUMENT_ROOT']) .'/.htninja') ||
	@file_exists($nfw_['file'] = $_SERVER['DOCUMENT_ROOT'] .'/.htninja') ) {
	$nfw_['res'] = @include($nfw_['file']);
	if ( $nfw_['res'] == 'ALLOW' ) {
		define( 'NFW_STATUS', 20 );
		unset($nfw_);
		return;
	}
	if ( $nfw_['res'] == 'BLOCK' ) {
		header('HTTP/1.1 403 Forbidden');
		header('Status: 403 Forbidden');
		header('Pragma: no-cache');
		header('Cache-Control: no-cache, no-store, must-revalidate');
		header('Expires: 0');
		die('403 Forbidden');
	}
}

$nfw_['wp_content'] = dirname(dirname(dirname( __DIR__ )));
// Check if we have a user-defined log directory
// (see "Path to NinjaFirewall's log and cache directory"
// at https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja ) :
if ( defined('NFW_LOG_DIR') ) {
	$nfw_['log_dir'] = NFW_LOG_DIR . '/nfwlog';
} else {
	$nfw_['log_dir'] = $nfw_['wp_content'] . '/nfwlog';
}
if (! is_dir($nfw_['log_dir']) ) {
	if (! mkdir( $nfw_['log_dir'] . '/cache', 0755, true) ) {
		define( 'NFW_STATUS', 13 );
		return;
	}
}

if ( strpos($_SERVER['SCRIPT_NAME'], 'wp-login.php' ) !== FALSE ) {
	nfw_bfd(1);
} elseif ( strpos($_SERVER['SCRIPT_NAME'], 'xmlrpc.php' ) !== FALSE ) {
	nfw_bfd(2);
}

if (empty ($wp_config)) {
	$wp_config = dirname($nfw_['wp_content']) . '/wp-config.php';
}

if (! file_exists($wp_config) ) {
	if (! @file_exists( $wp_config = dirname( dirname($nfw_['wp_content']) ) . '/wp-config.php') ) {
		define( 'NFW_STATUS', 1 );
		unset($nfw_);
		unset($wp_config);
		return;
	}
}
if (! $nfw_['fh'] = fopen($wp_config, 'r') ) {
	define( 'NFW_STATUS', 2 );
	unset($nfw_);
	unset($wp_config);
	return;
}

while (! feof($nfw_['fh'])) {
	$nfw_['line'] = fgets($nfw_['fh']);
	if ( preg_match('/^\s*define\s*\(\s*[\'"]DB_NAME[\'"]\s*,\s*[\'"](.+?)[\'"]/', $nfw_['line'], $nfw_['match']) ) {
		$nfw_['DB_NAME'] = $nfw_['match'][1];
	} elseif ( preg_match('/^\s*define\s*\(\s*[\'"]DB_USER[\'"]\s*,\s*[\'"](.+?)[\'"]/', $nfw_['line'], $nfw_['match']) ) {
		$nfw_['DB_USER'] = $nfw_['match'][1];
	} elseif ( preg_match('/^\s*define\s*\(\s*[\'"]DB_PASSWORD[\'"]\s*,\s*([\'"])(.+?)\1/', $nfw_['line'], $nfw_['match']) ) {
		$nfw_['DB_PASSWORD'] = $nfw_['match'][2];
	} elseif ( preg_match('/^\s*define\s*\(\s*[\'"]DB_HOST[\'"]\s*,\s*[\'"](.+?)[\'"]/', $nfw_['line'], $nfw_['match']) ) {
		$nfw_['DB_HOST'] = $nfw_['match'][1];
	} elseif ( preg_match('/^\s*\$table_prefix\s*=\s*[\'"](.+?)[\'"]/', $nfw_['line'], $nfw_['match']) ) {
		$nfw_['table_prefix'] = $nfw_['match'][1];
	}
}
fclose($nfw_['fh']);
unset($wp_config);
if ( (! isset($nfw_['DB_NAME'])) || (! isset($nfw_['DB_USER'])) || (! isset($nfw_['DB_PASSWORD'])) ||	(! isset($nfw_['DB_HOST'])) || (! isset($nfw_['table_prefix'])) ) {
	define( 'NFW_STATUS', 3 );
	unset($nfw_);
	return;
}

nfw_check_dbhost();
@$nfw_['mysqli'] = new mysqli($nfw_['DB_HOST'], $nfw_['DB_USER'], $nfw_['DB_PASSWORD'], $nfw_['DB_NAME'], $nfw_['port'], $nfw_['socket']);
if ($nfw_['mysqli']->connect_error) {
	define( 'NFW_STATUS', 4 );
	unset($nfw_);
	return;
}

if (! $nfw_['result'] = @$nfw_['mysqli']->query('SELECT * FROM `' . $nfw_['mysqli']->real_escape_string($nfw_['table_prefix']) . "options` WHERE `option_name` = 'nfw_options'")) {
	define( 'NFW_STATUS', 5 );
	$nfw_['mysqli']->close();
	unset($nfw_);
	return;
}
if (! $nfw_['options'] = @$nfw_['result']->fetch_object() ) {
	define( 'NFW_STATUS', 6 );
	$nfw_['mysqli']->close();
	unset($nfw_);
	return;
}
$nfw_['result']->close();

if (! $nfw_['nfw_options'] = @unserialize($nfw_['options']->option_value) ) {
	$nfw_['mysqli']->close();
	define( 'NFW_STATUS', 11 );
	unset($nfw_);
	return;
}

if (! empty($nfw_['nfw_options']['clogs_pubkey']) && isset($_POST['clogs_req']) ) {
	include('fw_centlog.php');
	fw_centlog();
	exit;
}

if ( empty($nfw_['nfw_options']['enabled']) ) {
	$nfw_['mysqli']->close();
	define( 'NFW_STATUS', 20 );
	unset($nfw_);
	return;
}

if (! empty($nfw_['nfw_options']['response_headers']) && function_exists('header_register_callback')) {
	define('NFW_RESHEADERS', $nfw_['nfw_options']['response_headers']);
	if (! empty( $nfw_['nfw_options']['response_headers'][6] ) && ! empty( $nfw_['nfw_options']['csp_frontend_data'] ) ) {
		define( 'CSP_FRONTEND_DATA', $nfw_['nfw_options']['csp_frontend_data']);
	}
	if (! empty( $nfw_['nfw_options']['response_headers'][7] ) && ! empty( $nfw_['nfw_options']['csp_backend_data'] )  ) {
		define( 'CSP_BACKEND_DATA', $nfw_['nfw_options']['csp_backend_data'] );
	}
	header_register_callback('nfw_response_headers');
}

if (! empty($nfw_['nfw_options']['force_ssl']) ) {
	define('FORCE_SSL_ADMIN', true);
}
if (! empty($nfw_['nfw_options']['disallow_edit']) ) {
	define('DISALLOW_FILE_EDIT', true);
}
if (! empty($nfw_['nfw_options']['disallow_mods']) ) {
	define('DISALLOW_FILE_MODS', true);
}

$nfw_['a_msg'] = '';
if ( strpos($_SERVER['SCRIPT_NAME'], '/plugins.php' ) !== FALSE ) {
	if ( isset( $_REQUEST['action2'] )) {
		if ( (! isset( $_REQUEST['action'] )) || ( $_REQUEST['action'] == '-1') ) {
			$_REQUEST['action'] = $_REQUEST['action2'];
		}
		$_REQUEST['action2'] = '-1';
	}
	if ( isset( $_REQUEST['action'] )  ) {
		if ( $_REQUEST['action'] == 'update-selected' ) {
			if (! empty( $_POST['checked'] ) ) {
				$nfw_['a_msg'] = '1:4:' . @implode(", ", $_POST['checked']);
			}
		} elseif ( $_REQUEST['action'] == 'activate' ) {
			$nfw_['a_msg'] = '1:3:' . @$_REQUEST['plugin'];
		} elseif ( $_REQUEST['action'] == 'activate-selected' ) {
			if (! empty( $_POST['checked'] ) ) {
				$nfw_['a_msg'] = '1:3:' . @implode(", ", $_POST['checked']);
			}
		} elseif ( $_REQUEST['action'] == 'deactivate' ) {
			$nfw_['a_msg'] = '1:5:' . @$_REQUEST['plugin'];
		} elseif ( ( $_REQUEST['action'] == 'deactivate-selected' ) ){
			if (! empty( $_POST['checked'] ) ) {
				$nfw_['a_msg'] = '1:5:' . @implode(", ", $_POST['checked']);
			}
		} elseif ( ( $_REQUEST['action'] == 'delete-selected' ) &&
			( isset($_REQUEST['verify-delete'])) ) {
			if (! empty( $_POST['checked'] ) ) {
				$nfw_['a_msg'] = '1:6:' . @implode(", ", $_POST['checked']);
			}
		}
	}
} elseif ( strpos($_SERVER['SCRIPT_NAME'], '/themes.php' ) !== FALSE ) {
	if ( isset( $_GET['action'] )  ) {
		if ( $_GET['action'] == 'activate' ) {
			$nfw_['a_msg'] = '2:3:' . @$_GET['stylesheet'];
		} elseif ( $_GET['action'] == 'delete' ) {
			$nfw_['a_msg'] = '2:4:' . @$_GET['stylesheet'];
		}
	}
} elseif ( strpos($_SERVER['SCRIPT_NAME'], '/update.php' ) !== FALSE ) {
	if ( isset( $_GET['action'] )  ) {
		if ( $_REQUEST['action'] == 'update-selected' ) {
			if (! empty( $_POST['checked'] ) ) {
				$nfw_['a_msg'] = '1:4:' . @implode(", ", $_POST['checked']);
			}
		} elseif ( $_GET['action'] == 'upgrade-plugin' ) {
			$nfw_['a_msg'] = '1:4:' . @$_REQUEST['plugin'];
		} elseif ( $_GET['action'] == 'activate-plugin' ) {
			$nfw_['a_msg'] = '1:3:' . @$_GET['plugin'];
		} elseif ( $_GET['action'] == 'install-plugin' ) {
			$nfw_['a_msg'] = '1:2:' . @$_REQUEST['plugin'];
		} elseif ( $_GET['action'] == 'upload-plugin' ) {
			$nfw_['a_msg'] = '1:1:' . @$_FILES['pluginzip']['name'];
		} elseif ( $_GET['action'] == 'install-theme' ) {
			$nfw_['a_msg'] = '2:2:' . @$_REQUEST['theme'];
		} elseif ( $_GET['action'] == 'upload-theme' ) {
			$nfw_['a_msg'] = '2:1:' . @$_FILES['themezip']['name'];
		}
	}
} elseif ( strpos($_SERVER['SCRIPT_NAME'], '/admin-ajax.php' ) !== FALSE ) {
	if ( isset( $_REQUEST['action']) && $_REQUEST['action'] == 'update-plugin' ) {
		if (! empty($_REQUEST['plugin']) ) {
			$nfw_['a_msg'] = '1:4:' . @$_REQUEST['plugin'];
		}
	}
	if ( isset( $_REQUEST['action']) && $_REQUEST['action'] == 'delete-plugin' ) {
		if (! empty($_REQUEST['plugin']) ) {
			$nfw_['a_msg'] = '1:6:' . @$_REQUEST['plugin'];
		}
	}

} elseif ( strpos($_SERVER['SCRIPT_NAME'], '/update-core.php' ) !== FALSE ) {
	if ( isset( $_GET['action'] )  ) {
		if ( $_GET['action'] == 'do-plugin-upgrade' ) {
			if (! empty( $_POST['checked'] ) ) {
				$nfw_['a_msg'] = '1:4:' . @implode(", ", $_POST['checked']);
			}
		} elseif ( $_GET['action'] == 'do-core-upgrade' ) {
			$nfw_['a_msg'] = '3:1:' . @$_POST['version'];
		}
	}
}
if ( $nfw_['a_msg'] ) {
	define('NFW_ALERT', $nfw_['a_msg']);
}

nfw_check_ip();

nfw_check_session();
if (! empty($_SESSION['nfw_goodguy']) ) {

	if (! empty($_SESSION['nfw_malscan'] ) && isset( $_POST['malscan'] ) ) {
		include('fw_malwarescan.php');
		fw_malwarescan();
	}

	if (! empty($_SESSION['nfw_livelog']) &&  isset($_POST['livecls']) && isset($_POST['lines'])) {
		include('fw_livelog.php');
		fw_livelog_show();
	}

	if (! $nfw_['result'] = @$nfw_['mysqli']->query('SELECT * FROM `' . $nfw_['mysqli']->real_escape_string($nfw_['table_prefix']) . "options` WHERE `option_name` = 'nfw_rules'")) {
		define( 'NFW_STATUS', 7 );
		$nfw_['mysqli']->close();
		unset($nfw_);
		return;
	}
	if (! $nfw_['rules'] = @$nfw_['result']->fetch_object() ) {
		define( 'NFW_STATUS', 8 );
		$nfw_['mysqli']->close();
		unset($nfw_);
		return;
	}
	if (! $nfw_['nfw_rules'] = @unserialize($nfw_['rules']->option_value) ) {
		$nfw_['mysqli']->close();
		define( 'NFW_STATUS', 12 );
		unset($nfw_);
		return;
	}

	if (isset($nfw_['nfw_rules']['999']) ) {
		$nfw_['adm_rules'] = array();
		foreach ($nfw_['nfw_rules']['999'] as $key => $value) {
			if (empty($nfw_['nfw_rules'][$key]['ena']) ) { continue; }
			$nfw_['adm_rules'][$key] = $nfw_['nfw_rules'][$key];
		}
		if (! empty($nfw_['adm_rules'])) {
			nfw_check_request( $nfw_['adm_rules'], $nfw_['nfw_options'] );
		}
	}
	$nfw_['mysqli']->close();
	define( 'NFW_STATUS', 20 );
	unset($nfw_);
	return;
}
define('NFW_SWL', 1);

if ( file_exists($nfw_['log_dir'] .'/cache/livelogrun.php')) {
	include('fw_livelog.php');
	fw_livelog_record();
}

if (! empty($nfw_['nfw_options']['php_errors']) ) {
	@error_reporting(0);
	@ini_set('display_errors', 0);
}

if (! empty($nfw_['nfw_options']['allow_local_ip']) && ! filter_var(NFW_REMOTE_ADDR, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ) {
	$nfw_['mysqli']->close();
	unset($nfw_);
	define( 'NFW_STATUS', 20 );
	return;
}

if ( (@$nfw_['nfw_options']['scan_protocol'] == 1) && ($_SERVER['SERVER_PORT'] == 443) ) {
	$nfw_['mysqli']->close();
	unset($nfw_);
	define( 'NFW_STATUS', 20 );
	return;
}
if ( (@$nfw_['nfw_options']['scan_protocol'] == 2) && ($_SERVER['SERVER_PORT'] != 443) ) {
	$nfw_['mysqli']->close();
	define( 'NFW_STATUS', 20 );
	unset($nfw_);
	return;
}

if (! empty($nfw_['nfw_options']['fg_enable']) && ! defined('NFW_WPWAF') ) {
	include('fw_fileguard.php');
	fw_fileguard();
}

if (! empty($nfw_['nfw_options']['no_host_ip']) && @filter_var(parse_url('http://'.$_SERVER['HTTP_HOST'], PHP_URL_HOST), FILTER_VALIDATE_IP) ) {
	nfw_log('HTTP_HOST is an IP', $_SERVER['HTTP_HOST'], 1, 0);
   nfw_block();
}

if (! empty($nfw_['nfw_options']['referer_post']) && $_SERVER['REQUEST_METHOD'] == 'POST' && ! isset($_SERVER['HTTP_REFERER']) ) {
	nfw_log('POST method without Referer header', $_SERVER['REQUEST_METHOD'], 1, 0);
   nfw_block();
}

if ( strpos($_SERVER['SCRIPT_NAME'], '/xmlrpc.php' ) !== FALSE ) {
	if (! empty($nfw_['nfw_options']['no_xmlrpc']) ) {
		nfw_log('Access to WordPress XML-RPC API', $_SERVER['SCRIPT_NAME'], 2, 0);
		nfw_block();
	}
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		if (! isset( $HTTP_RAW_POST_DATA ) ) {
			$HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
		}

		if (! empty($nfw_['nfw_options']['no_xmlrpc_multi']) ) {

			if ( @strpos( $HTTP_RAW_POST_DATA, '<methodName>system.multicall</methodName>') !== FALSE ) {
				nfw_log('Access to WordPress XML-RPC API (system.multicall method)', $_SERVER['SCRIPT_NAME'], 2, 0);
				nfw_block();
			}
		}

		if (! empty($nfw_['nfw_options']['no_xmlrpc_pingback']) ) {

			if ( @strpos( $HTTP_RAW_POST_DATA, '<methodName>pingback.ping</methodName>') !== FALSE ) {
				nfw_log('Access to WordPress XML-RPC API (pingback.ping)', $_SERVER['SCRIPT_NAME'], 2, 0);
				nfw_block();
			}
		}
	}
}
if (! empty($nfw_['nfw_options']['no_xmlrpc_pingback']) && strpos($_SERVER['HTTP_USER_AGENT'], '; verifying pingback from ') !== FALSE) {
	nfw_log('Blocked pingback verification', $_SERVER['HTTP_USER_AGENT'], 2, 0);
   nfw_block();
}

if (! empty($nfw_['nfw_options']['no_post_themes']) && $_SERVER['REQUEST_METHOD'] == 'POST' && strpos($_SERVER['SCRIPT_NAME'], $nfw_['nfw_options']['no_post_themes']) !== FALSE ) {
	nfw_log('POST request in the themes folder', $_SERVER['SCRIPT_NAME'], 2, 0);
   nfw_block();
}

if (! empty($nfw_['nfw_options']['wp_dir']) && preg_match( '`' . $nfw_['nfw_options']['wp_dir'] . '`', $_SERVER['SCRIPT_NAME']) ) {
	nfw_log('Forbidden direct access to PHP script', $_SERVER['SCRIPT_NAME'], 2, 0);
   nfw_block();
}

nfw_check_upload();

if (! $nfw_['result'] = @$nfw_['mysqli']->query('SELECT * FROM `' . $nfw_['mysqli']->real_escape_string($nfw_['table_prefix']) . "options` WHERE `option_name` = 'nfw_rules'")) {
	define( 'NFW_STATUS', 7 );
	$nfw_['mysqli']->close();
	unset($nfw_);
	return;
}

if (! $nfw_['rules'] = @$nfw_['result']->fetch_object() ) {
	define( 'NFW_STATUS', 8 );
	$nfw_['mysqli']->close();
	unset($nfw_);
	return;
}
$nfw_['result']->close();

if (! $nfw_['nfw_rules'] = @unserialize($nfw_['rules']->option_value) ) {
	$nfw_['mysqli']->close();
	define( 'NFW_STATUS', 12 );
	unset($nfw_);
	return;
}

nfw_check_request( $nfw_['nfw_rules'], $nfw_['nfw_options'] );

if (! empty($nfw_['nfw_options']['get_sanitise']) && ! empty($_GET) ){
	$_GET = nfw_sanitise( $_GET, 1, 'GET');
}
if (! empty($nfw_['nfw_options']['post_sanitise']) && ! empty($_POST) ){
	$_POST = nfw_sanitise( $_POST, 1, 'POST');
}
if (! empty($nfw_['nfw_options']['request_sanitise']) && ! empty($_REQUEST) ){
	$_REQUEST = nfw_sanitise( $_REQUEST, 1, 'REQUEST');
}
if (! empty($nfw_['nfw_options']['cookies_sanitise']) && ! empty($_COOKIE) ) {
	$_COOKIE = nfw_sanitise( $_COOKIE, 3, 'COOKIE');
}
if (! empty($nfw_['nfw_options']['ua_sanitise']) && ! empty($_SERVER['HTTP_USER_AGENT']) ) {
	$_SERVER['HTTP_USER_AGENT'] = nfw_sanitise( $_SERVER['HTTP_USER_AGENT'], 1, 'HTTP_USER_AGENT');
}
if (! empty($nfw_['nfw_options']['referer_sanitise']) && ! empty($_SERVER['HTTP_REFERER']) ) {
	$_SERVER['HTTP_REFERER'] = nfw_sanitise( $_SERVER['HTTP_REFERER'], 1, 'HTTP_REFERER');
}
if (! empty($nfw_['nfw_options']['php_path_i']) && ! empty($_SERVER['PATH_INFO']) ) {
	$_SERVER['PATH_INFO'] = nfw_sanitise( $_SERVER['PATH_INFO'], 2, 'PATH_INFO');
}
if (! empty($nfw_['nfw_options']['php_path_t']) && ! empty($_SERVER['PATH_TRANSLATED']) ) {
	$_SERVER['PATH_TRANSLATED'] = nfw_sanitise( $_SERVER['PATH_TRANSLATED'], 2, 'PATH_TRANSLATED');
}
if (! empty($nfw_['nfw_options']['php_self']) && ! empty($_SERVER['PHP_SELF']) ) {
	$_SERVER['PHP_SELF'] = nfw_sanitise( $_SERVER['PHP_SELF'], 2, 'PHP_SELF');
}

@$nfw_['mysqli']->close();
define( 'NFW_STATUS', 20 );
unset($nfw_);
return;

// =====================================================================

function nfw_check_session() {

	if (version_compare(PHP_VERSION, '5.4', '<') ) {
		if (session_id() ) return;
	} else {
		if (session_status() === PHP_SESSION_ACTIVE) return;
	}

	@ini_set('session.cookie_httponly', 1);
	@ini_set('session.use_only_cookies', 1);
	if ($_SERVER['SERVER_PORT'] == 443) {
		@ini_set('session.cookie_secure', 1);
	}
	session_start();
}

// =====================================================================

function nfw_check_ip() {

	if ( defined('NFW_REMOTE_ADDR') ) { return; }

	global $nfw_;

	if (strpos($_SERVER['REMOTE_ADDR'], ',') !== false) {
		// Ensure we have a proper and single IP (a user may use the .htninja file
		// to redirect HTTP_X_FORWARDED_FOR, which may contain more than one IP,
		// to REMOTE_ADDR):
		$nfw_['match'] = array_map('trim', @explode(',', $_SERVER['REMOTE_ADDR']));
		foreach($nfw_['match'] as $nfw_['m']) {
			if ( filter_var($nfw_['m'], FILTER_VALIDATE_IP) )  {
				define( 'NFW_REMOTE_ADDR', $nfw_['m']);
				break;
			}
		}
	}
	if (! defined('NFW_REMOTE_ADDR') ) {
		define('NFW_REMOTE_ADDR', htmlspecialchars($_SERVER['REMOTE_ADDR']) );
	}
}

// =====================================================================

function nfw_check_upload() {

	if ( defined('NFW_STATUS') ) { return; }

	global $nfw_;

	$f_uploaded = nfw_fetch_uploads();
	$tmp = '';
	if ( empty($nfw_['nfw_options']['uploads']) ) {
		$tmp = '';
		foreach ($f_uploaded as $key => $value) {
			if (! $f_uploaded[$key]['name']) { continue; }
         $tmp .= $f_uploaded[$key]['name'] . ' (' . number_format($f_uploaded[$key]['size']) . ' bytes) ';
      }
      if ( $tmp ) {
			nfw_log('Blocked file upload attempt', rtrim($tmp, ' '), 3, 0);
			nfw_block();
		}
	} else {
		foreach ($f_uploaded as $key => $value) {
			if (! $f_uploaded[$key]['name']) { continue; }

			if ( $f_uploaded[$key]['size'] > 67 && $f_uploaded[$key]['size'] < 129 ) {
				$data = file_get_contents( $f_uploaded[$key]['tmp_name'] );
				if ( preg_match('`^X5O!P%@AP' . '\[4\\\PZX54\(P\^\)7CC\)7}\$EIC' .
				                'AR-STANDARD-ANTIVI' . 'RUS-TEST-FILE!\$H' . '\+H\*' .
				                '[\x09\x10\x13\x20\x1A]*`', $data) ) {
					nfw_log('EICAR Standard Anti-Virus Test File blocked', $f_uploaded[$key]['name'] . ' (' . number_format($f_uploaded[$key]['size']) . ' bytes)', 3, 0);
					nfw_block();
				}
			}

			if (! defined('NFW_NO_MIMECHECK') && isset( $f_uploaded[$key]['type'] ) && ! preg_match('/\/.*\bphp\d?\b/i', $f_uploaded[$key]['type']) &&
			preg_match('/\.ph(?:p([34x]|5\d?)?|t(ml)?)(?:\.|$)/', $f_uploaded[$key]['name']) ) {
				nfw_log('Blocked file upload attempt (MIME-type mismatch)', $f_uploaded[$key]['type'] .' != '. $f_uploaded[$key]['name'], 3, 0);
				nfw_block();
			}


			if (! empty($nfw_['nfw_options']['sanitise_fn']) ) {
				$tmp = '';
				if ( empty( $nfw_['nfw_options']['substitute'] ) ) {
					$nfw_['nfw_options']['substitute'] = 'X';
				}
				$f_uploaded[$key]['name'] = preg_replace('/[^\w\.\-]/i', $nfw_['nfw_options']['substitute'], $f_uploaded[$key]['name'], -1, $count);
				if ($count) {
					$tmp = ' (sanitising '. $count . ' char. from filename)';
				}
				if ( $tmp ) {
					list ($kn, $is_arr, $kv) = explode('::', $f_uploaded[$key]['where']);
					if ( $is_arr ) {
						$_FILES[$kn]['name'][$kv] = $f_uploaded[$key]['name'];
					} else {
						$_FILES[$kn]['name'] = $f_uploaded[$key]['name'];
					}
				}
			}
			nfw_log('File upload detected, no action taken' . $tmp , $f_uploaded[$key]['name'] . ' (' . number_format($f_uploaded[$key]['size']) . ' bytes)', 5, 0);
		}
	}
}

// =====================================================================

function nfw_fetch_uploads() {

	$f_uploaded = array();
	$count = 0;
	foreach ($_FILES as $nm => $file) {
		if ( is_array($file['name']) ) {
			foreach($file['name'] as $key => $value) {
				$f_uploaded[$count]['name'] = $file['name'][$key];
				$f_uploaded[$count]['type'] = $file['type'][$key];
				$f_uploaded[$count]['size'] = $file['size'][$key];
				$f_uploaded[$count]['tmp_name'] = $file['tmp_name'][$key];
				$f_uploaded[$count]['where'] = $nm . '::1::' . $key;
				$count++;
			}
		} else {
			$f_uploaded[$count]['name'] = $file['name'];
			$f_uploaded[$count]['type'] = $file['type'];
			$f_uploaded[$count]['size'] = $file['size'];
			$f_uploaded[$count]['tmp_name'] = $file['tmp_name'];
			$f_uploaded[$count]['where'] = $nm . '::0::0' ;
			$count++;
		}
	}
	return $f_uploaded;
}

// =====================================================================

function nfw_check_request( $nfw_rules, $nfw_options ) {

	if ( defined('NFW_STATUS') ) { return; }

	global $nfw_, $HTTP_RAW_POST_DATA;

	foreach ( $nfw_rules as $id => $rules ) {

		if ( empty( $rules['ena']) ) { continue; }

		$wherelist = explode('|', $rules['cha'][1]['whe']);

		foreach ($wherelist as $where) {

			if ( nfw_disabled_scan( $where, $nfw_options ) ) { continue; }

			// =================================================================
			if ( $where == 'RAW' ) {
				if (! isset( $HTTP_RAW_POST_DATA ) ) {
					$HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
				}

				if ( nfw_matching( 'RAW', $_SERVER['REQUEST_METHOD'], $nfw_rules, $rules, 1, $id, $HTTP_RAW_POST_DATA, $nfw_options ) ) {
					nfw_check_subrule( 'RAW', $_SERVER['REQUEST_METHOD'], $nfw_rules, $nfw_options, $rules, $id );
				}
				continue;
			}

			// =================================================================
			if ( $where == 'POST' || $where == 'GET' || $where == 'COOKIE' ||
				$where == 'SERVER' || $where == 'REQUEST' || $where == 'FILES' ||
				$where == 'SESSION'
			) {

				if ( empty($GLOBALS['_' . $where]) ) {continue;}

				foreach ($GLOBALS['_' . $where] as $key => $val) {

					if ( nfw_matching( $where, $key, $nfw_rules, $rules, 1, $id, null, $nfw_options ) ) {
						nfw_check_subrule( $where, $key, $nfw_rules, $nfw_options, $rules, $id );
					}

				}
				continue;
			}

			// =================================================================

			if ( isset( $_SERVER[$where] ) ) {

				if ( nfw_matching( 'SERVER', $where, $nfw_rules, $rules, 1, $id, null, $nfw_options ) ) {
					nfw_check_subrule( 'SERVER', $where, $nfw_rules, $nfw_options, $rules, $id );
				}
				continue;
			}

			// =================================================================

			$w = explode(':', $where);

			if ( empty($w[1]) || ! isset( $GLOBALS['_'.$w[0]][$w[1]] ) || nfw_disabled_scan( $w[0], $nfw_options ) ) {
				continue;
			}

			if ( nfw_matching( $w[0], $w[1], $nfw_rules, $rules, 1, $id, null, $nfw_options ) ) {
				nfw_check_subrule( $w[0], $w[1], $nfw_rules, $nfw_options, $rules, $id );
			}

			// =================================================================

		}

	}

}

// =====================================================================

function nfw_check_subrule( $w0, $w1, $nfw_rules, $nfw_options, $rules, $id ) {

	if ( isset( $rules['cha'][1]['cap'] ) ) {
		nfw_matching( $w0, $w1, $nfw_rules, $rules, 2, $id, null, $nfw_options );

	} else {
		$w = explode(':', $rules['cha'][2]['whe']);

		if (! isset( $w[1] ) ) {

			if ( $w[0] == 'RAW' ) {
				if ( nfw_disabled_scan( 'POST', $nfw_options) && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
					return;
				}
				global $HTTP_RAW_POST_DATA;
				if (! isset( $HTTP_RAW_POST_DATA ) ) {
					$HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
				}
				nfw_matching( $_SERVER['REQUEST_METHOD'], 'RAW', $nfw_rules, $rules, 2, $id, $HTTP_RAW_POST_DATA, $nfw_options );
				return;
			}
			$w[2] = $w[1] = $w[0];
			$w[0] = 'SERVER';
		} else {
			$w[2] = null;
		}

		if (! isset( $GLOBALS['_'.$w[0]][$w[1]] ) ) {
			return;
		}

		if ( nfw_disabled_scan( $w[0], $nfw_options, $w[2] ) ) {
			return;
		} else {
			nfw_matching( $w[0], $w[1], $nfw_rules, $rules, 2, $id, null, $nfw_options);
		}
	}

}

// =====================================================================

function nfw_disabled_scan( $where, $nfw_options, $extra = null ) {

	if ( $extra ) { $where = $extra; }

	if ( $where == 'POST' && empty($nfw_options['post_scan']) ||
		$where == 'GET' && empty($nfw_options['get_scan']) ||
		$where == 'COOKIE' && empty($nfw_options['cookies_scan']) ||
		$where == 'HTTP_USER_AGENT' && empty($nfw_options['ua_scan']) ||
		$where == 'HTTP_REFERER' && empty($nfw_options['referer_scan'])
	) {
		return 1;
	}
	return 0;
}

// =====================================================================

function nfw_matching( $where, $key, $nfw_rules, $rules, $subid, $id, $RAW_POST = null, $nfw_options ) {

	global $nfw_;

	if ( isset( $RAW_POST ) ) {
		$val = $RAW_POST;
	} else {
		$val = $GLOBALS['_'.$where][$key];
	}

	if ( is_array($val) ) {
		if ( isset( $nfw_['flattened'][$where][$key] ) ) {
			$val = $nfw_['flattened'][$where][$key];
		} else {
			$val = nfw_flatten( ' ', $val );
			$nfw_['flattened'][$where][$key] = $val;
		}
	}

	if ( $where == 'POST' && ! empty($nfw_options['post_b64']) && ! isset($nfw_['b64'][$where][$key]) && $val ) {
		nfw_check_b64($key, $val);
		$nfw_['b64'][$where][$key] = 1;
	}

	if ( isset( $rules['cha'][$subid]['exe'] ) ) {
		$val = @$rules['cha'][$subid]['exe']($val);
	}

	$t = '';

	if ( isset( $rules['cha'][$subid]['nor'] ) ) {
		$t .= 'N';
		if ( isset( $nfw_[$t][$where][$key] ) && ! isset( $rules['cha'][$subid]['exe'] ) ) {
			$val = $nfw_[$t][$where][$key];
		} else {
			$val = nfw_normalize( $val, $nfw_rules );
			if (! isset( $rules['cha'][$subid]['exe']) ) {
				$nfw_[$t][$where][$key] = $val;
			}
		}
	}

	if ( isset( $rules['cha'][$subid]['tra'] ) ) {
		$t .= 'T' . $rules['cha'][$subid]['tra'];
		if ( isset( $nfw_[$t][$where][$key] )  && ! isset( $rules['cha'][$subid]['exe'] ) ) {
			$val = $nfw_[$t][$where][$key];
		} else {
			$val = nfw_transform_string( $val, $rules['cha'][$subid]['tra'] );
			if (! isset( $rules['cha'][$subid]['exe']) ) {
				$nfw_[$t][$where][$key] = $val;
			}
		}
	}
	if ( empty( $rules['cha'][$subid]['noc']) ) {
		$t .= 'C';
		if ( isset( $nfw_[$t][$where][$key] ) && ! isset( $rules['cha'][$subid]['exe'] ) ) {
			$val = $nfw_[$t][$where][$key];
		} else {
			$val = nfw_compress_string( $val );
			if (! isset( $rules['cha'][$subid]['exe']) ) {
				$nfw_[$t][$where][$key] = $val;
			}
		}
	}

	if ( nfw_operator( $val, $rules['cha'][$subid]['wha'], $rules['cha'][$subid]['ope']	) ) {
		if ( isset( $rules['cha'][$subid+1]) ) {
			return 1;
		} else {
			if ( isset( $nfw_['flattened'][$where][$key] ) ) {
				nfw_log($rules['why'], $where .':' . $key . ' = ' . $nfw_['flattened'][$where][$key], $rules['lev'], $id);
			} elseif ( isset( $RAW_POST ) ) {
				nfw_log($rules['why'], $where .':' . $key . ' = ' . $RAW_POST, $rules['lev'], $id);
			} else {
				nfw_log($rules['why'], $where .':' . $key . ' = ' . $GLOBALS['_'.$where][$key], $rules['lev'], $id);
			}
			nfw_block();
		}
	}
	return 0;
}

// =====================================================================

function nfw_operator( $val, $what, $op ) {

	if ( $op == 2 ) {
		if ( $val != $what ) {
			return true;
		}
	} elseif ( $op == 3 ) {
		if ( strpos($val, $what) !== FALSE ) {
			return true;
		}
	} elseif ( $op == 4 ) {
		if ( stripos($val, $what) !== FALSE ) {
			return true;
		}
	} elseif ( $op == 5 ) {
		if ( preg_match("`$what`", $val ) ) {
			return true;
		}
	} elseif ( $op == 6 ) {
		if (! preg_match("`$what`", $val) ) {
			return true;
		}
	} elseif ( $op == 7 ) {
		return true;

	} elseif ( $op == 8 ) {
		if ( strpos($val, $what) === FALSE ) {
			return true;
		}
	} elseif ( $op == 9 ) {
		if ( stripos($val, $what) === FALSE ) {
			return true;
		}
	} else {
		if ( $val == $what ) {
			return true;
		}
	}
}

// =====================================================================

function nfw_normalize( $string, $nfw_rules ) {

	if ( empty( $string ) ) {
		return;
	}

	$norm = rawurldecode( $string );
	if (! $norm ) {
		return $string;
	}

	if ( preg_match('/&(?:#x0*[0-9a-f]{2}|#0*[12]?[0-9]{2}|amp|[lg]t|nbsp|quot)(?!;|\d)/i', $norm) ) {
		$norm = preg_replace('/&(#x0*[0-9a-f]{2}|#0*[12]?[0-9]{2}|amp|[lg]t|nbsp|quot)(?!;|\d)/i', '&\1;', $norm);
		if (! $norm ) {
			return $string;
		}
	}

	if ( preg_match('/\\\x[a-f0-9]{2}/i', $norm) ) {
		$norm = preg_replace_callback('/\\\x([a-f0-9]{2})/i', 'nfw_hex2ascii', $norm);
		if (! $norm ) {
			return $string;
		}
	}

	$norm = nfw_html_decode( $norm );
	if (! $norm ) {
		return $string;
	}

	if ( preg_match('/&#x?[0-9a-f]+;/i', $norm) ) {
		$norm = preg_replace('/(&#x?[0-9a-f]+;)/i', '', $norm);
		if (! $norm ) {
			return $string;
		}
	}

	if ( preg_match( '/(?:%|\\\)u[0-9a-f]{4}/i', $norm ) ) {
		$norm = preg_replace_callback('/(?:%|\\\)(u[0-9a-f]{4})/i', 'nfw_udecode', $norm);
		if (! $norm ) {
			return $string;
		}
	}

	if ( empty( $nfw_rules[2]['ena'] ) ) {
		$norm = preg_replace('/\x0|%00/', '', $norm);
		if (! $norm ) {
			return $string;
		}
	}

	return $norm;
}

// =====================================================================

function nfw_html_decode( $norm ) {

	global $nfw_;

	$nfw_['entity_in'] = array (
		'&Tab;','&NewLine;','&excl;','&quot;','&QUOT;','&num;','&dollar;',
		'&percnt;','&amp;','&AMP;','&apos;','&lpar;','&rpar;','&ast;',
		'&midast;','&plus;','&comma;','&period;','&sol;','&colon;','&semi;',
		'&lt;','&LT;','&equals;','&gt;','&GT;','&quest;','&commat;','&lsqb;',
		'&lbrack;','&bsol;','&rsqb;','&rbrack;','&Hat;','&lowbar;','&grave;',
		'&DiacriticalGrave;','&lcub;','&lbrace;','&verbar;','&vert;','&VerticalLine;',
		'&rcub;','&rbrace;','&nbsp;','&NonBreakingSpace;','&nvlt;','&nvgt;',"\xa0",
	);

	$nfw_['entity_out'] = array (
		'','','!','"','"','#','$','%','&','&',"'",'(',')','*','*','+',',','.','/',
		':',';','<','<','=','>','>','?','@','[','[','\\',']',']','^','_','`','`',
		'{','{','|','|','|','}','}',' ',' ','','',' '
	);

	$normout = str_replace( $nfw_['entity_in'], $nfw_['entity_out'], $norm);
	$normout = html_entity_decode( $normout, ENT_QUOTES, 'UTF-8' );

	return $normout;

}

// =====================================================================

function nfw_compress_string( $string, $where = null ) {

	if ( $where == 1 ) {
		$replace = ' ';
	} else {
		$replace = '';
	}

	$string = str_replace( array( "\x09", "\x0a","\x0b", "\x0c", "\x0d"),
				$replace, $string);
	$string = trim ( preg_replace('/\x20{2,}/', ' ', $string) );
	return $string;

}

// =====================================================================

function nfw_transform_string( $string, $where ) {

	if ( $where == 1 ) {
		$norm = trim( preg_replace_callback('((^([^a-z/&|#]*)|([\'"])(?:\\\\.|[^\n\3\\\\])*?\3|(?:[0-9a-z_$]+)|.)'.
			'(?:\s|--[^\n]*+\n|/\*(?:[^*!]|\*(?!/))*+\*/)*'.
			'(?:(?:\#|--(?:[\x00-\x20\x7f]|$)|/\*$)[^\n]*+\n|/\*!(?:\d{5})?|\*/|/\*(?:[^*!]|\*(?!/))*+\*/)*)si',
			'nfw_delcomments1',  $string . "\n") );
		$norm = preg_replace('/[\'"]\x20*\+?\x20*[\'"]/', '', $norm);
		$norm = strtolower( str_replace(	array('+', "'", '"', "(", ')', '`', ',', ';'), ' ', $norm) );

	} elseif ( $where == 2 ) {
		$norm = trim( preg_replace_callback('((^|([\'"])(?:\\\\.|[^\n\2\\\\])*?\2|(?:[0-9a-z_$]+)|.)'.
			'(?://[^\n]*+\n|/\*(?:[^*]|\*(?!/))*+\*/)*)si',
			'nfw_delcomments2',  $string . "\n") );
		$norm = preg_replace( array('/[\n\r\t\f\v]/', '`/\*\s*\*/`', '/[\'"`]\x20*[+.]?\x20*[\'"`]/'),
				array('', ' ', ''), $norm);
	} elseif ( $where == 3 ) {
		$norm = preg_replace( array('`/(\./)+`','`/{2,}`', '`/(.+?)/\.\./\1\b`'), array('/', '/', '/\1'), $string);
	}

	return $norm;

}

// =====================================================================

function nfw_delcomments1 ( $match ) {

	if (! empty($match[2]) ) { return ' '; }
	if ( $match[0] != $match[1] ) {
		return $match[1]. ' ';
	}
	return $match[1];

}

function nfw_delcomments2 ( $match ) {

	if ( $match[0] != $match[1] ) {
		return $match[1]. ' ';
	}
	return $match[1];

}

// =====================================================================

function nfw_udecode( $match ) {

	return @json_decode('"\\'.$match[1].'"');

}

// =====================================================================

function nfw_hex2ascii( $match ) {

	return chr( '0x'.$match[1] );

}

// =====================================================================

function nfw_flatten( $glue, $pieces ) {

	if ( defined('NFW_STATUS') ) { return; }

	$ret = array();

   foreach ($pieces as $r_pieces) {
      if ( is_array($r_pieces)) {
         $ret[] = nfw_flatten($glue, $r_pieces);
      } else {
			if (! empty($r_pieces) ) {
				$ret[] = $r_pieces;
			}
      }
   }
   return implode($glue, $ret);
}

// =====================================================================

function nfw_check_b64( $key, $string ) {

	if ( defined('NFW_STATUS') || strlen($string) < 4 ) { return; }

	$decoded = base64_decode($string);
	if ( strlen($decoded) < 4 ) { return; }

	if ( preg_match( '`\b(?:\$?_(COOKIE|ENV|FILES|(?:GE|POS|REQUES)T|SE(RVER|SSION))|HTTP_(?:(?:POST|GET)_VARS|RAW_POST_DATA)|GLOBALS)\s*[=\[)]|\b(?i:array_map|assert|base64_(?:de|en)code|chmod|curl_exec|(?:ex|im)plode|error_reporting|eval|file(?:_get_contents)?|f(?:open|write|close)|fsockopen|function_exists|gzinflate|md5|move_uploaded_file|ob_start|passthru|[ep]reg_replace|phpinfo|stripslashes|strrev|(?:shell_)?exec|substr|system|unlink)\s*\(|\becho\s*[\'"]|<(?i:a[\s/]|applet|div|embed|i?frame(?:set)?|img|link|meta|marquee|object|script|style|textarea)\b|\W\$\{\s*[\'"]\w+[\'"]|<\?(?i:php|=)|(?i:(?:\b|\d)select\b.+?from\b.+?(?:\b|\d)where|(?:\b|\d)insert\b.+?into\b|(?:\b|\d)union\b.+?(?:\b|\d)select\b|(?:\b|\d)update\b.+?(?:\b|\d)set\b)`', $decoded) ) {
		nfw_log('BASE64-encoded injection', 'POST:' . $key . ' = ' . $string, '3', 0);
		nfw_block();
	}
}

// =====================================================================

function nfw_sanitise( $str, $how, $msg ) {

	if ( defined('NFW_STATUS') ) { return; }

	if (! isset($str) ) { return null; }

	global $nfw_;

	if (is_string($str) ) {
		if (get_magic_quotes_gpc() ) { $str = stripslashes($str); }
		if ($how == 1) {
			$str2 = $nfw_['mysqli']->real_escape_string($str);
			$str2 = str_replace(	array(  '`', '<', '>'), array( '\\`', '&lt;', '&gt;'),	$str2);
		} elseif ($how == 2) {
			$str2 = str_replace(	array('\\', "'", '"', "\x0d", "\x0a", "\x00", "\x1a", '`', '<', '>'),
				array('\\\\', "\\'", '\\"', '-', '-', '-', '-', '\\`', '&lt;', '&gt;'),	$str);
		} else {
			$str2 = str_replace(	array('\\', "'", "\x00", "\x1a", '`', '<'),
				array('\\\\', "\\'", '-', '-', '\\`', '&lt;'),	$str);
		}
		if (! empty($nfw_['nfw_options']['debug']) ) {
			if ($str2 != $str) {
				nfw_log('Sanitising user input', $msg . ': ' . $str, 7, 0);
			}
			return $str;
		}
		if ($str2 != $str) {
			nfw_log('Sanitising user input', $msg . ': ' . $str, 6, 0);
		}
		return $str2;

	} else if (is_array($str) ) {
		foreach($str as $key => $value) {
			if (get_magic_quotes_gpc() ) {$key = stripslashes($key);}
			if ($how == 3) {
				$key2 = str_replace(	array('\\', "'", "\x00", "\x1a", '`', '<', '>'),
					array('\\\\', "\\'", '-', '-', '\\`', '&lt;', '&gt;'),	$key, $occ);
			} else {
				$key2 = str_replace(	array('\\', "'", '"', "\x0d", "\x0a", "\x00", "\x1a", '`', '<', '>'),
					array('\\\\', "\\'", '\\"', '-', '-', '-', '-', '&#96;', '&lt;', '&gt;'),	$key, $occ);
			}
			if ($occ) {
				unset($str[$key]);
				nfw_log('Sanitising user input', $msg . ': ' . $key, 6, 0);
			}
			$str[$key2] = nfw_sanitise($value, $how, $msg);
		}
		return $str;
	}
}

// =====================================================================

function nfw_block() {

	if ( defined('NFW_STATUS') ) { return; }

	global $nfw_;

	if (! empty($nfw_['nfw_options']['debug']) ) {
		return;
	}

	@$nfw_['mysqli']->close();

	$http_codes = array(
      400 => '400 Bad Request', 403 => '403 Forbidden',
      404 => '404 Not Found', 406 => '406 Not Acceptable',
      500 => '500 Internal Server Error', 503 => '503 Service Unavailable',
   );
   if (! isset($http_codes[$nfw_['nfw_options']['ret_code']]) ) {
		$nfw_['nfw_options']['ret_code'] = 403;
	}

	if (empty($nfw_['num_incident']) ) { $nfw_['num_incident'] = '000000'; }
	$tmp = str_replace( '%%NUM_INCIDENT%%', $nfw_['num_incident'],  base64_decode($nfw_['nfw_options']['blocked_msg']) );
	$tmp = @str_replace( '%%NINJA_LOGO%%', '<img title="NinjaFirewall" src="' . $nfw_['nfw_options']['logo'] . '" width="75" height="75">', $tmp );
	$tmp = str_replace( '%%REM_ADDRESS%%', NFW_REMOTE_ADDR, $tmp );

	@session_destroy();

	if (! headers_sent() ) {
		header('HTTP/1.1 ' . $http_codes[$nfw_['nfw_options']['ret_code']] );
		header('Status: ' .  $http_codes[$nfw_['nfw_options']['ret_code']] );
		header('Pragma: no-cache');
		header('Cache-Control: no-cache, no-store, must-revalidate');
		header('Expires: 0');
	}

	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">' . "\n" .
		'<html><head><title>NinjaFirewall: ' . $http_codes[$nfw_['nfw_options']['ret_code']] .
		'</title><style>body{font-family:sans-serif;font-size:13px;color:#000;}</style><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body bgcolor="white">' . $tmp . '</body></html>';
	exit;
}

// =====================================================================

function nfw_log($loginfo, $logdata, $loglevel, $ruleid) {

	if ( defined('NFW_STATUS') ) { return; }

	global $nfw_;

	if ( $loglevel == 6) {
		$nfw_['num_incident'] = '0000000';
		$http_ret_code = '200';
	} else {
		if (! empty($nfw_['nfw_options']['debug']) ) {
			$nfw_['num_incident'] = '0000000';
			$loglevel = 7;
			$http_ret_code = '200';
		} else {
			$nfw_['num_incident'] = mt_rand(1000000, 9000000);
			$http_ret_code = $nfw_['nfw_options']['ret_code'];
		}
	}

   if (strlen($logdata) > 200) { $logdata = mb_substr($logdata, 0, 200, 'utf-8') . '...'; }
	$res = '';
	$string = str_split($logdata);
	foreach ( $string as $char ) {
		if ( ord($char) < 32 || ord($char) > 126 ) {
			$res .= '%' . bin2hex($char);
		} else {
			$res .= $char;
		}
	}

	if (! $tzstring = ini_get('date.timezone') ) {
		$tzstring = 'UTC';
	}
	date_default_timezone_set($tzstring);
	$cur_month = date('Y-m');

	$stat_file = $nfw_['log_dir']. '/stats_' . $cur_month . '.php';
	$log_file = $nfw_['log_dir']. '/firewall_' . $cur_month . '.php';

	if ( file_exists( $stat_file ) ) {
		$nfw_stat = file_get_contents( $stat_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
	} else {
		$nfw_stat = '0:0:0:0:0:0:0:0:0:0';
	}
	$nfw_stat_arr = explode(':', $nfw_stat . ':');
	$nfw_stat_arr[$loglevel]++;

	@file_put_contents( $stat_file, $nfw_stat_arr[0] . ':' . $nfw_stat_arr[1] . ':' .
		$nfw_stat_arr[2] . ':' . $nfw_stat_arr[3] . ':' . $nfw_stat_arr[4] . ':' .
		$nfw_stat_arr[5] . ':' . $nfw_stat_arr[6] . ':' . $nfw_stat_arr[7] . ':' .
		$nfw_stat_arr[8] . ':' . $nfw_stat_arr[9], LOCK_EX );

	if (! file_exists($log_file) ) {
		$tmp = '<?php exit; ?>' . "\n";
	} else {
		$tmp = '';
	}

	if (! defined('NFW_REMOTE_ADDR') ) { define('NFW_REMOTE_ADDR', $_SERVER['REMOTE_ADDR']); }

	// Which encoding to use?
	if ( defined('NFW_LOG_ENCODING') ) {
		if ( NFW_LOG_ENCODING == 'b64' ) {
			$encoding = '[b64:' . base64_encode( $res ) . ']';
		} elseif ( NFW_LOG_ENCODING == 'none' ) {
			$encoding = '[' . $res . ']';
		} else {
			$unp = unpack('H*', $res);
			$encoding = '[hex:' . array_shift( $unp )  . ']';
		}
	} else {
		$unp = unpack('H*', $res);
		$encoding = '[hex:' . array_shift( $unp )  . ']';
	}

	@file_put_contents( $log_file,
		$tmp . '[' . time() . '] ' . '[' . round( microtime(true) - $nfw_['fw_starttime'], 5) . '] ' .
      '[' . $_SERVER['SERVER_NAME'] . '] ' . '[#' . $nfw_['num_incident'] . '] ' .
      '[' . $ruleid . '] ' .
      '[' . $loglevel . '] ' . '[' . NFW_REMOTE_ADDR . '] ' .
      '[' . $http_ret_code . '] ' . '[' . $_SERVER['REQUEST_METHOD'] . '] ' .
      '[' . $_SERVER['SCRIPT_NAME'] . '] ' . '[' . $loginfo . '] ' .
      $encoding . "\n", FILE_APPEND | LOCK_EX );
}

// =====================================================================

function nfw_bfd($where) {

	if ( defined('NFW_STATUS') ) { return; }

	global $nfw_;
	$bf_conf_dir = $nfw_['log_dir'] . '/cache';

	if (! file_exists($bf_conf_dir . '/bf_conf.php') ) {
		return;
	}

	$now = time();
	require($bf_conf_dir . '/bf_conf.php');
	if ( empty($bf_enable) ) {
		return;
	}

	if ( $where == 2 && empty($bf_xmlrpc) ) {
		return;
	}

	// NinjaFirewall <= 3.4.2:
	if (! isset( $auth_msgtxt ) ) {
		$auth_msgtxt = $auth_msg;
		$b64 = 0;
	// NinjaFirewall > 3.4.2:
	} else {
		$b64 = 1;
	}
	// NinjaFirewall < 3.5:
	if (! isset( $bf_allow_bot ) ) {
		$bf_allow_bot = 0;
	}
	if (! isset( $bf_type ) ) {
		$bf_type = 0;
	}

	if ( $where == 1 && $bf_allow_bot == 0 ) {
		if ( empty( $_SERVER['HTTP_ACCEPT'] ) || empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) || empty( $_SERVER['HTTP_USER_AGENT'] ) || stripos( $_SERVER['HTTP_USER_AGENT'], 'Mozilla' ) === FALSE ) {
			header('HTTP/1.0 404 Not Found');
			header('Pragma: no-cache');
			header('Cache-Control: no-cache, no-store, must-revalidate');
			header('Expires: 0');
			$nfw_['nfw_options']['ret_code'] = '404';
			nfw_log('Blocked access to the login page', 'bot detection is enabled', 1, 0);
			@session_destroy();
			exit('404 Not Found');
		}
	}

	if ( $bf_enable == 2 ) {
		nfw_check_auth($auth_name, $auth_pass, $auth_msgtxt, $bf_rand, $b64, $bf_allow_bot, $bf_type, $captcha_text);
		return;
	}


	if ( file_exists($bf_conf_dir . '/bf_blocked' . $where . $_SERVER['SERVER_NAME'] . $bf_rand) ) {

		$mtime = filemtime( $bf_conf_dir . '/bf_blocked' . $where . $_SERVER['SERVER_NAME'] . $bf_rand );
		if ( ($now - $mtime) < $bf_bantime * 60 ) {

			nfw_check_auth($auth_name, $auth_pass, $auth_msgtxt, $bf_rand, $b64, $bf_allow_bot, $bf_type, $captcha_text);
			return;
		} else {

			@unlink($bf_conf_dir . '/bf_blocked' . $where . $_SERVER['SERVER_NAME'] . $bf_rand);
		}
	}


	if ( strpos($bf_request, $_SERVER['REQUEST_METHOD']) === false ) {
		return;
	}


	if ( file_exists($bf_conf_dir . '/bf_' . $where . $_SERVER['SERVER_NAME'] . $bf_rand ) ) {
		$tmp_log = file( $bf_conf_dir . '/bf_' . $where . $_SERVER['SERVER_NAME'] . $bf_rand, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if ( count( $tmp_log) >= $bf_attempt ) {
			if ( ($tmp_log[count($tmp_log) - 1] - $tmp_log[count($tmp_log) - $bf_attempt]) <= $bf_maxtime ) {

				$bfdh = fopen( $bf_conf_dir . '/bf_blocked' . $where . $_SERVER['SERVER_NAME'] . $bf_rand, 'w');
				fclose( $bfdh );

				unlink( $bf_conf_dir . '/bf_' . $where . $_SERVER['SERVER_NAME'] . $bf_rand );
				$nfw_['nfw_options']['ret_code'] = '401';
				if ($where == 1) {
					$where = 'wp-login.php';
				} else {
					$where = 'XML-RPC API';
				}
				nfw_log('Brute-force attack detected on ' . $where, 'enabling HTTP authentication for ' . $bf_bantime . 'mn', 3, 0);
				if (! empty($bf_authlog) ) {
					if (defined('LOG_AUTHPRIV') ) { $tmp = LOG_AUTHPRIV; }
					else { $tmp = LOG_AUTH;	}
					@openlog('ninjafirewall', LOG_NDELAY|LOG_PID, $tmp);
					@syslog(LOG_INFO, 'Possible brute-force attack from '. $_SERVER['REMOTE_ADDR'] .
							' on '. $_SERVER['SERVER_NAME'] .' ('. $where .'). Blocking access for ' . $bf_bantime . 'mn.');
					@closelog();
				}
				nfw_check_auth($auth_name, $auth_pass, $auth_msgtxt, $bf_rand, $b64, $bf_allow_bot, $bf_type, $captcha_text);
				return;

			}
		}
		$mtime = filemtime( $bf_conf_dir . '/bf_' . $where . $_SERVER['SERVER_NAME'] . $bf_rand );
		if ( ($now - $mtime) > $bf_bantime * 60 ) {
			unlink( $bf_conf_dir . '/bf_' . $where . $_SERVER['SERVER_NAME'] . $bf_rand );
		}
	}

	@file_put_contents($bf_conf_dir . '/bf_' . $where . $_SERVER['SERVER_NAME'] . $bf_rand, $now . "\n", FILE_APPEND | LOCK_EX);

}
// =====================================================================

function nfw_check_auth( $auth_name, $auth_pass, $auth_msgtxt, $bf_rand, $b64, $bf_allow_bot, $bf_type, $captcha_text ) {

	if ( defined('NFW_STATUS') ) { return; }

	nfw_check_session();

	if ( isset($_SESSION['nfw_bfd']) && $_SESSION['nfw_bfd'] == $bf_rand ) {
		return;
	}

	if ( $bf_type == 0 ) {
		// Password protection:
		if (! empty($_REQUEST['u']) && ! empty($_REQUEST['p']) ) {
			if ( $_REQUEST['u'] === $auth_name && sha1($_REQUEST['p']) === $auth_pass ) {
				$_SESSION['nfw_bfd'] = $bf_rand;
				return;
			}
		}
	} else {
		// Make sure the GD extension is loaded:
		if ( function_exists( 'gd_info' ) ) {
			// Captcha protection:
			if (! empty( $_REQUEST['c'] ) && isset( $_SESSION['nfw_bfd_c'] ) ) {
				if ( $_SESSION['nfw_bfd_c'] == strtolower( $_REQUEST['c'] ) ) {
					$_SESSION['nfw_bfd'] = $bf_rand;
					unset( $_SESSION['nfw_bfd_c'] );
					return;
				}
			}
		} else {
			// Return in no GD extension:
			return;
		}
	}

	session_destroy();

	if ( $b64 ) { $auth_msgtxt = base64_decode( $auth_msgtxt ); }

	header('HTTP/1.0 401 Unauthorized');
	header('X-Frame-Options: SAMEORIGIN');
	header('Pragma: no-cache');
	header('Cache-Control: no-cache, no-store, must-revalidate');
	header('Expires: 0');
	if ( $bf_type == 0 ) {
		$message = '<html><head><title>Brute-force protection by NinjaFirewall</title><link rel="stylesheet" href="./wp-includes/css/buttons.min.css" type="text/css"><link rel="stylesheet" href="./wp-admin/css/login.min.css" type="text/css"></head><body class="login wp-core-ui" style="color:#444"><div id="login"><center><h2>' . $auth_msgtxt . '</h2><form method="post"><label>Brute-force protection by NinjaFirewall</label><br><br><p><input class="input" type="text" name="u" placeholder="Username"></p><p><input class="input" type="password" name="p" placeholder="Password"></p><p align="right"><input type="submit" value="Login Page&nbsp;&#187;" class="button-secondary"></p></form></center></div></body></html>';
	} else {
		$message = '<html><head><title>Brute-force protection by NinjaFirewall</title><link rel="stylesheet" href="./wp-includes/css/buttons.min.css" type="text/css"><link rel="stylesheet" href="./wp-admin/css/login.min.css" type="text/css"></head><body class="login wp-core-ui" style="color:#444"><div id="login"><center><form method="post"><p><label>'. base64_decode( $captcha_text ) .'</label></p><br><p>' . nfw_get_captcha() . '</p><p><input class="input" type="text" name="c" autofocus></p><p align="right"><input type="submit" value="Login Page&nbsp;&#187;" class="button-secondary"></p></form><br><label>Brute-force protection by NinjaFirewall.</label></center></div></body></html>';
	}
	if ( $bf_allow_bot == 0 ) {
		ini_set('zlib.output_compression','Off');
		header('Content-Encoding: gzip');
		echo gzencode( $message, 1 );
	} else {
		header('Content-Type: text/html; charset=utf-8');
		echo $message;
	}

	exit;
}

// =====================================================================
function nfw_get_captcha() {

	session_start();

	$characters  = 'AaBbCcDdEeFfGgHhiIJjKkLMmNnPpRrSsTtUuVvWwXxYyZz123456789';
	$captcha = '';
	while( strlen( $captcha ) < 5 ) {
		$captcha .= substr( $characters, mt_rand() % strlen( $characters ), 1 );
	}

	// Background image with dimensions
	$image = imagecreate( 200, 60 );
	// Background color:
	imagecolorallocate( $image, 255, 255, 255 );
	// Text color:
	$text_color = imagecolorallocate( $image, 77, 77, 77 );
	// Font:
	global $nfw_;
	if ( file_exists( "{$nfw_['log_dir']}/font.ttf" ) ) {
		imagettftext( $image, 35, 0, 15, 45, $text_color, "{$nfw_['log_dir']}/font.ttf", $captcha );
	} else {
		imagettftext( $image, 35, 0, 15, 45, $text_color, __DIR__ . '/share/font.ttf', $captcha );
	}

	ob_start();
	imagepng( $image );
	$img_content = ob_get_contents();
	ob_end_clean();
	imagedestroy( $image );

	$res = '<img src="data:image/png;base64,'. base64_encode( $img_content ) .'" />';

	$_SESSION['nfw_bfd_c'] = strtolower( $captcha );

	return $res;
}

// =====================================================================
// From WP db_connect() :
function nfw_check_dbhost() {

	global $nfw_;

	$nfw_['port'] = null;
	$nfw_['socket'] = null;
	$port_or_socket = strstr( $nfw_['DB_HOST'], ':' );
	if ( ! empty( $port_or_socket ) ) {
		$nfw_['DB_HOST'] = substr( $nfw_['DB_HOST'], 0, strpos( $nfw_['DB_HOST'], ':' ) );
		$port_or_socket = substr( $port_or_socket, 1 );
		if ( 0 !== strpos( $port_or_socket, '/' ) ) {
			$nfw_['port'] = intval( $port_or_socket );
			$maybe_socket = strstr( $port_or_socket, ':' );
			if ( ! empty( $maybe_socket ) ) {
				$nfw_['socket'] = substr( $maybe_socket, 1 );
			}
		} else {
			$nfw_['socket'] = $port_or_socket;
		}
	}
}

// =====================================================================

function nfw_response_headers() {

	if (! defined('NFW_RESHEADERS') ) { return; }
	$NFW_RESHEADERS = NFW_RESHEADERS;

	$rewrite = array();

	if (! empty( $NFW_RESHEADERS[0] ) ) {
		foreach (@headers_list() as $header) {
			if (strpos($header, 'Set-Cookie:') === false) { continue; }
			if (stripos($header, '; httponly') !== false) {
				$rewrite[] = $header;
				continue;
			}
			$rewrite[] = $header . '; httponly';
		}
		if (! empty($rewrite) ) {
			@header_remove('Set-Cookie');
			foreach($rewrite as $cookie) {
				header($cookie, false);
			}
		}
	}

	if (! empty( $NFW_RESHEADERS[1] ) ) {
		header('X-Content-Type-Options: nosniff');
	}

	if (! empty( $NFW_RESHEADERS[2] ) ) {
		if ($NFW_RESHEADERS[2] == 1) {
			header('X-Frame-Options: SAMEORIGIN');
		} else {
			header('X-Frame-Options: DENY');
		}
	}

	if (! empty( $NFW_RESHEADERS[3] ) ) {
		header('X-XSS-Protection: 1; mode=block');
	}

	if (! empty( $NFW_RESHEADERS[6] ) && strpos($_SERVER['SCRIPT_NAME'], '/wp-admin/') === FALSE ) {
		header('Content-Security-Policy: ' . CSP_FRONTEND_DATA);
	}
	if (! empty( $NFW_RESHEADERS[7] ) && strpos($_SERVER['SCRIPT_NAME'], '/wp-admin/') !== FALSE ) {
		header('Content-Security-Policy: ' . CSP_BACKEND_DATA);
	}

	if ( empty($NFW_RESHEADERS[4] ) ) { return; }

	if ( $_SERVER['SERVER_PORT'] != 443 &&
	(! isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) ||
	$_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') ) {
		return;
	}
	if ($NFW_RESHEADERS[4] == 1) {
		$max_age = 'max-age=2628000';
	} elseif ($NFW_RESHEADERS[4] == 2) {
		$max_age = 'max-age=15768000';
	} elseif ($NFW_RESHEADERS[4] == 3) {
		$max_age = 'max-age=31536000';
	} elseif ($NFW_RESHEADERS[4] == 4) {
		$max_age = 'max-age=0';
	}
	if (! empty( $NFW_RESHEADERS[5] ) ) {
		$max_age .= ' ; includeSubDomains';
	}
	header('Strict-Transport-Security: '. $max_age);
}

// =====================================================================
// EOF
