<?php

  /*
   * File: login.php
   * 
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Link: http://
   */
   
   session_start();
   
   $dRootDir = '../';
   
   require_once($dRootDir . '_config.php');
   require_once($dRootDir . 'inc/params.php');
   require_once($dRootDir . 'inc/classes/template.php');
      
   $tpl = new Template($gTemplate);
   $tpl->assign('systemVersion', $gVersion);
   
   $userName = !empty($_POST['userName']) ? trim($_POST['userName']) : '';
   $userPwd = !empty($_POST['userPwd']) ? trim($_POST['userPwd']) : '';
   if (!empty($userName))
   {    
       require_once($dRootDir . 'inc/classes/db.php');
       $db = new Db($userName,"0");
	   $SQL = "SELECT f_id, f_userPwd, f_groupId FROM ly_admins WHERE (f_userName='".$userName."')";
       $arr = $db->fetchRow($SQL);

       if (!empty($arr['f_id']) && ($arr['f_userPwd'] == md5($userPwd)))
       {
	       $SQL = "SELECT f_purviews FROM ly_admingroups WHERE (f_id= '". $arr['f_groupId'] . "')";
           $purviews = $db->fetchOne($SQL);
           $_SESSION['ses_adminId'] = $arr['f_id'];
           $_SESSION['ses_adminName'] = $userName;
           $_SESSION['ses_adminPurviews'] = $purviews;
           $db->log("log","登录成功",$userName);
           header("location: ./");
           exit();
       }
       else
       {
	       $SQL = $userName. ":" .$userPwd;
           $db->log("log","登录失败",$SQL);
           $tpl->assign("msg", "用户不存在，或是密码错误。");
       }
   }
   
   $tpl->display();
   
?>