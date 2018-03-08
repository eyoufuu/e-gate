<?php

  /*
   * File: function.mc_getcontent.php
   * 
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Link: http:
   */
   
   function smarty_function_mc_getContent($params, &$smarty)
   {
        extract($params);
        
        $necessaryParams = array('type', 'id', 'varname');
        $paramCount = count($necessaryParams);
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (!isSet($$necessaryParams[$i]))
            {
                $smarty->trigger_error("mc_getcontent: missing '" . $necessaryParams[$i] . "' parameter");
                return;
            }
        }
        
        $type = !empty($type) ? trim($type) : '';
        $id = !empty($id) ? trim($id) : '';
        $varname = !empty($varname) ? trim($varname) : ''; 
        $items = !empty($items) ? trim($items) : '*'; 
        $lang = !empty($lang) ? trim($lang) : '';
        
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (empty($$necessaryParams[$i]))
            {
                $smarty->trigger_error("mc_getcontent: invalid '" . $necessaryParams[$i] . "' parameter");
                return;
            }
        }
        
        switch ($type)
        {                
            case 'category':
                $condition = "f_id='$id'";                
                $table = 'categories';
                break;
                
            case 'news':    
                $table = 'contents';  
                $condition = "f_id='$id'";   
                break;
                
            case 'product':         
                $table = 'contents';
                $condition = "f_id='$id'";
                break;
                
            case 'company':         
                $table = 'contents';
                $condition = "f_id='$id'";
                break;
                
            case 'job':
                $condition = "f_id='$id'";      
                $table = 'jobs';
                break;
                
            case 'guestbook':
                $condition = "f_id='$id'";      
                $table = 'guestbook';
                break;
                
            case 'user':
                $condition = "f_id='$id'";      
                $table = 'users';
                break;
                
            case 'channel':
                $condition = "f_id='$id'";      
                $table = 'channels';
                break;
                
            case 'content':
                $condition = "f_id='$id'";      
                $table = 'contents';
                break;
                
            case 'order':
                $condition = "f_id='$id'";      
                $table = 'orders';
                break;
                
            case 'admin':
                $condition = "f_id='$id'";      
                $table = 'admins';
                break;
                
            case 'admingroup':
                $condition = "f_id='$id'";      
                $table = 'admingroups';
                break;
                
            default:
                $smarty->trigger_error("mc_getContent: unknown 'type' parameter");
                return;
        }
        
        $sqlCondition = '';
        if (!empty($condition))
        {
            $sqlCondition = "WHERE ($condition)";
        }
           
        global $db;
        global $gDb;
        $table = $gDb['prefix'] . $table;
        $sql = "SELECT $items FROM $table $sqlCondition";
        $arr = $db->fetchRow($sql);
        
        $smarty->assign($varname, $arr);
        
        unset($arr);
   }
   
?>