#include <stdio.h>
#include <stdlib.h>
#include "mysql/mysql.h"

#define SQL_SERVER "localhost"
#define SQL_NAME_BASE "baseconfig"
#define SQL_NAME_AUDIT "audit"
#define SQL_USER "root"
#define SQL_PWD  "123456"

int g_isqosopen;		
int g_isipmacbind;
int g_isarp_ipmacbind;		

static MYSQL *db_handel,mysql;
static MYSQL_ROW row;
static int query_error;


void sql_free(MYSQL_RES *result)
{
    mysql_free_result(result);
}
int sql_query_init()
{
    mysql_init(&mysql);
    my_bool re_connect = 1;
    mysql_options(&mysql,MYSQL_OPT_RECONNECT,&re_connect);   
    mysql_options(&mysql, MYSQL_SET_CHARSET_NAME, "utf8");//utf8
    db_handel=mysql_real_connect(&mysql,SQL_SERVER,SQL_USER,SQL_PWD,SQL_NAME_BASE,0,0,0); 
    if(db_handel==NULL)
    {
            printf(mysql_error(&mysql));
            return 0;
    }
    return 1;
}
MYSQL_RES *sql_query(char* dbname,char *sql)
{
    	if(!sql_query_init())
   	{
   		printf("sql init er\n");
		exit(0);
	}
    static MYSQL_RES *query_result;
    query_error=mysql_query(db_handel,sql);
    if(query_error!=0)
    {
        printf(mysql_error(db_handel));
        return NULL;
    }
    query_result=mysql_store_result(db_handel);
    return query_result;
}

void readglobalpara()
{

#define GBL_ISQOSOPEN           atoi(row[0])
#define GBL_ISIPMACBIND		    atoi(row[1])
#define GBL_ISARP_IPMACBIND      atoi(row[2])   

	MYSQL_RES * results;
	results = sql_query(SQL_NAME_BASE,"select 	isqosopen,isipmacbind,isarp_ipmacbind from globalpara");
	if(NULL == results)
	{
		printf("sql err\n");
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
		g_isqosopen = GBL_ISQOSOPEN;		
		g_isipmacbind = GBL_ISIPMACBIND;
		g_isarp_ipmacbind = GBL_ISARP_IPMACBIND;			
	}
    	sql_free(results);
}

void echobindipmac(char * para)
{
#define IPMAC_IP		row[0]
#define IPMAC_MAC	row[1]pa
#define IPMAC_BIND 	atoi(row[2])

	MYSQL_RES * results;

	char buf[200];   

	results = sql_query(SQL_NAME_BASE,"select ip,mac from ipmac where bind=1");
	if(NULL == results)
	{
		printf("ipmac er\n");
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{		
	      sprintf(buf,"echo %s %s > %s",row[0],row[1],para);
	      system(buf);
	}
    	sql_free(results);
}

int main(int argc, char **argv)
{
	int ch;
	while((ch = getopt(argc,argv,"d"))!= -1)
	switch(ch)
	{
		case 'd':
			daemon(1,0);
			break;
		default:
			break;
	}
//init timesrv
	system("/timesrv -d");
//init list91
	system("/user -d");
//init adserver
	system("/adserver -d");
//init qos
	if(g_isqosopen)
	{
		system("/tc");
	}
// init iarp.ko
	system("insmod /iarp.ko");
	switch(g_isarp_ipmacbind)
	{
		case 0:
	    		system("echo 1 > /proc/ipmac_arp_c");
			break;
		case 1:
			echobindipmac("/proc/ipmac_arp");			
			system("echo 3 > /proc/ipmac_arp_c");	  
			break;
		default:
			printf("arp er\n");
			break;			
	}

//insmod bri.ko module
        system("insmod /briflow2.ko");
	switch(g_isipmacbind)
	 {
		case 0:	
			system("echo 0  > /proc/bridge_bypass");
			break;
		case 1:
			system("echo 1 > /proc/bridge_bypass");
			break;
		case 2:
			echobindipmac("/proc/bridge_ipmac");				
			system("echo 2 > /proc/bridge_bypass");
			break;
		case 3:
			echobindipmac("/proc/bridge_ipmac");				
			system("echo 3 > /proc/bridge_bypass");
			break;
		default:
			printf("g_ipmacbind er\n");
			break;
	 }
}
