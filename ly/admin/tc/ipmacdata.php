 <?php  
  	require_once('_inc.php');
	$result = $db->query2("SELECT ip,mac from ipmac","M",false);
	$return_array =array();
	
	foreach($result as $row){		
		$return_array[long2ip($row['ip']] = $row['mac']
		
	}			
	echo json_encode($return_array);			
?>