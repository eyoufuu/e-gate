#ifndef _CTL_H
#define _CTL_H

#include "global.h"

extern u8 g_sendpkthead[2048];


#define PKT_B1(x)               (*(u8*)(x))
#define PKT_B2(x)               (*(u8*)(x+1))
#define PKT_LEN(x) 				(*(u16*)(x+2))
#define PKT_MAJ(x)                (*(u8*)(x+4))
#define PKT_MIN(x)                (*(u8*)(x+5))
#define PKT_CMD(x) 				(*(u16*)(x+6))

#define SET_PKT_B1(x,data) 		(*(u8*)(x)) = data
#define SET_PKT_B2(x,data) 	    (*(u8*)(x+1)) = data
#define SET_PKT_LEN(x,len) 	    (*(u16*)(x+2)) = len
#define SET_PKT_MAJ(x,maj) 	    (*(u8*)(x+4)) = maj
#define SET_PKT_MIN(x,min) 		(*(u8*)(x+5)) = min
#define SET_PKT_CMD(x,id) 		(*(u16*)(x+6)) = id

//inline void ctl_getpktinfo(u32 phyindev,u32* dir,u32* innerip, u32* upflow,u32* downflow,u32 size);

inline void ctl_initpkthead(u8* buf);
inline void ctl_setpkthead(u8* buf,u16 len,  u16 cmd);

inline u32 ctl_getpktdir(u32 phyindev);
inline u32 ctl_getinnerip(u32 sip,u32 dip);

inline u32 ctl_getsystime(struct tm** ptm, time_t* currentTime);
void ctl_readsharedmemid(int* shm_id);
inline int ctl_search(void *source, void *target,u32 soc_length, u32 tar_length);

inline void ctl_httpget(u8* buf,u16 buflen,u8** get, u16* getlen);
inline void ctl_httphost(u8* buf,u16 buflen,u8** host,u16* hostlen);

int ctl_split(char** buf,int m,int n,char* str,const char* de);

#endif
