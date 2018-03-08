#include <sys/socket.h>
#include <sys/un.h>
#include <time.h>
#include <unistd.h>
#include <mysql.h>
#include "pop3Parse.h"
#include "packetdefine.h"
#include "mysqlHandle.h"
#include "bdbmem.h"
#include "FileLog.h"
#include "jhash.h"
#include "global_time.h"


mem_hash *pop3_mem; //存放post包的Post_head 包，以区分是否是同一个连接
static const char auditdb[] = {"baseconfig"};
//static const char policymanagerdb[] = {"policymanager"};
//static const char reportdb[] = {"reportmanager"};
static const char createtable[] = {"CREATE TABLE IF not exists `%s`(`logtime` int(11) unsigned NOT NULL default '0',\
						`seqnum` int(11) unsigned NOT NULL default '0', `ip_inner` int(11) unsigned NOT NULL default '0',\
						`port_inner` int(11) unsigned NOT NULL default '0', `ip_outter` int(11) unsigned NOT NULL default '0',\
						`port_outter` int(11) unsigned NOT NULL default '0', `jhash` bigint(21) unsigned NOT NULL default '0',\
						`ack` int(11) unsigned NOT NULL default '0', `account_id` int(11) unsigned NOT NULL default '0',\
						`content` blob NOT NULL,  `pop3status` int(11) unsigned NOT NULL default '0',\
						PRIMARY KEY  (`logtime`,`seqnum`,`ip_inner`,`port_inner`,`ip_outter`,`port_outter`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8; "};
static const char insertsql[] = {"insert into `%s` values('%u', '%u', '%u', '%u', '%u', '%u', '%llu', '%u', '%u',  '%s', '%u');"};

u_int32 init_pop3handle()
{
	//init post bdb
	pop3_mem = create_hash_mem_info("Pop3connMemDB",POP3_MEM_CONN_SIZE);
	if(NULL == pop3_mem)
	{
		return 0;
	}
	return 1;
}

 void final_pop3handle()
 {
 	delete_all_mem_hash(pop3_mem);
	close_mem_hash(pop3_mem);
 }

u_int32 isMailHead(const char* buf,const u_int16 buflen)
{
	int i =0;
	int ret = 0;
	int pointer;
	ret = strSearch((void*)(buf),(void*)MAILHEAD,buflen,8);
	if(ret<0)
		return 0;
	else
		return 1;
}

u_int32 isMailEnd(const char* buf,const u_int16 buflen)
{
	int i =0;
	int ret = 0;
	int pointer;
	ret = strSearchEnd((void*)(buf),(void*)MAILEND,buflen,5);
	if(ret<0)
		return 0;
	else
		return 1;
}


static const char * get_tablename_create()
{
     static int xnumber  = 0;
      static char tablename[32] = {0};
      static int  xyear    =      1900; 
      static int  xmonth =      1;	   	

        xyear    =       g_ptm->curdate.tm_year +1900; 
        xmonth =       g_ptm->curdate.tm_mon+1;	   	
	if(xnumber != xyear*xmonth)
	{
      		sprintf(tablename,"%d%02dpop3data",xyear,xmonth);
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



void pop3_handle(const char *buf, u_int32 buflen)
{
	const Pro_pop3 *packet = NULL;

	//const struct udp_header *udp;
	const char *packetbody = NULL;
	int port = 0;

	packet = (Pro_pop3 *)buf;
	//unsigned char* ip_s;
	//unsigned char* ip_t;
	//ip_s = &packet->ip_src;
	//ip_t = &packet->ip_dst;
	//printf("\n\n\n\n\n\nreceive the packet: sourceip:%d.%d.%d.%d, sourceport:%u, destip:%d.%d.%d.%d, destport:%u,seqnum:%u,ack:%u, account:%s.\n",
	//					*ip_s,*(ip_s+1),*(ip_s+2),*(ip_s+3)/*ntohl((packet->ip_src))*/,
	//					ntohs(packet->s_port), 
	//					*ip_t,*(ip_t+1),*(ip_t+2),*(ip_t+3)/*ntohl((packet->ip_dst))*/,
	//					ntohs(packet->d_port),ntohl(packet->seqnum), ntohl(packet->ack), packet->account);
	/*WADEBUG(D_ALL)("receive the packet: sourceip:%u, sourceport:%u, destip:%u, destport:%u,seqnum:%u,ack:%u.\n",
						ntohl((packet->ip_inner)),
						ntohs(packet->port_inner), 
						ntohl((packet->ip_outter)),
						ntohs(packet->port_outter),ntohl(packet->seqnum), ntohl(packet->ack));*/
	printf("start pop3 handle!\n---------------------------------\n");
	packetbody = (const char *)(buf+sizeof(Pro_pop3));
	//WADEBUG(D_ALL)("the packet body:%s\n", packetbody);
	if((memcmp(packetbody, "+OK", 3) == 0) || (memcmp(packetbody, "-ERR", 3) == 0))  //过滤掉握手包
	{
		return;
	}
	Pop3_head *pop3_head;
	Pop3_head pop3_head_new;

	u_int32 time_start = 0;

	u_int64  hash_value = gethash_value_1(TCP_CONNECT, packet->port_inner, ntohl((packet->ip_inner)));
	WADEBUG(D_ALL)("the hash value is :%llu\n", hash_value);

	char value[32] = {0};
	u_int32 ret = query_mem_hash_core(pop3_mem, &hash_value, value, sizeof(value)-1);
	if(ret == RET_BDB_FAIL)   //数据库里没有，存入
	{
		if(!isMailHead(packetbody, strlen(packetbody)))
		{
			WADEBUG(D_ALL)("the data is not the mail head.\n");
			return;
		}
		pop3_head_new.ack = packet->ack;
		time_start = g_ptm->curtime;
		pop3_head_new.time = time_start;
		if(-1 == record_mem_hash_core(pop3_mem, &hash_value, (char*)&pop3_head_new , sizeof(Pop3_head)))
		{
			WADEBUG(D_ALL)("insert into the bdb failed.\n");
			return ;
		}
	}
	else if(ret == RET_BDB_OK)   //数据库里有，取出
	{
		pop3_head = (Pop3_head*)value;
		if(pop3_head->ack == packet->ack)  //ACK相同，表明同一个连接，取出数据
		{
			time_start = pop3_head->time;
		}
		else			//ACK不同，表明是另外一个连接，删除老的，将新的存入
		{
			if(!isMailHead(packetbody, strlen(packetbody)))
			{
				WADEBUG(D_ALL)("the data have no host\n");
				return;
			}
			pop3_head_new.ack = packet->ack;
			time_start = (u_int32)time(NULL);
			pop3_head_new.time = time_start;
			if(-1 == replace_mem_hash_core(pop3_mem, &hash_value, (char*) &pop3_head_new , sizeof(Pop3_head)))
			{
				WADEBUG(D_ALL)("replace the data failed.\n");
				return ;
			}
		}
	}
	else
	{
		WADEBUG(D_ALL)("return unknow value.\n");
		return;
	}

	if(isMailEnd(packetbody, strlen(packetbody)))
	{
		delete_mem_hash_core(pop3_mem, &hash_value); 
	}
	const char *tname = get_tablename_create();
	if(tname==NULL)
	{
	      WADEBUG(D_ALL)("pop3name get error!");
		return;
	}

	char strbodyData[MAXLINE*2 +1] ;
	mysql_escapestring(auditdb, strbodyData,packetbody, buflen-sizeof(Pro_pop3));
	
	char sqlstr[MAXLINE*2] ;
	sprintf(sqlstr, insertsql, tname, time_start, ntohl(packet->seqnum), ntohl(packet->ip_inner), ntohs(packet->port_inner), 
						ntohl(packet->ip_outter),  ntohs(packet->port_outter), hash_value, packet->ack, packet->account_id, strbodyData,0);

	WADEBUG(D_ALL)("%s\n",sqlstr);
	if((unsigned int)-1 == exeInsert(auditdb, sqlstr, strlen(sqlstr)))
	{
		WADEBUG(D_ALL)("pop3 insert into database failed.\n");
		return ;
	}
	WADEBUG(D_ALL)("insert successful\n");
}
