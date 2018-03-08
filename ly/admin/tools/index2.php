<?php
//
// Copyright (c) 2008 Morgan Collins <morgan -at- morcant.com>
//
// PHP-NMAP v0.2 - A PHP web frontend to the command line utility NMAP
// http://www.morcant.net/projects/php-nmap
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
   require_once('_inc.php');

$cfg = new stdClass;

// Path to NMAP Executable
$cfg->nmapcmd = '/usr/local/bin/nmap';

// Default Scan Type
// To get SYN, you need to chmod +s nmap
$cfg->default_scan_option = 'connect';

// Enable verbose output
$cfg->default_verbose = true;

// Default Ping Type
$cfg->default_ping_type = 'tcp_icmp';

// Detect OS Type
$cfg->default_os_detect = true;

// Default host should be that of the client
$cfg->default_remote_addr = true;

// Host Flags
$cfg->host_flags = '';

// Table Background Color
$cfg->tablebgcolor = '#e1e1e1';

// Host Section Background Color
$cfg->hostsectioncolor = '#84C1FF';

// Scan Section Background Color
$cfg->scansectioncolor = '#e5f1f4';

// General Section Background Color
$cfg->generalsectioncolor = '#f8fbfc';

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link href="../common/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../common/common.js"></script>
</head>
<body>

<?php
	if ($_POST['submit'] && $_POST['host']) {
		$args = '';

		switch ($_POST['scan_type']) {
			case 'connect':
				$args .= '-sT ';
				break;
			case 'syn':
				$args .= '-sS ';
				break;
			case 'null':
				$args .= '-sN ';
				break;
			case 'fin';
				$args .= '-sF ';
				break;
			case 'xmas':
				$args .= '-sX ';
				break;
			case 'ack':
				$args .= '-sA ';
				break;
			case 'window':
				$args .= '-sW ';
				break;
			case 'ping';
				$args .= '-sP ';
				break;
			default:
				$args .= '-sT ';
				break;
		}
		
		switch ($_POST['ping_type']) {
			case 'tcp':
				$args .= '-PT ';
				break;
			case 'tcp_icmp':
				$args .= '-PB ';
				break;
			case 'icmp':
				$args .= '-PI ';
				break;
			case 'none':
				$args .= '-P0 ';
				break;
			default:
				$args .= '-PB ';
				break;
		}

		if ($_POST['os_detect'])
			$args .= '-O ';
			
		if ($_POST['ident_info'])
			$args .= '-I ';
			
		if ($_POST['fragmentation'])
			$args .= '-f ';
		
		if ($_POST['verbose'])
			$args .= '-v ';
		
		if ($_POST['use_port'])
			$args .= '-p ' .  escapeshellarg($_POST['port_range']);

		if ($_POST['fast_scan'])
			$args .= '-F ';

		if ($_POST['use_decoy'])
			$args .= '-D ' .  escapeshellarg($_POST['decoy_name']);

		if ($_POST['use_device'])
			$args .= '-e ' .  escapeshellarg($_POST['device_name']);

		if ($_POST['dont_resolve'])
			$args .= '-n ';

		if ($_POST['udp_scan'])
			$args .= '-sU ';

		if ($_POST['rpc_scan'])
			$args .= '-sR ';
		
		$args .= $cfg->host_flags . ' ' . escapeshellarg($_POST['host']);

		?>
		<pre>
		<?php
	/*	$sucommand = "su --login root --command '".$cfg->nmapcmd . ' ' . $args . ' 2>&1'. "'";
		echo $sucommand;
		$rootpasswd = "111111";
		
		if(($fp = popen($sucommand, "w"))==false)
		{
			die("Open failed: ${php_errormsg}\n");
		}
		fputs($fp, $rootpasswd);
		pclose($fp);*/
	
		system("/usr/bin/sudo ".$cfg->nmapcmd . ' ' . $args . ' 2>&1');

		?>
		</pre>
		<?php
	} else {
?>

<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">

<h1>PHP-NMAP</h1>
<table bgcolor="<?php echo $cfg->tablebgcolor; ?>" border="0" cols="4" width="95%" cellpadding="5" cellspacing="0" >
	<tr bgcolor="<?php echo $cfg->hostsectioncolor; ?>">
		<td width="120"><b>主机扫描</b>:</td>
		<td width="220" colspan="2"><input type="text" name="host" size="20" value="<?php if ($cfg->default_remote_addr) echo $_SERVER['REMOTE_ADDR']; ?>"></td>
		<td width="160" align="right"><input type="submit" name="submit" value="扫描">&nbsp;<input type="reset" value="清除"></td>
	</tr>
	
	<tr>
		<td bgcolor="<?php echo $cfg->scansectioncolor; ?>"><b>扫描选项</b>:</td>
		<td width="150" bgcolor="<?php echo $cfg->generalsectioncolor; ?>">&nbsp;</td>
		<td width="150" bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><b>一般选项</b>:</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>">&nbsp;</td>
	</tr>
	
	<tr>
		<td bgcolor="<?php echo $cfg->scansectioncolor; ?>"><input type="radio" name="scan_type" value="connect" <?php if ($cfg->default_scan_option == 'connect') echo 'CHECKED'; ?>>连接扫描</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="dont_resolve">不使用域名解析</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="radio" name="ping_type" value="tcp" <?php if ($cfg->default_ping_type == 'tcp') echo 'CHECKED'; ?>>TCP Ping</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="fragmentation">段</td>
	</tr>
	
	<tr>
		<td bgcolor="<?php echo $cfg->scansectioncolor; ?>"><input type="radio" name="scan_type" value="syn" <?php if ($cfg->default_scan_option == 'syn') echo 'CHECKED'; ?>>SYN半开扫描</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="fast_scan">快速扫描</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="radio" name="ping_type" value="tcp_icmp" <?php if ($cfg->default_ping_type == 'tcp_icmp') echo 'CHECKED'; ?>>TCP&ICMP Ping</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="ident_info">得到标识符信息</td>
	</tr>
	
	<tr>
		<td bgcolor="<?php echo $cfg->scansectioncolor; ?>"><input type="radio" name="scan_type" value="null" <?php if ($cfg->default_scan_option == 'null') echo 'CHECKED'; ?>>NULL扫描</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="verbose" <?php if ($cfg->default_verbose) echo 'CHECKED'; ?>>Verbose</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="radio" name="ping_type" value="icmp" <?php if ($cfg->default_ping_type == 'icmp') echo 'CHECKED'; ?>>ICMP Ping</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="resolve_all">解析域名</td>
	</tr>
	
	<tr>
		<td bgcolor="<?php echo $cfg->scansectioncolor; ?>"><input type="radio" name="scan_type" value="fin" <?php if ($cfg->default_scan_option == 'fin') echo 'CHECKED'; ?>>FIN扫描</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="udp_scan">UDP扫描</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="radio" name="ping_type" value="none" <?php if ($cfg->default_ping_type == 'none') echo 'CHECKED'; ?>>不使用Ping</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="os_detect" <?php if ($cfg->default_os_detect) echo 'CHECKED'; ?>>操作系统指纹扫描</td>
	</tr>
		
	<tr>
		<td bgcolor="<?php echo $cfg->scansectioncolor; ?>"><input type="radio" name="scan_type" value="xmas" <?php if ($cfg->default_scan_option == 'xmas') echo 'CHECKED'; ?>>XMAS扫描</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="rpc_scan">RPC扫描</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>">&nbsp;</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>">&nbsp;</td>
	</tr>
	
	<tr>
		<td bgcolor="<?php echo $cfg->scansectioncolor; ?>"><input type="radio" name="scan_type" value="ack" <?php if ($cfg->default_scan_option == 'ack') echo 'CHECKED'; ?>>ACK扫描</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="use_port">端口范围:</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="use_decoy">Use Decoy(s):</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="checkbox" name="use_device">Use Device:</td>
	</tr>

	<tr>
		<td bgcolor="<?php echo $cfg->scansectioncolor; ?>"><input type="radio" name="scan_type" value="window" <?php if ($cfg->default_scan_option == 'window') echo 'CHECKED'; ?>>Window扫描</td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="text" name="port_range" size="10"></td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="text" name="decoy_name" size="10"></td>
		<td bgcolor="<?php echo $cfg->generalsectioncolor; ?>"><input type="text" name="device_name" size="10"></td>
	</tr>
	
</table>
</form>

<?php
	} // if ($submit)
?>

</body>
</html>
