/*File: if.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
 */
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/ioctl.h>
#include <sys/socket.h>
#include <sys/errno.h>
#include <net/if.h>
#include <netinet/in.h>
#include "global.h"

u32 if_getlocalip(const char* name)
{
	u32 i,count=0;

	u32 sock; 
	struct sockaddr_in sin; 
	struct ifreq ifr;
	sock = socket(AF_INET, SOCK_DGRAM, 0); 
	if(sock>=0)//!<0
	{
		sprintf(ifr.ifr_name,"%s",name);
		if(ioctl(sock,SIOCGIFADDR,&ifr)<0)
		{
			close(sock);
			return 0;
		}
		memcpy(&sin, &ifr.ifr_addr, sizeof(sin));
		close(sock);
		return sin.sin_addr.s_addr;
	}
	return 0;
}
u32 if_getlocalnm(const char* name)
{
        u32 i,count=0;

        u32 sock;
        struct sockaddr_in sin;
        struct ifreq ifr;
        sock = socket(AF_INET, SOCK_DGRAM, 0);
        if(sock>=0)//!<0
        {
                sprintf(ifr.ifr_name,"%s",name);
                if(ioctl(sock,SIOCGIFNETMASK,&ifr)<0)
                {
                        close(sock);
                        return 0;
                }
                memcpy(&sin, &ifr.ifr_addr, sizeof(sin));
                close(sock);
                return sin.sin_addr.s_addr;
        }
        return 0;
}
void if_getlocalmac(const char* name,char* buf)
{
        int i, sockfd;
        static struct ifreq req;
        buf[0] = '\0';
        char tmp[5]="";
//      char *strmac="00:19:DB:E8:06:1A";
        sockfd = socket(PF_INET, SOCK_DGRAM, 0);
        if(sockfd<0)
        {
                perror("Unable to create socket:");
                return;
        }
        strcpy(req.ifr_name,name);
        ioctl(sockfd, SIOCGIFHWADDR, &req);
 //     printf("MAC address of %s:\t", req.ifr_name);
//      sprintf(buf, "%02X:", req.ifr_hwaddr.sa_data[0]&0xFF);
        for(i=0;i<5;i++)
        {
                sprintf(tmp,"%02X:",req.ifr_hwaddr.sa_data[i]&0xFF);
                strcat(buf,tmp);
        }
        sprintf(tmp,"%02X",req.ifr_hwaddr.sa_data[i]&0xFF);
        strcat(buf,tmp);
        close(sockfd);
//      printf("%s\n",buf);
}

#include <errno.h>
#include <libnfnetlink/libnfnetlink.h>

typedef struct _TInterface{
	u32 id;
	u8 name[8];
	u8 mac[20];
	u32 run; //0 not running 1 running
	u32 type;//wan or lan or other
}TInterface;

TInterface g_if[MAX_DEVNUM];
void if_setwanlanindex()
{
	u32 i = 0;
	for(i=0;i<MAX_DEVNUM;i++)
	{
		if(g_if[i].type == DEV_IF_LAN)
		{
			g_lanindex = g_if[i].id;			
		}
		if(g_if[i].type == DEV_IF_WAN)
		{
			g_wanindex = g_if[i].id;
		}
	}
}
void if_setphydevtype(const char* name,int type)
{
	if(strlen(name)<4)
	{
		DEBUG(D_FATAL)("input error\n");
		return;
	}
	switch(name[3])
	{
		case '0':
			g_if[0].type = type;
			break;
		case '1':
			g_if[1].type = type;
			break;
		case '2':
			g_if[2].type = type;
			break;			
		case '3':
			g_if[3].type = type;			
			break;
		default:
			DEBUG(D_FATAL)("input error\n");
			break;			
	}
}
int if_getphydevindex(const char* name)
{
	if(strlen(name)<4)
	{
		DEBUG(D_FATAL)("input error\n");
		return -1;
	}
	switch(name[3])
	{
		case '0':
			return g_if[0].run;
		case '1':
			return g_if[1].run;
		case '2':
			return g_if[2].run;
		case '3':
			return g_if[3].run;
		default:
			DEBUG(D_FATAL)("input error\n");
			return -1;			
	}
}
int if_getphydevrunning(const char* name)
{
	if(strlen(name)<4)
	{
		DEBUG(D_FATAL)("input error\n");
		return -1;
	}
	switch(name[3])
	{
		case '0':
			return g_if[0].id;
		case '1':
			return g_if[1].id;
		case '2':
			return g_if[2].id;
		case '3':
			return g_if[3].id;
		default:
			DEBUG(D_FATAL)("input error\n");
			return -1;			
	}
}
void if_initdevif()
{
    u32 i;
    struct nlif_handle *h;

    h = nlif_open();
    if (h == NULL)
	{
        perror("nlif_open");
        exit(EXIT_FAILURE);
    }
    nlif_query(h);
	for(i=0;i<20;i++)
	{
        u8 name[IFNAMSIZ];
        unsigned int flags;

        if (nlif_index2name(h, i, name) == -1)
            continue;
        if (nlif_get_ifflags(h, i, &flags) == -1)
            continue;

		if(!strcmp(name,DEV_ETH0))
		{
			g_if[0].id = i;
			strcpy(g_if[0].name,DEV_ETH0);
			g_if[0].run = flags & IFF_RUNNING;
			g_if[0].type = DEV_IF_UNKNOW;
			if_getlocalmac(DEV_ETH0, g_if[0].mac);
		}
		if(!strcmp(name,DEV_ETH1))
		{
			g_if[1].id = i;
			strcpy(g_if[1].name,DEV_ETH1);
			g_if[1].run = flags & IFF_RUNNING;
			g_if[1].type = DEV_IF_UNKNOW;
			if_getlocalmac(DEV_ETH1, g_if[1].mac);
		}
		if(!strcmp(name,DEV_ETH2))
		{
			g_if[2].id = i;
			strcpy(g_if[2].name,DEV_ETH2);
			g_if[2].run = flags & IFF_RUNNING;
			g_if[2].type = DEV_IF_UNKNOW;
			if_getlocalmac(DEV_ETH2, g_if[2].mac);
		}
		if(!strcmp(name,DEV_ETH3))
		{
			g_if[3].id = i;
			strcpy(g_if[3].name,DEV_ETH3);
			g_if[3].run = flags & IFF_RUNNING;
			g_if[3].type = DEV_IF_UNKNOW;
			if_getlocalmac(DEV_ETH3, g_if[3].mac);
		}
    }
	nlif_close(h);
}
