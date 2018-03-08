/**
 * \brief 处理post数据包
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
	unsigned int type;    //post类型，分为MAIL和发帖
	unsigned int ack;		//连接的ACK
	unsigned int time;		//发包的时间，为第一个包的时间
	unsigned int post_stat;	  //post状态
	
}Post_head;

#define POST_MEM_CONN_SIZE 10   //保存POST连接信息的BDB大小，单位为M
#define POST_MEM_HOST_SIZE 4	//保存需要审计的POST HOST的信息，单位为M

/*
 * \brief POST包的类型
 */
enum
{
	post_mail = 1,	//POST MAIL
	post_post = 2	//POST 发帖
};

/*
 * \brief POST包的状态
 */
enum
{
	can_anayle = 0,     //host在数据库表中，表示该包可以解析
	anayled	=	1,		//该包已经解析过
	can_not_anayle = 2  //该包不能被解析
};

extern mem_hash *post_mem;   //存放post包的Post_head 包，以区分是否是同一个连接
//extern mem_hash *post_host;	//保存发帖的时候的host名字，只对我们目前支持的host进行审计，其它的不记入数据库

/**
 * \brief 初始化处理post数据的一些资源
 */
u_int32 init_posthandle();

/**
 * \brief 对post申请的资源的释放
 */
 void final_posthandle();

/*
 * \brief 从指定POST字符串中查找HOST
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \param hostname: 获取到的HOST地址
 * \param s_hostlen: HOST长度
 */
void getHostFrom(const char* buf,const u_int16 buflen,char** hostname,u_int16* s_hostlen);


/**
 *\brief 判断该包是否是post mail包
 * \param buf: 源字符串
 * \param buflen:源字符串长度
 * \return 是POST MAIL包，返回true， 否则返回false
 */
u_int32 isPostmailPack(const char* buf,const u_int16 buflen);

 /**
 * \brief 处理POST包
 * \param buf POST数据包
 * \param buflen POST数据包长度
 */
void post_handle(const char *buf, u_int32 buflen);

 #endif
