/**
 * \brief 包头格式定义，将发送数据的包头统一定为此格式
 * \author zhengjianfang
 * \date 2009-09-16
 */

 #ifndef _PACKETHEAD_DEFINE_H_
#define _PACKETHEAD_DEFINE_H_

#include <netinet/in.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <string.h>
#include "globDefine.h"

#pragma pack(1)

#define HASH_INITVAL 23
#define MAXLINE 2048+256    //定义包的最大长度
#define URL_LEN 1024

typedef struct _Packet_head
{
	u_char b1;           //保留1//  0
	u_char b2;           //保留2//  1 
	u_short packet_len;		//包长
       u_char major;			//版本号 //1
	u_char min;				//副版本号0
	u_short cmd;			//协议号，用来区分是什么协议
}Packet_head;

typedef struct _Pro_get
{
      Packet_head head;
	u_int32 ip_inner;
	u_int32 ip_outter;
	u_char mac[6];
	u_int32 account_id;
	u_char policyid;
	u_char get_type;
	u_int16 typeid;
	u_int16 isblocked;
}Pro_get;

typedef struct _Pro_post
{
       Packet_head head;
	u_int32 ip_inner;
	u_int32 ip_outter;
	u_int16 port_inner;
	u_int16 port_outter;
	u_char mac[6];
	u_int32 account_id;
	u_int32 seqnum;				//seqnumber,包序列号
	u_int32 ack;					//包的ACK值
}Pro_post;

typedef struct _Pro_smtp
{
       Packet_head head;
	u_int32 ip_inner;
	u_int32 ip_outter;
	u_int16 port_inner;
	u_int16 port_outter;
	u_char mac[6];
	u_int32 account_id;
	u_int32 seqnum;				//seqnumber,包序列号
	u_int32 ack;					//包的ACK值
}Pro_smtp;

typedef struct _Pro_pop3
{
     Packet_head head;
	u_int32 ip_inner;
	u_int32 ip_outter;
	u_int16 port_inner;
	u_int16 port_outter;
	u_int32 account_id;
	u_int32 seqnum;				//seqnumber,包序列号
	u_int32 ack;					//包的ACK值
}Pro_pop3;

enum
{
	type_login = 0,		//客户端登陆指令
	type_get = 1011,
	type_post = 1021,
	type_smtp = 1031,     	//SMTP协议类型
	type_pop3 = 1041,	 	//POP3协议类型
};

#endif
