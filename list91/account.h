#ifndef _ACCOUNT_H
#define _ACCOUNT_H

#include "global.h"

#define MAX_ACC_NUM 3000
#define MAX_ACC_NAME 32

typedef struct _TAccount{
//	u8 name[32];
	u32 usedip;
	u32 bindip;

//	u32 groupid;
//	u32 personid;
	
	u32 policyid;
	
/*	u32 issmtplog;
	u32 ispop3log;
	u32 ispostlog;
	u32 isgetlog;		*/
}TAccount;

extern TAccount* g_account[MAX_ACC_NUM];
#endif