<?php
    
    /*
     * Modified on: 2008-7-17
     * By: red (9409249@gmail.com)
     *
     * Created on: 2008-7-14
     * By: red (9409249@gmail.com)
     *
     */
    
    function smarty_function_db_fetchPage($params, &$smarty)
    {    
        extract($params);
        
        $table = !empty($table) ? trim($table) : '';
        $items = !empty($items) ? trim($items) : '';
        $condition = !empty($condition) ? trim($condition) : '';
        $orderby = !empty($orderby) ? trim($orderby) : '';
        $page = !empty($page) ? intval($page) : 0;
        $pagesize = !empty($pagesize) ? intval($pagesize) : 20;
        $varname = !empty($varname) ? trim($varname) : '';
        
        $necessaryParams = array('table', 'items', 'pagesize', 'varname');
        $paramCount = count($necessaryParams);
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (!isSet($$necessaryParams[$i]))
            {
                $smarty->trigger_error("db_FetchPage: missing '" . $necessaryParams[$i] . "' parameter");
                return;
            }
            
            $$necessaryParams[$i] = trim($$necessaryParams[$i]);
        }
            
        $sqlCondition = '';
        if (!empty($condition))
        {
            $sqlCondition = "WHERE ($condition)";
        }
        
        $sqlOrderby = '';
        if (!empty($orderby))
        {
            $sqlOrderby = "ORDER BY $orderby";
        }
               
        global $db;
        global $page;
        global $gDb;
        $table = $gDb['prefix'] . $table;
        $sql = "SELECT $items FROM $table $sqlCondition $sqlOrderby";
        $arr = $db->fetchPage($sql, $page, $pagesize);
        
        $smarty->assign($varname, $arr);
        
        unset($arr);
    }
?>