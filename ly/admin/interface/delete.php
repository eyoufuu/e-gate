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
   
   $ip = !empty($_GET['ip']) ? trim($_GET['ip']) : '';
   if (empty($ip))
   {
       //$tpl->assign('msg', '缺少参数');
       //$tpl->assign('backUrl', "list.php?p=$page");
       //$tpl->display('_msg.tpl');
       exit();
   }
   
   $sql = "DELETE FROM ipmac WHERE ip='$ip'"; 
   $db->query($sql);
      
   header("location: ip_mac.php");
   
?>