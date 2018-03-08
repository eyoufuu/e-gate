/*File: readsql.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
 */
#include "readsql.h"



static MYSQL *db_handel,mysql;
static MYSQL_ROW row;
static int query_error;
static int connect_ok = 0;
MYSQL_RES *sql_query(char* dbname,char *sql);
int sql_res(MYSQL_RES *result);


int sql_query_init()
{
    mysql_init(&mysql);
    my_bool re_connect = 1;
    mysql_options(&mysql,MYSQL_OPT_RECONNECT,&re_connect);   
    mysql_options(&mysql, MYSQL_SET_CHARSET_NAME, "utf8");//utf8
    db_handel=mysql_real_connect(&mysql,SQL_SERVER,SQL_USER,SQL_PWD,SQL_NAME_BASE,0,0,0); 
    if(db_handel==NULL)
    {
            printf(mysql_error(&mysql));
            return 0;
    }
    connect_ok =1;
    return 1;
}

MYSQL_RES *sql_query(char* dbname,char *sql)
{
   if(connect_ok==0)
   {
   	if(!sql_query_init())
   	{
   		     printf("error sql_mysql init() ,exit\n");
			exit(0);
	}
   }
    static MYSQL_RES *query_result;
    query_error=mysql_query(db_handel,sql);
    if(query_error!=0)
    {
        printf(mysql_error(db_handel));
        return NULL;
    }
    query_result=mysql_store_result(db_handel);
   // mysql_close(db_handel);
    return query_result;
}
void sql_free(MYSQL_RES *result)
{
    mysql_free_result(result);
}
int sql_res(MYSQL_RES *result)
{
    unsigned int i,num_fields;
    MYSQL_FIELD *fileds;
    num_fields=mysql_num_fields(result);
    fileds=mysql_fetch_fields(result);
    while((row=mysql_fetch_row(result))!=NULL)
    {
            for(i=0;i<num_fields;i++)
            {
                    printf("%s: %s \n",fileds[i].name,row[i]?row[i]:"NULL");
            }
    }
    return 0;
}
   

void sql_createprolog(u8* name)
{
	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	u32 i=0;
	u8 buf[500];
	sprintf(buf,"%s %s %s",TABLE_DEFINE_CMD_CREATE,name,TABLE_DEFINE_PRO_CONTENT);
	sql_query(SQL_NAME_BASE,buf);
}

void sql_readcardtype()
{
	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	u32 i=0;
	results = sql_query(SQL_NAME_BASE,"select name,type from cardinfo");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query cardinfo error\n");
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
		if_setphydevtype(row[0],atoi(row[1]));
	}
}
/*
void sql_readmodule()
{
#define MODULE_ID           atoi(row[0])
#define MODULE_STATE     atoi(row[1])
#define MODULE_SERVICE atoi(row[2])
        MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
        char buf[200];
	sprintf(buf,"select id,state,service from module where service = %d",0);
	results = sql_query(SQL_NAME_BASE,buf);
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query global para error\n");
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
	        g_modules[MODULE_ID].id = MODULE_ID;
                g_modules[MODULE_ID].state = MODULE_STATE;
                g_modules[MODULE_ID].service = MODULE_SERVICE;
	}
}*/
void sql_readprocattype()
{
    MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	results = sql_query(SQL_NAME_BASE,"select proid,type from procat where proid>=0  order by proid");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query protype error\n");
		return;
	}
    memset((void*)g_protype,0,sizeof(g_protype));
	while((row = mysql_fetch_row(results))!=NULL)
	{
        if(atoi(row[0])>256)
            continue;
        g_protype[atoi(row[0])] = __hnl(atoi(row[1]));        

	}
    sql_free(results);
    
}
void sql_readglobalpara()
{
#define GBL_LOGINTERVALTIME     atoi(row[0])
#define GBL_ISQOSOPEN           atoi(row[1])
#define GBL_GATE				atoi(row[2])
#define GBL_ISIPMACBIND		    atoi(row[3])
#define GBL_SYSTEMMODE 	    atoi(row[4])
#define GBL_ISREMIND 		    atoi(row[5])
#define GBL_ISFILETYPEOPEN      atoi(row[6])
#define GBL_ISMAILOPEN	             atoi(row[7])
#define GBL_ISBBSOPEN                 atoi(row[8])
#define GBL_ISIMOPEN                     atoi(row[9])
#define GBL_ISNETDISKOPEN         atoi(row[10])
#define GBL_ISFTPOPEN                  atoi(row[11])
#define GBL_ISTFTPOPEN                 atoi(row[12])
#define GBL_ISARP_IPMACBIND      atoi(row[13])   
#define GBL_ISBLOGOPEN                atoi(row[14])

	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	results = sql_query(SQL_NAME_BASE,"select logintervaltime,isqosopen,gate,isipmacbind,systemmode,isremindpage,isfiletypeopen \
 	,ismailopen,isbbsopen,isimopen,isnetdiskopen,isftpopen,istftpopen,isarp_ipmacbind	,isblogopen from globalpara");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query global para error\n");
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
	    	g_gate = GBL_GATE;
		g_isqosopen = GBL_ISQOSOPEN;
		log2_setintervaltime(GBL_LOGINTERVALTIME);
		g_sysmode = GBL_SYSTEMMODE;
		g_isipmacbind = GBL_ISIPMACBIND;
	        g_isremind = GBL_ISREMIND;
	        g_isfiletypeopen =  GBL_ISFILETYPEOPEN;

		g_ismailopen = GBL_ISMAILOPEN;
		g_isbbsopen = GBL_ISBBSOPEN;
		g_isimopen = GBL_ISIMOPEN;
		g_isnetdiskopen = GBL_ISNETDISKOPEN;
		g_isftpopen = GBL_ISFILETYPEOPEN;
		g_istftpopen = GBL_ISTFTPOPEN;
		g_isarp_ipmacbind = GBL_ISARP_IPMACBIND;	
		g_isblogopen = GBL_ISBLOGOPEN;
	}
    	sql_free(results);
}

void sql_readfilecat()
{
#define FILECAT_ID  		atoi(row[0])
#define FILECAT_NAME 		row[1]
	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	results = sql_query(SQL_NAME_BASE,"select typeid,name from filecat");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query filecat error\n");
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
		filetype_insertftnode(FILECAT_ID,FILECAT_NAME);
	}
    sql_free(results);
/*	int i=0;
	for(i=0;i<MAX_FILETYPE;i++)
	{
		struct hlist_node *pos,*n;
		TFtnode* node;
		hlist_for_each_safe(pos,n,&g_ftlist[i])
		{
			node = hlist_entry(pos,TFtnode,ftnode);
			printf("t=%s\n",node->type);
		}
	}
	printf("%d\n",g_ftnum);*/
}
void sql_readbwip()
{
    MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	results = sql_query(SQL_NAME_BASE,"select ip,pass from specip");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query useraccount error\n");
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
        u32 ip = strtoul(row[0],NULL,10);
        u32 netip = __hnl(ip);
		u16 hash = GETIPHASH(netip);
		u8  netseghash = GETNETSEGHASH(netip);
	
        TEmpnode* pemp = emp_getempnode(hash,netseghash,netip);
        if(pemp != NULL)
        {
            pemp->emp.specip = atoi(row[1]);
        }
	}
    sql_free(results);
}
void sql_readbwhost()
{
	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	results = sql_query(SQL_NAME_BASE,"select host,pass from specweb");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query useraccount error\n");
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
        bwhost_add(row[0],atoi(row[1]));		
	}
    sql_free(results);
	
}
/*
void sql_readalluseraccount()
{
#define ACC_ID				atoi(row[0])
#define ACC_ACCOUNT			row[1]
#define ACC_PASSWD			row[2]
#define ACC_POLICYID		atoi(row[3])

	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
    char buf[100];
    
	results = sql_query(SQL_NAME_BASE,"select id,account,passwd policyid from useraccount");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query useraccount error\n");
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
		TAccount acc;
		acc.usedip = 0;
		acc.bindip = 0;
		acc.policyid = ACC_POLICYID;
		
		acc_addaccount(ACC_ID,&acc);		
	}
}
u32 sql_readuseaccount(u32 accid)
{
	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	u32 rownum = 0;
	char buf[200];
	sprintf(buf,"select * from useraccount where id=%d",accid);
	results = sql_query(SQL_NAME_BASE,buf);
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query useraccount error\n");
		return 0;
	}
	if(0 == (rownum=mysql_num_rows(results)))
	{
		return 0;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
		TAccount acc;
		acc.usedip = 0;
		acc.bindip = ACC_BINDIP;

		acc.groupid = ACC_GROUPID;
		acc.personid = ACC_PERSONID;
		
		acc.policyid = ACC_POLICYID;
    	acc_addaccount(accid,&acc);		
	}
	return rownum;
}
*/
#if 1
u32 sql_readallipmac()
{
#define IPMAC_IP		strtoul(row[0],NULL,10)
#define IPMAC_MAC		row[1]
#define IPMAC_BIND 		atoi(row[2])
	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	char buf[200];
    u8 strTmp[3];
    u32 i = 0;
	u8 ipmac[12];
	u8 offset = 4;
	sprintf(buf,"select * from ipmac");
	results = sql_query(SQL_NAME_BASE,buf);
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query all user ip error \n");
		return;
	}
//	fileds= mysql_fetch_fields(results);
	while((row = mysql_fetch_row(results))!=NULL)
	{
		*((u32*)ipmac) = __hnl(IPMAC_IP);
        if(IPMAC_BIND)
        {
    		for(i=0;i<6;i++)   
    		{              
    	        strTmp[0] = IPMAC_MAC[i*3];   
    	        strTmp[1] = IPMAC_MAC[i*3+1];   
    	        strTmp[2] = 0;   
    	        ipmac[offset+i] = strtol(strTmp,NULL,16);
    		}
        }
        else
        {
            for(i=0;i<6;i++)
            {
                ipmac[offset+i] = 0;            
            }
        }
		SendKernelMessage((char*)ipmac,0,IMP2_IPMAC,10);
	}
    sql_free(results);
}
#endif
#if 0
u32 sql_readallipmac()
{
#define IPMAC_IP		strtoul(row[0],NULL,10)
#define IPMAC_MAC		row[1]
#define IPMAC_BIND 		atoi(row[2])
	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	char buf[200];
    u8 strTmp[3];
    u32 ip = 0;
	u8 mac[7];
	u32  i = 0;
	sprintf(buf,"select * from ipmac where bind=1");
	results = sql_query(SQL_NAME_BASE,buf);
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query all user ip error \n");
		return;
	}
//	fileds= mysql_fetch_fields(results);
	while((row = mysql_fetch_row(results))!=NULL)
	{
		ip = __hnl(IPMAC_IP);
		for(i=0;i<6;i++)   
		{   
		        strTmp[0] = IPMAC_MAC[i*3];   
		        strTmp[1] = IPMAC_MAC[i*3+1];   
		        strTmp[2] = 0;   
		        mac[i]  =strtol(strTmp,NULL,16);
		}
		u16 iphash = GETIPHASH(ip);
		u8   netseghash = GETNETSEGHASH(ip);
		TEmpnode* pemp = emp_getempnode(iphash,netseghash,ip);
		if(pemp != NULL)
		{
			memcpy(pemp->emp.mac,mac,6);
		}
	}		
}
#endif
u32 sql_readuserip(u32 ip,TEmployee* p)
{
#define IP_NETSEGID			atoi(row[0])
#define IP_IP					strtoul(row[1],NULL,10)
#define IP_MAC 				row[2]
#define IP_IPMACBIND 			atoi(row[3])
#define IP_NAME 				row[4]
#define IP_GROUPID 			atoi(row[5]) 
#define IP_PERSONID 			atoi(row[6])
#define IP_POLICYID 		        atoi(row[7])
#define IP_SPECIP 				atoi(row[8])

        u8   strTmp[3];
        u32 i = 0;
	u32 rownum = 0;
	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	char buf[200];
	sprintf(buf,"select * from userip where ip=%u",ip);
	results = sql_query(SQL_NAME_BASE,buf);
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query userip error %u\n",ip);
		return 0;
	}
//	fileds= mysql_fetch_fields(results);
	if(0 == (rownum=mysql_num_rows(results)))
	{
		return 0;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
                p->ip = __hnl(ip);
                p->mode = SYS_IP;
                p->accid = 0;
                p->activetime = g_ptm->curtime;
                p->policyid = IP_POLICYID;
                p->specip = IP_SPECIP;
                p->personid = IP_PERSONID;
                p->groupid = IP_GROUPID;
		if(IP_MAC !=NULL)
		{
	                for(i=0;i<6;i++)   
	                {   
	                        strTmp[0] = IP_MAC[i*3];   
	                        strTmp[1] = IP_MAC[i*3+1];   
	                        strTmp[2] = 0;   
	                        p->mac[i] = strtol(strTmp,NULL,16);
	                }
		}
	}
    sql_free(results);
	return rownum;
}
void sql_readalluserip()
{
	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;

	results = sql_query(SQL_NAME_BASE,"select bindip,policyid from useraccount");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query all user ip error \n");
		return;
	}
//	fileds= mysql_fetch_fields(results);
	while((row = mysql_fetch_row(results))!=NULL)
	{
		TEmployee emp;
		emp.ip = __hnl(strtoul(row[0],NULL,10));
		emp.mode = SYS_IP;
		emp.accid = 0;
		emp.activetime = g_ptm->curtime;
        emp.policyid = atoi(row[1]);
		emp.specip = 2;
        emp.personid = 0;
		emp.groupid = 0;
        emp.remindtime = 0;
        emp.mac[0]= '\0';
/*		if(IP_MAC != NULL)
		{
			for(i=0;i<6;i++)   
            {   
                    strTmp[0] = IP_MAC[i*3];   
                    strTmp[1] = IP_MAC[i*3+1];   
                    strTmp[2] = 0;   
                    emp.mac[i] = strtol(strTmp,NULL,16);
            }
		}*/
		u16 iphash = GETIPHASH(emp.ip);
		u8 netseghash = GETNETSEGHASH(emp.ip);
		emp_updateempnode(iphash,netseghash,&emp);		
	}
    sql_free(results);
}

void sql_readnetseg()
{
#define NETSEG_IPS				strtoul(row[0],NULL,10)
#define NETSEG_IPE				strtoul(row[1],NULL,10)
#define NETSEG_POLICYID			atoi(row[2])

	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	char buf[200];
//del old emp node;
    emp_delallempnode();
        
	sprintf(buf,"select ips,ipe,policyid from netseg where monitor=1");
	results = sql_query(SQL_NAME_BASE,buf);
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query userip netseg \n");
		return;
	}
//	fileds= mysql_fetch_fields(results);
	while((row = mysql_fetch_row(results))!=NULL)
	{
		u32 i = 0;
		TEmployee emp;
		for(i=NETSEG_IPS;i<=NETSEG_IPE;i++)
		{		
			emp.ip = __hnl(i);
			if(likely(g_sysmode == SYS_IP))
			{
				emp.mode = SYS_IP;
			}
			else
			{
				emp.mode = SYS_ACCOUNT;
			}
			emp.accid = 0;
			emp.activetime = g_ptm->curtime;
            emp.policyid = NETSEG_POLICYID;
            emp.personid = 0;
			emp.groupid = 0;
            emp.specip = 2;
            emp.remindtime = 0;
            emp.mac[0] = '\0';
         	u16 iphash = GETIPHASH(emp.ip);
			u8 netseghash = GETNETSEGHASH(emp.ip);

			emp_addempnode(iphash,netseghash,&emp);
        }
	}
    sql_free(results);
}
void sql_readpolicy(u32 polid)
{
#define POL_PROCTL				row[0]
#define POL_WEBFILTER			atoi(row[1])
#define POL_WEBINFO			    row[2]
#define POL_FILETYPEFILTER		atoi(row[3])
#define POL_FILEINFO            row[4]

#define POL_KEYWORDFILTER		atoi(row[5])
#define POL_KEYWORDUTF			row[6]
#define POL_KEYWORDGB			row[7]
#define POL_SMTPAUDIT			atoi(row[8])
#define POL_POP3AUDIT			atoi(row[9])
#define POL_POSTAUDIT			atoi(row[10])
#define POL_TIME				atoi(row[11])
#define POL_WEEK			    atoi(row[12])
#define POL_TIMES1				atoi(row[13])
#define POL_TIMEE1				atoi(row[14])
#define POL_TIMES2			    atoi(row[16])
#define POL_TIMEE2				atoi(row[16])

	MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
	char buf[500];
    
    int i=0;
    int len=0;

    sprintf(buf,"select proctl,webfilter,webinfo,filetypefilter,fileinfo,keywordfilter,\
        keywordutf,keywordgb,smtpaudit,pop3audit,postaudit,time,week,times1,timee1,times2,timee2 \
        from policy where (stat=1 and policyid =%d)",polid);
	results = sql_query(SQL_NAME_BASE,buf);
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query proid error %d\n",polid);
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
        char pieces[256][10];
        char keywordutf[50][100];
        char keywordgb[50][100];
 //       memset(pieces,0,1280);      
 //       printf("@@@@@@@@@@%s\n",POL_PROCTL);
        len = ctl_split((char**)pieces,256,10,POL_PROCTL,"|");
//        printf("@@@@@@@@@@%s %d\n",POL_PROCTL,len);
        for(i=0;i<len;i++)
        {
            u32 val = 0;
            char* pstr = strchr((char*)pieces[i],',');
            if(pstr != NULL)
            {
                val = atoi((char*)(pstr+1));
                *pstr = '\0';
                u32 proid = atoi((char*)pieces[i]);
                switch(val)
                {
                    case 1:
                        pol_setpropasslog(polid,proid,1,1);
                        break;
                    case 2:
                        pol_setpropasslog(polid,proid,0,0);
                        break;
                    case 3:
                        pol_setpropasslog(polid,proid,0,1);
                        break;
                    default:
                        break;
                }                
            }            
        }
        
		pol_setwebfilter(polid,POL_WEBFILTER);
  //       printf("webinfo=%s len=%d\n",POL_WEBINFO,len);
 //       memset(pieces,0,1280);
        len = ctl_split((char**)pieces,256,10,POL_WEBINFO,"|");
       
        for(i=0;i<len;i++)
        {
            u32 webid = 0;
            u32 val = 0;
            char* pstr = strchr((char*)pieces[i],',');           
            if(pstr != NULL)
            {
                val = atoi((char*)(pstr+1));
                *pstr = '\0';
                u32 proid = atoi((char*)pieces[i]);
                switch(val)
                {
                    case 1:
                        pol_setwebpasslog(polid,proid,1,1,0);
                        break;
                    case 2:
                        pol_setwebpasslog(polid,proid,0,0,0);
                        break;
                    case 3:
                        pol_setwebpasslog(polid,proid,0,1,0);
                        break;
                    case 4:
                        pol_setwebpasslog(polid,proid,1,0,1);
                        break;
                    case 5:
                         pol_setwebpasslog(polid,proid,1,1,1);

                    default:
                        break;
                }

            }
           
        }
        
		pol_setfiletypefilter(polid,POL_FILETYPEFILTER);
        
 //       memset(pieces,0,1280);
        len = ctl_split((char**)pieces,256,10,POL_FILEINFO,"|");
        for(i=0;i<len;i++)
        {
            u32 fileid = 0;
            u32 val = 0;
            char* pstr = strchr((char*)pieces[i],',');           
            if(pstr != NULL)
            {
                val = atoi((char*)(pstr+1));
                *pstr = '\0';
                u32 proid = atoi((char*)pieces[i]);
                switch(val)
                {
                    case 1:
                        pol_setfiletypepasslog(polid,proid,1,1);
                        break;
                    case 2:
                        pol_setfiletypepasslog(polid,proid,0,0);
                        break;
                    case 3:
                        pol_setfiletypepasslog(polid,proid,0,1);
                        break;
                    default:
                        break;
                }
            }            
        }
        
		pol_setkeywordfilter(polid,POL_KEYWORDFILTER);

        len = ctl_split((char**)keywordutf,50,100,POL_KEYWORDUTF,"|");
        ctl_split((char**)keywordgb,50,100,POL_KEYWORDGB,"|");
        

        char tmp1[5][100];
        char tmp2[5][100];
        for(i=0;i<len;i++)
        {
            ctl_split((char**)tmp1,5,100,keywordutf[i],",");
            ctl_split((char**)tmp2,5,100,keywordgb[i],",");
            pol_addkeyword(polid,i,tmp2[0],tmp1[0],0);
            switch(tmp1[1][0]-'0')
            {
                case 1:
                    pol_setkeywordpasslog(polid,i,1,1);
                    break;
                case 2:
                    pol_setkeywordpasslog(polid,i,0,0);
                    break;
                case 3:
                    pol_setkeywordpasslog(polid,i,0,1);
                    break;
                default:
                    break;
            }		
        }
        pol_setwebcheckflag(polid);
		pol_setsmtpaudit(polid,POL_SMTPAUDIT);
		pol_setpop3audit(polid,POL_POP3AUDIT);
		pol_setpostaudit(polid,POL_POSTAUDIT);
        pol_settimescope(polid,POL_TIME,POL_WEEK,POL_TIMES1,POL_TIMEE1,POL_TIMES2,POL_TIMEE2);
	}
    sql_free(results);
}
void sql_readallpolicy()
{
	u32 i =0 ;
	for(i=0;i<MAX_POLICY;i++)
	{
		sql_readpolicy(i);
	}
	return;
}
void sql_readfiletype()
{
    MYSQL_RES * results;
	MYSQL_FIELD *fileds;
	u32 num_fileds;
    if(!g_isfiletypeopen)
    {
        g_filechecklen = 0;
        return;
    }
	results = sql_query(SQL_NAME_BASE,"select address from file_transter where pass=0 and function='filetype'");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query file_transter error\n");
		return;
	}
    u32 j = 0;
	while((row = mysql_fetch_row(results))!=NULL)
	{
        char pieces[20][100];
        u32 len = ctl_split((char**)pieces,20,100,row[0]," ");
        u32 i=0;
        if(len>20)
            continue;
        g_filecheck[j][0] = len;
        for(i=1;i<=len;i++)//i start from 1 0:len
        {            
            g_filecheck[j][i]=strtol((char*)pieces[i-1],NULL,16);
        }
        j++;
        g_filechecklen++;	   
	}
    sql_free(results);
}
void sql_readfiletrans_mail()
{
    MYSQL_RES * results;
	MYSQL_FIELD *fileds;

	results = sql_query(SQL_NAME_BASE,"select address from file_transter where pass=0 and function='webmail'");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query file_transter error\n");
		return;
	}

	while((row = mysql_fetch_row(results))!=NULL)
	{
        		bwhost_add(row[0],0);	
	}
   	sql_free(results);
}
void sql_readfiletrans_bbs()
{
    MYSQL_RES * results;
	MYSQL_FIELD *fileds;

	results = sql_query(SQL_NAME_BASE,"select address from file_transter where pass=0 and function='bbs'");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query file_transter error\n");
		return;
	}

	while((row = mysql_fetch_row(results))!=NULL)
	{
        		bwhost_add(row[0],0);	
	}
   	sql_free(results);
}
void sql_readfiletrans_netdisk()
{
    MYSQL_RES * results;
	MYSQL_FIELD *fileds;

	results = sql_query(SQL_NAME_BASE,"select address from file_transter where pass=0 and function='netdisk'");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query file_transter error\n");
		return;
	}

	while((row = mysql_fetch_row(results))!=NULL)
	{
        		bwhost_add(row[0],0);	
	}
   	sql_free(results);
}
void sql_readfiletrans_blog()
{
    MYSQL_RES * results;
	MYSQL_FIELD *fileds;

	results = sql_query(SQL_NAME_BASE,"select address from file_transter where pass=0 and function='blog'");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query file_transter error\n");
		return;
	}

	while((row = mysql_fetch_row(results))!=NULL)
	{
        		bwhost_add(row[0],0);	
	}
   	sql_free(results);
}
void sql_readfiletrans_im()
{
    	MYSQL_RES * results;
	MYSQL_FIELD *fileds;

	results = sql_query(SQL_NAME_BASE,"select address from file_transter where pass=0 and function='im'");
	if(NULL == results)
	{
		DEBUG(D_FATAL)("query file_transter error\n");
		return;
	}
	while((row = mysql_fetch_row(results))!=NULL)
	{
        		u32 i=0;
		u32 proid = atoi(row[0]);
		if(proid<256)
		{
			for(i=0;i<100;i++)
			{
				pol_setpropasslog(i,proid,0,0);
			}
		}
	}
   	sql_free(results);
}
/*int main(int argc,char *argv[])
{
        MYSQL_RES * results;
        results=sql_query(DB_SQL_EMAIL_FILTER);
        query_show(results);
        return 0;
}*/
