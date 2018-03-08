<?php

  /*
   * File: template.php
   * 
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2008-7-14
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Link: http:
   */
   
   require_once($dRootDir . 'inc/classes/smarty/Smarty.class.php');
   
   class Template extends Smarty
   {
       function Template($arrParams = '')
       {
            if (is_array($arrParams))
            {
                $this->template_dir = $arrParams['templateDir'];
                $this->config_dir = $arrParams['configDir'];
                $this->compile_dir = $arrParams['compileDir'];
                $this->cache_dir = $arrParams['cacheDir'];
                $this->left_delimiter = $arrParams['leftDelimiter'];
                $this->right_delimiter = $arrParams['rightDelimiter']; 
                $this->compile_id = $_SERVER["PHP_SELF"];
            }
            else
            {
                $this->template_dir = "./";
                $this->config_dir = "./";
                $this->compile_dir = "./tmp/compile/";
                $this->cache_dir = "./tmp/cache/";
                $this->left_delimiter = '{';
                $this->right_delimiter = '}';
                $this->compile_id = $_SERVER["PHP_SELF"];
            }
            
            $this->use_sub_dirs = false;
       }
        
        //取出合成HTML结果
        function fetch($templateFile = '', $cacheId = null, $compileId = null, $display = false)
        {
            $templateFile = trim($templateFile);
            
            if (empty($templateFile))
            {
                $templateFile = $this->getTemplateFileName();
            }
            
            return parent::fetch($templateFile, $cacheId, $compileId, $display);
        }
        
        //调用模板，合成后输出
        function display($templateFile = '', $cacheId = null, $compileId = null)
        {
            $templateFile = trim($templateFile);
            
            if (empty($templateFile))
            {
                $templateFile = $this->getTemplateFileName();
            }
            
            parent::display($templateFile, $cacheId, $compileId);
        }
        
        //获取默认模板文件名
        function getTemplateFileName()
        {
            $path = (!empty ($_SERVER["SCRIPT_FILENAME"])) ? $_SERVER["SCRIPT_FILENAME"] : $_SERVER["PATH_TRANSLATED"];
            $templateFile = basename($path);
            $templateFile = str_replace('.php', '.tpl', $templateFile);
            
            return $templateFile;
        }
   }
   
?>