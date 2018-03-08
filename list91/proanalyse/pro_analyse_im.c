#include "pro_analyse_im.h"
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


static inline u32 tcp_im_80_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 6)
		{
			if((*(payload+2) == 0x02) && (*(payload+5) == 0x00) && (*(payload+plen-1) == 0x03))		//qq
				return PRO_QQ;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_80_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 5)
	{
		if((__nhs(get_u16(payload, 0)) == plen) && (get_u16(payload,3) == 0x5917) && (*(payload+plen-1) == 0x03))
			return PRO_QQ;
	}
	return cat_ret;
}

static inline u32 tcp_im_8080_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+2) == 0x66) && (*(payload+3) == 0x65) && (*(payload+4) == 0x74) && (*(payload+5) == 0x69) && (*(payload+6) == 0x6F) && (*(payload+7) == 0x6E))		//飞信
				return PRO_FETION;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_1863_0x56(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 2)
		{
			if((*(payload+1) == 0x45) &&(*(payload+2) == 0x52))		//msn登陆
				return PRO_MSN;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_1863_0x50(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 2)
		{
			if((*(payload+1) == 0x4E) &&(*(payload+2) == 0x47))		//msn登陆
				return PRO_MSN;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_1863_0x4E(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_DOWN == up_down)
	{
		if(plen > 2)
		{
			if((*(payload+1) == 0x4C) &&(*(payload+2) == 0x4E))		//msn登陆
				return PRO_MSN;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_1863_0x4D(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen >= 336)
	{
		if((*(payload+1) == 0x53) &&(*(payload+2) == 0x47) && (memcmp((payload+300), "5D3E02AB-6190-11D3-BBBB-00C04F795683", 36) == 0))
			return PRO_MSN_FILE_TRANS;													//MSN传输文件
	}
	if(plen >= 310)
	{
		if((*(payload+1) == 0x53) &&(*(payload+2) == 0x47) && (memcmp((payload+300), "Bridges: T", 10) == 0))
			return PRO_MSN_FILE_TRANS;													//MSN传输文件
	}
	return cat_ret;
}

static inline u32 tcp_im_1863_0x41(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 4)
	{
		if((*(payload+1) == 0x4E) && (*(payload+2) == 0x53) && (*(payload+plen-2) == 0x0D) && (*(payload+plen-1) == 0x0A) )
			return PRO_MSN;													//MSN传输文件
	}
	return cat_ret;
}

static inline u32 tcp_im_5050_0x59(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x4D) &&(*(payload+2) == 0x53) && (*(payload+3) == 0x47))		//雅虎通
				return PRO_YAHOO_MESS;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_5190_0x2A(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 1)
		{
			if(*(payload+1) == 0x01)		//ICQ
				return PRO_ICQ;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x2A(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 1)
		{
			if(*(payload+1) == 0x01)		//ICQ
				return PRO_ICQ;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x73(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 5)
		{
			if((*(payload+1) == 0x74) && (*(payload+2) == 0x72) && (*(payload+3) == 0x65) && (*(payload+4) == 0x61) && (*(payload+5) == 0x6D))		//google talk
				return PRO_GOOGLE_TALK;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x0C(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+4) == 0x01) && (*(payload+5) == 0x01) && (*(payload+6) == 0x00) && (*(payload+7) == 0x00))		//网易泡泡
				return PRO_163_POPO;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 7)
	{
		if((*(payload+1) == 0x00) && (*(payload+2) == 0x01) && (*(payload+3) == 0x00) && (*(payload+4) == 0x31) && (*(payload+5) == 0x56) && (*(payload+6) == 0x4D) && (*(payload+7) == 0x49))		
				return PRO_BAIDU_HI_LOGIN;  //baidu hi 登陆
	}
	if(DIR_UP == up_down)
	{
		if(plen > 6)
		{
			if((*(payload+2) == 0x02) && (*(payload+5) == 0x00) && (*(payload+plen-1) == 0x03))		//qq
				return PRO_QQ;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x03(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 2)
		{
			if((*(payload+1) == 0x01) && (*(payload+2) == 0x00))		//skype
				return PRO_SKYPE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x3E(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x02) && (*(payload+2) == 0xF5) && (*(payload+3) == 0x0B) && (*(payload+4) == 0x06) && (*(payload+5) == 0x00) && (*(payload+6) == 0x08) && (*(payload+7) == 0x00))
				return PRO_163_POPO;   //网易泡泡
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x6C(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x41) && (*(payload+2) == 0x56) && (*(payload+3) == 0x61))		//LAVA-LAVA
				return PRO_LAVA_LAVA;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x04(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if((__nhs(get_u16(payload, 3)) == plen) && (*(payload+plen-1) == 0x03))		//QQ传输文件
		return PRO_QQ_FILE_TRANS;
	return cat_ret;
}

static inline u32 tcp_im_443_0x30(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 5)
	{
		if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+4) == 0x00) && (*(payload+5) == 0x00))		//MSN传输文件
			return PRO_MSN_FILE_TRANS;
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x78(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 7)
	{
		if((*(payload+1) == 0x05) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+4) == 0x02) && (*(payload+5) == 0x00) && (*(payload+6) == 0x00) && (*(payload+7) == 0x00))		//MSN传输文件
			return PRO_MSN_FILE_TRANS;
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x01(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 15)
	{
		if((*(payload+1) == 0x00) && (*(payload+2) == 0x15) && (*(payload+3) == 0x00) && (*(payload+4) == 0x00) && (*(payload+5) == 0x10) && (*(payload+6) == 0x00) && (*(payload+7) == 0x00)
					&& (*(payload+8) == 0x7B) && (*(payload+9) == 0x32) && (*(payload+10) == 0x71) && (*(payload+11) == 0x05) && (*(payload+12) == 0xFF) && (*(payload+13) == 0x17) && (*(payload+14) == 0x71) && (*(payload+15) == 0x05))		//BAIDU HI 传输文件
			return PRO_BAIDU_HI_FILE_TRANS;
	}
	return cat_ret;
}

static inline u32 tcp_im_443_0x41(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 4)
	{
		if((*(payload+1) == 0x4E) && (*(payload+2) == 0x53) && (*(payload+plen-2) == 0x0D) && (*(payload+plen-1) == 0x0A) )
			return PRO_MSN;													//MSN传输文件
	}
	return cat_ret;
}

static inline u32 tcp_im_5222_0x73(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 5)
		{
			if((*(payload+1) == 0x74) && (*(payload+2) == 0x72) && (*(payload+3) == 0x65) && (*(payload+4) == 0x61) && (*(payload+5) == 0x6D))		//google talk
				return PRO_GOOGLE_TALK;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_443_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen >= 286)
	{
		if((*(payload+56) == 0x49) && (*(payload+57) == 0x4E) && (*(payload+58) == 0x56) && (*(payload+59) == 0x49) && (*(payload+60) == 0x54) 
							&& (*(payload+61) == 0x45) && (*(payload+62) == 0x20) && (*(payload+63) == 0x4D) && (*(payload+64) == 0x53) && (*(payload+65) == 0x4E)
							&& (memcmp((payload+250),"5D3E02AB-6190-11D3-BBBB-00C04F795683",36) == 0))		//MSN传输文件
			return PRO_MSN_FILE_TRANS;
	}
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+2) == 0x66) && (*(payload+3) == 0x65) && (*(payload+4) == 0x74) && (*(payload+5) == 0x69) && (*(payload+6) == 0x6F) && (*(payload+7) == 0x6E))		//飞信
					return PRO_FETION;
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_16000_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 14)
		{
			if((*(payload+9) == 0x74) && (*(payload+10) == 0x61) && (*(payload+11) == 0x6F) && (*(payload+12) == 0x62) && (*(payload+13) == 0x61) && (*(payload+14) == 0x6F))	
				return PRO_ALI_WANGWANG;  //阿里旺旺
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_3502_0x12(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x02) && (*(payload+2) == 0x00) && (*(payload+3) == 0x01))	
				return PRO_SOUQ;  //搜Q
		}
	}
	return cat_ret;
}

static inline u32 tcp_im_XXXX_0x37(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 8)
	{
		if((*(payload+plen-8) == 0x00) && (*(payload+plen-7) == 0x00) && (*(payload+plen-6) == 0x00) && (*(payload+plen-5) == 0x00) 
									&& (*(payload+plen-4) == 0x00) && (*(payload+plen-3) == 0x03) && (*(payload+plen-2) == 0x30) && (*(payload+plen-1) == 0x68) && (memcmp((payload+1), "95683}", 6)==0))	
				return PRO_MSN_FILE_TRANS;  // MSN传输文件
	}
	return cat_ret;
}

static inline u32 tcp_im_XXXX_0x27(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 10)
	{
		if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+4) == 0x00) && (*(payload+5) == 0x14)
					&& (*(payload+6) == 0x00) && (*(payload+7) == 0x00) && (*(payload+8) == 0x00) && (*(payload+9) == 0x00) && (*(payload+10) == 0x01))		//QQ传输文件
			return PRO_QQ_FILE_TRANS;
	}
	return cat_ret;
}

static inline u32 tcp_im_XXXX_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen >= 336)
	{
		if((*(payload+48) == 0x49) && (*(payload+49) == 0x4E) && (*(payload+50) == 0x56) && (*(payload+51) == 0x49) 
									&& (*(payload+52) == 0x54) && (*(payload+53) == 0x45) && (memcmp((payload+300), "5D3E02AB-6190-11D3-BBBB-00C04F795683", 36)==0))		//MSN传输文件
				return PRO_MSN_FILE_TRANS;  // MSN传输文件
	}
	return cat_ret;
}

static inline u32 udp_im_8000_0x02(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(*(payload+plen-1) == 0x03)		//QQ登陆
			return PRO_QQ;
	}
	return cat_ret;
}

static inline u32 udp_im_8000_0x04(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(*(payload+plen-1) == 0x03)		//QQ传输文件
			return PRO_QQ_FILE_TRANS;
	}
	return cat_ret;
}

static inline u32 udp_im_5700_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 5)
	{
		if((*(payload+1) == 0x00) && (*(payload+3) == 0x00) && (*(payload+4) == 0x00) && (*(payload+5) == 0x00))
			return PRO_SINA_UC;    //新浪UC
	}
	return cat_ret;
}

static inline u32 udp_im_3002_0x01(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 10)
	{
		if((*(payload+7) == 0x00) && (*(payload+8) == 0x00) && (*(payload+9) == 0x01) && (*(payload+10) == 0x00))
			return PRO_SINA_UC;    //新浪UC
	}
	return cat_ret;
}

static inline u32 udp_im_3001_0x01(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 10)
	{
		if((*(payload+7) == 0x00) && (*(payload+8) == 0x00) && (*(payload+9) == 0x01) && (*(payload+10) == 0x00))
			return PRO_SINA_UC;    //新浪UC
	}
	return cat_ret;
}

static inline u32 udp_im_3128_0x01(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 2)
	{
		if((*(payload+1) == 0x52) && (*(payload+2) == 0x58))
			return PRO_SINA_UC;    //新浪UC
	}
	return cat_ret;
}


//方向
//包
//数据包的长度，取出ip头部和tcp头部

unsigned int  analyse_im_tcp_80(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = tcp_im_80_0x00(dir, payload, plen);
		break;
	}
	if(cat_ret == 0)
		cat_ret = tcp_im_80_XXXX(dir, payload, plen);
	return cat_ret;

}

unsigned int  analyse_im_tcp_8080(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = tcp_im_80_0x00(dir, payload, plen);
		break;
	}
#endif
	if(cat_ret == 0)
		cat_ret = tcp_im_8080_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_im_tcp_1863(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x56:
			cat_ret = tcp_im_1863_0x56(dir, payload, plen);
		break;
		case 0x50:
			cat_ret = tcp_im_1863_0x50(dir, payload, plen);
		break;
		case 0x4E:
			cat_ret = tcp_im_1863_0x4E(dir, payload, plen);
		break;
		case 0x4D:
			cat_ret = tcp_im_1863_0x4D(dir, payload, plen);
		break;
		case 0x41:
			cat_ret = tcp_im_1863_0x41(dir, payload, plen);
        case 0x43:
            cat_ret = PRO_MSN;
		break;
	}
	return cat_ret;
}

unsigned int  analyse_im_tcp_5050(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x59:
			cat_ret = tcp_im_5050_0x59(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_im_tcp_5190(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x59:
			cat_ret = tcp_im_5190_0x2A(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_im_tcp_443(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x59:
			cat_ret = tcp_im_443_0x2A(dir, payload, plen);
		break;
		case 0x73:
			cat_ret = tcp_im_443_0x73(dir, payload, plen);
		break;
		case 0x0C:
			cat_ret = tcp_im_443_0x0C(dir, payload, plen);
		break;
		case 0x00:
			cat_ret = tcp_im_443_0x00(dir, payload, plen);
		break;
		case 0x03:
			cat_ret = tcp_im_443_0x03(dir, payload, plen);
		break;
		case 0x3E:
			cat_ret = tcp_im_443_0x3E(dir, payload, plen);
		break;
		case 0x6C:
			cat_ret = tcp_im_443_0x6C(dir, payload, plen);
		break;
		case 0x04:
			cat_ret = tcp_im_443_0x04(dir, payload, plen);
		break;
		case 0x30:
			cat_ret = tcp_im_443_0x30(dir, payload, plen);
		break;
		case 0x78:
			cat_ret = tcp_im_443_0x78(dir, payload, plen);
		break;
		case 0x01:
			cat_ret = tcp_im_443_0x01(dir, payload, plen);
		break;
		case 0x41:
			cat_ret = tcp_im_443_0x41(dir, payload, plen);
		break;
	}
	if(cat_ret == 0)
		cat_ret = tcp_im_443_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_im_tcp_5222(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x73:
			cat_ret = tcp_im_5222_0x73(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_im_tcp_16000(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = tcp_im_80_0x00(dir, payload, plen);
		break;
	}
#endif
	if(cat_ret == 0)
		cat_ret = tcp_im_16000_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_im_tcp_3502(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x12:
			cat_ret = tcp_im_3502_0x12(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_im_tcp_XXXX(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x37:
			cat_ret = tcp_im_XXXX_0x37(dir, payload, plen);
		break;
		case 0x27:
			cat_ret = tcp_im_XXXX_0x27(dir, payload, plen);
		break;
	}
	if(cat_ret == 0)
		cat_ret = tcp_im_XXXX_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_im_udp_8000(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x02:
			cat_ret = udp_im_8000_0x02(dir, payload, plen);
		break;
		case 0x04:
			cat_ret = udp_im_8000_0x04(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_im_udp_5700(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = udp_im_5700_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_im_udp_3001(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = udp_im_3001_0x01(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_im_udp_3002(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = udp_im_3002_0x01(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_im_udp_3128(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = udp_im_3128_0x01(dir, payload, plen);
		break;
	}
	return cat_ret;
}

static const struct module_register __dllregister[]={
       {"analyse_im_tcp_80",analyse_im_tcp_80,FUNC_RULE,100,IP_TCP,DIR_IN,80},
	{"analyse_im_tcp_8080",analyse_im_tcp_8080,FUNC_RULE,100,IP_TCP,DIR_UP,8080},
	{"analyse_im_tcp_1863",analyse_im_tcp_1863,FUNC_RULE,100,IP_TCP,DIR_IN,1863},
	{"analyse_im_tcp_5050",analyse_im_tcp_5050,FUNC_RULE,100,IP_TCP,DIR_UP,5050},
	{"analyse_im_tcp_5190",analyse_im_tcp_5190,FUNC_RULE,100,IP_TCP,DIR_UP,5190},
	{"analyse_im_tcp_443",analyse_im_tcp_443,FUNC_RULE,100,IP_TCP,DIR_IN,443},
	{"analyse_im_tcp_5222",analyse_im_tcp_5222,FUNC_RULE,100,IP_TCP,DIR_UP,5222},
   	{"analyse_im_tcp_16000",analyse_im_tcp_16000,FUNC_RULE,100,IP_TCP,DIR_UP,16000},
   	{"analyse_im_tcp_3502",analyse_im_tcp_3502,FUNC_RULE,100,IP_TCP,DIR_UP,3502},
   	{"analyse_im_tcp_XXXX",analyse_im_tcp_XXXX,FUNC_RULE,100,IP_TCP,DIR_IN,0},
   	{"analyse_im_udp_8000",analyse_im_udp_8000,FUNC_RULE,100,IP_UDP,DIR_UP,8000},
   	{"analyse_im_udp_5700",analyse_im_udp_5700,FUNC_RULE,100,IP_UDP,DIR_IN,5700},
   	{"analyse_im_udp_3001",analyse_im_udp_3001,FUNC_RULE,100,IP_UDP,DIR_IN,3001},
   	{"analyse_im_udp_3002",analyse_im_udp_3002,FUNC_RULE,100,IP_UDP,DIR_IN,3002},
   	{"analyse_im_udp_3128",analyse_im_udp_3128,FUNC_RULE,100,IP_UDP,DIR_IN,3128}
};



const struct module_register* get_module_info_im(int * func_num)
{
	*func_num = RET_NUMBER;
	return (const struct module_register*)GET_POINT;
}



