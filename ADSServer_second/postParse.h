/**
 * \brief ����post���ݰ�
 * \author zhengjianfang
 * \date 2009-09-12
 */
 #ifndef _POST_PARSE_H_
 #define _POST_PARSE_H_

#include "globDefine.h"
#include "String.h"
#include "bdbmem.h"


#define HOSTNAME "Host"
#define POSTMAIL "mail"

typedef struct _post_head
{
	unsigned int type;    //post���ͣ���ΪMAIL�ͷ���
	unsigned int ack;		//���ӵ�ACK
	unsigned int time;		//������ʱ�䣬Ϊ��һ������ʱ��
	unsigned int post_stat;	  //post״̬
	
}Post_head;

#define POST_MEM_CONN_SIZE 10   //����POST������Ϣ��BDB��С����λΪM
#define POST_MEM_HOST_SIZE 4	//������Ҫ��Ƶ�POST HOST����Ϣ����λΪM

/*
 * \brief POST��������
 */
enum
{
	post_mail = 1,	//POST MAIL
	post_post = 2	//POST ����
};

/*
 * \brief POST����״̬
 */
enum
{
	can_anayle = 0,     //host�����ݿ���У���ʾ�ð����Խ���
	anayled	=	1,		//�ð��Ѿ�������
	can_not_anayle = 2  //�ð����ܱ�����
};

extern mem_hash *post_mem;   //���post����Post_head �����������Ƿ���ͬһ������
//extern mem_hash *post_host;	//���淢����ʱ���host���֣�ֻ������Ŀǰ֧�ֵ�host������ƣ������Ĳ��������ݿ�

/**
 * \brief ��ʼ������post���ݵ�һЩ��Դ
 */
u_int32 init_posthandle();

/**
 * \brief ��post�������Դ���ͷ�
 */
 void final_posthandle();

/*
 * \brief ��ָ��POST�ַ����в���HOST
 * \param buf: Դ�ַ���
 * \param buflen:Դ�ַ�������
 * \param hostname: ��ȡ����HOST��ַ
 * \param s_hostlen: HOST����
 */
void getHostFrom(const char* buf,const u_int16 buflen,char** hostname,u_int16* s_hostlen);


/**
 *\brief �жϸð��Ƿ���post mail��
 * \param buf: Դ�ַ���
 * \param buflen:Դ�ַ�������
 * \return ��POST MAIL��������true�� ���򷵻�false
 */
u_int32 isPostmailPack(const char* buf,const u_int16 buflen);

 /**
 * \brief ����POST��
 * \param buf POST���ݰ�
 * \param buflen POST���ݰ�����
 */
void post_handle(const char *buf, u_int32 buflen);

 #endif
