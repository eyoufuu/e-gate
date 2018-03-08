<?php

  /*
   * File: delete.php
   * 
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Link: http://www.lysafe365.com/
   */
   
   require_once("_inc.php");
   
   $userId= !empty($_GET['uId']) ? trim($_GET['uId']) : '';
   $page = !empty($_GET['p']) ? intval($_GET['p']) : 1;
   
   if (empty($userId))
   {
       $tpl->assign('msg', '缺少参数');
       $tpl->assign('backUrl', "list.php?p=$page");
       $tpl->display('_msg.tpl');
       exit();
   }
   
   $sql = "DELETE FROM " . $gDb['prefix'] . "users WHERE (f_id='$userId')";
   $db->query($sql);
      
   header("location: list.php?p=$page");
   
?>