<?php
   require_once('_inc.php');
?>
<?php 
	$pid = $_GET['pid'];
	$SQL = "select proctl,webfilter,webinfo,filetypefilter,fileinfo,keywordfilter,keywordutf,smtpaudit,pop3audit,postaudit,time,week,times1,timee1,times2,timee2 from policy where policyid=$pid";
	$result = $db->fetchRow($SQL);
	
	$result['keywordutf'] = urldecode($result['keywordutf']);
	
	$return_array = array("policy"=>$result);
	
	echo json_encode($return_array);
?>