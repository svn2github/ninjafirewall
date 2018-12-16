=== NinjaFirewall (WP Edition) - Advanced Security ===
Contributors: nintechnet, bruandet
Tags: security, firewall, malware, antispam, virus, scanner, hacked site, brute force, seguridad, seguranca, sicherheit, sicurezza, veiligheid
Requires at least: 3.7
Tested up to: 5.0
Stable tag: 3.8
Requires PHP: 5.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A true Web Application Firewall to protect and secure WordPress.

== Description ==

= A true Web Application Firewall =

NinjaFirewall (WP Edition) is a true Web Application Firewall. Although it can be installed and configured just like a plugin, it is a stand-alone firewall that sits in front of WordPress.

It allows any blog administrator to benefit from very advanced and powerful security features that usually aren't available at the WordPress level, but only in security applications such as the Apache [ModSecurity](http://www.modsecurity.org/ "") module or the PHP [Suhosin](http://suhosin.org/ "") extension.

> NinjaFirewall requires at least PHP 5.3 (5.4 or higher recommended to use all its features) or HHVM 3.4+, MySQLi extension and is only compatible with Unix-like OS (Linux, BSD). It is **not compatible with Microsoft Windows**.

NinjaFirewall can hook, scan, sanitise or reject any HTTP/HTTPS request sent to a PHP script before it reaches WordPress or any of its plugins. All scripts located inside the blog installation directories and sub-directories will be protected, including those that aren't part of the WordPress package. Even encoded PHP scripts, hackers shell scripts and backdoors will be filtered by NinjaFirewall.

= Powerful filtering engine =

NinjaFirewall includes the most powerful filtering engine available in a WordPress plugin. Its most important feature is its ability to normalize and transform data from incoming HTTP requests which allows it to detect Web Application Firewall evasion techniques and obfuscation tactics used by hackers, as well as to support and decode a large set of encodings. See our blog for a full description: [An introduction to NinjaFirewall filtering engine](https://blog.nintechnet.com/introduction-to-ninjafirewall-filtering-engine/ "").

= Fastest and most efficient brute-force attack protection for WordPress =

By processing incoming HTTP requests before your blog and any of its plugins, NinjaFirewall is the only plugin for WordPress able to protect it against very large brute-force attacks, including distributed attacks coming from several thousands of different IPs.

See our benchmarks and stress-tests: [Brute-force attack detection plugins comparison](https://blog.nintechnet.com/wordpress-brute-force-attack-detection-plugins-comparison-2015/ "")

The protection applies to the `wp-login.php` script but can be extended to the `xmlrpc.php` one. The incident can also be written to the server `AUTH` log, which can be useful to the system administrator for monitoring purposes or banning IPs at the server level (e.g., Fail2ban).

= Real-time detection =

**File Guard** real-time detection is a totally unique feature provided by NinjaFirewall: it can detect, in real-time, any access to a PHP file that was recently modified or created, and alert you about this. If a hacker uploaded a shell script to your site (or injected a backdoor into an already existing file) and tried to directly access that file using his browser or a script, NinjaFirewall would hook the HTTP request and immediately detect that the file was recently modified or created. It would send you an alert with all details (script name, IP, request, date and time).

= File integrity monitoring  =

**File Check** lets you perform file integrity monitoring by scanning your website hourly, twicedaily or daily. Any modification made to a file will be detected: file content, file permissions, file ownership, timestamp as well as file creation and deletion.

= Watch your website traffic in real time =

**Live Log** lets you watch your website traffic in real time. It displays connections in a format similar to the one used by the `tail -f` Unix command. Because it communicates directly with the firewall, i.e., without loading WordPress, **Live Log** is fast, lightweight and it will not affect your server load, even if you set its refresh rate to the lowest value.

= Events Notification =

NinjaFirewall can alert you by email on specific events triggered within your blog. Some of those alerts are enabled by default and it is highly recommended to keep them enabled. It is not unusual for a hacker, after breaking into your WordPress admin console, to install or just to upload a backdoored plugin or theme in order to take full control of your website.

Monitored events:

* Administrator login.
* Modification of any administrator account in the database.
* Plugins upload, installation, (de)activation, update, deletion.
* Themes upload, installation, activation, deletion.
* WordPress update.

= Stay protected against the latest WordPress security vulnerabilities =

To get the most efficient protection, NinjaFirewall can automatically update its security rules daily, twice daily or even hourly. Each time a new vulnerability is found in WordPress or one of its plugins/themes, a new set of security rules will be made available to protect your blog immediately.

= Strong Privacy =

Unlike a Cloud Web Application Firewall, or Cloud WAF, NinjaFirewall works and filters the traffic on your own server and infrastructure. That means that your sensitive data (contact form messages, customers credit card number, login credentials etc) remains on your server and is not routed through a third-party company's servers, which could pose unnecessary risks (e.g., decryption of your HTTPS traffic in order to inspect it, employees accessing your data or logs in plain text, theft of private information, man-in-the-middle attack etc).

Your website can run NinjaFirewall and be compliant with the General Data Protection Regulation (GDPR). [See our blog for more details](https://blog.nintechnet.com/ninjafirewall-general-data-protection-regulation-compliance/ "GDPR Compliance").

= IPv6 compatibility =

IPv6 compatibility is a mandatory feature for a security plugin: if it supports only IPv4, hackers can easily bypass the plugin by using an IPv6. NinjaFirewall natively supports IPv4 and IPv6 protocols, for both public and private addresses.

= Multi-site support =

NinjaFirewall is multi-site compatible. It will protect all sites from your network and its configuration interface will be accessible only to the Super Admin from the network main site.

= Possibility to prepend your own PHP code to the firewall =

You can prepend your own PHP code to the firewall with the help of an [optional distributed configuration file](https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja). It will be processed before WordPress and all its plugins are loaded. This is a very powerful feature, and there is almost no limit to what you can do: add your own security rules, manipulate HTTP requests, variables etc.

= Low Footprint Firewall =

NinjaFirewall is very fast, optimised, compact, and requires very low system resource.
See for yourself: download and install [Query Monitor](https://wordpress.org/plugins/query-monitor/ "") and [Xdebug Profiler](https://xdebug.org/ "") and compare NinjaFirewall performances with other security plugins.

= Non-Intrusive User Interface =

NinjaFirewall looks and feels like a built-in WordPress feature. It does not contain intrusive banners, warnings or flashy colors. It uses the WordPress simple and clean interface and is also smartphone-friendly.

= Contextual Help =

Each NinjaFirewall menu page has a contextual help screen with useful information about how to use and configure it.
If you need help, click on the *Help* menu tab located in the upper right corner of each page in your admin panel.

= Need more security ? =

Check out our new supercharged edition: [NinjaFirewall WP+ Edition](https://nintechnet.com/ninjafirewall/wp-edition/ "NinjaFirewall WP+ Edition")

* Unix shared memory use for inter-process communication and blazing fast performances.
* IP-based Access Control.
* Role-based Access Control.
* Country-based Access Control via geolocation.
* URL-based Access Control.
* Bot-based Access Control.
* [Centralized Logging](https://blog.nintechnet.com/centralized-logging-with-ninjafirewall/ "Centralized Logging").
* Antispam for comment and user regisration forms.
* Rate limiting option to block aggressive bots, crawlers, web scrapers and HTTP attacks.
* Response body filter to scan the output of the HTML page right before it is sent to your visitors browser.
* Better File uploads management.
* Better logs management.
* [Syslog logging](https://blog.nintechnet.com/syslog-logging-with-ninjafirewall/ "Syslog logging").

[Learn more](https://nintechnet.com/ninjafirewall/wp-edition/ "") about the WP+ Edition unique features. [Compare](https://nintechnet.com/ninjafirewall/wp-edition/?comparison "") the WP and WP+ Editions.


= Requirements =

* WordPress 3.3+
* Admin/Superadmin with `manage_options` + `unfiltered_html capabilities`.
* PHP 5.3+ (5.4 or higher recommended), PHP 7.x or [HHVM 3.4+](https://blog.nintechnet.com/installing-ninjafirewall-with-hhvm-hiphop-virtual-machine/ "")
* MySQL or MariaDB with MySQLi extension
* Apache / Nginx / LiteSpeed compatible
* Unix-like operating systems only (Linux, BSD etc). NinjaFirewall is **NOT** compatible with Microsoft Windows.

== Frequently Asked Questions ==

= Why is NinjaFirewall different from other security plugins for WordPress ? =

NinjaFirewall sits between the attacker and WordPress. It can filter requests before they reach your blog and any of its plugins. This is how it works :

`Visitor -> HTTP server -> PHP -> NinjaFirewall #1 -> WordPress -> NinjaFirewall #2 -> Plugins & Themes -> WordPress exit -> NinjaFirewall #3`

And this is how all WordPress plugins work :

`Visitor > HTTP server > PHP > WordPress > Plugins -> WordPress exit`


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

== Changelog ==

= 3.8 =

* A lot of code was cleaned-up, fixed and improved as well as the whole files structure of the plugin.
* Increased the height of the textarea in the "Firewall Log" and "Live Log" pages.
* Fixed an issue where some caching plugins could mess with the database monitoring process which could return erroneous results.
* Improved the database monitoring process for blogs that have a huge amount of rows in the "wp_usermeta" table.
* The "File Check" notification will include the number of new, modified and deleted files in the body of the email.
* [WP+ Edition] Added an option to disable login alerts for users whose IP address is whitelisted. See "NinjaFirewall > Event Notifications > Do not send a notification if the user is in the IP Access Control whitelist".
* [WP+ Edition] Fixed an issue where, after deleting the log, it was once again deleted if the page was reloaded in the browser.
* Fixed an issue where any `auto_prepend_file` directive left by another application in the .htaccess was not removed before starting the installation of NinjaFirewall on servers running LiteSpeed or Apache + mod_php.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 3.7.2 =

* Added a new option to block any attempt by non-admin users to modify some important WordPress settings (e.g., by exploiting a vulnerability, using a backdoor etc). See "Firewall Policies > Basic Policies > Block attempts to modify important WordPress settings".
* [WP+ Edition] Fixed a bug in the "Web Filter" callback function where the firewall was writing its log in the `/wp-content/` folder instead of `/wp-content/nfwlog/`.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Small fixes and adjustments.

= 3.7.1 =

* Fixed two potential PHP notices in the firewall on systems running PHP 7.2+.
* Added a function to the firewall engine to detect octal-encoded values that could be used as WAF evasion techniques (e.g. `?foo=\050\141\154\145\162\164\051\050\170\163\163\051`).
* If you have a complex database setup that NinjaFirewall is not able to properly retrieve, you can give it a MySQLi link identifier in the `.htninja` instead. See "Giving NinjaFirewall a MySQLi link identifier" at http://nin.link/htninja/ for more details.
* Added right to left language support.
* Improved HTTPS detection in the firewall.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Fixed potential "ini_set" PHP warning when a session was started by another plugin.
* Many small fixes and adjustments.

= 3.7 =

* Added a new option to the "Live Log" page: you can apply filters in order to include or exclude files and folders. See "Live Log > Inclusion and exclusion filters".
* Added a new option to the "Firewall Options" page: NinjaFirewall will automatically backup its configuration (options, policies and rules) everyday for the last 5 days so that you can restore its configuration to an earlier date if needed. See "Firewall Options > Configuration backup".
* [WP+ Edition] The "IP Access Control" whitelist and blacklist can now support CIDR notation for IPv4 and IPv6 (e.g., 66.155.0.0/17, 2c0f:f248::/32).
* Added a warning to the "Login Protection" page if Jetpack is installed and the XML-RPC API protection is activated.
* Added a notice to the "Login Protection" page to remind that the "Authentication log" option can only work when the protection is set to "Yes, if under attack".
* Fixed a potential "401 Unauthorized" HTTP response when attempting to access the XMLRPC API using a non-POST method.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Minor fixes.

= 3.6.8 =

* Fixed an issue where third-party plugins making use of PHP session but without properly checking the status of the current session could behave erratically.

= 3.6.7 =

* Added a new option to block any attempt (e.g., exploiting a vulnerability, using a backdoor etc) to create a user account. See "Firewall Policies > Basic Policies > Block user accounts creation".
* The "Daily Activity Report" will include the domain name of the blog in the email subject.
* Fixed a potential "Zend OPcache API " warning message when saving the "Login Protection" options.
* The "Updates" menu was renamed to "Rules Update".
* Improved PHP session handling.
* Fixed a potential "Call to a member function close() on null" PHP error in the firewall.
* [WP+ Edition] Fixed a bug in the "Web Filter" page where the button to submit the HTML form was not visible.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Minor fixes and adjustments.

= 3.6.6 =

* The "Statistics" page and dashboard widget will display the same values. Previously, the total of blocked threats displayed in the "Statistics" page was reset if the corresponding firewall log was deleted.
* Fixed a bug in the Garbage Collector: in some cases, the firewall log was deleted a few days later than expected.
* The Garbage Collector will still be able to run even if WP-Cron is disabled.
* Fixed an issue introduced in WordPress 4.9.6: NinjaFirewall was not visible in the list of plugins when using WP-CLI. Note that if you want to enable/disable it from WP-CLI you will need to append the `--user` switch to your command (e.g., `$ wp plugin activate nfwplus --user=some_admin`).
* Minor fixes.

= 3.6.5 =

* The brute-force protection will not be triggered when users click on the email confirmation link, which points to the wp-login.php script, sent by the new WordPress "Export Personal Data" feature.
* The firewall will automatically detect if the blog runs on an old multisite installation where the main site options table is named "wp_1_options" instead of "wp_options".

= 3.6.4 =

* Fixed potential "session_status()" error with old PHP installations.

= 3.6.3 =

* Added the "Referrer-Policy" header (see "Firewall Policies > Advanced Policies > HTTP response headers").
* Added the "418 I'm a teapot" HTTP error code (see "Firewall Options > HTTP error code to return").
* Modified how PHP sessions were handled in order to prevent conflicts with third-party applications that may attempt to start a session without checking if one was already started (e.g., Piwik/Zend Framework, phpMyadmin).
* Added more options to the X-XSS-Protection header; it can be set to "0", "1", "1; mode=block" or disabled (see "Firewall Policies > Advanced Policies > HTTP response headers").
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Minor fixes.

= 3.6.2 =

* Added an option to automatically delete the firewall log(s) after a period of time (see "NinjaFirewall > Firewall Log > Auto-delete log").
* Added an option to enter the admin email address during the installation process.
* [WP+ Edition] The "Access Control" page was split into 5 tabs: "General", "Geolocation", "IP Access Control", "URL Access Control" and "Bot Access Control".
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Many small fixes throughout the code: bugs, typos, contextual help corrections, various adjustments etc.

= 3.6.1 =

* Added "IP Anonymization" option. It will anonymize IP addresses in the firewall log by removing their last 3 characters. See "NinjaFirewall > Firewall Options > IP Anonymization".
* Fixed a bug where the "Login Protection" wrongly applied to password protected pages.
* Fixed a bug where the garbage collector cron job was not deleted when NinjaFirewall was disabled.
* Added a warning that NinjaFirewall requires `unfiltered_html` capability when attempting to activate it.
* [WP+ Edition] The "Uploads > Allow, but block scripts, ELF and system files" firewall policy was renamed to "Allow, but block dangerous files" and will also block dangerous SVG files. Therefore, the complete list of blocked files is now: scripts (PHP, CGI, Ruby, Python, bash/shell), C/C++ source code, binaries (MZ/PE/NE and ELF formats), system files (.htaccess, .htpasswd and PHP INI) and SVG files containing Javascript/XML events.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Minor fixes.

= v3.6 =

* Important: We have removed the "Anti-Malware" option from NinjaFirewall. Instead, we have now a brand new and much better antivirus plugin: NinjaScanner. You can download it from wordpress.org: https://wordpress.org/plugins/ninjascanner/
* [WP+ Edition] Fixed a bug where IPs that were whitelisted in the "Access Control" page could not connect to the REST API if its access was disabled in the "Firewall Policies".
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Minor fixes.
