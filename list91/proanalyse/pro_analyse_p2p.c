#include "pro_analyse_p2p.h"
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


static inline u32 tcp_p2p_80_0xFE(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen >3)
		{
			if((*(payload+1) == 0x20) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+3) == 0x20))		//QQLIVE
				return PRO_QQLIVE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_80_0x3D(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+5) == 0x00) && (*(payload+6) == 0x00) && (*(payload+7) == 0x00))		//迅雷
				return PRO_XUNLEI;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_80_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 5)
	{
		if((*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+4) == 0xE9) && (*(payload+5) == 0x03))		//PPLIVE
				return PRO_PPLIVE;					//pplive
	}
	if(DIR_UP == up_down)
	{
		if(plen > 41)
		{
			if((*(payload+29) == 0x73) && (*(payload+30) == 0x61) && (*(payload+31) == 0x6E) && (*(payload+32) == 0x64) && (*(payload+33) == 0x61) 
										&& (*(payload+34) == 0x69) && (*(payload+35) == 0x2E) && (*(payload+36) == 0x6E) && (*(payload+37) == 0x65)
										&& (*(payload+38) == 0x74) && (*(payload+39) == 0x2E) && (*(payload+40) == 0x38) && (*(payload+41) == 0x30))	
				return PRO_XUNLEI;       //迅雷
		}
		if(plen > 17)
		{
			if((*(payload+5) == 0x42) && (*(payload+6) == 0x61) && (*(payload+7) == 0x69) && (*(payload+8) == 0x64) && (*(payload+9) == 0x75) 
										&& (*(payload+10) == 0x50) && (*(payload+11) == 0x32) && (*(payload+12) == 0x50) && (*(payload+13) == 0x2D)
										&& (*(payload+14) == 0x50) && (*(payload+15) == 0x65) && (*(payload+16) == 0x65) && (*(payload+17) == 0x72))	
				return PRO_BAIDU_XIABA;       //百度下吧
		}
		if(plen > 22)
		{
			if((*(payload+12) == 0x73) && (*(payload+13) == 0x34) && (*(payload+14) == 0x2E) && (*(payload+15) == 0x66) && (*(payload+16) == 0x6C) 
										&& (*(payload+17) == 0x61) && (*(payload+18) == 0x73) && (*(payload+19) == 0x68) && (*(payload+20) == 0x67)
										&& (*(payload+21) == 0x65) && (*(payload+22) == 0x74))	
				return PRO_FLASHGET;       //flashget
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_8080_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 6)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x01) && (*(payload+4) == 0x00) && (*(payload+5) == 0x00) && (*(payload+6) == 0x01))		//BITTORRENT
				return PRO_BITTORRENT;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_8080_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+2) == 0x32) && (*(payload+3) == 0x01) && (*(payload+6) == 0x00) && (*(payload+7) == 0x00))		//沸点网络电视
				return PRO_FEIDIAN_NETTV;
		}
		if(plen > 11)
		{
			if((*(payload+8) == 0x3D) && (*(payload+9) == 0x00) && (*(payload+10) == 0x00) && (*(payload+11) == 0x00))		//迅雷
				return PRO_XUNLEI;
		}
		if(plen > 12)
		{
			if((*(payload+9) == 0x3C) && (*(payload+10) == 0x00) && (*(payload+11) == 0x00) && (*(payload+12) == 0x00))		//迅雷
				return PRO_XUNLEI;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_443_0x02(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 4)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+plen-1) == 0x03))		//QQLIVE
				return PRO_QQLIVE;
		}
		if(plen > 8)
		{
			if((*(payload+1) == 0x00) && (*(payload+6) == 0x01) && (*(payload+7) == 0x00) && (*(payload+plen-1) == 0x03))
				return PRO_SUPER_XUANFENG;   //超级旋风
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_443_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 4)
		{
			if((*(payload+1) == 0x3E) && (*(payload+2) == 0x02) && (*(payload+3) == 0x18) && (*(payload+plen-1) == 0x03))		//QQLIVE
				return PRO_QQLIVE;
		}
		if(plen > 6)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x8F) && (*(payload+4) == 0x80) && (*(payload+5) == 0xF9) && (*(payload+6) == 0x9B))		//UUSee
				return PRO_UUSEE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_443_0x47(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 138)
		{
			if((*(payload+1) == 0x45) && (*(payload+2) == 0x54) && (*(payload+3) == 0x20) && (*(payload+4) == 0x2F) && (*(payload+5) == 0x3F) 
								&& (*(payload+135) == 0x48) && (*(payload+136) == 0x54) && (*(payload+137) == 0x54) && (*(payload+138) == 0x50))		//超级旋风
				return PRO_SUPER_XUANFENG;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_8000_0x02(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 4)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+plen-1) == 0x03))		//QQLIVE
				return PRO_QQLIVE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_554_0x52(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_DOWN == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x54) && (*(payload+2) == 0x53) && (*(payload+3) == 0x50) && (*(payload+4) == 0x2F) && (*(payload+5) == 0x31) && (*(payload+6) == 0x2E) && (*(payload+7) == 0x30))		//RTSP
				return PRO_RTSP;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_4672_0xE4(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 2)
		{
			if((*(payload+1) == 0x10) && (*(payload+plen-1) == 0x00))		//totolook
				return PRO_TOTOLOOK;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_5598_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 35)
		{
			if((*(payload+32) == 0x00) && (*(payload+33) == 0xB3) && (*(payload+34) == 0x28) && (*(payload+35) == 0x3A))		//青娱乐
				return PRO_QINGYULE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_8443_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x01) && (*(payload+4) == 0x00) && (*(payload+5) == 0x00) && (*(payload+6) == 0x02) && (*(payload+7) == 0x00))		//MYSEE
				return PRO_MYSEE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_3076_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00))		//迅雷
				return PRO_XUNLEI;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_3077_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00))		//迅雷
				return PRO_XUNLEI;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_3078_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00))		//迅雷
				return PRO_XUNLEI;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_443_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 4)
	{
		if(get_u8(payload,0) == plen-4)
		{
			if(*(payload+1) == 0x00 && *(payload+2) == 0x00 && *(payload+3) == 0x00)
				return PRO_UUSEE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_3468_0x47(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 12)
		{
			if((*(payload+1) == 0x45) && (*(payload+2) == 0x54) && (*(payload+3) == 0x20) && (*(payload+4) == 0x2F) && (*(payload+12) == 0x2F))	
				return PRO_BAIBAO;  //百宝
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_XXXX_0x13(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 19)
		{
			if((*(payload+1) == 0x42) && (*(payload+2) == 0x69) && (*(payload+3) == 0x74) && (*(payload+4) == 0x54) && (*(payload+5) == 0x6F) 
						&& (*(payload+6) == 0x72) && (*(payload+7) == 0x72) && (*(payload+8) == 0x65) && (*(payload+9) == 0x6E) && (*(payload+10) == 0x74) 
						&& (*(payload+11) == 0x20) && (*(payload+12) == 0x70) && (*(payload+13) == 0x72) && (*(payload+14) == 0x6F) && (*(payload+15) == 0x74)
						&& (*(payload+16) == 0x6F) && (*(payload+17) == 0x63) && (*(payload+18) == 0x6F) && (*(payload+19) == 0x6C))
				return PRO_BITTORRENT;    //bittorrent
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_XXXX_0x50(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 9)
		{
			if((*(payload+1) == 0x53) && (*(payload+2) == 0x50) && (*(payload+3) == 0x72) && (*(payload+4) == 0x6F) && (*(payload+5) == 0x74) 
						&& (*(payload+6) == 0x6F) && (*(payload+7) == 0x63) && (*(payload+8) == 0x6F) && (*(payload+9) == 0x6C))
				return PRO_PPSTREAM;    //PPSTREAM 
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_XXXX_0xE9(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 4)
		{
			if((*(payload+1) == 0x03) && (*(payload+2) == 0x41) && (*(payload+3) == 0x01) && (*(payload+4) == 0x98))		//PPLIVE
				return PRO_PPLIVE;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_XXXX_0x47(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_DOWN == up_down)
	{
		if(plen > 9)
		{
			if((*(payload+1) == 0x45) && (*(payload+2) == 0x54) && (*(payload+3) == 0x20) && (*(payload+4) == 0x2F) && (*(payload+5) == 0x2E) 
									&& (*(payload+6) == 0x68) && (*(payload+7) == 0x61) && (*(payload+8) == 0x73) && (*(payload+9) == 0x68))		//KAZAA
				return PRO_KAZAA; 
		}
		if(plen > 12)
		{
			if((*(payload+1) == 0x45) && (*(payload+2) == 0x54) && (*(payload+3) == 0x20) && (*(payload+4) == 0x2F) && (*(payload+5) == 0x64) 
									&& (*(payload+6) == 0x61) && (*(payload+7) == 0x74) && (*(payload+8) == 0x61) && (*(payload+9) == 0x3F)
									&& (*(payload+10) == 0x66) && (*(payload+11) == 0x69) && (*(payload+12) == 0x64))		//BITTORRENT
				return PRO_BITTORRENT;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_XXXX_0x3D(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_DOWN == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+4) == 0x30) 
									&& (*(payload+5) == 0x00) && (*(payload+6) == 0x00) && (*(payload+7) == 0x00))		//迅雷
				return PRO_XUNLEI;
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_XXXX_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 7)
	{
		if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x65) && (*(payload+4) == 0x01) 
									&& (*(payload+5) == 0x2D) && (*(payload+6) == 0xD6) && (*(payload+7) == 0x68))		//超级旋风
				return PRO_SUPER_XUANFENG;
	}
	return cat_ret;
}

static inline u32 tcp_p2p_XXXX_0xFE(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 6)
	{
		if((get_u8(payload,1) == get_u8(payload,4)) && (get_u8(payload,2) == get_u8(payload,3)) &&  (get_u16(payload, 1) == plen-3))
			return PRO_SUPER_XUANFENG;  //超级旋风
	}
	return cat_ret;
}

static inline u32 tcp_p2p_XXXX_0x02(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 5)
	{
		if((*(payload+1) == 0x01) && (*(payload+2) == 0x11))		//超级旋风
		{
			u16 len = *((u16 *)(payload+3));
			if(__nhs(len) == plen)
			{
				if(*(payload+plen-1) == 0x03)
					return PRO_SUPER_XUANFENG;
			}
			else
			{
				if(DIR_DOWN == up_down)
					return PRO_SUPER_XUANFENG;
			}
		}
	}
	return cat_ret;
}

static inline u32 tcp_p2p_XXXX_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0xA0:
		case 0xE3:
		case 0xC5:
			{
				if(plen >= 6)
				{
					u32 rec32_bytes = get_u32(payload,1);
					if(rec32_bytes+5 == plen)
						return PRO_EMULE; //标识一字节+ 4字节长度
					/////////////////////////////////////经过测试这个还是有的
					if(plen>= rec32_bytes +6)
					{
						u32 tmp = rec32_bytes+5;
						u32 recx = get_u8(payload,tmp);  //这里解决了回绕的问题，一个tcp包里面包含多个emule短包
						if(recx == 0xa0 || recx == 0xe3 || recx == 0xc5) 
							return PRO_EMULE;
					}
				}
			}
		break;
		case 0x3c:
        		if(plen>=9)
        		{
				if(get_u32(payload,0) == 0x0000003c)
					return PRO_XUNLEI;
        		}
		break;
		case 0x3e:
			if(plen >= 9)
			{
				if(get_u32(payload,0)==0x0000003e)
				{
					if(plen== get_u32(payload,4)+8)
						return PRO_XUNLEI;
				}
			}
		break;
	}
	if(plen > 4)
	{
		if(get_u8(payload,0) == plen-4)
		{
			if(*(payload+1) == 0x00 && *(payload+2) == 0x00 && *(payload+3) == 0x00)
				return PRO_UUSEE;
		}
	}
    if(plen>14)
    {
        if(get_u32(payload,10) == 0x00000103)
        {
            return PRO_XUNLEI;
        }
    }
	return cat_ret;
}

static inline u32 udp_p2p_8000_0x13(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 2)
		{
			if((*(payload+1) == 0x50) && (*(payload+2) == 0x07))		//UUSEE
				return PRO_UUSEE;
		}
		if(plen == 1024)
		{
			if(get_u32(payload,0) == 0x0B084E13)
				return PRO_UUSEE;
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_8000_0x09(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 4)
		{
			if(get_u16(payload,1) == 0x0809)
				return PRO_UUSEE;
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_8000_0xA3(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 4)
		{
			if(get_u16(payload,0) == 0x35A3)
				return PRO_UUSEE;
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_8000_0x86(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_DOWN == up_down)
	{
		if(plen > 4)
		{
			if(get_u32(payload,0) == 0x4BD84786)
				return PRO_UUSEE;
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_8000_0x14(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen == 8)
	{
		if((*(payload+1) == 0x05) && (*(payload+2) == 0x07))		//UUSEE
			return PRO_UUSEE;
	}
	return cat_ret;
}

static inline u32 udp_p2p_4662_0xE3(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 5)
		{
			if((*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+4) == 0x00) && (*(payload+5) == 0x10))
				return PRO_EMULE;   //电驴
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_9099_0x04(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 5)
		{
			if((*(payload+1) == 0x16) && (*(payload+2) == 0x50) && (*(payload+3) == 0x4F) && (*(payload+4) == 0x43) && (*(payload+5) == 0x4F))
				return PRO_PP_DIANDIANTONG;    //PP点点通
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_12110_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 19)
		{
			if((*(payload+14) == 0x41) && (*(payload+15) == 0xB5) && (*(payload+16) == 0x3A) && (*(payload+17) == 0xC0) && (*(payload+18) == 0xB7) && (*(payload+19) == 0x06))
				return PRO_BAIDU_XIABA;    //baidu下吧
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_12111_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 19)
		{
			if((*(payload+14) == 0x41) && (*(payload+15) == 0xB5) && (*(payload+16) == 0x3A) && (*(payload+17) == 0xC0) && (*(payload+18) == 0xB7) && (*(payload+19) == 0x06))
				return PRO_BAIDU_XIABA;    //baidu下吧
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_11111_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 19)
		{
			if((*(payload+14) == 0x41) && (*(payload+15) == 0xB5) && (*(payload+16) == 0x3A) && (*(payload+17) == 0xC0) && (*(payload+18) == 0xB7) && (*(payload+19) == 0x06))
				return PRO_BAIDU_XIABA;    //baidu下吧
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_11112_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 19)
		{
			if((*(payload+14) == 0x41) && (*(payload+15) == 0xB5) && (*(payload+16) == 0x3A) && (*(payload+17) == 0xC0) && (*(payload+18) == 0xB7) && (*(payload+19) == 0x06))
				return PRO_BAIDU_XIABA;    //baidu下吧
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_443_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 9)
	{
		if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x10) && (*(payload+8) == 0x00) && (*(payload+9) == 0x00))
			return PRO_MYSEE;    //mysee
	}
	return cat_ret;
}

static inline u32 udp_p2p_80_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 9)
	{
		if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x10) && (*(payload+8) == 0x00) && (*(payload+9) == 0x00))
			return PRO_MYSEE;    //mysee
	}
	return cat_ret;
}

static inline u32 udp_p2p_8080_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 9)
	{
		if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x10) && (*(payload+8) == 0x00) && (*(payload+9) == 0x00))
			return PRO_MYSEE;    //mysee
	}
	return cat_ret;
}

static inline u32 udp_p2p_53124_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+2) == 0x32) && (*(payload+3) == 0x01)) 
				return PRO_FEIDIAN_NETTV;    //沸点网络电视
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_53125_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+2) == 0x32) && (*(payload+3) == 0x01)) 
				return PRO_FEIDIAN_NETTV;    //沸点网络电视
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_53126_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 3)
		{
			if((*(payload+2) == 0x32) && (*(payload+3) == 0x01)) 
				return PRO_FEIDIAN_NETTV;    //沸点网络电视
		}
	}
	return cat_ret;
}


static inline u32 udp_p2p_XXXX_0x32(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 12)
	{
		if((*(payload+2) == 0x00) && (*(payload+3) == 0x00))
			return PRO_XUNLEI;  //迅雷
	}
	if(plen>5)
	{
		if(*(payload+4)==0x11 || *(payload+4) ==0x12)
			return PRO_XUNLEI;
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0xC9(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 12)
	{
		if((*(payload+6) == 0x69) && (*(payload+9) == 0x64) && (*(payload+10) == 0x66) && (*(payload+11) == 0x60) && (*(payload+12) == 0x25))
			return PRO_PP_DOG;  //屁屁狗
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0xE9(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 4)
		{
			if((*(payload+1) == 0x03) && (*(payload+2) == 0x41) && (*(payload+3) == 0x01) && (*(payload+4) == 0x98))
				return PRO_PP_DOG;  //pplive
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0x00(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if (get_u32(payload, 0) == 0xF5030000)
		return PRO_SAFE360_UPDATE;   //360在线升级
	if((plen == 525) && get_u32(payload, 0) == 0x0d020000)
		return PRO_QVOD;
	if(get_u32(payload, 0) == 0x0d000000)
		return PRO_QVOD;
	if(DIR_UP == up_down)
	{
		if(plen > 13)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload+10) == 0x1D) && (*(payload+11) == 0xE0) && (*(payload+12) == 0xE2) && (*(payload+13) == 0xC9))
				return PRO_BITTORRENT;  //bittorrent
		}
	}
	switch(plen)
	{
		case 16:
			if ((get_u32(payload, 0) == 0x17040000) && (get_u32(payload, 4) == 0x80191027))
				return PRO_BITTORRENT;  //bittorrent
			break;
		case 36:
			if (get_u32(payload, 8) == 0x00040000)
				return PRO_BITTORRENT;
			break;
		case 57:
			if (get_u32(payload, 8) == 0x04040000)
				return PRO_BITTORRENT;
			break;
		case 59:
			if (get_u32(payload, 8) == 0x06040000)
				return PRO_BITTORRENT;
			break;
		case 203:
			if (get_u32(payload, 0) == 0x05040000)
				return PRO_BITTORRENT;
			break;
		case 21:
			if (get_u32(payload, 0) == 0x01040000)
				return PRO_BITTORRENT;
			break;
		case 44:
			if ((get_u32(payload,0)  == 0x27080000) && (get_u32(payload,4) == 0x50295037))
				return PRO_BITTORRENT;
			break;
		default:
			if(plen >= 32)
			{
				if ((get_u32(payload, 8) == 0x02040000)&& (get_u32(payload, 28) ==0x04010000))
					return PRO_BITTORRENT;
			}
			break;
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0xFE(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 6)
	{
		if((get_u8(payload,1) == get_u8(payload,4)) && (get_u8(payload,2) == get_u8(payload,3)) &&  (get_u16(payload, 1) == plen-3))
			return PRO_SUPER_XUANFENG;  //超级旋风
	}
	if(plen == 1050)
	{
		if((get_u32(payload,0) == 0x000417fe))
			return PRO_SUPER_XUANFENG;  //超级旋风
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0x64(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 22)
		{
			if((*(payload+1) == 0x31) && (*(payload+2) == 0x3A) && (*(payload+3) == 0x61) && (*(payload+4) == 0x64) && (*(payload+5) == 0x32) && (*(payload+6) == 0x3A) 
																&& (*(payload+7) == 0x69) && (*(payload+8) == 0x64) && (*(payload+9) == 0x32) && (*(payload+10) == 0x30) && (*(payload+11) == 0x3A))
				return PRO_BITTORRENT;  //bittorrent 
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0x2C(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(DIR_UP == up_down)
	{
		if(plen > 7)
		{
			if((*(payload+1) == 0x00) && (*(payload+2) == 0x02) && (*(payload+3) == 0x12) && (*(payload+4) == 0xFC) && (*(payload+5) == 0x13) && (*(payload+6) == 0xD9) && (*(payload+7) == 0xA1))
				return PRO_VJBASE;  //VJBASE
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0x7E(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 3)
	{
		if (get_u32(payload, 0) == 0xE7E77E7E)
			return PRO_P2P_TUDOU;  //土豆
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0xFF(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen == 4)
	{
		if(*(payload+1)==0x0a )   //0x00000aff
		{
			if(get_u16(payload,2)==0x0000)
			{
				return PRO_EMULE;
			}
		}
	}
	else if(plen == 8)
	{
		if(*(payload+1)==0x0a)
		{
			if(get_u16(payload,4)==0x020c)
			{
				return PRO_EMULE;
			}
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0xE3(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	switch (*(payload+1))
	{
		/* client -> server status request */
	case 0x96:
		if (plen > 5) 
			return PRO_EMULE;
		break;
		/* server -> client status request */
	case 0x97: 
		if (plen > 33) 
			return PRO_EMULE;
		break;
		/* server description request */
		/* e3 2a ff f0 .. | size == 6 */
	case 0xa2: 
		if ( (plen > 5) && ( get_u16(payload,2) == 0xFF) ) 
			return PRO_EMULE;/*__constant_htons(0xfff0)*/ 
		break;
		/* server description response */
		/* e3 a3 ff f0 ..  | size > 40 && size < 200 */
		//case 0xa3: return ((IPP2P_EDK * 100) + 53);
		//	break;
	case 0x9a: 
		if (plen > 17) 
			return PRO_EMULE;
		break;
	case 0x9b: 
		if (plen > 24) 
			return PRO_EMULE;
		break;
	case 0x92: 
		if (plen > 9) 
			return PRO_EMULE;
		break;
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0xE4(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	switch (*(payload+1))
	{
	case 0x01:
		if (plen == 2) 
			return PRO_EMULE;
		break;
	case 0x19: 
		if(plen == 28)
			return PRO_XUNLEI;
		if (plen > 21) 
			return PRO_EMULE;
		break;
	case 0x20:
	case 0x21:
		if ( (plen > 33) && ( *(payload+2) != 0x00)  && ( *(payload+33) != 0x00) ) 
			return PRO_EMULE;
		break;
	case 0x00: 
	case 0x10: 
		if ((plen > 26) && (*(payload+26) == 0x00)) 
			return PRO_EMULE;
		break;
	case 0x11: 
		{
			if ((plen ==38) && (*(payload+plen-1) == 0x00))
				return PRO_XUNLEI;
			if (((plen > 26) && (*(payload+26) == 0x00)) || (*(payload+plen-1) == 0x00))
				return PRO_EMULE;
		}
		break;
	case 0x18: 
		if (((plen > 26) && (*(payload+26) == 0x00)) || (*(payload+plen-1) == 0x00))
			return PRO_EMULE;
		break;
		/* e4 52 .. | size = 44 */
	case 0x52: 
		if (plen > 35 ) 
			return PRO_EMULE;
		break;
		/* e4 58 .. | size == 6 */
	case 0x58: 
		if (plen > 5 ) 
			return PRO_EMULE;
		break;
		/* e4 59 .. | size == 2 */
	case 0x59: 
		if (plen > 1 )
			return PRO_EMULE;
		break;
		/* e4 28 .. | packet_len == 52,77,102,127... */
	case 0x28: 
	case 0x29: 
		if (((plen-44) % 25) == 0) 
			return PRO_EMULE;
		break;
		/* e4 50 xx xx | size == 4 */
	case 0x50: 
		if (plen > 3) 
			return PRO_EMULE;
		break;
		/* e4 40 xx xx | size == 48 */
	case 0x40: 
		if (plen > 47) 
			return PRO_EMULE;
		break;
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0x25(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 8)
	{
		if((*(payload+1) == 0x00) && (*(payload+2) == 0x00) && (*(payload+3) == 0x00) && (*(payload-4) == 0x00) && (*(payload-3) == 0x00) && (*(payload-2) == 0x00) && (*(payload-1) == 0x00))
			return PRO_PPLIVE;  //Pplive
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0xF1(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	int len = get_u32(payload,1);//取四个字节的长度
	if(len+5 == plen)
	{
		if(*(payload+5) == 0xea || *(payload+5)==0xeb || *(payload+5)==0xe2 || *(payload+5) == 0xe9)
		{
			return PRO_EMULE;
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0x13(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen > 2)
	{
		if((*(payload+1) == 0x50) && (*(payload+2) == 0x07))		//UUSEE
			return PRO_UUSEE;
	}
	if(DIR_UP == up_down)
	{
		if(plen == 1024)
		{
			if(get_u32(payload,0) == 0x0B084E13)
				return PRO_UUSEE;
		}
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0x14(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen == 8)
	{
		if((*(payload+1) == 0x05) && (*(payload+2) == 0x07))		//UUSEE
			return PRO_UUSEE;
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_0x01(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen == 28)
	{
		return PRO_PPLIVE;
	}
	return cat_ret;
}

static inline u32 udp_p2p_XXXX_XXXX(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	u32 cat_ret = 0;
	if(plen >= 336)
	{
		if((*(payload+68) == 0x49) && (*(payload+69) == 0x4E) && (*(payload+70) == 0x56) && (*(payload+71) == 0x49) && (*(payload+72) == 0x54) && (*(payload+73) == 0x45) && (memcmp((payload+300), "5D3E02AB-6190-11D3-BBBB-00C04F795683", 36)==0))
			return PRO_PPLIVE;  //PPLIVE
	}
	if(((*(payload+6) == 0x0C) && (*(payload+7) == 0x00) && (*(payload+8) == 0x00) && (*(payload+9) == 0x01)) || ((*(payload+7) == 0x00) && (*(payload+8) == 0x00) && (*(payload+9) == 0x01) && (*(payload+10) == 0x01)))
			return PRO_PPLIVE;  //PPLIVE
	if (*(payload+plen-1) == 0x00)
	{
		if (memcmp((payload+plen-6), "KaZaA", 5) == 0) 
			return PRO_KAZAA;
	}
	if(*(payload + 2) == 0x43)
	{
		u16 packet_len = get_u16(payload,0);
		if((packet_len== plen-4) || (packet_len == plen))
			return PRO_PPSTREAM;
	}
	if(DIR_DOWN == up_down)
	{
		if(plen==71)
		{
			if((get_u8(payload, 53) == get_u8(payload,49)+1) && (get_u8(payload, 57) == get_u8(payload,53)+1) && (get_u8(payload, 61) == get_u8(payload,57)+1) && (get_u8(payload, 65) == get_u8(payload,61)+1) && (get_u8(payload, 69) == get_u8(payload,65)+1))
				return PRO_PPLIVE;
		}
	}
	return cat_ret;
}

/**
 * \brief 以下的为要注册的函数
 * \param dir 方向
 * \param payload 包
 * \param plen 数据包的长度，取出IP头部和TCP头部
 */
 
unsigned int  analyse_p2p_tcp_80(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0xFE:
			cat_ret = tcp_p2p_80_0xFE(dir, payload, plen);
		break;
		case 0x3D:
			cat_ret = tcp_p2p_80_0x3D(dir, payload, plen);
		break;
	}
	if(cat_ret == 0)
		cat_ret = tcp_p2p_80_XXXX(dir, payload, plen);

}

unsigned int  analyse_p2p_tcp_8080(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x00:
			cat_ret = tcp_p2p_8080_0x00(dir, payload, plen);
		break;
	}
	if(cat_ret == 0)
		cat_ret = tcp_p2p_8080_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_443(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x02:
			cat_ret = tcp_p2p_443_0x02(dir, payload, plen);
		break;
		case 0x00:
			cat_ret = tcp_p2p_443_0x00(dir, payload, plen);
		break;
		case 0x47:
			cat_ret = tcp_p2p_443_0x47(dir, payload, plen);
		break;
	}
	if(cat_ret == 0)
		cat_ret = tcp_p2p_443_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_8000(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x02:
			cat_ret = tcp_p2p_8000_0x02(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_554(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x02:
			cat_ret = tcp_p2p_554_0x52(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_4672(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0xE4:
			cat_ret = tcp_p2p_4672_0xE4(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_5598(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
//	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0xE4:
			cat_ret = tcp_p2p_4672_0xE4(dir, payload, plen);
		break;
	}
#endif
//	if(cat_ret == 0)
		cat_ret = tcp_p2p_5598_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_8443(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0xE4:
			cat_ret = tcp_p2p_8443_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_3077(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
//	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0xE4:
			cat_ret = tcp_p2p_4672_0xE4(dir, payload, plen);
		break;
	}
#endif
//	if(cat_ret == 0)
		cat_ret = tcp_p2p_3077_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_3078(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
//	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0xE4:
			cat_ret = tcp_p2p_4672_0xE4(dir, payload, plen);
		break;
	}
#endif
	//if(cat_ret == 0)
		cat_ret = tcp_p2p_3078_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_3076(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
//	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0xE4:
			cat_ret = tcp_p2p_4672_0xE4(dir, payload, plen);
		break;
	}
#endif
//	if(cat_ret == 0)
		cat_ret = tcp_p2p_3076_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_3468(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x47:
			cat_ret = tcp_p2p_3468_0x47(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_tcp_XXXX(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x13:
			cat_ret = tcp_p2p_XXXX_0x13(dir, payload, plen);
		break;
		case 0x50:
			cat_ret = tcp_p2p_XXXX_0x50(dir, payload, plen);
		break;
		case 0xE9:
			cat_ret = tcp_p2p_XXXX_0xE9(dir, payload, plen);
		break;
		case 0x47:
			cat_ret = tcp_p2p_XXXX_0x47(dir, payload, plen);
		break;
		case 0x3D:
			cat_ret = tcp_p2p_XXXX_0x3D(dir, payload, plen);
		break;
		case 0x00:
			cat_ret = tcp_p2p_XXXX_0x00(dir, payload, plen);
		break;
		case 0xFE:
			cat_ret = tcp_p2p_XXXX_0xFE(dir, payload, plen);
		break;
		case 0x02:
			cat_ret = tcp_p2p_XXXX_0x02(dir, payload, plen);
		break;
	}
	if(cat_ret == 0)
		cat_ret = tcp_p2p_XXXX_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_udp_8000(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x13:
			cat_ret = udp_p2p_8000_0x13(dir, payload, plen);
		break;
		case 0x09:
			cat_ret = udp_p2p_8000_0x09(dir, payload, plen);
		break;
		case 0xA3:
			cat_ret = udp_p2p_8000_0xA3(dir, payload, plen);
		break;
		case 0x86:
			cat_ret = udp_p2p_8000_0x86(dir, payload, plen);
		break;
		case 0x14:
			cat_ret = udp_p2p_8000_0x14(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_udp_4662(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0xE3:
			cat_ret = udp_p2p_4662_0xE3(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_udp_9099(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x04:
			cat_ret = udp_p2p_9099_0x04(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_udp_12110(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
//	u8 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0x04:
			cat_ret = udp_p2p_9099_0x04(dir, payload, plen);
		break;
	}
#endif
//	if(cat_ret == 0)
		cat_ret = udp_p2p_12110_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_udp_12111(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
//	u8 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0x04:
			cat_ret = udp_p2p_9099_0x04(dir, payload, plen);
		break;
	}
#endif
//	if(cat_ret == 0)
		cat_ret = udp_p2p_12111_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_udp_11111(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
//	u8 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0x04:
			cat_ret = udp_p2p_9099_0x04(dir, payload, plen);
		break;
	}
#endif
//	if(cat_ret == 0)
		cat_ret = udp_p2p_11111_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_udp_11112(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
//	u8 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0x04:
			cat_ret = udp_p2p_9099_0x04(dir, payload, plen);
		break;
	}
#endif
//	if(cat_ret == 0)
		cat_ret = udp_p2p_11112_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_udp_443(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x00:
			cat_ret = udp_p2p_443_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_udp_80(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x00:
			cat_ret = udp_p2p_80_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_udp_8080(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
	switch(firstbyte)
	{
		case 0x00:
			cat_ret = udp_p2p_8080_0x00(dir, payload, plen);
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_udp_53124(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0x04:
			cat_ret = udp_p2p_9099_0x04(dir, payload, plen);
		break;
	}
#endif
	if(cat_ret == 0)
		cat_ret = udp_p2p_53124_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_udp_53125(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0x04:
			cat_ret = udp_p2p_9099_0x04(dir, payload, plen);
		break;
	}
#endif
	if(cat_ret == 0)
		cat_ret = udp_p2p_53125_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_udp_53126(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	u32 firstbyte = *payload;
#if 0
	switch(firstbyte)
	{
		case 0x04:
			cat_ret = udp_p2p_9099_0x04(dir, payload, plen);
		break;
	}
#endif
	if(cat_ret == 0)
		cat_ret = udp_p2p_53126_XXXX(dir, payload, plen);
	return cat_ret;
}

unsigned int  analyse_p2p_udp_innerport_7600(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	
	if((*payload) == 0x01 && (*(payload+1))==0x01)
		return PRO_FLASHGET;
	switch(*payload)
	{
		case 0x01:
			if(*(payload+1) == 0x01)
			return PRO_FLASHGET;    //网际快车
		break;
		case 0x02:
			if((get_u32(payload,29))==0x04000000)
				return PRO_FLASHGET;
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_udp_innerport_4645(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x21:
			if((*(payload+1))==0x00)
				return PRO_PPLIVE;
		break;
		case 0x22:
			if((*(payload+1))==0x00)
				return PRO_PPLIVE;
		break;
	}
	return cat_ret;
}

unsigned int  analyse_p2p_udp_XXXX(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	u32 cat_ret = 0;
	switch(*payload)
	{
		case 0x32:
			cat_ret = udp_p2p_XXXX_0x32(dir, payload, plen);
		break;
		case 0xC9:
			cat_ret = udp_p2p_XXXX_0xC9(dir, payload, plen);
		break;
		case 0xE9:
			cat_ret = udp_p2p_XXXX_0xE9(dir, payload, plen);
		break;
		case 0x00:
			cat_ret = udp_p2p_XXXX_0x00(dir, payload, plen);
		break;
		case 0xFE:
			cat_ret = udp_p2p_XXXX_0xFE(dir, payload, plen);
		break;
		case 0x64:
			cat_ret = udp_p2p_XXXX_0x64(dir, payload, plen);
		break;
		case 0x2C:
			cat_ret = udp_p2p_XXXX_0x2C(dir, payload, plen);
		break;
		case 0x7E:
			cat_ret = udp_p2p_XXXX_0x7E(dir, payload, plen);
		break;
		case 0xFF:
			cat_ret = udp_p2p_XXXX_0xFF(dir, payload, plen);
		break;
		case 0xE3:
			cat_ret = udp_p2p_XXXX_0xE3(dir, payload, plen);
		break;
		case 0xE4:
			cat_ret = udp_p2p_XXXX_0xE4(dir, payload, plen);
		break;
		case 0x25:
			cat_ret = udp_p2p_XXXX_0x25(dir, payload, plen);
		break;
		case 0xF1:
			cat_ret = udp_p2p_XXXX_0xF1(dir, payload, plen);
		break;
		case 0x13:
			cat_ret = udp_p2p_XXXX_0x13(dir, payload, plen);
		break;
		case 0x14:
			cat_ret = udp_p2p_XXXX_0x14(dir, payload, plen);
		break;
		case 0x01:
			cat_ret = udp_p2p_XXXX_0x01(dir, payload, plen);
		break;
	}
	if(cat_ret == 0)
		cat_ret = udp_p2p_XXXX_XXXX(dir, payload, plen);
	return cat_ret;
}

static const struct module_register __dllregister[]={
       {"analyse_p2p_tcp_80",analyse_p2p_tcp_80,FUNC_RULE,100,IP_TCP,DIR_IN,80},
	{"analyse_p2p_tcp_8080",analyse_p2p_tcp_8080,FUNC_RULE,100,IP_TCP,DIR_UP,8080},
	{"analyse_p2p_tcp_443",analyse_p2p_tcp_443,FUNC_RULE,100,IP_TCP,DIR_IN,443},
	{"analyse_p2p_tcp_8000",analyse_p2p_tcp_8000,FUNC_RULE,100,IP_TCP,DIR_UP,8000},
	{"analyse_p2p_tcp_554",analyse_p2p_tcp_554,FUNC_RULE,100,IP_TCP,DIR_DOWN,554},
	{"analyse_p2p_tcp_4672",analyse_p2p_tcp_4672,FUNC_RULE,100,IP_TCP,DIR_UP,4672},
	{"analyse_p2p_tcp_5598",analyse_p2p_tcp_5598,FUNC_RULE,100,IP_TCP,DIR_UP,5598},
   	{"analyse_p2p_tcp_8443",analyse_p2p_tcp_8443,FUNC_RULE,100,IP_TCP,DIR_UP,8443},
   	{"analyse_p2p_tcp_3077",analyse_p2p_tcp_3077,FUNC_RULE,100,IP_TCP,DIR_UP,3077},
   	{"analyse_p2p_tcp_3078",analyse_p2p_tcp_3078,FUNC_RULE,100,IP_TCP,DIR_UP,3078},
   	{"analyse_p2p_tcp_3076",analyse_p2p_tcp_3076,FUNC_RULE,100,IP_TCP,DIR_UP,3076},
   	{"analyse_p2p_tcp_3468",analyse_p2p_tcp_3468,FUNC_RULE,100,IP_TCP,DIR_UP,3468},
   	{"analyse_p2p_tcp_XXXX",analyse_p2p_tcp_XXXX,FUNC_RULE,100,IP_TCP,DIR_IN,0},
   	{"analyse_p2p_udp_8000",analyse_p2p_udp_8000,FUNC_RULE,100,IP_UDP,DIR_IN,8000},
   	{"analyse_p2p_udp_4662",analyse_p2p_udp_4662,FUNC_RULE,100,IP_UDP,DIR_UP,4662},
   	{"analyse_p2p_udp_9099",analyse_p2p_udp_9099,FUNC_RULE,100,IP_UDP,DIR_UP,9099},
   	{"analyse_p2p_udp_12110",analyse_p2p_udp_12110,FUNC_RULE,100,IP_UDP,DIR_UP,12110},
   	{"analyse_p2p_udp_12111",analyse_p2p_udp_12111,FUNC_RULE,100,IP_UDP,DIR_UP,12111},
   	{"analyse_p2p_udp_11111",analyse_p2p_udp_11111,FUNC_RULE,100,IP_UDP,DIR_UP,11111},
   	{"analyse_p2p_udp_11112",analyse_p2p_udp_11112,FUNC_RULE,100,IP_UDP,DIR_UP,11112},
   	{"analyse_p2p_udp_443",analyse_p2p_udp_443,FUNC_RULE,100,IP_UDP,DIR_IN,443},
   	{"analyse_p2p_udp_80",analyse_p2p_udp_80,FUNC_RULE,100,IP_UDP,DIR_IN,80},
   	{"analyse_p2p_udp_8080",analyse_p2p_udp_8080,FUNC_RULE,100,IP_UDP,DIR_IN,8080},
   	{"analyse_p2p_udp_53124",analyse_p2p_udp_53124,FUNC_RULE,100,IP_UDP,DIR_UP,53124},
   	{"analyse_p2p_udp_53125",analyse_p2p_udp_53125,FUNC_RULE,100,IP_UDP,DIR_UP,53125},
   	{"analyse_p2p_udp_53126",analyse_p2p_udp_53126,FUNC_RULE,100,IP_UDP,DIR_UP,53126},
   	{"analyse_p2p_udp_innerport_7600",analyse_p2p_udp_innerport_7600,FUNC_RULE,100,IP_UDP,DIR_IN,0},
   	{"analyse_p2p_udp_innerport_4645",analyse_p2p_udp_innerport_4645,FUNC_RULE,100,IP_UDP,DIR_IN,0},

//   	{"analyse_p2p_udp_innerport_7600",analyse_p2p_udp_innerport_7600,FUNC_RULE_INNERPORT,0,IP_UDP,DIR_IN,7600},
//   	{"analyse_p2p_udp_innerport_4645",analyse_p2p_udp_innerport_4645,FUNC_RULE_INNERPORT,0,IP_UDP,DIR_IN,4645},
   	{"analyse_p2p_udp_XXXX",analyse_p2p_udp_XXXX,FUNC_RULE,0,IP_UDP,DIR_IN,0}
};

const struct module_register* get_module_info_p2p(int * func_num)
{
	*func_num = RET_NUMBER;
	return (const struct module_register*)GET_POINT;
}

