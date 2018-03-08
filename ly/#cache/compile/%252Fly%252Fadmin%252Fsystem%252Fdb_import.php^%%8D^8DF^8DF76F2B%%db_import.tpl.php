<?php /* Smarty version 2.6.19, created on 2010-07-28 09:12:24
         compiled from db_import.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'filesize_format', 'db_import.tpl', 50, false),)), $this); ?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>备份与恢复</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<style type="text/css" title="currentStyle">
			@import "../js/js_new/demo_page.css";
			@import "../js/js_new/demo_table.css";
		</style>
</head>

<body>
  
  <h1>数据备份</h1>
</div>
<form name="form1" method="post" action="db_import.php">
       <p>
	      
         <input name="btnSubmit" type="submit" class="inputButton" id="btnSubmit" value=" 备份数据库 ">
		 <input name="symbol" type="hidden" id="user"  value="symbol" >
      </p>
</form>
  
  
  
  <h1>数据恢复</h1>
    <?php if ($this->_tpl_vars['arr']): ?>
    <table cellpadding="0" cellspacing="0" border="0"  class="display">
    	<thead>
        <tr>
        <th align="center">备份文件</th>
        <th align="center">大小</th>
        <th align="center">下载</th>
        <th align="center">恢复</th>
        <th align="center">删除</th>
        </tr>
        </thead>
        <tbody>
        <?php unset($this->_sections['loop']);
$this->_sections['loop']['name'] = 'loop';
$this->_sections['loop']['loop'] = is_array($_loop=$this->_tpl_vars['arr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['loop']['show'] = true;
$this->_sections['loop']['max'] = $this->_sections['loop']['loop'];
$this->_sections['loop']['step'] = 1;
$this->_sections['loop']['start'] = $this->_sections['loop']['step'] > 0 ? 0 : $this->_sections['loop']['loop']-1;
if ($this->_sections['loop']['show']) {
    $this->_sections['loop']['total'] = $this->_sections['loop']['loop'];
    if ($this->_sections['loop']['total'] == 0)
        $this->_sections['loop']['show'] = false;
} else
    $this->_sections['loop']['total'] = 0;
if ($this->_sections['loop']['show']):

            for ($this->_sections['loop']['index'] = $this->_sections['loop']['start'], $this->_sections['loop']['iteration'] = 1;
                 $this->_sections['loop']['iteration'] <= $this->_sections['loop']['total'];
                 $this->_sections['loop']['index'] += $this->_sections['loop']['step'], $this->_sections['loop']['iteration']++):
$this->_sections['loop']['rownum'] = $this->_sections['loop']['iteration'];
$this->_sections['loop']['index_prev'] = $this->_sections['loop']['index'] - $this->_sections['loop']['step'];
$this->_sections['loop']['index_next'] = $this->_sections['loop']['index'] + $this->_sections['loop']['step'];
$this->_sections['loop']['first']      = ($this->_sections['loop']['iteration'] == 1);
$this->_sections['loop']['last']       = ($this->_sections['loop']['iteration'] == $this->_sections['loop']['total']);
?>
        <?php if ($this->_sections['loop']['index']%2): ?>
        <?php $this->assign('bgClass', ""); ?>
        <?php else: ?>
        <?php $this->assign('bgClass', "class='gradeA'"); ?>
        <?php endif; ?>
        <tr <?php echo $this->_tpl_vars['bgClass']; ?>
>
            <td align="center"><?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['file']; ?>
</td>
            <td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr'][$this->_sections['loop']['index']]['size'])) ? $this->_run_mod_handler('filesize_format', true, $_tmp) : smarty_modifier_filesize_format($_tmp)); ?>
</td>
            <td align="center"><a href="download.php?action=db&file=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['file']; ?>
">下载此备份</a></td>
            <td align="center"><a href="?action=import&file=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['file']; ?>
" onClick="return confirm('您将使用“ <?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['file']; ?>
 ”这个备份数据恢复，是否确定？');">使用此备份恢复</a></td>
            <td align="center"><a href="?action=delete&file=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['file']; ?>
" onClick="return confirm('您将删除“ <?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['file']; ?>
 ”这个备份，是否确定？');">删除此备份</a></td>
        </tr>
        <?php endfor; endif; ?>
        </tbody>
        <tfoot>
        <tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>
        </tfoot>
    </table>
    <?php else: ?>
    目前还没有备份。
    <?php endif; ?>
</body>
</html>