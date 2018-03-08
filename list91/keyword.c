#include "keyword.h"
#include "emp.h"
#include "ctl.h"
#include "sndpkt/pktsend.h"

#define HTTP_KEYWORD "<HTML>\
<title>´íÎó</title>\
<body>\
<p>&nbsp;</p>\
<p>&nbsp;</p>\
<p>&nbsp;</p>\
<p><center><font size=\"7\" color=\"red\">·ÃÎÊ´íÎó!</font><center></p>\
<p><center><font size=\"5\">¹Ø¼ü´Ê±»²ßÂÔ×è¶Ï!</font></center></p>\
<p>&nbsp;</p>\
<p>&nbsp;</p>\
</body>\
</HTML>"
/*extern inline u32 pol_getkeywordnum(u32 polid);
extern inline u32 get_get(u8* buf, u16 buflen, u8** get, u16* getlen);
extern inline u8* pol_getutfkeyword(u32 polid, u32 id);
extern inline u8* pol_getgbkeyword(u32 polid, u32 id);*/
extern inline TEmpnode* user_getipempptr();
inline int kw_match(u8* addr,u8* target,u32 tar_length)
{
	u32 i = 0;
	for(i=0;i<tar_length;i++)
	{
		while(*addr == '+'  ||*addr == '*')
			addr = addr + 1;
		if(*addr  != *target)
		{
			return -1;
		}
		addr++;
		target++;				
	}
	return 1;
}
inline int kw_search(void *source, void *target,u32 soc_length, u32 tar_length)
{
	void *addr;
	u8 *search_address;
	search_address = (u8 *)source;
	u32 i = 0;
	while((addr = memchr(search_address, *((char*)target), soc_length))!=NULL)
	{
		if(1 == kw_match((u8*)addr,(u8*) target,tar_length))
		{
			return 1;
		}
		soc_length = soc_length - (long)((u8*)addr - (u8*)search_address)-1;
		search_address=(u8 *)((u8 *)addr+1);		
	}
	return -1;	
}
inline u32 keyword_handle(u32 polid,u8*get,u16 getlen,u8*host,u16 hostlen,u8* mac)
{
	u32 i = 0;
//	regmatch_t pm[1];
//	const size_t nmatch = 1;
//	get[getlen] = 0;
	u32 pass = 1;
    u32 keywordnum = pol_getkeywordnum(polid);
    for(i=0;i<keywordnum;i++)
    {
//		if((0==regexec((regex_t*)pol_getkeywordreggb(polid,i),(char*)get,nmatch,pm,0)) ||(0==regexec((regex_t*)pol_getkeywordregutf(polid,i),(char*)get,nmatch,pm,0)))
		u8* pstrgb = (u8*)pol_getkeywordgb(polid,i);
		u32 strgblen = strlen(pstrgb);		
		if(-1!= kw_search((void*)get,(void*)pstrgb,getlen,strgblen))
		{
//             printf("k g match\n");
			goto match;
		}
		u8* pstrutf = (u8*)pol_getkeywordutf(polid,i);
		u32 strutflen = strlen(pstrutf);
		if(-1 != kw_search((void*)get,(void*)pstrutf,getlen,strutflen))
		{
   //         printf("k u match\n");
			goto match;
           
		}		
    }
	return pass;
match:
	if(0 == pol_getkeywordpass(polid,i))
	{
//        printf("k block\n");
		//RedirectUrl(NULL,HTTP_KEYWORD,g_pkt.seq,g_pkt.ack,g_pkt.innerip,g_pkt.outerip,g_pkt.innerport,g_pkt.outerport);
		if(g_isremind)
		{
		 u8 buf[1500];
		  sprintf(buf,PKT_CONTENT_REDIRCT,g_localipstr,g_localipstr,"warning.php",2);
	          sndpkt_redirecturl(buf,g_pkt.seq,g_pkt.ack,g_pkt.innerip,g_pkt.outerip,g_pkt.innerport,g_pkt.outerport);
		}
        else
        {
             sndpkt_tcp_rst(g_pkt.ack,g_pkt.outerip,g_pkt.innerip,g_pkt.outerport,g_pkt.innerport);
        }       
		pass = 0;
	}
    if(1 == pol_iskeywordlog(polid,i))
    {
        u32 keylen = strlen((char*)pol_getkeywordutf(polid,i));
        u32 len = 32+getlen+hostlen+keylen;
		ctl_setpkthead(g_sendpkthead,len,LOG_WEBGET);
		u32 accid = 0;
        
		TEmpnode* pemp = user_getipempptr();
		if(unlikely(SYS_LONGIN == emp_getempmode(pemp)))
		{
			accid =  emp_getempaccid(pemp);
		}
		(*(u32*)(g_sendpkthead+8)) = g_pkt.innerip;
        (*(u32*)(g_sendpkthead+12)) = g_pkt.outerip;
        memcpy(g_sendpkthead+16,mac,6);
                
		(*(u32*)(g_sendpkthead+22)) = accid;
        (*(u8*)(g_sendpkthead+26)) = polid;
        (*(u8*)(g_sendpkthead+27)) = 3;
	    (*(u16*)(g_sendpkthead+28)) = 0;
        (*(u16*)(g_sendpkthead+30)) = pass;
        
   //     memcpy((char*)g_sendpkthead+32,(char*)pol_getkeywordutf(polid,i),keylen);
		serv_sendto(g_sendpkthead,32);
 //       printf("kw b1=%d b2=%d \n",g_sendpkthead[0],g_sendpkthead[1]);
		serv_sendto(get,getlen);
    	serv_sendto(host,hostlen);
        serv_sendto((char*)pol_getkeywordutf(polid,i),keylen);
    }
    return pass;
}