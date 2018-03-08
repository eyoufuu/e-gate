<?php

/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   
       
       $srcFile = $dRootDir . '#data/_config.php';
       $dstFile = $dRootDir . '_config.php';
       
       $content = file_get_contents($srcFile);
       
       $arr = array
       (
         array('SITENAME', $gSite['siteName']),
         array('SITENAMEEN', $gSite['siteNameEn']),
         array('SITENAMEOTHER', $gSite['siteNameOther']),
         array('SITEDOMAIN', $gSite['siteDomain']),
         array('SITEKEYWORDS', $gSite['siteKeywords']),
         array('SITEDESCRIPTION', $gSite['siteDescription']),
         array('URLPREFIX', $gUrlPrefix),
         array('DBHOST', $gDb['host']),
         array('DBUSER', $gDb['user']),
         array('DBPWD', $gDb['pwd']),
         array('DBNAME', $gDb['db']),
         array('DBPREFIX', $gDb['prefix']),
         array('THEME', $gTheme),
         array('DATE', date('Y-m-d')),
         array('USER', $dAdminName)
       );
       for ($i = 0; $i < count($arr); $i++)
       {
           $content = str_replace('~`~' . $arr[$i][0] . '~`~', $arr[$i][1], $content);
       }
       
       copy($dRootDir . '_config.php', $dRootDir . 'bak._config.php');
       
       $done = file_put_contents($dstFile, $content);
   
?>