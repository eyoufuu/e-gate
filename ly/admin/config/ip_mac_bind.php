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
   include("../dbconfig.php");
?>    



<?php
  $db_tc = mysql_connect($dbhost, $dbuser, $dbpassword)
   or die("不能连接: " . mysql_error());
   mysql_select_db($database) or die("选择数据库错误.");
   $SQL = "";
   $permit = 0;
   if(is_array($_GET)&&count($_GET)>0)//先判断是否通过get传值了
    {
        if(isset($_GET["permit"]))//是否存在"id"的参数
        {
            $permit=$_GET["permit"];//存在
			$SQL = "update globalpara set isipmacbind = $permit";
			mysql_query( $SQL ) or die("数据库查询错误.".mysql_error());
			mysql_close($db_tc);
			return;
        }
    }
    else
	{
	  $SQL = "select isipmacbind from globalpara";
	  $result = mysql_query( $SQL ) or die("数据库查询错误.".mysql_error());
	  $row = mysql_fetch_array($result,MYSQL_ASSOC);
      $bind = $row['isipmacbind'];
	  mysql_close($db_tc);
      echo json_encode($bind);
	
	}
   
 ?>
