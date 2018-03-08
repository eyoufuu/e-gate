#ifndef _BWHOST_H
#define _BWHOST_H

#include "global.h"
#include <sys/types.h>
#include "list.h"

#define HOST_SPEC_NONE 	2
#define MAX_BWHOST 256


typedef struct _TBwhost{
	char str[100];
    u32  len;
	u32 pass;
    struct hlist_node bwnode;
}TBwhost;

inline void bwhost_init();
inline void bwhost_uninit();
inline u32 bwhost_add(u8* p,u32 pass);
inline void bwhost_del(u8 hash);
inline u32 bwhost_isspechost(u8* pstr,u32 len);
#endif
