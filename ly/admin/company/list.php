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
   
   $page = !empty($_GET['p']) ? intval($_GET['p']) : 1;
   $tpl->assign('page', $page);
   
   $categoryId = !empty($_GET['cId']) ? intval($_GET['cId']) : '';
   $tpl->assign('categoryId', $categoryId);
   
   $tpl->display();
   
?>