<?php 
require_once ('_inc.php');
 
 	$name = "";
 	$web = "";
 	$addr= "";
 	$phone= "";
 	$detail= "";
 	$logo= "";
	if(isset($_POST['companyname']))
		$name = $_POST['companyname'];
	if(isset($_POST['companyweb']))
		$web = $_POST['companyweb'];
	if(isset($_POST['companyaddr']))
		$addr = $_POST['companyaddr'];
	if(isset($_POST['companyphone']))
		$phone = $_POST['companyphone'];
	if(isset($_POST['companydetail']))
		$detail =$_POST['companydetail'];
//	if(isset($_FILES['companylogo']['type']))
//	{ 
		$error = ""; //上传文件出错信息
		$msg = "";
		$fileElementName = 'companylogo';
	    $allowType = array(".jpg",".gif",".png"); //允许上传的文件类型
	    $num      = strrpos($_FILES['companylogo']['name'] ,'.');  
		$fileSuffixName    = substr($_FILES['companylogo']['name'],$num,8);//此数可变  
		$fileSuffixName    = strtolower($fileSuffixName); //确定上传文件的类型
    
		$upFilePath             = './pic/logo'; //最终存放路径

		if(!empty($_FILES[$fileElementName]['error']))
		{
		   switch($_FILES[$fileElementName]['error'])
		   {
		
		    case '1':
		     $error = '传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
		     break;
		    case '2':
		     $error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
		     break;
		    case '3':
		     $error = '文件只有部分被上传';
		     break;
		    case '4':
		     $error = '没有文件被上传';
		     system("rm -rf ./pic/logo");
		     break;
		
		    case '6':
		     $error = '找不到临时文件夹';
		     break;
		    case '7':
		     $error = '文件写入失败';
		     break;
		    default:
		     $error = '未知错误';
		   }
		}
		elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
		{
		   $error = '没有上传文件.';
		}
		else if(!in_array($fileSuffixName,$allowType))
		{
			echo "####";
		   $error = '不允许上传的文件类型'; 
		}
		else
		{
		    echo "bbb".$_FILES[$fileElementName]['tmp_name']."@@@".$upFilePath;
		   $ok=move_uploaded_file($_FILES[$fileElementName]['tmp_name'],$upFilePath);
		   if($ok === FALSE)
		   {
		    $error = '上传失败';
		    echo $_FILES[$fileElementName]['error'];
	   		}
		}		
		
 $format = "insert into companyinfo  values('%s','%s','%s','%s','%s')";
 $sql = sprintf($format,$name,$web,$addr,$phone,$detail);
  echo $sql;
 $arr = $db->exec("delete from companyinfo");
 $arr = $db->exec($sql); 
?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head></html>