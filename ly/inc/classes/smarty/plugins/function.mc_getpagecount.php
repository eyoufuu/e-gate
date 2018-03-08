<?php

  /*
   * File: function.mc_getpagecount.php
   * 
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Link: http:/
   */
   
   function smarty_function_mc_getPageCount($params, &$smarty)
   {
        extract($params);
        
        $necessaryParams = array('type', 'pagesize', 'varname');
        $paramCount = count($necessaryParams);
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (!isSet($$necessaryParams[$i]))
            {
                $smarty->trigger_error("mc_getPageCount: missing '" . $necessaryParams[$i] . "' parameter");
                return;
            }
        }
                
        $type = !empty($type) ? trim($type) : '';
        $pagesize = !empty($pagesize) ? intval($pagesize) : '';
        $varname = !empty($varname) ? trim($varname) : '';  
        $item = !empty($item) ? trim($item) : 'f_id';
        $id = !empty($id) ? intval($id) : '';
        $lang = !empty($lang) ? trim($lang) : '';
            
        $paramCount = count($necessaryParams);
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (empty($$necessaryParams[$i]))
            {
                $smarty->trigger_error("mc_getPageCount: invalid '" . $necessaryParams[$i] . "' parameter");
                return;
            }
        }
                
        switch ($type)
        {
            case 'news':
                $condition = "f_type='1'";      
                $table = $gDb['prefix'] . 'contents';
                break;
                
            case 'product':
                $condition = "f_type='2'";                
                $table = $gDb['prefix'] . 'contents';
                break;
                
            case 'company':
                $condition = "f_type='3'";                
                $table = $gDb['prefix'] . 'contents';
                break;
                
            case 'job':
                $condition = "";      
                $table = $gDb['prefix'] . 'jobs';
                break;
                
            case 'guestbook':
                $condition = '';
                if (!empty($id))
                {
                    $condition = "f_userId='$id'";
                    $id = '';   
                    $status = '';
                }   
                $table = $gDb['prefix'] . 'guestbook';
                break;
                
            case 'user':
                $condition = "";      
                $table = $gDb['prefix'] . 'users';
                break;
                
            case 'other':
                $condition = "";      
                $table = $gDb['prefix'] . 'channels';
                break;
                
            case 'order':
                $condition = '';
                if (!empty($id))
                {
                    $condition = "f_userId='$id'";
                    $id = '';   
                }   
                $table = $gDb['prefix'] . 'orders';
                break;
                
            default:
                $smarty->trigger_error("mc_getPageCount: unknown 'type' parameter");
                return;
        }
        
        $and = '';
        if (!empty($condition))
        {
            $and = ' AND ';
        }
        
        if (!empty($status))
        {
            $condition .= $and . "f_status='$status'";
            $and = ' AND ';
        }
        
        if (!empty($id))
        {
            $condition .= $and . "f_categoryId='$id'";
            $and = ' AND ';
        }
        
        if (!empty($lang))
        {
            $condition .= $and . "f_lang='$lang'";
            $and = ' AND ';
        }
        
        $sqlCondition = '';
        $condition = trim($condition);
        if (!empty($condition))
        {
            $sqlCondition = "WHERE ($condition)";
        }
           
        global $db;        
        global $gDb;
        $table = $gDb['prefix'] . $table;
        $sql = "SELECT COUNT($item) AS _count FROM $table $sqlCondition";
        $arr = $db->fetchRow($sql);
        
        $smarty->assign($varname, ceil($arr['_count']/$pagesize));
        
        unset($arr);
   }
   
?>