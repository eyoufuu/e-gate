/**
 *\ brief ����POP3���ݰ����ļ�
 *\ author zhengjianfang 
 *\ date 2009-09-11
 */


#ifndef _POP3_PSRSE_H
#define _POP3_PSRSE_H

#include "globDefine.h"
#include "bdbmem.h"

#define MAILHEAD "Received"
#define MAILEND "\r\n.\r\n"

typedef struct _pop3_head
{
	unsigned int ack;		//���ӵ�ACK
	unsigned int time;		//������ʱ�䣬Ϊ��һ������ʱ��
	
}Pop3_head;

#define POP3_MEM_CONN_SIZE 10   //����POST������Ϣ��BDB��С����λΪM


extern mem_hash *pop3_mem;   //���smtp����Smtp_head �����������Ƿ���ͬһ������

/**
 * \brief ��ʼ������post���ݵ�һЩ��Դ
 */
u_int32 init_pop3handle();

/**
 * \brief ��post�������Դ���ͷ�
 */
 void final_pop3handle();

/**
 * \brief �ж��Ƿ���һ���ʼ��ĵ�һ����
 * \param buf: Դ�ַ���
 * \param buflen:Դ�ַ�������
 * \return ��POP2 mail ��ͷ������true�� ���򷵻�false
 */
u_int32 isMailHead(const char* buf,const u_int16 buflen);

/**
 * \brief �ж��Ƿ����ʼ���β(һ���ʼ��������)
 * \param buf: Դ�ַ���
 * \param buflen:Դ�ַ�������
 * \return ��POP3 MAIL��β������true�� ���򷵻�false
 */
u_int32 isMailEnd(const char* buf,const u_int16 buflen);

/**
 * \brief ����POP3��
 * \param buf pop3���ݰ�
 * \param buflen pop3���ݰ�����
 */
void pop3_handle(const char *buf, u_int32 buflen);

#endif