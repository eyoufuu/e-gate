{--mc_getcontent type="company" id="$infoId" varname="info"--}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
<script type="text/javascript" src="../../common/fckeditor/fckeditor.js"></script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">编辑企业信息</div>
</div>
<br>
<form action="" method="post" name="form1" onSubmit="return notEmpty(categoryId, '请选择分类。')&&notEmpty(subject, '请输入标题。')">
  <table border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td align="right"><span class="fontRed">* </span>网站语言：</td>
      <td>
        <select name="lang" id="lang">
                      
      {--section name=loop loop=$arrLangs--}           
            
          <option value="{--$arrLangs[loop].name--}" {--if $info.f_lang==$arrLangs[loop].name--}selected{--/if--}>{--$arrLangs[loop].description--}</option>
                      
      {--/section--}        
          
        </select>
      </td>
    </tr>
    <tr>
      <td align="right"><span class="fontRed">*</span> 信息标题：</td>
      <td>
        <input name="subject" type="text" id="subject" value="{--$info.f_subject--}" size="70" maxlength="255">
      </td>
    </tr>
  </table>
  <textarea name="content" id="content" style="width:100%; height:300px">{--$info.f_content--}</textarea>
<script type="text/javascript">
<!--
function htmlEdit(container)
{
    var sBasePath = '../../common/fckeditor/' ;
    var oFCKeditor = new FCKeditor(container) ;
    oFCKeditor.BasePath = sBasePath ;
    oFCKeditor.Config['FullPage'] = true ;
    oFCKeditor.Height = "300";
    oFCKeditor.Value = '' ;
    oFCKeditor.ReplaceTextarea();
}

htmlEdit('content');
//-->
</script>
(以下为选填内容)<br>
<div class="bgFleet paddingAll">
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>是否自动列出： </td>
    <td>
      <input type="radio" name="list" id="list" value="1" {--if $info.f_list==1--}checked{--/if--}>是
      <input type="radio" name="list" id="list" value="0" {--if $info.f_list==0--}checked{--/if--}>否 
      (是否自动在前台栏目列表中列出)
    </td>
  </tr>
  <tr>
    <td>是否锁定： </td>
    <td>
      <input type="radio" name="lock" id="lock" value="1" {--if $info.f_lock==1--}checked{--/if--}>锁定
      <input type="radio" name="lock" id="lock" value="0" {--if $info.f_lock==0--}checked{--/if--}>不锁定
      (若锁定，则不会在后台列表中显示“删除”按钮，以免被误删)
    </td>
  </tr>
  <tr>
    <td>显示顺序：</td>
    <td>
      <input name="order" type="text" id="order" value="{--$info.f_order--}" maxlength="5"> 
      (越小越靠前)
    </td>
  </tr>
  <tr>
    <td>关键词： </td>
    <td>
      <input name="keywords" type="text" id="keywords" size="50" maxlength="255" value="{--$info.f_keywords--}">
(多个关键词之间请用半角逗号分隔) </td>
  </tr>
  <tr>
    <td>内容摘要： </td>
    <td>
      <input name="description" type="text" id="description" value="{--$info.f_description--}" size="70" maxlength="255">
    </td>
  </tr>
</table>
</div>
<br>
<input name="page" type="hidden" value="{--$page--}"> 
<input name="infoId" type="hidden"value="{--$info.f_id--}">
<input name="picName" type="hidden" value="{--$info.f_pic--}"> 
<input name="btnSubmit" type="submit" class="inputButton" id="btnSubmit" value=" 提交 ">
</form>
</body>
</html>