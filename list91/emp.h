#ifndef _EMP_H
#define _EMP_H
#include <stddef.h>
#include "list.h"
#include "global.h"

extern struct hlist_head g_ipemplist[MAX_IP][MAX_NETSEG];


typedef struct _TEmployee{
	u32 ip;
	u32 mode; //µÇÂŒ×ŽÌ¬£¬ip£¬login£¬logout
	u32 accid;//if mode=!ip rec account
	u32 specip;//0,ºÚÃûµ¥ 1£¬°×Ãûµ¥ 2 ,·ÇÌØÊâ
	u32 policyid;
	u32 groupid;
	u32 personid;
	u32 activetime;
    u32 remindtime;
    u8  mac[6];
//    u16 isipmacbind;

}TEmployee;
typedef struct _TEmpnode{
	TEmployee emp;
	struct hlist_node empnode;
}TEmpnode;

inline void emp_initipemplist();
inline void emp_resetempspecip();
inline void emp_resetemplogout(u32 mode);

inline void emp_addempnode(u16 iphash, u8 netseghash, TEmployee * pEm);
inline void emp_delempnode(u16 hash, u8 netseghash);
inline void emp_delallempnode();
TEmpnode* emp_getempnode(u16 hash, u8 netseghash, u32 ip);

inline void emp_updateempnode(u16 hash, u8 netseghash, TEmployee * pEm);

inline u8* emp_getempmac(TEmpnode* p);

inline u32 emp_getempmode(TEmpnode* p);

inline u32 emp_getempspecip(TEmpnode* p);
inline void emp_setempspecip(TEmpnode* p,u32 val);

inline u32 emp_getemppolicyid(TEmpnode* p);

inline u32 emp_getempgroupid(TEmpnode* p);
inline u32 emp_getempid(TEmpnode* p);

inline u32 emp_getempactivetime(TEmpnode* p);
inline void emp_updateactivetime(TEmpnode* p,u32 time);
inline u32 emp_getempremindtime(TEmpnode* p);
inline void emp_updateremindtime(TEmpnode* p,u32 time);
/*
inline u32 emp_getempsmtp(TEmpnode* p);
inline u32 emp_getemppop3(TEmpnode* p);
inline u32 emp_getempget(TEmpnode* p);
inline u32 emp_getemppost(TEmpnode* p);
*/

#endif

