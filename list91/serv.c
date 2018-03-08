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
#include "imp2.h"
#define MAXRCVBUF 2000*1500

int g_servsockfd = 0;
struct sockaddr_un g_cliaddr;
u32 g_clilen = sizeof(g_cliaddr);

void serv_parsecmd(struct sockaddr* pcliaddr,socklen_t clilen,const u8* pkt);

void serv_sendto(const u8* buf,u32 len)
{
	if(NULL != buf)
	{
		int ret =sendto(g_servsockfd,buf,len,MSG_DONTWAIT,(const struct sockaddr*)&g_cliaddr,g_clilen);
//        int ret =sendto(g_servsockfd,buf,len,0,(const struct sockaddr*)&g_cliaddr,g_clilen);
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
void serv_init()
{
	struct sockaddr_un servaddr,cliaddr;
	g_servsockfd = socket(AF_LOCAL,SOCK_DGRAM,0);
	if(g_servsockfd<0)
	{
	//	printf("socket error\n");
		DEBUG(D_FATAL)("socket error\n");
	}	
	unlink(UNIXDG_PATH);
	memset(&servaddr,0, sizeof(servaddr));
	servaddr.sun_family = AF_LOCAL;
	strcpy(servaddr.sun_path,UNIXDG_PATH);
	if(bind(g_servsockfd,(struct sockaddr*)&servaddr,sizeof(servaddr))<0)
	{
		DEBUG(D_FATAL)("bind error\n");
	//	printf("bind error\n");
	}	
	dg_echo(g_servsockfd, (struct sockaddr*)&cliaddr,sizeof(cliaddr));
}
void serv_parsecmd(struct sockaddr* pcliaddr,socklen_t clilen,const u8* pkt)
{
#define PKT_PAYLOAD_PARA1(x) (*(u32*)(x+8))
#define PKT_PAYLOAD_PARA2(x) (*(u32*)(x+12))
#define PKT_PAYLOAD_PARA3(x) (*(u32*)(x+16))
	u32 len = PKT_LEN(pkt);
	u16 cmd = PKT_CMD(pkt);
    switch(cmd)
	{
        case EVT_NETSEG_MODIFY: //need to lock added here
        case EVT_SPE_HOST:       
		{	
            printf("cmd rcv EVT_SPE_HOST/EVT_NETSEG_MODIFY\n");
            g_interupt = cmd;            
		}
			break;
        case EVT_SPE_IP:
            printf("cmd rcv EVT_SPE_IP\n");
            emp_resetempspecip();
            sql_readbwip();
            break;        
        case EVT_GBL_LOGINMODE:
            sql_readglobalpara();
            emp_resetemplogout(g_sysmode);            
            break;
        case EVT_GBL_MODIFY:
	 case  EVT_GBL_REMINDPAGE:
            sql_readglobalpara();
            break;
	case EVT_IP_MODIFY:
			sql_readalluserip();
            printf("cmd rcv EVT_IP_MODIFY\n");
			break;
		case EVT_POL_MODIFY:
            if(len == 12)
    		{
    			u32 polid = PKT_PAYLOAD_PARA1(pkt);
                pol_reset(polid);
    			sql_readpolicy(polid);
                printf("cmd rcv EVT_POL_MODIFY\n");
    		}
			break;		
	/*	case EVT_CARD_MODIFY:
			if_initdevif();
			sql_readcardtype();
			if_setwanlanindex();
			break;*/
		case EVT_ACCOUNT_LOGIN: 
            if(len == 20)
    		{
    			u32 oper = PKT_PAYLOAD_PARA3(pkt);
                u32 polid = PKT_PAYLOAD_PARA2(pkt);
                u32 ip = PKT_PAYLOAD_PARA1(pkt);
                if(oper == 0)
                {
                    u32 netip = __hnl(ip);
    				u16 hash = GETIPHASH(netip);
    				u8  netseghash = GETNETSEGHASH(netip);
    			
                    TEmpnode* pem = emp_getempnode(hash,netseghash,netip);
    				if(pem != NULL)
    				{
                        pem->emp.mode = SYS_ACCOUNT;
    				}
                }
                else if(oper == 1)
                {
                    u32 netip = __hnl(ip);
    				u16 hash = GETIPHASH(netip);
    				u8  netseghash = GETNETSEGHASH(netip);
    			
                    TEmpnode* pem = emp_getempnode(hash,netseghash,netip);
    				if(pem != NULL)
    				{
                        pem->emp.mode = SYS_LONGIN;
    				}
                }
    			printf("cmd rcv EVT_ACCOUNT_LOGIN\n");
    		/*	if(g_account[accid]!=NULL)
    			{
    				u32 hostip = PKT_PAYLOAD_PARA2(pkt);
    				u32 netip = __hnl(hostip);
    				u16 hash = GETIPHASH(netip);
    				u8   netseghash = GETNETSEGHASH(netip);
    				TEmployee emp;
    				
    				emp.accid = accid;
    				emp.activetime = g_ptm->curtime;
    				//emp.groupid =g_account[accid]->groupid;
    				//emp.personid = g_account[accid]->personid;
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
    			}*/			
    		}
			break;
		case EVT_CLI_REGISTER:
			memcpy(&g_cliaddr,pcliaddr,clilen);
			g_clilen = clilen;
            printf("cmd rcv EVT_CLI_REGISTER\n");
			break;		
		case EVT_PRO_FEATHER:
		{
		/*	u32 proid = PKT_PAYLOAD_PARA1(pkt);
			u32 type =  PKT_PAYLOAD_PARA2(pkt);
			void* ptype = (void*)(pkt+18);
            printf("cmd rcv EVT_PRO_FEATHER\n");*/
		}
			break;
		case EVT_IPMAC:
		{
            printf("cmd rcv EVT_IPMAC\n");
			sql_readglobalpara();
#if 1
			if(g_isipmacbind)
			{
				u8 buf[4];
			//	buf[0] = 0;
			//	SendKernelMessage((char*) buf,0,IMP2_IPMAC_FLAG,1);
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
		}
			break;
        case EVT_SYS_IP_CHANGE:
            {
                g_localip = if_getlocalip("eth2");
            	struct in_addr inaddr;
            	inaddr.s_addr = g_localip;
            	strcpy(g_localipstr,inet_ntoa(inaddr));
                printf("cmd rcv EVT_SYS_IP_CHANGE\n");
            }
		break;
	case EVT_FILEOUT_FILETYPE:
		sql_readglobalpara();
		if(g_isfiletypeopen)
		{
			sql_readfiletype();
		}
		 printf("cmd rcv EVT_FILEOUT_FILETYPE\n");
		break;
	case EVT_FILEOUT_MAIL:		
	case EVT_FILEOUT_BBS:
	case EVT_FILEOUT_BLOG:
	case EVT_FILEOUT_NETDISK:
		{	
            printf("cmd rcv EVT_FILEOUt_HOST\n");
	     sql_readglobalpara();		
            g_interupt = cmd;            
		}
		break;
	case EVT_FILEOUT_IM:
		 printf("cmd rcv EVT_FILEOUT_IM\n");
		 sql_readglobalpara();
		 if(g_isimopen)
		{
			sql_readfiletrans_im();
		}
		else
		{
			sql_readallpolicy();
		}
		break;

	case EVT_FILEOUT_FTP:
		 printf("cmd rcv EVT_FILEOUT_FTP\n");
		 sql_readglobalpara();
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
		break;
	case EVT_FILEOUT_TFTP:
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
