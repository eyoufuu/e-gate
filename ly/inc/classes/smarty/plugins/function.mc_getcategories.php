<?php

  /*
   * File: function.mc_getcategories.php
   * 
   * Modified: 2008-7-18
   * By: red (9409249@gmail.com)
   *
   * Created: 2008-7-17
   * By: red (9409249@gmail.com)
   *
   * Link: http://mycom.yuanmas.com/
   */
   
   function smarty_function_mc_getCategories($params, &$smarty)
   {
        extract($params);
        
        $necessaryParams = array('type', 'varname');
        $paramCount = count($necessaryParams);
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (!isSet($$necessaryParams[$i]))
            {
                $smarty->trigger_error("mc_getCategories: missing '" . $necessaryParams[$i] . "' parameter");
                return;
            }
        }
                
        $type = !empty($type) ? trim($type) : '';
        $varname = !empty($varname) ? trim($varname) : '';    
        $items = !empty($items) ? trim($items) : '*';
        $count = !empty($count) ? intval($count) : '';
        $lang = !empty($lang) ? trim($lang) : '';
        $list = !empty($list) ? intval($list) : '';
        $orderby = !empty($orderby) ? trim($orderby) : '';
        
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (empty($$necessaryParams[$i]))
            {
                $smarty->trigger_error("mc_getCategories: invalid '" . $necessaryParams[$i] . "' parameter");
                return;
            }
        }
        
        switch ($type)
        {
            case 'news':
                $table = 'categories';
                $condition = "f_type='1'";
                break;
                
            case 'product':
                $table = 'categories';
                $condition = "f_type='2'";
                break;
                
            case 'company':
                $table = 'categories';
                $condition = "f_type='3'";
                break;
                
            case 'admin':
                $table = 'admingroups';
                $condition = "";
                break;
                
            default:
                $smarty->trigger_error("mc_getCategories: unknown 'type' parameter");
                return;
        }
        
        $and = '';
        if (!empty($condition))
        {
            $and = ' AND ';
        }
             
        if (!empty($lang))
        {
            $condition .= $and . "f_lang='$lang'";
            $and = ' AND ';
        }        
        
        if (!empty($list))
        {
            $condition .= $and . "f_list='$list'";
            $and = ' AND ';
        }
        
        $sqlCondition = '';
        $condition = trim($condition);
        if (!empty($condition))
        {
            $sqlCondition = "WHERE ($condition)";
        }
        
        $sqlOrderby = '';
        $orderby = trim($orderby);
        if (!empty($orderby))
        {
            $sqlOrderby = "ORDER BY $orderby";
        }
        
        $sqlLimit = '';
        if (!empty($count))
        {
            $sqlLimit = "LIMIT $count";
        }
           
        global $db;
        global $gDb;
        $table = $gDb['prefix'] . $table;
        $sql = "SELECT $items FROM $table $sqlCondition $sqlOrderby $sqlLimit";
        $arr = $db->fetchRows($sql);
        
        $smarty->assign($varname, $arr);
        
        unset($arr);
   }
   
?>