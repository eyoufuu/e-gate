/**
 * \brief ������־�ļ�,������־����д�ļ���־������
 * \author zhengjianfang
 * \date 2009-09-18
 */

 #ifndef _FILE_LOG_H_
 #define _FILE_LOG_H_


extern int g_debug_level;
extern char servername[32];
extern char logfilepath[64];

#define WADEBUG(message_level)   if (g_debug_level >= message_level) printf
//#define WADEBUG(message_level)  if (g_debug_level >= message_level) write_debug_log

extern void write_debug_log(const char *fmt, ...);
///���ﶨ�����debug��������WADEBUG(...)�궨���У���������Խ��debug����Խ�ߣ��������־��ϢԽ��ϸ��
#define D_FATAL		0		//��������
#define D_WARNING	1		//������Ϣ�����ڴ��󣬵����п��ܿ��Իָ�
#define D_INFO		2		//��������״̬��Ϣ����ʾ��������
#define D_DETAIL	3		//������е���־������������Ϣ�ȵȣ�Ч����͡�
#define D_ALL		100		//���������־
#define D_NONE		-100	//û����־���

#endif