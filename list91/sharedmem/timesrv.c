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

typedef unsigned int u32;
typedef unsigned short u16;
typedef unsigned char u8;

typedef struct _TTime{
        u32 curtime;
        struct tm curdate;
}TTime;

TTime* g_ptime;

#define BUF_SIZE sizeof(TTime)
#define TIME_COUNT 3
#define SHMID_PATH "/sharememid"

int create_sharedmem()
{
        return shmget(IPC_PRIVATE,BUF_SIZE,0666);               
}
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
int main(void) 
{	
	int shm_id = -1;
	read_sharedmemid(&shm_id);
	if(shm_id>0)
	{
		if(shmctl(shm_id,IPC_RMID,NULL)<0)
		{
			perror("fail to shmctl\n");
		}
	}
        shm_id = create_sharedmem();
        if(shm_id<0)
        {  //���������ڴ�
                perror("shmget\n");
                exit(1);
        }
  //    printf("successfully created segment:%d \n",shm_id );
        write_sharedmemid(shm_id);
        
        g_ptime = (TTime*)shmat(shm_id,0,0);
        if(((char*)g_ptime)<(char *)0)
        {
                perror("shmat\n");
                exit (1);
        }
 //       printf("segment attached at %p\n",(char*)g_ptime);   /*��������λ��*/
	struct tm* ptm;
        while(1)
        {
                sleep(TIME_COUNT);
		g_ptime->curtime = time(NULL);
		ptm = (struct tm*)localtime((time_t*)(&g_ptime->curtime));
		memcpy(&(g_ptime->curdate),ptm,sizeof(struct tm));
		printf("time=%u hour=%d,min=%d\n",g_ptime->curtime,g_ptime->curdate.tm_hour,g_ptime->curdate.tm_min);                
        }
	if((shmdt((char*)g_ptime))<0)
        {   /*�뵼��Ĺ����ڴ�η���*/
                printf("shmdt\n");
        }
}


