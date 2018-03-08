/**
 * \brief 用来定义基本的数据类型等
 * \ author zhengjianfang
 */


#ifndef _GLOB_DEFINE_H
#define _GLOB_DEFINE_H

typedef  unsigned int u_int;
typedef  unsigned int u_int32;
typedef  unsigned char u_char;
typedef  unsigned short u_short;
typedef  unsigned short u_int16;
typedef unsigned long long u_int64;

#define safe_delete(p)  do{ delete p; p=NULL; } while(false)  
#define  safe_delete_array(p) do{ delete []p;p=NULL;}while(false)

#endif