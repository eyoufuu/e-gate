#include <sys/socket.h>
#include <sys/un.h>
#include <time.h>
#include <unistd.h>
#include <mysql.h>
#include "postParse.h"
#include "packetdefine.h"
#include "mysqlHandle.h"
#include "bdbmem.h"
#include "FileLog.h"
#include "jhash.h"
#include "global_time.h"
#include "webmail.h"
#include "pktsend.h"
mem_hash *post_mem; //存放post包的Post_head 包，以区分是否是同一个连接
static mem_hash *post_host; //保存发帖的时候的host名字，只对我们目前支持的host进行审计，其它的不记入数据库
static const char auditdb[] = {"baseconfig"};
static const char policymanagerdb[] = {"baseconfig"};
//static const char reportdb[] = {"reportmanager"};
static const char createtable[] = {"CREATE TABLE IF not exists `%s`(`logtime` int(11) unsigned NOT NULL default '0',\
						`seqnum` int(11) unsigned NOT NULL default '0', `ip_inner` int(11) unsigned NOT NULL default '0',\
						`port_inner` int(11) unsigned NOT NULL default '0', `ip_outter` int(11) unsigned NOT NULL default '0',\
						`port_outter` int(11) unsigned NOT NULL default '0', `jhash` bigint(21) unsigned NOT NULL default '0',\
						`account_id` int(11) unsigned NOT NULL default '0',`mac_address` varchar(18) NOT NULL default '0',\
						`host` varchar(128) NOT NULL default '0', `content` blob NOT NULL,  `poststatus` int(11) unsigned NOT NULL default '0',\
						`url` varchar(1024) NOT NULL default '0',\
						PRIMARY KEY  (`logtime`,`seqnum`,`ip_inner`,`port_inner`,`ip_outter`,`port_outter`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8; "};
static const char insertsql[] = {"insert into `%s`(`logtime`,`seqnum`,`ip_inner`,`port_inner`,`ip_outter`,`port_outter`,`jhash`,`account_id`,`mac_address`,`host`,`content`,`poststatus`,`url`) \
							values('%u', '%u', '%u', '%u', '%u', '%u', '%llu', '%u', '%s', '%s','%s','%u' , '%s');"};
static const char selecthost[] = {"select distinct `host` from `postparams`;"};


static const char * get_tablename_create(int i )
{
      static int xnumber  = 0;
      static int  xyear    =      1900; 
      static int  xmonth =      1;	   	
      static char tablename_mail[32] = {0};
      static char tablename_post[32] = {0};
	  
       xyear    =      g_ptm->curdate.tm_year +1900; 
       xmonth =       g_ptm->curdate.tm_mon+1;	   
	if(xnumber != xyear*xmonth)
	{
	       char createsql[1024] = {0};
       	sprintf(tablename_post,"%d%02dpostdata",xyear,xmonth);
		sprintf(createsql, createtable,tablename_post);
		if(0 != execSql(auditdb, createsql, strlen(createsql)))
		{
			WADEBUG(D_FATAL)("create table post failed.\n");
			return NULL;
		}
	    sprintf(tablename_mail,"%d%02dwebmaildata",xyear,xmonth);
	    sprintf(createsql, createtable,tablename_mail);
           if(0 != execSql(auditdb, createsql, strlen(createsql)))
	    {
		       WADEBUG(D_FATAL)("create table webmail failed.\n");
			return NULL;
	    }
	    xnumber = xyear*xmonth;
	}
	if(i==post_mail)
		return tablename_mail;
	else
		return tablename_post;

}

u_int32 init_posthandle()
{
	//init post bdb
	post_mem = create_hash_mem_info("PostconnMemDB",POST_MEM_CONN_SIZE);
	if(NULL == post_mem)
	{
		return 0;
	}
	post_host = create_hash_mem_info("PosthostMemDB",POST_MEM_HOST_SIZE);
	if(NULL == post_host)
	{
		return 0;
	}
	
	MYSQL_RES *hostresult = exeSelect(policymanagerdb, selecthost, strlen(selecthost));
	if(hostresult == NULL)
	{
		WADEBUG(D_FATAL)("did not get the host data from mysql.\n");
		return 0;
	}
	unsigned int i;
	MYSQL_FIELD *fileds;
	MYSQL_ROW row;
	fileds=mysql_fetch_fields(hostresult);
	while((row=mysql_fetch_row(hostresult))!=NULL)
	{
		u_int32 rowlen = strlen(row[0]);
		u_int64 hash_value =  jhash((void *)row[0], rowlen, HASH_INITVAL);
		if(-1 == record_mem_hash_core(post_host, &hash_value, row[0] , rowlen+1))
		{
			mysql_free_result(hostresult);
			WADEBUG(D_FATAL)("insert into the bdb failed.\n");
			return 0;
		}
	}
	mysql_free_result(hostresult);

#if 0
	char value[32] = {0};
	u_int32 ret = query_mem_hash_core(post_host, &test, value, sizeof(value)-1);
	printf("get the host data from bdb:%s\n", value);
#endif
	return 1;
}

 void final_posthandle()
 {
 	delete_all_mem_hash(post_mem);
	close_mem_hash(post_mem);
	delete_all_mem_hash(post_host);
	close_mem_hash(post_host);
 }

u_int32 isPostmailPack(const char* buf,const u_int16 buflen)
{
	int i =0;
	int ret = 0;
	int pointer;
	ret = strSearch((void*)(buf),(void*)POSTMAIL,buflen,4);
	return ret;
/*	if(ret<0)
		return 0;
	else
		return 1;*/
}

inline int http_search(void *source, void *target,u32 soc_length, u32 tar_length)
{
	void *addr;
	u8 *search_address;
	search_address = (u8 *)source;
	while((addr = memchr(search_address, *((char*)target), soc_length))!=0)
	{
		if(memcmp(addr, target, tar_length)==0)
		{
			return (char*)addr-(char*)source;
		}		
		soc_length = soc_length - (long)((u8*)addr - (u8*)search_address)-1;
		search_address=(u8 *)((u8 *)addr+1);		
	}
	return -1;	
}

inline void http_host(u8* buf,u16 buflen,u8** host,u16* hostlen)
{
#define HTTP_HOST "Host"
	u32 i =0;
	int ret = 0;
    *hostlen = 0;
    if(buflen<15)//"host: \r\n"
        return;
	u32 len = buflen; 
//get hostinfo
	ret = http_search((void*)(buf),(void*)HTTP_HOST,len,4);
	if(ret<0)
	{
		return;
	}
	i = i+ret;
	i = i+6;//  i+4(host)	i++;(':')(' ')
	if(i>=len)
	{
		return;
	}	
	*host =(u8*)( buf + i);
	ret = http_search((void*)(buf+i),(void*)"\r\n",len-i,2);
	*hostlen = ret<0?0:ret;
}

const char* geturl(const char* buf,const u_int16 buflen, char ** url,u_int16* urllen)
{

   int i = 0;
   char c ;
   int left;
   c = buf[0];
   switch(c)
   {
   	   case 'P':
	   	i +=5;
		left = 5;
		break;
	   case 'G':
		i+=4;
		left = 4;
		break;
	   default:
	   	return NULL;
   }
   *url      =(char*)( buf+i); 
   while(i<buflen)
   {
	   if(buf[i] !=' ')
	   {
	       i++;
	   }
	   else 
	   	break;
   }
  
  *urllen = i-left;
   return *url;
}

void getHostFrom(const char* buf,const u_int16 buflen,char** hostname,u_int16* s_hostlen)
{
	int i =0;
	int ret = 0;
	int pointer;
	ret = strSearch((void*)(buf),(void*)HOSTNAME,buflen,strlen(HOSTNAME));
	if(ret<0)
	{
		return;
	}
	i = i+ret;
	i = i+4;

	while(*(buf+i) != ' '/*&&i<*buflen*/)
	{
		i++;
		if(i>=buflen)
		{
			return;
		}
	}
	i++;
	if(i>=buflen)
	{
		return;
	}
	pointer = i;
	*hostname =(char*)( buf + pointer);
	ret = strSearch((void*)(buf+i),(void*)"\r\n",buflen-i,2);
	if(ret<0)
	{
		return;
	}
	i = i+ret;
	*s_hostlen = i-pointer;
}


void post_handle(const char *buf, u_int32 buflen)
{
	const Pro_post *packet = NULL;

	//const struct udp_header *udp;
	const char *packetbody = NULL;
	int packetbodylen =0;
	int port = 0;

	packet = (Pro_post *)buf;
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
	packetbody = (const char *)(buf+sizeof(Pro_post));
	packetbodylen = buflen-sizeof(Pro_post);//strlen(packetbody);
	//WADEBUG(D_ALL)("the packet body:%s\n", packetbody);
	Post_head *post_head;
	Post_head post_head_new;

	char *hostname = NULL;
	static u_int16  s_hostlen  = 0;
	static char host[128] = {0};
	char url[1024] = {0};
       char strurl[MAXLINE*2 +1] ={0};
	u_int32 time_start = 0;
	u_int32 post_type = 0;
	u_int32 post_stat = can_anayle;
       static u_int32 mail_pos = -1;

	u_int64  hash_value = gethash_value_1(TCP_CONNECT, packet->port_inner, ntohl((packet->ip_inner)));
	//WADEBUG(D_ALL)("the hash value is :%llu\n", hash_value);

	char value[32] = {0};
	u_int32 ret = query_mem_hash_core(post_mem, &hash_value, value, sizeof(value)-1);
	if(ret == RET_BDB_FAIL)   //数据库里没有，存入
	{
		getHostFrom(packetbody, packetbodylen, &hostname, &s_hostlen);
		if(s_hostlen == 0 || hostname==NULL || s_hostlen >= 128)
		{
			WADEBUG(D_ALL)("the data have no host\n");
			return;
		}
		memcpy(host, hostname, s_hostlen);
		if((mail_pos = isPostmailPack(hostname, s_hostlen))!=-1)
		{
		     printf("a mail come in!------------\n");
		     post_type = post_mail;
		    handle_webmail(packet->seqnum, packet->ack,packet->ip_inner,packet->ip_outter,packet->port_inner,packet->port_outter,
                                               host ,s_hostlen, mail_pos,packetbody,packetbodylen);
	
		}
		else
		{
		      printf("a post come in!------------\n");
			post_type = post_post;
			//判断host是否在列表中，根据结果设置不同的状态值
			/*
			u_int64 hash_host =  jhash((void *)host, s_hostlen, HASH_INITVAL);
			char hostvalue[64] = {0};
			u_int32 ret_host = query_mem_hash_core(post_host, &hash_host, hostvalue, sizeof(hostvalue)-1);
			if(ret_host == RET_BDB_FAIL)
			{
				WADEBUG(D_ALL)("the host is not in the bdb:%s.\n", host);
				post_stat = can_not_anayle;
			}*/
		}
		post_head_new.type = post_type;
		post_head_new.ack = packet->ack;
		post_head_new.post_stat = post_stat;
		time_start = g_ptm->curtime;
		post_head_new.time = time_start;
		if(-1 == record_mem_hash_core(post_mem, &hash_value, (char*)&post_head_new , sizeof(Post_head)))
		{
			WADEBUG(D_ALL)("insert into the bdb failed.\n");
			return ;
		}
	}
	else if(ret == RET_BDB_OK)   //数据库里有，取出
	{
	       if(post_type==post_mail)
	       {
	       	     handle_webmail(packet->seqnum, packet->ack,packet->ip_inner,packet->port_outter,packet->port_inner,packet->port_outter,
                                               host ,s_hostlen, mail_pos,packetbody,packetbodylen);
	       }
		post_head = (Post_head*)value;
		if(post_head->ack == packet->ack)  //ACK相同，表明同一个连接，取出数据
		{
		       printf("the database has the value and the ack is %u\n",post_head->ack);
			time_start = post_head->time;
			post_type = post_head->type;
			post_stat = post_head->post_stat;
		}
		else			//ACK不同，表明是系统回复
		{
       	     printf("the database has the value and the ack is not same discard it! %u\n",packet->ack);
		     return ;
		/*
			getHostFrom(packetbody, strlen(packetbody), &hostname, &s_hostlen);
			if(s_hostlen == 0 || hostname==NULL || s_hostlen >= 128)
			{
				WADEBUG(D_ALL)("the connect is new, but there is no host in it.\n");
				return;
			}

			if(isPostmailPack(hostname, s_hostlen))
			{
				post_type = post_mail;
			}
			else
			{
				post_type = post_post;
				//判断host是否在列表中，根据结果设置不同的状态值
				u_int64 hash_host =  jhash((void *)host, s_hostlen, HASH_INITVAL);
				char hostvalue[64] = {0};
				u_int32 ret_host = query_mem_hash_core(post_host, &hash_host, hostvalue, sizeof(hostvalue)-1);
				if(ret_host == RET_BDB_FAIL)
				{
					WADEBUG(D_ALL)("the host is not in the dbd:%s.\n", host);
					post_stat = can_not_anayle;
				}
			}			
			memcpy(host, hostname, s_hostlen);
			post_head_new.type = post_type;
			post_head_new.ack = packet->ack;
			time_start = g_ptm->curtime;
			post_head_new.time = time_start;
			post_head_new.post_stat = post_stat;
			if(-1 == replace_mem_hash_core(post_mem, &hash_value, (char*) &post_head_new , sizeof(Post_head)))
			{
				WADEBUG(D_ALL)("replace the data failed.\n");
				return ;
			}*/
		}
	}
	else
	{
		WADEBUG(D_ALL)("return unknow value.\n");
		return;
	}

      const char* tablename = get_tablename_create(post_type);
      char * urlurl = NULL;
      u_int16 urllen = 0;
      //printf("the packet body is :%s\n",packetbody);
      if(geturl(packetbody,packetbodylen,&urlurl,&urllen)!=NULL)
      {
           memcpy(url,urlurl,urllen);
	    url[urllen] = '\0';

	   mysql_escapestring(auditdb, strurl, url, strlen(url));
      }

//	printf("TCP:%s:%d > %s:%d = %s\n", inet_ntoa(packet->ip_src), ntohs(packet->s_port), inet_ntoa(packet->ip_dst), ntohs(packet->d_port), packetbody);
	char strbodyData[MAXLINE*2 +1] ;
	mysql_escapestring(auditdb, strbodyData,packetbody, packetbodylen);

	u_int32 host_len = 0;
	char strhost[128*2 +1] ;
	if(strlen(host) == 0)
	{
		host_len = 1;
	}
	else 
		host_len = strlen(host);
	mysql_escapestring(auditdb, strhost,host, host_len);
	char mac[18] = {0};
	sprintf(mac, "%02x:%02x:%02x:%02x:%02x:%02x", packet->mac[0], packet->mac[1], packet->mac[2], packet->mac[3], packet->mac[4], packet->mac[5]);
	
	char sqlstr[MAXLINE*2] ;
	sprintf(sqlstr, insertsql, tablename, time_start, ntohl(packet->seqnum), ntohl(packet->ip_inner), ntohs(packet->port_inner), 
						ntohl(packet->ip_outter),  ntohs(packet->port_outter), hash_value, packet->account_id,  mac, strhost, strbodyData, post_stat,strurl);
	//WADEBUG(D_ALL)("%s\n", sqlstr);
	//WADEBUG(D_ALL)("begin to insert the data to mysql\n");
	if((unsigned int)-1 == exeInsert(auditdb, sqlstr, strlen(sqlstr)))
	{
		WADEBUG(D_ALL)("post insert into database failed.\n");
		
		FILE *test = fopen("/test.txt","w+");
		if(test!=NULL)
		{
			fprintf(test,"%s\n\n",sqlstr);
			fclose(test);
		}
		
		
		return ;
	}
	WADEBUG(D_ALL)("insert successful\n");
}
