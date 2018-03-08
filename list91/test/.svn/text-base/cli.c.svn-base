#include <stdio.h>
#include <sys/socket.h>
#include <sys/un.h>
#include <unistd.h>

#define UNIXDG_PATH "./serv"
#define MAXLINE 2048
typedef unsigned int u32;
typedef unsigned short u16;
typedef unsigned char u8;
int g_clisockfd = 0; //client sock
struct sockaddr_un g_servaddr;
u32 g_servaddrlen = sizeof(g_servaddr);//server addr
u32 i=0;
void cli_sendto(const u8* buf,u32 len)
{
	int ret =sendto(g_clisockfd,buf,len,0,(const struct sockaddr*)&g_servaddr,g_servaddrlen);
	if(ret != len)
	{
		printf("send error\n");
	}
}
void cli_init()
{
	struct sockaddr_un cliaddr;
	g_clisockfd = socket(AF_LOCAL,SOCK_DGRAM,0);
	if(g_clisockfd<0)
	{
		printf("socket error\n");
	}
	memset(&cliaddr,0,sizeof(cliaddr));
	cliaddr.sun_family = AF_LOCAL;
	strcpy(cliaddr.sun_path,tmpnam(NULL));
	if(bind(g_clisockfd,(struct sockaddr*)&cliaddr,sizeof(cliaddr))<0)
	{
		printf("bind error");
	}
	memset(&g_servaddr,0,sizeof(g_servaddr));
	g_servaddr.sun_family = AF_LOCAL;
	strcpy(g_servaddr.sun_path,UNIXDG_PATH);
	g_servaddrlen = sizeof(g_servaddr);
}

/////////////////////////////
int main()
{
	cli_init();
	char buf[1000]="good";
	sleep(3);
	 cli_sendto(buf,4);
	while(1)
	{
		int ret = recvfrom(g_clisockfd,buf,1000,0,0,0);
		buf[ret] = '\0';
		printf("%s i=%d\n",buf,i);
	//	i++;
	//	 cli_sendto(buf,20);
	}	
}
