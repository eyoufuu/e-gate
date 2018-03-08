/**
 *\ brief 处理SMTP数据包的文件
 *\ author zhengjianfang 
 *\ date 2009-09-11
 */
 
#include <sys/socket.h>
#include <sys/un.h>
#include <unistd.h>
#include <stdio.h>
#include "smtpParse.h"
#include "mysqlHandle.h"
#include "packetdefine.h"
#include "jhash.h"
#include "FileLog.h"
#include "global_time.h"

//static const char reportdb[] = {"reportmanager"};
static const char auditdb[] = {"baseconfig"};
mem_hash *smtp_mem;   //存放SMTP的包信息
static char create_title[] = {"CREATE TABLE IF not exists `%s`(`titleid` int(11) unsigned NOT NULL auto_increment,\
						`logtime` int(11) unsigned NOT NULL default '0', `ip_inner` int(11) unsigned NOT NULL default '0',\
						`port_inner` int(11) unsigned NOT NULL default '0', `ip_outter` int(11) unsigned NOT NULL default '0',\
						`port_outter` int(11) unsigned NOT NULL default '0', `account_id` int(11) unsigned NOT NULL default '0',\
						`sourcemailaddr` varchar(32) NOT NULL default '', `destmailaddr` blob NOT NULL,\
						`mac_address` varchar(20) NOT NULL default '0', `smtpstatus` int(11) unsigned NOT NULL default '0',\
						PRIMARY KEY  (`titleid`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;"};
static char insert_title[] = {"insert into `%s` (`logtime`, `ip_inner`, `port_inner`, `ip_outter`, `port_outter`, `account_id`, `sourcemailaddr`, `destmailaddr`, `mac_address`, `smtpstatus`)\
						values('%u', '%u', '%u', '%u', '%u', '%u', '%s', '%s', '%s', '%u');"};

static char create_content[] = {"CREATE TABLE IF not exists `%s` (`contentid` int(11) unsigned NOT NULL auto_increment,\
  							`titleid` int(11) unsigned NOT NULL default '0', `seqnum` int(11) unsigned NOT NULL default '0', `content` blob NOT NULL,\
  							PRIMARY KEY  (`contentid`)) ENGINE=MyISAM DEFAULT CHARSET=utf8; "};
static char insert_content[] = {"insert into `%s` (`titleid`, `seqnum`, `content`) values('%u', '%u', '%s');"};


//2010 -7-7 qianbo add the get_tablename_create
//title 0
//data 1
#define table_title 0
#define table_data 1
static const char * get_tablename_create(int title_data)
{
      static int xnumber  = 0;

      static char tablename_title[32] = {0};
      static char tablename_data[32]= {0};
      static int  xyear    =      1900; 
      static int  xmonth =      1;	   
	  
       xyear    =      g_ptm->curdate.tm_year +1900; 
       xmonth =       g_ptm->curdate.tm_mon+1;	   	      		
	if(xnumber != xyear*xmonth)
	{
	       sprintf(tablename_title,"%d%02dsmtptitle",xyear,xmonth);
		sprintf(tablename_data,"%d%02dsmtpdata",xyear,xmonth);
		char createsql[1024] = {0};
		sprintf(createsql, create_title,tablename_title);
		if(0 != execSql(auditdb, createsql, strlen(createsql)))
		{
			WADEBUG(D_FATAL)("create table failed.\n");
			return NULL;
		}
		sprintf(createsql,create_content,tablename_data);
		if(0 != execSql(auditdb, createsql, strlen(createsql)))
		{
			WADEBUG(D_FATAL)("create table failed.\n");
			return NULL;
		}
		
		xnumber = xyear*xmonth;
	}
	if(title_data==table_title)
		return tablename_title;
	else if(title_data==table_data)
		return tablename_data;

}



u_int32 init_smtphandle()
{
	//init post bdb
	smtp_mem = create_hash_mem_info("SmtpconnMemDB",SMTP_MEM_CONN_SIZE);
	if(NULL == smtp_mem)
	{
		return 0;
	}
	return 1;
}

void final_smtphandle()
{
	delete_all_mem_hash(smtp_mem);
	close_mem_hash(smtp_mem);
}

/*
 * \brief 从指定SMTP字符串中查找发件人邮箱地址
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \param s_mail: 获取到的发件人邮箱地址
 * \param s_maillen: 发件人邮箱地址长度
 */
void getMailFrom(const char* buf,const u_int16 buflen,char** s_mail,u_int16* s_maillen)
{
	int i =0;
	int ret = 0;
	int pointer;
	ret = strSearch((void*)(buf),(void*)MAILFROM,buflen,9);
	if(ret<0)
	{
		return;
	}
	i = i+ret;
	i = i+10;

	while(*(buf+i) != '<'/*&&i<*buflen*/)
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
	*s_mail =(char*)( buf + pointer);
	ret = strSearch((void*)(buf+i),(void*)">",buflen-i,1);
	if(ret<0)
	{
		return;
	}
	i = i+ret;
	*s_maillen = i-pointer;
}

/*
 * \brief 从指定SMTP字符串中查找收件人邮箱地址
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \param d_mail: 获取到的收件人邮箱地址
 * \param d_maillen: 收件人邮箱地址长度
 */
void getMailTo(const char* buf,const u_int16 buflen,char** d_mail,u_int16* d_maillen)
{
	int i =0;
	int ret = 0;
	int pointer;
	ret = strSearch((void*)(buf),(void*)MAILTO,buflen,7);
	if(ret<0)
	{
		return;
	}
	i = i+ret;
	i = i+8;//  i+4(host)	i++;(':')

	while(*(buf+i) != '<'/*&&i<*buflen*/)
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
	*d_mail =(char*)( buf + pointer);
	ret = strSearch((void*)(buf+i),(void*)">\r\n",buflen-i,3);
	if(ret<0)
	{
		return;
	}
	i = i+ret;
	*d_maillen = i-pointer;
}

/**
 *\brief 判断该包是否是SMTP 的DATA包
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \return 是DATA包，返回true， 否则返回false
 */
u_int32 isTheDATAPack(const char* buf,const u_int16 buflen)
{
	int i =0;
	int ret = 0;
	int pointer;
	ret = strSearch((void*)(buf),(void*)DATA_BIG,buflen,4);
	if(ret<0)
	{
		ret = strSearch((void*)(buf),(void*)DATA_SMALL,buflen,4);
		if(ret < 0)
			return 0;
		return 1;
	}
	else
		return 1;
}


/**
 *\brief 判断该包是否是SMTP 的QUIT包
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \return 是QUIT包，返回true， 否则返回false
 */
u_int32 isSMTPQUITPack(const char* buf,const u_int16 buflen)
{
	int i =0;
	int ret = 0;
	int pointer;
	ret = strSearch((void*)(buf),(void*)SMTPQUIT,buflen,4);
	if(ret<0)
		return 0;
	else
		return 1;
}


/**
 * \brief 初始化存放SMTP包头的结构体，是在收到MAIL FROM包的时候进行
 * \param pSmtpInfo: 需要初始化的SMTP包头的结构体
 * \param s_Port: 源端口
 * \param d_Port: 目的端口
 * \param s_ip: 源IP地址
 * \param d_ip: 目的IP地址
 * \param s_mail: 发件人邮箱地址
 * \param s_maillen: 发件人邮箱地址的长度
 */
void Initialize_SmtpInfo(SmtpInfo* pSmtpInfo, char *s_mail, u_int16 s_maillen)
{
	pSmtpInfo->id = 0;
	memcpy((char*)pSmtpInfo->s_mailaddr, s_mail, s_maillen);
	pSmtpInfo->s_mailaddr[s_maillen] = '\0';
//	memset(pSmtpInfo->d_mailaddr, 0, sizeof(pSmtpInfo->d_mailaddr));
	pSmtpInfo->d_mailaddr[0] = '\0';
	pSmtpInfo->status = 1;
	pSmtpInfo->starttime = g_ptm->curtime;
}


void smtp_handle(const char *buf, u_int32 buflen)
{
	const Pro_smtp *packet;
	const char *packetbody = NULL;

	packet = (Pro_smtp *)buf;
	packetbody = (const char *)(buf+sizeof(Pro_smtp));

	WADEBUG(D_ALL)("receive the packet: sourceip:%u, sourceport:%u, destip:%u, destport:%u,seqnum:%u,ack:%u.\n",
						ntohl((packet->ip_inner)),
						ntohs(packet->port_inner), 
						ntohl((packet->ip_outter)),
						ntohs(packet->port_outter),ntohl(packet->seqnum), ntohl(packet->ack));
	WADEBUG(D_ALL)("the packet body:%s\n", packetbody);

	u_int64 jhash_data = jhash_3words(ntohl(packet->ip_inner), ntohl(packet->ip_outter), ntohs(packet->port_inner), HASH_INITVAL);

	char value[2048] ;
	u_int32 ret = query_mem_hash_core(smtp_mem, &jhash_data, value, sizeof(value)-1);
	if(ret == RET_BDB_FAIL)   ////容器中还没有这个包，则判断是否是MAIL FROM 包，如果是，加入，如不是，跳过
	{
		char *mail_from = NULL;
		u_int16  s_maillen  = 0;
		getMailFrom(packetbody, strlen(packetbody), &mail_from, &s_maillen);
		if(s_maillen == 0 || s_maillen>64)
		{
			WADEBUG(D_ALL)("the first packet is not the mail first packet.\n");
			return;
		}
		
		SmtpInfo smtpInfo;
		Initialize_SmtpInfo(&smtpInfo, mail_from, s_maillen);
		if(-1 == record_mem_hash_core(smtp_mem, &jhash_data, (char*)&smtpInfo , sizeof(SmtpInfo)))
		{
			WADEBUG(D_ALL)("insert into the bdb failed.\n");
		}
		return ;
	}
	else          //容器里已经有这个包，根据状态字判断此时需要什么包
	{
		SmtpInfo *smtp_data = (SmtpInfo *)value;
		u_int32 status = smtp_data->status;
		switch(status)
		{
			case 1:  //需要RCPT TO 包，其它的包都扔掉
				{
					char *mail_to = NULL;
					u_int16  d_maillen  = 0;
					getMailTo(packetbody, strlen(packetbody), &mail_to, &d_maillen);
					if(d_maillen == 0 || d_maillen>64)
					{
						if(isSMTPQUITPack(packetbody, strlen(packetbody)))
						{
							delete_mem_hash_core(smtp_mem, &jhash_data); 
						}
						return ;
					}
					memcpy((char*)smtp_data->d_mailaddr, mail_to, d_maillen);
					smtp_data->d_mailaddr[d_maillen] = '\0';
					smtp_data->status = 2;
					if(-1 == replace_mem_hash_core(smtp_mem, &jhash_data, (char*)smtp_data, sizeof(SmtpInfo)))
					{
						WADEBUG(D_ALL)("insert into the bdb failed.\n");
					}
					return ;
				}
				break;
			case 2:    //需要DATA包或者RCPT TO 包
				{
					if(isTheDATAPack(packetbody, strlen(packetbody)))
					{
						const char *tname =get_tablename_create(table_title);
						if (tname==NULL)
						{
							 printf("error tablename is null");
							return;
						}
						char mac[18] = {0};
						sprintf(mac, "%02x:%02x:%02x:%02x:%02x:%02x", packet->mac[0], packet->mac[1], packet->mac[2], packet->mac[3], packet->mac[4], packet->mac[5]);
						char  sqlstr[2048];
						sprintf(sqlstr, insert_title, tname, smtp_data->starttime, ntohl(packet->ip_inner), ntohs(packet->port_inner),
								ntohl(packet->ip_outter), ntohs(packet->port_outter), packet->account_id, 
								smtp_data->s_mailaddr, smtp_data->d_mailaddr, mac, 0);
						WADEBUG(D_ALL)("%s\n", sqlstr);
						u_int32 message_id = exeInsert(auditdb, sqlstr, strlen(sqlstr));
						if((unsigned int)-1 == message_id)
						{
							delete_mem_hash_core(smtp_mem, &jhash_data); 
							return ;
						}
						smtp_data->id = message_id;
						smtp_data->status = 3;
						if(-1 == replace_mem_hash_core(smtp_mem, &jhash_data, (char*)smtp_data, sizeof(SmtpInfo)))
						{
							WADEBUG(D_ALL)("insert into the bdb failed.\n");
						}
						return ;
					}
					char *mail_to = NULL;
					u_int16  d_maillen  = 0;
					getMailTo(packetbody, strlen(packetbody), &mail_to, &d_maillen);
					if(d_maillen == 0 || d_maillen>64)
					{
						if(isSMTPQUITPack(packetbody, strlen(packetbody)))
						{
							delete_mem_hash_core(smtp_mem, &jhash_data); 
						}
						return;
					}
					smtp_data->d_mailaddr[strlen(smtp_data->d_mailaddr)+1] = '\0';
					smtp_data->d_mailaddr[strlen(smtp_data->d_mailaddr)] = ',';					
					u_int16 oldlen = strlen(smtp_data->d_mailaddr);
					if(oldlen + d_maillen >= sizeof(smtp_data->d_mailaddr))
						return ;
					memcpy((char*)((smtp_data->d_mailaddr)+strlen(smtp_data->d_mailaddr)), mail_to, d_maillen);
					smtp_data->d_mailaddr[oldlen + d_maillen] = '\0';
					if(-1 == replace_mem_hash_core(smtp_mem, &jhash_data, (char*)smtp_data, sizeof(SmtpInfo)))
					{
						WADEBUG(D_ALL)("insert into the bdb failed.\n");
					}
					return ;
				}
				break;
			case 3:     //判断是否是QUIT包，如果不是，则为数据包，存入数据库，如果是，则清除数据
				{
					if(isTheDATAPack(packetbody, strlen(packetbody)))
						return;
					if(isSMTPQUITPack(packetbody, strlen(packetbody)))
					{
						delete_mem_hash_core(smtp_mem, &jhash_data); 
						return ;
					}
					else
					{
						if(strlen(packetbody) == 0)
							return;
						const char *tname =get_tablename_create(table_data);
						char sqlstr[MAXLINE*2] ;
						char strbodyData[MAXLINE*2 +1] ;
						mysql_escapestring(auditdb, strbodyData,packetbody, buflen-sizeof(Pro_smtp));
						sprintf(sqlstr, insert_content, tname, smtp_data->id, ntohl(packet->seqnum), strbodyData);
						WADEBUG(D_ALL)("%s\n", sqlstr);
						if((unsigned int)-1 == exeInsert(auditdb, sqlstr, strlen(sqlstr)))
						{
							WADEBUG(D_ALL)("smtp insert the content into database failed.\n");
							return ;
						}
						return ;
					}
				}
				break;
		}
	} 
}
