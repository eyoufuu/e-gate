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
   
   $module = !empty($_GET['m']) ? $_GET['m'] : 'news';
   
   $tpl = new Template($gTemplate);  
   
   $tpl->assign('module', $module); 
   
   $tpl->display();
   
?>