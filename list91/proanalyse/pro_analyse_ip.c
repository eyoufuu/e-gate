#include "pro_analyse_global.h"
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <sys/time.h>

static int n_ip = 0;

u32 get_first_ip(u32 * ip)
{

   u32 x = 2439782106u;
    n_ip = 0;
    *ip =	x;
   n_ip++;
   return PRO_SINA_IGAME;
}

u32 get_next_ip(u32* ip)
{
    u32 x[]={0,3559341370u,3509009722u,3542564154u,269754074u,439626206u,622075610u,655630042u,316934521u,3358014778u,\
		283601098u,216492234u,1999213431u,527978610u,947409010u};
 
/*
    
	_add_core_ip(316934521, PRO_YOUKU);
	_add_core_ip(2439782106, PRO_SINA_IGAME);//218.30.108.145
	_add_core_ip(3559341370,PRO_XUNLEI);//58.61.39.212
	_add_core_ip(3509009722,PRO_XUNLEI);//58.61.39.209
	_add_core_ip(3542564154,PRO_XUNLEI);//58.61.39.211
	_add_core_ip(3358014778,PRO_XUNLEI);//58.61.39.200
	_add_core_ip(283601098,PRO_XUNLEI);
       _add_core_ip(216492234,PRO_XUNLEI);
	_add_core_ip(1999213431,PRO_XUNLEI);
	_add_core_ip(269754074,PRO_YUANHANG);
	_add_core_ip(439626206,PRO_YUANHANG);
	_add_core_ip(622075610,PRO_YUANHANG);
	_add_core_ip(655630042,PRO_YUANHANG);
	_add_core_ip(527978610,PRO_P2P_TUDOU);
	_add_core_ip(947409010,PRO_P2P_TUDOU);*/
    u32 ret = 0;
     switch(n_ip)
     {
     	case 1:
          	*ip =x[1];
		ret = PRO_XUNLEI;
		break;
	case 2:
		*ip = x[2];
		ret = PRO_XUNLEI;
		break;
	case 3:
		*ip = x[3];
		ret = PRO_XUNLEI;
		break;
	case 4:
		*ip = x[4];
		ret = PRO_YUANHANG;
		break;
	case 5:
		*ip =  x[5];
		ret = PRO_YUANHANG;
		break;
	case 6:
		*ip =  x[6];
		ret = PRO_YUANHANG;
		break;
	case 7:
		*ip = x[7];
		ret =PRO_YUANHANG;
		break;
	case 8:
		*ip =  x[8];
		ret = PRO_YOUKU;
		break;
	case 9:
		*ip =  x[9];
		ret = PRO_XUNLEI;
		break;
	case 10:
		*ip = x[10];
		ret = PRO_XUNLEI;
		break;
	case 11:
		* ip = x[11];
		ret =PRO_XUNLEI;
		break;
	case 12:
		*ip=  x[12];
		ret = PRO_XUNLEI;
		break;
	case 13:
		*ip= x[13];
		ret = PRO_P2P_TUDOU;
		break;
	case 14:
		*ip =  x[14];
		ret = PRO_P2P_TUDOU;
		break;
     }
     n_ip++;
    if(n_ip==15)
    {
             n_ip = 0;
		return MAX_IP_END;
    }
     return ret;	 
}


/*
unsigned int analyse_ip_group(unsigned int * ipf , unsigned int * ipt)
{
//ip起始位置
	*ipf = 99999;
//ip结束位置
	*ipt = 111111;
	return 222;
}
*/

