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
       $arrVars = array('adminId', 'userName', 'groupId', 'status', 'userPwd');
       foreach ($arrVars AS $key)
       {
           $$key = !empty($_POST[$key]) ? trim($_POST[$key]) : '';
       }
       
       if (empty($adminId) || empty($userName) || empty($groupId))
       {
           $tpl->assign("msg", '缺少参数。请完整输入。');
           $tpl->assign('backUrl', "admin_admin_modify.php?aId=$adminId");
           $tpl->display("_msg.tpl");
           exit();
       }
              
       $exist = $db->fetchOne("SELECT f_id FROM " . $gDb['prefix'] . "admins WHERE (f_userName='$userName' AND f_id<>'$adminId')");
       if ($exist)
       {
           $tpl->assign("msg", '该管理员已存在。');
           $tpl->assign('backUrl', "admin_admin_modify.php?aId=$adminId");
           $tpl->display("_msg.tpl");
           exit();
       }
       
       $status = empty($status) ? 0 : $status;
       
       //修改密码
       $sqlPwd = '';
       if (!empty($userPwd))
       {
           $userPwd = MD5($userPwd);
           $sqlPwd = ", f_userPwd='$userPwd'";
       }
       
       $sql = "UPDATE " . $gDb['prefix'] . "admins SET f_groupId='$groupId', f_status='$status', f_userName='$userName' $sqlPwd WHERE (f_id='$adminId')";
       $db->query2($sql,"编辑管理员",true);

       $tpl->assign("msg", '保存成功。');
       $tpl->assign('backUrl', "admin_admins.php");
       $tpl->display("_msg.tpl");
       exit();
   }  
   
   $adminId = !empty($_GET['aId']) ? intval($_GET['aId']) : '';
   
   if (empty($adminId))
   {
       header("location: admin_admins.php?gId=$groupId");
       exit();
   }
   
   $tpl->assign('adminId', $adminId);
   
   $tpl->display();
   
?>