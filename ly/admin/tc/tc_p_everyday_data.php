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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../js/jquery.js" type="text/javascript"></script>
<title>IP观察</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>

</head>
<body>

<h1>即时信息总量 </h1>
  
<div id ="month_day"> 
<?php
     date_default_timezone_set('Asia/Shanghai');
     $date_ym = date('Ym');
     $date_day = date('d');
     $IPs = $_REQUEST['IP'];
	 $IP = ip2long($IPs);
	 $tablename_pro = $dateym . "flowdata";
	 $tablename_web = $dateym . "web";
	 echo "IP地址:" . $IPs ."<br>"; 
	 echo "今日日期:". $date_ym . $date_day . "日"
?>   
</div>
<a href ="#today_web" onclick="onShowHide('today_web')"><h1>当日查看的网站</h1></a>
<div id = "today_web" >
		
</div>  
<div id = "today_search">
    
</div>  
<a href = "#today_pro" onclick="onShowHide('today_pro')"><h1>当日使用的网络软件</h1></a>
<div id = "today_pro">

</div>  
<script type="text/javascript" src="../common/common.js"></script>
<script type="text/javascript">
function onShowHide(s)
{
	$("#"+s).toggle();
}
jQuery(document).ready(function(){
    function onDataReceived(json)
	{
	    var pro = json.pro;
		var web = json.web;
	    for(var i = 0;i<pro.length;i++)
			$("#today_pro").append(pro[0]+pro[1]+pro[2]);
		for(var j = 0;j<web.length;j++)
		{
			$("#today_web").append("网站:"+web[j][0]+":"+"<br>");
			$("#today_web").append("url:"+/*decodeURI*/web[j][1]+"<br>");
		}
	}
    $.ajax({
            url: "tc_p_everyday_data_data.php?IP=<?php echo $IP?>",
            method: 'GET',
            dataType: 'json',
			cache:false,
            success: onDataReceived
			});

});
</script>

</body>
</html>