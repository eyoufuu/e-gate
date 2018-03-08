#include <stdio.h>
#include <stdlib.h>
#include <netdb.h>
#include <sys/socket.h>
#include <string.h>
#define NULL 0
int main(int argc, char **argv)
{
 char *ptr,**pptr;
 struct hostent *hptr;
 char str[32];
 char ipaddr[16];
 struct in_addr hipaddr;
 socklen_t len  =4; 
 /* 取得命令后第一个参数，即要解析的IP地址 */
 ptr = argv[1];
 /* 调用inet_aton()，ptr就是以字符串存放的地方的指针，hipaddr是in_addr形式的地址 */
 if(!inet_aton(ptr,&hipaddr))
 {
   printf("error:1!");
   return 1;
 }
 /* 调用gethostbyaddr()。调用结果都存在hptr中 */
 printf("%u\n",hipaddr.s_addr);
 if( (hptr = gethostbyaddr((const char*)&(hipaddr.s_addr), len, AF_INET) ) == NULL )
 {
	printf("%s\n",hstrerror(h_errno));
//  printf("error:2:%d",h_errno);
  return 1; /* 如果调用gethostbyaddr发生错误，返回1 */
 }
 printf("offici:qal hostname:%s\n",hptr->h_name);
 /* 主机可能有多个别名*/
 for(pptr = hptr->h_aliases; *pptr != NULL; pptr++)
  printf("  alias:%s\n",*pptr);
 /* 根据地址类型，将地址打出来 */
 switch(hptr->h_addrtype)
 {
  case AF_INET:
  case AF_INET6:
   pptr=hptr->h_addr_list;
   for(;*pptr!=NULL;pptr++)
    printf("  address:%s\n", inet_ntop(hptr->h_addrtype, *pptr, str, sizeof(str)));
   break;
  default:
   printf("unknown address type\n");
   break;
 }
 return 0;
}