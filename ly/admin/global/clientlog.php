<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "  http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<?php
   require_once('_inc.php');
   if(isset($_POST['radio']))
   { 
    switch($_POST['radio']) 
    {
	   	case "1":
	    		$SQL="update globalpara set systemmode=0";
	    		$result = $db->exec($SQL,"客户端登陆方式");	     		
	    		break;
		case "2":
				$SQL="update globalpara set systemmode=1";
				$result = $db->exec($SQL,"客户端登陆方式");
			   break;
		default:
			break;
    }
    if(isset($_POST['check']))
    {
    	$SQL="update globalpara set isremindpage=1";
		$result = $db->exec($SQL,"提醒阻挡页面设置");
    }
    else
    {
    	$SQL="update globalpara set isremindpage=0";
		$result = $db->exec($SQL,"提醒阻挡页面设置");
    }
    system($gCmd ." 4");
  }
   
 
  $sql_log="select systemmode,isremindpage from globalpara;";
  $result = $db->query2($sql_log,"全局设置",true);
  
  $loginmode = $result['0']['systemmode'];
  $isremind = $result['0']['isremindpage'];
   
   
   
?>


<html>
	<head>
	<title>报表条件</title>    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="../common/main.css" rel="stylesheet" type="text/css"/>
	<link href="./css/tab.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="screen" href="../themes/redmond/jquery-ui-1.7.1.custom.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.jqgrid.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.multiselect.css" />
</head>
<style>.a3{width:30;border:0;text-align:center}</style>  
<body> 
 
	<br>
	<form name=queryinput action="clientlog.php" method="post" >
	  <h1>客户端登陆方式</h1>
		<div>
		 <table border="0" cellpadding="0" cellspacing="0">
         <tr width='20px'>
		   <td><input type="radio" name="radio" id="mode"  value="1" <?php if($loginmode=='0') echo 'checked';?>></td>
		   <td align="left">IP方式</td>
		 </tr>
        <tr width='20px'>
		 <td><input type="radio" name="radio" id="userid"  value="2" <?php if($loginmode=='1') echo 'checked';?>></td>
		 <td align="left">帐号方式</td>
		</tr>
		</table>
      </div>
      <h1>提示阻挡页面</h1>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr width='20px'>
		   <td><input type="checkbox" name="check" id="isremind"  value="1" <?php if($isremind=='1') echo 'checked';?>></td>
		   <td align="left">当请求的页面被阻挡时，是否向用户提示阻挡页面</td>
		</tr>
      </table>
	    <br/>
		<INPUT class = "inputButton_in" type="submit" name="提交" value="提交" id="submit" size="20" >	
	</form>
 
	

</body>
</html>
