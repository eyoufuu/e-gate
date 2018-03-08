#ifdef    _64_
#define _LARGEFILE64_SOURCE
#define _FILE_OFFSET_BITS 64
#define __USE_FILE_OFFSET64
#endif
#define _64_
#include <stdio.h>
#include <string.h>
#include <mntent.h>
#include <sys/statfs.h>
#include <mntent.h>
#ifndef _PATH_MOUNTED
# define _PATH_MOUNTED "/etc/mtab"
#endif

int  main()
{
	struct    mntent    *ent;
	struct    statfs    stat;
//	struct disk_info disk;
    	FILE *fp = setmntent(_PATH_MOUNTED,"r");
	printf("%15s\t%12s\t%12s\t%12s\t%20s\n","FileSystem","All(K)","Free","Available","Mounted on");

    	while(ent = getmntent(fp))
	{
        	bzero(&stat,sizeof(struct statfs));
        	if(-1 == statfs(ent->mnt_dir,&stat))
		{
            		//perror("statfs:");
                        continue;
            	}
                unsigned long long t =(unsigned long long)stat.f_blocks*stat.f_bsize/(1024);
                unsigned long long f =(unsigned long long) stat.f_bsize*stat.f_bavail/(1024);
                if(t == 0)
                    continue;
                printf("%s\tall: %lld\t free: %lld\t%s\n",ent->mnt_fsname,t,f,ent->mnt_dir);
       }
       endmntent(fp);
}
            
