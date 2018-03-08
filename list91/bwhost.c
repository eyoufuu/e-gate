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
	u32 len = strlen(pstr);
    if(len>100)
        return 0;
    u8* p = pstr;
    char* ptmp = NULL;
    u32 check = 1;
    if(len>4)
    {
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
    }
    char buf[10][20];
    u32 buflen = ctl_split(buf,10,20,p,".*");
    switch(buflen)
    {
        case 1:
            {
                u32 piecelen = strlen((char*)buf[0]);
                if(piecelen<=2)
                    return;
                if(piecelen==3)
                {
                    buf[0][piecelen] = '.';
                    buf[0][piecelen+1] = '\0';
                    piecelen = piecelen +1;
                }
    //            printf("%s\n",(char*)buf[0]);
          
            	TBwhost* pbwhost = (TBwhost*)malloc(sizeof(TBwhost));
            	if(pbwhost != NULL)
            	{				
            		strcpy(pbwhost->str1,(char*)buf[0]);
                    pbwhost->len1 = piecelen;
                    pbwhost->str2[0]='\0';
                    pbwhost->len2 = 0;
            		pbwhost->pass = pass;
                    pbwhost->findnum = 1;
            		hlist_add_head(&pbwhost->bwnode,&g_bwhost[BWHOST_HASHVAL(buf[0])]);
            	}
            }
            break;
        case 2:
            {
                u32 i=0;
                for(i=0;i<2;i++)
                {
                    u32 piecelen = strlen((char*)buf[i]);
                    if(piecelen==3)
                    {
                        buf[0][piecelen] = '.';
                        buf[0][piecelen+1] = '\0';
                        piecelen = piecelen +1;
                    }
     //               printf("%s\n",(char*)buf[i]);
                    if(piecelen>2)
                    {
                        TBwhost* pbwhost = (TBwhost*)malloc(sizeof(TBwhost));
                    	if(pbwhost != NULL)
                    	{				
                    		strcpy(pbwhost->str1,(char*)buf[1-i]);
                            pbwhost->len1 = strlen((char*)buf[1-i]);
                            
                            pbwhost->str2[0]='\0';
                            pbwhost->len2 = 0;
                    		pbwhost->pass = pass;
                            pbwhost->findnum = 1;
                    		hlist_add_head(&pbwhost->bwnode,&g_bwhost[BWHOST_HASHVAL(buf[i])]);
                    	}
                    }
                }
            }
            break;
        case 3:
            {
                u32 piecelen = strlen((char*)buf[0]);
                if(piecelen==3)
                {
                    buf[0][piecelen] = '.';
                    buf[0][piecelen+1] = '\0';
                    piecelen = piecelen +1;
                }
      //          printf("%s\n",(char*)buf[0]);
                if(piecelen>2)
                {
                    TBwhost* pbwhost = (TBwhost*)malloc(sizeof(TBwhost));
                	if(pbwhost != NULL)
                	{				
                		strcpy(pbwhost->str1,(char*)buf[1]);
                        pbwhost->len1 = strlen((char*)buf[1]);
                        strcpy(pbwhost->str2,(char*)buf[2]);
                        pbwhost->len2 = strlen((char*)buf[2]);
                		pbwhost->pass = pass;
                        pbwhost->findnum = 2;
                		hlist_add_head(&pbwhost->bwnode,&g_bwhost[BWHOST_HASHVAL(buf[0])]);
                	}
                }
                piecelen = strlen((char*)buf[1]);
                if(piecelen==3)
                {
                    buf[0][piecelen] = '.';
                    buf[0][piecelen+1] = '\0';
                    piecelen = piecelen +1;
                }
      //          printf("%s\n",(char*)buf[1]);
                if(piecelen>2)
                {
                    TBwhost* pbwhost = (TBwhost*)malloc(sizeof(TBwhost));
                	if(pbwhost != NULL)
                	{				
                		strcpy(pbwhost->str1,(char*)buf[0]);
                        pbwhost->len1 = strlen((char*)buf[0]);
                        strcpy(pbwhost->str2,(char*)buf[2]);
                        pbwhost->len2 = strlen((char*)buf[2]);
                		pbwhost->pass = pass;
                        pbwhost->findnum = 2;
                		hlist_add_head(&pbwhost->bwnode,&g_bwhost[BWHOST_HASHVAL(buf[1])]);
                	}
                }
                piecelen = strlen((char*)buf[2]);
                if(piecelen==3)
                {
                    buf[0][piecelen] = '.';
                    buf[0][piecelen+1] = '\0';
                    piecelen = piecelen +1;
                }
    //            printf("%s\n",(char*)buf[2]);
                if(piecelen>2)
                {
                    TBwhost* pbwhost = (TBwhost*)malloc(sizeof(TBwhost));
                	if(pbwhost != NULL)
                	{				
                		strcpy(pbwhost->str1,(char*)buf[0]);
                        pbwhost->len1 = strlen((char*)buf[0]);
                        strcpy(pbwhost->str2,(char*)buf[1]);
                        pbwhost->len2 = strlen((char*)buf[1]);
                		pbwhost->pass = pass;
                        pbwhost->findnum = 2;
                		hlist_add_head(&pbwhost->bwnode,&g_bwhost[BWHOST_HASHVAL(buf[2])]);
                	}
                }
            }
            break;
        default:
            return;
    }

    g_bwhostnum++;    
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
            u8* ptmp =(u8*)memmem(p,plen,node->str1,node->len1);
            if(ptmp != NULL)
            {   
                if(node->findnum==2)
                {
                    u8* ptmp2 =(u8*)memmem(p,plen,node->str2,node->len2);
                    if(ptmp2 != NULL)
                    {
   //                     printf("%d,str1=%s,str2=%s pass=%d\n",node->findnum,node->str1,node->str2,node->pass);
                        return node->pass;
                    }
                }
                else
                {                
  //                  printf("%d,str1=%s,str2=%s pass=%d\n",node->findnum,node->str1,node->str2,node->pass);
                    return node->pass;
                }
            }        
    	}
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
