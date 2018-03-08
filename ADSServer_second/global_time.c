#include "FileLog.h"
#include "global_time.h"

TTime *g_ptm;

int init_global_time()
{
	int id;
	int ret = ctl_readsharedmemid(&id);
	g_ptm = (TTime*)cite_sharedmem(id);
}

int ctl_readsharedmemid(int* shm_id)
{
	int fd;
	int ret = 0;
	fd = open(SHMID_PATH,O_RDONLY,S_IRUSR|S_IWUSR);
	if(fd == -1)
	{
		//WADEBUG(D_ALL)("shared time open error.\n");
		ret = 1;
	}
	if(-1==read(fd, (void*)shm_id,sizeof(int)))
	{
		//WADEBUG(D_ALL)("read sharedmemid error.\n");
		ret = 1;
	}    
	close(fd);
	return ret;
}

