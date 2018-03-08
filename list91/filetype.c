/*File: get.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
 */
#include "filetype.h"
#include "cli.h"
#include "serv.h"
#include "emp.h"
#include "ctl.h"
//#include "pktsend.h"

#define FILETYPE_HASHVAL(x) (*((u32*)x))%137
u32 g_ftnum = 0;
struct hlist_head g_ftlist[MAX_FILETYPE];

extern inline TEmpnode* user_getipempptr();

inline void filetype_initftlist()
{
	u32 i = 0;
	for(i=0;i<MAX_FILETYPE;i++)
	{
		INIT_HLIST_HEAD(&g_ftlist[i]);
	}
}
inline void filetype_uninit()
{
    u32 i = 0;
    for(i=0;i<MAX_FILETYPE;i++)
    {
        filetype_delftnode(i);
    }
}
inline void filetype_insertftnode(u8 id, u8* str)
{
	struct hlist_node *pos,*n;
	TFtnode* node;
	u8 buf[10];
	u32 len = strlen(str);
	switch(len)
	{
		case 2:
			sprintf(buf,".%s ",str);
			break;
		case 3:
			sprintf(buf,"%s ",str);
			break;
		case 4:
			sprintf(buf,"%s ",str+1);
			break;
		default:
			return;
	}
	TFtnode* pFtnode = (TFtnode*)malloc(sizeof(TFtnode));
	if(pFtnode != NULL)
	{				
		strcpy(pFtnode->str,buf);
		pFtnode->id = id;
		hlist_add_head(&pFtnode->ftnode,&g_ftlist[FILETYPE_HASHVAL(buf)]);		
	}
}
inline u8 filetype_ftanalysis(u8* get,u16 getlen)
{
	struct hlist_node *pos,*n;
	TFtnode* node;
    if(unlikely(getlen<6))
    {
            return 0;
    }
/*	u32 hash = FILETYPE_HASHVAL((get+getlen-4));
	hlist_for_each_safe(pos,n,&g_ftlist[hash])
	{
		node = hlist_entry(pos,TFtnode,ftnode);
		if(0==memcmp((void*)(get+getlen-4),(void*)(node->str),4))
		{
			return node->id;
		}
	}*/
	u32 hash =  FILETYPE_HASHVAL((get+getlen-3));
	hlist_for_each_safe(pos,n,&g_ftlist[hash])
	{
		node = hlist_entry(pos,TFtnode,ftnode);
        if((*(u32*)(get+getlen-3)) == (*(u32*)(node->str)))
		//if(0==memcmp((void*)(get+getlen-3),(void*)(node->str),3))
		{
			return node->id;
		}
	}
	return 0;
}
#if 0
inline void filetype_insertftnode(u8 id, u8* str)
{
	struct hlist_node *pos,*n;
	TFtnode* node;
	u8 buf[10];
	u32 len = strlen(str);
	switch(len)
	{
		case 2:
			sprintf(buf,".%s ",str);
			break;
		case 3:
			sprintf(buf,".%s",str);
			break;
		case 4:
			sprintf(buf,"%s",str);
			break;
		default:
			return;
	}
	TFtnode* pFtnode = (TFtnode*)malloc(sizeof(TFtnode));
	if(pFtnode != NULL)
	{				
		strcpy(pFtnode->str,buf);
		pFtnode->id = id;
		hlist_add_head(&pFtnode->ftnode,&g_ftlist[FILETYPE_HASHVAL(buf)]);		
	}
}
inline u8 filetype_ftanalysis(u8* get,u16 getlen)
{
	struct hlist_node *pos,*n;
	TFtnode* node;
    if(unlikely(getlen<6))
    {
            return 0;
    }
	u32 hash = FILETYPE_HASHVAL((get+getlen-4));
	hlist_for_each_safe(pos,n,&g_ftlist[hash])
	{
		node = hlist_entry(pos,TFtnode,ftnode);
		if(0==memcmp((void*)(get+getlen-4),(void*)(node->str),4))
		{
			return node->id;
		}
	}
	hash =  FILETYPE_HASHVAL((get+getlen-3));
	hlist_for_each_safe(pos,n,&g_ftlist[hash])
	{
		node = hlist_entry(pos,TFtnode,ftnode);
		if(0==memcmp((void*)(get+getlen-3),(void*)(node->str),4))
		{
			return node->id;
		}
	}
	return 0;
}
#endif
inline void filetype_delftnode(u8 hash)
{
	struct hlist_node *pos,*n;
	TFtnode* node;
	hlist_for_each_safe(pos,n,&g_ftlist[hash])
	{
		node = hlist_entry(pos,TFtnode,ftnode);
		hlist_del(pos);
		free(node);
	}
}

u32 filetype_handle(u32 polid,u8 ftid,u8*get,u16 getlen,u8*host,u16 hostlen,u8* mac)
{
//	u8 ftid = filetype_ftanalysis(get,getlen);
	u8 pass = pol_getfiletypepass(polid,ftid);
//	printf("filetype = %d\n",ftid);
    if(unlikely(0 == pass))
    {
        sndpkt_tcp_rst(g_pkt.ack,g_pkt.outerip,g_pkt.innerip,g_pkt.outerport,g_pkt.innerport);
        // no need to redirect becauseof file type not page
    }
	if(unlikely(1 == pol_isfiletypelog(polid,ftid)))
	{
		u32 len = 32+getlen+hostlen;
		ctl_setpkthead(g_sendpkthead,len,LOG_WEBGET);
       
		TEmpnode* pemp = user_getipempptr();
        (*(u32*)(g_sendpkthead+8)) = g_pkt.innerip;
    	(*(u32*)(g_sendpkthead+12)) = g_pkt.outerip;
    	memcpy(g_sendpkthead+16,mac,6);
        u32 accid = 0;
		if(SYS_LONGIN == emp_getempmode(pemp))
		{
			accid =  emp_getempaccid(pemp);                        
		}		
        (*(u32*)(g_sendpkthead+22)) = accid;
        (*(u8*)(g_sendpkthead+26)) = polid;
        (*(u8*)(g_sendpkthead+27)) = 2;
        (*(u16*)(g_sendpkthead+28)) = ftid;		
        (*(u16*)(g_sendpkthead+30)) = pass;        
		serv_sendto(g_sendpkthead,32);
//         printf("ft b1=%d b2=%d \n",g_sendpkthead[0],g_sendpkthead[1]);
		serv_sendto(get,getlen);
		serv_sendto(host,hostlen);
	}       
	return pass;
}


