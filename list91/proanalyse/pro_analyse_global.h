#ifndef __PRO_ANALYSE_GLOBAL_H__
#define __PRO_ANALYSE_GLOBAL_H__
#include "../globaldef.h"
typedef u32  (*func_pro)(u8,      //dir 
					      u8*,//payload
					      u32);//plen
typedef u8* (*func_pro_ip)(u32* ipcount);//single ip and ips 
typedef unsigned int (*func_pro_ipgroup)(u32 * ipf, u32 *ipt);

struct module_register
{
#define FUNC_IP 0
#define FUNC_IP_GROUP 1
#define FUNC_RULE 2
	u8  func_name[30];
	func_pro function;
	//0 : ip        1: ipgroup              2:rule 
	u8  func_type;
	u8  func_pri;
	u8  func_protype;
	u8  func_dir;
	u16  func_port;
};

typedef int  (*f_register_mod_func)(struct module_register** );

#define RET_NUMBER sizeof(__dllregister)/sizeof(struct module_register)
#define GET_POINT     &(__dllregister[0])	
#define MAX_IP_END 0

#define NIPQUAD(addr) \
	((unsigned char *)&addr)[0], \
	((unsigned char *)&addr)[1], \
	((unsigned char *)&addr)[2], \
	((unsigned char *)&addr)[3]
#define NIPQUAD_FMT "%u.%u.%u.%u"


//

#endif



 

