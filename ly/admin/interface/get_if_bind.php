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
   or die("��������: " . mysql_error());
   mysql_select_db($database) or die("ѡ�����ݿ����.");

   $SQL = "select isipmacbind from globalpara";
//   echo $SQL;
   $result = mysql_query( $SQL ) or die("���ݿ��ѯ����.".mysql_error());
   $row = mysql_fetch_array($result,MYSQL_ASSOC);
   $bind = $row['isipmacbind'];
   echo json_encode($bind);
   mysql_close($db_tc);
 ?>
