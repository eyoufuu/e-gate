<?php /* Smarty version 2.6.19, created on 2010-07-28 09:04:35
         compiled from admin_group_add.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
<script type="text/javascript" src="../../common/fckeditor/fckeditor.js"></script>
</head>

<body>
  <h1>添加管理员组</h1>
<form action="" method="post" enctype="multipart/form-data" name="form1" onSubmit="return notEmpty(name, '请输入管理员组名称。')">
<div class="bgFleet paddingAll"> 
<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td align="right"><span class="fontRed">*</span> 管理员组名称： </td>
    <td>
      <input name="name" type="text" id="name" maxlength="255">
    </td>
  </tr>
  <tr>
    <td align="right" valign="top"><span class="fontRed">*</span> 管理员组权限：</td>
    <td>
      <table>     
      <tr> 
      <?php unset($this->_sections['loop']);
$this->_sections['loop']['name'] = 'loop';
$this->_sections['loop']['loop'] = is_array($_loop=$this->_tpl_vars['arrPurviews']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
      <?php if ($this->_sections['loop']['index']%4 == 0): ?>
      </tr>
      <tr>
      <?php endif; ?>
        <td><input name="arrPurviews[]" type="checkbox" value="<?php echo $this->_tpl_vars['arrPurviews'][$this->_sections['loop']['index']]['key']; ?>
"><?php echo $this->_tpl_vars['arrPurviews'][$this->_sections['loop']['index']]['name']; ?>
&nbsp;&nbsp;</td>
      <?php endfor; endif; ?>
      </tr>
      </table>
    </td>
  </tr>
</table>
</div>
<br>
<input name="btnSubmit" type="submit" class="inputButton_in" style="margin-left:500px;" size="20" id="btnSubmit" value=" 提交 ">
</form>
</body>
</html>