#ifndef _BWHOST_H
#define _BWHOST_H

#include "global.h"
#include <regex.h>
#include <sys/types.h>

#define HOST_SPEC_NONE 	2
#define HOST_BW_NUM 100


typedef struct _TBwhost{
	char str[100];
	u32 val;
	regex_t reg;
}TBwhost;

void bwhost_init();
void bwhost_uninit();
u32 bwhost_add(u8* p,u32 pass);
u32 bwhost_del(TBwhost* p);
u32 bwhost_isspechost(u8* pstr,u32 len);
#endif
