<?php die('Forbidden'); ?>|20160303.2|a:180:{i:1;a:5:{s:5:"where";s:31:"GET|POST|COOKIE|HTTP_USER_AGENT";s:4:"what";s:24:"(?:\.{2}[\\/]{1,4}){2}\b";s:3:"why";s:19:"Directory traversal";s:5:"level";i:3;s:2:"on";i:1;}i:3;a:5:{s:5:"where";s:31:"GET|POST|COOKIE|HTTP_USER_AGENT";s:4:"what";s:34:"[.\\/]/(?:proc/self/|etc/passwd)\b";s:3:"why";s:20:"Local file inclusion";s:5:"level";i:2;s:2:"on";i:1;}i:50;a:5:{s:5:"where";s:31:"GET|POST|COOKIE|HTTP_USER_AGENT";s:4:"what";s:31:"^(?i:https?|ftp)://.+/[^&/]+\?$";s:3:"why";s:21:"Remote file inclusion";s:5:"level";i:3;s:2:"on";i:1;}i:51;a:5:{s:5:"where";s:22:"COOKIE|HTTP_USER_AGENT";s:4:"what";s:49:"^(?i:https?)://\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}";s:3:"why";s:30:"Remote file inclusion (URL IP)";s:5:"level";i:2;s:2:"on";i:1;}i:52;a:5:{s:5:"where";s:31:"GET|POST|COOKIE|HTTP_USER_AGENT";s:4:"what";s:61:"\b(?i:include|require)(?i:_once)?\s*\([^)]*(?i:https?|ftp)://";s:3:"why";s:43:"Remote file inclusion (via require/include)";s:5:"level";i:3;s:2:"on";i:1;}i:53;a:5:{s:5:"where";s:31:"GET|POST|COOKIE|HTTP_USER_AGENT";s:4:"what";s:33:"^(?i:ftp)://(?:.+?:.+?\@)?[^/]+/.";s:3:"why";s:27:"Remote file inclusion (FTP)";s:5:"level";i:2;s:2:"on";i:1;}i:100;a:5:{s:5:"where";s:39:"GET|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:85:"<\s*/?(?i:applet|div|embed|i?frame(?:set)?|meta|marquee|object|script|textarea)\b.*?>";s:3:"why";s:14:"XSS (HTML tag)";s:5:"level";i:2;s:2:"on";i:1;}i:101;a:5:{s:5:"where";s:39:"GET|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:67:"\W(?:background(-image)?|-moz-binding)\s*:[^}]*?\burl\s*\([^)]+?://";s:3:"why";s:27:"XSS (remote background URI)";s:5:"level";i:3;s:2:"on";i:1;}i:102;a:5:{s:5:"where";s:39:"GET|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:80:"(?i:<[^>]+?(?:data|href|src)\s*=\s*['\"]?(?:https?|data|php|(?:java|vb)script):)";s:3:"why";s:16:"XSS (remote URI)";s:5:"level";i:3;s:2:"on";i:1;}i:103;a:5:{s:5:"where";s:39:"GET|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:157:"\b(?i:on(?i:abort|blur|(?:dbl)?click|dragdrop|error|focus|key(?:up|down|press)|(?:un)?load|mouse(?:down|out|over|up)|move|res(?:et|ize)|select|submit))\b\s*=";s:3:"why";s:16:"XSS (HTML event)";s:5:"level";i:2;s:2:"on";i:1;}i:104;a:5:{s:5:"where";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:84:"[:=\]]\s*['"]?(?:alert|confirm|eval|expression|prompt|String\.fromCharCode|url)\s*\(";s:3:"why";s:17:"XSS (JS function)";s:5:"level";i:3;s:2:"on";i:1;}i:105;a:5:{s:5:"where";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:56:"\bdocument\.(?:body|cookie|location|open|write(?:ln)?)\b";s:3:"why";s:21:"XSS (document object)";s:5:"level";i:2;s:2:"on";i:1;}i:106;a:5:{s:5:"where";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:30:"\blocation\.(?:href|replace)\b";s:3:"why";s:21:"XSS (location object)";s:5:"level";i:2;s:2:"on";i:1;}i:107;a:5:{s:5:"where";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:29:"\bwindow\.(?:open|location)\b";s:3:"why";s:19:"XSS (window object)";s:5:"level";i:2;s:2:"on";i:1;}i:108;a:5:{s:5:"where";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:33:"(?i:style)\s*=\s*['\"]?[^'\"]+/\*";s:3:"why";s:22:"XSS (obfuscated style)";s:5:"level";i:3;s:2:"on";i:1;}i:109;a:5:{s:5:"where";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:4:"^/?>";s:3:"why";s:31:"XSS (leading greater-than sign)";s:5:"level";i:2;s:2:"on";i:1;}i:110;a:5:{s:5:"where";s:12:"QUERY_STRING";s:4:"what";s:18:"(?:%%\d\d%\d\d){2}";s:3:"why";s:19:"XSS (double nibble)";s:5:"level";i:2;s:2:"on";i:1;}i:111;a:5:{s:5:"where";s:4:"POST";s:4:"what";s:32:"<(?is:script.*?>.*?</script.*?>)";s:3:"why";s:16:"XSS (JavaScript)";s:5:"level";i:2;s:2:"on";i:1;}i:112;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:58:"\[\+!\+\[\]\]\]\)\[\+!\+\[\]\+\[\+\[\]\]\]\+\(\[\]\[\[\]\]";s:3:"why";s:12:"XSS (JSFuck)";s:5:"level";i:3;s:2:"on";i:1;}i:150;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:59:"[\n\r]\s*\b(?:(?:reply-)?to|b?cc|content-[td]\w)\b\s*:.*?\@";s:3:"why";s:21:"Mail header injection";s:5:"level";i:2;s:2:"on";i:1;}i:153;a:5:{s:5:"where";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:56:"<!--#(?:config|echo|exec|flastmod|fsize|include)\b.+?-->";s:3:"why";s:21:"SSI command injection";s:5:"level";i:2;s:2:"on";i:1;}i:154;a:5:{s:5:"where";s:35:"COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:31:"(?s:<\?.+)|#!/(?:usr|bin)/.+?\s";s:3:"why";s:14:"Code injection";s:5:"level";i:3;s:2:"on";i:1;}i:155;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:398:"(?s:<\?(?![Xx][Mm][Ll]).*?(?:\$_?(?:COOKIE|ENV|FILES|GLOBALS|(?:GE|POS|REQUES)T|SE(RVER|SSION))\s*[=\[)]|\b(?i:array_map|assert|base64_(?:de|en)code|curl_exec|eval|(?:ex|im)plode|file(?:_get_contents)?|fsockopen|function_exists|gzinflate|move_uploaded_file|passthru|preg_replace|phpinfo|stripslashes|strrev|substr|system|(?:shell_)?exec)\s*\()|\x60.+?\x60)|#!/(?:usr|bin)/.+?\s|\W\$\{\s*['"]\w+['"]";s:3:"why";s:14:"Code injection";s:5:"level";i:3;s:2:"on";i:1;}i:156;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:115:"\b(?i:eval)\s*\(\s*(?i:base64_decode|exec|file_get_contents|gzinflate|passthru|shell_exec|stripslashes|system)\s*\(";s:3:"why";s:17:"Code injection #2";s:5:"level";i:2;s:2:"on";i:1;}i:157;a:5:{s:5:"where";s:8:"GET:fltr";s:4:"what";s:1:";";s:3:"why";s:25:"Code injection (phpThumb)";s:5:"level";i:3;s:2:"on";i:1;}i:158;a:5:{s:5:"where";s:17:"GET:phpThumbDebug";s:4:"what";s:1:".";s:3:"why";s:36:"phpThumb debug mode (potential SSRF)";s:5:"level";i:3;s:2:"on";i:1;}i:159;a:5:{s:5:"where";s:7:"GET:src";s:4:"what";s:2:"\$";s:3:"why";s:38:"TimThumb WebShot Remote Code Execution";s:5:"level";i:3;s:2:"on";i:1;}i:160;a:5:{s:5:"where";s:10:"GET|SERVER";s:4:"what";s:16:"^\s*\(\s*\)\s*\{";s:3:"why";s:40:"Shellshock vulnerability (CVE-2014-6271)";s:5:"level";i:3;s:2:"on";i:1;}i:161;a:5:{s:5:"where";s:19:"SERVER:HTTP_REFERER";s:4:"what";s:16:"\?a=\$stylevar\b";s:3:"why";s:37:"vBulletin vBSEO remote code injection";s:5:"level";i:3;s:2:"on";i:1;}i:162;a:6:{s:5:"where";s:14:"POST:avatarurl";s:4:"what";s:10:"pluginlist";s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:2:"do";i:3;s:18:"^updateprofilepic$";}s:3:"why";s:47:"vBulletin <4.2.2 memcache remote code execution";s:5:"level";i:3;s:2:"on";i:1;}i:163;a:5:{s:5:"where";s:43:"HTTP_X_FORWARDED_FOR|SERVER:HTTP_USER_AGENT";s:4:"what";s:21:"JDatabaseDriverMysqli";s:3:"why";s:49:"Joomla 1.5-3.4.5 Object injection (CVE-2015-8562)";s:5:"level";i:3;s:2:"on";i:1;}i:164;a:5:{s:5:"where";s:15:"HTTP_USER_AGENT";s:4:"what";s:7:"^.{300}";s:3:"why";s:52:"Excessive user-agent string length (300+ characters)";s:5:"level";i:2;s:2:"on";i:1;}i:200;a:5:{s:5:"where";s:15:"GET|POST|COOKIE";s:4:"what";s:43:"^(?i:admin(?:istrator)?)['"].*?(?:--|#|/\*)";s:3:"why";s:35:"SQL injection (admin login attempt)";s:5:"level";i:3;s:2:"on";i:1;}i:201;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:72:"\b(?i:[-\w]+@(?:[-a-z0-9]+\.)+[a-z]{2,8}'.{0,20}\band\b.{0,20}=[\s/*]*')";s:3:"why";s:34:"SQL injection (user login attempt)";s:5:"level";i:3;s:2:"on";i:1;}i:202;a:5:{s:5:"where";s:26:"GET:username|POST:username";s:4:"what";s:19:"[#'"=(),<>/\\*\x60]";s:3:"why";s:24:"SQL injection (username)";s:5:"level";i:3;s:2:"on";i:1;}i:203;a:5:{s:5:"where";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:42:"\b(?i:and)\s+\d+\s*=\s*\d+\s*;?\s*(?:--|#)";s:3:"why";s:33:"SQL injection (equal operator #2)";s:5:"level";i:2;s:2:"on";i:1;}i:204;a:5:{s:5:"where";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:60:"\b(?i:and|or|having)\b.+?['"]?\b(\w+)\b['"]?\s*=\s*['"]?\1\b";s:3:"why";s:30:"SQL injection (equal operator)";s:5:"level";i:3;s:2:"on";i:1;}i:205;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:68:"(?i:(?:(?:and|or|union|like)\b|;|').*?from\b.+?information_schema\b)";s:3:"why";s:34:"SQL injection (information_schema)";s:5:"level";i:3;s:2:"on";i:1;}i:206;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:54:"\*/.{0,50}\b(?i:limit|select|union|concat)\b.{0,50}/\*";s:3:"why";s:35:"SQL injection (comment obfuscation)";s:5:"level";i:3;s:2:"on";i:1;}i:207;a:5:{s:5:"where";s:3:"GET";s:4:"what";s:30:"^[-\d';].+\w.+(?:--|#|/\*)\s*$";s:3:"why";s:32:"SQL injection (trailing comment)";s:5:"level";i:3;s:2:"on";i:1;}i:208;a:5:{s:5:"where";s:35:"COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:162:"(?i:(?:\b(?:and|or|union)\b|;|').*?\b(?:alter|create|delete|drop|grant|information_schema|insert|load|rename|select|truncate|update)\b.+?\b(?:from|into|on|set)\b)";s:3:"why";s:13:"SQL injection";s:5:"level";i:1;s:2:"on";i:1;}i:209;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:200:"(?i:(?:(?:and|or|union)\b|;|').*?select\b.+?(?:from\b|limit\b|where\b|\@?\@?version\b|(?:user|benchmark|char|count|database|(?:group_)?concat(?:_ws)?|floor|md5|rand|substring|version)\s*\(|--|/\*|#$))";s:3:"why";s:22:"SQL injection (select)";s:5:"level";i:3;s:2:"on";i:1;}i:210;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:77:"(?i:(?:(?:and|or|union)\b|;|').*?insert\b.+?into\b.*?\([^)]+\).+?values.*?\()";s:3:"why";s:22:"SQL injection (insert)";s:5:"level";i:3;s:2:"on";i:1;}i:211;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:54:"(?i:(?:(?:and|or|union)\b|;|').*?update\b.+?set\b.+?=)";s:3:"why";s:22:"SQL injection (update)";s:5:"level";i:3;s:2:"on";i:1;}i:212;a:5:{s:5:"where";s:3:"GET";s:4:"what";s:56:"(?i:(?:(?:and|or|union)\b|;|').*?grant\b.+?on\b.+?to\s+)";s:3:"why";s:21:"SQL injection (grant)";s:5:"level";i:3;s:2:"on";i:1;}i:213;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:53:"(?i:(?:(?:and|or|union)\b|;|').*?delete\b.+?from\b.+)";s:3:"why";s:22:"SQL injection (delete)";s:5:"level";i:3;s:2:"on";i:1;}i:214;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:89:"(?i:(?:[^a-z](?:and|or|union)\b|;|').*?(?:alter|create|drop)\b.+?(?:DATABASE|TABLE)\b.+?)";s:3:"why";s:33:"SQL injection (alter/create/drop)";s:5:"level";i:3;s:2:"on";i:1;}i:215;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:63:"(?i:(?:(?:and|or|union)\b|;|').*?(?:rename|truncate)\b.+?table)";s:3:"why";s:31:"SQL injection (rename/truncate)";s:5:"level";i:3;s:2:"on";i:1;}i:216;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:106:"(?i:(?:(?:and|or|union)\b|;|').*?select\b.+?(?:into\b.+?(?:(?:dump|out)file|\@['\"\x60]?\w+)|load_file))\b";s:3:"why";s:37:"SQL injection (select into/load_file)";s:5:"level";i:3;s:2:"on";i:1;}i:217;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:69:"(?i:(?:(?:and|or|union)\b|;|').*?load\b.+?data\b.+?infile\b.+?into)\b";s:3:"why";s:20:"SQL injection (load)";s:5:"level";i:3;s:2:"on";i:1;}i:218;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:29:"\b(?i:waitfor\b\W*?\bdelay)\b";s:3:"why";s:26:"SQL injection (time-based)";s:5:"level";i:2;s:2:"on";i:1;}i:219;a:5:{s:5:"where";s:3:"GET";s:4:"what";s:39:"(?i:\bbenchmark\s*\(\d+\s*,\s*md5\s*\()";s:3:"why";s:25:"SQL injection (benchmark)";s:5:"level";i:2;s:2:"on";i:1;}i:220;a:5:{s:5:"where";s:3:"GET";s:4:"what";s:49:"(?i:concat\b.+?floor\s*\(\s*rand\s*\(\s*\d+\s*\))";s:3:"why";s:20:"SQL injection (rand)";s:5:"level";i:3;s:2:"on";i:1;}i:221;a:5:{s:5:"where";s:19:"SERVER:QUERY_STRING";s:4:"what";s:56:"(?i:list\[select\]=.+?select.+?from.+?session_id.+?from)";s:3:"why";s:36:"Joomla SQL injection (CVE-2015-7857)";s:5:"level";i:3;s:2:"on";i:1;}i:250;a:5:{s:5:"where";s:9:"HTTP_HOST";s:4:"what";s:20:"[^-a-zA-Z0-9._:\[\]]";s:3:"why";s:21:"Malformed Host header";s:5:"level";i:2;s:2:"on";i:1;}i:300;a:5:{s:5:"where";s:3:"GET";s:4:"what";s:6:"^['\"]";s:3:"why";s:13:"Leading quote";s:5:"level";i:2;s:2:"on";i:1;}i:301;a:5:{s:5:"where";s:3:"GET";s:4:"what";s:11:"^[\x09\x20]";s:3:"why";s:13:"Leading space";s:5:"level";i:1;s:2:"on";i:1;}i:302;a:5:{s:5:"where";s:22:"QUERY_STRING|PATH_INFO";s:4:"what";s:44:"\bHTTP_RAW_POST_DATA|HTTP_(?:POS|GE)T_VARS\b";s:3:"why";s:12:"PHP variable";s:5:"level";i:2;s:2:"on";i:1;}i:303;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:12:"phpinfo\.php";s:3:"why";s:29:"Attempt to access phpinfo.php";s:5:"level";i:1;s:2:"on";i:1;}i:304;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:30:"/scripts/(?:setup|signon)\.php";s:3:"why";s:26:"phpMyAdmin hacking attempt";s:5:"level";i:2;s:2:"on";i:1;}i:305;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:26:"\.ph(?:p[345]?|t|tml)\..+?";s:3:"why";s:23:"PHP handler obfuscation";s:5:"level";i:3;s:2:"on";i:1;}i:306;a:5:{s:5:"where";s:22:"SERVER:HTTP_USER_AGENT";s:4:"what";s:28:"\bcompatible; MSIE [1-6]\.\d";s:3:"why";s:26:"Bogus user-agent signature";s:5:"level";i:1;s:2:"on";i:1;}i:309;a:5:{s:5:"where";s:65:"QUERY_STRING|PATH_INFO|COOKIE|SERVER:HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:141:"\b(?:\$?_(COOKIE|ENV|FILES|(?:GE|POS|REQUES)T|SE(RVER|SSION))|HTTP_(?:(?:POST|GET)_VARS|RAW_POST_DATA)|GLOBALS)\s*[=\[)]|\W\$\{\s*['"]\w+['"]";s:3:"why";s:24:"PHP predefined variables";s:5:"level";i:2;s:2:"on";i:1;}i:310;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:78:"(?i:(?:conf(?:ig(?:ur(?:e|ation)|\.inc|_global)?)?)|settings?(?:\.?inc)?)\.php";s:3:"why";s:30:"Access to a configuration file";s:5:"level";i:2;s:2:"on";i:1;}i:311;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:40:"/tiny_?mce/plugins/spellchecker/classes/";s:3:"why";s:23:"TinyMCE path disclosure";s:5:"level";i:2;s:2:"on";i:1;}i:312;a:5:{s:5:"where";s:20:"HTTP_X_FORWARDED_FOR";s:4:"what";s:24:"[^.0-9a-fA-F:\x20,unkow]";s:3:"why";s:29:"Non-compliant X_FORWARDED_FOR";s:5:"level";i:1;s:2:"on";i:1;}i:313;a:5:{s:5:"where";s:12:"QUERY_STRING";s:4:"what";s:14:"^-[bcndfiswzT]";s:3:"why";s:31:"PHP-CGI exploit (CVE-2012-1823)";s:5:"level";i:3;s:2:"on";i:1;}i:314;a:5:{s:5:"where";s:19:"SERVER:HTTP_REFERER";s:4:"what";s:1133:"^http://(?:www\.)?(?:100dollars-seo\.com|4webmasters\.org|7zap\.com|adviceforum\.info|bestbowling\.ru|best-funnycatsanddogs\.com|best-seo-(?:offer|report|solution)\.com|blackhatworth\.com|brianjeanmp\.net|buttons-for-(?:your-)website\.com|carmods\.ru|chimiver\.info|cookingmeat\.ru|cumgoblin\.com|dedicatesales\.com|darodar\.com|descargar-musica-gratis\.net|doska-vsem\.ru|downloadsphotoshop\.com|econom\.co|energy-ua\.com|event-tracking\.com|fbdownloader\.com|fishingwiki\.ru|f(?:loating|ree)-share-buttons\.com|feel-planet.com|golden-praga\.ru|goldishop\.ru|hvd-store\.com|hulfingtonpost\.com|iloveitaly.(?:com?|ru)|intl-alliance\.com|julia(?:diets\.com|world\.net)|kambasoft\.com|kinoix\.net|kinzeco\.ru|make-money-online\.|masserect\.com|mccpharmacy\.com|mebel-alait\.ru|modjocams\.com|nardulan\.com|nudepatch\.net|poisk-zakona\.ru|prahaprint\.cz|priceg\.com|rankalexa\.net|rankings-analytics\.com|savetubevideo\.com|semalt(?:media)?\.com|sexytrend.ru|sfd-chess\.ru|srecorder\.co|success-seo\.com|thefinery\.ru|valegames\.com|videos-for-your-business\.com|video--production\.com|video-hollywood\.ru|vskidku\.ru|webmonetizer\.net)";s:3:"why";s:13:"Referrer spam";s:5:"level";i:1;s:2:"on";i:1;}i:315;a:5:{s:5:"where";s:97:"GET|HTTP_HOST|SERVER_PROTOCOL|SERVER:HTTP_USER_AGENT|QUERY_STRING|SERVER:HTTP_REFERER|HTTP_COOKIE";s:4:"what";s:41:">\s*/dev/(?:tc|ud)p/[^/]{5,255}/\d{1,5}\b";s:3:"why";s:56:"/dev TCP/UDP device file access (possible reverse shell)";s:5:"level";i:3;s:2:"on";i:1;}i:316;a:5:{s:5:"where";s:148:"HTTP_CF_CONNECTING_IP|HTTP_CLIENT_IP|HTTP_FORWARDED|HTTP_FORWARDED_FOR|HTTP_INCAP_CLIENT_IP|HTTP_X_CLUSTER_CLIENT_IP|HTTP_X_FORWARDED|HTTP_X_REAL_IP";s:4:"what";s:8:"[<>\?\$]";s:3:"why";s:38:"Non-compliant IP found in HTTP headers";s:5:"level";i:2;s:2:"on";i:1;}i:317;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:30:"/\.[^/]+\.ph(?:p[345]?|t|tml)$";s:3:"why";s:17:"Hidden PHP script";s:5:"level";i:2;s:2:"on";i:1;}i:318;a:5:{s:5:"where";s:19:"SERVER:QUERY_STRING";s:4:"what";s:54:"com.opensymphony.xwork2.dispatcher.HttpServletResponse";s:3:"why";s:36:"Apache Struts2 remote code execution";s:5:"level";i:3;s:2:"on";i:1;}i:319;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:13:"\/e[\d]\.php$";s:3:"why";s:34:"Potential Ransom Crypwall backdoor";s:5:"level";i:3;s:2:"on";i:1;}i:350;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:188:"(?i:bypass|c99(?:madShell|ud)?|c100|cookie_(?:usage|setup)|diagnostics|dump|endix|gifimg|goog[l1]e.+[\da-f]{10}|imageth|imlog|r5[47]|safe0ver|sniper|(?:jpe?g|gif|png))\.ph(?:p[345]?|t|tml)";s:3:"why";s:14:"Shell/backdoor";s:5:"level";i:3;s:2:"on";i:1;}i:351;a:5:{s:5:"where";s:28:"GET:nixpasswd|POST:nixpasswd";s:4:"what";s:3:"^.?";s:3:"why";s:26:"Shell/backdoor (nixpasswd)";s:5:"level";i:3;s:2:"on";i:1;}i:352;a:5:{s:5:"where";s:12:"QUERY_STRING";s:4:"what";s:16:"\bact=img&img=\w";s:3:"why";s:20:"Shell/backdoor (img)";s:5:"level";i:3;s:2:"on";i:1;}i:353;a:5:{s:5:"where";s:12:"QUERY_STRING";s:4:"what";s:15:"\bc=img&name=\w";s:3:"why";s:21:"Shell/backdoor (name)";s:5:"level";i:3;s:2:"on";i:1;}i:354;a:5:{s:5:"where";s:12:"QUERY_STRING";s:4:"what";s:36:"^image=(?:arrow|file|folder|smiley)$";s:3:"why";s:22:"Shell/backdoor (image)";s:5:"level";i:3;s:2:"on";i:1;}i:355;a:5:{s:5:"where";s:6:"COOKIE";s:4:"what";s:21:"\buname=.+?;\ssysctl=";s:3:"why";s:23:"Shell/backdoor (cookie)";s:5:"level";i:3;s:2:"on";i:1;}i:356;a:5:{s:5:"where";s:30:"POST:sql_passwd|GET:sql_passwd";s:4:"what";s:1:".";s:3:"why";s:27:"Shell/backdoor (sql_passwd)";s:5:"level";i:3;s:2:"on";i:1;}i:357;a:5:{s:5:"where";s:12:"POST:nowpath";s:4:"what";s:3:"^.?";s:3:"why";s:24:"Shell/backdoor (nowpath)";s:5:"level";i:3;s:2:"on";i:1;}i:358;a:5:{s:5:"where";s:18:"POST:view_writable";s:4:"what";s:3:"^.?";s:3:"why";s:30:"Shell/backdoor (view_writable)";s:5:"level";i:3;s:2:"on";i:1;}i:359;a:5:{s:5:"where";s:6:"COOKIE";s:4:"what";s:13:"\bphpspypass=";s:3:"why";s:23:"Shell/backdoor (phpspy)";s:5:"level";i:3;s:2:"on";i:1;}i:360;a:5:{s:5:"where";s:6:"POST:a";s:4:"what";s:90:"^(?:Bruteforce|Console|Files(?:Man|Tools)|Network|Php|SecInfo|SelfRemove|Sql|StringTools)$";s:3:"why";s:18:"Shell/backdoor (a)";s:5:"level";i:3;s:2:"on";i:1;}i:361;a:5:{s:5:"where";s:12:"POST:nst_cmd";s:4:"what";s:2:"^.";s:3:"why";s:24:"Shell/backdoor (nstview)";s:5:"level";i:3;s:2:"on";i:1;}i:362;a:5:{s:5:"where";s:8:"POST:cmd";s:4:"what";s:206:"^(?:c(?:h_|URL)|db_query|echo\s\\.*|(?:edit|download|save)_file|find(?:_text|\s.+)|ftp_(?:brute|file_(?:down|up))|mail_file|mk|mysql(?:b|_dump)|php_eval|ps\s.*|search_text|safe_dir|sym[1-2]|test[1-8]|zend)$";s:3:"why";s:20:"Shell/backdoor (cmd)";s:5:"level";i:2;s:2:"on";i:1;}i:363;a:5:{s:5:"where";s:5:"GET:p";s:4:"what";s:65:"^(?:chmod|cmd|edit|eval|delete|headers|md5|mysql|phpinfo|rename)$";s:3:"why";s:18:"Shell/backdoor (p)";s:5:"level";i:3;s:2:"on";i:1;}i:364;a:5:{s:5:"where";s:12:"QUERY_STRING";s:4:"what";s:139:"^act=(?:bind|cmd|encoder|eval|feedback|ftpquickbrute|gofile|ls|mkdir|mkfile|processes|ps_aux|search|security|sql|tools|update|upload)&d=%2F";s:3:"why";s:20:"Shell/backdoor (act)";s:5:"level";i:3;s:2:"on";i:1;}i:365;a:5:{s:5:"where";s:10:"FILES:F1l3";s:4:"what";s:2:"^.";s:3:"why";s:22:"Potential PHP backdoor";s:5:"level";i:3;s:2:"on";i:1;}i:366;a:6:{s:5:"where";s:16:"POST:contenttype";s:4:"what";s:14:"(?:plain|html)";s:3:"why";s:29:"Potential mass-mailing script";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:4:"send";}}i:367;a:6:{s:5:"where";s:9:"REQUEST:p";s:4:"what";s:10:"^0da1090c$";s:3:"why";s:18:"Potential backdoor";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:1:"c";i:3;s:1:".";}}i:2;a:5:{s:5:"where";s:89:"GET|POST|COOKIE|SERVER:HTTP_USER_AGENT|SERVER:HTTP_REFERER|REQUEST_URI|PHP_SELF|PATH_INFO";s:4:"what";s:8:"%00|\x00";s:3:"why";s:32:"ASCII character 0x00 (NULL byte)";s:5:"level";i:3;s:2:"on";i:1;}i:500;a:5:{s:5:"where";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:20:"[\x01-\x08\x0e-\x1f]";s:3:"why";s:46:"ASCII control characters (1 to 8 and 14 to 31)";s:5:"level";i:2;s:2:"on";i:1;}i:510;a:5:{s:5:"where";s:20:"GET|POST|REQUEST_URI";s:4:"what";s:11:"/nothingyet";s:3:"why";s:45:"DOCUMENT_ROOT server variable in HTTP request";s:5:"level";i:2;s:2:"on";i:1;}i:520;a:5:{s:5:"where";s:58:"GET|POST|COOKIE|SERVER:HTTP_USER_AGENT|SERVER:HTTP_REFERER";s:4:"what";s:45:"\b(?i:ph(p|ar)://[a-z].+?|\bdata:.*?;base64,)";s:3:"why";s:21:"PHP built-in wrappers";s:5:"level";i:3;s:2:"on";i:1;}i:531;a:5:{s:5:"where";s:15:"HTTP_USER_AGENT";s:4:"what";s:338:"(?i:acunetix|analyzer|AhrefsBot|backdoor|bandit|blackwidow|BOT for JCE|collect|core-project|dts agent|emailmagnet|ex(ploit|tract)|flood|grabber|harvest|httrack|havij|hunter|indy library|inspect|LoadTimeBot|Microsoft URL Control|Miami Style|mj12bot|morfeus|nessus|NetLyzer|pmafind|scanner|siphon|spbot|sqlmap|survey|teleport|updown_tester)";s:3:"why";s:24:"Suspicious bots/scanners";s:5:"level";i:1;s:2:"on";i:1;}i:540;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:33:"^(?i:127\.0\.0\.1|localhost|::1)$";s:3:"why";s:32:"Localhost IP in GET/POST request";s:5:"level";i:2;s:2:"on";i:1;}i:1351;a:5:{s:5:"where";s:3:"GET";s:4:"what";s:14:"wp-config\.php";s:3:"why";s:31:"Access to WP configuration file";s:5:"level";i:2;s:2:"on";i:1;}i:1352;a:5:{s:5:"where";s:36:"GET:abspath|GET:ABSPATH|POST:ABSPATH";s:4:"what";s:2:"//";s:3:"why";s:32:"WordPress: Remote file inclusion";s:5:"level";i:3;s:2:"on";i:1;}i:1353;a:5:{s:5:"where";s:8:"POST:cs1";s:4:"what";s:2:"\D";s:3:"why";s:37:"WordPress: SQL injection (e-Commerce)";s:5:"level";i:3;s:2:"on";i:1;}i:1354;a:5:{s:5:"where";s:3:"GET";s:4:"what";s:66:"\b(?:wp_(?:users|options)|nfw_(?:options|rules)|ninjawp_options)\b";s:3:"why";s:36:"WordPress: SQL injection (WP tables)";s:5:"level";i:2;s:2:"on";i:1;}i:1355;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:96:"/plugins/buddypress/bp-(?:blogs|xprofile/bp-xprofile-admin|themes/bp-default/members/index)\.php";s:3:"why";s:39:"WordPress: path disclosure (buddypress)";s:5:"level";i:2;s:2:"on";i:1;}i:1356;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:14:"ToolsPack\.php";s:3:"why";s:29:"WordPress: ToolsPack backdoor";s:5:"level";i:3;s:2:"on";i:1;}i:1357;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:31:"preview-shortcode-external\.php";s:3:"why";s:41:"WordPress: WooThemes WooFramework exploit";s:5:"level";i:3;s:2:"on";i:1;}i:1358;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:46:"/plugins/(?:index|(?:hello-dolly/)?hello)\.php";s:3:"why";s:46:"WordPress: unauthorized access to a PHP script";s:5:"level";i:2;s:2:"on";i:1;}i:1359;a:5:{s:5:"where";s:4:"POST";s:4:"what";s:48:"<!--(?:m(?:clude|func)|dynamic-cached-content)\b";s:3:"why";s:26:"WordPress: Dynamic content";s:5:"level";i:3;s:2:"on";i:1;}i:1360;a:5:{s:5:"where";s:16:"POST:acf_abspath";s:4:"what";s:1:".";s:3:"why";s:44:"WordPress: Advanced Custom Fields plugin RFI";s:5:"level";i:3;s:2:"on";i:1;}i:1361;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:78:"/wp-content/themes/(?:eCommerce|eShop|KidzStore|storefront)/upload/upload\.php";s:3:"why";s:31:"WordPress: Access to upload.php";s:5:"level";i:3;s:2:"on";i:1;}i:1362;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:85:"/wp-content/themes/OptimizePress/lib/admin/media-upload(?:-lncthumb|-sq_button)?\.php";s:3:"why";s:48:"WordPress: Access to OptimizePress upload script";s:5:"level";i:3;s:2:"on";i:1;}i:1363;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:15:"/uploadify\.php";s:3:"why";s:37:"WordPress: Access to Uploadify script";s:5:"level";i:3;s:2:"on";i:1;}i:1364;a:6:{s:5:"where";s:7:"GET:img";s:4:"what";s:6:"\.php$";s:3:"why";s:66:"WordPress: Revolution Slider vulnerability (local file disclosure)";s:5:"level";i:2;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:6:"action";i:3;s:21:"^revslider_show_image";}}i:1365;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:20:"/code_generator\.php";s:3:"why";s:62:"WordPress: Gravity Forms vulnerability (arbitrary file upload)";s:5:"level";i:3;s:2:"on";i:1;}i:1366;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:22:"/wp-admin/install\.php";s:3:"why";s:40:"WordPress: Access to WP installer script";s:5:"level";i:2;s:2:"on";i:1;}i:1367;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:21:"/temp/update_extract/";s:3:"why";s:59:"WordPress: Revolution Slider potential shell upload exploit";s:5:"level";i:3;s:2:"on";i:1;}i:1368;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:14:"/dl-skin\.php$";s:3:"why";s:60:"WordPress: arbitrary file access vulnerability (dl-skin.php)";s:5:"level";i:3;s:2:"on";i:1;}i:1369;a:6:{s:5:"where";s:12:"POST:execute";s:4:"what";s:15:"[^degiklmnptw_]";s:3:"why";s:52:"WordPress: Download Manager remote command execution";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:15:"^wpdm_ajax_call";}}i:1370;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:23:"/RedSteel/download.php$";s:3:"why";s:63:"WordPress: arbitrary file access vulnerability (RedSteel theme)";s:5:"level";i:3;s:2:"on";i:1;}i:1371;a:5:{s:5:"where";s:8:"GET:page";s:4:"what";s:22:"fancybox-for-wordpress";s:3:"why";s:32:"WordPress: Fancybox 0day attempt";s:5:"level";i:3;s:2:"on";i:1;}i:1372;a:5:{s:5:"where";s:8:"GET:task";s:4:"what";s:17:"wpdm_upload_files";s:3:"why";s:63:"WordPress: Download Manager unauthenticated file upload attempt";s:5:"level";i:3;s:2:"on";i:1;}i:1373;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:37:"/modules/export/templates/export\.php";s:3:"why";s:58:"WordPress: WP Ultimate CSV Importer information disclosure";s:5:"level";i:3;s:2:"on";i:1;}i:1374;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:25:"/wp-symposium/server/php/";s:3:"why";s:36:"WordPress: WP Symposium shell upload";s:5:"level";i:3;s:2:"on";i:1;}i:1375;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:36:"/filedownload/download.php/index.php";s:3:"why";s:44:"WordPress: Filedownload plugin vulnerability";s:5:"level";i:3;s:2:"on";i:1;}i:1376;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:23:"/admin/upload-file\.php";s:3:"why";s:54:"WordPress: Holding Pattern theme arbitrary file upload";s:5:"level";i:3;s:2:"on";i:1;}i:1377;a:5:{s:5:"where";s:26:"REQUEST:users_can_register";s:4:"what";s:2:"^.";s:3:"why";s:48:"WordPress: possible privilege escalation attempt";s:5:"level";i:3;s:2:"on";i:1;}i:1378;a:5:{s:5:"where";s:20:"REQUEST:default_role";s:4:"what";s:2:"^.";s:3:"why";s:48:"WordPress: possible privilege escalation attempt";s:5:"level";i:3;s:2:"on";i:1;}i:1379;a:5:{s:5:"where";s:19:"REQUEST:admin_email";s:4:"what";s:2:"^.";s:3:"why";s:48:"WordPress: possible privilege escalation attempt";s:5:"level";i:3;s:2:"on";i:1;}i:1380;a:6:{s:5:"where";s:21:"GET:orderby|GET:order";s:4:"what";s:7:"[^a-z_]";s:3:"why";s:44:"WordPress: SEO by Yoast plugin SQL injection";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:4:"page";i:3;s:18:"^wpseo_bulk-editor";}}i:1381;a:5:{s:5:"where";s:11:"POST:action";s:4:"what";s:17:"icl_msync_confirm";s:3:"why";s:52:"WordPress: WPML plugin database modification attempt";s:5:"level";i:3;s:2:"on";i:1;}i:1382;a:6:{s:5:"where";s:8:"POST:log";s:4:"what";s:33:"^(?:systemwpadmin|badmin|obuser)$";s:3:"why";s:41:"Admin dashboard possible break-in attempt";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:3:"pwd";i:3;s:2:"^.";}}i:1383;a:6:{s:5:"where";s:14:"REQUEST:action";s:4:"what";s:54:"^(?:rev(?:olution_)?slider|showbiz)[_-]ajax[_-]action$";s:3:"why";s:59:"WordPress: Revolution Slider/Showbiz potential shell upload";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:13:"client_action";i:3;s:2:"^.";}}i:1384;a:6:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:16:"/admin-post\.php";s:3:"why";s:56:"WordPress: Google Analytics by Yoast stored XSS (reauth)";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:6:"reauth";i:3;s:2:"^.";}}i:1385;a:6:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:16:"/admin-post\.php";s:3:"why";s:66:"WordPress: Google Analytics by Yoast stored XSS (google_auth_code)";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:16:"google_auth_code";i:3;s:2:"^.";}}i:1386;a:6:{s:5:"where";s:19:"SERVER:HTTP_REFERER";s:4:"what";s:14:"\blang=..[^&]+";s:3:"why";s:36:"WordPress: WPML plugin SQL injection";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:8:"POST|GET";i:2;s:6:"action";i:3;s:13:"^wp-link-ajax";}}i:1387;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:20:"/sam-ajax-admin\.php";s:3:"why";s:67:"WordPress: unauthorized access to a PHP script (Simple Ads Manager)";s:5:"level";i:3;s:2:"on";i:1;}i:1388;a:6:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:22:"/server/php/index\.php";s:3:"why";s:67:"WordPress: unauthorized access to a PHP script (jQuery File Upload)";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:7:"^upload";}}i:1389;a:6:{s:5:"where";s:21:"GET:orderby|GET:order";s:4:"what";s:7:"[^a-z_]";s:3:"why";s:63:"WordPress: All-In-One-WP-Security-Firewall plugin SQL injection";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:4:"page";i:3;s:9:"^aiowpsec";}}i:1390;a:6:{s:5:"where";s:14:"REQUEST:action";s:4:"what";s:12:"ae-sync-user";s:3:"why";s:46:"WordPress: QAEngine Theme privilege escalation";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:6:"method";i:3;s:31:"^(?:create|update|remove|read)$";}}i:1391;a:6:{s:5:"where";s:12:"REQUEST:page";s:4:"what";s:20:"^pmxi-admin-settings";s:3:"why";s:37:"WordPress: WP All Import shell upload";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:6:"action";i:3;s:7:"^upload";}}i:1392;a:6:{s:5:"where";s:21:"POST:duplicator_delid";s:4:"what";s:6:"[^\d,]";s:3:"why";s:42:"WordPress: Duplicator plugin SLQ injection";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:6:"action";i:3;s:26:"^duplicator_package_delete";}}i:1393;a:5:{s:5:"where";s:4:"POST";s:4:"what";s:11:"="]">\["\s.";s:3:"why";s:41:"WordPress 3.x persistent script injection";s:5:"level";i:3;s:2:"on";i:1;}i:1394;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:27:"/includes/fileupload/files/";s:3:"why";s:53:"WordPress Creative Contact Form arbitrary file upload";s:5:"level";i:3;s:2:"on";i:1;}i:1395;a:5:{s:5:"where";s:14:"REQUEST:action";s:4:"what";s:25:"^crayon-theme-editor-save";s:3:"why";s:56:"WordPress: Crayon Syntax Highlighter theme editor access";s:5:"level";i:3;s:2:"on";i:1;}i:1396;a:5:{s:5:"where";s:11:"REQUEST_URI";s:4:"what";s:22:"%3C(?i:script\b).*?%3E";s:3:"why";s:28:"WordPress: XSS (REQUEST_URI)";s:5:"level";i:2;s:2:"on";i:1;}i:1397;a:5:{s:5:"where";s:21:"REQUEST:mashsb-action";s:4:"what";s:2:"^.";s:3:"why";s:50:"WordPress: Mashshare plugin information disclosure";s:5:"level";i:3;s:2:"on";i:1;}i:1398;a:6:{s:5:"where";s:24:"POST:user_id_social_site";s:4:"what";s:4:"^\d+";s:3:"why";s:61:"WordPress: Pie Register plugin potential privilege escalation";s:5:"level";i:2;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:11:"social_site";i:3;s:6:"^true$";}s:2:"on";i:1;}i:1399;a:6:{s:5:"where";s:18:"GET:invitaion_code";s:4:"what";s:11:"^(?:Jyk|PH)";s:3:"why";s:54:"WordPress Pie Register plugin base64-encoded injection";s:5:"level";i:3;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:16:"show_dash_widget";i:3;s:2:"^1";}s:2:"on";i:1;}i:1400;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:14:"/example\.html";s:3:"why";s:21:"WordPress <4.2.2: XSS";s:5:"level";i:3;s:2:"on";i:1;}i:1401;a:6:{s:5:"where";s:47:"GET:delete_backup_file|GET:download_backup_file";s:4:"what";s:2:"^.";s:3:"why";s:67:"WordPress: Simple Backup plugin arbitrary file download or deletion";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:4:"page";i:3;s:16:"^backup_manager$";}}i:1402;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:31:"/contus-video-gallery/email.php";s:3:"why";s:58:"WordPress: Video Gallery plugin potential spamming attempt";s:5:"level";i:2;s:2:"on";i:1;}i:1403;a:5:{s:5:"where";s:13:"POST:sm_email";s:4:"what";s:1:"<";s:3:"why";s:65:"WordPress: MailChimp Subscribe Forms plugin remote code execution";s:5:"level";i:3;s:2:"on";i:1;}i:1404;a:6:{s:5:"where";s:8:"GET:post";s:4:"what";s:2:"\D";s:3:"why";s:44:"WordPress Landing Pages plugin SQL injection";s:5:"level";i:3;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:15:"lp-variation-id";i:3;s:2:"^.";}s:2:"on";i:1;}i:1405;a:6:{s:5:"where";s:32:"GET:where1|GET:where2|GET:where3";s:4:"what";s:6:"[^a-z]";s:3:"why";s:46:"WordPress NewStatPress plugin SQLi/XSS attempt";s:5:"level";i:3;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:4:"page";i:3;s:12:"^nsp_search$";}s:2:"on";i:1;}i:1406;a:6:{s:5:"where";s:11:"POST:value_";s:4:"what";s:1:"<";s:3:"why";s:40:"WordPress Free Counter plugin stored XSS";s:5:"level";i:3;s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:6:"action";i:3;s:12:"^check_stat$";}s:2:"on";i:1;}i:1407;a:6:{s:5:"where";s:8:"GET:page";s:4:"what";s:17:"^wysija_campaigns";s:3:"why";s:46:"WordPress MailPoet unauthenticated file upload";s:5:"level";i:3;s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:6:"action";i:3;s:7:"^themes";}s:2:"on";i:1;}i:1408;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:20:"/wp-content/gallery/";s:3:"why";s:47:"WordPress NextGEN-Gallery arbitrary file upload";s:5:"level";i:3;s:2:"on";i:1;}i:1409;a:6:{s:5:"where";s:14:"REQUEST:action";s:4:"what";s:16:"at_async_loading";s:3:"why";s:48:"WordPress AddThis Sharing Buttons persistent XSS";s:5:"level";i:3;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:5:"pubid";i:3;s:1:"<";}s:2:"on";i:1;}i:1410;a:6:{s:5:"where";s:14:"REQUEST:action";s:4:"what";s:20:"^of_ajax_post_action";s:3:"why";s:48:"WordPress: Potential theme remote code execution";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:4:"type";i:3;s:5:"^save";}}i:1411;a:6:{s:5:"where";s:12:"REQUEST:name";s:4:"what";s:5:"\.php";s:3:"why";s:45:"WordPress: Gravity Form arbitrary file upload";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:7:"gf_page";i:3;s:7:"^upload";}}i:1412;a:6:{s:5:"where";s:19:"POST:cpd_keep_month";s:4:"what";s:2:"\D";s:3:"why";s:43:"WordPress Count per Day plugin SQLi attempt";s:5:"level";i:3;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:3:"tab";i:3;s:7:"^tools$";}s:2:"on";i:1;}i:1413;a:6:{s:5:"where";s:12:"POST:content";s:4:"what";s:41:"\shref\s*=\s*"\s*\[caption[^\]]+\][^"]+?<";s:3:"why";s:30:"WordPress <4.2.3 potential XSS";s:5:"level";i:3;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:10:"^editpost$";}s:2:"on";i:1;}i:1414;a:5:{s:5:"where";s:32:"POST:aiowps_unlock_request_email";s:4:"what";s:17:"[^a-zA-Z0-9+_.@-]";s:3:"why";s:63:"All In One WP Security Firewall plugin <3.9.8 XSS vulnerability";s:5:"level";i:3;s:2:"on";i:1;}i:1415;a:6:{s:5:"where";s:119:"POST:ga_adsense|POST:ga_admin_disable_DimentionIndex|POST:ga_downloads_prefix|POST:ga_downloads|POST:ga_outbound_prefix";s:4:"what";s:8:"['"()<=]";s:3:"why";s:56:"WordPress Google Analyticator <6.4.9.6 XSS vulnerability";s:5:"level";i:3;s:5:"extra";a:3:{i:1;s:3:"GET";i:2;s:4:"page";i:3;s:21:"^google-analyticator$";}s:2:"on";i:1;}i:1416;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:21:"/landing-pages/tests/";s:3:"why";s:52:"WordPress Landing Pages Plugin remote code execution";s:5:"level";i:3;s:2:"on";i:1;}i:1417;a:5:{s:5:"where";s:15:"GET:abdullkarem";s:4:"what";s:2:"^.";s:3:"why";s:14:"Suspicious bot";s:5:"level";i:3;s:2:"on";i:1;}i:1419;a:5:{s:5:"where";s:23:"REQUEST:HTTP_POTKBOAUTQ";s:4:"what";s:2:"^.";s:3:"why";s:18:"Potential backdoor";s:5:"level";i:3;s:2:"on";i:1;}i:1420;a:6:{s:5:"where";s:8:"POST:url";s:4:"what";s:5:"^[^h]";s:3:"why";s:36:"WordPress Font plugin path traversal";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:22:"^cross_domain_request$";}}i:1421;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:37:"/plugins/(?:[dD]ocs|wp-db-ajax-made)/";s:3:"why";s:26:"WordPress malicious plugin";s:5:"level";i:3;s:2:"on";i:1;}i:1422;a:6:{s:5:"where";s:9:"POST:file";s:4:"what";s:27:"^(?:ninjafirewall|nfwplus)/";s:3:"why";s:34:"Attempt to edit NinjaFirewall code";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:8:"^update$";}}i:1423;a:6:{s:5:"where";s:10:"POST:items";s:4:"what";s:12:"\$items\s*\[";s:3:"why";s:41:"Form Manager <1.7.3 remote code execution";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:14:"^fm_save_form$";}}i:1424;a:6:{s:5:"where";s:12:"POST:poll_id";s:4:"what";s:2:"\D";s:3:"why";s:38:"WP Fastest Cache 0.8.4.8 SQL Injection";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:6:"action";i:3;s:27:"^wpfc_wppolls_ajax_request$";}}i:1425;a:6:{s:5:"where";s:12:"POST:comment";s:4:"what";s:116:"<(?is)((a|abbr|acronym)\b.+?title|(blockquote|q)\b.+?cite|del\b.+?datetime)\s*=\s*['"][^>]+?on[a-z]{3,18}\s*=\s*.+?>";s:3:"why";s:41:"Potential XSS via WordPress comments form";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:6:"author";i:3;s:2:"^.";}}i:1426;a:6:{s:5:"where";s:12:"POST:comment";s:4:"what";s:13:"^(?s).{65000}";s:3:"why";s:41:"WordPress comments form payload too large";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:4:"POST";i:2;s:6:"author";i:3;s:2:"^.";}}i:1427;a:5:{s:5:"where";s:18:"GET:items_per_page";s:4:"what";s:2:"\D";s:3:"why";s:33:"Shoppica theme PHP code injection";s:5:"level";i:3;s:2:"on";i:1;}i:1428;a:5:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:25:"/plugins/Adsense_high_CPC";s:3:"why";s:26:"WordPress malicious plugin";s:5:"level";i:3;s:2:"on";i:1;}i:1429;a:6:{s:5:"where";s:22:"REQUEST:cpabc_ipncheck";s:4:"what";s:3:"^1$";s:3:"why";s:43:"WordPress Appointment Booking Calendar SQLi";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:10:"itemnumber";i:3;s:2:"\D";}}i:1430;a:5:{s:5:"where";s:14:"GET:wysija-key";s:4:"what";s:1:"<";s:3:"why";s:34:"WordPress MailPoet Newsletters XSS";s:5:"level";i:3;s:2:"on";i:1;}i:1431;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:17:"^wp_capabilities$";s:3:"why";s:41:"WordPress privilege escalation attempt #4";s:5:"level";i:3;s:2:"on";i:1;}i:1432;a:5:{s:5:"where";s:8:"GET|POST";s:4:"what";s:28:"[{;]s:13:"administrator"[;}]";s:3:"why";s:41:"WordPress privilege escalation attempt #5";s:5:"level";i:3;s:2:"on";i:1;}i:1433;a:6:{s:5:"where";s:11:"SCRIPT_NAME";s:4:"what";s:20:"/wp-admin/index\.php";s:3:"why";s:49:"WordPress Bulk Delete plugin privilege escalation";s:5:"level";i:3;s:2:"on";i:1;s:5:"extra";a:3:{i:1;s:7:"REQUEST";i:2;s:9:"bd_action";i:3;s:61:"^delete_(?:pages_by_status|posts_by_post_type|users_by_meta)$";}}i:999;a:13:{i:316;i:1;i:1380;i:1;i:1389;i:1;i:1392;i:1;i:1396;i:1;i:1400;i:1;i:1404;i:1;i:1405;i:1;i:1412;i:1;i:1413;i:1;i:1415;i:1;i:1422;i:1;i:1430;i:1;}}