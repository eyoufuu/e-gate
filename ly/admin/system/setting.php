<?php

  /*
   * File: setting.php
   * 
   * Modified: 2008-7-25
   * By: red (9409249@gmail.com)
   *
   * Created: 2008-7-25
   * By: red (9409249@gmail.com)
   *
   * Link: http://mycom.yuanmas.com/
   */
   
   require_once("_inc.php");
      
   if (!empty($_POST['btnSubmit']))
   {
       $arrVars = array('siteName', 'siteNameEn', 'siteNameOther', 'siteDomain', 'siteKeywords', 'siteDescription', 'urlPrefix');
       foreach ($arrVars AS $key)
       {
           $$key = !empty($_POST[$key]) ? trim($_POST[$key]) : '';
       }
       
       if (empty($siteName) || empty($urlPrefix))
       {
           $tpl->assign("msg", '缺少参数。请完整输入。');
           $tpl->assign('backUrl', "setting.php");
           $tpl->display("_msg.tpl");
           exit();
       }
       
       $siteKeywords = str_replace("，", ',', $siteKeywords);
       $siteKeywords = str_replace("　", ',', $siteKeywords);
       $siteKeywords = str_replace(" ", ',', $siteKeywords);
       
       $gSite['siteName'] = $siteName;
       $gSite['siteNameEn'] = $siteNameEn;
       $gSite['siteNameOther'] = $siteNameOther;
       $gSite['siteDomain'] = $siteDomain;
       $gSite['siteKeywords'] = $siteKeywords;
       $gSite['siteDescription'] = $siteDescription;
       
       $gUrlPrefix = $urlPrefix;

       require('_save_config.php');
       
       if ($done)
       {
           $msg = "修改成功！";
       }
       else
       {
           $msg = "修改失败。";
       }
       
       $tpl->assign('msg', $msg);
       $tpl->display('_msg.tpl');
       exit();
   }
   
   $tpl->assign($gSite);
   $tpl->assign('urlPrefix', $gUrlPrefix);
   
   $tpl->display();
   
?>