#include "bwhost.h"
#include "cli.h"
#include "serv.h"
#include "emp.h"


static u8 g_bwhostnum = 0;
static TBwhost g_bwhost[HOST_BW_NUM];

u32 bwhost_isopen()
{
	return g_bwhostnum;
}
void bwhost_init()
{
	g_bwhostnum = 0;
}
void bwhost_uninit()
{
	u32 i = 0;
	for(i=0;i<g_bwhostnum;i++)
	{
		regfree(&g_bwhost[g_bwhostnum].reg);
	}
	g_bwhostnum = 0;
}
u32 bwhost_add(u8* pstr, u32 pass)
{
	strcpy(g_bwhost[g_bwhostnum].str,pstr);
	g_bwhost[g_bwhostnum].val = pass;
	if(0 != regcomp(&g_bwhost[g_bwhostnum].reg,pstr,REG_EXTENDED | REG_ICASE | REG_NOSUB))
	{ 
		int errcode;
		char buf[100];		
		regerror (errcode,NULL,buf,100);
		DEBUG(D_FATAL)("bw add error %d,%s\n",errcode,buf);
		return 0;
	}
	g_bwhostnum++;
	return 1;	
}
u32 bwhost_isspechost(u8* pstr,u32 len)
{
	u32 i = 0;
	regmatch_t pm[2];
	const size_t nmatch = 2;
	pstr[len] = 0;
	for(i=0;i<g_bwhostnum;i++)
	{
		if(0==regexec(&g_bwhost[i].reg,(char*)pstr,nmatch,pm,0))
		{
			return g_bwhost[i].val;
		}
	}	
	return HOST_SPEC_NONE;
}
