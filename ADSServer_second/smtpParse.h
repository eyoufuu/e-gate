/**
 *\ brief 处理SMTP数据包的文件
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

#define SMTP_MEM_CONN_SIZE 8   //保存POST连接信息的BDB大小，单位为M
extern mem_hash *smtp_mem;
/**
 * \brief SMTP包头的结构体
 */
typedef struct _SmtpInfo
{
	char s_mailaddr[100];		//发件人邮箱地址
	char d_mailaddr[1024];	//收件人邮箱地址，可能有多个
       u_int32   id; 				//保存将包头信息插入到数据库后返回的ID，用来关联存放包内容的表
	u_int32 status;			//包状态，指明此时发送到哪个包，1表示收到了MAIL FROM包，2表示收到RCTP包
	u_int32 starttime;			//收到MAIL FROM包的时间，用来表明该次操作的开始时间
	
}SmtpInfo;


/**
 * \brief 初始化处理smtp数据的一些资源
 */
u_int32 init_smtphandle();

/**
 * \brief 对smtp申请的资源的释放
 */
void final_smtphandle();

/*
 * \brief 从指定SMTP字符串中查找发件人邮箱地址
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \param s_mail: 获取到的发件人邮箱地址
 * \param s_maillen: 发件人邮箱地址长度
 */
void getMailFrom(const char* buf,const u_int16 buflen,char** s_mail,u_int16* s_maillen);

/*
 * \brief 从指定SMTP字符串中查找收件人邮箱地址
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \param d_mail: 获取到的收件人邮箱地址
 * \param d_maillen: 收件人邮箱地址长度
 */
void getMailTo(const char* buf,const u_int16 buflen,char** d_mail,u_int16* d_maillen);

/**
 *\brief 判断该包是否是SMTP 的DATA包
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \return 是DATA包，返回true， 否则返回false
 */
u_int32 isTheDATAPack(const char* buf,const u_int16 buflen);


/**
 *\brief 判断该包是否是SMTP 的QUIT包
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \return 是QUIT包，返回true， 否则返回false
 */
u_int32 isSMTPQUITPack(const char* buf,const u_int16 buflen);


/**
 * \brief 初始化存放SMTP包头的结构体，是在收到MAIL FROM包的时候进行
 * \param pSmtpInfo: 需要初始化的SMTP包头的结构体
 * \param s_mail: 发件人邮箱地址
 * \param s_maillen: 发件人邮箱地址的长度
 */
void Initialize_SmtpInfo(SmtpInfo* pSmtpInfo, char *s_mail, u_int16 s_maillen);


/**
 * \brief 处理SMTP包
 * \param buf smtp数据包
 * \param buflen smtp数据包长度
 */
void smtp_handle(const char *buf, u_int32 buflen);

#endif