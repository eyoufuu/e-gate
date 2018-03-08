#include <sys/socket.h>
#include <sys/un.h>
#include <time.h>
#include <unistd.h>
#include <stdio.h> 
#include <stdlib.h>
#include <mysql.h>
#include "flowParse.h"
#include "packetdefine.h"
#include "mysqlHandle.h"
#include "FileLog.h"
#include "global_time.h"

static const char auditdb[] = {"baseconfig"};
//static const char reportdb[] = {"reportmanager"};
static const char createtable[] = {"CREATE TABLE IF not exists `%s`(`id` int(11) unsigned NOT NULL auto_increment,\
						`logtime` int(11) unsigned NOT NULL default '0', `account_id` int(11) unsigned NOT NULL default '0',\
						`ip_inner` int(11) unsigned NOT NULL default '0', `pro_id` int(11) unsigned NOT NULL default '0',\
						`upflow` int(11) unsigned NOT NULL default '0', `downflow` int(11) unsigned NOT NULL default '0',\
						`packets_passed_num` int(11) unsigned NOT NULL default '0', `packets_blocked_num` int(11) unsigned NOT NULL default '0',\
						PRIMARY KEY  (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8; "};
static const char insertsql[] = {"insert into `%s`(`logtime`, `account_id`, `ip_inner`, `pro_id', `upflow`, `downflow`, `packets_passed_num`, `packets_blocked_num`)values('%u', '%u', '%u', '%u', '%u', '%u', '%u', '%u');"};





void flow_handle(const char *buf, u_int32 buflen)
{
	const Pro_flow *packet = NULL;

	packet = (Pro_flow *)buf;
	//unsigned char* ip_s;
	//unsigned char* ip_t;
	//ip_s = &packet->ip_src;
	//ip_t = &packet->ip_dst;
	//printf("\n\n\n\n\n\nreceive the packet: sourceip:%d.%d.%d.%d, sourceport:%u, destip:%d.%d.%d.%d, destport:%u,seqnum:%u,ack:%u, account:%s.\n",
	//					*ip_s,*(ip_s+1),*(ip_s+2),*(ip_s+3)/*ntohl((packet->ip_src))*/,
	//					ntohs(packet->s_port), 
	//					*ip_t,*(ip_t+1),*(ip_t+2),*(ip_t+3)/*ntohl((packet->ip_dst))*/,
//					ntohs(packet->d_port),ntohl(packet->seqnum), ntohl(packet->ack), packet->account);

	int time_start =  g_ptm->curtime;

	time_t timeseg = (time_t)time_start;
	struct tm *local_time = NULL;
	local_time = localtime(&timeseg); 
	char time_now[64] = {0};
	strftime(time_now, sizeof(time_now), "%Y%m%d", local_time); 

	char tablename[32] = {0};
	strcat(tablename, time_now);
	strcat(tablename, "flowdata");

	char createsql[1024] = {0};
	sprintf(createsql, createtable,tablename);
	WADEBUG(D_ALL)("%s\n", createsql);
	if(0 != execSql(auditdb, createsql, strlen(createsql)))
	{
		WADEBUG(D_ALL)("create table failed.\n");
		return ;
	}
	
	char sqlstr[MAXLINE] ;
	sprintf(sqlstr, insertsql, tablename, time_start, packet->account_id, ntohl(packet->ip_inner), 
						packet->protocol_id,  packet->up_flow,   packet->down_flow, packet->packets_across, packet->packets_block);
	WADEBUG(D_ALL)("%s\n", sqlstr);
	WADEBUG(D_ALL)("begin to insert the data to mysql\n");
	if((unsigned int)-1 == exeInsert(auditdb, sqlstr, strlen(sqlstr)))
	{
		WADEBUG(D_ALL)("insert into database failed.\n");
		return ;
	}
	WADEBUG(D_ALL)("insert successful\n");
}