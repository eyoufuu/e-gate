#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "../globaldef.h"





/*
int ana_split(char** buf,int m,int n,char* str,const  char* de)
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
}
*/


static inline int try_number(const char *data, size_t dlen, u_int32_t array[],
		      int array_size, char sep, char term)
{
	u32 i, len;

	memset(array, 0, sizeof(array[0])*array_size);

	/* Keep data pointing at next char. */
	for (i = 0, len = 0; len < dlen && i < array_size; len++, data++) {
		if (*data >= '0' && *data <= '9') {
			array[i] = array[i]*10 + *data - '0';
		}
		else if (*data == sep)
			i++;
		else {
			/* Unexpected character; true if it's the
			   terminator and we're finished. */
			if (*data == term && i == array_size - 1)
				return len;

			return 0;
		}
	}
	return 0;
}

/* Returns 0, or length of numbers: 192,168,1,1,5,6 */
int try_rfc959(const char *data, size_t dlen,
		      unsigned int *ip, unsigned short *port, char term)
{
	int length;
	u_int32_t array[6];

	//so the baby is like this
	//const char * baby = "227 Entering Passive Mode (114,80,208,12,82,25).";
	length = try_number(data+27, dlen-27, array, 6, ',', term);
	if (length == 0)
		return 0;

	*ip =  htonl((array[0] << 24) | (array[1] << 16) |
				    (array[2] << 8) | array[3]);
	*port = htons((array[4] << 8) | array[5]);
	return length;
}



static inline u32 tcp_ftp_21_0x55(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	if((*(payload+1) == 0x53) && (*(payload+2) == 0x45) && (*(payload+3) == 0x52))		//FTP登陆
			return PRO_FTP_LOGIN;
	return PRO_FTP;
}

static inline u32 tcp_ftp_21_0x52(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
		//PETR command 
	if((*(payload+1) == 0x45) && (*(payload+2) == 0x54) && (*(payload+3) == 0x52))		//FTP文件下载
			return PRO_FTP_FILE_DOWN;
	return PRO_FTP;
}

static inline u32 tcp_ftp_21_0x53(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/)
{
	if((*(payload+1) == 0x54) && (*(payload+2) == 0x4F) && (*(payload+3) == 0x52))		//FTP上传文件
			return PRO_FTP_FILE_UP;
	return PRO_FTP;
}

static inline u32 tcp_ftp_21_0x32(unsigned char  up_down,
							  const unsigned char * payload, 
							  int plen/*payload len*/,unsigned int* ip, unsigned short * port)
{
	u32 cat_ret = 0;
	if(DIR_DOWN == up_down)
	{
	     if((*(payload+1)==0x32) && (*(payload+2)==0x37) && (*(payload+19)==0x65) )
		{
//			if((*(payload+1) == 0x32) && (*(payload+2) == 0x37) && (*(payload+3) == 0x20) && (*(payload+4) == 0x45) && (*(payload+5) == 0x6E) 
//					&& (*(payload+16) == 0x73) && (*(payload+17) == 0x69) && (*(payload+18) == 0x76) && (*(payload+19) == 0x65))		//FTP下载文件

                      if(try_rfc959(payload,plen,ip,port,')')>0)
				return PRO_FTP_FILE_DOWN;
		}
	}
	
	return PRO_FTP;
}

u32 tcp_ftp(unsigned char up_down,
	          const unsigned char * payload,
	          int plen, unsigned int * outterip, unsigned short * outterport)
{
       *outterip = 0;
	 *outterport = 0;
	switch(*payload)
	{
		case 0x55:
			return  tcp_ftp_21_0x55(up_down, payload, plen);
		case 0x52:
			return  tcp_ftp_21_0x52(up_down, payload, plen);
		case 0x53:
			return  tcp_ftp_21_0x53(up_down, payload, plen);
		case 0x32:
			return  tcp_ftp_21_0x32(up_down, payload, plen,outterip,outterport);
	}
	return PRO_FTP;

}


