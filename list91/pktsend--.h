#ifndef __PKTSEND_H__
#define __PKTSEND_H__

#include "global.h"
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <netinet/in.h>
#include <linux/types.h>
#include <linux/tcp.h>
#include <linux/ip.h>
#include <string.h>
#include <errno.h>

#define PKT_CONTENT_REDIRCT "HTTP/1.0 302 Moved Temporarily\r\nServer:%s\r\nLocation: http://%s/ly/warnning/%s?p=%d\r\nContent-Type: text/html\r\n\r\n"

int TCP_RST_send2(u32 seq,u32 src_ip, u32  dst_ip, u16 src_prt, u16 dst_prt);
int TCP_RST_send(u32 seq,u32 src_ip, u32  dst_ip, u16 src_prt, u16 dst_prt);
int CreateRawSocket();
void CloseRawSocket();
int RedirectUrl(const char *ServerAddress,const char* HttpData,u32 seq_number,u32 ack_number,u32 src_ip, u32  dst_ip, u16 src_prt, u16 dst_prt);

#endif

