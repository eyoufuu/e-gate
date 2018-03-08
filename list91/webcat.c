#include "webcat.h"
#include "jhash.h"
#include "serv.h"
#include "bdb.h"
#include "ctl.h"
#include "emp.h"
#include "sndpkt/pktsend.h"

DB* g_pbdb = NULL;
extern inline TEmpnode* user_getipempptr();
void webcat_init()
{
        if(0 == openbdb())
        {
                DEBUG(D_WARNING)("openbdb webcat error\n");
                exit(1);
        }        
}
void webcat_uninit()
{
       closedb();
}
u8 webcat_getwebcat(u32 jhash)
{
	return querybdb(&jhash);
}

inline u32 webcat_handle(u32 polid,u8* get,u16 getlen,u8* host,u16 hostlen,u8* mac)
{
    u32 pass = 1;
    u32 jh = jhash((void*)host,hostlen,HASH_VAL);
    u8 webid = webcat_getwebcat(jh);
//    DEBUG(D_INFO)("webid= %d\n",webid);
    //if(unlikely(0 != webid))
    {
        pass = pol_getwebpass(polid,webid);
        if(pass == 0)
        {
            if(g_isremind)//block page remind
            {
                u8 buf[1500];
    		sprintf(buf,PKT_CONTENT_REDIRCT,g_localipstr,g_localipstr,"warning.php",1);
                sndpkt_redirecturl(buf,g_pkt.seq,g_pkt.ack,g_pkt.innerip,g_pkt.outerip,g_pkt.innerport,g_pkt.outerport);
            }
            else
            {
                 sndpkt_tcp_rst(g_pkt.ack,g_pkt.outerip,g_pkt.innerip,g_pkt.outerport,g_pkt.innerport);
            }
        }
        else
        {
            if(pol_iswebremind(polid,webid))//remind for energe consuming
            {  
                TEmpnode* pemp = user_getipempptr();
                if(g_ptm->curtime - pemp->emp.remindtime >1800)
                {
                    emp_updateremindtime(pemp,g_ptm->curtime);
                    
                    u8 buf[1000];
                    if(getlen+hostlen<800)
                    {
                        sprintf(buf,"HTTP/1.0 302 Moved Temporarily\r\nServer:%s\r\nLocation: http://%s/ly/warning/%s?p=%d&r=",g_localipstr,g_localipstr,"remind.php",3);
                        u32 len = strlen(buf);
                        u32 endlen = strlen("\r\nContent-Type: text/html\r\n\r\n");
                        memcpy(buf+len,host,hostlen);
                        len = len+hostlen;
                        memcpy(buf+len,get,getlen);
                        len = len+getlen;
                        memcpy(buf+len,"\r\nContent-Type: text/html\r\n\r\n",endlen);
                        len = len + endlen;
                        buf[len] = '\0';                    			
                        sndpkt_redirecturl(buf,g_pkt.seq,g_pkt.ack,g_pkt.innerip,g_pkt.outerip,g_pkt.innerport,g_pkt.outerport);
                    }
                }
                
            }
        }
        if(1 == pol_isweblog(polid,webid))
        {
            u16 len = 32+getlen+hostlen;
            ctl_setpkthead(g_sendpkthead,len,LOG_WEBGET);   
            
            (*(u32*)(g_sendpkthead+8)) = g_pkt.innerip;
            (*(u32*)(g_sendpkthead+12)) = g_pkt.outerip;
            memcpy(g_sendpkthead+16,mac,6);
            u32 accid = 0;
            TEmpnode* pemp = user_getipempptr();
            if(unlikely(SYS_LONGIN == emp_getempmode(pemp)))
            {
                accid =  emp_getempaccid(pemp);                        
            }		
            (*(u32*)(g_sendpkthead+22)) = accid;
            (*(u8*)(g_sendpkthead+26)) = polid;
            (*(u8*)(g_sendpkthead+27)) = 1;
            (*(u16*)(g_sendpkthead+28)) = webid;	
            (*(u16*)(g_sendpkthead+30)) = pass;
            serv_sendto(g_sendpkthead,32);        
  //          printf("wc b1=%d b2=%d \n",g_sendpkthead[0],g_sendpkthead[1]);
            serv_sendto(get,getlen);
            serv_sendto(host,hostlen);
        }
    }
    return pass;
}
