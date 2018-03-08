<?php

  /*
   * File: function.paginator.php
   * 
   * Modified: 2008-7-18
   * By: red (9409249@gmail.com)
   *
   * Created: 2008-7-18
   * By: red (9409249@gmail.com)
   *
   * Link: http://mycom.yuanmas.com/
   */
   
    function smarty_function_paginator($params, &$smarty)
    {
        extract($params);    
        
        $necessaryParams = array('page', 'pagecount', 'url');
        $paramCount = count($necessaryParams);
        for ($i = 0; $i < $paramCount; $i++)
        {
            if (!isSet($$necessaryParams[$i]))
            {
                $smarty->trigger_error("paginator: missing '" . $necessaryParams[$i] . "' parameter");
                return;
            }
        }
        
        $page = !empty($page) ? intval($page) : 1;
        $pagecount = !empty($pagecount) ? intval($pagecount) : 0;
        $url = !empty($url) ? trim($url) : '';        
        $format = !empty($format) ? trim($format) : 'F P N L 第C/T页';
        $first = !empty($first) ? trim($first) : '首页';
        $last = !empty($last) ? trim($last) : '末页';
        $prev = !empty($prev) ? trim($prev) : '上一页';
        $next = !empty($next) ? trim($next) : '下一页';

        if (empty($url))
        {
            $smarty->trigger_error("paginator: invalid 'url' parameter");
            return;
        }            
                    
        $paginatorStr = $format;
        
        //当前页
        if (FALSE !== strpos($format, 'C'))
        {
            $paginatorStr = (0 == $pagecount) ? str_replace('C', 0, $paginatorStr) : str_replace('C', $page, $paginatorStr);;
        }
        
        //总页数
        if (FALSE !== strpos($format, 'T'))
        {
            $paginatorStr = str_replace('T', $pagecount, $paginatorStr);;
        }
    
        //首页
        if (FALSE !== strpos($format, 'F'))
        {
            if ($page <= 1)
            {
                $link = $first;
            }
            else
            {
                $tmpUrl = str_replace('PAGENUMBER', 1, $url);
                $link = '<a href="' . $tmpUrl . '">' . $first . '</a>';
            }
            
            $paginatorStr = str_replace('F', $link, $paginatorStr);;
        }
    
        //末页
        if (FALSE !== strpos($format, 'L'))
        {
            if ($page >= $pagecount)
            {
                $link = $last;
            }
            else
            {
                $tmpUrl = str_replace('PAGENUMBER', $pagecount, $url);
                $link = '<a href="' . $tmpUrl . '">' . $last . '</a>';
            }
            
            $paginatorStr = str_replace('L', $link, $paginatorStr);;
        }
    
        //上一页
        if (FALSE !== strpos($format, 'P'))
        {
            if ($page <= 1)
            {
                $link = $prev;
            }
            else
            {
                $tmpUrl = str_replace('PAGENUMBER', $page - 1, $url);
                $link = '<a href="' . $tmpUrl . '">' . $prev . '</a>';
            }
            
            $paginatorStr = str_replace('P', $link, $paginatorStr);;
        }
    
        //下一页
        if (FALSE !== strpos($format, 'N'))
        {
            if ($page >= $pagecount)
            {
                $link = $next;
            }
            else
            {
                $tmpUrl = str_replace('PAGENUMBER', $page + 1, $url);
                $link = '<a href="' . $tmpUrl . '">' . $next . '</a>';
            }
            
            $paginatorStr = str_replace('N', $link, $paginatorStr);;
        }
        
        print($paginatorStr);
    }    
   
?>