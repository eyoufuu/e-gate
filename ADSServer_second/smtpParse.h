/**
 *\ brief ����SMTP���ݰ����ļ�
 *\ author zhengjianfang 
 *\ date 2009-09-11
 */

#ifndef _SMTP_PARSE_H_
#define _SMTP_PARSE_H_


#include <string.h>
#include "String.h"
#include "globDefine.h"
#include "bdbmem.h"

#define MAILFROM "MAIL FROM"
#define MAILTO "RCPT TO"
#define DATA_BIG "DATA"
#define DATA_SMALL "Data"
#define SMTPQUIT "QUIT"

#define SMTP_MEM_CONN_SIZE 8   //����POST������Ϣ��BDB��С����λΪM
extern mem_hash *smtp_mem;
/**
 * \brief SMTP��ͷ�Ľṹ��
 */
typedef struct _SmtpInfo
{
	char s_mailaddr[100];		//�����������ַ
	char d_mailaddr[1024];	//�ռ��������ַ�������ж��
       u_int32   id; 				//���潫��ͷ��Ϣ���뵽���ݿ�󷵻ص�ID������������Ű����ݵı�
	u_int32 status;			//��״̬��ָ����ʱ���͵��ĸ�����1��ʾ�յ���MAIL FROM����2��ʾ�յ�RCTP��
	u_int32 starttime;			//�յ�MAIL FROM����ʱ�䣬���������ôβ����Ŀ�ʼʱ��
	
}SmtpInfo;


/**
 * \brief ��ʼ������smtp���ݵ�һЩ��Դ
 */
u_int32 init_smtphandle();

/**
 * \brief ��smtp�������Դ���ͷ�
 */
void final_smtphandle();

/*
 * \brief ��ָ��SMTP�ַ����в��ҷ����������ַ
 * \param buf: Դ�ַ���
 * \param buflen:Դ�ַ�������
 * \param s_mail: ��ȡ���ķ����������ַ
 * \param s_maillen: �����������ַ����
 */
void getMailFrom(const char* buf,const u_int16 buflen,char** s_mail,u_int16* s_maillen);

/*
 * \brief ��ָ��SMTP�ַ����в����ռ��������ַ
 * \param buf: Դ�ַ���
 * \param buflen:Դ�ַ�������
 * \param d_mail: ��ȡ�����ռ��������ַ
 * \param d_maillen: �ռ��������ַ����
 */
void getMailTo(const char* buf,const u_int16 buflen,char** d_mail,u_int16* d_maillen);

/**
 *\brief �жϸð��Ƿ���SMTP ��DATA��
 * \param buf: Դ�ַ���
 * \param buflen:Դ�ַ�������
 * \return ��DATA��������true�� ���򷵻�false
 */
u_int32 isTheDATAPack(const char* buf,const u_int16 buflen);


/**
 *\brief �жϸð��Ƿ���SMTP ��QUIT��
 * \param buf: Դ�ַ���
 * \param buflen:Դ�ַ�������
 * \return ��QUIT��������true�� ���򷵻�false
 */
u_int32 isSMTPQUITPack(const char* buf,const u_int16 buflen);


/**
 * \brief ��ʼ�����SMTP��ͷ�Ľṹ�壬�����յ�MAIL FROM����ʱ�����
 * \param pSmtpInfo: ��Ҫ��ʼ����SMTP��ͷ�Ľṹ��
 * \param s_mail: �����������ַ
 * \param s_maillen: �����������ַ�ĳ���
 */
void Initialize_SmtpInfo(SmtpInfo* pSmtpInfo, char *s_mail, u_int16 s_maillen);


/**
 * \brief ����SMTP��
 * \param buf smtp���ݰ�
 * \param buflen smtp���ݰ�����
 */
void smtp_handle(const char *buf, u_int32 buflen);

#endif