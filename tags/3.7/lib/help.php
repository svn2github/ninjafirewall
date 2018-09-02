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

if (! defined( 'NFW_ENGINE_VERSION' ) ) {
	header('HTTP/1.1 404 Not Found');
	header('Status: 404 Not Found');
	exit;
}

// Contextual help - choose Help on the top right
// of the admin panel to preview this.

/* ------------------------------------------------------------------ */ // i18n+

function help_nfsubmain() {

	// Overview menu help :

	get_current_screen()->add_help_tab( array(
		'id'        => 'main01',
		'title'     => __('Overview', 'ninjafirewall'),
		'content'   => '<br />' . __('This is the Overview page; it shows information about the firewall status. We recommend you keep an eye on it because, in case of problems, all possible errors and warnings will be displayed here.', 'ninjafirewall') . '<br />&nbsp;'
	) );
	get_current_screen()->set_help_sidebar(
		'<p><strong>' . __( 'For more information:', 'ninjafirewall') . '</strong></p>' .

		'<p><a href="https://blog.nintechnet.com/securing-wordpress-with-a-web-application-firewall-ninjafirewall/">'. __('Securing WordPress with NinjaFirewall.', 'ninjafirewall') . '</a></p>' .

		'<p><a href="https://nintechnet.com/ninjafirewall/wp-edition/help/">'. __('Installation, help and troubleshooting', 'ninjafirewall') . '</a></p>' .

		'<p><a href="http://wordpress.org/support/plugin/ninjafirewall/">' . __( 'Support Forum', 'ninjafirewall') . '</a></p>' .
		'<p>'. __('Updates via Twitter', 'ninjafirewall') . '<br /><a href="https://twitter.com/nintechnet"><img border="0" src="' . plugins_url( '/images/twitter_ntn.png', __DIR__ ) . '" width="116" height="28"></a></p>'
	);

}

/* ------------------------------------------------------------------ */ // i18n+

function help_nfsubstat() {

	// Stats menu help :

	get_current_screen()->add_help_tab( array(
		'id'        => 'help01',
		'title'     => __('Monthly stats', 'ninjafirewall'),
		'content'   => '<br />'.
			__('Statistics are taken from the current log. It is rotated on the first day of each month.', 'ninjafirewall') .
			'<br />'.
			sprintf( __('You can view the log by clicking on the <a href="%s">Firewall Log</a> menu.', 'ninjafirewall'), '?page=nfsublog')
	) );
	get_current_screen()->add_help_tab( array(
		'id'        => 'help02',
		'title'     => __('Benchmarks', 'ninjafirewall'),
		'content'   => '<br />'.
			__('Benchmarks show the time NinjaFirewall took, in seconds, to proceed each request it has blocked.', 'ninjafirewall')
	) );
}
/* ------------------------------------------------------------------ */ // i18n+

function help_nfsubopt() {

	// Firewall options menu help :

	get_current_screen()->add_help_tab( array(
		'id'        => 'opt01',
		'title'     =>  __('Firewall protection', 'ninjafirewall'),
		'content'   => '<br />' .
			sprintf( __('This option allows you to disable NinjaFirewall. It has basically the same effect as deactivating it from the <a href="%s">Plugins</a> menu page.', 'ninjafirewall'), admin_url() . 'plugins.php') .
			'<br />'.
			__('Your site will remain unprotected until you enable it again.', 'ninjafirewall')
	) );
	get_current_screen()->add_help_tab( array(
		'id'        => 'opt02',
		'title'     => __('Debugging mode', 'ninjafirewall'),
		'content'   => '<br />' .
			sprintf( __('In Debugging mode, NinjaFirewall will not block or sanitise suspicious requests but will only log them. The <a href="%s">Firewall Log</a> will display <code>DEBUG_ON</code> in the LEVEL column.', 'ninjafirewall'), '?page=nfsublog') .
			'<p>' . __('We recommend to run it in Debugging Mode for at least 24 hours after installing it on a new site and then to keep an eye on the firewall log during that time. If you notice a false positive in the log, you can simply use NinjaFirewall\'s Rules Editor to disable the security rule that was wrongly triggered.', 'ninjafirewall') . '</p>'
	) );
get_current_screen()->add_help_tab( array(
		'id'        => 'optipanon',
		'title'     => __('IP anonymization', 'ninjafirewall'),
		'content'   => '<p>'. __('This option will anonymize IP addresses in the firewall log by removing their last 3 characters.', 'ninjafirewall') .' '. __('It does not apply to private IP addresses and the Login Protection feature.', 'ninjafirewall') .'</p>'.
		'<p>'. __('Note that it will affect only IP addresses written to the firewall log after enabling this option.', 'ninjafirewall') .' '.	__('Also, if you are redirecting events to the syslog server (NinjaFirewall <font color="#21759B">WP+</font> Edition), IP addresses will be anonymized too.', 'ninjafirewall') .'</p>'
	) );
	get_current_screen()->add_help_tab( array(
		'id'        => 'opt03',
		'title'     =>  __('Error code and message to return', 'ninjafirewall'),
		'content'   => '<br />' .
			__('Lets you customize the HTTP error code returned by NinjaFirewall when blocking a dangerous request and the message to display to the user.' , 'ninjafirewall') . ' ' .
			__('You can use any HTML tags and 3 built-in variables:' , 'ninjafirewall') .
			'<li><code>%%REM_ADDRESS%%</code> : '. __('the blocked user IP.' , 'ninjafirewall') . '</li>
			<li><code>%%NUM_INCIDENT%%</code> : '. __('the unique incident number as it will appear in the firewall log "INCIDENT" column.' , 'ninjafirewall') . '</li>
			<li><code>%%NINJA_LOGO%%</code> : '. __('NinjaFirewall logo.' , 'ninjafirewall') . '</li>'
	) );
	list ( $major_current ) = explode( '.', NFW_ENGINE_VERSION );
	get_current_screen()->add_help_tab( array(
		'id'        => 'opt04',
		'title'     =>  __('Export/import configuration', 'ninjafirewall'),
		'content'   => '<br />' .
			sprintf( __('This options lets you export you current configuration or import it from another NinjaFirewall (WP Edition) installation. The imported file must match the major version of your current version (%s) otherwise it will be rejected. Note that importing will override all firewall rules and options.', 'ninjafirewall'), (int) $major_current .'.x' ) .
			'<p><img src="' . plugins_url( '/images/glyphicons-warning.png', __DIR__ ) . '">&nbsp;<span class="description">' .
			__('"File Check" configuration will not be exported/imported.', 'ninjafirewall') . '</span></p>'
	) );

	get_current_screen()->add_help_tab( array(
		'id'        => 'opt06',
		'title'     =>  __('Configuration backup', 'ninjafirewall'),
		'content'   => '<br />' .
		__('NinjaFirewall will automatically backup its configuration (options, policies and rules) everyday for the last 5 days. If you want to restore its configuration to an earlier date, select the corresponding file in the list.', 'ninjafirewall')

	) );
}
/* ------------------------------------------------------------------ */ // i18n+

function help_nfsubpolicies() {

	// Firewall policies menu help :

	// Show this text only if we are running in "Full WAF" mode:
	if ( defined('NFW_WPWAF') ) {
		$res= '';
	} else {
		$res = sprintf( __('Keep in mind, however, that the Firewall Policies apply to any PHP scripts located inside the %s directory and its sub-directories, and not only to your WordPress index page.', 'ninjafirewall'), '<code>' . ABSPATH . '</code>');
	}

	get_current_screen()->add_help_tab( array(
		'id'        => 'policies01',
		'title'     => __('Policies overview', 'ninjafirewall'),
		'content'   => '<br />' .
			sprintf( __('Because NinjaFirewall sits in front of WordPress, it can hook, scan and sanitise all PHP requests, HTTP variables, headers and IPs before they reach your blog: <code><a href="%s">$_GET</a></code>, <code><a href="%s">$_POST</a></code>, <code><a href="%s">$_COOKIE</a></code>, <code><a href="%s">$_REQUEST</a></code>, <code><a href="%s">$_FILES</a></code>, <code><a href="%s">$_SERVER</a></code> in HTTP and/or HTTPS mode.', 'ninjafirewall'), 'http://www.php.net/manual/en/reserved.variables.get.php', 'http://www.php.net/manual/en/reserved.variables.post.php', 'http://www.php.net/manual/en/reserved.variables.cookies.php', 'http://www.php.net/manual/en/reserved.variables.request.php', 'http://www.php.net/manual/en/reserved.variables.files.php', 'http://php.net/manual/en/reserved.variables.server.php') .
			'<br />' .
			__('Use the options below to enable, disable or to tweak these rules according to your needs.', 'ninjafirewall') .
			'<br />' .
			$res .
			'<br />'
	) );
	get_current_screen()->add_help_tab( array(
		'id'        => 'policies02',
		'title'     =>  __('Scan and Sanitise', 'ninjafirewall'),
		'content'   => '<br />'.
		__('You can choose to scan and reject dangerous content but also to sanitise requests and variables. Those two actions are different and can be combined together for better security.', 'ninjafirewall') .
		'<li>'. __('Scan : if anything suspicious is detected, NinjaFirewall will block the request and return an HTTP error code and message (defined in the "Firewall Options" page). The user request will fail and the connection will be closed immediately.', 'ninjafirewall') .'</li>
		<li>'. sprintf( __('Sanitise : this option will not block but sanitise the user request by escaping characters that can be used to exploit vulnerabilities (%s) and replacing <code>&lt;</code> and <code>&gt;</code> with their corresponding HTML entities (<code>&amp;lt;</code>, <code>&amp;gt;</code>). If it is a variable, i.e. <code>?name=value</code>, both its name and value will be sanitised.', 'ninjafirewall'), '<code>\'</code>, <code>"</code>, <code>\\</code>, <code>\n</code>, <code>\r</code>, <code>`</code>, <code>\x1a</code>, <code>\x00</code>, <code>*</code>, <code>?</code>') .'
		<br />' .
		__('This action will be performed when the filtering process is over, right before NinjaFirewall forwards the request to your PHP script.', 'ninjafirewall') . '
		<br />
		<br />
		<img src="' . plugins_url( '/images/glyphicons-warning.png', __DIR__ ) . '">&nbsp;<span class="description">'. __('If you enabled <code>POST</code> requests sanitising, articles and messages posted by your visitors could be corrupted with excessive backslashes or substitution characters.', 'ninjafirewall'). '</span></li>'
	) );
	get_current_screen()->add_help_tab( array(
		'id'			=> 'policies04',
		'title'		=> __('Firewall Policies', 'ninjafirewall'),
		'content'	=> '
		<div style="height:400px;">

		<!-- Basic Policies  -->
		<h3>' . __('Basic Policies', 'ninjafirewall'). '</h3>

		<strong>HTTP / HTTPS</strong>
		<li>' . __('Whether to filter HTTP and/or HTTPS traffic', 'ninjafirewall'). '</li>
		<br />
		<strong>' . __('Uploads', 'ninjafirewall'). '</strong>
		<li>' . __('File Uploads:', 'ninjafirewall'). '<span class="description"> ' . __('whether to allow/disallow file uploads.', 'ninjafirewall'). '</span></li>
		<li>' . __('Sanitise filenames:', 'ninjafirewall'). '<span class="description"> ' . __('any character that is not a letter <code>a-zA-Z</code>, a digit <code>0-9</code>, a dot <code>.</code>, a hyphen <code>-</code> or an underscore <code>_</code> will be removed from the filename and replaced with the substitution character.', 'ninjafirewall'). '</span></li>
		<br />
		<strong>WordPress</strong>
		<li>' . __('Whether to block direct access to PHP files located in specific WordPress directories.', 'ninjafirewall'). '</li>
		<li>' . __('Block user accounts creation', 'ninjafirewall'). ':<span class="description"> ' . __('enabling this policy will block any attempt (e.g., exploiting a vulnerability, using a backdoor etc) to create a user account. If you allow user registration, you should not enable it.', 'ninjafirewall'). '</span></li>
		<li>' . __('Protect against username enumeration:', 'ninjafirewall'). '<span class="description"> ' . __('it is possible to enumerate usernames either through the WordPress author archives, the REST API or the login page. Although this is not a vulnerability but a WordPress feature, some hackers use it to retrieve usernames in order to launch more accurate brute-force attacks. If it is a failed login attempt, NinjaFirewall will sanitise the error message returned by WordPress. If it is an author archives scan, it will invalidate it and redirect the user to the blog index page. Regarding the WP REST API, it will block the request immediately.', 'ninjafirewall'). '</span></li>
		<li>' . __('WordPress REST API:', 'ninjafirewall'). '<span class="description"> ' . __('it allows you to access your WordPress site\'s data through an easy-to-use HTTP REST API. Since WordPress 4.7, it is enabled by default. NinjaFirewall allows you to block any access to that API if you do not intend to use it.', 'ninjafirewall'). '</span></li>
		<li>' . __('WordPress XML-RPC API:', 'ninjafirewall'). '<span class="description"> ' . __('XML-RPC is a remote procedure call (RPC) protocol which uses XML to encode its calls and HTTP as a transport mechanism. WordPress has an XMLRPC API that can be accessed through the <code>xmlrpc.php</code> file. Since WordPress version 3.5, it is always activated and cannot be turned off. NinjaFirewall allows you to immediately block any access to that file, or only to block an access using the <code>system.multicall</code> method often used in brute-force amplification attacks or to block Pingbacks.', 'ninjafirewall'). '</span></li>
		<li>' . __('Block <code>POST</code> requests in the themes folder <code>/wp-content/themes</code>:', 'ninjafirewall'). '<span class="description"> ' . __('this option can be useful to block hackers from installing backdoor in the PHP theme files. However, because some custom themes may include an HTML form (contact, search form etc), this option is not enabled by default.', 'ninjafirewall'). '</span></li>
		<li>' . __('Force SSL for admin and logins <code>FORCE_SSL_ADMIN</code>:', 'ninjafirewall'). '<span class="description"> ' . __('enable this option when you want to secure logins and the admin area so that both passwords and cookies are never sent in the clear. Ensure that you can access your admin console from HTTPS before enabling this option, otherwise you will lock yourself out of your site!', 'ninjafirewall'). '</span></li>
		<li>' . __('Disable the plugin and theme editor <code>DISALLOW_FILE_EDIT</code>:', 'ninjafirewall'). '<span class="description"> ' . __('disabling the plugin and theme editor provides an additional layer of security if a hacker gains access to a well-privileged user account.', 'ninjafirewall'). '</span></li>
		<li>' . __('Disable plugin and theme update/installation <code>DISALLOW_FILE_MODS</code>:', 'ninjafirewall'). '<span class="description"> ' . __('this option will block users being able to use the plugin and theme installation/update functionality from the WordPress admin area. Setting this constant also disables the Plugin and Theme editor.', 'ninjafirewall'). '</span></li>

		<br />

		<!-- Intermediate Policies  -->
		<h3>' . __('Intermediate Policies', 'ninjafirewall'). '</h3>

		<strong>' . __('HTTP GET variable', 'ninjafirewall'). '</strong>
		<li>' . __('Whether to scan and/or sanitise the <code>GET</code> variable.', 'ninjafirewall'). '</li>
		<br />
		<strong>' . __('HTTP POST variable', 'ninjafirewall'). '</strong>
		<li>' . __('Whether to scan and/or sanitise the <code>POST</code> variable.', 'ninjafirewall'). '</li>
		<li>' . __('Decode Base64-encoded <code>POST</code> variable:', 'ninjafirewall'). '<span class="description"> ' . __('NinjaFirewall will decode and scan base64 encoded values in order to detect obfuscated malicious code. This option is only available for the <code>POST</code> variable.', 'ninjafirewall'). '</span></li>
		<br />
		<strong>' . __('HTTP REQUEST variable', 'ninjafirewall'). '</strong>
		<li>' . __('Whether to sanitise the <code>REQUEST</code> variable.', 'ninjafirewall'). '</li>
		<br />
		<strong>' . __('Cookies', 'ninjafirewall'). '</strong>
		<li>' . __('Whether to scan and/or sanitise cookies.', 'ninjafirewall'). '</li>
		<br />
		<strong>' . __('HTTP_USER_AGENT server variable', 'ninjafirewall'). '</strong>
		<li>' . __('Whether to scan and/or sanitise <code>HTTP_USER_AGENT</code> requests.', 'ninjafirewall'). '</li>
		<li>' . __('Block suspicious bots/scanners:', 'ninjafirewall'). '<span class="description"> ' . __('rejects some known bots, scanners and various malicious scripts attempting to access your blog.', 'ninjafirewall'). '</span></li>
		<br />
		<strong>' . __('HTTP_REFERER server variable', 'ninjafirewall'). '</strong>
		<li>' . __('Whether to scan and/or sanitise <code>HTTP_REFERER</code> requests.', 'ninjafirewall'). '</li>
		<li>' . __('Block POST requests that do not have an <code>HTTP_REFERER</code> header:', 'ninjafirewall'). '<span class="description"> ' . __('this option will block any <code>POST</code> request that does not have a Referrer header (<code>HTTP_REFERER</code> variable). If you need external applications to post to your scripts (e.g. Paypal IPN, WordPress WP-Cron...), you are advised to keep this option disabled otherwise they will likely be blocked. Note that <code>POST</code> requests are not required to have a Referrer header and, for that reason, this option is disabled by default.', 'ninjafirewall'). '</span></li>
		<br />
		<strong>IP</strong>
		<li>' . __('Block localhost IP in <code>GET/POST</code> requests:', 'ninjafirewall'). '<span class="description"> ' . __('this option will block any <code>GET</code> or <code>POST</code> request containing the localhost IP (127.0.0.1). It can be useful to block SQL dumpers and various hacker\'s shell scripts.', 'ninjafirewall'). '</span></li>
		<li>' . __('Block HTTP requests with an IP in the <code>HTTP_HOST</code> header:', 'ninjafirewall'). '<span class="description"> ' . sprintf( __('this option will reject any request using an IP instead of a domain name in the <code>Host</code> header of the HTTP request. Unless you need to connect to your site using its IP address, (e.g. %s), enabling this option will block a lot of hackers scanners because such applications scan IPs rather than domain names.', 'ninjafirewall'), 'http://' . htmlspecialchars($_SERVER['SERVER_ADDR']) . '/index.php'). '</span></li>
		<li>' . __('Scan traffic coming from localhost and private IP address spaces:', 'ninjafirewall'). '<span class="description"> ' . __('this option will allow the firewall to scan traffic from all non-routable private IPs (IPv4 and IPv6) as well as the localhost IP. We recommend to keep it enabled if you have a private network (2 or more servers interconnected).', 'ninjafirewall'). '</span></li>

		<br />

		<!-- Advanced Policies  -->
		<h3>' . __('Advanced Policies', 'ninjafirewall'). '</h3>

		<strong>' . __('HTTP response headers', 'ninjafirewall'). '</strong>
		<br />
		' . __('In addition to filtering incoming requests, NinjaFirewall can also hook the HTTP response in order to alter its headers. Those modifications can help to mitigate threats such as XSS, phishing and clickjacking attacks.', 'ninjafirewall'). '
		<br />
		<li>' . __('Set <code>X-Content-Type-Options</code> to protect against MIME type confusion attacks:', 'ninjafirewall'). '<span class="description"> ' . __('this header will send the nosniff value to instruct the browser to disable content or MIME sniffing and to use the content-type returned by the server. Some browsers try to guess (sniff) and override the content-type by looking at the content itself which, in some cases, could lead to security issues such as MIME Confusion Attacks.', 'ninjafirewall'). '</span></li>
		<li>' . __('Set <code>X-Frame-Options</code> to protect against clickjacking attempts:', 'ninjafirewall'). '<span class="description"> ' . __('this header indicates a policy whether a browser must not allow to render a page in a &lt;frame&gt; or &lt;iframe&gt;. Hosts can declare this policy in the header of their HTTP responses to prevent clickjacking attacks, by ensuring that their content is not embedded into other pages or frames. NinjaFirewall accepts two different values:', 'ninjafirewall'). '
			<ul>
				<li><code>SAMEORIGIN</code>: ' . __('a browser receiving content with this header must not display this content in any frame from a page of different origin than the content itself.', 'ninjafirewall'). '</li>
				<li><code>DENY</code>: ' . __('a browser receiving content with this header must not display this content in any frame.', 'ninjafirewall'). '</li>
			</ul>
			</span>
			' . __('NinjaFirewall does not support the <code>ALLOW-FROM</code> value.', 'ninjafirewall'). '
			<br />' .
			__('Since v3.1.3, WordPress sets this value to <code>SAMEORIGIN</code> for the administrator and the login page only.', 'ninjafirewall'). '</li>
		<li>' . __('Set <code>X-XSS-Protection</code> (IE/Edge, Chrome, Opera and Safari browsers):', 'ninjafirewall'). '<span class="description"> ' . __('this header allows browsers to identify and block XSS attacks by preventing malicious scripts from executing. It is enabled by default on all compatible browsers.', 'ninjafirewall'). '</span></li>'.
		'<p><img src="' . plugins_url( '/images/glyphicons-warning.png', __DIR__ ) . '">&nbsp;<span class="description">' . __("If a visitor disabled their browser's XSS filter, you cannot re-enable it with that option.", 'ninjafirewall'). '</span></p>'.

		'<li>' . __('Force <code>HttpOnly</code> flag on all cookies to mitigate XSS attacks:', 'ninjafirewall'). '<span class="description"> ' . __('adding this flag to cookies helps to mitigate the risk of cross-site scripting by preventing them from being accessed through client-side scripts. NinjaFirewall can hook all cookies sent by your blog, its plugins or any other PHP script, add the <code>HttpOnly</code> flag if it is missing, and re-inject those cookies back into your server HTTP response headers right before they are sent to your visitors. Note that WordPress sets that flag on the logged in user cookies only.', 'ninjafirewall'). '</span></li>
		<p><img src="' . plugins_url( '/images/glyphicons-warning.png', __DIR__ ) . '">&nbsp;<span class="description">' . __('If your PHP scripts send cookies that need to be accessed from JavaScript, you should keep that option disabled.', 'ninjafirewall'). '</span></p>
		<li>' . __('Set <code>Strict-Transport-Security</code> (HSTS) to enforce secure connections to the server:', 'ninjafirewall'). '<span class="description"> ' . __('this policy enforces secure HTTPS connections to the server. Web browsers will not allow the user to access the web application over insecure HTTP protocol. It helps to defend against cookie hijacking and Man-in-the-middle attacks. Most recent browsers support HSTS headers.', 'ninjafirewall'). '</span></li>
		<li>' . __('Set <code>Content-Security-Policy</code>:', 'ninjafirewall'). '<span class="description"> ' . __('this policy helps to mitigate threats such as XSS, phishing and clickjacking attacks. It covers JavaScript, CSS, HTML frames, web workers, fonts, images, objects (Java, ActiveX, audio and video files), and other HTML5 features.', 'ninjafirewall'). ' ' . __('NinjaFirewall lets you configure the CSP policy separately for the frontend (blog, website) and the backend (WordPress admin dashboard).', 'ninjafirewall') . '</span></li>
		<li>' . __('Set <code>Referrer-Policy</code>:', 'ninjafirewall'). '<span class="description"> ' . __('this HTTP header governs which referrer information, sent in the Referer header, should be included with requests made.', 'ninjafirewall') . '</span></li>

		<br />
		<strong>PHP</strong>
		<li>' . __('Block PHP built-in wrappers:', 'ninjafirewall'). '<span class="description"> ' . __('PHP has several wrappers for use with the filesystem functions. It is possible for an attacker to use them to bypass firewalls and various IDS to exploit remote and local file inclusions. This option lets you block any script attempting to pass a <code>expect://</code>, <code>file://</code>, <code>phar://</code>, <code>php://</code>, <code>zip://</code> or <code>data://</code> stream inside a <code>GET</code> or <code>POST</code> request, cookies, user agent and referrer variables.', 'ninjafirewall'). '</span></li>
		<li>' . sprintf( __('Block serialized PHP objects:', 'ninjafirewall'). '<span class="description"> ' . __('Object Serialization is a PHP feature used by many applications to generate a storable representation of a value. However, some insecure PHP applications and plugins can turn that feature into a critical vulnerability called <a href="%s">PHP Object Injection</a>. This option can block serialized PHP objects found inside a a <code>GET</code> or <code>POST</code> request, cookies, user agent and referrer variables.', 'ninjafirewall'), 'https://www.owasp.org/index.php/PHP_Object_Injection'). '</span></li>
		<li>' . __('Hide PHP notice and error messages:', 'ninjafirewall'). '<span class="description"> ' . __('this option lets you hide errors returned by your scripts. Such errors can leak sensitive informations which can be exploited by hackers.', 'ninjafirewall'). '</span></li>
		<li>' . __('Sanitise <code>PHP_SELF</code>, <code>PATH_TRANSLATED</code>, <code>PATH_INFO</code>:', 'ninjafirewall'). '<span class="description"> ' . __('this option can sanitise any dangerous characters found in those 3 server variables to prevent various XSS and database injection attempts.', 'ninjafirewall'). '</span></li>
		<br />
		<strong>' . __('Various', 'ninjafirewall'). '</strong>
		<li>' . sprintf( __('Block the <code>DOCUMENT_ROOT</code> server variable (%s) in HTTP requests:', 'ninjafirewall'), '<code>' . $_SERVER['DOCUMENT_ROOT'] . '</code>'). '<span class="description"> ' . __('this option will block scripts attempting to pass the <code>DOCUMENT_ROOT</code> server variable in a <code>GET</code> or <code>POST</code> request. Hackers use shell scripts that often need to pass this value, but most legitimate programs do not.', 'ninjafirewall'). '</span></li>
		<li>' . __('Block ASCII character 0x00 (NULL byte):', 'ninjafirewall'). '<span class="description"> ' . __('this option will reject any <code>GET</code> or <code>POST</code> request, <code>HTTP_USER_AGENT</code>, <code>REQUEST_URI</code>, <code>PHP_SELF</code>, <code>PATH_INFO</code>, <code>HTTP_REFERER</code> variables containing the ASCII character 0x00 (NULL byte). Such a character is dangerous and should always be rejected.', 'ninjafirewall'). '</span></li>
		<li>' . __('Block ASCII control characters 1 to 8 and 14 to 31:', 'ninjafirewall'). '<span class="description"> ' . __('this option will reject any <code>GET</code> or <code>POST</code> request, <code>HTTP_USER_AGENT</code>, <code>HTTP_REFERER</code> variables containing ASCII characters from 1 to 8 and 14 to 31.', 'ninjafirewall'). '</span></li>

		</div>'
	) );
	get_current_screen()->add_help_tab( array(
		'id'        => 'policies03',
		'title'     => __('Users Whitelist', 'ninjafirewall'),
		'content'   => '<br />'.
		sprintf( __('By default, any logged in WordPress administrator will not be blocked by NinjaFirewall. You can also add any logged in users to the whitelist (make sure you trust them all before doing so).<br />This feature applies to all Firewall Policies listed below, except <code>FORCE_SSL_ADMIN</code>, <code>DISALLOW_FILE_EDIT</code>, <code>DISALLOW_FILE_MODS</code> options and the <a href="%s">Login Protection</a> which, if enabled, are always enforced.', 'ninjafirewall'), '?page=nfsubloginprot').
		'<br />'
	) );

}
/* ------------------------------------------------------------------ */ // i18n+

function help_nfsubfileguard() {

	// File Guard :
	get_current_screen()->add_help_tab( array(
		'id'        => 'fileguard01',
		'title'     => __('File Guard', 'ninjafirewall'),
		'content'   => '<br/>' .
			__('File Guard can detect, in real-time, any access to a PHP file that was recently modified or created, and alert you about this.', 'ninjafirewall') .
			'<br />' .
			__('If a hacker uploaded a shell script to your site (or injected a backdoor into an already existing file) and tried to directly access that file using his browser or a script, NinjaFirewall would hook the HTTP request and immediately detect that the file was recently modified/created. It would send you a detailed alert (script name, IP, request, date and time). Alerts will be sent to the contact email address defined in the "Event Notifications" menu.', 'ninjafirewall') .
			'<p>' . __('If you do not want to monitor a folder, you can exclude its full path or a part of it (e.g., <code>/var/www/public_html/cache/</code> or <code>/cache/</code> etc). NinjaFirewall will compare this value to the <code>$_SERVER["SCRIPT_FILENAME"]</code> server variable and, if it matches, will ignore it.', 'ninjafirewall') . '</p>' .
			__('Multiple values must be comma-separated (e.g., <code>/foo/bar/,/cache/</code>).', 'ninjafirewall') .'</li>' .
			'<p><img src="' . plugins_url( '/images/glyphicons-warning.png', __DIR__ ) . '">&nbsp;<span class="description">' . __('File Guard real-time detection is a totally unique feature, because NinjaFirewall is the only plugin for WordPress that can hook HTTP requests sent to any PHP script, even if that script is not part of the WordPress package (third-party software, shell script, backdoor etc).', 'ninjafirewall') . '</span></p>'
	) );
}
/* ------------------------------------------------------------------ */ // i18n+
function help_nfsubnetwork() {

	// Network (multisite version only) :
	get_current_screen()->add_help_tab( array(
		'id'        => 'network01',
		'title'     => __('Network', 'ninjafirewall'),
		'content'   => '<br />' .
			__('Even if NinjaFirewall administration menu is only available to the Super Admin (from the main site), you can still display its status to all sites in the network by adding a small NinjaFirewall icon to their admin bar. It will be visible only to the administrators of those sites.', 'ninjafirewall') .
			'<br />' .
			__('It is recommended to enable this feature as it is the only way to know whether the sites in your network are protected and if NinjaFirewall installation was successful.', 'ninjafirewall') .
			'<br />'.
			__('Note that when it is disabled, the icon still remains visible to you, the Super Admin.', 'ninjafirewall')
	) );
}
/* ------------------------------------------------------------------ */ // i18n+

function help_nfsubfilecheck() {

	// File check menu help :
	get_current_screen()->add_help_tab( array(
		'id'        => 'filecheck01',
		'title'     => __('File Check', 'ninjafirewall'),
		'content'   => '<p>'. __('File Check lets you perform file integrity monitoring upon request or on a specific interval.', 'ninjafirewall') .
			'<br />' .
			__('You need to create a snapshot of all your files and then, at a later time, you can scan your system to compare it with the previous snapshot. Any modification will be immediately detected: file content, file permissions, file ownership, timestamp as well as file creation and deletion.', 'ninjafirewall') .'</p>' .
			'<li>'. sprintf( __('Create a snapshot of all files stored in that directory: by default, the directory is set to WordPress <code>ABSPATH</code> (%s)', 'ninjafirewall'), '<code>' . ABSPATH . '</code>') .'</li>'.
			'<li>'.  __('Exclude the following files/folders: you can enter a directory or a file name (e.g., <code>/foo/bar/</code>), or a part of it (e.g., <code>foo</code>). Or you can exclude a file extension (e.g., <code>.css</code>).', 'ninjafirewall') .
			'<br />' .
			__('Multiple values must be comma-separated (e.g., <code>/foo/bar/,.css,.png</code>).', 'ninjafirewall') .'</li>' .
			'<li>'.  __('Do not follow symbolic links: by default, NinjaFirewall will not follow symbolic links.', 'ninjafirewall') .'</li>'
	) );

	get_current_screen()->add_help_tab( array(
		'id'        => 'filecheck02',
		'title'     => __('Scheduled scans', 'ninjafirewall'),
		'content'   => '<p>'. __('NinjaFirewall can scan your system on a specific interval (hourly, twicedaily or daily).', 'ninjafirewall').
			'<br />'.
			__('It can either send you a scan report only if changes are detected, or always send you one after each scan.', 'ninjafirewall').
			'<br />'.
			__('Reports will be sent to the contact email address defined in the "Event Notifications" menu.', 'ninjafirewall'). '</p>'.

			'<p><img src="' . plugins_url( '/images/glyphicons-warning.png', __DIR__ ) . '">&nbsp;<span class="description">'. sprintf( __('Scheduled scans rely on <a href="%s">WordPress pseudo cron</a> which works only if your site gets sufficient traffic.', 'ninjafirewall'), 'http://codex.wordpress.org/Category:WP-Cron_Functions') . '</span></p>'
	) );

}

/* ------------------------------------------------------------------ */ // i18n+

function help_nfsubevent() {

	// Event Notifications menu help :

	get_current_screen()->add_help_tab( array(
		'id'        => 'log01',
		'title'     => __('Event Notifications', 'ninjafirewall'),
		'content'   => '<br />' . __('NinjaFirewall can alert you by email on specific events triggered within your blog. They include installations, updates, activations etc, as well as users login and modification of any administrator account in the database. Some of those alerts are enabled by default and it is highly recommended to keep them enabled. It is not unusual for a hacker, after breaking into your WordPress admin console, to install or just to upload a backdoored plugin or theme in order to take full control of your website.', 'ninjafirewall')
	) );
}
/* ------------------------------------------------------------------ */ // i18n+

function help_nfsublogin() {

	// Login protection menu help :

	get_current_screen()->add_help_tab( array(
		'id'        => 'login01',
		'title'     => __('Login Protection', 'ninjafirewall'),
		'content'   => '
		<div style="height:250px;">

		<p>' . __('By processing incoming HTTP requests before your blog and any of its plugins, NinjaFirewall is the only plugin for WordPress able to protect it against very large brute-force attacks, including distributed attacks coming from several thousands of different IPs.', 'ninjafirewall') .

		'<p>' . __('You can choose two different types of protection: a password or a captcha. You can enable the protection only if an attack is detected or to keep it always activated.', 'ninjafirewall') . '</p>

		<strong>' . __('Yes, if under attack:', 'ninjafirewall') . '</strong>
		<br />' .
		__('The protection will be triggered when too many login attempts are detected, regardless of the offending IP. It blocks the attack instantly and prevents it from reaching WordPress, but still allows you to access your administration console using either the predefined username/password combination or the captcha code. ', 'ninjafirewall') . '
		<br />
		<strong>' . __('Always ON:', 'ninjafirewall') . '</strong>
		<br />'.
		__('NinjaFirewall will always enforce the HTTP authentication or captcha implementation each time you access the login page.', 'ninjafirewall') . '
		<br />
		<br />
		<strong>' . __('Type of protection:', 'ninjafirewall') . '</strong>
		<p>' . __('<b>Password:</b> It password-protects the login page. NinjaFirewall uses its own very fast authentication scheme and it is compatible with any HTTP server (Apache, Nginx, Lighttpd etc).', 'ninjafirewall') . '</p>
		<p>' . __('<b>Captcha:</b> It will display a 5-character captcha code.', 'ninjafirewall') . '</p>
		<p><b>' . __('Bot protection:', 'ninjafirewall') . '</b>
		<br />' . __('NinjaFirewall will attempt to block bots and scripts immediately, i.e., even before they start a brute-force attack.', 'ninjafirewall') . '</p>

		<br />&nbsp;
		</div>'
	) );

	get_current_screen()->add_help_tab( array(
		'id'        => 'login02',
		'title'     => __('AUTH log', 'ninjafirewall'),
		'content'   => '
		<div style="height:250px;">
		<p>' . __('NinjaFirewall can write to the server Authentication log when the brute-force protection is triggered. This can be useful to the system administrator for monitoring purposes or banning IPs at the server level.', 'ninjafirewall') . '
		<br />' .
		__('If you have a shared hosting account, keep this option disabled as you do not have any access to the server\'s logs.', 'ninjafirewall') .
		'<br />' .
		__('On Debian-based systems, the log is located in <code>/var/log/auth.log</code>, and on Red Hat-based systems in <code>/var/log/secure</code>. The logline uses the following format:', 'ninjafirewall') .
		'<p><code>ninjafirewall[<font color="red">AA</font>]: Possible brute-force attack from <font color="red">BB</font> on <font color="red">CC</font> (<font color="red">DD</font>). Blocking access for <font color="red">EE</font>mn.</code><p>
		<ul>
			<li>' . __('AA: the process ID (PID).', 'ninjafirewall') . '</li>
			<li>' . __('BB: the user IPv4 or IPv6 address.', 'ninjafirewall') . '</li>
			<li>' . __('CC: the blog (sub-)domain name.', 'ninjafirewall') . '</li>
			<li>' . __('DD: the target: it can be either <code>wp-login.php</code> or <code>XML-RPC API</code>.', 'ninjafirewall') . '</li>
			<li>' . __('EE: the time, in minutes, the protection will remain active.', 'ninjafirewall') . '</li>
		</ul>'.
		__('Sample loglines:', 'ninjafirewall') .
		'<br />
		<textarea class="small-text code" style="width:100%;height:80px;" wrap="off">Aug 31 01:40:35 www ninjafirewall[6191]: Possible brute-force attack from 172.16.0.1 on mysite.com (wp-login.php). Blocking access for 5mn.'. "\n" . 'Aug 31 01:45:28 www ninjafirewall[6192]: Possible brute-force attack from fe80::6e88:14ff:fe3e:86f0 on blog.domain.com (XML-RPC API). Blocking access for 25mn.</textarea>
		<p><img src="' . plugins_url( '/images/glyphicons-warning.png', __DIR__ ) . '">&nbsp;<span class="description">' . sprintf( __('Be careful if you are behind a load balancer, reverse-proxy or CDN because the Login Protection feature will always record the <code>REMOTE_ADDR</code> IP. If you have an application parsing the AUTH log in order to ban IPs (e.g. Fail2ban), you <strong>must</strong> setup your HTTP server to forward the correct IP (or use the <code><a href="%s">.htninja</a></code> file), otherwise you will likely block legitimate users.', 'ninjafirewall'), 'https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja') . '</span></p>
		</div>'
	) );


}
/* ------------------------------------------------------------------ */ // i18n+

function help_nfsublog() {

	// Firewall log menu help :

	get_current_screen()->add_help_tab( array(
		'id'        => 'log01',
		'title'     => __('Firewall Log', 'ninjafirewall'),
		'content'   => '<br />'.
			__('The firewall log displays blocked and sanitised requests as well as some useful information. It has 6  columns:', 'ninjafirewall') . '
			<li>' . __('DATE : date and time of the incident.', 'ninjafirewall') . '</li>
			<li>' . __('INCIDENT : unique incident number/ID as it was displayed to the blocked user.', 'ninjafirewall') . '</li>
			<li>' . __('LEVEL : level of severity (<code>CRITICAL</code>, <code>HIGH</code> or <code>MEDIUM</code>), information (<code>INFO</code>, <code>UPLOAD</code>) and debugging mode (<code>DEBUG_ON</code>).', 'ninjafirewall') . '</li>
			<li>' . __('RULE : reference of the NinjaFirewall built-in security rule that triggered the action. A hyphen (<code>-</code>) instead of a number means it was a rule from the "Firewall Policies" page.', 'ninjafirewall') . '</li>
			<li>' . __('IP : the user IPv4 or IPv6 address.', 'ninjafirewall') . '</li>
			<li>' . __('REQUEST : the HTTP request including offending variables and values as well as the reason the action was logged.', 'ninjafirewall') . '</li>'
	) );

	get_current_screen()->add_help_tab( array(
		'id'        => 'log02',
		'title'     => __('Auto-delete log', 'ninjafirewall'),
		'content'   => '<br />'.
			__('This options lets you configure NinjaFirewall to delete its old logs automatically. By default, logs are never deleted, <b>even when uninstall NinjaFirewall</b>. Leave this value to <code>0</code> if you don\'t want to delete old logs.', 'ninjafirewall'). '</p>'
	) );

	// Centralized Logging tab:
	get_current_screen()->add_help_tab( array(
		'id'        => 'log03',
		'title'     => __('Centralized Logging', 'ninjafirewall'),
		'content'   =>
			'<p>'. __('Centralized Logging lets you remotely access the firewall log of all your NinjaFirewall protected websites from one single installation. You do not need any longer to log in to individual servers to analyse your log data.', 'ninjafirewall') .	' ' . sprintf( __('<a href="%s">Consult our blog</a> for more info about it.', 'ninjafirewall'), 'https://blog.nintechnet.com/centralized-logging-with-ninjafirewall/' ) . '</p>' .
			'<li>' .	 __('Enter your public key (optional): This is the public key that was created from your main server.', 'ninjafirewall') . '</li>' .

			'<p><img src="' . plugins_url( '/images/glyphicons-warning.png', __DIR__ ) . '">&nbsp;<span class="description">'.
			__('Centralized Logging will keep working even if NinjaFirewall is disabled. Delete your public key below if you want to disable it.', 'ninjafirewall') .
			'</span></p>'
	) );

	// GDPR compliance tab:
	get_current_screen()->add_help_tab( array(
		'id'        => 'log04',
		'title'     => __('GDPR Compliance', 'ninjafirewall'),
		'content'   =>
			'<p>'.  __('NinjaFirewall is compliant with the General Data Protection Regulation (GDPR). For more info, please visit our blog:', 'ninjafirewall') .' <a href="https://blog.nintechnet.com/ninjafirewall-general-data-protection-regulation-compliance/">https://blog.nintechnet.com/ninjafirewall-general-data-protection-regulation-compliance/</a>'.
			'</p>'
	) );

}
/* ------------------------------------------------------------------ */ // i18n+

function help_nfsublivelog() {

	// Firewall Live Log menu help :

	get_current_screen()->add_help_tab( array(
		'id'        => 'log01',
		'title'     => __('Live Log', 'ninjafirewall'),
		'content'   =>
			'<p>' .	__('Live Log lets you watch your blog traffic in real time, just like the Unix <code>tail -f</code> command. Note that requests sent to static elements like JS/CSS files and images are not managed by NinjaFirewall.', 'ninjafirewall') .'</p>

			<p>' . __('You can enable/disable the monitoring process, change the refresh rate, clear the screen, enable automatic vertical scrolling, change the log format, select which traffic you want to view (HTTP/HTTPS) and the timezone.', 'ninjafirewall') .' '. __('You can also apply filters to include or exclude files and folders (REQUEST_URI).', 'ninjafirewall') .
			'</p>

			<p>' . __('Live Log does not make use of any WordPress core file (e.g., <code>admin-ajax.php</code>). It communicates directly with the firewall without loading WordPress bootstrap. Consequently, it is fast, lightweight and it should not affect your server load, even if you set its refresh rate to the lowest value.', 'ninjafirewall') .	'</p>

			<p><img src="' . plugins_url( '/images/glyphicons-warning.png', __DIR__ ) . '">&nbsp;<span class="description">' . __('If you are using the optional <code>.htninja</code> configuration file to whitelist your IP, the Live Log feature will not work.', 'ninjafirewall') . '
		</span></p>'
	) );
	get_current_screen()->add_help_tab( array(
		'id'        => 'log02',
		'title'     => __('Log Format', 'ninjafirewall'),
		'content'   => '<p>'. __('You can easily customize the log format. Possible values are:', 'ninjafirewall') .'</p>' .
			'<li>'. __('<code>%time</code>: the server date, time and timezone.', 'ninjafirewall') . '</li>' .
			'<li>'. __('<code>%name</code>: authenticated user (HTTP basic auth), if any.', 'ninjafirewall') . '</li>' .
			'<li>'. __('<code>%client</code>: the client REMOTE_ADDR. If you are behind a load balancer or CDN, this will be its IP.', 'ninjafirewall') . '</li>' .
			'<li>'. __('<code>%method</code>: HTTP method (e.g., GET, POST).', 'ninjafirewall') . '</li>' .
			'<li>'. __('<code>%uri</code>: the URI which was given in order to access the page (REQUEST_URI).', 'ninjafirewall') . '</li>' .
			'<li>'. __('<code>%referrer</code>: the referrer (HTTP_REFERER), if any.', 'ninjafirewall') . '</li>' .
			'<li>'. __('<code>%ua</code>: the user-agent (HTTP_USER_AGENT), if any.', 'ninjafirewall') . '</li>' .
			'<li>'. __('<code>%forward</code>: HTTP_X_FORWARDED_FOR, if any. If you are behind a load balancer or CDN, this will likely be the visitor true IP.', 'ninjafirewall') . '</li>' .
			'<li>'. __('<code>%host</code>: the requested host (HTTP_HOST), if any.', 'ninjafirewall') . '</li>' .
			__('Additionally, you can include any of the following characters: <code>"</code>, <code>%</code>, <code>[</code>, <code>]</code>, <code>space</code> and lowercase letters <code>a-z</code>.', 'ninjafirewall')
	) );
}

/* ------------------------------------------------------------------ */ // i18n+

function help_nfsubedit() {

	// Firewall Rules Editor menu help :

	get_current_screen()->add_help_tab( array(
		'id'        => 'editor01',
		'title'     => __('Rules Editor', 'ninjafirewall'),
		'content'   => '<br />' .
			__('Besides the "Firewall Policies", NinjaFirewall includes also a large set of built-in rules used to protect your blog against the most common vulnerabilities and hacking attempts. They are always enabled and you cannot edit them, but if you notice that your visitors are wrongly blocked by some of those rules, you can use the Rules Editor below to disable them individually:', 'ninjafirewall') . '
			<br />
			<li>'. __('Check your firewall log and find the rule ID you want to disable (it is displayed in the <code>RULE</code> column).', 'ninjafirewall') . '</li>
			<li>'. __('Select its ID from the enabled rules list below and click the "Disable it" button.', 'ninjafirewall') . '</li>
			<br />
			<span class="description">'. __('Note: if the <code>RULE</code> column from your log shows a hyphen <code>-</code> instead of a number, that means that the rule can be changed in the "Firewall Policies" page.', 'ninjafirewall') . '</span>'
	) );

	get_current_screen()->add_help_tab( array(
		'id'        => 'editor02',
		'title'     => __('Credits', 'ninjafirewall'),
		'content'   =>
			'<p>' . __('NinjaFirewall security rules protect against many vulnerabilities. Some of them were reported by the following companies, individuals or mailing lists:', 'ninjafirewall') . '<p>
			<table cellpadding="2" cellspacing="3">
				<tr>
					<th scope="row" style="text-align:left">g0blin Research</th><td>https://g0blin.co.uk/</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">HomeLab IT</th><td>https://homelab.it/</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">Klikki Oy</th><td>https://klikki.fi/</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">Netsparker</th><td>https://netsparker.com/</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">Packet Storm</th><td>https://packetstormsecurity.com/</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">Plugin Vulnerabilities</th><td>https://pluginvulnerabilities.com/</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">Pritect Network</th><td>http://pritect.net/</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">SecLists.Org</th><td>http://seclists.org/ (fulldisclosure &amp; oss-sec)</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">SecurityFocus</th><td>http://securityfocus.com/</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">Sucuri</th><td>https://sucuri.net/</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">Summer Of Pwnage</th><td>https://sumofpwn.nl/</td>
				</tr>
				<tr>
					<th scope="row" style="text-align:left">WordPress Hütte</th><td>http://wphutte.com/</td>
				</tr>
			</table>
			</span>'
	) );

}

/* ------------------------------------------------------------------ */ // i18n+

function help_nfsubupdates() {

	// Firewall Updates menu help :

	get_current_screen()->add_help_tab( array(
		'id'        => 'updates01',
		'title'     => __('Updates', 'ninjafirewall'),
		'content'   => '<p>'.
		__('To get the most efficient protection, you can ask NinjaFirewall to automatically update its security rules.', 'ninjafirewall') .
		'<br />' .
		__('Each time a new vulnerability is found in WordPress or one of its plugins/themes, a new set of security rules will be made available to protect against such vulnerability. Updates can be checked as often as daily, twice daily or even hourly.', 'ninjafirewall') .
		'<br />' .
		__('Only security rules will be downloaded. If a new version of NinjaFirewall (including new files, options and features) was available, it would have to be updated from the dashboard plugins menu as usual.', 'ninjafirewall') .
		'</p><p>' .
		__('We recommend to enable this feature, as it is the <strong>only way to keep your WordPress secure</strong> against new vulnerabilities.', 'ninjafirewall') . '</p>'
	) );
}

/* ------------------------------------------------------------------ */
// EOF
