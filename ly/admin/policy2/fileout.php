<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
   require_once('_inc.php');
 
  if (isset($_POST['symbol']))
  {
    if ($_POST['symbol']=="add")
	{
	  $function=$_POST['function_text'];
	  $key=$_POST['key_text'];
	  $address=$_POST['address_text'];
      $arr = "insert into file_transter(`key`,`address`,`function`) values('$key','$address','$function')";
	//echo $arr;
	  $db->exec($arr);
     }	
    else if($_POST['symbol']=="del")
     {
	   $key=$_POST['deltext'];
	   $arr = "delete from file_transter where `key`='$key';";
	  // echo $arr;
	   $db->exec($arr);
	 }	
	  else if($_POST['symbol']=="change")
     { 
       $blockchange = $_POST['changes'];
       echo $blockchange;
	   
	   $keycheck=explode(',',$blockchange);
       foreach ($keycheck as $var)
         {
		   $keytemp=explode(':',$var);
		   $SQL = "update file_transter set block=$keytemp[1] where `key`='$keytemp[0]'";
		   //echo $SQL;
		   $db->query2($SQL); 
		 
		 }  	
       	 
	 }	
  }
  
 
   
   $arr = "select * from file_transter";
   $arr = $db->fetchRows($arr);
    
   
?>


<html> 
	<head> 
		<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
		
		
		<title>防止文件外发</title> 
		<style type="text/css" title="currentStyle"> 
			@import "./css/demo_page.css";
			@import "./css/demo_table.css";
		</style> 
		<script type="text/javascript" language="javascript" src="./js/jquery.js"></script> 
		<script type="text/javascript" language="javascript" src="./js/jquery.dataTables.js"></script> 
		
		
		
		<script type="text/javascript" charset="utf-8"> 
			(function($) {
			
			$.fn.dataTableExt.oApi.fnGetColumnData = function ( oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty ) {
				
				// check that we have a column id
				if ( typeof iColumn == "undefined" ) return new Array();
				
				// by default we only wany unique data
				if ( typeof bUnique == "undefined" ) bUnique = true;
				
				// by default we do want to only look at filtered data
				if ( typeof bFiltered == "undefined" ) bFiltered = true;
				
				// by default we do not wany to include empty values
				if ( typeof bIgnoreEmpty == "undefined" ) bIgnoreEmpty = true;
				
				// list of rows which we're going to loop through
				var aiRows;
				
				// use only filtered rows
				if (bFiltered == true) aiRows = oSettings.aiDisplay; 
				// use all rows
				else aiRows = oSettings.aiDisplayMaster; // all row numbers
			     
				// set up data array	
				var asResultData = new Array();
				
				for (var i=0,c=aiRows.length; i<c; i++) {
					iRow = aiRows[i];
					//alert(iRow);
					var aData = this.fnGetData(iRow);
					
					var sValue = aData[iColumn];
					
					// ignore empty values?
					if (bIgnoreEmpty == true && sValue.length == 0) continue;
			
					// ignore unique values?
					else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1) continue;
					
					// else push the value onto the result data array
					else asResultData.push(sValue);
					
				}
				
				return asResultData;
			    	
				
			}}(jQuery));
			
		
			function fnCreateSelect( aData )
			{   
			    
				var r='<select><option value=""></option>', i, iLen=aData.length;
				for ( i=0 ; i<iLen ; i++ )
				{
					r += '<option value="'+aData[i]+'">'+aData[i]+'</option>';
				}
				return r+'</select>';
			}
			
			
			$(document).ready(function() {
			
			  
			   /* Add a click handler to the rows - this could be used as a callback */
				$("#example tbody").click(function(event) {
					$(oTable.fnSettings().aoData).each(function (){
						$(this.nTr).removeClass('row_selected');
					});
					$(event.target.parentNode).addClass('row_selected');
				});
				
				/* Add a click handler for the delete row */
				$('#delete').click( function() {
					var anSelected = fnGetSelected( oTable );
					var delbefore = new Array();
					var delafter = new Array();
					delbefore=oTable.fnGetColumnData(1);
					//alert(delbefore);
					oTable.fnDeleteRow(anSelected[0]);
					delafter=oTable.fnGetColumnData(1);
					//alert(anSelected[0].tagname));
					//alert(delafter);
					for(var i=0;i<delbefore.length;i++)
					 {
					   if(delbefore[i] != delafter[i])
					    {
                		  var  deltext=delbefore[i];
                          break;						 
					    }
					   
					   
					 }
					 
					$('#symbol').val("del"); 
                    $('#deltext').val(deltext);       
				  	$('#form').submit();	   
				} );
				
				$('#change').click( function() {
					
					 $('#symbol').val("change");
					 var aTrs = oTable.fnGetNodes();
					 var aData = oTable.fnGetData();
				     var txt=""
					 //alert(aData);
					 
				     for ( var i=0 ; i<aData.length; i++ )
				     {  
					    var idd="#"+aData[i][1];
						 //alert(idd);
						 
						if( typeof($(idd).attr("checked")) == 'undefined')
						 continue;
					    
					   if($(idd).attr("checked")==true)
		              
			             if (i == aData.length-1)
						  txt = txt+aData[i][1]+":0";
						 else 
						  txt = txt+aData[i][1]+":0,";
		               
					   else
                        
						 if (i == aTrs.length-1)
						  txt = txt+aData[i][1]+":1";
						  else
						 txt = txt+aData[i][1]+":1,";		
						 
					   
				     } 
					  //alert(txt);   
					$('#changes').val(txt);
					$('#form').submit();	   
				} );
				
				/* Add a click handler for the delete row */
				$('#add').click( function() {
				   	var function_text = $("#function_text").val();
					if (function_text == "")
                     {  					
					  alert("类型不能为空");
					  return false;
					  
					 };
	                var key_text = $("#key_text").val();
					if (key_text == "")
                     {  					
					  alert("名字不能为空");
					  return false;
					  
					 };
	                var address_text = $("#address_text").val();
					if (address_text == "")
                     {  					
					  alert("地址不能为空");
					  return false;
					  
					 };
	                 var key_log = "<input type = 'checkbox' checked='checked'" + "' />";
	                 var tempkey = new Array();
					 var tempaddress = new Array();
					 tempkey=oTable.fnGetColumnData(1);
					 tempaddress=oTable.fnGetColumnData(2);
					 
					 for(var i=0;i<tempaddress.length;i++)
                       {  
                         if(tempaddress[i] == address_text)
                          {
						    alert("此记录已经存在");
							return false;
						  }						 
					   
					   };
                       
                        for(var i=0;i<tempkey.length;i++)
                       {  
                         if(tempkey[i] == key_text)
                          {
						    alert("此记录已经存在");
							return false;
						  }						 
					   
					   };					 
	 					   
	 
	                 $('#example').dataTable().fnAddData( [
	                    	function_text,
	                        key_text,
		                    address_text,
	                    	key_log] );
	                      // alert("添加成功");
						   $('#symbol').val("add"); 
                           $('#form').submit();	   
						   
				    } );
			   
				/* Initialise the DataTable */
				var oTable = $('#example').dataTable( {
					"oLanguage": {
						"sSearch": "全文搜索:"
					}
				} );
			   
				/* Add a select menu for each TH element in the table footer */
				$("#test").each( function ( i ) {
				      
					this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(i) );
					$('select', this).change( function () {
						oTable.fnFilter( $(this).val(), i );
					} );
				} );
			} );
		</script>
		
	<script>
  
   
/* Get the rows which are currently selected */
			function fnGetSelected( oTableLocal )
			{
				var aReturn = new Array();
				var aTrs = oTableLocal.fnGetNodes();
				
				for ( var i=0 ; i<aTrs.length ; i++ )
				{
					if ( $(aTrs[i]).hasClass('row_selected') )
					{
						aReturn.push( aTrs[i] );
					}
				}
				return aReturn;
			}

 </script>	
		
		
		
		
	</head> 
	<body id="dt_example"> 
		<div id="container"> 
			<div class="full_width big"> 
				<i>防止文件外发</i>  
			</div> 
			
			
			<h1>防止文件外发 </h1><div id="test" ></div> 
			
			<div id="demo"> 
			<form id="form" action="fileout.php" method="post"   > 
					<div style="text-align:right; padding-bottom:1em;"> 
						
					</div> 
             <table cellpadding="0" cellspacing="0" border="0" class="display" id="example"> 
	           <thead> 
		        <tr> 
			     <th>类型</th> 
			     <th>名字</th>
				 <th>地址</th>
				 <th>阻挡</th>
			   
		       </tr> 
	         </thead> 
	        <tbody> 
			 <?php
	     $odd="true";
		
	     foreach($arr as $value){
	  ?>
		<tr class="<?php if($odd=="true") { echo "gradeA"; $odd="false"; }else { echo "gradeB"; $odd="true";} ?> ">
	        <td class="left"><?php echo  trim($value['function'])  ?></td>
			<td class="center"><?php echo  trim($value['key']) ?></td>
			<td class="center"><?php echo  trim($value['address']) ?></td>
		    <td class="center"><input type="checkbox" id="<?php echo $value['key'] ?>"  <?php if($value['block']==0) echo "checked" ;else echo ""  ?>  ></td> 
         </tr>
		<?php
		
          }		
		?>
       </tbody> 
	         <tfoot> 
		      <tr> 
			     <th><input type="text" id="function_text" name="function_text" ></th>
				 <th><input type="text" name="key_text" id="key_text" name="function_text" ></th>
				 <th><input type="text" id="address_text" name="address_text"> </th>
				 <th><a href="javascript:void(0);" id="add"  >添加</a></th>
			  </tr> 
	          </tfoot> 
             </table> 
			 <p><a href="javascript:void(0)" id="change">修改</a></p> 
			 <p><a href="javascript:void(0)" id="delete">删除所选的行</a></p> 
			  <p><input type="hidden" name="symbol" id="symbol" ></p> 
			  <p><input type="hidden" name="deltext" id="deltext" ></p> 
			  <p><input type="hidden" name="changes" id="changes" ></p> 
		</form> 
		   </div> 
			<div class="spacer"></div> 
	        <div id="footer" style="text-align:center;"> 
				<span style="font-size:10px;">DataTables &copy; Allan Jardine 2008-2010</span> 
			</div> 
		</div> 
	</body> 
</html>