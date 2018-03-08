#include <stdio.h>
#include "FileLog.h"
#include "timetick.h"
#include "postParse.h"
#include "smtpParse.h"
#include "pop3Parse.h"
#include "globDefine.h"
#include "packetdefine.h"

pthread_t  p_id;

void* time_tick(void* param)
{
	while(1)
	{
		sleep(60);
		char value[32] = {0};
		u_int64 jhash_value = 0;
		u_int32 time_now = 0;
		if(RET_BDB_OK == get_first_dbrecord(post_mem, &jhash_value, value, sizeof(value)-1))
		{
			Post_head *post_head;
			post_head = (Post_head*)value;
			time_now = (u_int32)(time(NULL));
			if(time_now - post_head->time > 1800)
			{
				 delete_mem_hash_core(post_mem, &jhash_value);
			}
			jhash_value = 0;
			post_head = NULL;
			while(RET_BDB_OK == get_next_dbrecord(post_mem, &jhash_value, value, sizeof(value)-1))
			{
				post_head = (Post_head*)value;
				if(time_now - post_head->time > 1800)
				{
					 delete_mem_hash_core(post_mem, &jhash_value); 
				}
				jhash_value = 0;
				post_head = NULL;
			}
		}

		if(RET_BDB_OK == get_first_dbrecord(pop3_mem, &jhash_value, value, sizeof(value)-1))
		{
			Pop3_head *pop3info;
			pop3info = (Pop3_head *)value;
			if(time_now - pop3info->time > 1800)
			{
				 delete_mem_hash_core(pop3_mem, &jhash_value);
			}
			jhash_value = 0;
			pop3info = NULL;
			while(RET_BDB_OK == get_next_dbrecord(pop3_mem, &jhash_value, value, sizeof(value)-1))
			{
				pop3info = (Pop3_head *)value;
				if(time_now - pop3info->time > 1800)
				{
					 delete_mem_hash_core(pop3_mem, &jhash_value); 
				}
				jhash_value = 0;
				pop3info = NULL;
			}
		}

		if(RET_BDB_OK == get_first_dbrecord(smtp_mem, &jhash_value, value, sizeof(value)-1))
		{
			SmtpInfo *smtpinfo;
			smtpinfo = (SmtpInfo *)value;
			if(time_now - smtpinfo->starttime > 1800)
			{
				 delete_mem_hash_core(smtp_mem, &jhash_value);
			}
			jhash_value = 0;
			smtpinfo = NULL;
			while(RET_BDB_OK == get_next_dbrecord(smtp_mem, &jhash_value, value, sizeof(value)-1))
			{
				smtpinfo = (SmtpInfo *)value;
				if(time_now - smtpinfo->starttime > 1800)
				{
					 delete_mem_hash_core(smtp_mem, &jhash_value); 
				}
				jhash_value = 0;
				smtpinfo = NULL;
			}
		}
	}
}

