/*File: account.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
  */
#include "account.h"

TAccount* g_account[MAX_ACC_NUM];

void acc_initaccount()
{
//	paccount = create_hash_mem_info(ACCOUNT_H_NAME,ACCOUNT_H_SIZE);	
	u32 i = 0;
	for(i=0;i<MAX_ACC_NUM;i++)
	{
		memset(g_account[i],0,(size_t)MAX_ACC_NUM);		
	}
	
}
void acc_uninitaccount()
{
//	delete_all_mem_hash(paccount);
//	close_mem_hash(paccount);
	u32 i=0;
	for(i=0;i<MAX_ACC_NUM;i++)
	{
		if(g_account[i] != 0)
		{
			free(g_account[i]);
		}
	}
}
u32 acc_addaccount(u32 accid,TAccount* p)
{
//	return record_mem_hash_string(paccount,p->name,(void*)p,sizeof(TAccount));
	u32 len = sizeof(TAccount);
	TAccount* pacc = (TAccount*)malloc(len);
	if(pacc!=NULL)
	{
		memcpy(pacc,p,len);
		g_account[accid] = pacc;
		return 1;
	}
	return 0;	
}


