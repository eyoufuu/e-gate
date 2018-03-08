#include "pro_analyse_game.h"
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


static inline u32 tcp_game_80_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x40) && (*(payload+2) == 0x02) && (*(payload+5) == 0x00) && (*(payload+6) == 0x82) && (*(payload+plen-1) == 0x03))		//qq游戏
				return PRO_QQGAME;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_443_0x63(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x06) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00))		//联众
				return PRO_LIANZHONG;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_443_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x40) && (*(payload+2) == 0x02) && (*(payload+5) == 0x00) && (*(payload+6) == 0x82) && (*(payload+plen-1) == 0x03))		//qq游戏
				return PRO_QQGAME;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_8000_0xFF(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 2)
		{
			if((*(payload+1) == 0x65) && (*(payload+2) == 0x00))		//中国游戏中心
				return PRO_CHINA_GAMECENTER;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_6020_0x76(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 1)
		{
			if(*(payload+1) == 0xAD)		//征途
				return PRO_ZHENGTU;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_3724_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 6)
		{
			if((*(payload+1) == 0x03) && (*(payload+4) == 0x57) && (*(payload+5) == 0x6F) && (*(payload+6) == 0x57))		//魔兽
				return PRO_MOSHOU;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_3028_0x44(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 1)
		{
			if(*(payload+1) == 0x00)		//远航
				return PRO_YUANHANG;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_1201_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+5) == 0x00) && (*(payload+7) == 0x00))		//浩方
				return PRO_HAOFANG;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_1203_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+5) == 0x00) && (*(payload+7) == 0x00))		//浩方
				return PRO_HAOFANG;
		}
	}
	return cat_ret;
}


static inline u32 tcp_game_5100_0x06(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x01) && (*(payload+2) == 0x00) && (*(payload+3) == 0x04))		//边锋
				return PRO_BIANFENG;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_4490_0x06(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x01) && (*(payload+2) == 0x00) && (*(payload+3) == 0x04))		//边锋
				return PRO_BIANFENG;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_25510_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		return PRO_JINGWUTUAN;
	}
	return cat_ret;
}

static inline u32 tcp_game_25511_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		return PRO_JINGWUTUAN;
	}
	return cat_ret;
}

static inline u32 tcp_game_5331_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 42)
		{
			if((*(payload+22) == 0x3C) && (*(payload+23) == 0x73) && (*(payload+24) == 0x74) && (*(payload+25) == 0x72) && (*(payload+26) == 0x65) 
						&& (*(payload+27) == 0x61) && (*(payload+28) == 0x6D) && (*(payload+29) == 0x3A) && (*(payload+30) == 0x73) && (*(payload+31) == 0x74) 
						&& (*(payload+32) == 0x72) && (*(payload+33) == 0x65) && (*(payload+34) == 0x61) && (*(payload+35) == 0x6D) && (*(payload+36) == 0x20) 
						&& (*(payload+37) == 0x74) && (*(payload+38) == 0x6F) && (*(payload+39) == 0x3D) && (*(payload+40) == 0x27) && (*(payload+41) == 0x69) && (*(payload+42) == 0x6D))		//VS游戏平台
				return PRO_GAMEPLAT_VS;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_1118_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 42)
		{
			if((*(payload+22) == 0x3C) && (*(payload+23) == 0x73) && (*(payload+24) == 0x74) && (*(payload+25) == 0x72) && (*(payload+26) == 0x65) 
						&& (*(payload+27) == 0x61) && (*(payload+28) == 0x6D) && (*(payload+29) == 0x3A) && (*(payload+30) == 0x73) && (*(payload+31) == 0x74) 
						&& (*(payload+32) == 0x72) && (*(payload+33) == 0x65) && (*(payload+34) == 0x61) && (*(payload+35) == 0x6D) && (*(payload+36) == 0x20) 
						&& (*(payload+37) == 0x74) && (*(payload+38) == 0x6F) && (*(payload+39) == 0x3D) && (*(payload+40) == 0x27) && (*(payload+41) == 0x69) && (*(payload+42) == 0x6D))		//VS游戏平台
				return PRO_GAMEPLAT_VS;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_1117_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 42)
		{
			if((*(payload+22) == 0x3C) && (*(payload+23) == 0x73) && (*(payload+24) == 0x74) && (*(payload+25) == 0x72) && (*(payload+26) == 0x65) 
						&& (*(payload+27) == 0x61) && (*(payload+28) == 0x6D) && (*(payload+29) == 0x3A) && (*(payload+30) == 0x73) && (*(payload+31) == 0x74) 
						&& (*(payload+32) == 0x72) && (*(payload+33) == 0x65) && (*(payload+34) == 0x61) && (*(payload+35) == 0x6D) && (*(payload+36) == 0x20) 
						&& (*(payload+37) == 0x74) && (*(payload+38) == 0x6F) && (*(payload+39) == 0x3D) && (*(payload+40) == 0x27) && (*(payload+41) == 0x69) && (*(payload+42) == 0x6D))		//VS游戏平台
				return PRO_GAMEPLAT_VS;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_1119_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 42)
		{
			if((*(payload+22) == 0x3C) && (*(payload+23) == 0x73) && (*(payload+24) == 0x74) && (*(payload+25) == 0x72) && (*(payload+26) == 0x65) 
						&& (*(payload+27) == 0x61) && (*(payload+28) == 0x6D) && (*(payload+29) == 0x3A) && (*(payload+30) == 0x73) && (*(payload+31) == 0x74) 
						&& (*(payload+32) == 0x72) && (*(payload+33) == 0x65) && (*(payload+34) == 0x61) && (*(payload+35) == 0x6D) && (*(payload+36) == 0x20) 
						&& (*(payload+37) == 0x74) && (*(payload+38) == 0x6F) && (*(payload+39) == 0x3D) && (*(payload+40) == 0x27) && (*(payload+41) == 0x69) && (*(payload+42) == 0x6D))		//VS游戏平台
				return PRO_GAMEPLAT_VS;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_6666_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 42)
		{
			if((*(payload+22) == 0x3C) && (*(payload+23) == 0x73) && (*(payload+24) == 0x74) && (*(payload+25) == 0x72) && (*(payload+26) == 0x65) 
						&& (*(payload+27) == 0x61) && (*(payload+28) == 0x6D) && (*(payload+29) == 0x3A) && (*(payload+30) == 0x73) && (*(payload+31) == 0x74) 
						&& (*(payload+32) == 0x72) && (*(payload+33) == 0x65) && (*(payload+34) == 0x61) && (*(payload+35) == 0x6D) && (*(payload+36) == 0x20) 
						&& (*(payload+37) == 0x74) && (*(payload+38) == 0x6F) && (*(payload+39) == 0x3D) && (*(payload+40) == 0x27) && (*(payload+41) == 0x69) && (*(payload+42) == 0x6D))		//VS游戏平台
				return PRO_GAMEPLAT_VS;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_5327_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 42)
		{
			if((*(payload+22) == 0x3C) && (*(payload+23) == 0x73) && (*(payload+24) == 0x74) && (*(payload+25) == 0x72) && (*(payload+26) == 0x65) 
						&& (*(payload+27) == 0x61) && (*(payload+28) == 0x6D) && (*(payload+29) == 0x3A) && (*(payload+30) == 0x73) && (*(payload+31) == 0x74) 
						&& (*(payload+32) == 0x72) && (*(payload+33) == 0x65) && (*(payload+34) == 0x61) && (*(payload+35) == 0x6D) && (*(payload+36) == 0x20) 
						&& (*(payload+37) == 0x74) && (*(payload+38) == 0x6F) && (*(payload+39) == 0x3D) && (*(payload+40) == 0x27) && (*(payload+41) == 0x69) && (*(payload+42) == 0x6D))		//VS游戏平台
				return PRO_GAMEPLAT_VS;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_1200_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 42)
		{
			if((*(payload+22) == 0x3C) && (*(payload+23) == 0x73) && (*(payload+24) == 0x74) && (*(payload+25) == 0x72) && (*(payload+26) == 0x65) 
						&& (*(payload+27) == 0x61) && (*(payload+28) == 0x6D) && (*(payload+29) == 0x3A) && (*(payload+30) == 0x73) && (*(payload+31) == 0x74) 
						&& (*(payload+32) == 0x72) && (*(payload+33) == 0x65) && (*(payload+34) == 0x61) && (*(payload+35) == 0x6D) && (*(payload+36) == 0x20) 
						&& (*(payload+37) == 0x74) && (*(payload+38) == 0x6F) && (*(payload+39) == 0x3D) && (*(payload+40) == 0x27) && (*(payload+41) == 0x69) && (*(payload+42) == 0x6D))		//VS游戏平台
				return PRO_GAMEPLAT_VS;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_5329_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 42)
		{
			if((*(payload+22) == 0x3C) && (*(payload+23) == 0x73) && (*(payload+24) == 0x74) && (*(payload+25) == 0x72) && (*(payload+26) == 0x65) 
						&& (*(payload+27) == 0x61) && (*(payload+28) == 0x6D) && (*(payload+29) == 0x3A) && (*(payload+30) == 0x73) && (*(payload+31) == 0x74) 
						&& (*(payload+32) == 0x72) && (*(payload+33) == 0x65) && (*(payload+34) == 0x61) && (*(payload+35) == 0x6D) && (*(payload+36) == 0x20) 
						&& (*(payload+37) == 0x74) && (*(payload+38) == 0x6F) && (*(payload+39) == 0x3D) && (*(payload+40) == 0x27) && (*(payload+41) == 0x69) && (*(payload+42) == 0x6D))		//VS游戏平台
				return PRO_GAMEPLAT_VS;
		}
	}
	return cat_ret;
}

static inline u32 tcp_game_5328_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 42)
		{
			if((*(payload+22) == 0x3C) && (*(payload+23) == 0x73) && (*(payload+24) == 0x74) && (*(payload+25) == 0x72) && (*(payload+26) == 0x65) 
						&& (*(payload+27) == 0x61) && (*(payload+28) == 0x6D) && (*(payload+29) == 0x3A) && (*(payload+30) == 0x73) && (*(payload+31) == 0x74) 
						&& (*(payload+32) == 0x72) && (*(payload+33) == 0x65) && (*(payload+34) == 0x61) && (*(payload+35) == 0x6D) && (*(payload+36) == 0x20) 
						&& (*(payload+37) == 0x74) && (*(payload+38) == 0x6F) && (*(payload+39) == 0x3D) && (*(payload+40) == 0x27) && (*(payload+41) == 0x69) && (*(payload+42) == 0x6D))		//VS游戏平台
				return PRO_GAMEPLAT_VS;
		}
	}
	return cat_ret;
}


//方向
//包
//数据包的长度，取出ip头部和tcp头部

unsigned int  analyse_game_tcp_80(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = tcp_game_80_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_443(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x63:
			cat_ret = tcp_game_443_0x63(dir, payload, plen);
		break;
		case 0x00:
			cat_ret = tcp_game_443_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_8000(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0xFF:
			cat_ret = tcp_game_8000_0xFF(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_6020(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x76:
			cat_ret = tcp_game_6020_0x76(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_3724(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = tcp_game_3724_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_3028(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x44:
			cat_ret = tcp_game_3028_0x44(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_1201(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x44:
			cat_ret = tcp_game_1201_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_1203(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x44:
			cat_ret = tcp_game_1203_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_5100(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x44:
			cat_ret = tcp_game_5100_0x06(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_4490(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x44:
			cat_ret = tcp_game_4490_0x06(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_25510(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = tcp_game_25510_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_25511(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = tcp_game_25511_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_game_tcp_5331(unsigned char dir,
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
		cat_ret = tcp_game_5331_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_game_tcp_1117(unsigned char dir,
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
		cat_ret = tcp_game_1117_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_game_tcp_1118(unsigned char dir,
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
		cat_ret = tcp_game_1118_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_game_tcp_1119(unsigned char dir,
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
		cat_ret = tcp_game_1119_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_game_tcp_6666(unsigned char dir,
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
		cat_ret = tcp_game_6666_XXXX(dir, payload, plen);
	return cat_ret;
}


unsigned int  analyse_game_tcp_5327(unsigned char dir,
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
		cat_ret = tcp_game_5327_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_game_tcp_1200(unsigned char dir,
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
		cat_ret = tcp_game_1200_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_game_tcp_5328(unsigned char dir,
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
		cat_ret = tcp_game_5328_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_game_tcp_5329(unsigned char dir,
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
		cat_ret = tcp_game_5329_XXXX(dir, payload, plen);
	return cat_ret;
}



static const struct module_register __dllregister[]={
       {"analyse_game_tcp_80",analyse_game_tcp_80,FUNC_RULE,100,IP_TCP,DIR_UP,80},
	{"analyse_game_tcp_443",analyse_game_tcp_443,FUNC_RULE,100,IP_TCP,DIR_UP,443},
	{"analyse_game_tcp_8000",analyse_game_tcp_8000,FUNC_RULE,100,IP_TCP,DIR_UP,8000},
	{"analyse_game_tcp_6020",analyse_game_tcp_6020,FUNC_RULE,100,IP_TCP,DIR_UP,6020},
	{"analyse_game_tcp_3724",analyse_game_tcp_3724,FUNC_RULE,100,IP_TCP,DIR_UP,3724},
	{"analyse_game_tcp_3028",analyse_game_tcp_3028,FUNC_RULE,100,IP_TCP,DIR_UP,3028},
	{"analyse_game_tcp_1201",analyse_game_tcp_1201,FUNC_RULE,100,IP_TCP,DIR_UP,1201},
   	{"analyse_game_tcp_1203",analyse_game_tcp_1203,FUNC_RULE,100,IP_TCP,DIR_UP,1203},
   	{"analyse_game_tcp_5100",analyse_game_tcp_5100,FUNC_RULE,100,IP_TCP,DIR_UP,5100},
   	{"analyse_game_tcp_4490",analyse_game_tcp_4490,FUNC_RULE,100,IP_TCP,DIR_UP,4490},
   	{"analyse_game_tcp_25510",analyse_game_tcp_25510,FUNC_RULE,100,IP_TCP,DIR_UP,25510},
   	{"analyse_game_tcp_25511",analyse_game_tcp_25511,FUNC_RULE,100,IP_TCP,DIR_UP,25511},
   	{"analyse_game_tcp_5331",analyse_game_tcp_5331,FUNC_RULE,100,IP_TCP,DIR_UP,5331},
   	{"analyse_game_tcp_1117",analyse_game_tcp_1117,FUNC_RULE,100,IP_TCP,DIR_UP,1117},
   	{"analyse_game_tcp_1118",analyse_game_tcp_1118,FUNC_RULE,100,IP_TCP,DIR_UP,1118},
   	{"analyse_game_tcp_1119",analyse_game_tcp_1119,FUNC_RULE,100,IP_TCP,DIR_UP,1119},
   	{"analyse_game_tcp_6666",analyse_game_tcp_6666,FUNC_RULE,100,IP_TCP,DIR_UP,6666},
   	{"analyse_game_tcp_5327",analyse_game_tcp_5327,FUNC_RULE,100,IP_TCP,DIR_UP,5327},
   	{"analyse_game_tcp_1200",analyse_game_tcp_1200,FUNC_RULE,100,IP_TCP,DIR_UP,1200},
   	{"analyse_game_tcp_5328",analyse_game_tcp_5328,FUNC_RULE,100,IP_TCP,DIR_UP,5328},
   	{"analyse_game_tcp_5329",analyse_game_tcp_5329,FUNC_RULE,100,IP_TCP,DIR_UP,5329}
};

const struct module_register* get_module_info_game(int * func_num)
{
	*func_num = RET_NUMBER;
	return (const struct module_register*)GET_POINT;
}


