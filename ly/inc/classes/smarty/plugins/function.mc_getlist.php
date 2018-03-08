<?php

  /*
   * File: function.mc_getlist.php
   * 
   * Modified: 2010-3-2
   * By: qianbo (9409249@gmail.com)
   *
   * Created: 2008-7-17
   * By: red (9409249@gmail.com)
   *
   */
   
   function smarty_function_mc_getList($params, &$smarty)
   {
        extract($params);
        
        $necessaryParams = array('type', 'varname');
        $paramCount = count($necessaryParams);
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (!isSet($$necessaryParams[$i]))
            {
                $smarty->trigger_error("mc_getList: missing '" . $necessaryParams[$i] . "' parameter");
                return;
            }
        }
                
        $type = !empty($type) ? trim($type) : '';
        $pagesize = !empty($pagesize) ? intval($pagesize) : '';
        $varname = !empty($varname) ? trim($varname) : '';    
        $id = !empty($id) ? trim($id) : '';
        $items = !empty($items) ? trim($items) : '*';
        $page = !empty($page) ? intval($page) : '1';
        $recommend = !empty($recommend) ? intval($recommend) : FALSE; 
        $havepic = !empty($havepic) ? $havepic : FALSE; 
        $lang = !empty($lang) ? trim($lang) : '';
        $group = !empty($group) ? trim($group) : '';
        $status = !empty($status) ? intval($status) : '';
        $list = !empty($list) ? intval($list) : '';
        $orderby = !empty($orderby) ? trim($orderby) : 'f_id DESC';   
        
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (empty($$necessaryParams[$i]))
            {
                $smarty->trigger_error("mc_getList: invalid '" . $necessaryParams[$i] . "' parameter");
                return;
            }
        }
        $ly_config = 0;
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
                
            case 'admin':
                $condition = "";      
                $table = $gDb['prefix'] . 'admins';
                break;
            
			case 'ly_config_interface':
			    $ly_config = 1;
				$condition = "";
				$table = "cardinfo";
				$orderby = "";
				break;				
            default:
                $smarty->trigger_error("mc_getList: unknown 'type' parameter");
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
        
        if ($recommend)
        {
            $condition .= $and . "f_isRecommend='1'";
            $and = ' AND ';
        }
        
        if ($havepic)
        {
            $condition .= $and . "f_pic<>''";
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
        
        if (!empty($group))
        {
            $condition .= $and . "f_groupId='$group'";
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
           
        global $db;
        global $gDb;
		if($ly_config == 0)
		{
           $table = $gDb['prefix'] . $table;
		}
        $sql = "SELECT $items FROM $table $sqlCondition $sqlOrderby";
        //print($sql);
        
        if (!empty($pagesize))
        {
            $arr = $db->fetchPage($sql, $page, $pagesize);
        }
        else
        {
            $arr = $db->fetchRows($sql);
        }
        
        $smarty->assign($varname, $arr);
        
        unset($arr);
   }
   
?>