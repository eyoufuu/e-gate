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
       $groupId = !empty($_POST['groupId']) ? trim($_POST['groupId']) : '';
       $name = !empty($_POST['name']) ? trim($_POST['name']) : '';
       $arrPurviews = $_POST['arrPurviews'];
       
       if (empty($name) || empty($groupId))
       {
           $tpl->assign("msg", '缺少参数。请完整输入。');
           $tpl->assign('backUrl', "admin_group_modify.php?gId=$groupId");
           $tpl->display("_msg.tpl");
           exit();
       }
              
       $exist = $db->fetchOne("SELECT f_id FROM " . $gDb['prefix'] . "admingroups WHERE (f_name='$name' AND f_id<>'$groupId')");
       if ($exist)
       {
           $tpl->assign("msg", '该管理员组已存在。');
           $tpl->assign('backUrl', "admin_group_modify.php?gId=$groupId");
           $tpl->display("_msg.tpl");
           exit();
       }
       
       $purviews = '|' . implode('|', $arrPurviews) . '|';
       
       $sql = "UPDATE " . $gDb['prefix'] . "admingroups SET f_name='$name', f_purviews='$purviews' WHERE (f_id='$groupId')";
       $db->exec($sql,"编辑管理员组");

       $tpl->assign("msg", '保存成功。');
       $tpl->assign('backUrl', "admin_groups.php");
       $tpl->display("_msg.tpl");
       exit();
   }  
   
   $groupId = !empty($_GET['gId']) ? intval($_GET['gId']) : '';
   
   if (empty($groupId))
   {
       header("location: admin_groups.php");
       exit();
   }
   
   $tpl->assign('groupId', $groupId);
   
   require_once("../_params/_purviews.php");
   $tpl->assign('arrPurviews', $gPurviews);
   
   $tpl->display();
   
?>