<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 +---------------------------------------------------------------------+
 | REVISION: 2015-10-07 23:43:21                                       |
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

// Block immediately if user is not allowed :
nf_not_allowed( 'block', __LINE__ );

// Fetch readme.txt :
if ( $data = @file_get_contents( dirname( plugin_dir_path(__FILE__) ) . '/readme.txt' ) ) {
	$what = '== Changelog ==';
	$pos_start = strpos( $data, $what );
	$changelog = substr( $data, $pos_start + strlen( $what ) + 1 );
} else {
	$changelog = __('Error : cannot find changelog :(', 'ninjafirewall');
}

// Hide/show the corresponding table when the user clicks a button
// (e.g., changelog, privacy policy etc) :
echo '<script>
function show_table(table_id) {
	var av_table = [11, 12, 13, 14];
	for (var i = 0; i < av_table.length; i++) {
		if ( table_id == av_table[i] ) {
			document.getElementById(table_id).style.display = "";
		} else {
			document.getElementById(av_table[i]).style.display = "none";
		}
	};
}
var dgs=0;
function nfw_eg() {
	setTimeout("nfw_eg()",5);if(dgs<180){dgs++;document.body.style.webkitTransform = "rotate("+dgs+"deg)";document.body.style.msTransform = "rotate("+dgs+"deg)";document.body.style.transform = "rotate("+dgs+"deg)";}document.body.style.overflow="hidden";
}
</script>
<div class="wrap">
	<div style="width:54px;height:52px;background-image:url( ' . plugins_url() . '/ninjafirewall/images/ninjafirewall_50.png);background-repeat:no-repeat;background-position:0 0;margin:7px 5px 0 0;float:left;" title="NinTechNet"></div>
	<h2>' . __('About', 'ninjafirewall') .'</h2>
	<br />
	<br />
	<center>';
?>
	<table border="0" width="80%" style="padding:10px;-moz-box-shadow:-3px 5px 5px #999;-webkit-box-shadow:-3px 5px 5px #999;box-shadow:-3px 5px 5px #999;background-color:#749BBB;border:1px solid #638DB0;color:#fff;border-radius:6px">
		<tr>
			<td style="text-align:center">
				<font style="font-size: 2em; font-weight: bold;">NinjaFirewall (WP Edition) v<?php echo NFW_ENGINE_VERSION ?></font>
				<br />
				<font onContextMenu="nfw_eg();return false;">&copy;</font> 2012-<?php echo date( 'Y' ) ?> <a href="http://nintechnet.com/" target="_blank" title="The Ninja Technologies Network" style="color:#fcdc25"><strong>NinTechNet</strong></a>
				<br />
				The Ninja Technologies Network
				<p><a href="https://twitter.com/nintechnet"><img border="1" src="<?php echo plugins_url() ?>/ninjafirewall/images/twitter_ntn.png" width="116" height="28" target="_blank"></a></p>
			</td>
		</tr>
		<tr style="text-align:center">
			<td width="100%">
				<table width="100%" border="0">
					<tr>
						<td style="width:33.3333%">
							<font style="font-size: 1.5em; font-weight: bold;">NinjaFirewall</font>
							<p>Web Application Firewall<br />for PHP and WordPress.</p>
							<i style="border-radius:20%;display:inline-block;height:150px;vertical-align:middle;width:150px;border:5px solid #FFF;box-shadow: -2px 3px 3px #999 inset;background:transparent url('<?php echo plugins_url() ?>/ninjafirewall/images/logo_pro_80.png') no-repeat scroll center center;background-color:#F8F8F8;"></i>
							<p><a href="http://nintechnet.com/ninjafirewall/" class="button-primary" style="color:#FFF;background-color:#449D44;border-color:#398439;">Free Download</a></p>
						</td>
						<td style="width:33.3333%">
							<font style="font-size: 1.5em; font-weight: bold;">NinjaMonitoring</font>
							<p>Website Monitoring<br />for just $4.99/month.</p>
							<i style="border-radius:20%;display:inline-block;height:150px;vertical-align:middle;width:150px;border:5px solid #FFF;box-shadow: -2px 3px 3px #999 inset;background:transparent url('<?php echo plugins_url() ?>/ninjafirewall/images/logo_nm_80.png') no-repeat scroll center center;background-color:#F8F8F8;"></i>
							<p><a href="http://nintechnet.com/ninjamonitoring/" class="button-primary" style="color:#FFF;background-color:#EC971F;border-color:#D58512;">7-Day Free Trial</a></p>
						</td>
						<td style="width:33.3333%">
							<font style="font-size: 1.5em; font-weight: bold;">NinjaRecovery</font>
							<p>Malware removal<br />and hack recovery.</p>
							<i style="border-radius:20%;display:inline-block;height:150px;vertical-align:middle;width:150px;border:5px solid #FFF;box-shadow: -2px 3px 3px #999 inset;background:transparent url('<?php echo plugins_url() ?>/ninjafirewall/images/logo_nr_80.png') no-repeat scroll center center;background-color:#F8F8F8;"></i>
							<p><a href="http://nintechnet.com/ninjarecovery/" class="button-primary" style="color:#FFF;background-color:#C9302C;border-color:#AC2925;">Clean Your Site!</a></p>
						</td>
					</tr>
				</table>
			</td>
		</tr>

	</table>
<?php
	echo '
		<br />
		<br />
		<input class="button-secondary" type="button" value="' . __('Changelog', 'ninjafirewall') . '" onclick="show_table(12);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="button-primary" type="button" value="' . __('Spread the word about the Ninja !', 'ninjafirewall') . '" onclick="show_table(11);" active>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="button-secondary" type="button" value="' . __('System Info', 'ninjafirewall') . '" onclick="show_table(13);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="button-secondary" type="button" value="' . __('Privacy Policy', 'ninjafirewall') . '" onclick="show_table(14);">
		<br />
		<br />

		<table id="11" style="display:none;" border="0" width="500">
			<tr style="text-align:center;">
				<td><a href="http://www.facebook.com/sharer.php?u=http://ninjafirewall.com/" target="_blank"><img title="Share it" src="' . plugins_url() . '/ninjafirewall/images/facebook.png" width="90" height="90" style="border: 0px solid #DFDFDF;padding:0px;-moz-box-shadow:-3px 5px 5px #999;-webkit-box-shadow:-3px 5px 5px #999;box-shadow:-3px 5px 5px #999;background-color:#FCFCFC;"></a></td>
				<td><a href="https://plus.google.com/share?url=http://ninjafirewall.com/" target="_blank"><img title="Share it" src="' . plugins_url() . '/ninjafirewall/images/google.png" width="90" height="90" style="border: 0px solid #DFDFDF;padding:0px;-moz-box-shadow:-3px 5px 5px #999;-webkit-box-shadow:-3px 5px 5px #999;box-shadow:-3px 5px 5px #999;background-color:#FCFCFC;"></a></td>
				<td><a href="http://twitter.com/share?text=NinjaFirewall&url=http://ninjafirewall.com/" target="_blank"><img title="Share it" src="' . plugins_url() .  '/ninjafirewall/images/twitter.png" width="90" height="90" style="border: 0px solid #DFDFDF;padding:0px;-moz-box-shadow:-3px 5px 5px #999;-webkit-box-shadow:-3px 5px 5px #999;box-shadow:-3px 5px 5px #999;background-color:#FCFCFC;"></a></td>
				<td><a href="https://wordpress.org/support/view/plugin-reviews/ninjafirewall?rate=5#postform"><img title="Rate it" border="0" src="'. plugins_url() .'/ninjafirewall/images/rate.png" width="116" height="28" style="border: 0px solid #DFDFDF;padding:0px;-moz-box-shadow:-3px 5px 5px #999;-webkit-box-shadow:-3px 5px 5px #999;box-shadow:-3px 5px 5px #999;background-color:#FCFCFC;"><br />Rate it on WordPress.org!</a>
				</td>
			</tr>
		</table>

		<table id="12" style="display:none;" width="500">
			<tr>
				<td>
					<textarea class="small-text code" cols="60" rows="8">' . htmlspecialchars($changelog) . '</textarea>
				</td>
			</tr>
		</table>

		<table id="13" border="0" style="display:none;" width="500">
			<tr valign="top"><td width="47%;" align="right">REMOTE_ADDR</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . htmlspecialchars($_SERVER['REMOTE_ADDR']) . '</td></tr>
			<tr valign="top"><td width="47%;" align="right">SERVER_ADDR</td><td width="3%">&nbsp;</td><td width="50%" align="left">' .htmlspecialchars($_SERVER['SERVER_ADDR']) . '</td></tr>
			<tr valign="top"><td width="47%;" align="right">SERVER_NAME</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . htmlspecialchars($_SERVER['SERVER_NAME']) . '</td></tr>
			<tr valign="top"><td width="47%;" align="right">HTTP_HOST</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . htmlspecialchars($_SERVER['HTTP_HOST']) . '</td></tr>';

if ( PHP_VERSION ) {
	echo '<tr valign="top"><td width="47%;" align="right">' . __('PHP version', 'ninjafirewall') . '</td><td width="3%">&nbsp;</td><td width="50%" align="left">'. PHP_VERSION . ' (';
	if ( defined('HHVM_VERSION') ) {
		echo 'HHVM';
	} else {
		echo strtoupper(PHP_SAPI);
	}
	echo ')</td></tr>';
}
if ( $_SERVER['SERVER_SOFTWARE'] ) {
	echo '<tr valign="top"><td width="47%;" align="right">' . __('HTTP server', 'ninjafirewall') . '</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . htmlspecialchars($_SERVER['SERVER_SOFTWARE']) . '</td></tr>';
}
if ( PHP_OS ) {
	echo '<tr valign="top"><td width="47%;" align="right">' . __('Operating System', 'ninjafirewall') . '</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . PHP_OS . '</td></tr>';
}
if ( $load = sys_getloadavg() ) {
	echo '<tr valign="top"><td width="47%;" align="right">' . __('Load Average', 'ninjafirewall') . '</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . $load[0] . ', '. $load[1] . ', '. $load[2] . '</td></tr>';
}
if (! preg_match( '/^win/i', PHP_OS ) ) {
	$MemTotal = $MemFree = $Buffers = $Cached = 0;
	$data = @explode( "\n", `cat /proc/meminfo` );
	foreach ( $data as $line ) {
		if ( preg_match( '/^MemTotal:\s+?(\d+)\s/', $line, $match ) ) {
			$MemTotal = $match[1] / 1024;
		} elseif ( preg_match( '/^MemFree:\s+?(\d+)\s/', $line, $match ) ) {
			$MemFree = $match[1];
		} elseif ( preg_match( '/^Buffers:\s+?(\d+)\s/', $line, $match ) ) {
			$Buffers = $match[1];
		} elseif ( preg_match( '/^Cached:\s+?(\d+)\s/', $line, $match ) ) {
			$Cached = $match[1];
		}
	}
	$free = ( $MemFree + $Buffers + $Cached ) / 1024;
	if ( $free ) {
		echo '<tr valign="top"><td width="47%;" align="right">' . __('RAM', 'ninjafirewall') . '</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . number_format( $free ) . ' ' . __('MB free', 'ninjafirewall') . ' / '. number_format( $MemTotal ) . ' ' . __('MB total', 'ninjafirewall') . '</td></tr>';
	}

	$cpu = @explode( "\n", `grep 'model name' /proc/cpuinfo` );
	if (! empty( $cpu[0] ) ) {
		array_pop( $cpu );
		echo '<tr valign="top"><td width="47%;" align="right">' . __('Processor(s)', 'ninjafirewall') . '</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . count( $cpu ) . '</td></tr>';
		echo '<tr valign="top"><td width="47%;" align="right">' . __('CPU model', 'ninjafirewall') . '</td><td width="3%">&nbsp;</td><td width="50%" align="left">' . str_replace ("model name\t:", '', htmlspecialchars($cpu[0])) . '</td></tr>';
	}
}

echo '
		</table>
		<table id="14" style="display:none;" width="500">
			<tr>
				<td>
					<textarea class="small-text code" cols="60" rows="8">' . __('NinTechNet strictly follows the WordPress Plugin Developer guidelines', 'ninjafirewall') . ' &lt;http://wordpress.org/plugins/about/guidelines/&gt;: ' . __('NinjaFirewall (WP Edition) is free, open source and fully functional, no "trialware", no "obfuscated code", no "crippleware", no "phoning home". It does not require a registration process or an activation key to be installed or used.', 'ninjafirewall') . "\n" .  __('Because we do not collect any user data, we do not even know that you are using (and hopefully enjoying !) our product.', 'ninjafirewall') . '</textarea>
				</td>
			</tr>
		</table>
	</center>
</div>';

/* ------------------------------------------------------------------ */
// EOF
