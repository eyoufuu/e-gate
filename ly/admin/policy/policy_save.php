<?php
   require_once('_inc.php');
function transform2utf($str)
   {
   //   $utf=iconv("EUC-CN","UTF-8",$str);
   //   echo $utf."<br>";
	   $url = urlencode($str);
	//   echo $url."<br>";
	   $tmpurl = str_replace("%7C","|",$url);
	   return str_replace("%2C",",",$tmpurl);	   
   }
function transform2gbk($str)
   {
      $gbk=iconv("UTF-8","GB2312",$str);
	//   echo $utf."<br>";
	   $url = urlencode($gbk);
	//   echo $url."<br>";
	   $tmpurl = str_replace("%7C","|",$url);
	   return str_replace("%2C",",",$tmpurl);	   
   }
?>
<?php
	$pid = $_POST['pid'];
	$tf = $_REQUEST['tf'];
	$week = $_REQUEST['week'];
	$ts1 = $_REQUEST['ts1'];
	$te1 = $_REQUEST['te1'];
	$ts2 = $_REQUEST['ts2'];
	$te2 = $_REQUEST['te2'];
	$pro = $_REQUEST['pi'];

	$wf = $_REQUEST['wf'];
	$wc = $_REQUEST['wi'];
	$ff = $_REQUEST['ff'];
	$fc = $_REQUEST['fi'];
	$smtp = $_REQUEST['smtp'];
	$pop3 = $_REQUEST['pop3'];
	$post = $_REQUEST['post'];
	
	$kf = $_REQUEST['kf'];
//	$kwutf = $_POST['kwutf'];
//	$kwgb = $_REQUEST['kwgb'];
	
	$kwutf = transform2utf($_POST['kwutf']);
	$kwgb = transform2gbk($_POST['kwutf']);

    $format = "update policy set proctl='%s',webfilter=%d,webinfo='%s',filetypefilter=%d,fileinfo='%s',keywordfilter=%d, keywordutf='%s',keywordgb='%s',smtpaudit=%d,pop3audit=%d,postaudit=%d,time=%d,week=%d,times1=%d ,timee1=%d,times2=%d,timee2=%d where policyid=%d";
	$SQL = sprintf($format,$pro,$wf,$wc,$ff,$fc,$kf,$kwutf,$kwgb,$smtp,$pop3,$post,$tf,$week,$ts1,$te1,$ts2,$te2,$pid);
	$db->exec($SQL);
?>

<?php 
//header("Location: policy_name2.php");
?>