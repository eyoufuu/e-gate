<?php /* Smarty version 2.6.19, created on 2010-07-28 09:02:25
         compiled from admin_groups.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'mc_getcategories', 'admin_groups.tpl', 26, false),)), $this); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>

	<style type="text/css" title="currentStyle">
			@import "../js/js_new/demo_page.css";
			@import "../js/js_new/demo_table.css";
		</style>


</head>

<body>
  <h1>管理员组列表</h1>
<!-- <table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="50" class="bgFleet borderBottom">编号</td>
    <td class="bgFleet borderBottom">名称</td>
    <td align="center" class="bgFleet borderBottom">管理员列表</td>
    <td align="center" class="bgFleet borderBottom">添加管理员</td>
    <td align="center" class="bgFleet borderBottom">操作</td>
  </tr>
 <?php echo smarty_function_mc_getcategories(array('type' => 'admin','items' => "*",'varname' => 'arr'), $this);?>

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
 <?php $this->assign('bgClass', "class='bgFleet'"); ?>
 <?php else: ?>
 <?php $this->assign('bgClass', ""); ?>
 <?php endif; ?>
  <tr>
    <td <?php echo $this->_tpl_vars['bgClass']; ?>
><?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_id']; ?>
</td>
    <td <?php echo $this->_tpl_vars['bgClass']; ?>
><?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_name']; ?>
</td>
    <td align="center" <?php echo $this->_tpl_vars['bgClass']; ?>
><a href="admin_admins.php?gId=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_id']; ?>
">管理员列表</a></td>
    <td align="center" <?php echo $this->_tpl_vars['bgClass']; ?>
><a href="admin_admin_add.php?gId=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_id']; ?>
">添加管理员</a></td>
    <td align="center" <?php echo $this->_tpl_vars['bgClass']; ?>
><a href="admin_group_modify.php?gId=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_id']; ?>
">编辑</a> | <a href="admin_group_delete.php?gId=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_id']; ?>
" onClick="return confirm('确实要删除这个管理员组吗？');">删除</a></td>
  </tr>
  <?php endfor; endif; ?>
</table>
 -->
 <table cellpadding="0" cellspacing="0" border="0"  class="display">
 <thead>
 <tr>
    <th align="center">编号</td>
    <th align="center">名称</td>
    <th align="center">管理员列表</td>
    <th align="center">添加管理员</td>
    <th align="center">操作</td>
  </tr>
  </thead>
  <tbody>
 <?php echo smarty_function_mc_getcategories(array('type' => 'admin','items' => "*",'varname' => 'arr'), $this);?>

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
    <td align="center" ><?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_id']; ?>
</td>
    <td align="center" ><?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_name']; ?>
</td>
    <td align="center" ><a href="admin_admins.php?gId=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_id']; ?>
">管理员列表</a></td>
    <td align="center" ><a href="admin_admin_add.php?gId=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_id']; ?>
">添加管理员</a></td>
    <td align="center" ><a href="admin_group_modify.php?gId=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_id']; ?>
">编辑</a> | <a href="admin_group_delete.php?gId=<?php echo $this->_tpl_vars['arr'][$this->_sections['loop']['index']]['f_id']; ?>
" onClick="return confirm('确实要删除这个管理员组吗？');">删除</a></td>
  </tr>
  <?php endfor; endif; ?>
  </tbody>
  <tfoot>
  <tr>
    <th align="center">&nbsp;</td>
    <th align="center">&nbsp;</td>
    <th align="center">&nbsp;</td>
    <th align="center">&nbsp;</td>
    <th align="center">&nbsp;</td>
  </tr>
  </tfoot>
</table>
</body>
</html>