<?php

  /*
   * Modified on: 2008-7-17
   * By: red (9409249@gmail.com)
   *
   * Created on: 2008-7-14
   * By: red (9409249@gmail.com)
   *
   */
   
    function smarty_function_db_fetchRow($params, &$smarty)
    {
        extract($params);
        
        $table = !empty($table) ? trim($table) : '';
        $items = !empty($items) ? trim($items) : '';
        $condition = !empty($condition) ? trim($condition) : '';
        $varname = !empty($varname) ? trim($varname) : '';
        
        $necessaryParams = array('table', 'items', 'condition', 'varname');
        $paramCount = count($necessaryParams);
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (!isSet($$necessaryParams[$i]))
            {
                $smarty->trigger_error("db_FetchRow: missing '" . $necessaryParams[$i] . "' parameter");
                return;
            }
            
            $$necessaryParams[$i] = trim($$necessaryParams[$i]);
        }
        
        $sqlCondition = '';
        $condition = trim($condition);
        if (!empty($condition))
        {
            $sqlCondition = "WHERE ($condition)";
        }
           
        global $db;
        $sql = "SELECT $items FROM $table $sqlCondition";
        $arr = $db->fetchRow($sql);
        
        $smarty->assign($varname, $arr);
        
        unset($arr);
    }
   
?>