<?php

   /*
   * File: user.php
   * 
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Link: http://www.lysafe365.com/
   */
   
   require_once('_inc.php');
   
   $userId = !empty($_GET['uId']) ? intval($_GET['uId']) : '';
   $page = !empty($_GET['p']) ? intval($_GET['p']) : 1;     
   
   if (empty($userId))
   {
       header("location: list.php");
       exit();
   }
   
   $tpl->assign('userId', $userId);
   $tpl->assign('page', $page);
   
   $tpl->display();
   
?>