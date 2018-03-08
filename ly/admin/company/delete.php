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
   
   $infoId= !empty($_GET['iId']) ? trim($_GET['iId']) : '';
   $page = !empty($_GET['p']) ? intval($_GET['p']) : 1;
   
   if (empty($infoId))
   {
       $tpl->assign('msg', '缺少参数');
       $tpl->assign('backUrl', "list.php?p=$page");
       $tpl->display('_msg.tpl');
       exit();
   }
   
   $sql = "DELETE FROM " . $gDb['prefix'] . "contents WHERE (f_type='3' AND f_id='$infoId')";
   $db->query($sql);
      
   header("location: list.php?p=$page");
   
?>