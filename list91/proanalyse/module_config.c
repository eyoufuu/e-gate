#include <stdio.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h> 
#include <dirent.h>
#include "module_config.h"

//#define PATH_MAX 260


const char * get_exe_path()
{
	static char buf[PATH_MAX]; 
	static int havegit = 0;
	
	int rslt = 0;
	
	if(havegit==0)
	{
		rslt = readlink("/proc/self/exe", buf, PATH_MAX); 
		if ( rslt < 0 || rslt >= PATH_MAX ) 
		{ 
			return NULL; 
		}
		
       	while(rslt>=0 && buf[rslt]!='/')
       	{
       		buf[rslt] = '\0';
			rslt--;
       	}
		havegit = 1;
	}
	return buf;
}


int read_module_config_3(int(*load_m_func)(const char*))
{
	//from /modulecache  read all dll 
      DIR* dir;
      struct dirent* drt;
      char fullpath[PATH_MAX];	  
	dir = opendir("/dllcache");
	if(dir==NULL)
	{
		perror("cant not open dllcache\n");
		return -1;
	}
	
	while((drt = readdir(dir))!=NULL)
	{
		if (strcmp(drt->d_name, ".") == 0 ||strcmp(drt->d_name, "..") == 0)
       		continue;        /* ignore dot and dot-dot */
		if(drt->d_type == 8)
		{
		      sprintf(fullpath,"%s%s","/dllcache/",drt->d_name);
//			printf("load dll %s start\n",fullpath);
			load_m_func(fullpath);
//			printf("load dll %s end\n",fullpath);
		}
		
	}
//	printf("cose dir\n");
	
	closedir(dir);
	return 0;
	
}


int read_module_config_2(int (*load_m_func)(const char*))
{
#define FILE_L_MAX_C 64
	FILE* stream;
	char buffer[128];
	char filepath[PATH_MAX];
	int    flag ;
	int    i = 0;
	int value = 0;
	const char * path = get_exe_path();
	sprintf(filepath,"%s%s",path,"config_module");
	
	if((stream =fopen(filepath,"r"))==NULL)
	{
		fprintf(stderr,"no this file or error!\n");
		return ;
	}
	else
	{
		while(!feof(stream))
		{
			value = fscanf(stream,"%s %d\n",buffer,&flag);
			if(value!=2)
				continue;
			if(buffer[0]=='#')
				continue;
			i++;
			load_m_func(buffer);
			printf("%s %d\n",buffer,flag);
			
		}
	}
	fclose(stream);
	return i;

}

#if 0
static void read_module_config()
{
#define FILE_LINE_MAX_C 64
	FILE* stream;
	char buffer[FILE_LINE_MAX_C];
	char * sline;
	char c ;
	int i = 0;
       int len =0;

	
	if((stream =fopen("./config_module","r"))==NULL)
	{
		fprintf(stderr,"no this file or error!\n");
		return ;
	}
	else
	{
		while(!feof(stream))
		{
			memset(buffer,0,FILE_LINE_MAX_C);
			sline = fgets(buffer,FILE_LINE_MAX_C,stream);
			printf("%s\n",buffer);
			if(NULL!=sline)
			{
				i = 0;
				while(i<FILE_LINE_MAX_C)
				{
					c = buffer[i];
					if(c == 0x20)
					{
						i++;
						continue;
					}
					if(c=='#' ) break;
					if(c=='/' && buffer[i+1] == '/')break;
					if(c=='*') break;
					break;
				}
				//È¥³ý\n
				len = strlen(buffer);
				printf("len %d\n",len);
				buffer[len-1]='\0'; 
			       if(load_module((char*)buffer)!=-1)
			       {
			       	printf("good load file %s",sline);
			       }
			}
		}
	}
	fclose(stream);

}
#endif

