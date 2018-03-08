#include <stdio.h>
#include <sys/socket.h>
#include <sys/un.h>
#include <unistd.h>
#include <errno.h>
#define UNIXDG_PATH "/serv"
#define MAXLINE 2048
typedef unsigned int u32;
typedef unsigned short u16;
typedef unsigned char u8;
int g_clisockfd = 0; //client sock
struct sockaddr_un g_servaddr;
u32 g_servaddrlen = sizeof(g_servaddr);//server addr
u32 i=0;
char g_buf[1000];
void initpkthead(u8* buf)
{
#define SET_PKT_B1(x,data) 		(*(u8*)(x)) = data
#define SET_PKT_B2(x,data) 	    (*(u8*)(x+1)) = data
#define SET_PKT_LEN(x,len) 	    (*(u16*)(x+2)) = len
#define SET_PKT_MAJ(x,maj) 	    (*(u8*)(x+4)) = maj
#define SET_PKT_MIN(x,min) 		(*(u8*)(x+5)) = min
#define SET_PKT_CMD(x,id) 		(*(u16*)(x+6)) = id

    SET_PKT_B1(buf,0);
    SET_PKT_B2(buf,1);
    SET_PKT_LEN(buf,0);
    SET_PKT_MAJ(buf,1);
    SET_PKT_MIN(buf,0);
    SET_PKT_CMD(buf,0);
}
inline void setpkthead(u8* buf,u32 len,u16 cmd)
{
	SET_PKT_LEN(buf,len);
	SET_PKT_CMD(buf,cmd);
}
void cli_sendto(const u8* buf,u32 len)
{
	int ret =sendto(g_clisockfd,buf,len,0,(const struct sockaddr*)&g_servaddr,g_servaddrlen);
	if(ret != len)
	{
		printf("send error ret = %d len=%d errno = %s\n",ret,len,strerror(errno));
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
//sndcmd cmd [ip] [parm]
int main(int argc, char **argv)
{
	int cmd = 0;
	u32 ip = 0;
    u32 param1 = 0;
    u32 param2 = 0;
	int len = 0;
	switch(argc)
	{
		case 2:
			cmd = atoi(argv[1]);
			len = 8;
			break;
		case 3:
			cmd = atoi(argv[1]);
			ip = strtoul(argv[2],NULL,10);
			memcpy(g_buf+8,&ip,4);
			len = 12;
			break;
        case 4:
            cmd = atoi(argv[1]);
			ip = strtoul(argv[2],NULL,10);
			memcpy(g_buf+8,&ip,4);
            param1 = strtoul(argv[3],NULL,10);
            memcpy(g_buf+12,&param1,4);
            len =16;
            break;
        case 5:
            cmd = atoi(argv[1]);
			ip = strtoul(argv[2],NULL,10);
			memcpy(g_buf+8,&ip,4);
            param1 = strtoul(argv[3],NULL,10);
            memcpy(g_buf+12,&param1,4);
            param2 = strtoul(argv[4],NULL,10);
            memcpy(g_buf+16,&param2,4);
            len =20;
            break;
		default:
			 printf("param num uncorrect!\n");
			return;
	}
	initpkthead(g_buf);
	cli_init();
	setpkthead(g_buf,len,cmd);
    cli_sendto(g_buf,len);
/*	while(1)
	{
		int ret = recvfrom(g_clisockfd,buf,1000,0,0,0);
		buf[ret] = '\0';
		printf("%s i=%d\n",buf,i);
	//	i++;
	//	 cli_sendto(buf,20);
	}*/	
}
