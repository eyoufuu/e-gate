<?php
   require_once('_inc.php');
   
   		$return_array_cpu = array();
		@exec("sed -n '1,5p' /proc/stat |grep '^cpu'", $return_array_cpu);
		
		$return_array_mem = array();
		@exec("sed -n '1,2p' /proc/meminfo", $return_array_mem);
		
		$return_array_disk = array();
		@exec("df -hl |grep '/$'", $return_array_disk);
		
		$return_array =array();
		
		$return_array = array("cpu"=>$return_array_cpu,"mem"=>$return_array_mem,"disk"=>$return_array_disk);
		//echo $str;
		echo json_encode($return_array);
		//echo $str;
   
   
?>