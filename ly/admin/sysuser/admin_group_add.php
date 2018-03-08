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
       $name = !empty($_POST['name']) ? trim($_POST['name']) : '';
       
       if (empty($name))
       {
           $tpl->assign("msg", '缺少参数。请完整输入。');
           $tpl->assign('backUrl', "admin_group_add.php");
           $tpl->display("_msg.tpl");
           exit();
       }
              
       $exist = $db->fetchOne("SELECT f_id FROM ".$gDb['prefix']."admingroups WHERE (f_name='$name')");
       if ($exist)
       {
           $tpl->assign("msg", '该管理员组已存在。');
           $tpl->assign('backUrl', "admin_group_add.php");
           $tpl->display("_msg.tpl");
           exit();
       }
       
       $purviews = '|NEWS|' . implode('|', $_POST['arrPurviews']) . '|';
       echo $purviews;
       $sql = "INSERT INTO ".$gDb['prefix']."admingroups SET f_name='$name', f_purviews='$purviews'";
       echo $sql;
       $db->exec($sql,"添加管理员组");

       $tpl->assign("msg", '添加成功。');
       $tpl->assign('backUrl', "admin_groups.php");
       $tpl->display("_msg.tpl");
       exit();
   }  
   
   require_once("../_params/_purviews.php");
   $tpl->assign('arrPurviews', $gPurviews);
   
   $tpl->display();
   
?>