/**
 * \brief ��ͷ��ʽ���壬���������ݵİ�ͷͳһ��Ϊ�˸�ʽ
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
#define MAXLINE 2048+256    //���������󳤶�
#define URL_LEN 1024

typedef struct _Packet_head
{
	u_char b1;           //����1//  0
	u_char b2;           //����2//  1 
	u_short packet_len;		//����
       u_char major;			//�汾�� //1
	u_char min;				//���汾��0
	u_short cmd;			//Э��ţ�����������ʲôЭ��
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
	u_int32 seqnum;				//seqnumber,�����к�
	u_int32 ack;					//����ACKֵ
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
	u_int32 seqnum;				//seqnumber,�����к�
	u_int32 ack;					//����ACKֵ
}Pro_smtp;

typedef struct _Pro_pop3
{
     Packet_head head;
	u_int32 ip_inner;
	u_int32 ip_outter;
	u_int16 port_inner;
	u_int16 port_outter;
	u_int32 account_id;
	u_int32 seqnum;				//seqnumber,�����к�
	u_int32 ack;					//����ACKֵ
}Pro_pop3;

enum
{
	type_login = 0,		//�ͻ��˵�½ָ��
	type_get = 1011,
	type_post = 1021,
	type_smtp = 1031,     	//SMTPЭ������
	type_pop3 = 1041,	 	//POP3Э������
};

#endif
