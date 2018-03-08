#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <sys/time.h>
#include "pro_analyse_stock.h"                





static inline u32 tcp_stock_8000_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 7)
	{
		if((*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+4) == 0x03) && (*(payload+5) == 0x03) && (*(payload+6) == 0x00) && (*(payload+7) == 0x00))		//和讯股道
			return PRO_STOCK_HEXUN;
	}
	return cat_ret;
}

static inline u32 tcp_stock_22223_0x4C(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 9)
		{
			if((*(payload+1) == 0x4F) && (*(payload+2) == 0x4E) && (*(payload+3) == 0x47) && (*(payload+4) == 0x41) && (*(payload+5) == 0x43) 
										&& (*(payload+6) == 0x43) && (*(payload+7) == 0x4F) && (*(payload+8) == 0x55) && (*(payload+9) == 0x4E))		//大智慧
				return PRO_DAZHIHUI;
		}
	}
	return cat_ret;
}

static inline u32 tcp_stock_8601_0xFD(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 5)
		{
			if((*(payload+1) == 0xFD) && (*(payload+2) == 0xFD) && (*(payload+3) == 0xFD) && (*(payload+4) == 0x30) && (*(payload+5) == 0x30))		//同花顺
				return PRO_TONGHUASHUN;
		}
	}
	return cat_ret;
}

static inline u32 tcp_stock_8001_0xFD(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 5)
		{
			if((*(payload+1) == 0xFD) && (*(payload+2) == 0xFD) && (*(payload+3) == 0xFD) && (*(payload+4) == 0x30) && (*(payload+5) == 0x30))		//同花顺
				return PRO_TONGHUASHUN;
		}
	}
	return cat_ret;
}

static inline u32 tcp_stock_8888_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x01) && (*(payload+3) == 0x00))		//证券之星
				return PRO_STOCK_STAR;
		}
	}
	return cat_ret;
}

static inline u32 tcp_stock_7709_0xB1(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_DOWN == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0xCB) && (*(payload+2) == 0x74) && (*(payload+3) == 0x00))	
				return PRO_STOCK_TONGDAXIN;  //通达信
		}
	}
	return cat_ret;
}

static inline u32 tcp_stock_XXXX_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen>6)
	{
		if(get_u32(payload, 4) == plen-8)
			return PRO_DAZHIHUI;
	}
	return cat_ret;
}

static inline u32 tcp_stock_XXXX_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen>6)
	{
		if(get_u32(payload, 2) == plen-8)
			return PRO_DAZHIHUI;
	}
	return cat_ret;
}

//方向
//包
//数据包的长度，取出ip头部和tcp头部

unsigned int  analyse_stock_tcp_8000(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = tcp_stock_80_0x00(dir, payload, plen);
		break;
	}
#endif
	if(cat_ret == 0)
		cat_ret = tcp_stock_8000_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_stock_tcp_22223(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x4C:
			cat_ret = tcp_stock_22223_0x4C(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_stock_tcp_8601(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0xFD:
			cat_ret = tcp_stock_8601_0xFD(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_stock_tcp_8001(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0xFD:
			cat_ret = tcp_stock_8001_0xFD(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_stock_tcp_8888(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = tcp_stock_8888_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_stock_tcp_7709(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0xB1:
			cat_ret = tcp_stock_7709_0xB1(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_stock_tcp_XXXX(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = tcp_stock_XXXX_0x00(dir, payload, plen);
		break;
	}
	if(cat_ret == 0)
		cat_ret = tcp_stock_XXXX_XXXX(dir, payload, plen);
	return cat_ret;
}


static const struct module_register __dllregister[]={
       {"analyse_stock_tcp_8000",analyse_stock_tcp_8000,2,100,IP_TCP,DIR_IN,8000},
   	{"analyse_stock_tcp_22223",analyse_stock_tcp_22223,2,100,IP_TCP,DIR_UP,22223},
   	{"analyse_stock_tcp_8601",analyse_stock_tcp_8601,2,100,IP_TCP,DIR_UP,8601},
   	{"analyse_stock_tcp_8001",analyse_stock_tcp_8001,2,100,IP_TCP,DIR_UP,8001},
   	{"analyse_stock_tcp_8888",analyse_stock_tcp_8888,2,100,IP_TCP,DIR_UP,8888},
   	{"analyse_stock_tcp_7709",analyse_stock_tcp_7709,2,100,IP_TCP,DIR_DOWN,7709},
   	{"analyse_stock_tcp_XXXX",analyse_stock_tcp_XXXX,2,100,IP_TCP,DIR_IN,0}
};

const struct module_register* get_module_info_stock(int * func_num)
{
	*func_num = RET_NUMBER;
	return (const struct module_register*)GET_POINT;
}

