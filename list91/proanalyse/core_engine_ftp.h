#ifndef __CORE_ENGINE_FTP_H__
#define  __CORE_ENGINE_FTP_H__
#include "../globaldef.h"

u32 tcp_ftp(unsigned char up_down,
	          const unsigned char * payload,
	          int plen, unsigned int * outterip, unsigned short * outterport);



#endif
		      
