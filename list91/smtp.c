#include "smtp.h"
#include "serv.h"
#include "emp.h"
#include "ctl.h"


extern inline TEmpnode* user_getipempptr();

inline void smtp_handle(u8* pdata,u16 datalen,u8* mac)
{
	ctl_setpkthead(g_sendpkthead,38+datalen,LOG_SMTP);//sip(4byte) +dip+sport+dport+seq+ack+accountname
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
    (*(u32*)(g_sendpkthead+34)) =g_pkt.ack;
        
	serv_sendto(g_sendpkthead,38);
	serv_sendto(pdata,datalen);
}