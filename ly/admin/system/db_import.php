<?php
 require_once('_inc.php');
 if(isset($_POST['symbol']))
 {
    $dataDir = '../../#data/db/';
	$filename = date('Ymd-His') . '.sql';
      
    $command = "/opt/lampp/bin/mysqldump baseconfig > ".$dataDir."/".$filename;
    exec($command);
	 $tpl->assign("msg", "数据备份成功！");
        $tpl->assign('backUrl', "db_import.php");
        $tpl->display("_msg.tpl");
        exit();  
 }
   @set_time_limit(0);
   
  
   
   $dataDir = '../../#data/db/';
   
   if (('import' == $_GET['action']) && (!empty($_GET['file'])))
   {
      /* $filename = $_GET['file'];
       $oriFilename = '';
       if (preg_match("/.zip$/", $filename))
       {
           //解压缩
           $oriFilename = $filename;
           $filename = str_replace('.zip$', '.sql', $filename);
           require_once($dRootDir . 'inc/classes/phpzip.php');
           $zip = new phpzip();
           $zip->unzip($dataDir . $oriFilename, $dataDir);
       }
       
        $fp = fopen($dataDir . $filename, "r") OR die('File not found.');
        $sql = '';
        while (!feof($fp))
        {
            $line = trim(fgets($fp, 512 * 1024));
			
            if (preg_match("/;$/", $line))
            {
                $sql .= $line;
                $db->exec($sql);
                
                $sql = '';
            }
			
            else if (!preg_match("/^(\|--)/", $line))
            {
                $sql .= $line;
            }
        }
        fclose($fp);    
        
        if (!empty($oriFilename))
        {
            unlink($dataDir . $filename);
        }
      */
	    $dataDir = '../../#data/db/';
	    $filename = date('Ymd-His') . '.sql';
      
        $command = "/opt/lampp/bin/mysql baseconfig < ".$dataDir."/".$_GET['file'];
        exec($command);
        $tpl->assign("msg", "数据恢复成功！");
        $tpl->assign('backUrl', "db_import.php");
        $tpl->display("_msg.tpl");
        exit();  
   }  
   
   if (('delete' == $_GET['action']) && (!empty($_GET['file'])))
   {          
       if (unlink($dataDir . $_GET['file']))
       {
           header("location: ?");
           exit();
       }
   }
      
   $arrSqls = array();
   if ($handle = openDir($dataDir)) 
   {    
        while (FALSE !== ($file = readDir($handle)))
        {
            if (is_file($dataDir . $file))
            {
                $arrSqls[] = array
                (
                    'file' => $file,
                    'size' => filesize($dataDir . $file)
                );
            }
        }    
        closedir($handle);
   }
   $tpl->assign('arr', $arrSqls);
   
   $tpl->display();
?>