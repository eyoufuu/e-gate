<?php
/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   require_once('_inc.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网卡列表</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<script language="javascript" type="text/javascript" src="./ipcheck.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../themes/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.multiselect.css" />
<style type="text/css">
body{}
ul#navlist {
    display: inline;
    list-style: none;
}  
ul#navlist li {
    float: left;
    width: 40px;
    height:40px;
} 
ul#navlist li a {
    width: 30px;
    height:30px;
    padding:5px;
    display:block;
    color:#fff;
    text-decoration: none;
    font-size:7pt;
    font-family:arial;
    line-height:50px;
    text-align:center;
    border-right:1px solid #fff;
    border-left:1px solid #fff;
    border-top: 5px solid #fff;
    border-bottom: 5px solid #fff;
    background: #80C9FF;
} 
ul#navlist li a:hover {
    border-top: 5px solid #004a80;
    border-bottom: 5px solid #004a80;
    background:#004a80;
    font-size:9pt;
    font-weight:bold;
}
</style>

<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
<script src="../js/jquery.layout.js" type="text/javascript"></script>
<script src="../js/i18n/grid.locale-cn.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>
 <script>
   $(document).ready(function() {
    $("#dialog").dialog();
  });
  </script>

</head>
<body>
<ul id="navlist">
    <li><a href="#">设置</a></li>
    <li><a href="#">保存</a></li>
   
</ul>
</body>
</html>
