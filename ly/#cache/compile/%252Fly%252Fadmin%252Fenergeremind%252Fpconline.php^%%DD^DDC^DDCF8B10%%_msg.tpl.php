<?php /* Smarty version 2.6.19, created on 2010-08-04 14:39:17
         compiled from _msg.tpl */ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
<script type="text/javascript" src="../../common/fckeditor/fckeditor.js"></script>
</head>

<body>
<h1><?php if ($this->_tpl_vars['title']): ?><?php echo $this->_tpl_vars['title']; ?>
<?php else: ?>操作结果<?php endif; ?></h1>
<p class="fontBold"><?php echo $this->_tpl_vars['msg']; ?>
</p>
<p class="fontBold"><?php if ($this->_tpl_vars['backUrl']): ?>
  <input name="btnBack" type="button" class="inputButton" id="btnBack" value=" 返回 " onClick="location='<?php echo $this->_tpl_vars['backUrl']; ?>
';">
<?php endif; ?></p>
</body>
</html>