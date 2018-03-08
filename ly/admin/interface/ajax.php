<?php
 
$x = $_GET['value']; //+ ' is very good';

$data = array( 
    array('name' => 'Η®²¨',  'age' => '28'), 
    array('name' => 'ΜπΜπ',  'age' => '27') 
    #array('name' => mb_convert_encoding('²βΚΤ','UTF-8', 'GBK'), 'age' => '27') 
); 
echo json_encode($data);


/*
if($x=="1")
   echo "this is a test 1";
else if($x=="2")
   echo "this is a test 2";
else
  echo $x;*/
?>