<?php

/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   
   require_once("_inc.php");
      
   if (!empty($_POST['btnSubmit']))
   {
       $oldPwd = !empty($_POST['oldPwd']) ? trim($_POST['oldPwd']) : '';
       $pwd1 = !empty($_POST['pwd1']) ? trim($_POST['pwd1']) : '';
       $pwd2 = !empty($_POST['pwd2']) ? trim($_POST['pwd2']) : '';
       
       if (empty($oldPwd) || empty($pwd1))
       {
           $tpl->assign("msg", '缺少参数。');
           $tpl->assign("backUrl", 'pwd.php');
           $tpl->display("_msg.tpl");
           exit();
       }
       
       if ($pwd1 != $pwd2)
       {
           $tpl->assign("msg", '新密码不相同。');
           $tpl->assign("backUrl", 'pwd.php');
           $tpl->display("_msg.tpl");
           exit();
       }       
       
       $pwd = $db->fetchOne("SELECT f_userPwd FROM " . $gDb['prefix'] . "admins WHERE (f_id='$dAdminId')");
       
       if ($pwd != MD5($oldPwd))
       {
           $tpl->assign("msg", '旧密码不正确。');
           $tpl->assign("backUrl", 'pwd.php');
           $tpl->display("_msg.tpl");
           exit();
       }
       
       $newPwd = MD5($pwd1);
       $sql = "UPDATE " . $gDb['prefix'] . "admins SET f_userPwd='$newPwd' WHERE (f_id='$dAdminId')";
       $db->query($sql);
       
       $tpl->assign("msg", '修改成功！请退出系统，然后使用新用户密码登录。');
       $tpl->display("_msg.tpl");
       exit();
   }
   
   $tpl->assign('adminName', $dAdminName);
   
   $tpl->display();
   
?>