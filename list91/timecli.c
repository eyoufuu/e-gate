#include <sys/types.h>
#include <sys/ipc.h>
#include <sys/shm.h>
#include <stdlib.h>
#include <stdio.h>

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

#define SHMID_PATH "./sharememid"
#define TIME_COUNT 3
void read_sharedmemid(int* shm_id)
{
	int fd;
	fd = open(SHMID_PATH,O_RDONLY,S_IRUSR|S_IWUSR);
	if(fd == -1)
	{	
		printf("open  error\n");
		return;
	}
	if(-1==read(fd,(void*)shm_id,sizeof(int)))
	{
		printf("read sharedmemid error\n");
	}    
	close(fd);
}
int main ( int argc, char *argv[] )
{
        int shm_id ;
	TTime* ptime = NULL;
        read_sharedmemid(&(shm_id));
        if(shm_id<=0)
        {
                perror("read error\n");
                exit (1);
        }
        /*���빲���ڴ�Σ����ں�ѡ��Ҫ�����λ��*/
        if((ptime = (TTime*)shmat(shm_id,0,0))<0)
        {
                perror("shmat" );
                exit (1);
        }        
//        printf(" segment attached at %p\n",shm_buf); /*��������λ��*/
//        system("ipcs -m");
	while(1)
	{
        	sleep(TIME_COUNT);/* ���� */
		printf("time=%u hour=%d,min=%d\n",ptime->curtime,ptime->curdata.tm_hour,ptime->curdata.tm_min);
	}
        if((shmdt((char*)ptime))<0)
        {   /*�뵼��Ĺ����ڴ�η���*/
                perror("shmdt\n");
        }
	return 0;
}


