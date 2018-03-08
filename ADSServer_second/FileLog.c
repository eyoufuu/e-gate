#include "FileLog.h"
#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <stdarg.h>

int g_debug_level=D_ALL;
char servername[32] = "default";
char logfilepath[64] = "./";

void GetDate(char* date,int datelen) 
{
	time_t timenow = time(NULL);
	struct tm *local_time = NULL;
	local_time = localtime(&timenow); 
	strftime(date, datelen, "%Y-%m-%d", local_time);
}
	
void GetTime(char* time_now,int timelen) 
{ 
	time_t timenow = time(NULL);
	struct tm *local_time = NULL;
	local_time = localtime(&timenow); 
	strftime(time_now, timelen, "%H:%M:%S", local_time); 
} 

void GetFilename(char *filename, int filelen)
{
	char datetime[32] = {0};
	GetDate(datetime, sizeof(datetime));
	sprintf(filename, "%s%s_%s.log", logfilepath, servername, datetime);
}

void write_debug_log(const char *fmt, ...)
{
//	printf("%s", fmt);
	FILE *logfp;
	va_list ap;
	char filename[64] = {0};
	GetFilename(filename, sizeof(filename));
	char d[50],t[50];
	logfp=fopen(filename,"a+t");
	if(logfp==NULL)
	{
		return;
	}

	va_start(ap, fmt);
	//_strtime( t );
	//_strdate(d );
	GetDate(d,50);
	GetTime(t,50);

	fprintf(logfp,"%s %s\t", d,t);
	vfprintf(logfp,fmt,ap);
	fclose(logfp);
//	printf(fmt,va_arg(ap, char*));
	va_end(ap);
}


