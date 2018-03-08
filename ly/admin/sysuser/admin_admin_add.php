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
   
   if (!empty($_POST['btnSubmit']))
   {
       $arrVars = array('userName', 'groupId', 'status', 'userPwd');
       foreach ($arrVars AS $key)
       {
           $$key = !empty($_POST[$key]) ? trim($_POST[$key]) : '';
       }
       
       if (empty($userName) || empty($groupId))
       {
           $tpl->assign("msg", '缺少参数。请完整输入。');
           $tpl->assign('backUrl', "admin_admin_add.php?gId=$groupId");
           $tpl->display("_msg.tpl");
           exit();
       }
              
       $exist = $db->fetchOne("SELECT f_id FROM " . $gDb['prefix'] . "admins WHERE (f_userName='$userName')");
       if ($exist)
       {
           $tpl->assign("msg", '该管理员已存在。');
           $tpl->assign('backUrl', "admin_admin_add.php?gId=$groupId");
           $tpl->display("_msg.tpl");
           exit();
       }
       
       $status = empty($status) ? 0 : $status;
       $userPwd = MD5($userPwd);
       
       $sql = "INSERT INTO " . $gDb['prefix'] . "admins SET f_groupId='$groupId', f_status='$status', f_userName='$userName', f_userPwd='$userPwd'";
       $db->exec($sql,"添加管理员");

       $tpl->assign("msg", '添加成功。');
       $tpl->assign('backUrl', "admin_admins.php?gId=$groupId");
       $tpl->display("_msg.tpl");
       exit();
   }
   
   $groupId = !empty($_GET['gId']) ? intval($_GET['gId']) : '';
   
   $tpl->assign('groupId', $groupId);
   
   $tpl->display();
   
?>