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
   $SQL = "";
   $permit = 0;
   if(is_array($_GET)&&count($_GET)>0)//���ж��Ƿ�ͨ��get��ֵ��
    {
        if(isset($_GET["permit"]))//�Ƿ����"id"�Ĳ���
        {
            $permit=$_GET["permit"];//����
			$SQL = "update globalpara set isipmacbind = $permit";
			mysql_query( $SQL ) or die("���ݿ��ѯ����.".mysql_error());
			mysql_close($db_tc);
			return;
        }
    }
    else
	{
	  $SQL = "select isipmacbind from globalpara";
	  $result = mysql_query( $SQL ) or die("���ݿ��ѯ����.".mysql_error());
	  $row = mysql_fetch_array($result,MYSQL_ASSOC);
      $bind = $row['isipmacbind'];
	  mysql_close($db_tc);
      echo json_encode($bind);
	
	}
   
 ?>
