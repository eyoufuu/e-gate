<?php


/*
 * File: util.php
 * Link: http://
 * 
 * Modified: 2010-3-2
 * By: qianbo (qianbo@chd.edu.cn)
 *
 * Created: 2008-7-17
 * By: qianbo (qianbo@chd.edu.cn)
 *
 */

class Util
{
    //处理GPC转义
    function gpc($gpcType = "")
    {
        $gpcFlag = ini_get('magic_quotes_gpc');

        if (ini_get('magic_quotes_gpc'))
        {
            //已开启，不需处理
            return;
        }
        
        if (is_array($_GET))
        {
            foreach ($_GET AS $key => $value)
            {
                $_GET[$key] = addSlashes($value);
            }
        }
        
        if (is_array($_POST))
        {
            foreach ($_POST AS $key => $value)
            {
                if (!is_array($_POST[$key]))
                {
                    $_POST[$key] = addSlashes($value);
                }
                else
                {
                    //复选框处理
                    foreach ($_POST[$key] AS $subKey => $subValue)
                    {
                        $_POST[$key][$subKey] = addSlashes($subValue);
                    }
                }
            }
        }
    }

    //输出数组
    function printArray($arr)
    {
        print ('<xmp>');
        print_r($arr);
        print ('</xmp>');
    }
    
    //缩小图片
    function resizeImage($srcFilename, $dstFilename, $dstWidth, $dstHeight = '*', $force = FALSE, $markFilename = '')
    {
        $srcImgData = @imageCreateFromJpeg($srcFilename);
        if (!$srcImgData)
        {
            $srcImgData = @imageCreateFromGif($srcFilename);
        }
        
        if (!$srcImgData)
        {
            $srcImgData = @imageCreateFromPng($srcFilename);
        }
        
        if (!$srcImgData)
        {
            return FALSE;
        }
        
        list ($srcWidth, $srcHeight) = getImageSize($srcFilename);
        
        if ((!$force) && ($dstWidth > $srcWidth))
        {
            $dstWidth = $srcWidth;
        }
        
        if ('*' == $dstHeight)
        {
            $dstHeight = round(($srcHeight / $srcWidth) * $dstWidth, 0);
        }
        
        
        $dstImgData = imageCreateTrueColor($dstWidth, $dstHeight);
        
        imageCopyResized($dstImgData, $srcImgData, 0, 0, 0, 0, $dstWidth, $dstHeight, $srcWidth, $srcHeight);
    
        /*
        //加水印
        if (!empty($markFilename))
        {
            $srcImgData = imageCreateFromGif($markFilename);
            list ($srcWidth, $srcHeight) = getImagesize($srcFilename);
            imageCopy($dstImgData, $srcImgData, 0, 0, 0, 0, $srcWidth, $srcHeight);
        }
        */
        
        /*
        //加字
        $str = 'MyCOM';    
        $textColor = imageColorAllocate($dstImgData, 100, 100, 100);
        $font = 2;
        $x = 23;
        $y = 46;
        imageString($dstImgData, $font, $x, $y, $str, $textColor);
        $textColor = imageColorAllocate($dstImgData, 200, 200, 200);
        $x = $x - 1;
        $y = $y - 1;
        imageString($dstImgData, $font, $x, $y, $str, $textColor);
        */
        
        //保存图片
        $ext = Util::getFileExt($dstFilename);
        
        switch ($ext)
        {                
            case 'gif':
                imageGif($dstImgData, $dstFilename);
                break;
                
            case 'png':
                imagePng($dstImgData, $dstFilename);
                break;
                
            case 'jpg':
            default:
                imageJpeg($dstImgData, $dstFilename);
                break;
        }
        
        
        //释放临时图片
        imageDestroy($dstImgData);
        imageDestroy($srcImgData);
        
        return TRUE;
    }
    
    //获取文件扩展名
    function getFileExt($fileName)
    {
        return strrchr($fileName, '.');
    }
}
?>