<?php

    /*
     * Modified on: 2008-7-14
     * By: red (9409249@gmail.com)
     *
     * Created on: 2008-7-14
     * By: red (9409249@gmail.com)
     *
     */
    
    function smarty_modifier_cnsubstr($str, $len)
    {
        $tmpstr = '';
        $start = 0;
        
        $len = $len * 3;
        
        if ($start < 0 || ($start > strlen($str)))
        {
            return '';
        }

        if (strlen($str) <= $len)
        {
            return $str;
        }

        $strlen = $start + $len - 3;
        for ($i = $start; $i < $strlen; $i++)
        {
            if (ord($str[$i]) > 0x80)
            {
                $tmpstr .= $str[$i] . $str[++ $i] . $str[++ $i];
            }
            else
            {
                $tmpstr .= $str[$i];
            }
        }

        return $tmpstr . "…";
    }
?>