/*File: httpsearch.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
 */
#include "httpsearch.h"

#ifndef HTTP_HOST
#define HTTP_HOST 		"Host"
#endif//HTTP_HOST

#ifndef HTTP_GET
#define HTTP_GET		"GET"
#endif//HTTP_GET

#ifndef HTTP_POST
#define HTTP_POST		"POST"
#endif//HTTP_POST

#ifndef likely
#define likely(x) __builtin_expect(!!(x),1)
#endif//lilely(x)

#ifndef unlikely
#define unlikely(x) __builtin_expect(!!(x),0)
#endif//unlikely(x)


inline int http_search(void *source, void *target,u32 soc_length, u32 tar_length)
{
	void *addr;
	u8 *search_address;
	search_address = (u8 *)source;
	while((addr = memchr(search_address, *((char*)target), soc_length))!=0)
	{
		if(memcmp(addr, target, tar_length)==0)
		{
			return (char*)addr-(char*)source;
		}		
		soc_length = soc_length - (long)((u8*)addr - (u8*)search_address)-1;
		search_address=(u8 *)((u8 *)addr+1);		
	}
	return 0;	
}
inline void http_post(u8* buf,u16 buflen,u8** post, u16* postlen)
{
    u32 i =5;//  i+4(post )
	int ret = 0;
//	u32 pointer;
    
    if(buflen<200)//"post  http/1.1"
    {
        *postlen = 0;
        return;
    }
//    if((*(char*)buf)!='G')
 //       return;
//    if((*(char*)(buf+1)) !='E')
//        return;
//    if((*(char*)(buf+2)) !='T')
//       return;
    if((*(u32*)buf)!=(*(u32*)("POST")))
    {
        *postlen = 0;
        return;
    }
//    i = 4;//  i+4(get )
#if 0     
//get hostinfo
	ret = http_search((void*)(buf),(void*)HTTP_GET,3,3);
	if(unlikely(ret<0))
	{
		return;
	}
//	i = i+ret;//no need this line because get at the beginning
	i = i+4;//  i+4(get )
#endif
#if 0
	while(*(buf+i) == ' '/*&&i<*buflen*/)
	{
		i++;
		if(unlikely(i>=buflen))
		{
			return;
		}
	}
#endif
    
//	pointer = i;
	*post =(u8*)( buf + i);
	ret = http_search((void*)(buf+i),(void*)" HTTP/",buflen-i,6);//" HTTP/" 6BYTE
	*postlen = ret;
/*	if(unlikely(ret<0))
	{
		return;
	}
	i = i+ret;
	*getlen = i-pointer;	*/
/*	u32 i =0;
	int ret = 0;
	u32 pointer;
    *postlen = 0;
    if(buflen<200)//"post  http/1.1"
        return;
//get hostinfo
	ret = http_search((void*)(buf),(void*)HTTP_POST,4,4);
	if(unlikely(ret<0))
	{
		return;
	}
//	i = i+ret;
	i = i+5;//  i+3(post);(' ')
#if 0
	while(*(buf+i) == ' ')
	{
		i++;
		if(unlikely(i>=buflen))
		{
			return;
		}
	}
#endif
	pointer = i;
	*post =(u8*)( buf + pointer);
	ret = http_search((void*)(buf+i),(void*)"HTTP/",buflen-i,5);//"HTTP/" 5BYTE
	if(unlikely(ret<0))
	{
		return;
	}
	i = i+ret-1;//" HTTP/" -SPACE
	*postlen = i-pointer;	*/
	
}
inline void http_get(u8* buf,u16 buflen,u8** get, u16* getlen)
{
	u32 i =4;//  i+4(get )
	int ret = 0;
//	u32 pointer;
    
    if(buflen<200)//"get  http/1.1"
    {
        *getlen = 0;
        return;
    }
//    if((*(char*)buf)!='G')
 //       return;
//    if((*(char*)(buf+1)) !='E')
//        return;
//    if((*(char*)(buf+2)) !='T')
//       return;
    if((*(u32*)buf)!=(*(u32*)("GET ")))
    {
        *getlen = 0;
        return;
    }
//    i = 4;//  i+4(get )
#if 0     
//get hostinfo
	ret = http_search((void*)(buf),(void*)HTTP_GET,3,3);
	if(unlikely(ret<0))
	{
		return;
	}
//	i = i+ret;//no need this line because get at the beginning
	i = i+4;//  i+4(get )
#endif
#if 0
	while(*(buf+i) == ' '/*&&i<*buflen*/)
	{
		i++;
		if(unlikely(i>=buflen))
		{
			return;
		}
	}
#endif
    
//	pointer = i;
	*get =(u8*)( buf + i);
	ret = http_search((void*)(buf+i),(void*)" HTTP/",buflen-i,6);//" HTTP/" 6BYTE
	*getlen = ret;
/*	if(unlikely(ret<0))
	{
		return;
	}
	i = i+ret;
	*getlen = i-pointer;	*/
}
inline void http_host(u8* buf,u16 buflen,u8** host,u16* hostlen)
{
	u32 i =6;//  i+4(host)	i++;(':')(' ')
	int ret = 0;
//	u32 pointer;
    
    if(buflen<15)//"host: \r\n"
    {
        *hostlen = 0;
        return;
    }
//get hostinfo
	ret = http_search((void*)(buf),(void*)HTTP_HOST,buflen,4);
	if(unlikely(ret==0))
	{
        *hostlen = 0;
		return;
	}
	i = i+ret;
//	i = i+6;//  i+4(host)	i++;(':')(' ') init val=6
#if 0
	while(*(buf+i) == ' '/*&&i<*buflen*/)
	{
		i++;
		if(unlikely(i>=len))
		{
			return;
		}
	}
#endif
	if(unlikely(i>=buflen))
	{
        *hostlen = 0;
		return;
	}	
//    printf("check %d %d\n",buflen,i);
//	pointer = i;
	*host =(u8*)( buf + i);
	ret = http_search((void*)(buf+i),(void*)"\r\n",buflen-i,2);
    *hostlen = ret;
/*	if(unlikely(ret<0))
	{
		return;
	}
	i = i+ret;
	*hostlen = i-pointer;*/
}