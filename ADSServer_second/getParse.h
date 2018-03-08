/**
 * \brief 处理get数据包
 * \author zhengjianfang
 * \date 2009-09-12
 */
 #ifndef _GET_PARSE_H_
 #define _GET_PARSE_H_

#include "globDefine.h"

 /**
 * \brief 处理POST包
 * \param buf POST数据包
 * \param buflen POST数据包长度
 */
void get_handle(const char *buf, u_int32 buflen);

 #endif
