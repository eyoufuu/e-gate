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
      
   if (!empty($_GET['themeName']))
   {
       $themeName = trim($_GET['themeName']);
       
       $gTheme = $themeName;

       require('_save_config.php');
       
       if ($done)
       {
           header("location: ?");
           eixt();
       }
       else
       {
           $msg = "修改失败。";
           $tpl->assign('msg', $msg);
           $tpl->display('_msg.tpl');
           exit();
       }
   }
   
   $tpl->assign($gSite);
   $tpl->assign('currentTheme', $gTheme);
   
   $themeRootDir = '../../themes/';
   $arrThemes = array();
   if ($handle = openDir($themeRootDir)) 
   {    
        while (FALSE !== ($file = readDir($handle)))
        {
            if (('.' != $file) && ('..' != $file) && ('CVS' != $file) && is_dir($themeRootDir . $file) && ($gTheme != $file))
            {
                $arrThemes[] = $file;
            }
        }    
        closedir($handle);
   }
   $tpl->assign('arrThemes', $arrThemes);
   
   $tpl->display();
   
?>