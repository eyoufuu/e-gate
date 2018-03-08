<?php

      require_once('_inc.php');
	  session_start();
     if(!isset($_SESSION["date1"]))
   		return;
     $ds = $_SESSION["date1"];
     $de = $_SESSION["date2"];
     $symbol= $_SESSION["symbol"]; 
   
     list($year,$month,$day)=explode('-',$ds);
      $date_ym = $year.$month;
      $tablename=$date_ym."web";
	
    $dates = strtotime($ds);
    $datee = strtotime($de);
   //if($dates==$datee)
     $datee=$datee+86400; 
   $arr = "select systemmode from globalpara";
   $arr = $db->fetchRow($arr);
   $systemmode= $arr["systemmode"];
   if ($systemmode == 0)
    $mode='用户';
   else
    $mode='帐号';  
    
   
    switch($symbol)
    {
    	case "1":
    	    if ($systemmode == 0)
            {
			 // $SQL="select u.id,u.logtime,w.name,u.ip_inner,u.host,u.note as keyword,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,host,note ,pass from $tablename where (get_type=3) and (logtime between  $start and $datee ) limit $start,$limit) as u  left join (select name,bindip from useraccount) as w on u.ip_inner=w.bindip"; 
              $SQL = " call filetypeip_all('$tablename',$dates,$datee)";
			
    		}
          else
		     { //$SQL="select  u.id ,u.logtime,w.account as name,u.ip_inner,u.host,u.note as keyword ,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,account_id,ip_inner,host,note ,pass from  $tablename  where (get_type=3) and (logtime between $start and $datee) limit $start,$limit) as u left join (select id,account from useraccount) as w on u.account_id=w.id ";
              $SQL = " call filetypeaccount_all('$tablename',$dates,$datee)";
    		  } 
    		break;
    	case "2":
    	      $username =  $_SESSION["username"];
    		if ($systemmode == 0)
              {
			    $arr = "select bindip from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["bindip"];
			   //$SQL="select u.id,u.logtime,w.name,u.ip_inner,u.host,u.note as keyword,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,account_id,host,note ,pass from $tablename where (ip_inner=$accountid) and (get_type=3) and (logtime between $start and $datee)  limit  $start,$limit) as u left join (select name,bindip from useraccount) as w on u.ip_inner=w.bindip";
			    $SQL = " call filetypeip_id('$tablename',$dates,$datee,$accountid)";
              }   		
          else
              {
			    $arr = "select id from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["id"];
			 //$SQL="select u.id,u.logtime,w.account as name,u.ip_inner,u.host,u.note as keyword,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,account_id,host,note,pass from $tablename where (account_id=$accountid) and (get_type=3) and (logtime between $start and $datee)  limit  $start,$limit) as u  left join (select id,account from useraccount) as w on u.account_id=w.id ";
			   $SQL = " call filetypeaccount_id('$tablename',$dates,$datee,$accountid)";
    		  }
   	   break;
    	case "3":
             	
               $account = $_SESSION["account"];
    		   $arr = "select id from useraccount where account= '$account'";
		       $arr = $db->fetchRow($arr);
		       $accountid= $arr["id"];    		 
			 //$SQL="select u.id,u.logtime,w.account as name,u.ip_inner,u.host,u.note as keyword,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,account_id,host,note,pass from $tablename where (account_id=$accountid) and (get_type=3) and (logtime between $start and $datee)  limit  $start,$limit) as u  left join (select id,account from useraccount) as w on u.account_id=w.id ";
			   $SQL = " call filetypeaccount_id('$tablename',$dates,$datee)";      		
			
   		break;
    	case "4":
		         $ipsi = ip2long($_SESSION["ips"]);
			     $ipei = ip2long($_SESSION["ipe"]); 
			 if ($systemmode == 0)
                {              
                 $format = "call filetypeip_ips('%s',%u,%u,%u,%u)"; 
			     $SQL=sprintf($format,$tablename,$dates,$datee,$ipsi,$ipei); 			  
			    }
    	  		
          else
               {
                $format = "call keywordaccount_ips('%s',%u,%u,%u,%u)"; 			   
			    $SQL = sprintf($format,$tablename,$dates,$datee,$ipsi,$ipei);   
               } 
				break;
    	      default:
    		   return;
    }
   
     $result = $db->fetchRow( $SQL );
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables */
	$aColumns = array('logtime', 'name', 'ip_inner', 'host','filetype','pass');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "logtime";
	
	/* Database connection information */
	$gaSql['user']       = "root";
	$gaSql['password']   = "123456";
	$gaSql['db']         = "baseconfig";
	$gaSql['server']     = "localhost";
	
	/* REMOVE THIS LINE (it just includes my SQL connection user/pass) */
	//include( $_SERVER['DOCUMENT_ROOT']."/datatables/mysql.php" );
	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * MySQL connection
	 */
	$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
		die( 'Could not open connection to server' );
	mysql_query("SET NAMES utf8");
	mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
		die( 'Could not select database '. $gaSql['db'] );
	
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
			 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
		}
		$sOrder = substr_replace( $sOrder, "", -2 );
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE ";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".implode(", ", $aColumns)."
		FROM   reporttmp
		$sWhere
		$sOrder
		$sLimit
	";
	
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   reporttmp
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$sOutput = '{';
	$sOutput .= '"sEcho": '.intval($_GET['sEcho']).', ';
	$sOutput .= '"iTotalRecords": '.$iTotal.', ';
	$sOutput .= '"iTotalDisplayRecords": '.$iFilteredTotal.', ';
	$sOutput .= '"aaData": [ ';
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$sOutput .= "[";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] == "pass" )
			{
				/* Special output formatting for 'version' */
				$sOutput .= ($aRow[ $aColumns[$i] ]=="1") ?
					'"放行",' :
					'"阻挡",';
					//'"'.str_replace('"', '\"', $aRow[ $aColumns[$i] ]).'",';
			}
			else
			{
				/* General output */
				$sOutput .= '"'.str_replace('"', '\"', $aRow[ $aColumns[$i] ]).'",';
			}
		}
		
		/*
		 * Optional Configuration:
		 * If you need to add any extra columns (add/edit/delete etc) to the table, that aren't in the
		 * database - you can do it here
		 */
		
		
		$sOutput = substr_replace( $sOutput, "", -1 );
		$sOutput .= "],";
	}
	$sOutput = substr_replace( $sOutput, "", -1 );
	$sOutput .= '] }';
	
	echo $sOutput;
?>