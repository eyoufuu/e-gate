<?php

  /*
   * Modified on: 2008-7-17
   * By: red (9409249@gmail.com)
   *
   * Created on: 2008-7-14
   * By: red (9409249@gmail.com)
   *
   */
   
    function smarty_function_db_fetchPageCount($params, &$smarty)
    {
        extract($params);
                
        $necessaryParams = array('table', 'pagesize', 'varname');
        $paramCount = count($necessaryParams);
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (!isSet($$necessaryParams[$i]))
            {
                $smarty->trigger_error("db_FetchPageCount: missing '" . $necessaryParams[$i] . "' parameter");
                return;
            }
            
            $$necessaryParams[$i] = trim($$necessaryParams[$i]);
        }
        
        $table = !empty($table) ? trim($table) : '';
        $item = !empty($item) ? trim($item) : 'f_id';
        $condition = !empty($condition) ? trim($condition) : '';
        $pagesize = !empty($pagesize) ? trim($pagesize) : '20';
        $varname = !empty($varname) ? trim($varname) : '';
        
        if (empty($table))
        {
            $smarty->trigger_error("db_FetchPageCount: invalid 'table' parameter");
            return;
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
        
        $count = $arr['_count'];
                
        $smarty->assign($varname, ceil($arr['_count']/$pagesize));
        
        unset($arr);
    }
   
?>