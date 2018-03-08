/**
 *\ brief 处理POP3数据包的文件
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
	unsigned int ack;		//连接的ACK
	unsigned int time;		//发包的时间，为第一个包的时间
	
}Pop3_head;

#define POP3_MEM_CONN_SIZE 10   //保存POST连接信息的BDB大小，单位为M


extern mem_hash *pop3_mem;   //存放smtp包的Smtp_head 包，以区分是否是同一个连接

/**
 * \brief 初始化处理post数据的一些资源
 */
u_int32 init_pop3handle();

/**
 * \brief 对post申请的资源的释放
 */
 void final_pop3handle();

/**
 * \brief 判断是否是一封邮件的第一个包
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \return 是POP2 mail 包头，返回true， 否则返回false
 */
u_int32 isMailHead(const char* buf,const u_int16 buflen);

/**
 * \brief 判断是否是邮件结尾(一封邮件发送完毕)
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \return 是POP3 MAIL包尾，返回true， 否则返回false
 */
u_int32 isMailEnd(const char* buf,const u_int16 buflen);

/**
 * \brief 处理POP3包
 * \param buf pop3数据包
 * \param buflen pop3数据包长度
 */
void pop3_handle(const char *buf, u_int32 buflen);

#endif