/*File: serv.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
 */
#include <stdio.h>
#include <sys/socket.h>
#include <sys/un.h>
#include <unistd.h>
#include <signal.h>
#include "serv.h"
#include "ctl.h"
#include "emp.h"
#include "log2.h"
#define MAXRCVBUF 200*1500

int g_servsockfd = 0;
struct sockaddr_un g_cliaddr;
u32 g_clilen = sizeof(g_cliaddr);

void serv_parsecmd(struct sockaddr* pcliaddr,socklen_t clilen,const u8* pkt);

void serv_sendto(const u8* buf,u32 len)
{
	if(NULL != buf)
	{
		int ret =sendto(g_servsockfd,buf,len,MSG_DONTWAIT,(const struct sockaddr*)&g_cliaddr,g_clilen);
//		if(ret != len)
//			DEBUG(D_WARNING)("send error\n");
	}
}
static void dg_echo(int sockfd,struct sockaddr* pcliaddr,socklen_t clilen)
{
        int rcvbuf;
        int len;
        char msg[UNIXDG_MAXLINE];

        rcvbuf = MAXRCVBUF;
        setsockopt(sockfd,SOL_SOCKET,SO_RCVBUF,&rcvbuf,sizeof(rcvbuf));
        while(1)
        {
                len = recvfrom(sockfd,msg,UNIXDG_MAXLINE,0,pcliaddr,&clilen);
		serv_parsecmd(pcliaddr,clilen,msg);
        }
}
int unix_serv_recv(int sockfd,char* msg,struct sockaddr* pcliaddr,socklen_t* clilen)
{
	retrun recvfrom(sockfd,msg,UNIXDG_MAXLINE,0,pcliaddr,clilen);
}
void unix_serv_init()
{
	int rcvbuf;        
        rcvbuf = MAXRCVBUF;
	struct sockaddr_un servaddr,cliaddr;
	g_servsockfd = socket(AF_LOCAL,SOCK_DGRAM,0);
	if(g_servsockfd<0)
	{
		printf("socket error\n");
	}	
	unlink(UNIXDG_PATH);
	memset(&servaddr,0, sizeof(servaddr));
	servaddr.sun_family = AF_LOCAL;
	strcpy(servaddr.sun_path,UNIXDG_PATH);
	if(bind(g_servsockfd,(struct sockaddr*)&servaddr,sizeof(servaddr))<0)
	{
		printf("bind error\n");
	}
        setsockopt(sockfd,SOL_SOCKET,SO_RCVBUF,&rcvbuf,sizeof(rcvbuf));
}
void serv_parsecmd(struct sockaddr* pcliaddr,socklen_t clilen,const u8* pkt)
{
	u32 len = PKT_LEN(pkt);
	u16 cmd = PKT_CMD(pkt);
	switch(cmd)
	{
		case EVT_IP_MODIFY:
			sql_readalluserip();
			break;
		case EVT_POL_MODIFY:
		{
			u32 polid = PKT_PAYLOAD_PARA1(pkt);
			sql_readpolicy(polid);
		}
			break;
		case EVT_GBL_PROLOG:
		{
			u32 flag = PKT_PAYLOAD_PARA1(pkt);
			log2_setintervaltime(flag);
		}
			break;
		case EVT_GBL_TIME:
			sql_readglobalpara();
			break;
		case EVT_GBL_MODE:
		{
			u32 mode = PKT_PAYLOAD_PARA1(pkt);
			if(mode != g_sysmode)
			{
				u32 i = 0;
				if(SYS_ACCOUNT == mode)
				{
					acc_initaccount();//init acc
					sql_readalluseraccount();// init acc
				}
				g_sysmode = mode;				
				sql_readnetseg();
				sql_readalluserip();					
			}
		}			
			break;
		case EVT_CARD_MODIFY:
			if_initdevif();
			sql_readcardtype();
			if_setwanlanindex();
			break;
		case EVT_INS_SUM:
		{
			u32 flag = PKT_PAYLOAD_PARA1(pkt);
			g_inssum = EVT_INS_SUM;			
		}
			break;
		case EVT_INS_PRO:
		{
			u32 flag = PKT_PAYLOAD_PARA1(pkt);
			u32 ip    = PKT_PAYLOAD_PARA2(pkt);
			if(INS_TRA_OPEN==flag)
			{
				g_instraffic = flag;
				g_insmode = INS_TRA_MODE_PRO;
				log2_createinsthread(ip);
			}
			else
			{
				g_instraffic = INS_TRA_CLOSE;
			}			
		}
			break;
		case EVT_INS_IP:
		{
			u32 flag = PKT_PAYLOAD_PARA1(pkt);
			if(INS_TRA_OPEN == flag)
			{
				g_instraffic = INS_TRA_OPEN;
				g_insmode = INS_TRA_MODE_IP;
				log2_createinsthread(0);
			}
			else
			{
				g_instraffic = INS_TRA_CLOSE;
			}
		}
			break;		
		case EVT_ACCOUNT_LOGIN: 
		{
			u32 accid = PKT_PAYLOAD_PARA1(pkt);
			
			if(g_account[accid]!=NULL)
			{
				u32 hostip = PKT_PAYLOAD_PARA2(pkt);
				u32 netip = __hnl(hostip);
				u16 hash = GETIPHASH(netip);
				u8   netseghash = GETNETSEGHASH(netip);
				TEmployee emp;
				
				emp.accid = accid;
				emp.activetime = g_ptm->curtime;
				emp.groupid =g_account[accid]->groupid;
				emp.personid = g_account[accid]->personid;
				emp.mode = SYS_LONGIN;
				emp.policyid = g_account[accid]->policyid;
                               	emp.ip = netip;
                		emp.specip = IP_NONE;
			
				g_account[accid]->usedip = netip;
				
				TEmpnode* pem = emp_getempnode(hash,netseghash,netip);
				if(pem != NULL)
				{
					emp_updateempnode(hash,netseghash,&emp);
				}
			}
			
		}
			break;
		case EVT_ACCOUNT_MODIFY:
			{
				u32 accid = PKT_PAYLOAD_PARA1(pkt);
				g_account[accid] = 0;
				sql_readuseaccount(accid);
			}
			break;
		case EVT_CLI_REGISTER:
			memcpy(&g_cliaddr,pcliaddr,clilen);
			g_clilen = clilen;
            		DEBUG(D_INFO)("reg init\n");
			break;
		case EVT_NETSEG_MODIFY:
		{	
			sql_readnetseg();
			sql_readalluserip();
		}
			break;
		case EVT_PRO_FEATHER:
		{
			u32 proid = PKT_PAYLOAD_PARA1(pkt);
			u32 type =  PKT_PAYLOAD_PARA2(pkt);
			void* ptype = (void*)(pkt+18);			
		}
			break;
		default:
			break;
	}
}



////////////////
/*
for test
#include <pthread.h>
void* thradmin(void* para)
{
	char buf[1000]="hello";
	while(1)
	{
		serv_sendto(buf,5);
	}
}
int main()
{
	pthread_t admin;
	int err = pthread_create(&admin,NULL,thradmin,NULL);
	serv_init();
}*/
