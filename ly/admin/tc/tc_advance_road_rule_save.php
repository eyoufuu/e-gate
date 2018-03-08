<?php
/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   require_once('_inc.php');
   $arr = $_POST;
    
	$SQL = "update procat set channelname= '%s' where type = %d" ; 
	try
	{
		foreach ($arr as $key => $val)
		{
			$execstr = sprintf($SQL, $val,$key);
			$result  = $db->query2($SQL,"协议流控");
		}
		$ret = array("msg"=>"成功执行","result"=>"success");
		echo json_encode($ret);
	}
	catch(Exception $e)
	{
		$ret = array("msg"=>"失败","result"=>"success");
		echo json_encode($ret);
	}
	
?>
