<?php


 require_once('_inc.php');
?>


<?php
  
	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
   $count=50;   
 	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	
	if($start <0) $start = 0;
	
  	 $responce->page = $page;
	 $responce->total = $total_pages;
	 $responce->records = $count;
    $sql_key="select * from filecat;";
    $filetype=$db->fetchRows($sql_key);
    $i=0;
    foreach ($filetype as $row)
                    {
                                  

                     $responce->rows[$i]['cell']=array($keyword,$block,$log);                
                     $i++;
                     };       
            echo json_encode($responce);
?> 




