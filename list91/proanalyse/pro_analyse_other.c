#include "pro_analyse_other.h"
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <sys/time.h>
                




#if 0
static int ini_dll(void* param)
{

        const char      *error;
        int             erroffset;
        int             ovector[OVECCOUNT];
	 re = pcre_compile(pattern_2, 0,/*PCRE_DOTALL*/, &error, &erroffset, NULL);
    	if (re == NULL) 
	{
                printf("PCRE compilation failed at offset %d: %s\n", erroffset, error);
	}

        return 1;
}

int register_mod(struct module_register** register_)

{
	ini_dll(NULL);
	*register_ = (struct module_register *)GET_POINT;
	
	return RET_NUMBER;
}

void unregister_mod(struct module_register* register_)
{
	//如果需要释放则写在下面
}
#endif
//////////////////////////////////////////////////////////////////////




static inline u32 tcp_other_25_0x45(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x48) &&(*(payload+2) == 0x4C) && (*(payload+3) == 0x4F))		//SMTP
				return PRO_SMTP;
		}
	}
	return cat_ret;
}

static inline u32 tcp_other_110_0x55(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x53) &&(*(payload+2) == 0x45) && (*(payload+3) == 0x52))		//POP3
				return PRO_POP3;
		}
	}
	return cat_ret;
}

static inline u32 tcp_other_1080_0x05(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
			return PRO_SOCK5;   //sock5
	}
	return cat_ret;
}

static inline u32 tcp_other_1433_0x01(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 18)
		{
			if((*(payload+8) == 0x49) && (*(payload+10) == 0x4E) && (*(payload+12) == 0x53) && (*(payload+14) == 0x45) && (*(payload+16) == 0x52) && (*(payload+18) == 0x54))		//insert语句
				return PRO_SQL_INSERT; 
			if((*(payload+8) == 0x44) && (*(payload+10) == 0x45) && (*(payload+12) == 0x4C) && (*(payload+14) == 0x45) && (*(payload+16) == 0x54) && (*(payload+18) == 0x45))		//delete语句
				return PRO_SQL_DELETE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_other_22_0x53(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 2)
		{
			if((*(payload+1) == 0x53) && (*(payload+2) == 0x48))		//SSH
				return PRO_SSH;
		}
	}
	return cat_ret;
}

static inline u32 tcp_other_143_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 6)
		{
			if(memcmp((payload+2), "LOGIN", 5) == 0)
				return PRO_IMAP4;													//IMAP4
		}
	}
	return cat_ret;
}

static inline u32 tcp_other_137_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+5) == 0x53) && (*(payload+6) == 0x4D) && (*(payload+7) == 0x42))		//文件共享
				return PRO_FILE_SHARE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_other_445_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+5) == 0x53) && (*(payload+6) == 0x4D) && (*(payload+7) == 0x42))		//文件共享
				return PRO_FILE_SHARE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_other_5354_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 29)
		{
			if((*(payload+26) == 0x50) && (*(payload+27) == 0x4F) && (*(payload+28) == 0x43) && (*(payload+29) == 0x4F))		//POCO
				return PRO_POCO;
		}
	}
	return cat_ret;
}

static inline u32 tcp_other_XXXX_0x43(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 6)
		{
			if((*(payload+1) == 0x4F) && (*(payload+2) == 0x4E) && (*(payload+3) == 0x4E) && (*(payload+4) == 0x45) && (*(payload+5) == 0x43) && (*(payload+6) == 0x54))		//HTTP代理
				return PRO_HTTP_AGENT;
		}
	}
	return cat_ret;
}

static inline u32 tcp_other_XXXX_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_DOWN == up_down)
	{
		if(plen > 11)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+5) == 0x00) && (*(payload+6) == 0x00) 
									&& (*(payload+7) == 0x00) && (*(payload+8) == 0x03) && (*(payload+9) == 0x30) && (*(payload+10) == 0x68) && (*(payload+11) == 0x73))		//POCO
				return PRO_POCO;
		}
	}
	return cat_ret;
}

static inline u32 udp_other_XXXX_0x80(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x94) && (*(payload+4) == 0x04) && (*(payload+5) == 0x29) && (*(payload+6) == 0x00) && (*(payload+7) == 0x00))
				return PRO_POCO;  //POCO
		}
	}
	return cat_ret;
}

static inline u32 udp_other_XXXX_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if (get_u32(payload, 0) == 0xF5030000)
		return PRO_SAFE360_UPDATE;   //360在线升级
	return cat_ret;
}



//方向
//包
//数据包的长度，取出ip头部和tcp头部



unsigned int  analyse_other_tcp_21(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;

	return PRO_FTP;
}

unsigned int  analyse_other_tcp_25(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x45:
			cat_ret = tcp_other_25_0x45(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_other_tcp_110(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x55:
			cat_ret = tcp_other_110_0x55(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_other_tcp_1080(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x05:
			cat_ret = tcp_other_1080_0x05(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_other_tcp_1433(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x01:
			cat_ret = tcp_other_1433_0x01(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_other_tcp_22(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x53:
			cat_ret = tcp_other_22_0x53(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_other_tcp_143(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0xE4:
			cat_ret = tcp_p2p_4672_0xE4(dir, payload, plen);
		break;
	}
#endif
	if(cat_ret == 0)
		cat_ret = tcp_other_143_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_other_tcp_137(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0xE4:
			cat_ret = tcp_p2p_4672_0xE4(dir, payload, plen);
		break;
	}
#endif
	if(cat_ret == 0)
		cat_ret = tcp_other_137_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_other_tcp_445(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0xE4:
			cat_ret = tcp_p2p_4672_0xE4(dir, payload, plen);
		break;
	}
#endif
	if(cat_ret == 0)
		cat_ret = tcp_other_445_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_other_tcp_5354(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0xE4:
			cat_ret = tcp_p2p_4672_0xE4(dir, payload, plen);
		break;
	}
#endif
	if(cat_ret == 0)
		cat_ret = tcp_other_5354_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_other_tcp_XXXX(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x43:
			cat_ret = tcp_other_XXXX_0x43(dir, payload, plen);
		break;
		case 0x00:
			cat_ret = tcp_other_XXXX_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_other_udp_XXXX(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x80:
			cat_ret = udp_other_XXXX_0x80(dir, payload, plen);
		break;
		case 0x00:
			cat_ret = udp_other_XXXX_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

static const struct module_register __dllregister[]={
       {"analyse_other_tcp_21",analyse_other_tcp_21,FUNC_RULE,100,IP_TCP,DIR_IN,21},
	{"analyse_other_tcp_25",analyse_other_tcp_25,FUNC_RULE,100,IP_TCP,DIR_UP,25},
	{"analyse_other_tcp_110",analyse_other_tcp_110,FUNC_RULE,100,IP_TCP,DIR_UP,110},
	{"analyse_other_tcp_1080",analyse_other_tcp_1080,FUNC_RULE,100,IP_TCP,DIR_UP,1080},
	{"analyse_other_tcp_1433",analyse_other_tcp_1433,FUNC_RULE,100,IP_TCP,DIR_UP,1433},
	{"analyse_other_tcp_22",analyse_other_tcp_22,FUNC_RULE,100,IP_TCP,DIR_UP,22},
	{"analyse_other_tcp_143",analyse_other_tcp_143,FUNC_RULE,100,IP_TCP,DIR_UP,143},
   	{"analyse_other_tcp_137",analyse_other_tcp_137,FUNC_RULE,100,IP_TCP,DIR_UP,137},
   	{"analyse_other_tcp_445",analyse_other_tcp_445,FUNC_RULE,100,IP_TCP,DIR_UP,445},
   	{"analyse_other_tcp_5354",analyse_other_tcp_5354,FUNC_RULE,100,IP_TCP,DIR_UP,5354},
   	{"analyse_other_tcp_XXXX",analyse_other_tcp_XXXX,FUNC_RULE,100,IP_TCP,DIR_IN,0},
   	{"analyse_other_udp_XXXX",analyse_other_udp_XXXX,FUNC_RULE,100,IP_UDP,DIR_IN,0}
};
const struct module_register* get_module_info_other(int * func_num)
{
	*func_num = RET_NUMBER;
	return (const struct module_register*)GET_POINT;
}

