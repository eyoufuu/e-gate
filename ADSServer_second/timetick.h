/**
 * \brief ϵͳά���̣߳�����ά��ϵͳ����Դ����Ϣ
 * \author zhengjianfang
 * \date 2009-09-25
 */

#include<pthread.h>

 #ifndef _TIME_TICK_H_
 #define _TIME_TICK_H_
 extern pthread_t  p_id;

void* time_tick(void* param);

 #endif