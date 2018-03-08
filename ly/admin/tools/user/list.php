<?php

  /*
   * File: list.php
   * 
   * Modified: 2008-7-25
   * By: red (9409249@gmail.com)
   *
   * Created: 2008-7-25
   * By: red (9409249@gmail.com)
   *
   * Link: http://mycom.yuanmas.com/
   */
   
   require_once('_inc.php');
   
   $page = !empty($_GET['p']) ? intval($_GET['p']) : 1;
   $tpl->assign('page', $page);
   
   $tpl->display();
   
?>