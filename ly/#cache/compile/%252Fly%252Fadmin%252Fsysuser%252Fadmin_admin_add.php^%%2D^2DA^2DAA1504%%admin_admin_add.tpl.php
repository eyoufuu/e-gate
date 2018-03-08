<?php /* Smarty version 2.6.19, created on 2010-07-28 09:12:20
         compiled from admin_admin_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'mc_getcategories', 'admin_admin_add.tpl', 20, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
</head>

<body>
  <h1>添加管理员</h1>
<form action="" method="post" enctype="multipart/form-data" name="form1" onSubmit="return notEmpty(groupId, '请选择所属管理员组。')&&notEmpty(userName, '请输入管理员登录名。')&&notEmpty(userPwd, '请输入登录密码。')">
<div class="bgFleet paddingAll"> 
<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td align="right"><span class="fontRed">* </span>所属管理员组：</td>
    <td>
      <select name="groupId" id="groupId">          
        <option value="">请选择</option>       
      <?php echo smarty_function_mc_getcategories(array('type' => 'admin','varname' => 'arrGroups'), $this);?>

      <?php unset($this->_sections['loop']);
$this->_sections['loop']['name'] = 'loop';
$this->_sections['loop']['loop'] = is_array($_loop=$this->_tpl_vars['arrGroups']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
        <option value="<?php echo $this->_tpl_vars['arrGroups'][$this->_sections['loop']['index']]['f_id']; ?>
" <?php if ($this->_tpl_vars['groupId'] == $this->_tpl_vars['arrGroups'][$this->_sections['loop']['index']]['f_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['arrGroups'][$this->_sections['loop']['index']]['f_name']; ?>
</option>        
      <?php endfor; endif; ?>          
      </select>
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fontRed">*</span> 登录名： </td>
    <td>
      <input name="userName" type="text" id="userName" maxlength="255">
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fontRed">*</span> 登录密码：</td>
    <td>
      <input name="userPwd" type="text" id="userPwd" maxlength="255" autocomplete="off">
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fontRed">*</span> 状态：</td>
    <td>
      <input name="status" type="radio" id="status" value="1" checked>启用
      <input name="status" type="radio" id="status2" value="0">禁用
    </td>
  </tr>
</table>
</div>
<br>
<input name="btnSubmit" type="submit" style="margin-left:500px;" class="inputButton_in" size="20" id="btnSubmit" value=" 提交 ">
</form>
</body>
</html>