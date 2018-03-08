#include <sys/types.h>
#include <sys/ipc.h>
#include <sys/shm.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <time.h>
#include <unistd.h>
#include <fcntl.h>
#include <sys/stat.h>
#include "sharedmem.h"
typedef unsigned int u32;
typedef unsigned short u16;
typedef unsigned char u8;
/*
typedef struct _TTime{
        u32 curtime;
        struct tm curdate;
}TTime;
*/
TTime* g_ptime;

#define TIME_COUNT 3
#define SHMID_PATH "/sharememid"
//extern "C"
//{
#include "sharedmem.h"
//}
void write_sharedmemid(int shm_id)
{
	int fd;
	fd = open(SHMID_PATH,O_WRONLY|O_CREAT|O_TRUNC,S_IRUSR|S_IWUSR);
	if(fd == -1)
	{	
		printf("open systemlog error");
		return;
	}
	if(-1==write(fd,(void*)&shm_id,4))
	{
		printf("record sharedmemid error\n");
	}	
	close(fd);
}
void read_sharedmemid(int* shm_id)
{
	int fd;
	fd = open(SHMID_PATH,O_RDONLY,S_IRUSR|S_IWUSR);
	if(fd == -1)
	{	
		printf("open error\n");
		return;
	}
	if(-1==read(fd,(void*)shm_id,sizeof(int)))
	{
		printf("read sharedmemid error\n");
	}    
	close(fd);
}
int main(int argc, char **argv) 
{	
	int ch;
	while((ch = getopt(argc,argv,"d"))!= -1)
	switch(ch)
	{
		case 'd':
			daemon(1,0);
			break;
		default:
			break;
	}
	int shm_id = -1;	
        shm_id = create_sharedmem(sizeof(TTime));
        if(shm_id<0)
        {  //ŽŽœš¹²ÏíÄÚŽæ
                perror("shmget\n");
                exit(1);
        }
        write_sharedmemid(shm_id);        
	g_ptime = (TTime*)cite_sharedmem(shm_id);

	struct tm* ptm;
        while(1)
        {
                sleep(TIME_COUNT);
		g_ptime->curtime = time(NULL);
		ptm = (struct tm*)localtime((time_t*)(&g_ptime->curtime));
		memcpy(&(g_ptime->curdate),ptm,sizeof(struct tm));
//		printf("time=%u hour=%d,min=%d\n",g_ptime->curtime,g_ptime->curdate.tm_hour,g_ptime->curdate.tm_min);                
        }
	uncite_sharedmem((char*)g_ptime);
	destroy_sharedmem(shm_id);
}


