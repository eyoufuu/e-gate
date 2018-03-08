#!/opt/lampp/bin/php
<?php
/*
function read_input()
{
	$fp = fopen("/dev/stdin", "r");
	$input = trim(fgets($fp, 255));
	fclose($fp);
	return $input;
}
*/
$dbhost     = "localhost";
$dbuser     = "root";
$dbpassword = "123456";
$database   = "baseconfig";
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error conecting to db.");
mysql_set_charset('utf8',$db);
$result = mysql_query("select isarp_ipmacbind from globalpara");
$row = mysql_fetch_row($result);
$comm =$row[0];
if($comm==0)
{
   printf("%d is not bind !\n",$row['isipmacbind']);
}
else
{
    //插入模块  
	$filename = '/ly/iarp.ko';
	if (file_exists($filename)) 
	{
		printf("The file $filename exists and the command is %d\n" ,$comm);
		@exec("/sbin/insmod $filename");
		$result = mysql_query("SELECT ip,mac from ipmac");
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
		{
			printf("[%u]:[%s]\n",$row['ip'] , $row['mac']);
			$return_array = array();
			//unset($return_array); 
			
			$command = "echo " . $row['ip'] . "," . $row['mac'] . " > /proc/ipmac_arp";
			printf("%s\n" , $command); 
			system($command);
			//@exec($command, $return_array);
		}
		//所有输入完毕后，才能执行下面的控制语句
		@exec("echo " . $comm ." > /proc/ipmac_arp_c");

	}
	else 
	{
		echo "The file $filename does not exist";
	}
}
?>