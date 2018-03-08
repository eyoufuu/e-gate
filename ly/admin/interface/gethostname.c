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
 /* ȡ��������һ����������Ҫ������IP��ַ */
 ptr = argv[1];
 /* ����inet_aton()��ptr�������ַ�����ŵĵط���ָ�룬hipaddr��in_addr��ʽ�ĵ�ַ */
 if(!inet_aton(ptr,&hipaddr))
 {
   printf("error:1!");
   return 1;
 }
 /* ����gethostbyaddr()�����ý��������hptr�� */
 printf("%u\n",hipaddr.s_addr);
 if( (hptr = gethostbyaddr((const char*)&(hipaddr.s_addr), len, AF_INET) ) == NULL )
 {
	printf("%s\n",hstrerror(h_errno));
//  printf("error:2:%d",h_errno);
  return 1; /* �������gethostbyaddr�������󣬷���1 */
 }
 printf("offici:qal hostname:%s\n",hptr->h_name);
 /* ���������ж������*/
 for(pptr = hptr->h_aliases; *pptr != NULL; pptr++)
  printf("  alias:%s\n",*pptr);
 /* ���ݵ�ַ���ͣ�����ַ����� */
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