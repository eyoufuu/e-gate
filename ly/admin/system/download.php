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
      
   if (('db' == $_GET['action']) && (!empty($_GET['file'])))
   {
        $dataDir = '../../#data/db/';
        
        $filename = basename($_GET['file']);
        $file = $dataDir . $filename;
        
        if (!file_exists($file))
        {
            $tpl->assign("msg", "文件不存在。");
            $tpl->assign('backUrl', "db_import.php");
            $tpl->display("_msg.tpl");
            exit();
        }
        
        $fileSize = filesize($file); 
        
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Content-Length: " . $fileSize);
        Header("Content-Disposition: attachment; filename=$filename");
        
        $fp = fopen($file, "r");
        $bufferSize = 1024;
        $curPos = 0;
        
        while(!feof($fp) && (($fileSize - $curPos) > $bufferSize))
        {
            $buffer = fread($fp,$bufferSize);
            print($buffer);
            $curPos += $bufferSize;
        }
        
        $buffer = fread($fp, $fileSize - $curPos);
        print($buffer);
   }
   
?>