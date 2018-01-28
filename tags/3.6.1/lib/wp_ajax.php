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

@file_put_contents( NFW_LOG_DIR . '/nfwlog/cache/malscan.log', time() . ": [AX] Entering ajax callback\n" );

if ( check_ajax_referer( 'nfw_msajax_javascript', 'nfw_sc_nonce', false ) && ! empty( $_POST['sigs'] ) ){
	$sigs = rtrim( $_POST['sigs'], ':' );
	wp_schedule_single_event( time() - 10, 'nfmalwarescan', array( $sigs ) );
	$doing_wp_cron = sprintf( '%.22F', microtime( true ) );
	set_transient( 'doing_cron', $doing_wp_cron );
	$cron_request = apply_filters( 'cron_request', array(
		'url'  => add_query_arg( 'doing_wp_cron', $doing_wp_cron, site_url( 'wp-cron.php' ) ),
		'key'  => $doing_wp_cron,
		'args' => array(
			//~ 'timeout'   => 0.01,
			'blocking'  => false,
			'sslverify' => apply_filters( 'https_local_ssl_verify', false )
		)
	), $doing_wp_cron );

	@file_put_contents( NFW_LOG_DIR . '/nfwlog/cache/malscan.log', time() . ": [AX] POSTing request to " . site_url( 'wp-cron.php' ) . "\n", FILE_APPEND );

	$res = wp_remote_post( $cron_request['url'], $cron_request['args'] );

	if ( is_wp_error( $res ) ) {
		@file_put_contents( NFW_LOG_DIR . '/nfwlog/cache/malscan.log', time() . ": [AX] ERROR: ". $res->get_error_message() . "\n", FILE_APPEND );
		echo htmlspecialchars( $res->get_error_message() );
	} else {
		echo 'OK';
	}
} else {
	@file_put_contents( NFW_LOG_DIR . '/nfwlog/cache/malscan.log', time() . ": [AX] ERROR: security nonces do not match\n", FILE_APPEND );
	// Nonces do not match:
	echo '1';
}
wp_die();
/* ------------------------------------------------------------------ */
// EOF
