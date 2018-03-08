<?php
ob_start();
/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   require_once('_inc.php');
?>


<?php
$week_set=0;
$time_enable=($_POST['time_open']==1)?1:0;
if($time_enable==1)
{
	$time_start1= explode(":",$_POST['time_start1']);
	$time_start2= explode(":",$_POST['time_start2']);
	$time_end1= explode(":",$_POST['time_end1']);
	$time_end2= explode(":",$_POST['time_end2']);
	
//	echo $time_start1[0]."<br>";
//	$hour1=ltrim("0", $time_start1[0]);
//	$hour2=ltrim("0", $time_start2[0]);
//	echo $hour1."<br>";
//	echo $hour2."<br>";
	$time_start1_hour=(($hour1=ltrim($time_start1[0],"0"))=="")?"0":$hour1;
	$time_start1_second=sprintf("%02d",$time_start1[1]);
	$time_start2_hour=(($hour2=ltrim($time_start2[0],"0"))=="")?"0":$hour2;
	$time_start2_second=sprintf("%02d",$time_start2[1]);
	
	$time_end1_hour=(($hour3=ltrim($time_end1[0],"0"))=="")?"0":$hour3;
	$time_end1_second=sprintf("%02d",$time_end1[1]);
	$time_end2_hour=(($hour4=ltrim($time_end2[0],"0"))=="")?"0":$hour4;
	$time_end2_second=sprintf("%02d",$time_end2[1]);
	
	$time_s1=$time_start1_hour.$time_start1_second;
	$time_s2=$time_start2_hour.$time_start2_second;
	$time_e1=$time_end1_hour.$time_end1_second;
	$time_e2=$time_end2_hour.$time_end2_second;
	for ($i=0;$_POST['week_set'][$i]!="";$i++)
	{
		$week_set = intval($week_set)|intval($_POST['week_set'][$i]);
	}
}
else
{
	$time_s1=0;
	$time_s2=0;
	$time_e1="2400";
	$time_e2="2400";
}

/**
 * 这段用来修改策略的参数
 */
$pro_set="";
for($i=0;$i<255;$i++)
{
	$pro_set=$pro_set."1";
}
echo $pro_set."<br>";
if(!empty($_POST['pro_select']))
{
	$arr_proset = explode(",", $_POST['pro_select']);
	foreach($arr_proset as $value_proset)
	{
		$pro_set[$value_proset]="0";
	}
	$pro_set[0]="1";
}
echo $pro_set."<br>";
$audit_smtp = 0;
$audit_pop3 = 0;
$audit_post = 0;
$audit_webtype = 0;
$audit_filetype = 0;
$audit_keyword = 0;
for ($i=0;$_POST['audit'][$i]!="";$i++)//通过for循环取值
{
	switch($_POST['audit'][$i])
	{
		case 1:
			$audit_smtp = 1;
			break;
		case 2:
			$audit_pop3 = 1;
			break;
		case 3:
			$audit_post = 1;
			break;
		case 4:
			$audit_webtype = 1;
			break;
		case 5:
			$audit_filetype = 1;
			break;
		case 6:
			$audit_keyword = 1;
			break;
	}
}
if($_SESSION["create_policy"]==1)
{
	$sql_getmax_createsort = "select create_sort from policy order by create_sort desc";
	$arr = $db->fetchRows($sql_getmax_createsort);
	$max_sort = $arr[0]['create_sort'];
	$new_sort = (integer)$max_sort + 1;
	$new_policy = "create_sort=".$new_sort.",";
}
else
{
	$new_policy = "";
}
$sql_update = "update policy set name='".$_POST["policyname"]."', description='"
				.preg_replace('#\s+#', '', trim($_POST["description"]))."', proctl='".$pro_set."', smtpaudit=".$audit_smtp.", pop3audit="
				.$audit_pop3.", postaudit=".$audit_post.", webfilter=".$audit_webtype
				.", filetypefilter=".$audit_filetype.", keywordfilter=".$audit_keyword
				.", time=".$time_enable.", week=".$week_set.", times1=".$time_s1.", times2=".$time_s2.", timee1=".$time_e1.", timee2=".$time_e2.", ".$new_policy." stat=1 where policyid=".$_SESSION["policy_id"].";";
echo $sql_update;
$arr = $db->query($sql_update);
?>

<?php 
/**
 * 修改网页过滤项的相关参数
 */



//$mysql_query ("SELECT * FROM TABLE WHERE Field IN (".implode(",", $arr).")"); 
$sql_updateall = "update webinfo set pass = 1, log=0 where policyid=".$_SESSION["policy_id"];
$arr = $db->query($sql_updateall);
if(!empty($_POST['webpass_set']))
{
	$sql_update_webinfo = "update webinfo set pass=0 where policyid = ".$_SESSION["policy_id"]." and webid in(".implode(",", $_POST['webpass_set']).")";
	$arr = $db->query($sql_update_webinfo);
}
if(!empty($_POST['weblog_set']))
{
	$sql_update_webinfo = "update webinfo set log=1 where policyid = ".$_SESSION["policy_id"]." and webid in(".implode(",", $_POST['weblog_set']).")";
	$arr = $db->query($sql_update_webinfo);
}

$sql_updateall = "update fileinfo set pass = 1, log=0 where policyid=".$_SESSION["policy_id"];
$arr = $db->query($sql_updateall);
if(!empty($_POST['filepass_set']))
{
	$sql_update_fileinfo = "update fileinfo set pass=0 where policyid = ".$_SESSION["policy_id"]." and fileid in(".implode(",", $_POST['filepass_set']).")";
	$arr = $db->query($sql_update_fileinfo);
}
if(!empty($_POST['filelog_set']))
{
	$sql_update_fileinfo = "update fileinfo set log=1 where policyid = ".$_SESSION["policy_id"]." and fileid in(".implode(",", $_POST['filelog_set']).")";
	$arr = $db->query($sql_update_fileinfo);
}

$sql_updateall = "update proinfo set pass = 1 where policyid=".$_SESSION["policy_id"];
$arr = $db->query($sql_updateall);

//删除关键字
if(!empty($_POST['del_id']))
{
	$arr_del = explode("@", $_POST['del_id']);
	$sql_del_keyword = "delete from keywordinfo where keywordid in(".implode(",", $arr_del).")";
	echo $sql_del_keyword."<br>";
	$arr = $db->query($sql_del_keyword);
}

//增加或修改关键字
for ($j=0;$_POST['hidden_data'][$j]!="";$j++)//通过for循环取值
{
	if($_POST['hidden_data'][$j]=="0")
		continue;
	$arr_keyword = explode("#", $_POST['hidden_data'][$j]);
	$name = $arr_keyword[1];
	$gb_name = iconv("UTF-8","GB2312",$name);
	$pass = $arr_keyword[2];
	$log = $arr_keyword[3];
	$description = $arr_keyword[4];
	if(substr($_POST['hidden_data'][$j], 0,1)=="0")
	{
		$sql_insert_keyword = "insert into keywordinfo(`policyid`,`utf`,`gb`,`pass`,`log`,`description`) values('".$_SESSION["policy_id"]
							."', '".$name."', '".$gb_name."', '".$pass."', '".$log."', '".$description."')";
		echo $sql_insert_keyword."<br>";
		$arr = $db->query($sql_insert_keyword);
	}
	else
	{
		$keywordid = ltrim($arr_keyword[0], "0");
		$sql_update_keyword = "update keywordinfo set utf='".$name."', gb='".$gb_name."', pass=".$pass.", log=".$log.", description='".$description."' where keywordid=".$keywordid;
		echo $sql_update_keyword."<br>";
		$arr = $db->query($sql_update_keyword);
	}
}
//for ($i=0;$_POST['webpass_set'][$i]!="";$i++)//通过for循环取值
//{
//	$webid = $_POST['webpass_set'][$i];
//	$sql_update_webinfo = "update webinfo set pass=1 where policyid = ".$_SESSION["policy_id"]." and webid in(".implode(",", $_POST['webpass_set']).")";
//	echo $sql_update_webinfo;
//	$arr = $db->query($sql_update_webinfo);
//}
/*for ($i=0;$_POST['weblog_set'][$i]!="";$i++)//通过for循环取值
{
	$webid = $_POST['weblog_set'][$i];
	$sql_update_webinfo = "update webinfo set log=1 where policyid = ".$_SESSION["policy_id"]." and webid=".$webid;
	$arr = $db->query($sql_update_webinfo);
}*/
?>

<?php 
header("Location: policy_name.php");
?>