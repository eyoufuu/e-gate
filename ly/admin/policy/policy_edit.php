<?php
    require_once('_inc.php');
	//$db_1 = mysql_connect($dbhost, $dbuser, $dbpassword)
	//or die("连接错误: " . mysql_error());
	//mysql_select_db($database) or die("Error conecting to db.");
    $rev_oper = $_POST['oper'];
    switch($rev_oper)
    {
    	case 'del':
    		$id = $_POST['id'];
    		$SQL = "update policy set stat=0,create_sort=0 where policyid=$id";
			$result = $db->exec($SQL);			
    		break;
    	case 'edit':
    		$name = $_POST['policy_name'];
    		$des = $_POST['policy_description'];
    		$id = $_POST['id'];
    		$SQL = "update policy set name='$name',description='$des' where policyid=$id;";
			$db->exec($SQL);
    		break;
    	case 'add':
    		$name = $_POST['policy_name'];
    		$des = $_POST['policy_description'];
    		$SQL = "select policyid from policy where stat=0 and policyid>0 limit 1";
    		$result = $db->fetchOne($SQL);
    		$id = $result['policyid'];
    		if($id =="")
    			break;
    		$SQL = "select max(create_sort) as max from policy;";
    		$result = $db->fetchOne($SQL);
    		$max = $result['max']+1; 
    		$SQL = "update policy set name='$name',proctl=0,webfilter=0,filetypefilter=0,keywordfilter=0,smtpaudit=0,pop3audit=0,postaudit=0,time=0,
    		times1=0,timee1=2400,times2=0,timee2=2400,description='$des',stat=1,create_sort=$max where policyid=$id;";
    		$db->exec($SQL);
    		break;
    }
    mysql_close($db);
	echo json_encode($response);
?>