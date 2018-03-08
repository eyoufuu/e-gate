/*File: global.c
    Copyright 2009 10 LINZ CO.,LTD
    Author(s): fuyou (a45101821@gmail.com)
 */
#include "global.h"

TTime* g_ptm = NULL;

u32 g_debuglevel = D_ALL;

u32 g_wanindex = 0;
u32 g_lanindex = 0;
TPktinfo g_pkt;
u32 g_interupt;

u32 g_isqosopen = 0;
u32 g_sysmode = SYS_IP;
u32 g_isipmacbind = 0;
u32 g_gate = 1;
u32 g_isremind = 0;

//TModule g_modules[20];

u32 g_localip = 0;
u8 g_localipstr[24]="\0";
u32 g_protype[256];

u8 g_TempBuffer[MAX_TEMP_BUFFER];

u32 g_isfiletypeopen;
u32 g_filechecklen;

//file trans out check
u8 g_ismailopen;
u8 g_isbbsopen;
u8 g_isimopen;
u8 g_isnetdiskopen;
u8 g_isftpopen;
u8 g_istftpopen;
u8 g_isarp_ipmacbind;
u8 g_isblogopen; 
//redirect g_buf;
//u8 g_redirectbuf[1024];



u8 g_filecheck[100][21];//len = g_filecheck[][0] 

//得到tcp包和udp包的偏移量
inline get_ip_pack_h_len(const unsigned char *data)
{
	return 4*(data[0] & 0x0f);
}
inline get_tcp_pack_h_len(const unsigned char *data)
{
	int ip_hl = get_ip_pack_h_len(data);
	return 4*(data[ip_hl + 12]>>4);
}
/*
int app_data_offset(const unsigned char *data,char ip_protocol)
{
  int ip_hl = get_ip_pack_h_len(data);

  if(ip_protocol == 1){
    // 12 == offset into TCP header for the header length field.
    int tcp_hl = 4*(data[ip_hl + 12]>>4);
    return ip_hl + tcp_hl;
  }
  else if(ip_protocol == 0)//udp
	  return ip_hl+8;
  else //we can not regonize the package is tcp or udp
	  return ip_hl+8;
}
*/

#define DEBUG_PATH "./logfile"

void getdate(char* date,int datelen) 
{
//	if(g_ptm != NULL)
	{
		strftime(date,datelen,"%Y-%m-%d ",&(g_ptm->curdate));
	}
}	
void gettime(char* time,int timelen) 
{ 
//	if(g_ptm != NULL)
	{
		strftime(time,timelen,"%H:%M:%S",&(g_ptm->curdate)); 
	}	
}
void write_debug_log(const char *fmt, ...)
{
	FILE *logfp;
	va_list ap;
	char d[50],t[50];
	static int count = 0;
	logfp=fopen(DEBUG_PATH,"a+t");
	if(logfp==NULL)
	{
		return;
	}

	va_start(ap, fmt);
	getdate(d,50);
	gettime(t,50);

	fprintf(logfp,"%s %s\t", d,t);
	vfprintf(logfp,fmt,ap);
	va_end(ap);
	count++;
	if(count == 2000)
	{
		count = 0;
		system("rm -f logfile");
	}
	fclose(logfp);
}



