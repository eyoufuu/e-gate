/**
 * \brief ��װһЩ�����ļ��ķ���
 */

 #ifndef _FILE_DEFINE_H_
#define _FILE_DEFINE_H_

#include<sys/types.h>
#include<sys/stat.h>
#include<fcntl.h>
#include "sharedmem.h"

#define SHMID_PATH  "/sharememid"

extern TTime *g_ptm;

/**
 * \brief ��װ��ȡ��ȡʱ��ʹ�õ�id
 */
int init_global_time();   

#endif