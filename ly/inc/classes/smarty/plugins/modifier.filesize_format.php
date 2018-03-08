<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty lower modifier plugin
 *
 * Type:     modifier<br>
 * Name:     lower<br>
 * Purpose:  convert string to lowercase
 * @link http://smarty.php.net/manual/en/language.modifier.lower.php
 *          lower (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_modifier_filesize_format($bits)
{
    $mb = 1024 * 1024;
    if ($bits > $mb)
    {
        $bitStr = number_format(round($bits / $mb, 2), 2) . "M";
    }
    else
    {        
        $bitStr = number_format(round($bits / 1024, 2), 2) . "K";
    }
    
    return $bitStr;    
}

?>
