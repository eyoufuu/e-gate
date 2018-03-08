/**
 * \brief mysql操作，该版本为简单处理，会逐渐改进
 * \author zhengjianfang
 * \date: 2009-09-12
 */

#ifndef	__MYSQLHANDLE_H_
#define	__MYSQLHANDLE_H_

#include <string.h>
#include <mysql.h> 
#include "globDefine.h"
#if 0
typedef struct
{
	const char *name;  //字段名
	int type;		//ZEBRA数据类型
	unsigned int size;   // 数据大小
}dbCol;
#endif

extern MYSQL *mysql;


/**
 * \brief 连接到mysql
 * \return 成功:true, 失败:false
 */
u_int32 mysql_connect(const char *dbname);

/**
  * \brief 执行mysql语句函数
  * \param sql 要执行的mysql语句
  * \param sqllen 要执行的sql语句长度
  * \return 成功:返回0，否则失败
  */
u_int32 execSql(const char *dbname, const char *sql, unsigned int sqllen);

/**
 * \brief 关闭mysql连接
 */
void close_mysql();

/**
 * \brief 执行MYSQL插入操作
 * \param sql 要执行的mysql语句
 * \param sqllen 要执行的sql语句长度
 * \return 返回自动增长的主键的值
 */
u_int32 exeInsert(const char *dbname, const char *sql, unsigned int sqllen);

MYSQL_RES *exeSelect(const char *dbname, const char *sql, unsigned int sqllen);

u_int32 mysql_escapestring(const char *dbname, char *to, const char *from, unsigned long length);

#endif

