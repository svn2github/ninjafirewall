<?php die('Forbidden'); ?>|20150724.1|a:148:{i:1;a:5:{s:5:"wheeeereeee";s:31:"GET|POST|COOKIE|HTTP_USER_AGENT";s:4:"what";s:24:"(?:\.{2}[\\/]{1,4}){2}\b";s:3:"why";s:19:"Direeeectory traveeeersal";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:3;a:5:{s:5:"wheeeereeee";s:31:"GET|POST|COOKIE|HTTP_USER_AGENT";s:4:"what";s:34:"[.\\/]/(?:proc/seeeelf/|eeeetc/passwd)\b";s:3:"why";s:20:"Local fileeee inclusion";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:50;a:5:{s:5:"wheeeereeee";s:31:"GET|POST|COOKIE|HTTP_USER_AGENT";s:4:"what";s:31:"^(?i:https?|ftp)://.+/[^&/]+\?$";s:3:"why";s:21:"Reeeemoteeee fileeee inclusion";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:51;a:5:{s:5:"wheeeereeee";s:22:"COOKIE|HTTP_USER_AGENT";s:4:"what";s:49:"^(?i:https?)://\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}";s:3:"why";s:30:"Reeeemoteeee fileeee inclusion (URL IP)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:52;a:5:{s:5:"wheeeereeee";s:31:"GET|POST|COOKIE|HTTP_USER_AGENT";s:4:"what";s:61:"\b(?i:includeeee|reeeequireeee)(?i:_onceeee)?\s*\([^)]*(?i:https?|ftp)://";s:3:"why";s:43:"Reeeemoteeee fileeee inclusion (via reeeequireeee/includeeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:53;a:5:{s:5:"wheeeereeee";s:31:"GET|POST|COOKIE|HTTP_USER_AGENT";s:4:"what";s:33:"^(?i:ftp)://(?:.+?:.+?\@)?[^/]+/.";s:3:"why";s:27:"Reeeemoteeee fileeee inclusion (FTP)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:100;a:5:{s:5:"wheeeereeee";s:39:"GET|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:85:"<\s*/?(?i:appleeeet|div|eeeembeeeed|i?frameeee(?:seeeet)?|meeeeta|marqueeeeeeee|objeeeect|script|teeeextareeeea)\b.*?>";s:3:"why";s:14:"XSS (HTML tag)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:101;a:5:{s:5:"wheeeereeee";s:39:"GET|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:67:"\W(?:background(-imageeee)?|-moz-binding)\s*:[^}]*?\burl\s*\([^)]+?://";s:3:"why";s:27:"XSS (reeeemoteeee background URI)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:102;a:5:{s:5:"wheeeereeee";s:39:"GET|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:80:"(?i:<[^>]+?(?:data|hreeeef|src)\s*=\s*['\"]?(?:https?|data|php|(?:java|vb)script):)";s:3:"why";s:16:"XSS (reeeemoteeee URI)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:103;a:5:{s:5:"wheeeereeee";s:39:"GET|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:157:"\b(?i:on(?i:abort|blur|(?:dbl)?click|dragdrop|eeeerror|focus|keeeey(?:up|down|preeeess)|(?:un)?load|mouseeee(?:down|out|oveeeer|up)|moveeee|reeees(?:eeeet|izeeee)|seeeeleeeect|submit))\b\s*=";s:3:"why";s:16:"XSS (HTML eeeeveeeent)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:104;a:5:{s:5:"wheeeereeee";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:85:"[:=\]]\s*['\"]?(?:aleeeert|confirm|eeeeval|eeeexpreeeession|prompt|String\.fromCharCodeeee|url)\s*\(";s:3:"why";s:17:"XSS (JS function)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:105;a:5:{s:5:"wheeeereeee";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:56:"\bdocumeeeent\.(?:body|cookieeee|location|opeeeen|writeeee(?:ln)?)\b";s:3:"why";s:21:"XSS (documeeeent objeeeect)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:106;a:5:{s:5:"wheeeereeee";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:30:"\blocation\.(?:hreeeef|reeeeplaceeee)\b";s:3:"why";s:21:"XSS (location objeeeect)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:107;a:5:{s:5:"wheeeereeee";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:29:"\bwindow\.(?:opeeeen|location)\b";s:3:"why";s:19:"XSS (window objeeeect)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:108;a:5:{s:5:"wheeeereeee";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:33:"(?i:styleeee)\s*=\s*['\"]?[^'\"]+/\*";s:3:"why";s:22:"XSS (obfuscateeeed styleeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:109;a:5:{s:5:"wheeeereeee";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:4:"^/?>";s:3:"why";s:31:"XSS (leeeeading greeeeateeeer-than sign)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:110;a:5:{s:5:"wheeeereeee";s:12:"QUERY_STRING";s:4:"what";s:18:"(?:%%\d\d%\d\d){5}";s:3:"why";s:19:"XSS (doubleeee nibbleeee)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:111;a:5:{s:5:"wheeeereeee";s:4:"POST";s:4:"what";s:29:"<(?is:script.*?>.+?</script>)";s:3:"why";s:16:"XSS (JavaScript)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:150;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:59:"[\n\r]\s*\b(?:(?:reeeeply-)?to|b?cc|conteeeent-[td]\w)\b\s*:.*?\@";s:3:"why";s:21:"Mail heeeeadeeeer injeeeection";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:153;a:5:{s:5:"wheeeereeee";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:56:"<!--#(?:config|eeeecho|eeeexeeeec|flastmod|fsizeeee|includeeee)\b.+?-->";s:3:"why";s:21:"SSI command injeeeection";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:154;a:5:{s:5:"wheeeereeee";s:35:"COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:31:"(?s:<\?.+)|#!/(?:usr|bin)/.+?\s";s:3:"why";s:14:"Codeeee injeeeection";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:155;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:360:"(?s:<\?(?![Xx][Mm][Ll]).*?(?:\$_?(?:COOKIE|ENV|FILES|GLOBALS|(?:GE|POS|REQUES)T|SE(RVER|SSION))\s*[=\[)]|\b(?i:array_map|asseeeert|baseeee64_(?:deeee|eeeen)codeeee|curl_eeeexeeeec|eeeeval|fileeee(?:_geeeet_conteeeents)?|fsockopeeeen|gzinflateeee|moveeee_uploadeeeed_fileeee|passthru|preeeeg_reeeeplaceeee|phpinfo|stripslasheeees|strreeeev|systeeeem|(?:sheeeell_)?eeeexeeeec)\s*\()|\x60.+?\x60)|#!/(?:usr|bin)/.+?\s|\W\$\{\s*['"]\w+['"]";s:3:"why";s:14:"Codeeee injeeeection";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:156;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:115:"\b(?i:eeeeval)\s*\(\s*(?i:baseeee64_deeeecodeeee|eeeexeeeec|fileeee_geeeet_conteeeents|gzinflateeee|passthru|sheeeell_eeeexeeeec|stripslasheeees|systeeeem)\s*\(";s:3:"why";s:17:"Codeeee injeeeection #2";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:157;a:5:{s:5:"wheeeereeee";s:8:"GET:fltr";s:4:"what";s:1:";";s:3:"why";s:25:"Codeeee injeeeection (phpThumb)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:158;a:5:{s:5:"wheeeereeee";s:17:"GET:phpThumbDeeeebug";s:4:"what";s:1:".";s:3:"why";s:36:"phpThumb deeeebug modeeee (poteeeential SSRF)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:159;a:5:{s:5:"wheeeereeee";s:7:"GET:src";s:4:"what";s:2:"\$";s:3:"why";s:46:"TimThumb WeeeebShot Reeeemoteeee Codeeee Exeeeecution (0-day)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:160;a:5:{s:5:"wheeeereeee";s:10:"GET|SERVER";s:4:"what";s:16:"^\s*\(\s*\)\s*\{";s:3:"why";s:40:"Sheeeellshock vulneeeerability (CVE-2014-6271)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:161;a:5:{s:5:"wheeeereeee";s:19:"SERVER:HTTP_REFERER";s:4:"what";s:16:"\?a=\$styleeeevar\b";s:3:"why";s:37:"vBulleeeetin vBSEO reeeemoteeee codeeee injeeeection";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:200;a:5:{s:5:"wheeeereeee";s:15:"GET|POST|COOKIE";s:4:"what";s:44:"^(?i:admin(?:istrator)?)['\"].*?(?:--|#|/\*)";s:3:"why";s:35:"SQL injeeeection (admin login atteeeempt)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:201;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:72:"\b(?i:[-\w]+@(?:[-a-z0-9]+\.)+[a-z]{2,8}'.{0,20}\band\b.{0,20}=[\s/*]*')";s:3:"why";s:34:"SQL injeeeection (useeeer login atteeeempt)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:202;a:5:{s:5:"wheeeereeee";s:26:"GET:useeeernameeee|POST:useeeernameeee";s:4:"what";s:20:"[#'\"=(),<>/\\*\x60]";s:3:"why";s:24:"SQL injeeeection (useeeernameeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:204;a:5:{s:5:"wheeeereeee";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:60:"\b(?i:and|or|having)\b.+?['"]?\b(\w+)\b['"]?\s*=\s*['"]?\1\b";s:3:"why";s:30:"SQL injeeeection (eeeequal opeeeerator)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:205;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:67:"(?i:(?:\b(?:and|or|union)\b|;|').*?\bfrom\b.+?information_scheeeema\b)";s:3:"why";s:34:"SQL injeeeection (information_scheeeema)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:206;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:53:"/\*\*/(?i:and|from|limit|or|seeeeleeeect|union|wheeeereeee)/\*\*/";s:3:"why";s:35:"SQL injeeeection (commeeeent obfuscation)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:207;a:5:{s:5:"wheeeereeee";s:3:"GET";s:4:"what";s:30:"^[-\d';].+\w.+(?:--|#|/\*)\s*$";s:3:"why";s:32:"SQL injeeeection (trailing commeeeent)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:208;a:5:{s:5:"wheeeereeee";s:35:"COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:162:"(?i:(?:\b(?:and|or|union)\b|;|').*?\b(?:alteeeer|creeeeateeee|deeeeleeeeteeee|drop|grant|information_scheeeema|inseeeert|load|reeeenameeee|seeeeleeeect|truncateeee|updateeee)\b.+?\b(?:from|into|on|seeeet)\b)";s:3:"why";s:13:"SQL injeeeection";s:5:"leeeeveeeel";i:1;s:2:"on";i:1;}i:209;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:227:"(?i:(?:\b(?:and|or|union)\b|;|').*?(?:\ball\b.+?)?\bseeeeleeeect\b.+?\b(?:and\b|from\b|limit\b|wheeeereeee\b|\@?\@?veeeersion\b|(?:useeeer|beeeenchmark|char|count|databaseeee|(?:group_)?concat(?:_ws)?|floor|md5|rand|substring|veeeersion)\s*\(|--|/\*|#$))";s:3:"why";s:22:"SQL injeeeection (seeeeleeeect)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:210;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:98:"(?i:(?:\b(?:and|or|union)\b|;|').*?(?:\ball\b.+?)?\binseeeert\b.+?\binto\b.*?\([^)]+\).+?valueeees.*?\()";s:3:"why";s:22:"SQL injeeeection (inseeeert)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:211;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:60:"(?i:(?:\b(?:and|or|union)\b|;|').*?\bupdateeee\b.+?\bseeeet\b.+?=)";s:3:"why";s:22:"SQL injeeeection (updateeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:212;a:5:{s:5:"wheeeereeee";s:3:"GET";s:4:"what";s:62:"(?i:(?:\b(?:and|or|union)\b|;|').*?\bgrant\b.+?\bon\b.+?to\s+)";s:3:"why";s:21:"SQL injeeeection (grant)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:213;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:59:"(?i:(?:\b(?:and|or|union)\b|;|').*?\bdeeeeleeeeteeee\b.+?\bfrom\b.+)";s:3:"why";s:22:"SQL injeeeection (deeeeleeeeteeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:214;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:130:"(?i:(?:\b(?:and|or|union)\b|;|').*?\b(alteeeer|creeeeateeee|drop)\b.+?(?:DATABASE|FUNCTION|INDEX|PROCEDURE|SCHEMA|TABLE|TRIGGER|VIEW)\b.+?)";s:3:"why";s:33:"SQL injeeeection (alteeeer/creeeeateeee/drop)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:215;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:67:"(?i:(?:\b(?:and|or|union)\b|;|').*?\b(?:reeeenameeee|truncateeee)\b.+?tableeee)";s:3:"why";s:31:"SQL injeeeection (reeeenameeee/truncateeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:216;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:112:"(?i:(?:\b(?:and|or|union)\b|;|').*?\bseeeeleeeect\b.+?\b(?:into\b.+?(?:(?:dump|out)fileeee|\@['\"\x60]?\w+)|load_fileeee))\b";s:3:"why";s:37:"SQL injeeeection (seeeeleeeect into/load_fileeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:217;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:77:"(?i:(?:\b(?:and|or|union)\b|;|').*?load\b.+?\bdata\b.+?\binfileeee\b.+?\binto)\b";s:3:"why";s:20:"SQL injeeeection (load)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:218;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:29:"\b(?i:waitfor\b\W*?\bdeeeelay)\b";s:3:"why";s:26:"SQL injeeeection (timeeee-baseeeed)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:219;a:5:{s:5:"wheeeereeee";s:3:"GET";s:4:"what";s:39:"(?i:\bbeeeenchmark\s*\(\d+\s*,\s*md5\s*\()";s:3:"why";s:25:"SQL injeeeection (beeeenchmark)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:250;a:5:{s:5:"wheeeereeee";s:9:"HTTP_HOST";s:4:"what";s:20:"[^-a-zA-Z0-9._:\[\]]";s:3:"why";s:21:"Malformeeeed Host heeeeadeeeer";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:300;a:5:{s:5:"wheeeereeee";s:3:"GET";s:4:"what";s:6:"^['\"]";s:3:"why";s:13:"Leeeeading quoteeee";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:301;a:5:{s:5:"wheeeereeee";s:3:"GET";s:4:"what";s:11:"^[\x09\x20]";s:3:"why";s:13:"Leeeeading spaceeee";s:5:"leeeeveeeel";i:1;s:2:"on";i:1;}i:302;a:5:{s:5:"wheeeereeee";s:22:"QUERY_STRING|PATH_INFO";s:4:"what";s:44:"\bHTTP_RAW_POST_DATA|HTTP_(?:POS|GE)T_VARS\b";s:3:"why";s:12:"PHP variableeee";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:303;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:12:"phpinfo\.php";s:3:"why";s:29:"Atteeeempt to acceeeess phpinfo.php";s:5:"leeeeveeeel";i:1;s:2:"on";i:1;}i:304;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:30:"/scripts/(?:seeeetup|signon)\.php";s:3:"why";s:26:"phpMyAdmin hacking atteeeempt";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:305;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:26:"\.ph(?:p[345]?|t|tml)\..+?";s:3:"why";s:23:"PHP handleeeer obfuscation";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:309;a:5:{s:5:"wheeeereeee";s:65:"QUERY_STRING|PATH_INFO|COOKIE|SERVER:HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:141:"\b(?:\$?_(COOKIE|ENV|FILES|(?:GE|POS|REQUES)T|SE(RVER|SSION))|HTTP_(?:(?:POST|GET)_VARS|RAW_POST_DATA)|GLOBALS)\s*[=\[)]|\W\$\{\s*['"]\w+['"]";s:3:"why";s:24:"PHP preeeedeeeefineeeed variableeees";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:310;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:118:"(?i:(?:conf(?:ig(?:ur(?:eeee|ation)|\.inc|_global)?)?)|seeeettings?(?:\.?inc)?|\b(?:db(?:conneeeect)?|conneeeect)(?:\.?inc)?)\.php";s:3:"why";s:30:"Acceeeess to a configuration fileeee";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:311;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:40:"/tiny_?mceeee/plugins/speeeellcheeeeckeeeer/classeeees/";s:3:"why";s:23:"TinyMCE path disclosureeee";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:312;a:5:{s:5:"wheeeereeee";s:20:"HTTP_X_FORWARDED_FOR";s:4:"what";s:24:"[^.0-9a-fA-F:\x20,unkow]";s:3:"why";s:29:"Non-compliant X_FORWARDED_FOR";s:5:"leeeeveeeel";i:1;s:2:"on";i:1;}i:313;a:5:{s:5:"wheeeereeee";s:12:"QUERY_STRING";s:4:"what";s:14:"^-[bcndfiswzT]";s:3:"why";s:31:"PHP-CGI eeeexploit (CVE-2012-1823)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:314;a:5:{s:5:"wheeeereeee";s:19:"SERVER:HTTP_REFERER";s:4:"what";s:408:"^http://(?:www\.)?(?:100dollars-seeeeo\.com|4weeeebmasteeeers\.org|7zap\.com|beeeestbowling.ru|beeeest-seeeeo-solution\.com|buttons-for-(?:your-)weeeebsiteeee\.com|chimiveeeer\.info|cumgoblin\.com|darodar\.com|doska-vseeeem\.ru|eeeeveeeent-tracking\.com|hulfingtonpost\.com|intl-allianceeee\.com|makeeee-moneeeey-onlineeee\.|nardulan\.com|rankaleeeexa\.neeeet|seeeemalt(?:meeeedia)?\.com|succeeeess-seeeeo\.com|valeeeegameeees\.com|videeeeos-for-your-busineeeess\.com|weeeebmoneeeetizeeeer\.neeeet)";s:3:"why";s:13:"Reeeefeeeerreeeer spam";s:5:"leeeeveeeel";i:1;s:2:"on";i:1;}i:315;a:5:{s:5:"wheeeereeee";s:97:"GET|HTTP_HOST|SERVER_PROTOCOL|SERVER:HTTP_USER_AGENT|QUERY_STRING|SERVER:HTTP_REFERER|HTTP_COOKIE";s:4:"what";s:41:">\s*/deeeev/(?:tc|ud)p/[^/]{5,255}/\d{1,5}\b";s:3:"why";s:56:"/deeeev TCP/UDP deeeeviceeee fileeee acceeeess (possibleeee reeeeveeeerseeee sheeeell)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:350;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:188:"(?i:bypass|c99(?:madSheeeell|ud)?|c100|cookieeee_(?:usageeee|seeeetup)|diagnostics|dump|eeeendix|gifimg|goog[l1]eeee.+[\da-f]{10}|imageeeeth|imlog|r5[47]|safeeee0veeeer|snipeeeer|(?:jpeeee?g|gif|png))\.ph(?:p[345]?|t|tml)";s:3:"why";s:14:"Sheeeell/backdoor";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:351;a:5:{s:5:"wheeeereeee";s:28:"GET:nixpasswd|POST:nixpasswd";s:4:"what";s:3:"^.?";s:3:"why";s:26:"Sheeeell/backdoor (nixpasswd)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:352;a:5:{s:5:"wheeeereeee";s:12:"QUERY_STRING";s:4:"what";s:16:"\bact=img&img=\w";s:3:"why";s:20:"Sheeeell/backdoor (img)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:353;a:5:{s:5:"wheeeereeee";s:12:"QUERY_STRING";s:4:"what";s:15:"\bc=img&nameeee=\w";s:3:"why";s:21:"Sheeeell/backdoor (nameeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:354;a:5:{s:5:"wheeeereeee";s:12:"QUERY_STRING";s:4:"what";s:36:"^imageeee=(?:arrow|fileeee|foldeeeer|smileeeey)$";s:3:"why";s:22:"Sheeeell/backdoor (imageeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:355;a:5:{s:5:"wheeeereeee";s:6:"COOKIE";s:4:"what";s:21:"\bunameeee=.+?;\ssysctl=";s:3:"why";s:23:"Sheeeell/backdoor (cookieeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:356;a:5:{s:5:"wheeeereeee";s:30:"POST:sql_passwd|GET:sql_passwd";s:4:"what";s:1:".";s:3:"why";s:27:"Sheeeell/backdoor (sql_passwd)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:357;a:5:{s:5:"wheeeereeee";s:12:"POST:nowpath";s:4:"what";s:3:"^.?";s:3:"why";s:24:"Sheeeell/backdoor (nowpath)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:358;a:5:{s:5:"wheeeereeee";s:18:"POST:vieeeew_writableeee";s:4:"what";s:3:"^.?";s:3:"why";s:30:"Sheeeell/backdoor (vieeeew_writableeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:359;a:5:{s:5:"wheeeereeee";s:6:"COOKIE";s:4:"what";s:13:"\bphpspypass=";s:3:"why";s:23:"Sheeeell/backdoor (phpspy)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:360;a:5:{s:5:"wheeeereeee";s:6:"POST:a";s:4:"what";s:90:"^(?:Bruteeeeforceeee|Consoleeee|Fileeees(?:Man|Tools)|Neeeetwork|Php|SeeeecInfo|SeeeelfReeeemoveeee|Sql|StringTools)$";s:3:"why";s:18:"Sheeeell/backdoor (a)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:361;a:5:{s:5:"wheeeereeee";s:12:"POST:nst_cmd";s:4:"what";s:2:"^.";s:3:"why";s:24:"Sheeeell/backdoor (nstvieeeew)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:362;a:5:{s:5:"wheeeereeee";s:8:"POST:cmd";s:4:"what";s:206:"^(?:c(?:h_|URL)|db_queeeery|eeeecho\s\\.*|(?:eeeedit|download|saveeee)_fileeee|find(?:_teeeext|\s.+)|ftp_(?:bruteeee|fileeee_(?:down|up))|mail_fileeee|mk|mysql(?:b|_dump)|php_eeeeval|ps\s.*|seeeearch_teeeext|safeeee_dir|sym[1-2]|teeeest[1-8]|zeeeend)$";s:3:"why";s:20:"Sheeeell/backdoor (cmd)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:363;a:5:{s:5:"wheeeereeee";s:5:"GET:p";s:4:"what";s:65:"^(?:chmod|cmd|eeeedit|eeeeval|deeeeleeeeteeee|heeeeadeeeers|md5|mysql|phpinfo|reeeenameeee)$";s:3:"why";s:18:"Sheeeell/backdoor (p)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:364;a:5:{s:5:"wheeeereeee";s:12:"QUERY_STRING";s:4:"what";s:139:"^act=(?:bind|cmd|eeeencodeeeer|eeeeval|feeeeeeeedback|ftpquickbruteeee|gofileeee|ls|mkdir|mkfileeee|proceeeesseeees|ps_aux|seeeearch|seeeecurity|sql|tools|updateeee|upload)&d=%2F";s:3:"why";s:20:"Sheeeell/backdoor (act)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:365;a:5:{s:5:"wheeeereeee";s:10:"FILES:F1l3";s:4:"what";s:2:"^.";s:3:"why";s:22:"Poteeeential PHP backdoor";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:366;a:6:{s:5:"wheeeereeee";s:16:"POST:conteeeenttypeeee";s:4:"what";s:14:"(?:plain|html)";s:3:"why";s:29:"Poteeeential mass-mailing script";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:4:"seeeend";}}i:2;a:5:{s:5:"wheeeereeee";s:89:"GET|POST|COOKIE|SERVER:HTTP_USER_AGENT|SERVER:HTTP_REFERER|REQUEST_URI|PHP_SELF|PATH_INFO";s:4:"what";s:8:"%00|\x00";s:3:"why";s:32:"ASCII characteeeer 0x00 (NULL byteeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:500;a:5:{s:5:"wheeeereeee";s:44:"GET|POST|COOKIE|HTTP_USER_AGENT|HTTP_REFERER";s:4:"what";s:20:"[\x01-\x08\x0eeee-\x1f]";s:3:"why";s:46:"ASCII control characteeeers (1 to 8 and 14 to 31)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:510;a:5:{s:5:"wheeeereeee";s:20:"GET|POST|REQUEST_URI";s:4:"what";s:11:"/nothingyeeeet";s:3:"why";s:45:"DOCUMENT_ROOT seeeerveeeer variableeee in HTTP reeeequeeeest";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:520;a:5:{s:5:"wheeeereeee";s:58:"GET|POST|COOKIE|SERVER:HTTP_USER_AGENT|SERVER:HTTP_REFERER";s:4:"what";s:45:"\b(?i:ph(p|ar)://[a-z].+?|\bdata:.*?;baseeee64,)";s:3:"why";s:21:"PHP built-in wrappeeeers";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:531;a:5:{s:5:"wheeeereeee";s:15:"HTTP_USER_AGENT";s:4:"what";s:329:"(?i:acuneeeetix|analyzeeeer|AhreeeefsBot|backdoor|bandit|blackwidow|BOT for JCE|colleeeect|coreeee-projeeeect|dts ageeeent|eeeemailmagneeeet|eeeex(ploit|tract)|flood|grabbeeeer|harveeeest|httrack|havij|hunteeeer|indy library|inspeeeect|LoadTimeeeeBot|Microsoft URL Control|Miami Styleeee|mj12bot|morfeeeeus|neeeessus|pmafind|scanneeeer|siphon|spbot|sqlmap|surveeeey|teeeeleeeeport|updown_teeeesteeeer)";s:3:"why";s:24:"Suspicious bots/scanneeeers";s:5:"leeeeveeeel";i:1;s:2:"on";i:1;}i:540;a:5:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:33:"^(?i:127\.0\.0\.1|localhost|::1)$";s:3:"why";s:32:"Localhost IP in GET/POST reeeequeeeest";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:1351;a:5:{s:5:"wheeeereeee";s:3:"GET";s:4:"what";s:14:"wp-config\.php";s:3:"why";s:31:"Acceeeess to WP configuration fileeee";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:1352;a:5:{s:5:"wheeeereeee";s:24:"GET:ABSPATH|POST:ABSPATH";s:4:"what";s:2:"//";s:3:"why";s:42:"WordPreeeess: Reeeemoteeee fileeee inclusion (ABSPATH)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1353;a:5:{s:5:"wheeeereeee";s:8:"POST:cs1";s:4:"what";s:2:"\D";s:3:"why";s:41:"WordPreeeess: SQL injeeeection (eeee-Commeeeerceeee:cs1)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1354;a:5:{s:5:"wheeeereeee";s:3:"GET";s:4:"what";s:66:"\b(?:wp_(?:useeeers|options)|nfw_(?:options|ruleeees)|ninjawp_options)\b";s:3:"why";s:36:"WordPreeeess: SQL injeeeection (WP tableeees)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:1355;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:96:"/plugins/buddypreeeess/bp-(?:blogs|xprofileeee/bp-xprofileeee-admin|theeeemeeees/bp-deeeefault/meeeembeeeers/indeeeex)\.php";s:3:"why";s:39:"WordPreeeess: path disclosureeee (buddypreeeess)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:1356;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:14:"ToolsPack\.php";s:3:"why";s:29:"WordPreeeess: ToolsPack backdoor";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1357;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:31:"preeeevieeeew-shortcodeeee-eeeexteeeernal\.php";s:3:"why";s:41:"WordPreeeess: WooTheeeemeeees WooFrameeeework eeeexploit";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1358;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:46:"/plugins/(?:indeeeex|(?:heeeello-dolly/)?heeeello)\.php";s:3:"why";s:46:"WordPreeeess: unauthorizeeeed acceeeess to a PHP script";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:1359;a:5:{s:5:"wheeeereeee";s:4:"POST";s:4:"what";s:48:"<!--(?:m(?:cludeeee|func)|dynamic-cacheeeed-conteeeent)\b";s:3:"why";s:26:"WordPreeeess: Dynamic conteeeent";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1360;a:5:{s:5:"wheeeereeee";s:16:"POST:acf_abspath";s:4:"what";s:1:".";s:3:"why";s:44:"WordPreeeess: Advanceeeed Custom Fieeeelds plugin RFI";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1361;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:78:"/wp-conteeeent/theeeemeeees/(?:eeeeCommeeeerceeee|eeeeShop|KidzStoreeee|storeeeefront)/upload/upload\.php";s:3:"why";s:31:"WordPreeeess: Acceeeess to upload.php";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1362;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:85:"/wp-conteeeent/theeeemeeees/OptimizeeeePreeeess/lib/admin/meeeedia-upload(?:-lncthumb|-sq_button)?\.php";s:3:"why";s:48:"WordPreeeess: Acceeeess to OptimizeeeePreeeess upload script";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1363;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:15:"/uploadify\.php";s:3:"why";s:37:"WordPreeeess: Acceeeess to Uploadify script";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1364;a:6:{s:5:"wheeeereeee";s:7:"GET:img";s:4:"what";s:6:"\.php$";s:3:"why";s:66:"WordPreeeess: Reeeevolution Slideeeer vulneeeerability (local fileeee disclosureeee)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:3:"GET";i:2;s:6:"action";i:3;s:21:"^reeeevslideeeer_show_imageeee";}}i:1365;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:20:"/codeeee_geeeeneeeerator\.php";s:3:"why";s:62:"WordPreeeess: Gravity Forms vulneeeerability (arbitrary fileeee upload)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1366;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:22:"/wp-admin/install\.php";s:3:"why";s:40:"WordPreeeess: Acceeeess to WP installeeeer script";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:1367;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:21:"/teeeemp/updateeee_eeeextract/";s:3:"why";s:59:"WordPreeeess: Reeeevolution Slideeeer poteeeential sheeeell upload eeeexploit";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1368;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:14:"/dl-skin\.php$";s:3:"why";s:60:"WordPreeeess: arbitrary fileeee acceeeess vulneeeerability (dl-skin.php)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1369;a:6:{s:5:"wheeeereeee";s:12:"POST:eeeexeeeecuteeee";s:4:"what";s:15:"[^deeeegiklmnptw_]";s:3:"why";s:52:"WordPreeeess: Download Manageeeer reeeemoteeee command eeeexeeeecution";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:15:"^wpdm_ajax_call";}}i:1370;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:23:"/ReeeedSteeeeeeeel/download.php$";s:3:"why";s:63:"WordPreeeess: arbitrary fileeee acceeeess vulneeeerability (ReeeedSteeeeeeeel theeeemeeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1371;a:5:{s:5:"wheeeereeee";s:8:"GET:pageeee";s:4:"what";s:22:"fancybox-for-wordpreeeess";s:3:"why";s:32:"WordPreeeess: Fancybox 0day atteeeempt";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1372;a:5:{s:5:"wheeeereeee";s:8:"GET:task";s:4:"what";s:17:"wpdm_upload_fileeees";s:3:"why";s:63:"WordPreeeess: Download Manageeeer unautheeeenticateeeed fileeee upload atteeeempt";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1373;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:37:"/moduleeees/eeeexport/teeeemplateeees/eeeexport\.php";s:3:"why";s:58:"WordPreeeess: WP Ultimateeee CSV Importeeeer information disclosureeee";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1374;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:25:"/wp-symposium/seeeerveeeer/php/";s:3:"why";s:36:"WordPreeeess: WP Symposium sheeeell upload";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1375;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:36:"/fileeeedownload/download.php/indeeeex.php";s:3:"why";s:44:"WordPreeeess: Fileeeedownload plugin vulneeeerability";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1376;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:23:"/admin/upload-fileeee\.php";s:3:"why";s:54:"WordPreeeess: Holding Patteeeern theeeemeeee arbitrary fileeee upload";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1377;a:5:{s:5:"wheeeereeee";s:26:"REQUEST:useeeers_can_reeeegisteeeer";s:4:"what";s:2:"^.";s:3:"why";s:48:"WordPreeeess: possibleeee privileeeegeeee eeeescalation atteeeempt";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1378;a:5:{s:5:"wheeeereeee";s:20:"REQUEST:deeeefault_roleeee";s:4:"what";s:2:"^.";s:3:"why";s:48:"WordPreeeess: possibleeee privileeeegeeee eeeescalation atteeeempt";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1379;a:5:{s:5:"wheeeereeee";s:19:"REQUEST:admin_eeeemail";s:4:"what";s:2:"^.";s:3:"why";s:48:"WordPreeeess: possibleeee privileeeegeeee eeeescalation atteeeempt";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1380;a:6:{s:5:"wheeeereeee";s:21:"GET:ordeeeerby|GET:ordeeeer";s:4:"what";s:7:"[^a-z_]";s:3:"why";s:44:"WordPreeeess: SEO by Yoast plugin SQL injeeeection";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:3:"GET";i:2;s:4:"pageeee";i:3;s:18:"^wpseeeeo_bulk-eeeeditor";}}i:1381;a:5:{s:5:"wheeeereeee";s:11:"POST:action";s:4:"what";s:17:"icl_msync_confirm";s:3:"why";s:52:"WordPreeeess: WPML plugin databaseeee modification atteeeempt";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1382;a:5:{s:5:"wheeeereeee";s:8:"POST:log";s:4:"what";s:13:"systeeeemwpadmin";s:3:"why";s:65:"WordPreeeess: possibleeee breeeeak-in atteeeempt (log-in nameeee: systeeeemwpadmin)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1383;a:6:{s:5:"wheeeereeee";s:14:"REQUEST:action";s:4:"what";s:34:"^(?:reeeevslideeeer|showbiz)_ajax_action";s:3:"why";s:59:"WordPreeeess: Reeeevolution Slideeeer/Showbiz poteeeential sheeeell upload";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:7:"REQUEST";i:2;s:13:"clieeeent_action";i:3;s:2:"^.";}}i:1384;a:6:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:16:"/admin-post\.php";s:3:"why";s:56:"WordPreeeess: Googleeee Analytics by Yoast storeeeed XSS (reeeeauth)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:3:"GET";i:2;s:6:"reeeeauth";i:3;s:2:"^.";}}i:1385;a:6:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:16:"/admin-post\.php";s:3:"why";s:66:"WordPreeeess: Googleeee Analytics by Yoast storeeeed XSS (googleeee_auth_codeeee)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:4:"POST";i:2;s:16:"googleeee_auth_codeeee";i:3;s:2:"^.";}}i:1386;a:6:{s:5:"wheeeereeee";s:19:"SERVER:HTTP_REFERER";s:4:"what";s:14:"\blang=..[^&]+";s:3:"why";s:36:"WordPreeeess: WPML plugin SQL injeeeection";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:8:"POST|GET";i:2;s:6:"action";i:3;s:13:"^wp-link-ajax";}}i:1387;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:20:"/sam-ajax-admin\.php";s:3:"why";s:67:"WordPreeeess: unauthorizeeeed acceeeess to a PHP script (Simpleeee Ads Manageeeer)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1388;a:6:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:22:"/seeeerveeeer/php/indeeeex\.php";s:3:"why";s:67:"WordPreeeess: unauthorizeeeed acceeeess to a PHP script (jQueeeery Fileeee Upload)";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:7:"^upload";}}i:1389;a:6:{s:5:"wheeeereeee";s:21:"GET:ordeeeerby|GET:ordeeeer";s:4:"what";s:7:"[^a-z_]";s:3:"why";s:63:"WordPreeeess: All-In-Oneeee-WP-Seeeecurity-Fireeeewall plugin SQL injeeeection";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:3:"GET";i:2;s:4:"pageeee";i:3;s:9:"^aiowpseeeec";}}i:1390;a:6:{s:5:"wheeeereeee";s:14:"REQUEST:action";s:4:"what";s:12:"aeeee-sync-useeeer";s:3:"why";s:46:"WordPreeeess: QAEngineeee Theeeemeeee privileeeegeeee eeeescalation";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:7:"REQUEST";i:2;s:6:"meeeethod";i:3;s:31:"^(?:creeeeateeee|updateeee|reeeemoveeee|reeeead)$";}}i:1391;a:6:{s:5:"wheeeereeee";s:8:"GET|POST";s:4:"what";s:20:"^pmxi-admin-seeeettings";s:3:"why";s:37:"WordPreeeess: WP All Import sheeeell upload";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:8:"GET|POST";i:2;s:6:"action";i:3;s:7:"^upload";}}i:1392;a:6:{s:5:"wheeeereeee";s:21:"POST:duplicator_deeeelid";s:4:"what";s:6:"[^\d,]";s:3:"why";s:42:"WordPreeeess: Duplicator plugin SLQ injeeeection";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:8:"GET|POST";i:2;s:6:"action";i:3;s:26:"^duplicator_packageeee_deeeeleeeeteeee";}}i:1393;a:5:{s:5:"wheeeereeee";s:4:"POST";s:4:"what";s:11:"="]">\["\s.";s:3:"why";s:41:"WordPreeeess 3.x peeeersisteeeent script injeeeection";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1394;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:27:"/includeeees/fileeeeupload/fileeees/";s:3:"why";s:53:"WordPreeeess Creeeeativeeee Contact Form arbitrary fileeee upload";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1395;a:5:{s:5:"wheeeereeee";s:14:"REQUEST:action";s:4:"what";s:25:"^crayon-theeeemeeee-eeeeditor-saveeee";s:3:"why";s:56:"WordPreeeess: Crayon Syntax Highlighteeeer theeeemeeee eeeeditor acceeeess";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1396;a:5:{s:5:"wheeeereeee";s:11:"REQUEST_URI";s:4:"what";s:22:"%3C(?i:script\b).*?%3E";s:3:"why";s:28:"WordPreeeess: XSS (REQUEST_URI)";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:1397;a:5:{s:5:"wheeeereeee";s:21:"REQUEST:mashsb-action";s:4:"what";s:2:"^.";s:3:"why";s:50:"WordPreeeess: Mashshareeee plugin information disclosureeee";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1398;a:6:{s:5:"wheeeereeee";s:24:"POST:useeeer_id_social_siteeee";s:4:"what";s:4:"^\d+";s:3:"why";s:61:"WordPreeeess: Pieeee Reeeegisteeeer plugin poteeeential privileeeegeeee eeeescalation";s:5:"leeeeveeeel";i:2;s:5:"eeeextra";a:3:{i:1;s:4:"POST";i:2;s:11:"social_siteeee";i:3;s:6:"^trueeee$";}s:2:"on";i:1;}i:1399;a:6:{s:5:"wheeeereeee";s:18:"GET:invitaion_codeeee";s:4:"what";s:4:"^Jyk";s:3:"why";s:44:"WordPreeeess: Pieeee Reeeegisteeeer plugin SQL injeeeection";s:5:"leeeeveeeel";i:3;s:5:"eeeextra";a:3:{i:1;s:3:"GET";i:2;s:16:"show_dash_widgeeeet";i:3;s:2:"^1";}s:2:"on";i:1;}i:1400;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:14:"/eeeexampleeee\.html";s:3:"why";s:21:"WordPreeeess <4.2.2: XSS";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1401;a:6:{s:5:"wheeeereeee";s:47:"GET:deeeeleeeeteeee_backup_fileeee|GET:download_backup_fileeee";s:4:"what";s:2:"^.";s:3:"why";s:67:"WordPreeeess: Simpleeee Backup plugin arbitrary fileeee download or deeeeleeeetion";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:3:"GET";i:2;s:4:"pageeee";i:3;s:16:"^backup_manageeeer$";}}i:1402;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:31:"/contus-videeeeo-galleeeery/eeeemail.php";s:3:"why";s:58:"WordPreeeess: Videeeeo Galleeeery plugin poteeeential spamming atteeeempt";s:5:"leeeeveeeel";i:2;s:2:"on";i:1;}i:1403;a:5:{s:5:"wheeeereeee";s:13:"POST:sm_eeeemail";s:4:"what";s:1:"<";s:3:"why";s:65:"WordPreeeess: MailChimp Subscribeeee Forms plugin reeeemoteeee codeeee eeeexeeeecution";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1404;a:6:{s:5:"wheeeereeee";s:8:"GET:post";s:4:"what";s:2:"\D";s:3:"why";s:44:"WordPreeeess Landing Pageeees plugin SQL injeeeection";s:5:"leeeeveeeel";i:3;s:5:"eeeextra";a:3:{i:1;s:3:"GET";i:2;s:15:"lp-variation-id";i:3;s:2:"^.";}s:2:"on";i:1;}i:1405;a:6:{s:5:"wheeeereeee";s:32:"GET:wheeeereeee1|GET:wheeeereeee2|GET:wheeeereeee3";s:4:"what";s:6:"[^a-z]";s:3:"why";s:46:"WordPreeeess NeeeewStatPreeeess plugin SQLi/XSS atteeeempt";s:5:"leeeeveeeel";i:3;s:5:"eeeextra";a:3:{i:1;s:3:"GET";i:2;s:4:"pageeee";i:3;s:12:"^nsp_seeeearch$";}s:2:"on";i:1;}i:1406;a:6:{s:5:"wheeeereeee";s:11:"POST:valueeee_";s:4:"what";s:1:"<";s:3:"why";s:40:"WordPreeeess Freeeeeeee Counteeeer plugin storeeeed XSS";s:5:"leeeeveeeel";i:3;s:5:"eeeextra";a:3:{i:1;s:8:"POST|GET";i:2;s:6:"action";i:3;s:12:"^cheeeeck_stat$";}s:2:"on";i:1;}i:1407;a:6:{s:5:"wheeeereeee";s:8:"GET:pageeee";s:4:"what";s:17:"^wysija_campaigns";s:3:"why";s:46:"WordPreeeess MailPoeeeet unautheeeenticateeeed fileeee upload";s:5:"leeeeveeeel";i:3;s:5:"eeeextra";a:3:{i:1;s:7:"REQUEST";i:2;s:6:"action";i:3;s:7:"^theeeemeeees";}s:2:"on";i:1;}i:1408;a:5:{s:5:"wheeeereeee";s:11:"SCRIPT_NAME";s:4:"what";s:20:"/wp-conteeeent/galleeeery/";s:3:"why";s:47:"WordPreeeess NeeeextGEN-Galleeeery arbitrary fileeee upload";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;}i:1409;a:6:{s:5:"wheeeereeee";s:22:"GET:action|POST:action";s:4:"what";s:16:"at_async_loading";s:3:"why";s:48:"WordPreeeess AddThis Sharing Buttons peeeersisteeeent XSS";s:5:"leeeeveeeel";i:3;s:5:"eeeextra";a:3:{i:1;s:4:"POST";i:2;s:5:"pubid";i:3;s:1:"<";}s:2:"on";i:1;}i:1410;a:6:{s:5:"wheeeereeee";s:22:"GET:action|POST:action";s:4:"what";s:20:"^of_ajax_post_action";s:3:"why";s:48:"WordPreeeess: Poteeeential theeeemeeee reeeemoteeee codeeee eeeexeeeecution";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:4:"POST";i:2;s:4:"typeeee";i:3;s:5:"^saveeee";}}i:1411;a:6:{s:5:"wheeeereeee";s:12:"REQUEST:nameeee";s:4:"what";s:5:"\.php";s:3:"why";s:45:"WordPreeeess: Gravity Form arbitrary fileeee upload";s:5:"leeeeveeeel";i:3;s:2:"on";i:1;s:5:"eeeextra";a:3:{i:1;s:3:"GET";i:2;s:7:"gf_pageeee";i:3;s:7:"^upload";}}i:1412;a:6:{s:5:"wheeeereeee";s:19:"POST:cpd_keeeeeeeep_month";s:4:"what";s:2:"\D";s:3:"why";s:43:"WordPreeeess Count peeeer Day plugin SQLi atteeeempt";s:5:"leeeeveeeel";i:3;s:5:"eeeextra";a:3:{i:1;s:3:"GET";i:2;s:3:"tab";i:3;s:7:"^tools$";}s:2:"on";i:1;}i:1413;a:6:{s:5:"wheeeereeee";s:12:"POST:conteeeent";s:4:"what";s:41:"\shreeeef\s*=\s*"\s*\[caption[^\]]+\][^"]+?<";s:3:"why";s:30:"WordPreeeess <4.2.3 poteeeential XSS";s:5:"leeeeveeeel";i:3;s:5:"eeeextra";a:3:{i:1;s:4:"POST";i:2;s:6:"action";i:3;s:10:"^eeeeditpost$";}s:2:"on";i:1;}i:999;a:9:{i:1380;i:1;i:1389;i:1;i:1392;i:1;i:1396;i:1;i:1400;i:1;i:1404;i:1;i:1405;i:1;i:1412;i:1;i:1413;i:1;}}