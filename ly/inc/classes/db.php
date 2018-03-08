<?php

  /*
   * Modified on: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created on: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
   */
   
    class UtilLog
    {
		//var $_date;//日期
		var $_opr; //操作 添加 删除 修改 
		var $_res; //成功 失败
		var $_username ;
		var $_error;//密码错误还是用户名错误
		var $_sql ; //SQL语句
		var $_identify;//管理员 操作员 审计员
		var $_sql_insert = "insert into `log_opr` (`date`,`opr`,`res`,`username`,`error`,`sql`,`identify`) values(now(),'%s','%s','%s','%s','%s','%s')";
		var $_SQL_t = "";//需要记录的sql语句
		//var $_db_log  ;
		function UtilLog($db ,$username,$identify)
		{
		    //$this->_db_log = $db;
			$this->_username = $username;
			$this->_identify = $identify;
		}
		/*
		function init($db_log_ini)
		{
			$this->_db_log = $db_log_ini;
		}*/
		function setopr($opr)
		{
			switch($opr)
			{
				case "add":
					$this->_opr = "添加";
					break;
				case "del":
					$this->_opr = "删除";
					break;
				case "edit":
					$this->_opr = "修改";
					break;
				case "log":
					$this->_opr = "登陆";
					break;
				case "read":
					$this->_opr = "读取";
					break;
			    case "auser":
					$this->_opr = "增加用户";
					break;
				case "duser":
					$this->_opr = "删除用户";
					break;
				case "limit":
					$this->_opr = "权限分配";	
					break;
				case "select":
					$this->_opr = "选取数据";
					break;
				default:
				    $this->_opr = $opr;
					break;
			}
		}
		
		function set_something($opr,$res,$error,$sql)
		{
			$this->setopr($opr);
			$this->_res = $res;
			$this->_error = $error;
			$this->_sql = $sql;
		}
		/*
		function log_no_connect()
		{
			$fp = fopen("./log/logphp_logopr.txt","ab");
			$SQL_t = sprintf($this->_sql_insert,$this->_res,$this->_opr,$this->_username,$this->_error,$this->_SQL,$this->_identify);
			$result = mysql_query( $SQL_t ) or fprintf($fp,"sql:%s error\r\n",$SQL_t);
			fclose($fp);
		}
	
		function log()
		{
		    global $dsn;
			global $user;
			global $password;
			global $dbase;
			$db_log = mysql_connect($dbhost, $dbuser, $dbpassword)
			or die("连接错误: " . mysql_error());
			mysql_select_db($database) or die("Error conecting to db.");
			$this->$SQL_t = sprintf($this->_sql_insert,$this->_res,$this->_opr,$this->_username,$this->_error,$this->_SQL,$this->_identify);
			$result = mysql_query( $SQL_t );
			//mysql_close($db_1og);		
		}*/
		
		
		function get_logstr($opr,$res,$error,$sql)
		{
			$this->set_something($opr,$res,$error,$sql);
			return sprintf($this->_sql_insert,$this->_res,$this->_opr,$this->_username,$this->_error,$this->_sql,$this->_identify);
			//$this->_db_log->insertlog($this->_SQL_t);
		}
	
   }
   
	class Db
    {
		var $_dsn = 'mysql:host=localhost;dbname=baseconfig;';
		var $_user   = 'root';
		var $_password = '123456';
		var $_dbh ;
		var $_stmt;
		var $_log;
		
		function Db($username="admin",$identify="1")
		{
			$this->_dbh = new PDO($this->_dsn,$this->_user,$this->_password);
			$this->_dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$this->_dbh->query("SET NAMES utf8");
			$this->_log = new UtilLog($this,$username,$identify);
		}
		//query是最快的用法
		/*
		function query($SQL,$Module="NULL")
		{
			try
			{
				return $this->_dbh->query($SQL);
			}
			catch(PDOException $e)
			{
				$fp = fopen("./log/db_error.txt","ab");
				fprintf($fp,"[module:%s]--[time:%s]\r\n",date("Y-m-d H:i:s"),$module);
				fprintf($fp,"SQL:%s\r\n",$Module,$SQL);
				fprintf($fp,"error:%s\r\n\r\n",$e->getMessage());
				fclose($fp);
			}
		}*/

	    function log($SQL,$Module,$other=null)
		{
			$SQL_trim = ltrim($SQL);
			$ch = $SQL_trim[0];
			$badchr    = array('\'','"');
			$goodchr    = array('','');
			$SQL_trim = str_replace($badchr,$goodchr,$SQL_trim);
			
			$str = "";
			try{
			switch($ch)
			{
				case "i":
				case "I":
					$str = $this->_log->get_logstr("add",$Module,"ok",$SQL_trim);
					$this->_dbh->exec($str);
					break;
				case "d":
				case "D":
					$str = $this->_log->get_logstr("del",$Module,"ok",$SQL_trim);
					$this->_dbh->exec($str);
					break;
				case "s":
				case "S":
				    if (($Module == "登录成功") || ($Module == "登录失败"))
					{ 
					 $str = $this->_log->get_logstr("read",$Module,"ok",$SQL_trim);
					 $this->_dbh->exec($str);
					} 
					break;
				case "c":
				case "C":
					$str = $this->_log->get_logstr("read","报表","存储过程调用",$SQL_trim);
					$this->_dbh->exec($str);
					break;
				case "u":
				case "U":
					$str = $this->_log->get_logstr("edit",$Module,"ok",$SQL_trim);
					$this->_dbh->exec($str);
					break;
				case "l":
				 //如果是log，则other里面才是sql语句
				    $SQL_trim = str_replace($badchr,$goodchr,$other);
					$str = $this->_log->get_logstr("log",$Module,"ok",$SQL_trim);
				    $this->_dbh->exec($str);
					break;
				default:
					$str = $this->_log->get_logstr($SQL,$Module,$other,$SQL_trim);
					$this->_dbh->exec($str);
					break;
			}//switch end
			}//try end
			catch(PDOException $e)
			{
			  // echo $e->getMessage() . " sql:" . $str;
				/*$fp = fopen("./db_log_error.txt","ab");
				fprintf($fp,"[log module:%s]--[time:%s]\r\n",$Module,date("Y-m-d H:i:s"));
				fprintf($fp,"SQL:%s\r\n",$str);
				fprintf($fp,"error:%s\r\n\r\n",$e->getMessage());
				fclose($fp);*/
			}
			
		}
		//exec用来执行不用返回值的
		function exec($SQL,$Module="NULL")
		{
		//insert delete 
			try
			{
			//返回行数
				$this->log($SQL,$Module);
				return $this->_dbh->exec($SQL);
			}
			catch(PDOException $e)
			{
			  // echo $e->getMessage() . "sql:" . $SQL;
			  /*
				$fp = fopen("./db_error.txt","ab");
				fprintf($fp,"[exec module:%s]--[time:%s]\r\n",$Module,date("Y-m-d H:i:s"));
				fprintf($fp,"SQL:%s\r\n",$SQL);
				fprintf($fp,"error:%s\r\n\r\n",$e->getMessage());
				fclose($fp);*/
			}
		}
		
		
		//这个下个版本再修改，下个版本可以使用参数，防止sql注入攻击，另外大量数据重复插入速度较快
		//select和存储过程 一定要用这个
		function query2($SQL,$Module="NULL",$iflog = true)
		{
			try
			{
	            $this->log($SQL,$Module);
				$this->_stmt= $this->_dbh->prepare($SQL);
				$this->_stmt->execute();
				$result = $this->_stmt->fetchAll();
				/*
				if($iflog==true)
				{  
				   //echo $iflog;
               	   //$this->log($SQL,$Module);
				}
			    */
				return $result;
			}
			catch(PDOException $e)
			{
			
			  // echo $e->getMessage() . "sql:" . $SQL;
			/*	$fp = fopen("./db_error.txt","ab");
				fprintf($fp,"[query2 module:%s]--[time:%s]\r\n",$Module,date("Y-m-d H:i:s"));
				fprintf($fp,"SQL:%s\r\n",$SQL);
				fprintf($fp,"error:%s\r\n\r\n",$e->getMessage());
				fclose($fp);*/
			}
		}
	    function query2one($SQL,$Module="NULL",$iflog=true)
		{
			try
			{
				$this->_stmt= $this->_dbh->prepare($SQL);
				$this->_stmt->execute();
				$result = $this->_stmt->fetch(PDO::FETCH_ASSOC);
				if($iflog==true)
				{
				    $this->log($SQL,$Module);
				}
				return $result;
			}
			catch(PDOException $e)
			{
			  // echo $e->getMessage() . "sql:" . $SQL;
			  /*
				$fp = fopen("./db_error.txt","ab");
				fprintf($fp,"[query2one module:%s]--[time:%s]\r\n",$Module,date("Y-m-d H:i:s"));
				fprintf($fp,"SQL:%s\r\n",$SQL);
				fprintf($fp,"error:%s\r\n\r\n",$e->getMessage());
				fclose($fp);*/
			}
		}
		function query2_count($SQL,$Module="NULL")
		{
			try
			{
				$this->_stmt= $this->_dbh->prepare($SQL);
				$this->_stmt->execute();
				return $this->_stmt->fetchColumn();
			}
			catch(PDOException $e)
			{
			   //echo $e->getMessage() . "SQL" . $SQL;
				/*$fp = fopen("./db_error.txt","ab");
				fprintf($fp,"[query2_count module:%s]--[time:%s]\r\n",$Module,date("Y-m-d H:i:s"));
				fprintf($fp,"SQL:%s\r\n",$SQL);
				fprintf($fp,"error:%s\r\n\r\n",$e->getMessage());
				fclose($fp);*/
			}
		}
		function fetchOne($SQL,$Module="NULL")
		{
			return $this->query2_count($SQL,$Module);
		}
		function fetchRow($SQL,$Module="NULL")
		{
			return $this->query2one($SQL,$Module);
		}
		function fetchRows($SQL,$Module="NULL")
		{
			return $this->query2($SQL,$Module);
		}
		
		function get_lastinsert_id()
		{
			return $this->_dbh->lastInsertId();
		}
		function get_rows_count()
		{
		    return $this->_stmt->rowCount();
		}
	    function _open()
		{
		}
		function close()
		{
			$this->_dbh->close();
		}
   }
	

  /*
   class Db
   {
       var $_version = '1.0';
       var $_type = 'mysql';
       
       var $_host = '';     //主机
       var $_user = '';     //用户名
       var $_pwd = '';     //密码
       var $_db = '';     //数据库
       
       var $_conn = null;       
       
       /**
        * 构造函数
        */
		/*
       function Db($arrParams = array('host'=>'localhost', 'user'=>'root', 'pwd'=>'', 'db'=>'test'))
       {
           $this->_host = $arrParams['host'];
           $this->_user = $arrParams['user'];
           $this->_pwd = $arrParams['pwd'];
           $this->_db = $arrParams['db'];
           $this->_open($this->_db);
       }
       
       /**
        * 执行查询
        *//*
       function query($sql)
       {           
	
		
           $rs = mysql_query( $sql ) or die("数据库查询错误.".mysql_error());
		   //$rs = mysql_db_query($this->_db, $sql) OR $this->_quit("Query error: <!-- $sql -->");
           
           return $rs;
       }
       
       /**
        * 返回某一单独字段值的查询。例如SELECT COUNT(id) FROM ...
        *//*
       function fetchOne($sql)
       {
           $rs = $this->query($sql);
           $arr = mysql_fetch_array($rs, MYSQL_NUM);
           mysql_free_result($rs);     
           
           return $arr[0];
       }
       
       /**
        * 只返回一行的查询
        *//*
       function fetchRow($sql)
       {
           $rs = $this->query($sql);
           $arr = mysql_fetch_array($rs, MYSQL_ASSOC);
           mysql_free_result($rs);
           
           return $arr;
       }
       
       /**
        * 返回多行的查询
        *//*
       function fetchRows($sql)
       {
           $result = $this->query($sql);
           $arr = array();
           while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
           {
               $arr[] = $row;
           }
           
          mysql_free_result($result);
         

          return $arr;
       }
       
       /**
        * 分页查询
        *//*
       function fetchPage($sql, $page = '1', $pageSize = '20')
       {
           $start = ($page - 1) * $pageSize;
           $arr = $this->fetchRows($sql . " LIMIT $start, $pageSize");
           
           return $arr;
       }
       
       /**
        * 关闭数据库
        *//*
       function close()
       {
           mysql_close();
       } 
       
       /**
        * 打开数据库
        *//*
       function _open($database = 'test')
       {
           $this->_conn = mysql_connect($this->_host, $this->_user, $this->_pwd) || $this->_quit('Connect error: ');
           
           if ($this->_getDbVersion() >= 4.1)
           {
               mysql_query('SET NAMES utf8');
           }
           
           return mysql_select_db($database);
       }
       
       /**
        * 获取数据库版本
        *//*
       function _getDbVersion()
       {
         $rs = mysql_query("SELECT VERSION();");
         $row = mysql_fetch_array($rs, MYSQL_NUM);
         
         $ver = $row[0];
         $vers = explode(".", trim($ver));
         $ver = $vers[0] . ".".$vers[1];
         
         return $ver;
       }
       
       /**
        * 退出
        *//*
       function _quit($msg = 'Error: ')
       {
           exit($msg . mysql_error());
           
           return false;
       }
    /*   function _callprocedure($sql)
       {
       		 $res=$this->query($sql);
       		 $res=$this->query();
       		       
       }*//*
       function fetcharray($rs,$param)
       {
       		 $row = mysql_fetch_array($rs,$param);
       		 return $row;
       }
   }
  */ 
?>