/**
 * \brief 定义日志文件,定义日志级别及写文件日志函数等
 * \author zhengjianfang
 * \date 2009-09-18
 */

 #ifndef _FILE_LOG_H_
 #define _FILE_LOG_H_


extern int g_debug_level;
extern char servername[32];
extern char logfilepath[64];

#define WADEBUG(message_level)   if (g_debug_level >= message_level) printf
//#define WADEBUG(message_level)  if (g_debug_level >= message_level) write_debug_log

extern void write_debug_log(const char *fmt, ...);
///这里定义的是debug级别，用在WADEBUG(...)宏定义中，其中数字越大，debug级别越高，输出的日志信息越详细。
#define D_FATAL		0		//致命错误
#define D_WARNING	1		//警告信息，属于错误，但是有可能可以恢复
#define D_INFO		2		//程序运行状态信息，显示工作流程
#define D_DETAIL	3		//输出所有的日志，包括调试信息等等，效率最低。
#define D_ALL		100		//输出所有日志
#define D_NONE		-100	//没有日志输出

#endif