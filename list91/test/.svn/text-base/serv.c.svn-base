/*File: serv.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
 */
#include <stdio.h>
#include <sys/socket.h>
#include <sys/un.h>
#include <unistd.h>
#include <signal.h>
#include <stdlib.h>

#define UNIXDG_PATH "/home/fy/serv"
#define UNIXDG_MAXLINE 2048

#define MAXRCVBUF 200*1500
typedef unsigned int u32;
typedef unsigned short u16;
typedef unsigned char u8;

int g_servsockfd = 0;
struct sockaddr_un g_cliaddr;
u32 g_clilen = sizeof(g_cliaddr);
u32 i=0;
void serv_parsecmd(struct sockaddr* pcliaddr,socklen_t clilen,const u8* pkt);

void serv_sendto(const u8* buf,u32 len)
{
int ret;
	if(NULL != buf)
	{
		int ret =sendto(g_servsockfd,buf,len,MSG_DONTWAIT,(const struct sockaddr*)&g_cliaddr,g_clilen);
		i++;
		printf("i=%d\n",i);
	}
}
static void dg_echo(int sockfd,struct sockaddr* pcliaddr,socklen_t clilen)
{
        int rcvbuf;
        int len;

        char msg[UNIXDG_MAXLINE];

        rcvbuf = MAXRCVBUF;
   //     setsockopt(sockfd,SOL_SOCKET,SO_RCVBUF,&rcvbuf,sizeof(rcvbuf));
        while(1)
        {
                len = recvfrom(sockfd,msg,UNIXDG_MAXLINE,0,pcliaddr,&clilen);
			msg[len]='\0';
			printf("%d,%s\n",clilen,msg);
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
		printf("socket error\n");
	}	
	unlink(UNIXDG_PATH);
	memset(&servaddr,0, sizeof(servaddr));
	servaddr.sun_family = AF_LOCAL;
	strcpy(servaddr.sun_path,UNIXDG_PATH);
	if(bind(g_servsockfd,(struct sockaddr*)&servaddr,sizeof(servaddr))<0)
	{
		printf("bind error\n");
	//	printf("bind error\n");
	}	
	dg_echo(g_servsockfd, (struct sockaddr*)&cliaddr,sizeof(cliaddr));
}
void serv_parsecmd(struct sockaddr* pcliaddr,socklen_t clilen,const u8* pkt)
{
#define PKT_LEN(x) 				(*(u32*)(x))
#define PKT_CMD(x) 				(*(u16*)(x+6))
	u32 len = PKT_LEN(pkt);
	u16 cmd = PKT_CMD(pkt);
	switch(cmd)
	{
		
		case 1:
			memcpy(&g_cliaddr,pcliaddr,clilen);
			g_clilen = clilen;
			break;
		
		default:
			break;
	}
	memcpy(&g_cliaddr,pcliaddr,clilen);
	g_clilen = clilen;
	printf("ok!\n");

}



////////////////

#include <pthread.h>
void* thradmin(void* para)
{
	char buf[1000];
	u32 i=0;
	while(1)
	{
		i++;
		sprintf(buf,"%u",i);
		printf("i=%u\n",i);
		serv_sendto(buf,strlen(buf));
	}
}
int main()
{
	pthread_t admin;

	int err = pthread_create(&admin,NULL,thradmin,NULL);
	serv_init();
}
