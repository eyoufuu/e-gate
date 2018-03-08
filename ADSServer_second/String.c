/**
 * \brief 用来进行基本的字符串处理
 * \ author zhengjianfang
 */

#include "String.h"

int  strSearch(void *source, void *target, unsigned short soc_length, unsigned short tar_length)
{
	void *addr;
	unsigned char *search_address;
	search_address = (unsigned char *)source;
	while((addr = memchr(search_address, *((char*)target), soc_length))!=NULL)
	{
		if(memcmp(addr, target, tar_length)==0)
		{
			return (char*)addr-(char*)source;
		}
		
		soc_length = soc_length - (long)((unsigned char *)addr - (unsigned char  *)search_address)-1;
		search_address=(unsigned char *)((unsigned char *)addr+1);
		
	}
	return -1;	
}

int strSearchEnd(void *source, void *target, unsigned short soc_length, unsigned short tar_length)
{
	void *addr;
	unsigned char *search_address;
	search_address = (unsigned char *)source;
	search_address += (soc_length - tar_length);
	if(memcmp(search_address, target, tar_length)==0)
	{
		return (char*)search_address -(char*)source;
	}
	return -1;
}