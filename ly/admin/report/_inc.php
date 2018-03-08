<?php
/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   session_start();
   if (empty($_SESSION['ses_adminId']))
   {
       print('<script type="text/javascript">top.location="../logout.php";</script>');
       exit();
   }
   $dRootDir = '../../';
   require_once($dRootDir . '_config.php');
   require_once($dRootDir . 'inc/params.php'); 
   require_once($dRootDir . 'inc/classes/db.php');
   require_once($dRootDir . 'inc/classes/template.php');
   require_once($dRootDir . 'inc/classes/util.php');
      
   $dAdminId = $_SESSION['ses_adminId'];
   $dAdminName = $_SESSION['ses_adminName'];
   
   $tpl = new Template($gTemplate);
   
   if (FALSE === strpos($_SESSION['ses_adminPurviews'], '|SUPER|'))
   {
   		if (FALSE === strpos($_SESSION['ses_adminPurviews'], '|OPERATOR|'))
   		{
	       $tpl->assign('title', '操作失败');
	       $tpl->assign('msg', '您没有权限进行此操作。');
	       $tpl->display('_msg.tpl');
	       exit();
   		}
   }
   
 //  $db = new Db($gDb);
   $dbaudit = new Db($dAdminName,$dAdminId);
   Util::gpc();
?>
