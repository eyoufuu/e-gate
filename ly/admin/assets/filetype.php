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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文件类型检测</title>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type="text/javascript" src="../js/example.js"></script>
<style type="text/css" title="currentStyle">
	@import "../css/demo_page.css";
	@import "../css/demo_table.css";
</style>

<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
		$('#file_base').dataTable( {
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": false,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": false } );
  
});
</script>

</head>

<body>
   <h1>外发文件类型检测</h1>
   <p>这个模块将为您尽力检测上传的文件中是否有某种类型的文件，请你在需要检测的文件类型后面打上钩。</p>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="protocol_base">
	<thead><tr><th align='left'>名称</th><th>代码</th><th>阻挡</th><th align = 'left'>描述</th></tr></thead>
	<tbody>
    <?php
	   $SQL = "select * from file_transter where function = '文件'";
	   
	    $res_cat = $db->query2($SQL,"M",false);
    	foreach($res_cat as $row)
		{
		    $block = $row['block'];
			$value = "";
			switch($block)
			{
			   case 0://阻挡
			      $value = "阻挡";
			      break;
			   case 1://放行
			      $value = "放行";
			      break;
			   case 2://特例
			      $value = "特例放行";
			      break;
			}
	?>
	   <tr><td><?php echo $row['key']; ?></td><td><?php echo $row['address']; ?></td>
	   <td><select id="select_value" value =<?php echo $value ?>>
	   <option>阻挡</option>
	   <option>放行</option>
	   <option>特例放行</option>
	   </select>
	   </td>
	   <td>--</td>
	   </tr>
	<?php
	    }
     ?> 	  
	</tbody>
	</table>
 

  
</body>
</html>
