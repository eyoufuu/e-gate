<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">网站设置</div>
</div>
<br>
<form action="" method="post" enctype="multipart/form-data" name="form1" onSubmit="return notEmpty(siteName, '请输入网站名称。')&&notEmpty(urlPrefix, '请输入网址前缀。')">
  <table border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td align="right"><span class="fontRed">*</span> 网站名称（简体中文）：</td>
      <td>
        <input name="siteName" type="text" id="siteName" value="{--$siteName--}" size="30" maxlength="255">
      </td>
    </tr>
    <tr>
      <td align="right">网站名称（ENGLISH）：</td>
      <td>
        <input name="siteNameEn" type="text" id="siteNameEn" value="{--$siteNameEn--}" size="30" maxlength="255">
      </td>
    </tr>
    <tr>
      <td align="right">网站名称（其它语言）：</td>
      <td>
        <input name="siteNameOther" type="text" id="siteNameOther" value="{--$siteNameOther--}" size="30" maxlength="255">
      </td>
    </tr>
    <tr>
      <td align="right">网站域名：</td>
      <td>
        <input name="siteDomain" type="text" id="siteDomain" value="{--$siteDomain--}" maxlength="255">
      </td>
    </tr>
    <tr>
      <td align="right">关键词：</td>
      <td>
        <input name="siteKeywords" type="text" id="siteKeywords" value="{--$siteKeywords--}" size="70" maxlength="255">
      </td>
    </tr>
    <tr>
      <td align="right"><span class="fontRed"> </span>描述：</td>
      <td>
        <input name="siteDescription" type="text" id="siteDescription" value="{--$siteDescription--}" size="70" maxlength="255">
      </td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right"><span class="fontRed">* </span>网址前缀：</td>
      <td>
        <input name="urlPrefix" type="text" id="urlPrefix" value="{--$urlPrefix--}" maxlength="255">
(如果启用URLRewrite，就填“page-”，否则填“./?”) </td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
<br>
<input name="btnSubmit" type="submit" class="inputButton" id="btnSubmit" value=" 提交 ">
</form>
</body>
</html>