/**
 * \brief ����һ����Э��Ŀͻ���
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
 * \brief ��ʼ����Э��ͻ���
 * \return �ɹ�:true ʧ��:false
 */
u_int32 dpclientInit();

/**
 * \brief ��������
 * \param buf ��Ҫ���͵����ݰ�
 * \param buflen ��Ҫ���͵����ݰ�����
 * \return ���͵����ݰ����ȣ�-1Ϊ����ʧ��
 */
u_int32 sendCmd(const void *buf, const u_int32 buflen);

/**
 * \brief ��������
 * \param buf ��Ž��յ�������
 * \param buflen ������Ž��յ������ݵĿռ��С
 * \return ���յ��İ�����,-1Ϊʧ��
 */
u_int32 recvCmd(char *buf, const u_int32 buflen);

/**
 * \brief �رտͻ�������
 */
void dpclient_close();


#endif