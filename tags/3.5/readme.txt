=== NinjaFirewall (WP Edition) ===
Contributors: nintechnet, bruandet
Tags: firewall, security, WAF, antivirus, protection, malware, admin, attack, backdoor, botnet, brute force,  denial, hack, hhvm, infection, injection, login, nginx, nintechnet, ninjafirewall, palomuuri, pare-feu, phishing, prevention, sécurité, sécuriser, seguridad, seguranca, sicherheit, sicurezza, veiligheid, shellshock, soaksoak, sqli, trojan, user enumeration, virus, Web application firewall, widget, wp-login, XML-RPC, xmlrpc, XSS
Requires at least: 3.3.0
Tested up to: 4.7
Stable tag: 3.5
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A true Web Application Firewall to protect and secure WordPress.

== Description ==

= A true Web Application Firewall =

NinjaFirewall (WP Edition) is a true Web Application Firewall. Although it can be installed and configured just like a plugin, it is a stand-alone firewall that sits in front of WordPress.

It allows any blog administrator to benefit from very advanced and powerful security features that usually aren't available at the WordPress level, but only in security applications such as the Apache [ModSecurity](http://www.modsecurity.org/ "") module or the PHP [Suhosin](http://suhosin.org/ "") extension.

> NinjaFirewall requires at least PHP 5.3 (5.4 or higher recommended to enable all its features) or HHVM 3.4+, MySQLi extension and is only compatible with Unix-like OS (Linux, BSD). It is **not compatible with Microsoft Windows**.

NinjaFirewall can hook, scan, sanitise or reject any HTTP/HTTPS request sent to a PHP script before it reaches WordPress or any of its plugins. All scripts located inside the blog installation directories and sub-directories will be protected, including those that aren't part of the WordPress package. Even encoded PHP scripts, hackers shell scripts and backdoors will be filtered by NinjaFirewall.

= Powerful filtering engine =

NinjaFirewall includes the most powerful filtering engine available in a WordPress plugin. Its most important feature is its ability to normalize and transform data from incoming HTTP requests which allows it to detect Web Application Firewall evasion techniques and obfuscation tactics used by hackers, as well as to support and decode a large set of encodings. See our blog for a full description: [An introduction to NinjaFirewall filtering engine](https://blog.nintechnet.com/introduction-to-ninjafirewall-filtering-engine/ "").

= Features =

* Full standalone web application firewall. Works before WordPress is loaded.
* Powerful filtering engine.
* Supports a large set of encodings.
* Detects and blocks obfuscated code and evasion techniques used to bypass Web Application Firewalls.
* Protects against remote file inclusion, local file inclusion, cross-site scripting, code execution, SQL injections, brute-force scanners, shell scripts, backdoors etc.
* Scans and sanitises GET/POST requests, HTTP/HTTPS traffic, cookies, server variables (`HTTP_USER_AGENT`, `HTTP_REFERER`, `PHP_SELF`, `PATH_TRANSLATED`, `PATH_INFO`) and raw POST data.
* Sanitises variables names and values.
* Privilege escalation protection.
* Anti-Malware Scanner.
* Advanced filtering options to block ASCII control characters, NULL bytes and PHP built-in wrappers.
* Decodes and scans Base64-encoded POST requests to detect backdoors and code injection attempts.
* Hooks and secures HTTP reponse headers to prevent XSS, phishing and clickjacking attempts (`X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`, `Strict-Transport-Security`, `Content-Security-Policy`).
* Hooks and modifies cookies to set the `HttpOnly` flag.
* Blocks username enumeration scanning attempts through the author archives and the login page.
* Blocks/allows uploads, sanitises uploaded file names.
* Blocks suspicious bots and scanners.
* Hides PHP error and notice messages.
* Blocks direct access to PHP scripts located inside specific directories (e.g., `/wp-content/uploads/`).
* Protects WordPress XML-RPC API.
* Whitelist option for WordPress administrator(s), localhost and private IP address spaces.
* Configurable HTTP return code and message.
* Rules editor to enable/disable built-in security rules.
* Activity log and statistics.
* Debugging mode.
* And many more...

= Fastest and most efficient brute-force attack protection for WordPress =

By processing incoming HTTP requests before your blog and any of its plugins, NinjaFirewall is the only plugin for WordPress able to protect it against very large brute-force attacks, including distributed attacks coming from several thousands of different IPs.

See our benchmarks and stress-tests: [Brute-force attack detection plugins comparison](https://blog.nintechnet.com/wordpress-brute-force-attack-detection-plugins-comparison-2015/ "")

The protection applies to the `wp-login.php` script but can be extended to the `xmlrpc.php` one. The incident can also be written to the server `AUTH` log, which can be useful to the system administrator for monitoring purposes or banning IPs at the server level (e.g., Fail2ban).

= Real-time detection =

**File Guard** real-time detection is a totally unique feature provided by NinjaFirewall: it can detect, in real-time, any access to a PHP file that was recently modified or created, and alert you about this. If a hacker uploaded a shell script to your site (or injected a backdoor into an already existing file) and tried to directly access that file using his browser or a script, NinjaFirewall would hook the HTTP request and immediately detect that the file was recently modified or created. It would send you an alert with all details (script name, IP, request, date and time).

= File integrity monitoring  =

**File Check** lets you perform file integrity monitoring by scanning your website hourly, twicedaily or daily. Any modification made to a file will be detected: file content, file permissions, file ownership, timestamp as well as file creation and deletion.

= Watch your website traffic in real time =

**Live Log** lets you watch your website traffic in real time. It displays connections in a format similar to the one used by most HTTP server logs. Because it communicates directly with the firewall, i.e., without loading WordPress, **Live Log** is fast, light and it will not affect your server load, even if you set its refresh rate to the lowest value.

= Events Notification =

NinjaFirewall can alert you by email on specific events triggered within your blog. Some of those alerts are enabled by default and it is highly recommended to keep them enabled. It is not unusual for a hacker, after breaking into your WordPress admin console, to install or just to upload a backdoored plugin or theme in order to take full control of your website.

Monitored events:

* Administrator login.
* Modification of any administrator account in the database.
* Plugins upload, installation, (de)activation, update, deletion.
* Themes upload, installation, activation, deletion.
* WordPress update.

= Anti-Malware Scanner =

It allows you to scan your website for malware. The scanning engine is compatible with the popular [Linux Malware Detect LMD](https://www.rfxn.com/projects/linux-malware-detect/ "") (whose anti-malware signatures are included with this release) and with some [ClamAV](https://www.clamav.net/ "") signatures as well. You can even write your own anti-malware signatures. See our blog for more details about that:  [Adding your own signatures to NinjaFirewall Anti-Malware](https://blog.nintechnet.com/adding-your-own-signatures-to-ninjafirewall-anti-malware/ "")

= Stay protected against the latest WordPress security vulnerabilities =

To get the most efficient protection, NinjaFirewall can automatically update its security rules daily, twice daily or even hourly. Each time a new vulnerability is found in WordPress or one of its plugins/themes, a new set of security rules will be made available to protect your blog immediately.

= Strong Privacy =

Unlike a Cloud Web Application Firewall, or Cloud WAF, NinjaFirewall works and filters the traffic on your own server and infrastructure. That means that your sensitive data (contact form messages, customers credit card number, login credentials etc) remains on your server and is not routed through a third-party company's servers, which could pose unnecessary risks (e.g., employees accessing your data or logs in plain text, theft of private information, man-in-the-middle attack etc).

= IPv6 compatibility =

IPv6 compatibility is a mandatory feature for a security plugin: if it supports only IPv4, hackers can easily bypass the plugin by using an IPv6. NinjaFirewall natively supports IPv4 and IPv6 protocols, for both public and private addresses.

= Multi-site support =

NinjaFirewall is multi-site compatible. It will protect all sites from your network and its configuration interface will be accessible only to the Super Admin from the network main site.

= Possibility to prepend your own PHP code to the firewall =

You can prepend your own PHP code to the firewall with the help of an [optional user configuration file](https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja). It will be processed before WordPress and all its plugins are loaded. This is a very powerful feature, and there is almost no limit to what you can do: add your own security rules, manipulate HTTP requests, variables etc.

= Low Footprint Firewall =

NinjaFirewall is very fast, optimised, compact, and requires very low system resource.
See for yourself: download and install [Query Monitor](https://wordpress.org/plugins/query-monitor/ "") and [Xdebug Profiler](https://xdebug.org/ "") and compare NinjaFirewall performances with other security plugins.

= Non-Intrusive User Interface =

NinjaFirewall looks and feels like a built-in WordPress feature. It does not contain intrusive banners, warnings or flashy colors. It uses the WordPress simple and clean interface and is also smartphone-friendly.

= Contextual Help =

Each NinjaFirewall menu page has a contextual help screen with useful information about how to use and configure it.
If you need help, click on the *Help* menu tab located in the upper right corner of each page in your admin panel.


= Supported Languages =

* English
* French

= Need more security ? =

Check out our new supercharged edition: [NinjaFirewall WP+](https://nintechnet.com/ninjafirewall/wp-edition/ "NinjaFirewall WP+")

* Unix shared memory use for inter-process communication and blazing fast performances.
* IP-based Access Control.
* Role-based Access Control.
* Country-based Access Control via geolocation.
* URL-based Access Control.
* Bot-based Access Control.
* Centralized Logging.
* Antispam for comment and user regisration forms.
* Rate limiting option to block aggressive bots, crawlers, web scrapers and HTTP attacks.
* Response body filter to scan the output of the HTML page right before it is sent to your visitors browser.
* Better File uploads management.
* Better logs management.

[Learn more](https://nintechnet.com/ninjafirewall/wp-edition/ "") about the WP+ Edition unique features. [Compare](https://nintechnet.com/ninjafirewall/wp-edition/?comparison "") the WP and WP+ Editions.


= Requirements =

* WordPress 3.3+
* PHP 5.3+ (5.4 or higher recommended) or [HHVM 3.4+](https://blog.nintechnet.com/installing-ninjafirewall-with-hhvm-hiphop-virtual-machine/ "")
* MySQLi extension
* Apache / Nginx / LiteSpeed
* Unix-like OS (Linux, BSD) only. NinjaFirewall is **NOT** compatible with Microsoft Windows.

== Frequently Asked Questions ==

= Why is NinjaFirewall different from other security plugins for WordPress ? =

NinjaFirewall sits between the attacker and WordPress. It can filter requests before they reach your blog and any of its plugins. This is how it works :

`Attacker > HTTP server > PHP > NinjaFirewall > WordPress`

And this is how all WordPress plugins work :

`Attacker > HTTP server > PHP > WordPress > Plugins`

Unlike other security plugins, it will protect all PHP scripts, including those that aren't part of the WordPress package.

= How powerful is NinjaFirewall? =
NinjaFirewall includes a very powerful filtering engine which can detect Web Application Firewall evasion techniques and obfuscation tactics used by hackers, as well as support and decode a large set of encodings. See our blog for a full description: [An introduction to NinjaFirewall 3.0 filtering engine](https://blog.nintechnet.com/introduction-to-ninjafirewall-filtering-engine/ "").

= Do I need root privileges to install NinjaFirewall ? =

NinjaFirewall does not require any root privilege and is fully compatible with shared hosting accounts. You can install it from your WordPress admin console, just like a regular plugin.


= Does it work with Nginx ? =

NinjaFirewall works with Nginx and others Unix-based HTTP servers (Apache, LiteSpeed etc). Its installer will detect it.

= Do I need to alter my PHP scripts ? =

You do not need to make any modifications to your scripts. NinjaFirewall hooks all requests before they reach your scripts. It will even work with encoded scripts (ionCube, ZendGuard, SourceGuardian etc).

= I moved my wp-config.php file to another directory. Will it work with NinjaFirewall ? =

NinjaFirewall will look for the wp-config.php script in the current folder or, if it cannot find it, in the parent folder.

= Will NinjaFirewall detect the correct IP of my visitors if I am behind a CDN service like Cloudflare ? =

You can use an optional configuration file to tell NinjaFirewall which IP to use. Please [follow these steps](https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja "").

= Will it slow down my site ? =

Your visitors will not notice any difference with or without NinjaFirewall. From WordPress administration console, you can click "NinjaFirewall > Status" menu to see the benchmarks and statistics (the fastest, slowest and average time per request). NinjaFirewall is very fast, optimised, compact, requires very low system resources and [outperforms all other security plugins](https://blog.nintechnet.com/wordpress-brute-force-attack-detection-plugins-comparison/ "").
By blocking dangerous requests and bots before WordPress is loaded, it will save bandwidth and reduce server load.

= Is there any Microsoft Windows version ? =

NinjaFirewall works on Unix-like servers only. There is no Microsoft Windows version and we do not expect to release any.


== Installation ==

1. Upload `ninjafirewall` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Plugin settings are located in 'NinjaFirewall' menu.

== Screenshots ==

1. Overview page.
2. Statistics and benchmarks page.
3. Options page.
4. Policies pages: NinjaFirewall has a large list of powerful and unique policies that you can tweak accordingly to your needs.
5. Contextual help.
6. Event notifications can alert you by email on specific events triggered within your blog.
7. Login page protection: the fastest and most efficient brute-force attack protection for WordPress.
8. Live Log: lets you watch your website traffic in real time. It is fast, light and it does not affect your server load.
9. Firewall Log.
10. Dashboard widget.
11. File Guard: this is a totally unique feature, because it can detect, in real-time, any access to a PHP file that was recently modified or created, and alert you about this.
12. Network.
13. Rules Editor.
14. File Check: lets you perform file integrity monitoring upon request or on a specific interval (hourly, twicedaily, daily).
15. Security rules updates.
16. Anti-Malware.

== Changelog ==

= v3.5 =

* The login page can also be protected with a captcha instead of the username/password combination (see "Login Protection > Type of protection").
* A new option was added to attempt to block bots before they start a brute-force attack (see "Login Protection > Enable bot protection").
* The substitution character used to sanitise filenames can be changed (see "Firewall Policies > Uploads > Sanitise filenames > Substitution character").
* The "X-Content-Type-Options" firewall policy will be disabled by default when installing NinjaFirewall.
* Fixed a bug where NinjaFirewall was not reporting the correct timezone.
* The firewall log encoding can be disabled or changed (hexadecimal, base64 or none). See http://nin.link/log_encoding/
* Updated Anti-Malware signatures.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Small fixes and minor adjustments.

