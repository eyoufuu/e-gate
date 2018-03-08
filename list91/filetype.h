#ifndef _FILETYPE_H
#define _FILETYPE_H

#include "list.h"
#include "global.h"

#define MAX_FILETYPE 	256


typedef struct _TFtnode{
	u32 id;
	u8 str[5];
	struct hlist_node ftnode;
}TFtnode;

inline void filetype_initftlist();
inline void filetype_insertftnode(u8 id, u8* str);
inline void filetype_delftnode(u8 hash);
inline u8 filetype_ftanalysis(u8* get,u16 getlen);

u32 filetype_handle(u32 polid,u8 ftid,u8*get,u16 getlen,u8*host,u16 hostlen,u8* mac);

#endif
