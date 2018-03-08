#include "pro_analyse_http.h"
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
//#include <pcre.h> 
#include <sys/time.h>

				  

//多行匹配
//char            pattern   [] = "(?s)Host(.*)\r\n";
//单行匹配
//static char pattern_1[] = "Host(.*)\r\n"; 
//static char pattern_2[] = "Content-Length:(.*)\r\n";
//static pcre            *re; 


#if 0
const struct module_register __dllregister[]={
       {"analyse_http_80",2,0,IP_TCP,DIR_IN,80},
	{"analyse_http_80",2,0,IP_TCP,DIR_IN,3128},
	{"analyse_http_80",2,0,IP_TCP,DIR_IN,8080},
       {"analyse_http_ip",FUNC_IP,0,IP_TCP,DIR_IN,80},
       {"analyse_http_ip_group",FUNC_IP_GROUP,0,IP_TCP,DIR_IN,80}
};

static int ini_dll(void* param)
{
#if 0
        const char      *error;
        int             erroffset;
        int             ovector[OVECCOUNT];
	 re = pcre_compile(pattern_2, 0,/*PCRE_DOTALL*/, &error, &erroffset, NULL);
    	if (re == NULL) 
	{
                printf("PCRE compilation failed at offset %d: %s\n", erroffset, error);
	}
#endif
        return 1;
}

int register_mod(struct module_register** register_)

{
	ini_dll(NULL);
	*register_ = (struct module_register *)GET_POINT;
	
	return RET_NUMBER;
}

void unregister_mod(struct module_register* register_)
{
	//如果需要释放则写在下面
}


#endif


enum 
{
//为了提高效率，我们已经把值计算出来了
//实际上就是以下几个值的整形计算
//static const char* S_HTTP ="HTTP";
//static const char* S_GET   = "GET ";
//static const char* S_POST = "POST";
//static const char* S_HOST = "Host";
	HTTP_VALUE = 1347703880,
	HTTP_GET_VALUE = 542393671,
	HTTP_POST_VALUE  = 1414745936,
	HTTP_Host_VALUE = 1953722184
};





//////////////////////////////////////////////////////////////////////



//必须确保m>0

char *qsearch_1(const char* text,int n,const char* patt,int m)
{
	char *start = (char*)text;
	int move = 0;
	int i = 0;

	if(text==NULL)
		return NULL;
	
	while(move<n)
	{
		start = (char*)text+move;
		if(*(start)==(*patt))
		{
			i = 0;
			for(;i<m-1;i++)
			{
				if(*(start+i)!=*(patt+i))
					break;
			}
			if(i==m-1)
			{
				return start;				
			}
		}
		move++;
	}
	return NULL;
}

inline int get_host_len(const char * hoststart)
{
	//www.sina.com.cn
	char *test = (char*)hoststart + 6;
	int hlen=0;
	while(*test++!='\r')
	{
		hlen++;
		if(hlen>40)
			return 0;
		
	}
	return hlen;
}

inline int get_content_length(const char *payload,int plen,char **pos)
{
	char * res = NULL;
	int i = 0;
    res = qsearch_1(payload, plen,"Content-Length: ",16);
    if(res == NULL)
	   	return -1;
	*pos = res + 16;
    
	return strtoul(*pos,NULL,10);
}
inline int get_host(const char *payload, int plen,char ** host, int* len)
{
	char * res =NULL;
	res = qsearch_1(payload,plen,"Host: ",6);
	if(res==NULL)
		return -1;
	*host = res+6;
	*len = get_host_len(res);
	return 0;
}

//media after 0d 0a 0d 0a
inline int get_media(const char * start, int plen, char ** pos)
{
	char *res = NULL;
    res = qsearch_1(start,plen,"\r\n\r\n",4);
	if(res == NULL)
		return -1;
	*pos = res+4;//point to media start
	return 0;
}



//////////////////////////////////////////////////////////////////////
inline unsigned int analyse_post(unsigned char dir,
					     unsigned char *payload,
					     unsigned int   plen)
{
	//
	// sandai.net:80 
	//先看host
	//POST / HTTP/1.1\r\n
	//Host: xxxxxx\r\n
	//xunlei 
	////Host: hub5btmain.sandai.net:80\r\n
	//emule
	////Host: vagaa.com\r\n

	int i = 0;
	char * host_pos = NULL;
	int host_len;
	char * content_len_pos = NULL;
	int content_length = 0;

	char * media_pos = NULL;
	char * ret = NULL;
   	 int tmp = 0;
	//得到host的起始位置，得到host的长度
    if(get_host(payload, plen, &host_pos, &host_len)==0)
	{
		if(get_u32(payload, 17)==HTTP_Host_VALUE)
		{
			if(qsearch_1(host_pos, host_len,"sandai.",7)!=NULL)
				return PRO_XUNLEI;
			if(qsearch_1(host_pos, host_len, "vagaa.c", 7)!=NULL)
				return PRO_EMULE;            
		}
        if(/**(host_pos+host_len-3)==0x3a && */*(host_pos+host_len-2)==0x38 &&  *(host_pos+host_len-1)==0x30)//:80added by fuyou
        {
            if(*(payload+5) == 0x2f && *(payload+6) == 0x20)
            {
                return PRO_XUNLEI;
            }
        }
  

        if(qsearch_1(host_pos, host_len,".360.",5)!=NULL)
        {
            return PRO_SAFE360_UPDATE;
        }
	if(qsearch_1(host_pos, host_len,".wps.",5)!=NULL)//added by fuyou  for wps
	{
		 return PRO_SAFE360_UPDATE;
	}
		tmp = host_pos-(char*)payload+host_len;
		content_length = get_content_length(host_pos,plen-tmp,&content_len_pos);

		tmp = content_len_pos - (char*)payload+3;
		if(content_length > 0)
		{
			//迅雷的mediatype 第9 字节开始的四字节数字等于content-length长度
			//\r\n\r\n 01 00 00 00 01 00 00 00 60 00 00 00
			if(get_media(content_len_pos,plen-tmp,&media_pos)==0)
			{
				if(get_u32(media_pos,8) ==content_length)
					return PRO_XUNLEI;
			}
		}
	}

	if(plen > 14)
	{
       	 if(memcmp((payload+5),"/p2p/index.html",15) == 0)//added by fuyou 20100711
        	{
            		return PRO_XUNLEI;
        	}
		if(memcmp(payload+16,"ctl=upload&action=",18)==0)
			return 116;
		//if(memcmp(payload+16,"ctl=upload&action=temp_upload",29)==0)
		//	return 116;
		if( (*(payload+5) == 0x2F) && (*(payload+6) == 0x64) && (*(payload+7) == 0x61)
							&& (*(payload+8) == 0x74) && (*(payload+9) == 0x61) && (*(payload+10) == 0x3F) && (*(payload+11) == 0x73) 
							&& (*(payload+12) == 0x69) && (*(payload+13) == 0x64) && (*(payload+14) == 0x3D))
			return PRO_ICQ;
	}
	if(plen>=124)
	{
	//这个要验证，好像不对
	// /gateway/ga
		if((*(payload+5) == 0x2F) && (*(payload+6) == 0x67) && (*(payload+7) == 0x61)
							&& (*(payload+8) == 0x74) && (*(payload+9) == 0x65) && (*(payload+10) == 0x77) && (*(payload+11) == 0x61) 
							&& (*(payload+12) == 0x79) && (*(payload+13) == 0x2F) && (*(payload+14) == 0x67) && (*(payload+15) == 0x61) 
							&& (*(payload+16) == 0x74) && (*(payload+17) == 0x65) && (*(payload+18) == 0x77) && (*(payload+19) == 0x61) 
							&& (*(payload+20) == 0x79) && (*(payload+21) == 0x2E) && (*(payload+22) == 0x64) && (*(payload+23) == 0x6C) 
							&& (*(payload+24) == 0x6C) && (*(payload+25) == 0x3F) && (memcmp((payload+100), "application/x-msnmsgrp2p", 24) == 0))
			return PRO_MSN_FILE_TRANS;  // msn传输文件
	}
	if(DIR_UP == dir)
	{
		if(plen>10)
		{// /ht/sd
			if( (*(payload+5) == 0x2F) && (*(payload+6) == 0x68) && (*(payload+7) == 0x74)
							&& (*(payload+8) == 0x2F) && (*(payload+9) == 0x73) && (*(payload+10) == 0x64))
			return PRO_FETION;    //飞信
		}
		if(plen > 22)
		{//s4.f
			if((*(payload+12) == 0x73) && (*(payload+13) == 0x34) && (*(payload+14) == 0x2E) && (*(payload+15) == 0x66) && (*(payload+16) == 0x6C) 
										&& (*(payload+17) == 0x61) && (*(payload+18) == 0x73) && (*(payload+19) == 0x68) && (*(payload+20) == 0x67)
										&& (*(payload+21) == 0x65) && (*(payload+22) == 0x74))	
				return PRO_FLASHGET;       //flashget
		}

	
	}
	return PRO_POST;
}


inline unsigned int analyse_get(unsigned char up_down,
					     unsigned char *payload,
					     unsigned int   plen)
{

	char * host_pos = NULL;
	int host_len;
	u32 cat_ret = PRO_GET;

	   if(get_host(payload, plen, &host_pos, &host_len)==0)//得到host
	   {
	   	if(qsearch_1(host_pos,host_len,"115cdn.com",10)!=NULL)
	   		return 115; //115网盘下载
	   }
	if(plen > 24)
	{
		if( (*(payload+4) == 0x68) && (*(payload+5) == 0x74) && (*(payload+6) == 0x74)&& (*(payload+7) == 0x70) && (*(payload+8) == 0x3A) 
								&& (*(payload+9) == 0x2F) && (*(payload+10) == 0x2F) && (*(payload+11) == 0x6C) && (*(payload+12) == 0x6F) && (*(payload+13) == 0x67) 
								&& (*(payload+14) == 0x69) && (*(payload+15) == 0x6E) && (*(payload+16) == 0x2E) && (*(payload+17) == 0x70) && (*(payload+18) == 0x6F) 
								&& (*(payload+19) == 0x70) && (*(payload+20) == 0x6F) && (*(payload+21) == 0x67) && (*(payload+22) == 0x61) && (*(payload+23) == 0x6D) && (*(payload+24) == 0x65))
			return PRO_163_POPO_GAME;			//网易泡泡游戏
	}
	if(plen > 13)
	{
		if( (*(payload+4) == 0x2F) && (*(payload+5) == 0x52) && (*(payload+6) == 0x6F)&& (*(payload+7) == 0x78) && (*(payload+8) == 0x4E) 
								&& (*(payload+9) == 0x65) && (*(payload+10) == 0x77) && (*(payload+11) == 0x46) && (*(payload+12) == 0x6C) && (*(payload+13) == 0x76))
			return PRO_LEIKE;    // 磊客 
	}
	if(plen > 12)
	{
		if((*(payload+10) == 0x52) && (*(payload+11) == 0x6F) && (*(payload+12) == 0x78))
			return PRO_LEIKE;   //磊客	
	}
	if(plen > 11)
	{
		if( (*(payload+4) == 0x2F) && (*(payload+5) == 0x61) && (*(payload+6) == 0x6E)&& (*(payload+7) == 0x6F) && (*(payload+8) == 0x75) 
								&& (*(payload+9) == 0x6E) && (*(payload+10) == 0x63) && (*(payload+11) == 0x65))
			return PRO_BITTORRENT;   //BITTORRENT
	}
	if(plen > 15)
	{
		if( (*(payload+4) == 0x2F) && (*(payload+5) == 0x3F) && (*(payload+6) == 0x69)&& (*(payload+7) == 0x6E) && (*(payload+8) == 0x66) 
								&& (*(payload+9) == 0x6F) && (*(payload+10) == 0x5F) && (*(payload+11) == 0x68) && (*(payload+12) == 0x61)
								&& (*(payload+13) == 0x73) && (*(payload+14) == 0x68) && (*(payload+15) == 0x3D))
			return PRO_BITTORRENT;   //BITTORRENT
	}
	if(DIR_UP == up_down)
	{
		if(plen > 41)
		{
			if((*(payload+29) == 0x73) && (*(payload+30) == 0x61) && (*(payload+31) == 0x6E) && (*(payload+32) == 0x64) && (*(payload+33) == 0x61) 
											&& (*(payload+34) == 0x69) && (*(payload+35) == 0x2E) && (*(payload+36) == 0x6E) && (*(payload+37) == 0x65)
											&& (*(payload+38) == 0x74) && (*(payload+39) == 0x2E) && (*(payload+40) == 0x38) && (*(payload+41) == 0x30))	
				return PRO_XUNLEI;       //迅雷
		}
		if(plen>22)
		{
			if((*(payload+12) == 0x73) && (*(payload+13) == 0x34) && (*(payload+14) == 0x2E) && (*(payload+15) == 0x66) && (*(payload+16) == 0x6C) 
										&& (*(payload+17) == 0x61) && (*(payload+18) == 0x73) && (*(payload+19) == 0x68) && (*(payload+20) == 0x67)
										&& (*(payload+21) == 0x65) && (*(payload+22) == 0x74))	
				return PRO_FLASHGET;       //flashget
		}
		if(plen > 63)
		{
			if((*(payload+59) == 0x48) && (*(payload+60) == 0x54) && (*(payload+61) == 0x54) && (*(payload+62) == 0x50))
			{
				if(memcmp(payload+3, " /P2/Download/", 14) == 0)
				return PRO_EMULE;
			}
		}
		if(plen > 11)
		{
			if(memcmp(payload+3, " /emule/p", 9) == 0)
				return PRO_EMULE;
		}
		if(plen>100) 
		{
			if((get_u32(payload,97) == 0x766C662E) || (get_u32(payload,98) == 0x766C662E))
				return PRO_YOUKU;
		}
		if(plen>55)
		{
			if((get_u32(payload,49) == 0x766C662E) || (get_u32(payload,49) == 0x766C682E))
				return PRO_SINA_BOKE;
		}
		if(plen>75)
		{
              		if(memmem(payload+75,plen-75,"Range: bytes",12)>0)//预测往后75字节
			  	return PRO_P2P_LARGE;
		}
		#if 0
		//这一段要重新考察
		if(plen >= 26)
		{
			if(memcmp(payload+5,"config/xaconfig.do?",19)==0)
				return PRO_MSN;
			if(memcmp(payload+5,"ppcrlcheck.srf",14)==0)
				return PRO_MSN;
			//GET /zh-cn/xml/default.xml      pplive
			if ( memcmp(payload+5,"zh-cn/xml/default.xml",21)==0 ) 
				return PRO_PPLIVE;
			/* message scrape */
			if ( memcmp(payload+5,"scrape?info_hash=",17)==0 ) 
				return PRO_BITTORRENT;
			/* message announce */
			if ( memcmp(payload+5,"announce",8)==0 ) 
				return PRO_BITTORRENT;
			if ( memcmp(payload+5,"?info_hash=",11)==0 ) 
				return PRO_BITTORRENT;
			if ( memcmp(payload+5,"data?fid=",9)==0 ) 
				return PRO_BITTORRENT;
		}
		#endif 

		        //if (memcmp(payload, "GET /", 5) == 0) //以下代码速度慢，先屏蔽
				//{
				//	end =plen-22;
				//	while (c < end) {
				//		if ( get_u16(payload,c) == __constant_htons(0x0a0d) && ((memcmp((payload+c+2), "X-Kazaa-Username: ", 18) == 0) || (memcmp((payload+c+2), "User-Agent: PeerEnabler/", 24) == 0)))
				//			return ((IPP2P_KAZAA  ) + 2);
				//		c++;
				//	}
				//}

 //     printf("the function will ret is %d\n",PRO_GET);
	return PRO_GET; //不行就是返回是get包		
	}
}

inline unsigned int analyse_http(unsigned char dir,  unsigned char *payload, unsigned int plen)
{
	char * pos = NULL;
	int content_len;
	int tmp;
	content_len = get_content_length(payload, plen, &pos);
	char * media_pos;
	if(content_len>0)
	{
		tmp = (pos- (char*)payload)+3;
		if(get_media(pos+3,plen-tmp, &media_pos)==0)
		{
			if(get_u32(media_pos,8)== content_len)
				return PRO_XUNLEI;
		}
	}
	return PRO_HTTP;
}



//方向
//包
//数据包的长度，取出ip头部和tcp头部

u32  analyse_http_80(u8 dir, u8 *payload, u32   plen)
{
/*
这里先不用判断上下行了，因为无论是上下行都要判断前面四个字节
*/
      // printf("analyse_http_80 dir:%d \n",dir);
	unsigned int flag = get_u32(payload,0);
	switch(flag)
	{
		case HTTP_GET_VALUE:
			{
			return analyse_get(dir, payload, plen);
			}
			break;
		case HTTP_POST_VALUE:
			return analyse_post(dir,  payload, plen);
			break;

		//HTTP/1.1
		case HTTP_VALUE:
			return analyse_http(dir,payload,plen);
			break;
	}
    if(*(payload)==0x00 && *(payload+2)==0x02)
	{
	      if(*(payload+plen-1)==0x03)
		  	return PRO_QQ; 
	}
       return UNTOUCHED;
	//其他情况	

}


const static struct module_register __dllregister[]={
       {"analyse_http_80",analyse_http_80,2,100,IP_TCP,DIR_IN,80},
	{"analyse_http_3128",analyse_http_80,2,100,IP_TCP,DIR_IN,3128},
	{"analyse_http_8080",analyse_http_80,2,100,IP_TCP,DIR_IN,8080}
};

const struct module_register* get_module_info_http80(int * func_num)
{
	*func_num = RET_NUMBER;
	return (const struct module_register*)GET_POINT;
}





	#if 0
		//下面一段和上面的猜解方法是一样的
		if(plen >= 32)
		{
		
			if ( memcmp(payload+7,"HTTP/1.1",8) ==0 )
			{
				if(memcmp(payload+17, "Host: vagaa.com", 15) == 0 /*&& memcmp(payload+34, "VAGAA-OPERATION: ", 17) == 0*/) 
					return PRO_EMULE;//emule
				if(plen>120)
				{
					//以下tcp协议完全正确，上海和西安测试都是如此。
					unsigned  char *t = (unsigned char*)strstr((const char*)(payload+93), "Connection: Keep-Alive"); //min length of Connection
					if (t)
					{
						t += 26;//Connection: Keep-Alive 0d 0a 0d 0a
						if ( (*t < 0x40) && (*(t+1) == 0x00) && get_u16(t,2) == 0x0000 && get_u16(t,5) == 0x0000 && (*(t+7) == 0x00) && (*(t+8) == (payload+plen-t-12)) ) 
							return PRO_XUNLEI;
					}
				}
			}
		}

//if -1 then no content-length
//if 0 then 0 有时候msn就会发这种post包，content-length 为0
//迅雷的包就是content length后字节数等于media type内容后移动过8个字节后取整形的值
inline int get_content_length(const char* text)
{
//#define CONTENT_LENGTH_NOT -1
	//text 指在了Content-Length: 上面
	char *ret    = NULL;
       char *start = NULL;
       char buffer[10];
	int i = 0;
	//ret = (char*)qsearch_1(text, n , "Content-Length: ",14);
	//if(ret==NULL)
	//	return CONTENT_LENGTH_NOT ;
	//跳过16字节 Content-Length: xxx
	ret =(char*)text+16; 
	//最多查8次  
	//因为1 MTU为1500字节2 post数据量不是很大，否则就跳过
	for(i =0;i<8;i++) 
	{
		if(*(ret+i)!='\r')
			buffer[i] = *(ret+i);
		else
		{
			buffer[i]='\0';
			break;
		}
	}
	return strtoul(buffer,NULL,10);
}



		
		#endif 

