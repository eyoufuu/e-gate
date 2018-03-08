/**
 * \brief 系统维护线程，用来维护系统的资源等信息
 * \author zhengjianfang
 * \date 2009-09-25
 */

#include<pthread.h>

 #ifndef _TIME_TICK_H_
 #define _TIME_TICK_H_
 extern pthread_t  p_id;

void* time_tick(void* param);

 #endif