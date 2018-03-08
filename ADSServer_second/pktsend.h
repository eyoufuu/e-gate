#ifndef __PKTSEND_H__
#define __PKTSEND_H__

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <netinet/in.h>
#include <linux/types.h>
#include <linux/tcp.h>
#include <linux/ip.h>
#include <string.h>
#include <errno.h>

#ifndef u16
#define u16 unsigned short
#endif//u16

#ifndef u32
#define u32 unsigned int
#endif//u32

#ifndef u8
#define u8 unsigned char
#endif//u8

//#define PKT_CONTENT_REDIRCT "HTTP/1.0 302 Moved Temporarily\r\nServer:%s\r\nLocation: http://%s\r\nContent-Type: text/html\r\n\r\n"
#define PKT_CONTENT_REDIRCT "HTTP/1.0 302 Moved Temporarily\r\nServer:%s\r\nLocation: http://%s/ly/warning/%s?p=%d\r\nContent-Type: text/html\r\n\r\n"

int TCP_RST_send2(u32 seq,u32 src_ip, u32  dst_ip, u16 src_prt, u16 dst_prt);
int sndpkt_tcp_rst(u32 seq,u32 src_ip, u32  dst_ip, u16 src_prt, u16 dst_prt);
int sndpkt_createrawsocket();
void sndpkt_closerawsocket();
int sndpkt_redirecturl(/*const char *ServerAddress,*/const char* HttpData,u32 seq_number,u32 ack_number,u32 src_ip, u32  dst_ip, u16 src_prt, u16 dst_prt);

#endif

