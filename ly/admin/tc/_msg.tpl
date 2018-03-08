<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
<script type="text/javascript" src="../../common/fckeditor/fckeditor.js"></script>
</head>

<body>
<h1>{--if $title--}{--$title--}{--else--}操作结果{--/if--}</h1>
<p class="fontBold">{--$msg--}</p>
<p class="fontBold">{--if $backUrl--}
  <input name="btnBack" type="button" class="inputButton" id="btnBack" value=" 返回 " onClick="location='{--$backUrl--}';">
{--/if--}</p>
</body>
</html>
