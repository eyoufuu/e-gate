 <?php  
  	require_once('_inc.php');
	$result = $db->query2("SELECT ip,mac from ipmac","M",false);
	$return_array =array();
	$ip = array();
	$mac =array();
	$i=0;
	$tmp;
	foreach($result as $row){		
		$ip[$i]= long2ip($row['ip']);
		$mac[$i] = $row['mac'];
		$i++;

	}		
	$return_array = array("ip"=>$ip,"mac"=>$mac);
	echo json_encode($return_array);			
?>