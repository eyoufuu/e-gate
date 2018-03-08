<?php
   require_once('_inc.php');
	//选取最大10ip流量
 
   $date_ym = date('Ym');
   $date_day = date('d');
   $tablename=$date_ym."flowdata";	

    $SQL = "select minute(FROM_UNIXTIME(logtime)) as hm,sum(upflow) as up,sum(downflow) as down from " . $tablename ." where hour(FROM_UNIXTIME(logtime))= hour(now()) and day(FROM_UNIXTIME(logtime))= day(now()) group by  hm";
	
   
    $result = $db->query2($SQL,"M",false ); 			
	$res_onehour = array(); 
	
	$res0_10_u = 0 ;$res0_10_d = 0;
	$res10_20_u = 0;$res10_20_d = 0 ;
	$res20_30_u = 0;$res20_30_d = 0 ;
	$res30_40_u = 0;$res30_40_d = 0;
	$res40_50_u = 0;$res40_50_d = 0;
	$res50_60_u = 0;$res50_60_d = 0;
	foreach($result as $row) {
	    $v = $row['hm'];
		if($v<10)
		{
		   $res0_10_u += $row['up'];
		   $res0_10_d += $row['down'];
		}
		else if($v<20 && $v>=10)
		{
			$res10_20_u += $row['up'];
			$res10_20_d += $row['down'];
		}
        else if($v<30 && $v>=20)
		{
			$res20_30_u +=$row['up'];
			$res20_30_d += $row['down'];
		}
        else if($v<40 && $v>=30)
		{
			$res30_40_u	+= $row['up'];
			$res30_40_d += $row['down'];	
		}
        else if($v<50 && $v>=40)
        {
		    $res40_50_u += $row['up'];
			$res40_50_d += $row['down'];
		}
        else if($v<60 && $v>=50)
        {
		   $res50_60_u += $row['up'];
		   $res50_60_d += $row['down'];
		}
	}
    $res_onehour[] = array(0,  $res0_10_u  	,$res0_10_d);
    $res_onehour[] = array(1,  $res10_20_u  ,$res10_20_d);
    $res_onehour[] = array(2,  $res20_30_u  ,$res20_30_d);
    $res_onehour[] = array(3,  $res30_40_u  ,$res30_40_d);
    $res_onehour[] = array(4,  $res40_50_u  ,$res40_50_d);
    $res_onehour[] = array(5,  $res50_60_u  ,$res50_60_d);
	
	echo json_encode($res_onehour);
?>