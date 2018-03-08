/*File: emp.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
 */
#include "emp.h"


struct hlist_head g_ipemplist[MAX_IP][MAX_NETSEG];



inline void emp_initempnode(TEmpnode* pIpem,TEmployee* pEm)
{
	memcpy(&(pIpem->emp),pEm,sizeof(TEmployee));
}

inline void emp_initipemplist()
{
	u32 i = 0;
	u32 j=0;
	for(i=0;i<MAX_IP;i++)
	{
		for(j=0;j<MAX_NETSEG;j++)
		{
			INIT_HLIST_HEAD(&g_ipemplist[i][j]);
		}		
	}
}

inline void emp_addempnode(u16 iphash,u8 netseghash,TEmployee* pEm)
{
	TEmpnode* pemnode = (TEmpnode*)malloc(sizeof(TEmpnode));
	if(pemnode != NULL)
	{
		emp_initempnode(pemnode,pEm);
		hlist_add_head(&pemnode->empnode,&g_ipemplist[iphash][netseghash]);
	}
}
inline void emp_delempnode(u16 hash,u8 netseghash)
{
	struct hlist_node *pos,*n;
	TEmpnode* node;
	hlist_for_each_safe(pos,n,&g_ipemplist[hash][netseghash])
	{
		node = hlist_entry(pos,TEmpnode,empnode);
		hlist_del(pos);
		free(node);
	}
}
inline void emp_delallempnode()
{
    u32 i = 0;
    u32 j = 0;

    for(i=0;i<MAX_IP;i++)
    {
            for(j=0;j<MAX_NETSEG;j++)
            {
                    emp_delempnode(i,j);
            }
    }
						
}
inline void emp_resetempspecip()
{
    u32 i,j;
    struct hlist_node* pos,*n;
    for(i=0;i<MAX_IP;i++)
    {
        for(j=0;j<MAX_NETSEG;j++)
        {
            hlist_for_each_safe(pos,n,&g_ipemplist[i][j])
        	{
        		TEmpnode *node = hlist_entry(pos,TEmpnode,empnode);
        		node->emp.specip = 2;        		
        	}
        }
    }
}
inline void emp_resetemplogout(u32 mode)
{
    u32 i,j;
    struct hlist_node* pos,*n;
    for(i=0;i<MAX_IP;i++)
    {
        for(j=0;j<MAX_NETSEG;j++)
        {
            hlist_for_each_safe(pos,n,&g_ipemplist[i][j])
        	{
        		TEmpnode *node = hlist_entry(pos,TEmpnode,empnode);
        		node->emp.mode = mode;        		
        	}
        }
    }
}
inline TEmpnode* emp_getempnode(u16 hash,u8 netseghash,u32 ip)
{
	struct hlist_node* pos,*n;
	hlist_for_each_safe(pos,n,&g_ipemplist[hash][netseghash])
	{
		TEmpnode *node = hlist_entry(pos,TEmpnode,empnode);
		if(node->emp.ip == ip)
		{
			return node;
		}
	}
	return NULL;
}

inline void emp_updateempnode(u16 hash,u8 netseghash,TEmployee* pEm)
{
	struct hlist_node* pos,*n;
	hlist_for_each_safe(pos,n,&g_ipemplist[hash][netseghash])
	{
		TEmpnode *node = hlist_entry(pos,TEmpnode,empnode);
		if(node->emp.ip == pEm->ip)
		{
			emp_initempnode(node,pEm);
		}
	}			
}
inline u8* emp_getempmac(TEmpnode* p)
{
        return p->emp.mac;
}
inline u32 emp_getempmode(TEmpnode* p)
{
	u32 ret = p->emp.mode;
	return ret;
}
inline void emp_setempmode(TEmpnode* p,u32 val)
{
	p->emp.mode = val;
}
inline u32 emp_getempspecip(TEmpnode* p)
{
	u32 ret = p->emp.specip;
	return ret;
}
inline void emp_setempspecip(TEmpnode* p,u32 val)
{
    p->emp.specip = val;
}
inline u32 emp_getemppolicyid(TEmpnode* p)
{
        return p->emp.policyid;
#if 0
	u32 ret = p->emp.onpolicyid;	

	if(g_timescope.open == TIMEGATE_OPEN)
	{
		u8 week = 0x1<<((7-g_ptm->curdate.tm_wday)%7);
		if(week & g_timescope.week)
		{
			u16 time = g_ptm->curdate.tm_hour*100 + g_ptm->curdate.tm_min;
			if(NOTINTIME1(time) && NOTINTIME2(time))
			{
				ret = p->emp.offpolicyid;
			}			
		}
		ret = p->emp.offpolicyid;
	}
	return ret;
#endif    
}
/*u32 emp_getemponpolicyid(void* p)
{
	TIpempnode* node = (TIpempnode*)p;
	return node->emp.onpolicyid;
}
u32 emp_getempoffpolicyid(void* p)
{
	TIpempnode* node = (TIpempnode*)p;
	return node->emp.offpolicyid;
}*/

inline u32 emp_getempgroupid(TEmpnode* p)
{
	u32 ret = p->emp.groupid;
	return ret;
}

inline u32 emp_getempid(TEmpnode* p)
{
	u32 ret = p->emp.personid;
	return ret;
}
inline u32 emp_getempaccid(TEmpnode* p)
{
	u32 ret = p->emp.accid;
	return ret;
}
inline u32 emp_getempactivetime(TEmpnode* p)
{
	u32 ret = p->emp.activetime;
	return ret;
}
inline void emp_updateactivetime(TEmpnode* p,u32 time)
{
	p->emp.activetime = time;
}
inline u32 emp_getempremindtime(TEmpnode* p)
{
	return p->emp.remindtime;
}
inline void emp_updateremindtime(TEmpnode* p,u32 time)
{
    p->emp.remindtime = time;
}
/*
inline u32 emp_getempsmtp(TEmpnode* p)
{
	u32 ret = p->emp.smtp;
	return ret;
}
inline u32 emp_getemppop3(TEmpnode* p)
{
	u32 ret = p->emp.pop3;
	return ret;
}
inline u32 emp_getempget(TEmpnode* p)
{
	u32 ret = p->emp.get;
	return ret;
}
inline u32 emp_getemppost(TEmpnode* p)
{
	u32 ret = p->emp.post;
	return ret;
}*/
