#ifndef __IMP2_H__
#define __IMP2_H__

#include <unistd.h>
#include <stdio.h>
#include <linux/types.h>
#include <string.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <asm/types.h>
#include <linux/netlink.h>
#include <signal.h>
#include "global.h"

#define MAX_PAYLOAD 512
#define IMP2_U_PID   0
#define IMP2_IPMAC_FLAG 5
//#define IMP2_IPMAC_STATUS 6
#define IMP2_IPMAC   7
#define IMP2_IP_BLACK 8
#define IMP2_CLOSE   2
#define NL_IMP2      31
/*struct packet_info
{
	  u32 ip;
	  u16 dir;
	  u16 len;
};

struct time_seg
{
    u16 _weekly;
    u16 _access;
    u16 _gate;
    u16 _times1 ;
    u16 _timee1 ;
    u16 _times2 ;
    u16 _timee2 ;
};//12个字节*/

typedef struct _msg_to_kernel
{
  struct nlmsghdr hdr;
  char pay_load[MAX_PAYLOAD];
}msg_to_kernel;

typedef struct _u_packet_info
{
  struct nlmsghdr hdr;
  char msg[1024];
}u_packet_info;

//(1)特例网址 4字节整形表示个数， 4字节整形表示IP，1字节char表示 放行阻断      。。。。。。。。
//(2)监控范围 4字节整形表示段数， 4字节整形表示IPStart，4字节整形表示IPEnd， 。。。。。。。。
//(3)时间段   是否启用时间段，    4字节整形表示是否启用，4字节整形表示时间开始(TimeStart），4字节整形表示时间结束(TimeEnd)
//(4）默认阻断还是放行
void SendKernelMessage (char* buf, char type, char cmd, u16 len);
int RecvKernelMessage(u_packet_info* info,u32 infolen);
#endif
