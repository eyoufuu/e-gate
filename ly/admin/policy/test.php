<html>
<head>
<?php 

 require_once('_inc.php');

?>

</head>
<body>
<?php
  $SQL = "select keywordinfo from policy where policyid=0;";
  $keyword =$db->fetchRow($SQL);
 echo $keyword[keywordinfo];
?>


</body>

</html>