#ifndef _LOG2_H
#define _LOG2_H
#include <stddef.h>
#include "list.h"
#include "global.h"

#define INS_TRA_MODE_IP 	0
#define INS_TRA_MODE_PRO 1
extern struct hlist_head g_loglist[MAX_IP][MAX_NETSEG][MAX_PRO];

extern u32 g_instraffic; // 0 closed; 1 open
extern u32 g_loginterval;
extern u32 g_insmode;

typedef struct _TLog{
	u32 ip;
	u32 activetime;
	
	u32 insup;
	u32 insdown;
	
	u32 staup;
	u32 stadown;
	
	u32 stapassnum;
	u32 stablocknum;
}TLog;

typedef struct _TLognode{
	TLog log;
	struct hlist_node lognode;
}TLognode;

inline void log2_initloglist();
inline void log2_insertinssumtraffic();

inline void log2_updateinssumtraffic(u32 upflow,u32 downflow);
TLognode* log2_getlognode(u8 iphash,u8 netseghash,u8 proid,u32 ip);

inline void log2_delalllognode();
inline void log2_addlognode(u8 iphash,u8 netseghash,u8 proid,u32 ip);

inline void log2_dellognode(u8 iphash,u8 netseghash,u8 proid,u32 ip);
void log2_createinsthread();


#endif 
