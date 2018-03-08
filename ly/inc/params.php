<?php

  /*
   * File: params.php
   * 
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Link: http://www.lysafe365.com/
   */
      
   //功能模块
   $gModules = array
   (
        'i'=>'index',    //首页
        'c'=>'company',    //公司介绍
        'n'=>'news',    //新闻
        'p'=>'product',    //产品
        'j'=>'job',    //招聘
        'g'=>'guestbook',    //留言
        'u'=>'user',    //用户中心
        'o'=>'other'    //其它栏目
   );
   
   //语言
   $gLangs = array
   (
        array('name'=>'cn', 'description'=>'简体中文'), 
        array('name'=>'tw', 'description'=>'繁體中文'),
        array('name'=>'en', 'description'=>'English')
    );

   
   //模板引擎参数
   $gTemplate = array
   (
        'templateDir' => "./",
        'configDir' => "./",
        'compileDir' => $dRootDir . "#cache/compile/",
        'cacheDir' => $dRootDir . "#cache/cache/",
        'leftDelimiter' => '{--',
        'rightDelimiter' => '--}'
   );
   
   //文件上传参数
   $gUpload = array
   (
       'dir' => 'uploads',
       'imageWidth' => 120,   //缩略图宽度
       'imageHeight' => '*'    //缩略图高度。若设为“*”，则自动按比例缩放
   );
   
?>