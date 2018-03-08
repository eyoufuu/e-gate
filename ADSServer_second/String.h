/**
 * \brief �������л������ַ�������
 * \ author zhengjianfang
 */


#ifndef _STRING_HANDLE_H_
#define _STRING_HANDLE_H_


#include <string.h>
#include "globDefine.h"


/**
 * \brief    ��ָ���ַ����в���ָ�������ַ���
 * \param source: Դ�ַ���
 * \param target: ��Ҫ��Դ�ַ����в��ҵ����ַ���
 * \param soc_length :Դ�ַ����ĳ���
 * \param tar_length: ���ַ����ĳ���
 * \return :���ҵ������ַ�����Դ�ַ����е�λ��(���ַ�����һ���ַ���λ��),ʧ�ܷ���-1
 */
int  strSearch(void *source, void *target, unsigned short soc_length, unsigned short tar_length);

/**
 * \brief ƥ���ַ�������󼸸��ַ��Ƿ��Ŀ���ַ���һ��
 * \param source: Դ�ַ���
 * \param target: ��Ҫ��Դ�ַ����в��ҵ����ַ���
 * \param soc_length :Դ�ַ����ĳ���
 * \param tar_length: ���ַ����ĳ���
 * \return ���ҵ������ַ�����Դ�ַ����е�λ��(���ַ�����һ���ַ���λ��),ʧ�ܷ���-1
 */
int strSearchEnd(void *source, void *target, unsigned short soc_length, unsigned short tar_length);

#endif