<?php
  require_once('_inc.php');
//proc/bridge_pass should be chmod to 777	
    switch($_REQUEST['radio']) 
    {
	   	case "0":
	    	$SQL="update globalpara set isipmacbind=0";
	    	$result = $db->exec($SQL,"BYPASS设置");
			system("/usr/bin/sudo echo 0 > /proc/bridge_bypass");
	   // 	system("/usr/bin/sudo /home/bypass_echo 0");	   
	    		break;
		case "1":
			$SQL="update globalpara set isipmacbind=1";
			$result = $db->exec($SQL,"BYPASS设置");
			system("/usr/bin/sudo echo 1 > /proc/bridge_bypass");
			   break;
		case "2":
			$SQL="update globalpara set isipmacbind=2";
			$db->exec($SQL,"BYPASS设置");
			
			$result = $db->query2("SELECT ip,mac,bind from ipmac","M",false);
			foreach($result as $row)
			{
				if($row['bind']==1)
				$cmd = "/usr/bin/sudo echo ".$row['ip']." " .$row['mac']." > /proc/bridge_ipmac";
				else
				$cmd = "/usr/bin/sudo echo ".$row['ip']." " ."00:00:00:00:00:00"." > /proc/bridge_ipmac";
				system($cmd);
			}			
			system("/usr/bin/sudo echo 2 > /proc/bridge_bypass");
			
			break;
		case "3":
			$SQL="update globalpara set isipmacbind=3";
			$db->exec($SQL,"BYPASS设置");
			$result = $db->query2("SELECT ip,mac,bind from ipmac","M",false);
			foreach($result as $row)
			{
				if($row['bind']==1)
				$cmd = "/usr/bin/sudo echo ".$row['ip']." ". $row['mac']." > /proc/bridge_ipmac";
				else
				$cmd = "/usr/bin/sudo echo ".$row['ip']." ". "00:00:00:00:00:00"." > /proc/bridge_ipmac";
				system($cmd);
			}
			system("/usr/bin/sudo echo 3 > /proc/bridge_bypass");
			break;
		default:
			break;
    }
?>