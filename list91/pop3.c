#include "pop3.h"
#include "emp.h"
#include "ctl.h"
#include "account.h"


extern inline TEmpnode* user_getipempptr();

inline void pop3_handle(u8* pdata, u16 datalen)
{
	ctl_setpkthead(g_sendpkthead,32+datalen,LOG_POP3);//sip(4byte) +dip+sport+dport+seq+ack+accountname
	TEmpnode* pemp = user_getipempptr();
    u32 accid = 0;
	(*(u32*)(g_sendpkthead+8)) = g_pkt.innerip;
	(*(u32*)(g_sendpkthead+12)) = g_pkt.outerip;
	(*(u16*)(g_sendpkthead+16)) = g_pkt.innerport;
	(*(u16*)(g_sendpkthead+18)) = g_pkt.outerport;
	if(unlikely(SYS_LONGIN == emp_getempmode(pemp)))
	{
		accid =  emp_getempaccid(pemp);
	}
	(*(u32*)(g_sendpkthead+20)) = accid;
    (*(u32*)(g_sendpkthead+24)) = g_pkt.seq;
    (*(u32*)(g_sendpkthead+28)) = g_pkt.ack;
	serv_sendto(g_sendpkthead,32);
	serv_sendto(pdata,datalen);
}

