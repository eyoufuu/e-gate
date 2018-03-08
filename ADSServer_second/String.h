/**
 * \brief 用来进行基本的字符串处理
 * \ author zhengjianfang
 */


#ifndef _STRING_HANDLE_H_
#define _STRING_HANDLE_H_


#include <string.h>
#include "globDefine.h"


/**
 * \brief    从指定字符串中查找指定的子字符串
 * \param source: 源字符串
 * \param target: 需要在源字符串中查找的子字符串
 * \param soc_length :源字符串的长度
 * \param tar_length: 子字符串的长度
 * \return :查找到的子字符串在源字符串中的位置(子字符串第一个字符的位置),失败返回-1
 */
int  strSearch(void *source, void *target, unsigned short soc_length, unsigned short tar_length);

/**
 * \brief 匹配字符串的最后几个字符是否和目标字符串一致
 * \param source: 源字符串
 * \param target: 需要在源字符串中查找的子字符串
 * \param soc_length :源字符串的长度
 * \param tar_length: 子字符串的长度
 * \return 查找到的子字符串在源字符串中的位置(子字符串第一个字符的位置),失败返回-1
 */
int strSearchEnd(void *source, void *target, unsigned short soc_length, unsigned short tar_length);

#endif