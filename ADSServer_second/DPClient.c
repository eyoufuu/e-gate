#include "packetdefine.h"
#include "DPClient.h"
#include <sys/un.h>
#include <sys/socket.h>
#include <sys/types.h>
#include <sys/un.h>
#include <unistd.h>
#include <stdlib.h>
#include "FileLog.h"


static int sockfd;		//定义连接句柄
static struct sockaddr_un servaddr;	//定义服务器端地址结构
static struct sockaddr_un clientaddr;	 //定义客户端地址结构

u_int32 dpclientInit()
{
	sockfd = socket(AF_LOCAL,SOCK_DGRAM,0);
	if(sockfd<0)
	{
		WADEBUG(D_FATAL)("socket error\n");
		return 0;
	}

	memset(&clientaddr,0,sizeof(clientaddr));
	clientaddr.sun_family = AF_LOCAL;
	strcpy(clientaddr.sun_path, UNIX_CLIPATH );

	unlink(UNIX_CLIPATH);

	int ret = bind(sockfd,(struct sockaddr*)&clientaddr,sizeof(clientaddr.sun_family)+strlen(clientaddr.sun_path));
	if(ret <0)
	{
		WADEBUG(D_FATAL)("bind error:%d.\n", ret);
		return 0;
	}
	memset(&servaddr,0,sizeof(servaddr));
	servaddr.sun_family = AF_LOCAL;
	strcpy(servaddr.sun_path,UNIX_SERPATH);

	Packet_head loginpacket;
	loginpacket.cmd = type_login;
	if((unsigned int) - 1 == sendCmd((const void *)&loginpacket, sizeof(Packet_head)))
	{
		sleep(4);
		if((unsigned int) - 1 == sendCmd((const void *)&loginpacket, sizeof(Packet_head)))
		{
			WADEBUG(D_FATAL)("send the login data failed.\n");
			return 0;
		}
	}
	return 1;
}


u_int32 sendCmd(const void *buf, const u_int32 buflen)
{
	u_int32 ret;
	ret = sendto(sockfd, buf, buflen, 0, (struct sockaddr *)(&servaddr), buflen);
	if(ret!=buflen)
	{
		return (unsigned int) -1;
	}
	return ret;
}

u_int32 recvCmd(char *buf, const u_int32 buflen)
{
#define CHECK_ERROR(x) if(x==0x00) return (unsigned int)-1
	u_int32 n = 0;
	u_int32 sockaddr_len = 0;
	struct sockaddr replyaddr;
	n = recvfrom(sockfd, buf, buflen, 0, NULL, NULL);
	const Packet_head *packethead;
	packethead = (Packet_head *)buf;
	if(packethead->b1!=0 || packethead->b2!=1 ||  packethead->major!= 1)
	{
	
		WADEBUG(D_WARNING)("datapacket %d %d or %d command is %d  is not right\n",packethead->b1,packethead->b2,packethead->major,packethead->cmd);
		//printf("%02x %02x %02x %02x %02x %02x \n",buf[0],buf[1],buf[2],buf[3],buf[4],buf[5]);
		return (unsigned int) -1;
	}

	u_int32 datalen = packethead->packet_len - n;

	if(packethead->cmd == type_post || packethead->cmd == type_smtp || packethead->cmd == type_pop3)
	{
		if((n == sizeof(Pro_post)) || (n == sizeof(Pro_pop3)))
		{
			u_int32 ret = recvfrom(sockfd, &buf[n], buflen-n, 0, &replyaddr, &sockaddr_len);
			if(ret != datalen || ret <= 0)
			{
				WADEBUG(D_WARNING)("received the data body len is not true:%d\n", ret);
				return (unsigned int) -1;
			}
		}
		else
			return (unsigned int) -1;
	}
	else if(packethead->cmd == type_get )
	{
	      char *tmp;
		if(n == sizeof(Pro_get))
		{
			u_int32 ret_url = recvfrom(sockfd, &buf[n], URL_LEN-n-1, 0, &replyaddr, &sockaddr_len);
			if( ret_url <= 0)
			{
				WADEBUG(D_WARNING)("received the data body len is not true:%d\n", ret_url);
				return (unsigned int) -1;
			}
			buf[n+ret_url] = '\0';
			u_int32 ret_host = recvfrom(sockfd, &buf[URL_LEN], URL_LEN-1, 0, &replyaddr, &sockaddr_len);

			buf[URL_LEN+ret_host]='\0';
			Pro_get* get_pack = (Pro_get*)buf;
			buf[2048] = '\0';
			if(get_pack->get_type==3)
			{
			 //  printf("the get type is 3\n");
			     u_int32 ret_key = recvfrom(sockfd,&buf[2048],100-1,0,&replyaddr,&sockaddr_len);
			     if(ret_key<0)
			     {
			     	   WADEBUG(D_FATAL)("error receive in key!\n");
			     }
			     buf[2048+ret_key]='\0';
			}
		}
		else
		{
			WADEBUG(D_WARNING)("received the get packet len is not true:%u\n", n);
			return (unsigned int) -1;
		}
	}
	else
		return (unsigned int) -1;
	return packethead->packet_len;
//       return datalen;
}

void dpclient_close()
{
	close(sockfd);
	unlink(UNIX_CLIPATH);
}
