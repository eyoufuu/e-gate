<?php
    require_once('_inc.php');
    $sql ="call log_oper($dates,$datee)";
	$result= $db->fetchRows($sql);
	//echo $result;
	$i=0;
	 foreach($result as $row ) {
			echo $row[bindip]."<br>";
			$i++;
          }
   
   
   
 ?>