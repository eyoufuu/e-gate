/**
 * \brief 封装一些操作文件的方法
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
 * \brief 封装读取获取时间使用的id
 */
int init_global_time();   

#endif