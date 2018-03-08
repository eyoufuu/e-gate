/**
 * \brief 定义一个域协议的客户端
 * \author zhengjianfang
 * \2009-9-12
 */

#ifndef _DPCLIENT_H_
#define _DPCLIENT_H_

#include <stdio.h>
#include "globDefine.h"

#define UNIX_SERPATH "/serv"
#define UNIX_CLIPATH "/client"


/**
 * \brief 初始化域协议客户端
 * \return 成功:true 失败:false
 */
u_int32 dpclientInit();

/**
 * \brief 发送数据
 * \param buf 所要发送的数据包
 * \param buflen 所要发送的数据包长度
 * \return 发送的数据包长度，-1为发送失败
 */
u_int32 sendCmd(const void *buf, const u_int32 buflen);

/**
 * \brief 接收数据
 * \param buf 存放接收到的数据
 * \param buflen 用来存放接收到的数据的空间大小
 * \return 接收到的包长度,-1为失败
 */
u_int32 recvCmd(char *buf, const u_int32 buflen);

/**
 * \brief 关闭客户端连接
 */
void dpclient_close();


#endif