#include <sys/types.h>
#include <sys/ipc.h>
#include <sys/shm.h>
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <fcntl.h>
#include <sys/stat.h>
#include "sharedmem.h"
int create_sharedmem(unsigned int bufsize)
{
        int id = shmget(IPC_PRIVATE,bufsize,0666);
	if(id<0)
        {  //创建共享内存
                perror("fail to create\n");
               return -1;
        }
	return id;               
}
void destroy_sharedmem(int id)
{
	if(id>0)
	{
		if(shmctl(id,IPC_RMID,NULL)<0)
		{
			perror("fail to destroy\n");
		}
	}
}
char* cite_sharedmem(int id)
{
	char* pmem = shmat(id,0,0);
        if(pmem<(char*)0)
        {
                perror("failt to get pointer\n");
		pmem = NULL;
        }
	return pmem;
}
void uncite_sharedmem(char* buf)
{
	if(shmdt(buf)<0)
        {   //与导入的共享内存段分离
                printf("shmdt\n");
        }
}


