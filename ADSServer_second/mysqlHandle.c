#include <stdio.h>
#include "mysqlHandle.h"

static char* hostname = "127.0.0.1";
static char* username = "root";
static char* password = "123456";
static u_int32 dbport = 3306;
MYSQL *mysql;

u_int32 mysql_connect(const char *dbname)
{
	if(mysql)
	{
		close_mysql();
	}
	mysql=mysql_init(NULL);
	if(mysql == NULL)
	{
		return 0;
	}
	if(mysql_real_connect(mysql, hostname, username, password, dbname, dbport, NULL, CLIENT_COMPRESS|CLIENT_INTERACTIVE)==NULL)
	{
		//Á¬½Ó´íÎó  mysql_error(mysql);
		return 0;
	}
	return 1;
}

void close_mysql()
{
	mysql_close(mysql);
	mysql = NULL;
}

u_int32 execSql(const char *dbname, const char *sql, unsigned int sqllen)
{
	if(sql==NULL || sqllen == 0 )
	{
		return (unsigned int) - 1;
	}
	if(!mysql_connect(dbname))
	{
		return (unsigned int) - 1;
	}
	//sql = sql;
	int ret = mysql_real_query(mysql, sql, sqllen);
	if(ret)
	{
		//mysql_error(mysql);
	}
	close_mysql();
	return ret;
}

u_int32 exeInsert(const char *dbname, const char *sql, unsigned int sqllen)
{
	if(sql==NULL || sqllen == 0 )
	{
		return (unsigned int) - 1;
	}
	if(!mysql_connect(dbname))
	{
		return (unsigned int) - 1;
	}
	sql = sql;
	int ret = mysql_real_query(mysql, sql, sqllen);
	if(ret)
	{
		ret = -1;
		close_mysql();
		mysql = NULL;
		return ret;
	}
	ret = mysql_insert_id(mysql);
	close_mysql();
	return ret;
}

MYSQL_RES *exeSelect(const char *dbname, const char *sql, unsigned int sqllen)
{
	if(sql==NULL || sqllen == 0 )
	{
		return NULL;
	}
	if(!mysql_connect(dbname))
	{
		return NULL;
	}
	sql = sql;
	int ret = mysql_query(mysql, sql);
	if(ret)
	{
		close_mysql();
		return NULL;
	}
	MYSQL_RES *result = NULL;
	result = mysql_store_result(mysql);
	close_mysql();
	return result;
}

u_int32 mysql_escapestring(const char *dbname, char *to, const char *from, unsigned long length)
{
	mysql_connect(dbname);
	if(mysql == NULL)
	{
		return (unsigned int) - 1;
	}
	int ret = mysql_real_escape_string(mysql, to,from, length);
	close_mysql();
	return ret;
	
}

