/*File: user.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
*/
#include <stdio.h>
#include <stddef.h>
#include <stdlib.h>
#include <unistd.h>
#include <netinet/in.h>
#include <linux/types.h>
#include <linux/netfilter.h>		/* for NF_ACCEPT */
#include <libnetfilter_queue/libnetfilter_queue.h>
#include <time.h>
#include <linux/tcp.h>
#include <linux/ip.h>
#include <linux/udp.h>
#include <fcntl.h>
#include <linux/rtc.h>
#include <linux/ioctl.h>
#include <pthread.h>
#include <signal.h>
#include <sys/mman.h>

#include <sys/socket.h>
#include <arpa/inet.h>

#include "global.h"
#include "cli.h"
#include "ctl.h"
#include "serv.h"
#include "log2.h"
#include "emp.h"
#include "sndpkt/pktsend.h"
#include "readsql.h"
#include "sharedmem/sharedmem.h"
#include "bwhost.h"
#include "http/httpsearch.h"
#include "imp2.h"
#include "core_engine.h"
//#include "pop3.h"
//#include "smtp.h"
//#include "post.h"
#define NET_PORT_80  20480//__hns(80)
#define NET_PORT_8080  36895//__hns(8080)

#define TIME_COUNT 3
#define TIME_INTEVAL 1800

TEmpnode* g_pemp;
u32 g_upflow = 0;
u32 g_downflow = 0;



inline TEmpnode* user_getipempptr()
{
	return g_pemp;
}

void* thradmin(void* para);
void* thrlog(void* para);
void  cbtime(u32 sig);

void* thradmin(void* para)
{
	serv_init();
	pthread_detach(pthread_self());
	return ((void*)0);
}
void* thrclear(void* para)
{
	u32 i,j,k;
	struct hlist_node *pos,*n;
	TEmpnode* node;
	sleep(1);//推迟一秒后进行账户模式清除，防止log 和clear 2个线程同时工作 
	for(i=0;i<MAX_IP;i++)
	{
		for(j=0;j<MAX_NETSEG;j++)
		{
			u32 lockid = i + j*256;
			hlist_for_each_safe(pos,n,&g_ipemplist[i][j])
			{
				node = hlist_entry(pos,TEmpnode,empnode);
				if(node->emp.mode == SYS_LONGIN)
				{
					if(g_ptm->curtime- node->emp.activetime>TIME_INTEVAL)
					{
						node->emp.mode == SYS_ACCOUNT;
					}
				}
			}
		}
	}
	pthread_detach(pthread_self());
	return ((void*)0);
}
void* thrlog(void* para)
{
	u32 i = 0;
	u32 j = 0;
	u32 k = 0;
	static u8 logtablename[20]="\0";
	static u16 month = 0;
	u8 buf[1000];

    if(g_ptm->curdate.tm_mon+1 != month)
	{
        sprintf(logtablename,"%d%02d%s",g_ptm->curdate.tm_year+1900,g_ptm->curdate.tm_mon+1,"flowdata");
		sql_createprolog(logtablename);
		month = g_ptm->curdate.tm_mon+1;
	}
    u32 curtime = g_ptm->curtime;
	struct hlist_node *pos,*n;
	TLognode* node;
    for(i=0;i<MAX_IP;i++)
	{
		for(j=0;j<MAX_NETSEG;j++)
		{
			for(k=0;k<MAX_PRO;k++)
			{				
				hlist_for_each_safe(pos,n,&g_loglist[i][j][k])
				{
                    node = hlist_entry(pos,TLognode,lognode);
                    TEmpnode* pemp = emp_getempnode(i,j,node->log.ip);
                    if(pol_isprolog(pemp->emp.policyid,k))
                    {    					
    					if(unlikely(node->log.staup !=0 || node->log.stadown !=0))
    					{
    						u32 accid = 0;                         
    						if(unlikely(SYS_ACCOUNT == g_sysmode))
                            {   
                                if(SYS_LONGIN == emp_getempmode(pemp))
                                {                                
                                    accid = emp_getempaccid(pemp);                                                              
                                }
                            } 
                            sprintf(buf,"%s %s %s values(%u,%d,%u,%d,%u,%u,%u,%u)",TABLE_DEFINE_CMD_INSERT,logtablename,\
    							TABLE_DEFINE_PRO_LOG,curtime,accid,__hnl(node->log.ip),k,node->log.staup,\
    							node->log.stadown,node->log.stapassnum,node->log.stablocknum);
                            sql_free(sql_query(SQL_NAME_BASE,buf));
    					}
                    }
                    node->log.staup = 0;
    				node->log.stadown = 0;
    				node->log.stapassnum = 0;
    				node->log.stablocknum = 0;   
				/*	else
					{
						hlist_del(pos);
						free(node);
					}*/
				}
			}
		}
	}
	pthread_detach(pthread_self());
	return ((void*)0);
}
#if 0
void* thrlog(void* para)
{
#define THRLOG_SENDNUM 30
	u32 i = 0;
	u32 j = 0;
	u32 k = 0;
	static u8 logtablename[20]="\0";
	static u16 today = 0;
	u8 buf[1000];
    u32 sendlen = 0;
	ctl_initpkthead(buf);
    
	struct hlist_node *pos,*n;
	TLognode* node;

    u32 count = 0;
        
    for(i=0;i<MAX_IP;i++)
	{
		for(j=0;j<MAX_NETSEG;j++)
		{
			LOCK_IP(i,j);
			for(k=0;k<MAX_PRO;k++)
			{				
				hlist_for_each_safe(pos,n,&g_loglist[i][j][k])
				{
					node = hlist_entry(pos,TLognode,lognode);
					if(unlikely(node->log.staup !=0 || node->log.stadown !=0))
					{
						u32 accid = 0;
						if(unlikely(SYS_ACCOUNT == g_sysmode))
                        {                  
                            TEmpnode* pemp = emp_getempnode(i,j,node->log.ip);
                            if(SYS_LONGIN == emp_getempmode(pemp))
                            {                                
                                accid = emp_getempaccid(pemp);                                                              
                            }
                        }                                                
                        if(unlikely(count==THRLOG_SENDNUM))
                        {
                            sendlen = 8 + 28*THRLOG_SENDNUM;
                           	ctl_setpkthead(buf,sendlen,LOG_TRA);
                            serv_sendto(buf,sendlen);
                            count = 0;
                        }                                               
						(*(u32*)(buf+8+count*28)) = node->log.ip;
						(*(u32*)(buf+12+count*28)) = k;
						(*(u32*)(buf+16+count*28)) = node->log.staup;
						(*(u32*)(buf+20+count*28)) = node->log.stadown;
						(*(u32*)(buf+24+count*28)) = node->log.stapassnum;
						(*(u32*)(buf+28+count*28)) = node->log.stablocknum;
                        (*(u32*)(buf+32+count*28)) = accid;                                                
						node->log.staup = 0;
						node->log.stadown = 0;
						node->log.stapassnum = 0;
						node->log.stablocknum = 0;                        
                        count++;
					}
					else
					{
						hlist_del(pos);
						free(node);
					}
				}
			}
			UNLOCK_IP(i,j);
		}
	}
    sendlen = 8 + 28*count;
    ctl_setpkthead(buf,sendlen,LOG_TRA);
    serv_sendto(buf,sendlen);
	pthread_detach(pthread_self());
	return ((void*)0);
}
#endif
void* thrtime(void* para)
{
    u32 logcount = 0;	
	u32 clearcount = 0;
    u32 i,j,k,curtime;
    char buf[200];
	log2_insertinssumtraffic();
    while(1)
    {
        sleep(3);
        curtime = g_ptm->curtime;
        
        log2_updateinssumtraffic(g_upflow,g_downflow);

        g_upflow = 0;
        g_downflow = 0;
        for(i=0;i<MAX_IP;i++)
        {
            for(j=0;j<MAX_NETSEG;j++)
            {                			        
                for(k=0;k<MAX_PRO;k++)
                {
                	struct hlist_node *pos,*n;
                	TLognode* node;
                	hlist_for_each_safe(pos,n,&g_loglist[i][j][k])
                	{
                		node = hlist_entry(pos,TLognode,lognode);
                		if(node->log.insdown ||node->log.insup)
                		{
                			sprintf(buf,"%s %s %s values(%u,%u,%u,%u,%d,%d)",TABLE_DEFINE_CMD_INSERT,"instraffic",\
                				TABLE_DEFINE_PRO_INS,curtime,node->log.ip,node->log.insup,node->log.insdown,k,0);
                	        sql_free(sql_query("baseconfig",buf));
                			node->log.insdown = 0;
                			node->log.insup = 0;
                		}
                	}
                 }
            }
        }
        sprintf(buf,"delete from instraffic where logtime<=%d and ip>0",g_ptm->curtime-300);//safe 5min instanse data
		sql_query("baseconfig",buf);
      //  if(log2_getintervaltime()>0)
        {
            logcount = logcount + TIME_COUNT;
            if(logcount >=log2_getintervaltime()/*interval*/)
            {
            	pthread_t log;
            	int err = pthread_create(&log,NULL,thrlog,NULL);
            	if(0 != err)
            	{
            		DEBUG(D_FATAL)("thrlog faild\n");
            	}
            	logcount = 0;
            }
        }
    	if(unlikely(g_sysmode == SYS_ACCOUNT))
    	{
    		clearcount = clearcount + TIME_COUNT;
    		if(clearcount == TIME_INTEVAL)
    		{
    			pthread_t clear;
    			int err = pthread_create(&clear,NULL,thrclear,NULL);
    			if(0 != err)
    			{
    				DEBUG(D_FATAL)("thrclear faild\n");
    			}
    			clearcount = 0;
    		}
    	}
    }
    pthread_detach(pthread_self());
    return ((void*)0);
}
#if 0
void cbtime(u32 sig)
{
	static u32 logcount = 0;	
	static u32 clearcount = 0;

	g_curtime = ctl_getsystime(&g_ptm,(time_t*)&g_curtime);
	if(g_inssum == 1)
	{
		//send sum ins traffic;
	}
	g_upflow = 0;
	g_downflow = 0;

	if(log2_getintervaltime() >0)
	{
		logcount = logcount + TIME_COUNT;
		if(logcount > 9/*interval*/)
		{
			pthread_t log;
			int err = pthread_create(&log,NULL,thrlog,NULL);
			if(0 != err)
			{
				DEBUG(D_FATAL)("thrlog faild\n");
			}
			logcount = 0;
		}
	}
	if(g_sysmode == SYS_ACCOUNT)
	{
		clearcount = clearcount + TIME_COUNT;
		if(clearcount == TIME_INTEVAL)
		{
			pthread_t clear;
			int err = pthread_create(&clear,NULL,thrclear,NULL);
			if(0 != err)
			{
				DEBUG(D_FATAL)("thrclear faild\n");
			}
			clearcount = 0;
		}
	}
	alarm(TIME_COUNT);
	return;
}
#endif
static void exit_signal(int signo)
{
	switch(signo)
        {
                case SIGKILL:
                    DEBUG(D_DETAIL)("killed!\n");
                    break;
                case SIGINT:
                    DEBUG(D_FATAL)("sigint!\n");
                    break;
                case SIGABRT:
                    DEBUG(D_FATAL)("abrt!\n");
                    break;
                case SIGSEGV:
                    DEBUG(D_FATAL)("egv!\n");
                    break;
                 default:
                    DEBUG(D_FATAL)("unknow!\n");
                    return;
        }
        exit(0);
}
void init()
{
//init signal
	signal(SIGINT|SIGABRT|SIGSEGV|SIGTERM|SIGKILL,exit_signal);
//init send out pkt head
	ctl_initpkthead(g_sendpkthead);	
//	cli_init();
//init timecli must be first to init
        int id;
        ctl_readsharedmemid(&id);     
	g_ptm = (TTime*)cite_sharedmem(id);
    if(g_ptm == NULL)
    {
        printf("init time first\n");
        exit(0);
    }
    
   
       
//get card info
	if_initdevif();
//read mysql get card type wan or lan or unknow
//	sql_readcardtype();	
    if_setphydevtype("eth0",DEV_IF_WAN);
    if_setphydevtype("eth1",DEV_IF_LAN);
//set g_wanindex and g_lanindex
	if_setwanlanindex();


//init global data
	sql_readglobalpara();
//  sql_readmodule();

#if 0
	if(g_isipmacbind)
	{
		u8 buf[4];
		buf[0] = 0;//先发送停止在发送开始
		SendKernelMessage((char*) buf,0,IMP2_IPMAC_FLAG,1);
		sql_readallipmac();
		buf[0] = 1;
		SendKernelMessage((char*)buf,0,IMP2_IPMAC_FLAG,1);
	}
	else
	{
		u8 buf[4];
		buf[0] = 0;
		SendKernelMessage((char*) buf,0,IMP2_IPMAC_FLAG,1);
	}
#endif
	
//init filecat 
	filetype_initftlist();
	sql_readfilecat();
//webcat init
    webcat_init();

//init bw host must init before policy
    bwhost_init();
	sql_readbwhost();

//init policy
	pol_init();
	sql_readallpolicy();
//	pol_generater();
    policy_echo();
//init ip-emp and ip-pro list
	emp_initipemplist();
	log2_initloglist();
//init netseg
	sql_readnetseg();
//init ip emp table
    if(g_sysmode == SYS_IP)
    {
	    sql_readalluserip();
    }
//init bw ip
/*    sql_readbwip();
 
	if(g_isipmacbind)
	{
		sql_readallipmac();
	}
*/

//init filetrans im block;
        if(g_isimopen)
	{
		sql_readfiletrans_im();
	}
	else
	{
		sql_readallpolicy();
	}
//init filetrans ftp
	if(g_isftpopen)
	{
		u32 i = 0;
		for(i=0;i<100;i++)
		{
			pol_setpropasslog(i,5,0,0);//ftp
			pol_setpropasslog(i,6,0,0);//ftp login
		}
	}
	else
	{
		sql_readallpolicy();
	}
// init filetrans filetype block
    	if(g_isfiletypeopen)
	{
		sql_readfiletype();
	}

//init filetrans blog;
    	if(g_isblogopen)
	{
		sql_readfiletrans_blog();
	}
//init filetrans netdisk
   	if(g_isnetdiskopen)
	{
		sql_readfiletrans_netdisk();
	}
//init filetrans bbs
    	if(g_isbbsopen)
	{
		sql_readfiletrans_bbs();
	}
//init filetrans webmail
  	if(g_ismailopen)
	{
		sql_readfiletrans_mail();
	}

//init g_redirectbuf
//	strcpy(g_redirectbuf,PKT_CONTENT_REDIRCT);
//pol_generater();
//init raw sock
	if(!sndpkt_createrawsocket())
	{
		DEBUG(D_FATAL)("create raw socket error\n");
	}
//get local ip
	g_localip = if_getlocalip("eth2");
	struct in_addr inaddr;
	inaddr.s_addr = g_localip;
	strcpy(g_localipstr,inet_ntoa(inaddr));
    
    sql_readprocattype();
	analyse_initialize();

    pthread_t timecli;
    int err = pthread_create(&timecli,NULL,thrtime,NULL);
    if(err!=0)
    {
       DEBUG(D_FATAL)("thrtime faild\n");
    }      
//init admin thread
	pthread_t admin;
	err = pthread_create(&admin,NULL,thradmin,NULL);
	if(err != 0)
	{
		DEBUG(D_FATAL)("thradmin faild\n");
		exit(1);
	}

 //
//init alarm to suply time
//	signal(SIGALRM,(void*)cbtime);
//	alarm(TIME_COUNT);
}
void uninit()
{
    emp_delallempnode();
    
	bwhost_uninit();
    webcat_uninit();
    filetype_uninit();
	sndpkt_closerawsocket();
    uncite_sharedmem((char*)g_ptm);
	analyse_uninitialize();
}

#if 0
static u_int32_t print_pkt (struct nfq_data *tb)
{
	int id = 0;
	struct nfqnl_msg_packet_hdr *ph;
	u_int32_t mark,ifi; 
	int ret;
	char *data;
	
	ph = nfq_get_msg_packet_hdr(tb);
	if (ph){
		id = ntohl(ph->packet_id);
		printf("hw_protocol=0x%04x hook=%u id=%u ",
			ntohs(ph->hw_protocol), ph->hook, id);
	}
	
	mark = nfq_get_nfmark(tb);
	if (mark)
		printf("mark=%u ", mark);

	ifi = nfq_get_indev(tb);
	if (ifi)
		printf("indev=%u ", ifi);

	ifi = nfq_get_outdev(tb);
	if (ifi)
		printf("outdev=%u ", ifi);

	ret = nfq_get_payload(tb, &data);
	if (ret >= 0)
		printf("payload_len=%d ", ret);

	fputc('\n', stdout);

	return id;
}
#endif
/*
static u_int32_t print_pkt (struct nfq_data *tb,u32 *result)
{
	u8* pdata;
	u32 payloadlen = 0;
	u32 pktid = 0;

	struct nfqnl_msg_packet_hdr *ph;
	
	ph = nfq_get_msg_packet_hdr(tb);
	pktid = ntohl(ph->packet_id);
	payloadlen = nfq_get_payload(tb,(char**)&pdata);

	g_iphdr = (struct iphdr*)(pdata);
	g_tcphdr = (struct tcphdr*)(pdata+g_iphdr->ihl*4);
	
//	struct udphdr *udp= (struct udphdr *)(pdata+ip->ihl*4);
//	printf("mark=%u\n", nfq_get_nfmark(tb));
//	printf("indev=%u,outdev=%u\n",nfq_get_indev(tb),nfq_get_outdev(tb));
//	printf("pindev=%u,poutdev=%u\n",nfq_get_physindev(tb),nfq_get_physoutdev(tb));

	ctl_getpktinfo(nfq_get_physindev(tb),&g_dir,&g_ip,&g_upflow,&g_downflow,payloadlen);
	if(g_dir == DIR_UNKNOW)// CAN BE DEL BC IF NOT IN IP-EMP WILL ACCEPT
	{
		*result = NF_ACCEPT;
		DEBUG(D_FATAL)("pkt dir unknow\n");
		return pktid;
	}
	g_iphash = GETIPHASH(g_ip);
	g_netseghash = GETNETSEGHASH(g_ip);

	u32 lockid = g_iphash + 256*(g_netseghash%MAX_NETSEG);

	if(LOCK_OK != TRYLOCK_IP(lockid))//lock ip 
	{
		*result = NF_ACCEPT;
		DEBUG(D_FATAL)("lock ip error\n");
		return pktid;
	}

	g_pemp = (TIpempnode*)emp_getipempnode(g_iphash,g_ip);
	if(NULL == g_pemp)
	{
		*result = g_gate;
		goto exit;
	}

	if(SYS_ACCOUNT == emp_getempmode(g_pemp))
	{
		if(g_dir == DIR_CS)
		{
			 u16 dport = ntohs(g_tcphdr->dest);
			 if(dport == 80 || dport ==8080)
			 {
			 	u8 buf[1500];
				sprintf(buf,PKT_CONTENT_REDIRCT,g_localipstr,g_localipstr);
			 	RedirectUrl(NULL, buf,g_tcphdr->seq,g_tcphdr->ack_seq,g_iphdr->saddr,g_iphdr->daddr,g_tcphdr->source,g_tcphdr->dest);
			 }
		}
		*result = NF_DROP;
		goto exit;
	}
	emp_updateactivetime(g_pemp,g_curtime);
	
//.....protocol analysis
	g_proid=10;

	if(ntohs(g_tcphdr->dest) == 80)
	{
		g_proid =1;
	}
		
	if(g_proid == 0)
	{
		*result = NF_ACCEPT;
		log2_updatelognode(g_iphash,g_netseghash,g_proid,g_ip,g_dir,payloadlen,*result);
		goto exit;
	}
	u32 specip = emp_getempspecip(g_pemp);
	if(IP_NONE != specip)
	{
		*result = specip?NF_ACCEPT:NF_DROP;
		log2_updatelognode(g_iphash,g_netseghash,g_proid,g_ip,g_dir,payloadlen,*result);
		goto exit;
	}
	
//get policy id	
	u32 polid = emp_getemppolicyid(g_pemp);//get pol id

	*result = pol_getproresult(polid,g_proid);// query pol res

	log2_updatelognode(g_iphash,g_netseghash,g_proid,g_ip,g_dir,payloadlen,*result);

	if(NF_ACCEPT == *result)
	{
		switch(g_proid)
		{
			case PRO_GET:
				if(0 == emp_getempget(g_pemp))
				{
					if(DIR_CS == g_dir)
					{
						u16 headlen =  (g_iphdr->ihl)*4 + (g_tcphdr->doff)*4;
						u16 datalen = __nhs(g_iphdr->tot_len) - headlen;
						*result = get_handle(polid,(u8*)(pdata+headlen),datalen);
					}
				}
				break;
			case PRO_POST:
				if(1 == emp_getemppost(g_pemp))
				{
					if(DIR_CS == g_dir)
					{
						u16 headlen = (g_iphdr->ihl)*4 + (g_tcphdr->doff)*4;						
						u16 datalen = __nhs(g_iphdr->tot_len)-headlen;

						u8 buf[MAXLINE];
						u32 len = 30 + datalen;//28 = sip(4byte) +dip+sport+dport+seq

						ctl_initpkthead(buf,len,LOG_POST,0);
						ctl_initpktcontent(g_iphdr->saddr,g_iphdr->daddr,g_tcphdr->source,g_tcphdr->dest,g_tcphdr->ack_seq,pdata+headlen,datalen);
						serv_sendto((const u8*)buf,len);
					}
				}
				break;
			case PRO_SMTP:
				if(1 == emp_getempsmtp(g_pemp))
				{
					if(DIR_CS == g_dir)
					{
						u16 headlen = (g_iphdr->ihl)*4+ (g_tcphdr->doff)*4;						
						u16 datalen = __nhs(g_iphdr->tot_len)-headlen;

						u8 buf[MAXLINE];
						u32 len = 30 + datalen;//30 = sip(4byte) +dip+sport+dport+seq

						ctl_initpkthead(buf,len,LOG_SMTP,0);
						ctl_initpktcontent(g_iphdr->saddr,g_iphdr->daddr,g_tcphdr->source,g_tcphdr->dest,g_tcphdr->ack_seq,pdata+headlen,datalen);
						serv_sendto((const u8*)buf,len);
					}
				}
				break;
			case PRO_POP3:
				if(1 == emp_getemppop3(g_pemp))
				{
					if(DIR_SC == g_dir)
					{
						u16 headlen = (g_iphdr->ihl)*4 + (g_tcphdr->doff)*4;						
						u16 datalen = __nhs(g_iphdr->tot_len) -headlen;
						
						u8 buf[MAXLINE];
						u32 len = 30 + datalen;

						ctl_initpkthead(buf,len,LOG_POP3,0);
						ctl_initpktcontent(g_iphdr->saddr,g_iphdr->daddr,g_tcphdr->source,g_tcphdr->dest,g_tcphdr->ack_seq,pdata+headlen,datalen);
						serv_sendto((const u8*)buf,len);
					}
				}
				break;
			default:
				break;
		}
	}
exit:
	UNLOCK_IP(lockid);
	return pktid;
}
*/


inline void init_udppktinfo(u32 phyindev, struct iphdr* piphdr,struct udphdr* pudphdr,u8* payload,u32 size)
{
	if(phyindev == g_lanindex)
	{
		g_pkt.dir = DIR_CS;		
		g_pkt.innerip = piphdr->saddr;
		g_pkt.outerip = piphdr->daddr;
		g_pkt.innerport = pudphdr->source;		
		g_pkt.outerport = pudphdr->dest;
        
                g_upflow = g_upflow + size;//record sum traffic              
                
	}
	else if(phyindev == g_wanindex)
	{
		g_pkt.dir = DIR_SC;		
		g_pkt.innerip = piphdr->daddr;
		g_pkt.outerip = piphdr->saddr;
		g_pkt.innerport = pudphdr->dest;
		g_pkt.outerport = pudphdr->source;
                g_downflow = g_downflow + size;//record sum traffic
	}
	else
	{
		g_pkt.dir = DIR_UNKNOW;
	}
//	g_pkt.seq = pudphdr->seq;
//	g_pkt.ack = pudphdr->ack_seq;
        g_pkt.iplen = size;
        g_pkt.payload = payload;
	g_pkt.protype = 17;

}
inline void init_tcppktinfo(u32 phyindev,struct iphdr* piphdr,struct tcphdr* ptcphdr,u8* payload, u32 size)
{
	if(phyindev == g_lanindex)
	{
		g_pkt.dir = DIR_CS;		
		g_pkt.innerip = piphdr->saddr;
		g_pkt.outerip = piphdr->daddr;		
		g_pkt.innerport = ptcphdr->source;
		g_pkt.outerport = ptcphdr->dest;
                g_upflow = g_upflow + size;//record sum traffic
	}
	else if(phyindev == g_wanindex)
	{
		g_pkt.dir = DIR_SC;		
		g_pkt.innerip = piphdr->daddr;
		g_pkt.outerip =piphdr->saddr;		
		g_pkt.innerport = ptcphdr->dest;
		g_pkt.outerport = ptcphdr->source;
                g_downflow = g_downflow + size;//record sum traffic
	}
	else
	{
		g_pkt.dir = DIR_UNKNOW;
	}
        g_pkt.iplen = size;
        g_pkt.payload = payload;
	g_pkt.protype = 6;

	g_pkt.seq = ptcphdr->seq;
	g_pkt.ack = ptcphdr->ack_seq;

}
inline u32 init_pktinfo(u32 phyindev,struct iphdr* piphdr,u8* payload, u32 payloadlen)
{
        u32 iphdrlen = piphdr->ihl*4;
        switch(piphdr->protocol)
        {
                case 17:
            	{
                    struct udphdr* phdr = (struct udphdr*)(payload+iphdrlen);
                    g_pkt.iplen = payloadlen;                       
                    g_pkt.protype = 17;
                    g_pkt.headerlen = iphdrlen+8;
            		g_pkt.payload = payload+g_pkt.headerlen;
            		if(phyindev == g_lanindex)
        	        {
        	            g_pkt.dir = DIR_CS;		
        	    		g_pkt.innerip = piphdr->saddr;
        	    		g_pkt.outerip = piphdr->daddr;
        	            g_pkt.innerport = phdr->source;		
        	            g_pkt.outerport = phdr->dest;
        	            g_upflow = g_upflow + payloadlen;//record sum traffic               
        	        }
        	        else if(phyindev == g_wanindex)
        	        {
        	            g_pkt.dir = DIR_SC;		
        	    		g_pkt.innerip = piphdr->daddr;
        	    		g_pkt.outerip = piphdr->saddr;
        	            g_pkt.innerport = phdr->dest;
        	            g_pkt.outerport = phdr->source;
        	            g_downflow = g_downflow + payloadlen;//record sum traffic
        	        }
        	        else
        	        {
        	            g_pkt.dir = DIR_UNKNOW;
        		//	    DEBUG(D_WARNING)("DIR_UNKNOW_udp %u %u %d\n",piphdr->saddr,piphdr->daddr,phyindev);
        	            return 0;
        	        }
                 }
                        break;
                case 6:
                {
                        struct tcphdr* phdr = (struct tcphdr*)(payload+iphdrlen);
                        g_pkt.iplen = payloadlen;
                      
                		g_pkt.protype = 6;
                		g_pkt.headerlen = iphdrlen + (phdr->doff)*4;
			            g_pkt.payload = payload+g_pkt.headerlen;		
	                	g_pkt.seq = phdr->seq;
	                	g_pkt.ack = phdr->ack_seq;
			    if(phyindev == g_lanindex)
		        {
		                g_pkt.dir = DIR_CS;		
		    		g_pkt.innerip = piphdr->saddr;
		    		g_pkt.outerip = piphdr->daddr;
		                g_pkt.innerport = phdr->source;		
		                g_pkt.outerport = phdr->dest;
		                g_upflow = g_upflow + payloadlen;//record sum traffic               
		        }
		        else if(phyindev == g_wanindex)
		        {
		                g_pkt.dir = DIR_SC;		
		    		g_pkt.innerip = piphdr->daddr;
		    		g_pkt.outerip = piphdr->saddr;
		                g_pkt.innerport = phdr->dest;
		                g_pkt.outerport = phdr->source;
		                g_downflow = g_downflow + payloadlen;//record sum traffic
		        }
		        else
		        {
		                g_pkt.dir = DIR_UNKNOW;
		//		DEBUG(D_WARNING)("DIR_UNKNOW_tcp %u %u %d\n",piphdr->saddr,piphdr->daddr,phyindev);
		                return 0;
		        }
                }
                    break;
                default:
                    return 0;
        }        
        return 1;
}
static int cb(struct nfq_q_handle *qh, struct nfgenmsg *nfmsg,struct nfq_data *nfa, void *data)
{
	u_int32_t result = NF_ACCEPT;
//  u32 log = 0;
    TLognode* lognode;
    u32 polid = 0;
    u8* pdata;
	struct nfqnl_msg_packet_hdr *ph = nfq_get_msg_packet_hdr(nfa);
	u32 pktid = __nhl(ph->packet_id);
        
	u32 payloadlen = nfq_get_payload(nfa,(char**)&pdata);
//    printf("make = %u\n",nfq_get_nfmark(nfa));
    u32 indev = nfq_get_physindev(nfa);
//interupt for db reload
    switch(g_interupt)
    {
        case 0:
            break;
        case 1://EVT_NETSEG_MODIFY
            emp_delallempnode();
            sql_readnetseg();
            sql_readalluserip();
            log2_delalllognode();            
            g_interupt = 0;
  //          printf("interupt netseg modify\n");
            return nfq_set_verdict_mark(qh,pktid, result,0,0, NULL); 
        case 7://EVT_SPE_HOST
        case 23://EVT_FILEOUT_MAIL:		
	 case 24://EVT_FILEOUT_BBS:
	 case 29://EVT_FILEOUT_BLOG:
	 case 26://EVT_FILEOUT_NETDISK:
            bwhost_uninit();
            sql_readbwhost();
	       if(g_isblogopen)
		{
			sql_readfiletrans_blog();
		}
		if(g_isnetdiskopen)
		{
			sql_readfiletrans_netdisk();
		}
		if(g_isbbsopen)
		{
			sql_readfiletrans_bbs();
		}
		if(g_ismailopen)
		{
			sql_readfiletrans_mail();
		}	     
            g_interupt = 0;
   //          printf("interupt speweb modify %d\n",bwhost_isopen());
            return nfq_set_verdict_mark(qh,pktid, result,0,0, NULL); 
        default:
            break;
    }
/*    
    char name[5];
    u32 ret = nfq_get_indev_name(struct nlif_handle *  nlif_handle,nfa,name) 
*/
    struct nfqnl_msg_packet_hw*phw = nfq_get_packet_hw(nfa);
        
	struct iphdr* piphdr = (struct iphdr*)(pdata);
    if(unlikely(0==init_pktinfo(indev,piphdr,pdata,payloadlen)))
    {           
         return nfq_set_verdict_mark(qh,pktid, result,0,0, NULL);
    }
  
//  printf("mark=%u make=%u len =%d\n", nfq_get_nfmark(nfa),g_pkt.dir,payloadlen);
//lock ip	
	u16 iphash = GETIPHASH(g_pkt.innerip);
	u8  netseghash = GETNETSEGHASH(g_pkt.innerip);

	if(unlikely((g_pemp = (TEmpnode*)emp_getempnode(iphash,netseghash,g_pkt.innerip)) == NULL))
	{
		result = g_gate;
        return nfq_set_verdict_mark(qh,pktid, result,0,0, NULL);
	}
#if 0
//ip mac bind    
	if(unlikely(g_isipmacbind == 1))//kernel handle it 
    {
            if(g_pkt.dir == DIR_CS)
            {
                    if(0!=memcmp((void*)emp_getempmac(g_pemp),(void*)phw->hw_addr,6))
                    {
			UNLOCK_IP(iphash, netseghash);
			return nfq_set_verdict_mark(qh,pktid,NF_DROP,0,0,NULL);
                    }
            }
    }
#endif
//.....protocol analysis
	u32 proid = analyse_protocol_2(&g_pkt,g_ptm->curtime);
//printf("proid=%d %c %c %c %c \n",proid,g_pkt.payload[0],g_pkt.payload[1],g_pkt.payload[2],g_pkt.payload[3]);
 /*    if(g_pkt.outerport == 20480)
    proid =2;
    else 
        proid = 23;*/
	
	u32 specip = emp_getempspecip(g_pemp);
	if(unlikely(IP_NONE != specip))
	{
		result = specip;
		goto unlock;
	}	
    if(unlikely(proid == 0))
	{
		goto unlock;
	}
//account mode
	if(unlikely(g_sysmode == SYS_ACCOUNT))
	{
		if(SYS_ACCOUNT == emp_getempmode(g_pemp))
		{
			if(g_pkt.dir == DIR_CS)
			{
			//	 u16 dport = __nhs(g_pkt.outerport);
				 if(unlikely(g_pkt.outerport == NET_PORT_80 || g_pkt.outerport == NET_PORT_8080))
				 {
				 	u8 buf[1500];
					sprintf(buf,PKT_CONTENT_REDIRCT,g_localipstr,g_localipstr,"userlogin.php",0,"","");				
					sndpkt_redirecturl(buf,g_pkt.seq,g_pkt.ack,g_pkt.innerip,g_pkt.outerip,g_pkt.innerport,g_pkt.outerport);
				 }
			}
			result = NF_DROP;
			return nfq_set_verdict_mark(qh,pktid, result,0,0, NULL);
		}
		emp_updateactivetime(g_pemp,g_ptm->curtime);
	}

//get policy id	
	polid = emp_getemppolicyid(g_pemp);	
//is policy time open
        if(unlikely(TIMEGATE_OPEN == pol_istimeopen(polid)))
        {
                if(0 == pol_isintimescope(polid))
                {
                      //  polid = 0; 
                      goto unlock;
                }
        }
//get policy  result        
	result = pol_getpropass(polid,proid);
 
//log = pol_isprolog(polid,proid);
	switch(proid)
	{
		case PRO_GET:
              if(likely(DIR_CS == g_pkt.dir))
              {
                    if(pol_getwebcheckflag(polid))
                    {
                        u8* get = NULL;u16 getlen = 0;u8* host = NULL;u16 hostlen = 0;
        				u16 datalen = g_pkt.iplen -g_pkt.headerlen;

        				http_get(pdata+g_pkt.headerlen,datalen, (u8**)&get,&getlen);
        				if(unlikely(getlen == 0))
        				{
        					goto unlock;
        				}
#if 1                        
                        COPY_TO_BUFFER(get,getlen);printf("url:");SHOW_BUFFER;
                        printf("len1=%d\n",datalen-getlen);
#endif                        
        				http_host((pdata+g_pkt.headerlen+getlen),(datalen-getlen),&host,&hostlen);
        				if(unlikely(hostlen == 0))
        				{
        					goto unlock;
        				}
#if 1						
                       COPY_TO_BUFFER(host,hostlen);printf("host:");SHOW_BUFFER;
#endif                        
                        if(unlikely(bwhost_isopen()))
        				{
        					u32 bw = bwhost_isspechost(host,hostlen);					
                            switch(bw)
                            {
                                case 0:
                                    sndpkt_tcp_rst(g_pkt.ack,g_pkt.outerip,g_pkt.innerip,g_pkt.outerport,g_pkt.innerport);
                                    result = bw;
        						    goto unlock;
                                    break;
                                case 1:
                                    result = bw;
        						    goto unlock;
                                    break;
                                default:
                                    break;
                            }
        				/*	if(HOST_SPEC_NONE != bw)//bw host
        					{
        						result = bw;
        						goto unlock;
        					}*/
        				}
        				

#if 0
        			//	COPY_TO_BUFFER(get,getlen);printf("url:");SHOW_BUFFER;
        			//	COPY_TO_BUFFER(host,hostlen);printf("host:");SHOW_BUFFER;
        				printf("filetypeid = %d\n",filetypeid);
#endif
                    u8 filetypeid = filetype_ftanalysis(get,getlen);         
                   printf("filetypeid = %d\n",filetypeid);
    				if(unlikely(filetypeid==0))
    				{
    					if(unlikely(pol_iswebfilter(polid) && result))
    					{
    						result = webcat_handle(polid,get,getlen,host,hostlen,phw->hw_addr);
    					}
    					if(unlikely(pol_iskeywordfilter(polid) && result))
    					{
    						result = keyword_handle(polid,get,getlen,host,hostlen,phw->hw_addr);
    					}
    				}
    				else
    				{
    					if(unlikely(pol_isfiletypefilter(polid) && result))
    					{                            
    						result = filetype_handle(polid,filetypeid,get,getlen,host,hostlen,phw->hw_addr);
    					}
    				}
                }                                                    
             }			
			break;
		case PRO_POST:
            if(likely(DIR_CS == g_pkt.dir))
            {
                u32 i=0;
                u16 datalen = g_pkt.iplen -g_pkt.headerlen;
                u16 soclen = 0;
		printf("postsearch start\n");
                for(i=0;i<g_filechecklen;i++)
                {
                    soclen = g_filecheck[i][0];
                    if(1==post_search(g_pkt.payload,(void*)(&g_filecheck[i][1]),datalen,soclen))
                    {
                        sndpkt_tcp_rst(g_pkt.ack,g_pkt.outerip,g_pkt.innerip,g_pkt.outerport,g_pkt.innerport);
                        result = 0;
                        goto unlock;
                    }
                }
		printf("postsearch ok\n");
                if(pol_ispostaudit(polid))
    	        {
        			if(likely(result))
        			{
                        post_handle((u8*)(pdata+g_pkt.headerlen),datalen,phw->hw_addr);	                        
        			}
                }
            }
			break;
		case PRO_SMTP:
            if(likely(DIR_CS == g_pkt.dir))
            {
                    if(pol_issmtpaudit(polid) &&  result)
                    {
                        u16 datalen = g_pkt.iplen-g_pkt.headerlen;
                        smtp_handle((u8*)(pdata+g_pkt.headerlen),datalen,phw->hw_addr);
                    }	
            }
			break;
		case PRO_POP3:
            if(likely(DIR_SC == g_pkt.dir))
			{
	        		if(pol_ispop3audit(polid) && result)
	        		{
		                u16 datalen = g_pkt.iplen-g_pkt.headerlen;
	                    pop3_handle((u8*)(pdata+g_pkt.headerlen),datalen);
				    }
			}
			break;
		default:
			break;
	}
unlock:
    lognode = log2_getlognode(iphash,netseghash,proid,g_pkt.innerip);
	if(unlikely(lognode != NULL))
	{		
      //  if(pol_isprolog(polid,proid))
        {                        
        	if(result== PKT_BLOCK)
        	{
        		lognode->log.stablocknum++;
                return nfq_set_verdict_mark(qh,pktid, result,proid,0, NULL);
           	}
        	else
        	{
        		lognode->log.stapassnum++;
        	}
            if(g_pkt.dir == DIR_CS)
        	{
        		lognode->log.staup += payloadlen;
                lognode->log.insup += payloadlen;
        	}
        	else
        	{
        		lognode->log.stadown += payloadlen;
                lognode->log.insdown += payloadlen;
        	}
        }
     /*   if(result== PKT_BLOCK)
    	{
            return nfq_set_verdict_mark(qh,pktid, result,proid,0, NULL);
       	}*/

	}
    else
    {
        log2_addlognode(iphash,netseghash,proid,g_pkt.innerip);
    }
	printf("new start\n");
	return nfq_set_verdict_mark(qh,pktid, result,g_protype[proid],0, NULL);
}
int main(int argc, char **argv)
{
	struct nfq_handle *h;
	struct nfq_q_handle *qh;
	struct nfnl_handle *nh;
	int fd;
	int rv;
#define RCV_BUF_SIZE 4096	
	char buf[RCV_BUF_SIZE] __attribute__ ((aligned));

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

	init();
	DEBUG(D_INFO)("opening library handle\n");
	h = nfq_open();
	if (!h)
	{
		DEBUG(D_FATAL)( "error during nfq_open()\n");
		exit(1);
	}

	if (nfq_unbind_pf(h, AF_INET) < 0)
	{
		DEBUG(D_FATAL)( "error during nfq_unbind_pf()\n");
		exit(1);
	}

	if (nfq_bind_pf(h, AF_INET) < 0)
	{
		DEBUG(D_FATAL)( "error during nfq_bind_pf()\n");
		exit(1);
	}

	qh = nfq_create_queue(h,  0, &cb, NULL);
	if (!qh)
	{
		DEBUG(D_FATAL)("error during nfq_create_queue()\n");
		exit(1);
	}

	if (nfq_set_mode(qh, NFQNL_COPY_PACKET, 0xffff) < 0)
	{
		DEBUG(D_FATAL)("can't set packet_copy mode\n");
		exit(1);
	}
	nh = nfq_nfnlh(h);
	fd = nfnl_fd(nh);

	while(1)
	{
		while(likely((rv = recv(fd, buf,RCV_BUF_SIZE,0))>= 0))
		{
			nfq_handle_packet(h, buf, rv);
		}
//		DEBUG(D_FATAL)("ub\n");
	}
	nfq_destroy_queue(qh);

#ifdef INSANE
	/* normally, applications SHOULD NOT issue this command, since
	 * it detaches other programs/sockets from AF_INET, too ! */
	DEBUG(D_FATAL)("unbinding from AF_INET\n");
	nfq_unbind_pf(h, AF_INET);
#endif
	uninit();
	nfq_close(h);
	exit(0);
}

