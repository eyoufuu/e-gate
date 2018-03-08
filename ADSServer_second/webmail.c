#include <string.h>
#include <unistd.h>
#include <stdio.h>
#include <regex.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <stdlib.h>
#include <sys/ioctl.h>
#include <sys/socket.h>
#include <sys/errno.h>
#include <net/if.h>
#include <netinet/in.h>
#include "pktsend.h"

#define DEBUG(x) fprintf(stderr,"info: %s\n",x)


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


//"HTTP/1.0 302 Moved Temporarily\r\nServer:%s\r\nLocation: http://%s/ly/warning/%s?p=%d\r\nContent-Type: text/html\r\n\r\n"

//int sndpkt_redirecturl(const char* HttpData,u32 seq_number,u32 ack_number,u32 src_ip, u32  dst_ip, u16 src_prt, u16 dst_prt);
//PKT_CONTENT_REDIRCT
//mail_pos ÊÇÎ»ÖÃ	   
int handle_webmail( u32 seq, u32 ack,u32 sip,u32 dip,u16 sport,u16 dport,
                                               const char * host ,const int hostlen,int mail_pos,const char * body, const int bodylen)
{
	static int register_socket = 0;
	static char server_ip_str[16];
	static char server_redirect[256];
       if(register_socket == 0)
       {
		struct in_addr inaddr;
		inaddr.s_addr = if_getlocalip("eth2");
		strcpy(server_ip_str,(char*)inet_ntoa(inaddr));
        	sndpkt_createrawsocket();
		//sprintf(server_redirect,PKT_CONTENT_REDIRCT,server_ip_str,server_ip_str,"warning.php",3);
		register_socket = 1;   
       }
	   
	char * pos = (char*)(host+mail_pos+5);
//char * posend = pos;
//mail.126.com
//cn.mc922.mail.yahoo.com
//mail.sina.com.cn
//mail.sohu.com
//mail.163.com
//mail.qq.com
//mail.google.com
switch(*pos)
{
   case '1':
   	if((*(pos+1)=='6') && (*(pos+2)=='3'))//163
   		{
   		  // sndpkt_tcp_rst(ack,dip,sip,dport,sport);
   		   //sndpkt_redirecturl(server_redirect,seq,ack,sip,dip,sport,dport);
   		   DEBUG("163 email\n");
   		}
	else if( (*(pos+1)=='2') && (*(pos+2)=='6'))//126
		{
   		   //sndpkt_tcp_rst(ack,dip,sip,dport,sport);
   		   //sndpkt_redirecturl(server_redirect,seq,ack,sip,dip,sport,dport);
   		   DEBUG("126 email\n");
		}
   	break;
   case 'q':
   	  if( *(pos+1) == 'q')//qq
   	  	{
   		   //sndpkt_tcp_rst(ack,dip,sip,dport,sport);
   		   DEBUG("qq email\n");
   	  	}
   	break;
   case 'y':
   	if((*(pos+1)=='a') && (*(pos+4) == 'o'))//yahoo
   		{
   		   //sndpkt_tcp_rst(ack,dip,sip,dport,sport);
       	  DEBUG("yahoo email\n");
   		}
   	break;
   case 'g':
   	if( *(pos+5)=='e')//google
   		{
   		   //sndpkt_tcp_rst(ack,dip,sip,dport,sport);
       	  DEBUG("google email\n");
   		}
   	break;
   case 's'://sina
   	if(*(pos+3)=='a')
   		{
   		   //sndpkt_tcp_rst(ack,dip,sip,dport,sport);
       	  DEBUG("sina email\n");
//   		   sndpkt_redirecturl(server_redirect,seq,ack,sip,dip,sport,dport);
   		}
   	break;
}
	return 1;
	
}

