#include <sys/socket.h>
#include <sys/un.h>
#include <stdio.h> 
#include <stdlib.h>
#include <time.h>
#include <unistd.h>
#include <mysql.h>
#include "getParse.h"
#include "packetdefine.h"
#include "mysqlHandle.h"
#include "FileLog.h"
#include "global_time.h"

static const char auditdb[] = {"baseconfig"};
static const char createtable[] = {"CREATE TABLE IF not exists `%s`(`id` int(11) unsigned NOT NULL auto_increment,\
						`logtime` int(11) unsigned NOT NULL default '0', `get_type` int(11) unsigned NOT NULL default '0',\
						`typeid`  int(4),`policyid` int(4),\
						`ip_inner` int(11) unsigned NOT NULL default '0', `ip_outter` int(11) unsigned NOT NULL default '0',\
						`account_id` int(11) unsigned NOT NULL default '0',`mac_address` varchar(18) NOT NULL default '0',\
						`host` varchar(64) NOT NULL default '0', `url` blob NOT NULL, `pass` int(11) unsigned NOT NULL default '1',\
						`note` varchar(100) ,\
						PRIMARY KEY  (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8; "};
static const char insertsql[] = {"insert into `%s`(`logtime`, `get_type`, `ip_inner`, `ip_outter`, `account_id`, `mac_address`, `host`, `url`, `pass`,`note`,`typeid`,`policyid`)values('%u', '%u', '%u', '%u', '%u', '%s', '%s', '%s', '%u','%s','%u','%u');"};

static const char * get_tablename_create()
{
      static int xnumber  = 0;
      static int  xyear    =      1900; 
      static int  xmonth =      1;	   	
      static char tablename[32] = {0};

      xyear    =      g_ptm->curdate.tm_year +1900, 
      xmonth =     g_ptm->curdate.tm_mon+1;	   	
	if(xnumber != xyear*xmonth)
	{
      		sprintf(tablename,"%d%02dweb",xyear,xmonth);
		char createsql[1024] = {0};
		sprintf(createsql, createtable,tablename);
		if(0 != execSql(auditdb, createsql, strlen(createsql)))
		{
			WADEBUG(D_FATAL)("create table failed.\n");
			return NULL;
		}
		xnumber = xyear*xmonth;
	}
	return tablename;

}


void get_handle(const char *buf, u_int32 buflen)
{
	const Pro_get *packet = NULL;

	//const struct udp_header *udp;
	const char *url = NULL;
	const char *host = NULL;
	const char *key  = NULL;
	int port = 0;

	packet = (Pro_get *)buf;
	//unsigned char* ip_s;
	//unsigned char* ip_t;
	//ip_s = &packet->ip_src;
	//ip_t = &packet->ip_dst;
	//printf("\n\n\n\n\n\nreceive the packet: sourceip:%d.%d.%d.%d, sourceport:%u, destip:%d.%d.%d.%d, destport:%u,seqnum:%u,ack:%u, account:%s.\n",
	//					*ip_s,*(ip_s+1),*(ip_s+2),*(ip_s+3)/*ntohl((packet->ip_src))*/,
	//					ntohs(packet->s_port), 
	//					*ip_t,*(ip_t+1),*(ip_t+2),*(ip_t+3)/*ntohl((packet->ip_dst))*/,
	//					ntohs(packet->d_port),ntohl(packet->seqnum), ntohl(packet->ack), packet->account);
	//WADEBUG(D_ALL)("receive the packet: sourceip:%u, destip:%u, seqnum:%u,ack:%u.\n",
	//					ntohl((packet->ip_inner)),
	//					ntohl((packet->ip_outter)),
	//					ntohl(packet->seqnum), ntohl(packet->ack));
	url = (const char *)(buf+sizeof(Pro_get));
	host = (const char *)(buf + URL_LEN);
	if(packet->get_type == 3)
	{
		     key = (const char*)(buf+2048);
	}
      const char * tname = NULL;
	tname = get_tablename_create();
	if(tname ==NULL)
		return ;

	char mac[18] = {0};
	sprintf(mac, "%02x:%02x:%02x:%02x:%02x:%02x", packet->mac[0], packet->mac[1], packet->mac[2], packet->mac[3], packet->mac[4], packet->mac[5]);

	
	char strurl[MAXLINE*2 +1] ;
	mysql_escapestring(auditdb, strurl, url, strlen(url));

	char sqlstr[MAXLINE*2] ;
	sprintf(sqlstr, insertsql, tname,g_ptm->curtime, packet->get_type, ntohl(packet->ip_inner), 
						ntohl(packet->ip_outter),  packet->account_id,  mac, host, strurl, packet->isblocked,key,packet->typeid,packet->policyid);

	if((unsigned int)-1 == exeInsert(auditdb, sqlstr, strlen(sqlstr)))
	{
		WADEBUG(D_ALL)("getparse insert into database failed.\n");
		return ;
	}
}


#if 0
//localtime get the time
//	int time_start =  g_ptm->curtime;
//	time_t timeseg = (time_t)time_start;
//	struct tm *local_time = NULL;
//	local_time = localtime(&timeseg); 
//	char time_now[64] = {0};
//	strftime(time_now, sizeof(time_now), "%Y%m", local_time); 
#endif
