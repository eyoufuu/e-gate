/**
 * \brief mysql�������ð汾Ϊ�򵥴������𽥸Ľ�
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
	const char *name;  //�ֶ���
	int type;		//ZEBRA��������
	unsigned int size;   // ���ݴ�С
}dbCol;
#endif

extern MYSQL *mysql;


/**
 * \brief ���ӵ�mysql
 * \return �ɹ�:true, ʧ��:false
 */
u_int32 mysql_connect(const char *dbname);

/**
  * \brief ִ��mysql��亯��
  * \param sql Ҫִ�е�mysql���
  * \param sqllen Ҫִ�е�sql��䳤��
  * \return �ɹ�:����0������ʧ��
  */
u_int32 execSql(const char *dbname, const char *sql, unsigned int sqllen);

/**
 * \brief �ر�mysql����
 */
void close_mysql();

/**
 * \brief ִ��MYSQL�������
 * \param sql Ҫִ�е�mysql���
 * \param sqllen Ҫִ�е�sql��䳤��
 * \return �����Զ�������������ֵ
 */
u_int32 exeInsert(const char *dbname, const char *sql, unsigned int sqllen);

MYSQL_RES *exeSelect(const char *dbname, const char *sql, unsigned int sqllen);

u_int32 mysql_escapestring(const char *dbname, char *to, const char *from, unsigned long length);

#endif

