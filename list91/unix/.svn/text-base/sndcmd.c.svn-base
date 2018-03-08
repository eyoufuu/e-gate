#include <stdio.h>
#include <sys/socket.h>
#include <sys/un.h>
#include <unistd.h>

#define SET_PKT_LEN(x,len) 			(*(u32*)(x)) = len
#define SET_PKT_VER_MAJ(x,num) 	(*(u8*)(x+4)) = num
#define SET_PKT_VER_MIN(x,num) 	(*(u8*)(x+5)) = num
#define SET_PKT_VER_CMD(x,com) 	(*(u16*)(x+6)) = com
#define SET_PKT_VON_ID(x,id) 		(*(u8*)(x+8)) = id
#define SET_PKT_PRO_ID(x,id) 		(*(u8*)(x+9)) = id
#define SET_PKT_RES(x,num) 		(*(u32*)(x+10)) = num

#define UNIXDG_PATH "/serv"
#define MAXLINE 2048

typedef unsigned int u32;
typedef unsigned short u16;
typedef unsigned char u8;

int g_clisockfd = 0; //client sock
struct sockaddr_un g_servaddr;
u32 g_servaddrlen = sizeof(g_servaddr);//server addr
u32 i=0;
static inline void setpkthead(u8* buf,u32 len,u16 cmd)
{
	SET_PKT_LEN(buf,len);
	SET_PKT_VER_CMD(buf,cmd);
}

static inline void initpkthead(u8* buf)
{
//	SET_PKT_LEN(g_sendpkthead,len);
	SET_PKT_VER_MAJ(buf,1);
	SET_PKT_VER_MIN(buf,0);
//	SET_PKT_VER_CMD(g_sendpkthead,cmd);
	SET_PKT_VON_ID(buf,1);
	SET_PKT_PRO_ID(buf,0);
	SET_PKT_RES(buf,0);
}
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
int main(int argc,char** argv)
{
	cli_init();
	u8 buf[1000]="";
	u32 cmd = 0;
	u32 data = 0;
	u32 len = 0;
	switch(argc)
	{
		case 2:
			cmd = atoi(argv[1]);
			len =18;
			break;
		case 3:
			cmd = atoi(argv[1]);
			data = atoi(argv[2]);
			len = 22;
			break;
		default:
			return 0;
	}
	initpkthead(buf);
	setpkthead(buf,len,cmd);
	*((u32*)buf) = data;
	cli_sendto(buf,len);
#if 0
	while(1)
	{
		int ret = recvfrom(g_clisockfd,buf,1000,0,0,0);
		buf[ret] = '\0';
		printf("%s i=%d\n",buf,i);
	//	i++;
	//	 cli_sendto(buf,20);
	}
#endif	
}
