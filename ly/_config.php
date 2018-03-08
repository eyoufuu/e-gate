<?php

  /*
   * File: _config.php
   * 
   * 
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Link: http://www.lysafe365.com/
   */
   
   //程序版本
   $gVersion = '1.0.1';
   
   //数据库参数
   $gDb = array
   (
       'host' => 'localhost',     //主机名
       'user' => 'root',     //用户
       'pwd' => '123456',     //密码
       'db' => 'baseconfig',    //数据库名
       'prefix' => 'ly_'     //表前缀
   );
   $gDbaudit = array
   (
       'host' => 'localhost',     //主机
       'user' => 'root',     //用户
       'pwd' => '123456',     //密码
       'db' => 'audit',    //数据库名
       'prefix' => 'ly_'     //表前缀
   );
   //网站参数
   $gSite = array
   (
       'siteName' => '这里放置公司名称',     //网站名称
       'siteNameEn' => '',     //网站名称(ENGLISH)
       'siteNameOther' => '',     //网站名称(其它语言)
       'siteDomain' => 'localhost',     //网站域名
       'siteKeywords' => '企业网站,com',    //关键词
       'siteDescription' => '这是XX公司官方网站。'    //网站描述
   );
   
   //风格
   $gTheme = 'customer';
   
   //URL参数
   $gUrlPrefix = './?';
   
   $gCmd = "/usr/bin/sudo /home/sndcmd"; 
?>