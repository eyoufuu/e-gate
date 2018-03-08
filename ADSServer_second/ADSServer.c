/**
 * \brief ADSServer���������������������ݰ����浽���ݿ���
 * \author zhengjianfang
 * \date 2009-09-15
 */

#include <string.h>
#include <time.h>
#include <signal.h>
#include <stdlib.h>
#include <unistd.h>
#include <pthread.h>
#include "jhash.h"
#include "smtpParse.h"
#include "pop3Parse.h"
#include "postParse.h"
#include "getParse.h"
#include "flowParse.h"
#include "globDefine.h"
#include "mysqlHandle.h"
#include "DPClient.h"
#include "packetdefine.h"
#include "FileLog.h"
#include "bdbmem.h"
#include "global_time.h"
#include "timetick.h"

void packet_handle(const char *buf, u_int32 buflen);

/**
 * \brief �źŴ�����
 * \param sig �ź�����
 */
void signal_handle(int sig)
{
	dpclient_close();
	final_posthandle();
	final_smtphandle();
	final_pop3handle();
	pthread_detach(p_id);
	exit(0);
}

/**
 * \brief ��ȡ�����ļ���Ŀǰ��û�в��������ļ��ķ�ʽ����ʱ������д
 */
void readConfig()
{
	signal(SIGINT, signal_handle);
	signal(SIGKILL, signal_handle);
	g_debug_level = D_ALL;
	strncpy(logfilepath, "/tmp/", sizeof(logfilepath));
	strncpy(servername, "ADServer", sizeof(servername));
}

u_int32 initServer()
{
	if(!init_global_time())
	{
		WADEBUG(D_FATAL)("init global_time failed.\n");
		return 0;
	}
	
	//init posthandle
	if(!init_posthandle())
	{
		WADEBUG(D_FATAL)("init post_handle failed.\n");
		return 0;
	}

	//init smtphandle
	if(!init_smtphandle())
	{
		WADEBUG(D_FATAL)("init smtp_handle failed.\n");
		return 0;
	}
	
	//init pop3handle
	if(!init_pop3handle())
	{
		WADEBUG(D_FATAL)("init pop3_handle failed.\n");
		return 0;
	}
	
	//init socket client
	if(!dpclientInit())
	{
		WADEBUG(D_FATAL)("init dpclient failed.\n");
		return 0;
	}

       if (0 != pthread_create(&p_id, NULL,time_tick,NULL))
	{
		WADEBUG(D_WARNING)("ERROR: create thread time_tick failed.\n");
		return 0;
       } 
	return 1;
}

/**
 * \brief ������
 */
int main(int argc, char **argv)
{
	//��ȡ�����ļ���Ϣ
	readConfig();

	//�ж��Ƿ��̨����
	int ch = 0;
	while((ch = getopt(argc,argv,"d"))!= -1)
	switch(ch)
	{
		case 'd':
			daemon(1,0);
		break;
		default:
			break;
	}

	if(!initServer())
	{
		WADEBUG(D_FATAL)("init server failed.\n");
		exit(0);
	}
	
	WADEBUG(D_INFO)("The server is running now!!\n");

	//into the main loop
	while (1) 
	{
		char buf[MAXLINE];
		u_int32 packetlen = recvCmd(buf, sizeof(buf));
		if((unsigned int)-1 == packetlen)
		{
			continue;
		}
		packet_handle(buf, packetlen);
	}
	exit(0);

}

/**
 * \brief �������ݰ������ݰ����ͷֱ���д���
 * \param buf ���ݰ�
 * \param buflen ���ݰ�����
 */
void packet_handle(const char *buf, u_int32 buflen)
{
	const Packet_head *packethead;
	packethead = (Packet_head *)buf;
	switch(packethead->cmd)
	{
		case type_smtp:
			{
				smtp_handle(buf, buflen);
				return;
			}
			break;
		case type_post:
			{
				post_handle(buf, buflen);
				return;
			}
			break;
		case type_pop3:
			{
				pop3_handle(buf, buflen);
				return;
			}
			break;
		case type_get:
			{
				get_handle(buf, buflen);
				return;
			}
			break;
	
	}
	WADEBUG(D_INFO)("received the type of the packet not exist :%d\n", packethead->cmd);
}


