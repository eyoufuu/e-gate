#include "post.h"
#include "serv.h"
#include "emp.h"
#include "ctl.h"


inline int post_match(u8* addr,u8* target,u32 tar_length)
{
	u32 i = 0;
	for(i=0;i<tar_length;i++)
	{		
		if(*addr  != *target)
		{
	            	if(*target != '?')
	    		{                
				    return -1;
	            	}
	            //	else
	           //	 printf("############# %c\n",*target);
		}
		addr++;
		target++;				
	}
	return 1;
}
inline int post_search(void *source, void *target,u32 soc_length, u32 tar_length)
{
	void *addr;
	u8 *search_address;
	search_address = (u8 *)source;
	u32 i = 0;
	while((addr = memchr(search_address, *((char*)target), soc_length))!=NULL)
	{
		if(1 == post_match((u8*)addr,(u8*) target,tar_length))
		{
			return 1;
		}
		soc_length = soc_length - (long)((u8*)addr - (u8*)search_address)-1;
		search_address=(u8 *)((u8 *)addr+1);		
	}
	return -1;	
}


extern inline TEmpnode* user_getipempptr();
inline void post_handle(u8* pdata,u16 datalen,u8* mac)
{
	ctl_setpkthead(g_sendpkthead,38+datalen,LOG_POST);//sip(4byte) +dip+sport+dport+seq+ack+accid
	TEmpnode* pemp = user_getipempptr();
    u32 accid = 0;
    (*(u32*)(g_sendpkthead+8)) = g_pkt.innerip;
	(*(u32*)(g_sendpkthead+12)) = g_pkt.outerip;
	(*(u16*)(g_sendpkthead+16)) = g_pkt.innerport;
	(*(u16*)(g_sendpkthead+18)) = g_pkt.outerport;
    memcpy(g_sendpkthead+20,mac,6);       
	if(unlikely(SYS_LONGIN == emp_getempmode(pemp)))
	{
		accid =  emp_getempaccid(pemp);
	}
	(*(u32*)(g_sendpkthead+26)) = accid;
    (*(u32*)(g_sendpkthead+30)) = g_pkt.seq;
    (*(u32*)(g_sendpkthead+34)) = g_pkt.ack;
	serv_sendto(g_sendpkthead,38);
	serv_sendto(pdata,datalen);
}

