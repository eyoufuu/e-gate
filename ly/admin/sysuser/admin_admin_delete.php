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
   
   $adminId = !empty($_GET['aId']) ? trim($_GET['aId']) : '';
   $groupId = !empty($_GET['aId']) ? trim($_GET['gId']) : '';
   
   if (empty($adminId))
   {
       $tpl->assign('msg', '缺少参数');
       $tpl->assign('backUrl', "admin_admins.php");
       $tpl->display('_msg.tpl');
       exit();
   }
      
   $sql = "DELETE FROM " . $gDb['prefix'] . "admins WHERE (f_id='$adminId')";
   $db->exec($sql,"删除管理员");
      
   header("location: admin_admins.php?gId=$groupId");
   
?>