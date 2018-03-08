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
   
   if (!empty($_POST['btnSubmit']))
   {
       $arrVars = array('infoId', 'lang', 'subject','content', 'order', 'keywords', 'description', 'page', 'list', 'lock');
       foreach ($arrVars AS $key)
       {
           $$key = !empty($_POST[$key]) ? trim($_POST[$key]) : '';
       }
       
       if (empty($infoId) || empty($subject) || empty($content))
       {
           $tpl->assign("msg", '缺少参数。请完整输入。');
           $tpl->assign('backUrl', "list.php?&p=$page");
           $tpl->display("_msg.tpl");
           exit();
       }
       
       $keywords = str_replace("，", ',', $keywords);
       $keywords = str_replace("　", ',', $keywords);
       $keywords = str_replace(" ", ',', $keywords);
       
       $list = empty($list) ? '0' : $list;
       $lock = empty($lock) ? '0' : $lock;
       
       $sql = "UPDATE " . $gDb['prefix'] . "contents SET f_type='3', f_lang='$lang', f_list='$list', f_lock='$lock', f_subject='$subject', f_content='$content', f_order='$order', f_keywords='$keywords', f_description='$description' WHERE (f_id='$infoId')";
       $db->query($sql);

       $tpl->assign("msg", '保存成功。');
       $tpl->assign('backUrl', "list.php?p=$page");
       $tpl->display("_msg.tpl");
       exit();
   }  
   
   $infoId = !empty($_GET['iId']) ? intval($_GET['iId']) : '';
   
   if (empty($infoId))
   {
       header("location: list.php");
       exit();
   }
   
   $tpl->assign('infoId', $infoId);
   $tpl->assign('page', $page);
   
   $tpl->display();
   
?>