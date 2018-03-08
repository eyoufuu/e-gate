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
   
   $groupId = !empty($_GET['gId']) ? trim($_GET['gId']) : '';
   
   if (empty($groupId))
   {
       $tpl->assign('msg', '缺少参数');
       $tpl->assign('backUrl', "admin_groups.php");
       $tpl->display('_msg.tpl');
       exit();
   }
   
   //是否还有属于该组的管理员
   $count = $db->fetchOne("SELECT COUNT(f_id) AS _count FROM " . $gDb['prefix'] . "admins WHERE (f_groupId='$groupId')");
   if ($count)
   {
       $tpl->assign('msg', '有管理员属于该组。请先删除或转移属于该组的管理员，然后再删除该组。');
       $tpl->assign('backUrl', "admin_groups.php");
       $tpl->display('_msg.tpl');
       exit();
   }
   
   $sql = "DELETE FROM " . $gDb['prefix'] . "admingroups WHERE (f_id='$groupId')";
   $db->exec($sql,"删除管理员组");
      
   header("location: admin_groups.php");
   
?>