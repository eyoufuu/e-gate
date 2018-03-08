#include "bwhost.h"
#include "cli.h"
#include "serv.h"
#include "emp.h"

#define BWHOST_HASHVAL(x) (*((u32*)x))%137
static u32 g_bwhostnum = 0;
struct hlist_head g_bwhost[MAX_BWHOST];
inline u32 bwhost_isopen()
{
	return g_bwhostnum;
}
inline void bwhost_init()
{
    u32 i = 0;
	for(i=0;i<MAX_BWHOST;i++)
	{
		INIT_HLIST_HEAD(&g_bwhost[i]);
	}
}
inline void bwhost_uninit()
{
    u32 i = 0;
	for(i=0;i<MAX_BWHOST;i++)
	{
        bwhost_del(i);
	}
    g_bwhostnum = 0;
}
inline u32 bwhost_add(u8* pstr, u32 pass)
{
	struct hlist_node *pos,*n;
	TBwhost* node;
	u8 buf[100];
	u32 len = strlen(pstr);
    if(len>100 || len <3)
        return 0;
    u8* p = pstr;
    char* ptmp = NULL;
    u32 check = 1;
    //delete unuseful info
    if((*(u32*)pstr) == (*(u32*)("www.")))
    {
        p = p+4;
    }
    ptmp = strstr(p,".com");
    if(ptmp != NULL)
    {
        *ptmp = '\0';
        check = 0;
    }
    if(check)
    {
        ptmp = strstr(p,".org");
        if(ptmp != NULL)
        {
            *ptmp = '\0';
            check = 0;
        }
    }
    if(check)
    {
        ptmp = strstr(p,".edu");
        if(ptmp != NULL)
        {
            *ptmp = '\0';
        }
    }
    printf("%s\n",p);
        
	TBwhost* pbwhost = (TBwhost*)malloc(sizeof(TBwhost));
	if(pbwhost != NULL)
	{				
		strcpy(pbwhost->str,p);
        pbwhost->len = strlen(p);
		pbwhost->pass = pass;
		hlist_add_head(&pbwhost->bwnode,&g_bwhost[BWHOST_HASHVAL(p)]);
        g_bwhostnum++;
	}
    
	return 1;	
}
inline void bwhost_del(u8 hash)
{
    struct hlist_node *pos,*n;
	TBwhost* node;
	hlist_for_each_safe(pos,n,&g_bwhost[hash])
	{
		node = hlist_entry(pos,TBwhost,bwnode);
		hlist_del(pos);
		free(node);
	}
    
}
inline u32 bwhost_isspechost(u8* pstr,u32 len)
{
	struct hlist_node *pos,*n;
	TBwhost* node;
    int i=0;

    u8* p = pstr;
    u32 plen = len;
    if((*(u32*)(pstr)) == (*(u32*)("www.")))
    {
        p = pstr+4;
        plen = plen -4;
    }
   for(i=0;i<3;i++)
   {        
    	u32 hash =  BWHOST_HASHVAL(p);
        hlist_for_each_safe(pos,n,&g_bwhost[hash])
    	{
    		node = hlist_entry(pos,TBwhost,bwnode);
            u8* ptmp =(u8*)memmem(p,plen,node->str,node->len);
            if(ptmp != NULL)
            {          
                printf("%d,str=%s,pass=%d\n",i,node->str,node->pass);
                return node->pass;
            }        
    	}
//        p = (u8*)memmem((const void*)p,plen,".",1);
        p = (u8*)memchr((const void*)p,'.',plen);
        if(p!=NULL)
        {
            p = p+1;
            plen = len-(p-pstr);            
        }
        else
        {
            return HOST_SPEC_NONE;
        }        
   }
	return HOST_SPEC_NONE;
}
