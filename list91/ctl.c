/*File: ctl.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
 */
#include "ctl.h"
#include "account.h"
#include <linux/ip.h>

#include <unistd.h>
#include <fcntl.h>
#include <sys/stat.h>

#define SHMID_PATH "/sharememid"

u8 g_sendpkthead[2048];

#define HTTP_HOST 		"Host"
#define HTTP_GET		"GET"


inline u32 ctl_getsystime(struct tm** ptm, time_t* curtime)
{
	*curtime = time(NULL);
	*ptm = (struct tm*)localtime(curtime); 
	return (u32)(*curtime);
}
void ctl_readsharedmemid(int* shm_id)
{
	int fd;
	fd = open(SHMID_PATH,O_RDONLY,S_IRUSR|S_IWUSR);
	if(fd == -1)
	{	
		printf("shared time open error\n");
                exit(1);
	}
	if(-1==read(fd,(void*)shm_id,sizeof(int)))
	{
		printf("read sharedmemid error\n");
	}    
	close(fd);
}
 /*inline void ctl_getpktinfo(u32 phyindev,u32* dir,u32* innerip, u32* upflow,u32* downflow,u32 size)
{
	struct iphdr* iphdr = (struct iphdr*)user_getiphdr();
	if(phyindev == g_lanindex)
	{
		*dir = DIR_CS;
		*innerip = iphdr->saddr;
		*upflow += size;
	}
	else if(phyindev == g_wanindex)
	{
		*dir = DIR_SC;
		*innerip = iphdr->daddr;
		*downflow += size;
	}
	else
	{
		*dir = DIR_UNKNOW;
	}
 }*/
inline u32 ctl_getpktdir(u32 phyindev)
{
	if(phyindev == g_lanindex)
	{
		return DIR_CS;
	}
	else if(phyindev == g_wanindex)
	{
		return DIR_SC;
	}
	else
	{
		return DIR_UNKNOW;
	}
}

inline u32 ctl_getinnerip(u32 sip,u32 dip)
{
	if(g_pkt.dir == DIR_CS)
	{
		return sip;	
	}
	else
	{
		return dip;
	}
}

#if 0
inline int ctl_search(void *source, void *target,u32 soc_length, u32 tar_length)
{
	void *addr;
	u8 *search_address;
	search_address = (u8 *)source;
	while((addr = memchr(search_address, *((char*)target), soc_length))!=NULL)
	{
		if(memcmp(addr, target, tar_length)==0)
		{
			return (char*)addr-(char*)source;
		}		
		soc_length = soc_length - (long)((u8*)addr - (u8*)search_address)-1;
		search_address=(u8 *)((u8 *)addr+1);		
	}
	return -1;	
}
#endif

inline void ctl_setpkthead(u8* buf,u16 len,u16 cmd)
{
	SET_PKT_LEN(buf,len);
	SET_PKT_CMD(buf,cmd);
}

inline void ctl_initpkthead(u8* buf)
{
    SET_PKT_B1(buf,0);
    SET_PKT_B2(buf,1);
    SET_PKT_LEN(buf,0);
    SET_PKT_MAJ(buf,1);
    SET_PKT_MIN(buf,0);
    SET_PKT_CMD(buf,0);
}
int ctl_split(char** buf,int m,int n,char* str,const  char* de)
{
        char* pstr = str;
        
        char* p=strtok(pstr,de);
        int i=0;
        while(p!=NULL)
        {
                sprintf((char*)((char*)buf+n*i),"%s",p);
                p = strtok(NULL,de);
                i++;
        }
        return i;
/* please do not delete this code can be used later
 int i=0;
   int len = strlen(str);
if(len==0)
return 0;
   char* pstart = str;
  char* p = (char*)memchr((void*)(str),de,len);

  while(p != NULL)
  {
        *p = '\0';
        if(p-pstart>0)
        {
            sprintf((char*)((char*)buf+n*i),"%s",pstart);
            i++;
        }
        p = p+1;
        pstart = p;
        if(*p=='\0')
            break;
        len = len -(p-pstart);

        p = (char*)memchr((void*)(p),de,len);
  }
  if(p==NULL)
  {
    sprintf(((char*)(buf)+n*i),"%s",pstart);
    i++;
  }
    return i;
*/
        
}
#if 0
inline void ctl_httpget(u8* buf,u16 buflen,u8** get, u16* getlen)
{
	u32 i =0;
	int ret = 0;
	u32 pointer;
//get hostinfo
	ret = ctl_search((void*)(buf),(void*)HTTP_GET,3,3);
	if(unlikely(ret<0))
	{
		return;
	}
	i = i+ret;
	i = i+3;//  i+3(get)

	while(*(buf+i) == ' '/*&&i<*buflen*/)
	{
		i++;
		if(unlikely(i>=buflen))
		{
			return;
		}
	}

	pointer = i;
	*get =(u8*)( buf + pointer);
	ret = ctl_search((void*)(buf+i),(void*)" HTTP/",buflen-i,6);//" HTTP/" 6BYTE
	if(unlikely(ret<0))
	{
		return;
	}
	i = i+ret;
	*getlen = i-pointer;	
}
inline void ctl_httphost(u8* buf,u16 buflen,u8** host,u16* hostlen)
{
	u32 i =0;
	int ret = 0;
	u32 pointer;
	u32 len = buflen; 
//get hostinfo
	ret = ctl_search((void*)(buf),(void*)HTTP_HOST,len,4);
	if(unlikely(ret<0))
	{
		return;
	}
	i = i+ret;
	i = i+5;//  i+4(host)	i++;(':')

	while(*(buf+i) == ' '/*&&i<*buflen*/)
	{
		i++;
		if(unlikely(i>=len))
		{
			return;
		}
	}
/*	if(i>=len)
	{
		return;
	}*/
	pointer = i;
	*host =(u8*)( buf + pointer);
	ret = ctl_search((void*)(buf+i),(void*)"\r\n",len-i,2);
	if(unlikely(ret<0))
	{
		return;
	}
	i = i+ret;
	*hostlen = i-pointer;

//	char urlaa[1024];
//	memcpy(urlaa,buf+pointer,*hostlen);
//	urlaa[*hostlen] = '\0';
//	printf("urlaa=%s\n",urlaa);
}

#endif
