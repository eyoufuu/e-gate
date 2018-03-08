/*File: policy.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
 */
#include "policy.h"

TPolicy g_policy[MAX_POLICY];

inline u32 pol_init()
{
	u32 i=0;
	for(i=0;i<MAX_POLICY;i++)
	{
        pol_reset(i);		
    }
}
void policy_echo()
{
    char str[3000];
    u32 i=0;
	u32 j=0;
    for(i=0;i<MAX_POLICY;i++)
	{
		for(j=0;j<256;j++)
		{
            if(g_policy[i].pro[j].pass==0 ||g_policy[i].pro[j].log==1)
            {
                printf("pol:%d proid:%d pass:%d log:%d\n",i,j,g_policy[i].pro[j].pass,g_policy[i].pro[j].log);
            }
		}
        for(j=0;j<256;j++)
        {            
            if(g_policy[i].web[j].pass==0 ||g_policy[i].web[j].log==1 )
            {
                printf("pol:%d webid:%d pass:%d log:%d\n",i,j,g_policy[i].web[j].pass,g_policy[i].web[j].log);
            }
        }
       
        for(j=0;j<256;j++)
        {
            if(g_policy[i].filetype[j].pass == 0 ||g_policy[i].filetype[j].log ==1 )
            {
                printf("pol:%d fileid:%d pass:%d log:%d\n",i,j,g_policy[i].filetype[j].pass,g_policy[i].filetype[j].log);
            }
        }
        if(g_policy[i].keyword.keywordfilter)
            printf("keynum:%d open:%d\n",g_policy[i].keyword.num,g_policy[i].keyword.keywordfilter);
        for(j=0;j<g_policy[i].keyword.num;j++)
        {
            printf("pol:%d key %d log:%d pass:%d gb:%s utf:%s \n",i,j,g_policy[i].keyword.word[j].log,\
                g_policy[i].keyword.word[j].pass,g_policy[i].keyword.word[j].gb,g_policy[i].keyword.word[j].utf);
        }
        
        if(g_policy[i].webfilter)
            printf("pol:%d wf:%d\n",i,g_policy[i].webfilter);
        if(g_policy[i].filetypefilter)
            printf("pol:%d ff:%d\n",i,g_policy[i].filetypefilter);
        if(g_policy[i].webcheck_flag)
            printf("pol:%d webcheck:%d\n",i,g_policy[i].webcheck_flag);
        if(g_policy[i].postaudit)
           printf("pol:%d post:%d\n",i,g_policy[i].postaudit);
        if(g_policy[i].pop3audit)
             printf("pol:%d pop3:%d\n",i,g_policy[i].pop3audit);
        if(g_policy[i].timescope.open)
             printf("pol:%d time:%d week:%d ts1:%d te1:%d ts2:%d te2:%d\n",i,g_policy[i].timescope.open,\
             g_policy[i].timescope.week,g_policy[i].timescope.times1,g_policy[i].timescope.timee1,g_policy[i].timescope.times2,\
             g_policy[i].timescope.timee2);
        if(g_policy[i].smtpaudit)
             printf("pol:%d smtp:%d\n",i,g_policy[i].smtpaudit);        
     }
}
//time
#define NOTINTIME1(polid,time) ((time>g_policy[polid].timescope.timee1) || (time<g_policy[polid].timescope.times1))
#define NOTINTIME2(polid,time) ((time>g_policy[polid].timescope.timee2) || (time<g_policy[polid].timescope.times2))
inline void pol_settimescope(u32 polid,u16 open,u16 week,u16 times1,u16 timee1,u16 times2,u16 timee2)
{
	g_policy[polid].timescope.open = open;
	g_policy[polid].timescope.week = week;
	g_policy[polid].timescope.times1 = times1;
	g_policy[polid].timescope.timee1 = timee1;
	g_policy[polid].timescope.times2 = times2;
	g_policy[polid].timescope.timee2 = timee2;
}
inline void pol_reset(u32 polid)
{
    u32 j=0;
    for(j=0;j<256;j++)
	{
		g_policy[polid].pro[j].pass = 1;
		g_policy[polid].pro[j].log = 0;

		g_policy[polid].web[j].pass = 1;
		g_policy[polid].web[j].log = 0;

		g_policy[polid].filetype[j].pass = 1;
		g_policy[polid].filetype[j].log = 0;                       
	}
    for(j=0;j<50;j++)
    {
        g_policy[polid].keyword.word[j].gb[0]='\0';
        g_policy[polid].keyword.word[j].utf[0]='\0';
        g_policy[polid].keyword.word[j].pass = 1;
        g_policy[polid].keyword.word[j].log = 0;
	    g_policy[polid].keyword.word[j].id = 0;
    }
	g_policy[polid].keyword.num = 0;
	g_policy[polid].keyword.keywordfilter = 0;                                  
    g_policy[polid].proctl = 0;
    g_policy[polid].webfilter = 0;
    g_policy[polid].filetypefilter = 0;
    
    g_policy[polid].webcheck_flag = 0;
    
    g_policy[polid].postaudit = 0;
    g_policy[polid].smtpaudit = 0;
    g_policy[polid].pop3audit = 0;
    pol_settimescope(polid,TIMEGATE_CLOSE, 127,0,2400,0,2400);  
}
inline u32 pol_istimeopen(u32 polid)
{
        return g_policy[polid].timescope.open;
}
inline u32 pol_isintimescope(u32 polid)//0-sun 1-mon
{
        u32 ret = 1;
        u8 week = 0x1<<((7-g_ptm->curdate.tm_wday)%7);
        if(week & g_policy[polid].timescope.week)
        {
            u16 time = g_ptm->curdate.tm_hour*100 + g_ptm->curdate.tm_min;
            if(NOTINTIME1(polid,time) && NOTINTIME2(polid,time))
            {
            	ret = 0;
            }			
        }
        return ret;
}
//proctl
inline u32 pol_isproctl(u32 polid)
{
    return g_policy[polid].proctl;
}
inline void pol_setproctl(u32 polid,u32 val)
{
	g_policy[polid].proctl = val;
}
inline u32 pol_getpropass(u32 polid,u32 proid)
{
	return g_policy[polid].pro[proid].pass;
}
inline u32 pol_isprolog(u32 polid,u32 proid)
{
    return g_policy[polid].pro[proid].log;
}
inline void pol_setpropasslog(u32 polid,u32 proid,u32 pass,u32 log)
{
	g_policy[polid].pro[proid].pass = pass;
	g_policy[polid].pro[proid].log = log;
}
//webfilter
inline u32 pol_iswebfilter(u32 polid)
{
    return g_policy[polid].webfilter;
}
inline void pol_setwebfilter(u32 polid,u32 val)
{
	g_policy[polid].webfilter = val;
}
inline u32 pol_getwebpass(u32 polid,u32 webid)
{
	return g_policy[polid].web[webid].pass;
}
inline u32 pol_isweblog(u32 polid,u32 webid)
{
	return g_policy[polid].web[webid].log;
}
inline u32 pol_iswebremind(u32 polid,u32 webid)
{
    return g_policy[polid].web[webid].remind;
}
inline void pol_setwebpasslog(u32 polid,u32 webid,u32 pass,u32 log,u32 remind)
{
	g_policy[polid].web[webid].pass = pass;
	g_policy[polid].web[webid].log = log;
    g_policy[polid].web[webid].remind = remind;
}
//filetype
inline u32 pol_isfiletypefilter(u32 polid)
{
    return g_policy[polid].filetypefilter;
}
inline void pol_setfiletypefilter(u32 polid,u32 val)
{
	g_policy[polid].filetypefilter = val;
}
inline u32 pol_getfiletypepass(u32 polid,u32 filetypeid)
{
	return g_policy[polid].filetype[filetypeid].pass;	
}
inline u32 pol_isfiletypelog(u32 polid,u32 filetypeid)
{
	return g_policy[polid].filetype[filetypeid].log;
}
inline void pol_setfiletypepasslog(u32 polid,u32 filetypeid,u32 pass,u32 log)
{
	g_policy[polid].filetype[filetypeid].pass = pass;
	g_policy[polid].filetype[filetypeid].log = log;
}
//policy keyword
inline u32 pol_iskeywordfilter(u32 polid)
{
     return g_policy[polid].keyword.keywordfilter;
}
inline void pol_setkeywordfilter(u32 polid,u32 val)
{
	 g_policy[polid].keyword.keywordfilter= val;
}
inline u32 pol_getkeywordnum(u32 polid)
{
        return g_policy[polid].keyword.num;
}
inline u32 pol_setkeywordnum(u32 polid,u32 num)
{
	g_policy[polid].keyword.num = num;
}
inline u32 pol_getkeywordpass(u32 polid,u32 pos)
{
        return g_policy[polid].keyword.word[pos].pass;
}
inline u32 pol_getkeywordid(u32 polid,u32 pos)
{
        return g_policy[polid].keyword.word[pos].id;
}
inline u32 pol_iskeywordlog(u32 polid,u32 pos)
{
        return g_policy[polid].keyword.word[pos].log;
}
inline void pol_setkeywordpasslog(u32 polid,u32 pos,u32 pass,u32 log)
{
	g_policy[polid].keyword.word[pos].pass = pass;
	g_policy[polid].keyword.word[pos].log = log;
}
inline u32 pol_addkeyword(u32 polid,u32 pos,u8* pgb,u8* putf,u32 id)
{
	strcpy(g_policy[polid].keyword.word[pos].gb,pgb);
	strcpy(g_policy[polid].keyword.word[pos].utf,putf);
#if 0	
	if(0 != regcomp(&g_policy[polid].keyword.word[id].reggb,pgb,REG_EXTENDED | REG_ICASE | REG_NOSUB))
	{ 
		int errcode;
		char buf[100];		
		regerror (errcode,NULL,buf,100);
		DEBUG(D_FATAL)("kw add  pgb err %d,%s\n",errcode,buf);
		return 0;
	}
	if(0 != regcomp(&g_policy[polid].keyword.word[id].regutf,putf,REG_EXTENDED | REG_ICASE | REG_NOSUB))
	{ 
		int errcode;
		char buf[100];		
		regerror (errcode,NULL,buf,100);
		DEBUG(D_FATAL)("kw add putf err %d,%s\n",errcode,buf);
		return 0;
	}
#endif
	g_policy[polid].keyword.word[pos].id = id;
	g_policy[polid].keyword.num++;
	
	return 1;	
}
inline u8* pol_getkeywordgb(u32 polid, u32 id)
{
	return (u8 *)(&(g_policy[polid].keyword.word[id].gb[0]));
}
inline u8* pol_getkeywordutf(u32 polid,u32 id)
{
	return (u8 *)(&(g_policy[polid].keyword.word[id].utf[0]));
}
#if 0
inline void pol_delkeyword(u32 polid)
{

	u32 i=0;
	for(i=0;i<g_policy[polid].keyword.num;i++)
	{
		regfree(&g_policy[polid].keyword.word[i].reggb);
		regfree(&g_policy[polid].keyword.word[i].regutf);		
	}
	g_policy[polid].keyword.num = 0;	
}
inline regex_t* pol_getkeywordreggb(u32 polid,u32 id)
{
        return (regex_t*)(&(g_policy[polid].keyword.word[id].reggb));
}
inline regex_t* pol_getkeywordregutf(u32 polid,u32 id)
{
        return (regex_t*)(&(g_policy[polid].keyword.word[id].regutf));
}
#endif
//webcheck_flag init;
inline void pol_setwebcheckflag(u32 polid)
{
    if(g_policy[polid].keyword.keywordfilter)
    {
        g_policy[polid].webcheck_flag += 2;
    }
    if(g_policy[polid].filetypefilter)
    {
        g_policy[polid].webcheck_flag +=4;
    }
    if(g_policy[polid].webfilter)
    {
        g_policy[polid].webcheck_flag +=8;
    }
    if(bwhost_isopen())
    {
        g_policy[polid].webcheck_flag +=1;
    }
}
inline u32 pol_getwebcheckflag(u32 polid)
{
    return g_policy[polid].webcheck_flag;
}
//postaudit
inline u32 pol_ispostaudit(u32 polid)
{
        return g_policy[polid].postaudit;
}
inline void pol_setpostaudit(u32 polid,u32 val)
{
	g_policy[polid].postaudit = val;
}
//smtpaudit
inline u32 pol_issmtpaudit(u32 polid)
{
        return g_policy[polid].smtpaudit;
}
inline void pol_setsmtpaudit(u32 polid,u32 val)
{
	 g_policy[polid].smtpaudit = val;
}
//pop3 audit
inline u32 pol_ispop3audit(u32 polid)
{
        return g_policy[polid].pop3audit;
}
inline void pol_setpop3audit(u32 polid,u32 val)
{
	g_policy[polid].pop3audit = val;
}

/*
void pol_generater()
{//policyid =0 用于存储模块设置
        u32 i =0;
        u32 j=0;
        if(g_modules[MODULE_POLICY].state == 3)
        {
                for(i=1;i<MAX_POLICY;i++)
                {
                         if(g_modules[MODULE_AUDIT].state != 3)
                        {
                                g_policy[i].postaudit = 0;
                                g_policy[i].smtpaudit = 0;
                                g_policy[i].pop3audit = 0;
                        }                        
                         if(g_modules[MODULE_WEBFILTER].state !=3)
                        {
                                g_policy[i].webfilter = 0;
                                g_policy[i].filetypefilter= 0;
                                g_policy[i].keyword.keywordfilter = 0;
                        }                                                                       
                        if(g_modules[MODULE_PROCTL].state != 3)
                        {
                                g_policy[i].proctl = 0;
                                for(j=0;j<256;j++)
                                {  
                                        g_policy[i].pro[j].pass = 1;
                                        g_policy[i].pro[j].log = 0;
                                }
                        }
                }
        }
        else
        {
                for(i=1;i<MAX_POLICY;i++)
                {
                        g_policy[i].timescope.open = 0;
                        if(g_modules[MODULE_AUDIT].state != 3)
                        {
                                g_policy[i].smtpaudit = 0;
                                g_policy[i].pop3audit = 0;
                                g_policy[i].postaudit = 0;
                        }
                        else
                        {
                                g_policy[i].smtpaudit = g_policy[0].smtpaudit;
                                g_policy[i].pop3audit = g_policy[0].pop3audit;
                                g_policy[i].postaudit = g_policy[0].postaudit;
                        }
                       
                         if(g_modules[MODULE_WEBFILTER].state != 3)
                        {
                                g_policy[i].webfilter = 0;
                                g_policy[i].filetypefilter = 0;
                                g_policy[i].keyword.keywordfilter = 0;
                        }
                         else
                        {
                                g_policy[i].webfilter = g_policy[0].webfilter;
                                g_policy[i].filetypefilter = g_policy[0].filetypefilter;
                                g_policy[i].keyword.keywordfilter = g_policy[0].keyword.keywordfilter;
                                g_policy[i].keyword.num = g_policy[0].keyword.num;
                                
                                if(g_policy[i].webfilter == 1)
                                {
                                        for(j=0;j<256;j++)
                                        {
                                                g_policy[i].web[j].pass = g_policy[0].web[j].pass;
                                                g_policy[i].web[j].log = g_policy[0].web[j].log;                                               
                                        }
                                }
                                if(g_policy[i].filetypefilter == 1)
                                {
                                        for(j=0;j<256;j++)
                                        {
                                                g_policy[i].filetype[j].pass = g_policy[0].filetype[j].pass;
                                                g_policy[i].filetype[j].log =  g_policy[0].filetype[j].log;
                                        }
                                }
                                for(j=0;j<g_policy[i].keyword.num;j++)
                                {
                                        g_policy[i].keyword.word[j].pass = g_policy[0].keyword.word[j].pass;
                                        g_policy[i].keyword.word[j].log = g_policy[0].keyword.word[j].log;
                                        strcpy((u8*)(&g_policy[i].keyword.word[j].gb[0]),(u8*)(&g_policy[0].keyword.word[j].gb[0]));
                                        strcpy((u8*)(&g_policy[i].keyword.word[j].utf[0]),(u8*)(&g_policy[0].keyword.word[j].utf[0]));   
                                }
                        }
                         if(g_modules[MODULE_PROCTL].state != 3)
                        {
                                for(j=0;j<256;j++)
                                {                       
                                        g_policy[i].pro[j].pass = 1;
                                        g_policy[i].pro[j].log = 0;
                                }
                        }
                         else
                        {
                                for(j=0;j<256;j++)
                                {                       
                                        g_policy[i].pro[j].pass = g_policy[0].pro[j].pass;
                                        g_policy[i].pro[j].log = g_policy[0].pro[j].log;
                                }
                        }                                   
                }
        }
}*/
