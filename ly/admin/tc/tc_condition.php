<?php

  require_once('_inc.php');
  if(isset($_POST['upaccount']))
  {
   $upa = $_POST['upaccount'];
   $downa = $_POST['downaccount'];
   $upper = $_POST['select1'];
   $downper = $_POST['select2'];
   $switch = $_POST['lock'];   
   $SQL = "update globalpara set isqosopen=$switch,upbw=$upa*8,downbw=$downa*8,percentupbw=100-$upper, percentdownbw=100-$downper";
 //echo $SQL; 
   $db->exec($SQL,"流控条件");
   if($switch==0)   
   {
   		system("/usr/bin/sudo /home/lytc");
   }
} 
  
  
   $sql_pro="select type,name from procat where proid=-1;";
   $result=$db->fetchRows($sql_pro);
   $sql_chid="select id from channel order by id";
   $chid=$db->query2one($sql_chid);
   $firstid=$chid['id'];
   
   $sql_updown="select * from globalpara";
   $updown=$db->query2one($sql_updown);
   $upflow=$updown['upbw']/8;
   $downflow=$updown['downbw']/8;
   $percentupbw=100-$updown['percentupbw'];
   $percentdownbw=100-$updown['percentdownbw'];
   $qos = $updown['isqosopen'];
    //echo $percentupbw;
	//echo $percentdownbw;


 


	
?>

<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>
    </title>
	<link href="../common/main.css" rel="stylesheet" type="text/css"/>
  </head>
  <body>
  <h1>总带宽</h1>
  
<form id="conditionform" action="tc_condition.php" method="post">

  <table>
		<tr>
		  <td> 上行总带宽：</td> 
		  <td align ='left' ><input name="upaccount" type="text" value="<?php echo $upflow; ?>" /></td>
		  <td align ='left' >KB</td>
		</tr>
	    <tr>
		  <td> 下行总带宽：</td>
		  <td align ='left'><input  name="downaccount" type="text" value="<?php echo $downflow; ?>" /></td>
		  <td align ='left' >KB</td>
		</tr>
	    
</table>	

 <h1>设置默认通道</h1>	
  <table>      		
		<tr>
		  <td> 限制到上行总带宽的：</td>
		  <td align ='left'>  <select size="1" name="select1"  >
                        <option value="5"  <?php if( $percentupbw ==  5) echo  "selected= \"selected\" "?>>5%</option>
                        <option value="10" <?php if( $percentupbw == 10) echo "selected=\"selected\" "?>>10%</option>
                        <option value="20" <?php if( $percentupbw == 20) echo "selected=\"selected\" "?>>20%</option>
						<option value="30" <?php if( $percentupbw == 30) echo "selected=\"selected\" "?>>30%</option>
						<option value="40" <?php if( $percentupbw == 40) echo "selected=\"selected\" "?>>40%</option>
						<option value="50" <?php if( $percentupbw == 50) echo "selected=\"selected\" "?>>50%</option>
						<option value="60" <?php if( $percentupbw == 60) echo "selected=\"selected\" "?>>60%</option>
						
                </select></td>
		</tr>
        <tr>
		  <td> 限制到下行总带宽的：</td>
		  <td align ='left'><select size="1" name="select2" >
                        <option value="5" <?php if( $percentdownbw ==  5) echo  "selected= \"selected\" "?>>5%</option>
                        <option value="10" <?php if( $percentdownbw == 10) echo "selected=\"selected\" "?>>10%</option>
                        <option value="20" <?php if( $percentdownbw == 20) echo "selected=\"selected\" "?>>20%</option>
						<option value="30" <?php if( $percentdownbw == 30) echo "selected=\"selected\" "?>>30%</option>
						<option value="40" <?php if( $percentdownbw == 40) echo "selected=\"selected\" "?>>40%</option>
						<option value="50" <?php if( $percentdownbw == 50) echo "selected=\"selected\" "?>>50%</option>
						<option value="60" <?php if( $percentdownbw == 60) echo "selected=\"selected\" "?>>60%</option>
                </select></td>
		</tr>
		<tr>
		  <td>注意：没有被分配的ip或协议使用默认通道</td>
		</tr>
		
		 
</table> 
 <h1>流控开关</h1>	
 	<table>
		<tr>
		    <td width='20px'><input type="radio" name="lock" id="userid"  value="1" <?php if($qos == 1 ) {echo 'checked';}  ?> /></td><td align = 'left' > 简单流控</td>
		    
		</tr>
		 <tr>
		    <td width='20px'><input type="radio" name="lock" id="accid"  value="2"  <?php if($qos == 2 ) {echo 'checked';} ?> /></td><td align = 'left' > 高级流控</td>
		    
		 </tr>
		<tr>
		    <td width ='20px'><input type="radio" name="lock" id="ipid" value="0" <?php if($qos == 0 ) {echo 'checked';} ?> /></td><td align = 'left' > 关闭流控</td>
		    
		</tr>
		</table> 
 
<br>
<INPUT class = "inputButton_in" type="submit" name="提交" value="提交" id="submit" size="20" />	
</form>

 </body>
</html>