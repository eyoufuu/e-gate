#include <stdio.h>
#include <sys/socket.h>
#include <sys/un.h>
#include <unistd.h>
#include "cli.h"


int g_clisockfd = 0; //client sock
struct sockaddr_un g_servaddr;
u32 g_servaddrlen = sizeof(g_servaddr);//server addr

void cli_sendto(const u8* buf,u32 len)
{
	int ret =sendto(g_clisockfd,buf,len,0,(const struct sockaddr*)&g_servaddr,g_servaddrlen);
	if(ret != len)
	{
		DEBUG(D_FATAL)("send error\n");
	}
}
void cli_init()
{
	struct sockaddr_un cliaddr;
	g_clisockfd = socket(AF_LOCAL,SOCK_DGRAM,0);
	if(g_clisockfd<0)
	{
		DEBUG(D_FATAL)("socket error\n");
	}
	memset(&cliaddr,0,sizeof(cliaddr));
	cliaddr.sun_family = AF_LOCAL;
	strcpy(cliaddr.sun_path,"tempfile");
	if(bind(g_clisockfd,(struct sockaddr*)&cliaddr,sizeof(cliaddr))<0)
	{
		DEBUG(D_FATAL)("bind error");
	}
	memset(&g_servaddr,0,sizeof(g_servaddr));
	g_servaddr.sun_family = AF_LOCAL;
	strcpy(g_servaddr.sun_path,UNIXDG_PATH);
	g_servaddrlen = sizeof(g_servaddr);
}
/*
for test
/////////////////////////////
int main()
{
	cli_init();
	char* buf[1000];
	cli_initpkthead(buf,18,21,0);
	 cli_sendto(buf,20);
	while(1)
	{
		int ret = recvfrom(g_clisockfd,buf,1000,0,0,0);
		buf[ret] = '\0';
		printf("%s\n",buf);
		 cli_sendto(buf,20);
	}	
}*/
