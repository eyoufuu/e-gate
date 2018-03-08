<?php

/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   
   @set_time_limit(0);
   
   require_once('_inc.php');
   
   $pageSize = 1000;
   $dataDir = '../../#data/db/';
   
   if (!empty($_POST['btnSubmit']))
   {                     
       if (!count($_POST['arrTables']))
       {
           $tpl->assign("msg", '请选择要备份的数据表。');
           $tpl->assign('backUrl', "db_export.php");
           $tpl->display("_msg.tpl");
           exit();
       }
       
       $filename = date('Ymd-His') . '.sql';
       $file = $dataDir . $filename;
       
       for ($i = 0; $i < count($_POST['arrTables']); $i++)
       {
            $table = $_POST['arrTables'][$i];
            
            $recCount = $db->fetchOne("SELECT COUNT(*) FROM `$table`");
            $pageCount = ceil($recCount / $pageSize);
            
            for ($pageIndex = 1; $pageIndex <= $pageCount; $pageIndex++)
            {
                $recFrom = ($pageIndex - 1) * $pageSize;
                
                $arr = $db->fetchRows("SELECT * FROM `$table` LIMIT $recFrom, $pageSize");
                $rowsCount = count($arr);
                if ($rowsCount > 0)
                {
                    for ($j = 0; $j < $rowsCount; $j++)
                    {
                        $arrValues = array();
                        foreach ($arr[$j] AS $value)
                        {
                            if (NULL === $value)
                            {
                                $arrValues[] = 'NULL';
                            }
                            else
                            {
                                $value = "'" . addSlashes($value). "'";
                                $value = str_replace("\r", '\r', $value);
                                $value = str_replace("\n", '\n', $value);
                                $arrValues[] = $value;
                            }
                        }
                        
                        writeSql($file, "REPLACE INTO `$table` VALUES (" . implode(", ", $arrValues) . ");\n");
                    }
                }
            }
            
            writeSql($file, "\n");        
       }
       
       //压缩
	
       $filename = str_replace('.sql', '.zip', $filename);
       require_once($dRootDir . 'inc/classes/phpzip.php');
	   
       $zip = new phpzip();
       $zip->zip($file, $dataDir . $filename);
       unlink($file);
       
       $tpl->assign("msg", "数据备份成功！<br/>备份文件为：$filename");
       $tpl->assign('backUrl', "db_export.php");
       $tpl->display("_msg.tpl");
       exit();
   }  
   
   $arr = $db->fetchRows('SHOW TABLE STATUS');
   $tpl->assign('arr', $arr);
   
   $tpl->display();
    
    //写入文件
    function writeSql($file, $content)
    {        
        $fp = fopen($file, 'a+');
        flock($fp, 2);
        fwrite($fp, $content);
        fclose($fp);
    }
   
?>