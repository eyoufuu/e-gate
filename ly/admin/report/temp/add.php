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
       $arrVars = array('lang', 'type', 'name', 'content', 'keywords', 'description', 'list', 'lock');
       foreach ($arrVars AS $key)
       {
           $$key = !empty($_POST[$key]) ? trim($_POST[$key]) : '';
       }
       
       if (empty($name))
       {
           $tpl->assign("msg", '缺少参数。请完整输入。');
           $tpl->assign('backUrl', "add.php?p=$page");
           $tpl->display("_msg.tpl");
           exit();
       }
                     
       $exist = $db->fetchOne("SELECT f_id FROM " . $gDb['prefix'] . "channels WHERE (f_name='$name')");
       if ($exist)
       {
           $tpl->assign("msg", '该栏目已存在。');
           $tpl->assign('backUrl', "add.php?p=$page");
           $tpl->display("_msg.tpl");
           exit();
       }
       
       $now = time();
       $keywords = str_replace("，", ',', $keywords);
       $keywords = str_replace("　", ',', $keywords);
       $keywords = str_replace(" ", ',', $keywords);
       
       $list = empty($list) ? '0' : $list;
       $lock = empty($lock) ? '0' : $lock;
       
       $sql = "INSERT INTO " . $gDb['prefix'] . "channels SET f_lang='$lang', f_name='$name', f_list='$list', f_lock='$lock'";
       $db->query($sql);
       
       $channelId = $db->fetchOne("SELECT f_id FROM " . $gDb['prefix'] . "channels WHERE (f_name='$name')");
       
       $sql = "INSERT INTO " . $gDb['prefix'] . "contents SET f_type='0', f_channelId='$channelId', f_lang='$lang', f_subject='$name', f_content='$content', f_keywords='$keywords', f_description='$description', f_userId='$dAdminId', f_userName='$dAdminName', f_addTime='$now'";
       $db->query($sql);
       
       $contentId = $db->fetchOne("SELECT f_id FROM " . $gDb['prefix'] . "contents WHERE (f_channelId='$channelId' AND f_subject='$name')");
       $db->query("UPDATE " . $gDb['prefix'] . "channels SET f_contentId='$contentId' WHERE (f_id='$channelId')");
       
       $tpl->assign("msg", '添加成功。');
       $tpl->assign('backUrl', "list.php?p=$page");
       $tpl->display("_msg.tpl");
       exit();
   }  
   
   $tpl->assign('page', $page);
   
   $tpl->display();
   
?>