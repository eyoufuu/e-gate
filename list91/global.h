#ifndef _GLOBAL_H
#define _GLOBAL_H

#include <stdio.h>
#include <stdarg.h>
#include <pthread.h>
#include <string.h>
#include <stdlib.h>
#include "globaldef.h"
#include "./sharedmem/sharedmem.h"

#ifndef NULL
#define NULL 0
#endif

#define likely(x)    __builtin_expect(!!(x), 1)
#define unlikely(x)  __builtin_expect(!!(x), 0)

#define PKT_BLOCK 		0
#define PKT_PASS  			1

#define MAX_IP 			256
#define MAX_NETSEG 		4
#define MAX_PRO 			256

#define MAX_POLICY 		100
#define MAX_DEVNUM 		4

#define INS_TRA_CLOSE 	0 
#define INS_TRA_OPEN   	1

#define DEV_ETH0 "eth0"
#define DEV_ETH1 "eth1"
#define DEV_ETH2 "eth2"
#define DEV_ETH3 "eth3"

#define DEV_IF_UNKNOW 	0
#define DEV_IF_LAN 		1
#define DEV_IF_WAN 		2



#define GETIPHASH(IP) 		(IP>>24)
#define GETNETSEGHASH(IP) (((IP>>16)&(0xff))%MAX_NETSEG)


#define 	LOG_WEBGET      1011
#define 	LOG_WEBHOST     1012
#define 	LOG_POST 		1021
#define 	LOG_SMTP 		1031
#define 	LOG_POP3		1041
#define 	LOG_TRA         1061

#define HASH_VAL 			23

#define UNIXDG_MAXLINE 	2048
#define UNIXDG_PATH 		"/serv"




enum MODULENAME
{
        MODULE_POLICY=0,
        MODULE_PROCTL,
        MODULE_WEBFILTER,
        MODULE_AUDIT,
        MODULE_OPENVPN,
        MODULE_FIREWALL,
        MODULE_REPORT,
        MODULEE_LANSCAN
};
typedef struct _TModule{
        u32 id;
        u32 state;
        u32 service;
}TModule;


typedef struct _TMutex{
	pthread_mutex_t mutex;	
}TMutex;
/*
typedef struct _TTime{
        u32 curtime;
        struct tm curdate;
}TTime;
*/
//globle var
extern TPktinfo g_pkt;
extern u32 g_interupt;
extern TTime*  g_ptm;
extern u32 	g_debuglevel;
extern u32 	g_wanindex;
extern u32 	g_lanindex;

extern u32  g_isqosopen;
extern u32 	g_sysmode;
extern u32 	g_isipmacbind;
extern u32 	g_gate;
extern u32 g_isremind;
extern u32 	g_localip;
extern u8 	g_localipstr[24];
extern u32 g_protype[256];
extern TModule g_modules[20];

extern u32 g_isfiletypeopen;
extern u32 g_filechecklen;
extern u8 g_filecheck[100][21];//len = g_filecheck[][0] 

//file trans out check;
extern u8 g_ismailopen;
extern u8 g_isbbsopen;
extern u8 g_isimopen;
extern u8 g_isnetdiskopen;
extern u8 g_isftpopen;
extern u8 g_istftpopen;
extern u8 g_isarp_ipmacbind;
extern u8 g_isblogopen;

//redirect g_buf
//extern u8 g_redirectbuf[1024];


#define DELETE(p)  do{ delete p; p=NULL; } while(false)
#define DELETEARRARY(p) do{ delete []p;p=NULL;}while(false)

enum MODE
{
	SYS_IP=0,
	SYS_ACCOUNT,
	SYS_LONGIN,
	SYS_MIX,
	SYS_POP3,
	SYS_IPMAC,
	SYS_AD
};

enum SPECIP
{
	IP_BLACK=0,
	IP_WHITE,
	IP_NONE
};

typedef struct _TStaflow{/* log to db  time+ip+proid+TStaflow*/ 
	u32 staup;
	u32 stadown;
	
	u32 stapassnum;
	u32 stablocknum;
}TStaflow;
typedef struct _TInsflow{/* instanse traffic */
	u32 upflow;
	u32 downflow;
}TInsflow;
/*typedef struct TIPADDR{ 
	union 
	{    
		struct 
		{      
			u8 s_b[4];    
		} S_un_b;    
		u32 S_addr;  
	}S_un;
	TIPADDR(u32 IP)
	{
		S_un.S_addr = IP;
	}
	u32 V(u32 i)
	{
		return S_un.S_un_b.s_b[i];
	}
}TIPADDR;
#define SHOWIPADDR(x) { do{struct TIPADDR  ipaddr(x);\
	                printf("IP:%d,%d,%d,%d\n",ipaddr.V(0),ipaddr.V(1),ipaddr.V(2),ipaddr.V(3));}while(0);}
//gcc cannot compile */






//debug 
#define D_FATAL		0
#define D_WARNING	1
#define D_INFO		2
#define D_DETAIL	        3
#define D_ALL		        100
#define D_NONE		-100

#define DEBUG(message_level) if(g_debuglevel >= message_level) printf
//#define DEBUG(message_level) write_debug_log
void getdate(char* date,int datelen);	
void gettime(char* time,int timelen);
void write_debug_log(const char *fmt, ...);


#endif
