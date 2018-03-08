<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php

  require_once('_inc.php');
  
 // $SQL2="select * from file_transter ";
 // $result2 = $db->fetchRows( $SQL2 );
  //echo "ok";
  $sql_fileout="select * from globalpara";
  $isfile=$db->query2one($sql_fileout); 
  
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="../themes/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.multiselect.css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="../js/jquery.layout.js" type="text/javascript"></script>
<script src="../js/i18n/grid.locale-cn.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>



<script type="text/javascript">
 jQuery(document).ready(function(){   
    	var sym="6";
		
      $("#submit").click(function(){
	      
		  if($("#fileout").attr("checked")==true)
              symbolisfileout = "1";
          else
	         symbolisfileout = "0";	
			 
		
		
        $.post("filetype_save.php",{isfileout:symbolisfileout,sym:sym});	 		
       
			  
	  
	  });

    
		  
	
   
});
</script>
</head>
<body>

<h1>FTP外发检测</h1>
<table>
   <tr>
	  <td align ='left'><input  id="fileout" type="checkbox" <?php if($isfile['isftpopen'] == 1) echo "checked"; ?>  /></td>
	  <td>启用</td>
   </tr>
		
</table>
</br>
<INPUT class = "inputButton_in" type="submit" name="提交" value="提交" id="submit" size="20" >	
 
</body>
</html>