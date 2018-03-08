#ifndef _MREADSQL_H
#define _READSQL_H

#include <stdio.h>
#include <stdlib.h>
#include "mysql/mysql.h"
#include "global.h"
#include "account.h"
#include "filetype.h"
#include "emp.h"
#include "policy.h"
#include "global.h"
#include "imp2.h"

/*
#define SQL_SERVER "192.168.1.81"
#define SQL_NAME_BASE "baseconfig"
#define SQL_NAME_AUDIT "audit"
#define SQL_USER "system"
#define SQL_PWD  "123456"
*/

#define SQL_SERVER "localhost"
#define SQL_NAME_BASE "baseconfig"
#define SQL_NAME_AUDIT "audit"
#define SQL_USER "root"
#define SQL_PWD  "123456"


#define TABLE_DEFINE_CMD_CREATE "CREATE TABLE IF NOT EXISTS "

#define TABLE_DEFINE_PRO_CONTENT "(id int(4) unsigned NOT NULL AUTO_INCREMENT,\
logtime int(4) unsigned default 0,\
account_id int(4) unsigned DEFAULT 0, \
ip_inner int(4) unsigned default 0,\
pro_id int(4) unsigned default 0,\
upflow int(4) unsigned default 0,\
downflow int(4) unsigned default 0,\
packets_passed_num  int(4) unsigned default 0,\
packets_blocked_num int(4) unsigned default 0,\
PRIMARY KEY (id) \
)"       

#define TABLE_DEFINE_CMD_INSERT "insert into"
#define TABLE_DEFINE_PRO_LOG "(logtime,account_id,ip_inner,pro_id,upflow,downflow,packets_passed_num,packets_blocked_num)"

#define TABLE_DEFINE_PRO_INS "(logtime,ip,up,down,proid,protype)"
#endif
