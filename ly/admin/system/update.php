<?php 
  require_once('_inc.php');
  if(isset($_POST['symbol']))
  {
  		echo "dbfile 正在升级";
  }
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">	
	<link href="../common/main.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../common/common.js"></script>
	<script>
	function onfun()
	{
		update();
	}
	</script>
</head>
<body>
<h1>系统升级</h1>
<form name="form1" method="post" action="update.php">	
	<input name="symbol" type="hidden" value="symbol" >
	<input class="inputButton" type="submit" value="升级"/>
</form>
</body>
</html>