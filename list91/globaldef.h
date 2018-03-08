#ifndef __GLOBAL_DEF_H__
#define __GLOBAL_DEF_H__




///////////////////////////////////
///////////////////////////////////
#define PRO_GET				2
#define PRO_POST			3
#define PRO_HTTP            4
#define PRO_FTP             5
#define PRO_FTP_LOGIN			6
#define PRO_FTP_FILE_DOWN		8
#define PRO_FTP_FILE_UP			7
#define PRO_MSN_LOGIN			28
#define PRO_MSN							27
#define PRO_MSN_FILE_TRANS		30
#define PRO_SMTP			23
#define PRO_POP3			24
#define PRO_YAHOO_MESS			31
#define PRO_ICQ				32
#define PRO_BITTORRENT			49
#define PRO_PCANYWHERE			61
#define PRO_TERMINAL_SERVICE		62
#define PRO_SOCK5				46
#define PRO_TELNET				44
#define PRO_IMAP4				25
#define PRO_XUNLEI				51
#define PRO_PPSTREAM			        64
#define PRO_QQLIVE				65
#define PRO_PPLIVE				66
#define PRO_QIANLONG				92
#define PRO_LIANZHONG				77
#define PRO_SQL_INSERT				11
#define PRO_SQL_DELETE				12
#define PRO_ZHENGTU				81
#define PRO_MOSHOU				82
#define PRO_YUANHANG				80
#define PRO_DAZHIHUI				91
#define PRO_QQGAME				84
#define PRO_SSH					45
#define PRO_HTTP_AGENT				47
#define PRO_KAZAA				52
#define PRO_RTSP				67
#define PRO_FILE_SHARE				96
#define PRO_NET_PRINT				97
#define PRO_CVS					98
#define PRO_HAOFANG				79
#define PRO_GAMEPLAT_VS				85
#define PRO_POCO				53
#define PRO_TOTOLOOK				54
#define PRO_CHINA_GAMECENTER			86
#define PRO_163_POPO				38
#define PRO_163_POPO_GAME			88
#define PRO_BIANFENG				78
#define PRO_QQ_LOGIN				34
#define PRO_QQ							33
#define PRO_SUPER_XUANFENG			57
#define PRO_UUSEE				68
#define PRO_QINGYULE				69
#define PRO_MYSEE				70
#define PRO_FEIDIAN_NETTV			72
#define PRO_JINGWUTUAN				89
#define PRO_LEIKE				71
#define PRO_TONGHUASHUN				93
#define PRO_STOCK_STAR				94
#define PRO_GOOGLE_TALK				39
#define PRO_BAIDU_XIABA				55
#define PRO_SKYPE				40
#define PRO_ALI_WANGWANG			41
#define PRO_LAVA_LAVA				42
#define PRO_QQ_FILE_TRANS			35
#define PRO_BAIBAO				58
#define PRO_STOCK_HEXUN				99
#define PRO_STOCK_TONGDAXIN			100
#define PRO_SOUQ				101
#define PRO_BAIDU_HI_LOGIN			102
#define PRO_BAIDU_HI_FILE_TRANS		        103
#define PRO_FETION				105
#define PRO_EMULE				50
#define PRO_PP_DIANDIANTONG			36
#define PRO_SINA_UC				37
#define PRO_VJBASE				73
#define PRO_SINA_IGAME				87
#define PRO_FLASHGET				59
#define PRO_PP_DOG				56
#define PRO_P2P_TUDOU					106
#define PRO_SAFE360_UPDATE			107
#define PRO_QVOD						108
#define PRO_YOUKU						109
#define PRO_SINA_BOKE					110
#define PRO_P2P_LARGE 111
#define PRO_WPS                                            117


#define IP_TCP 6     //tcp
#define IP_UDP 17   //udp

#define UNTOUCHED 0
#define NO_MATCH_YET 1
#define NO_MATCH 2

#define DIR_CS			0
#define DIR_SC 			1

#define DIR_UP 0
#define DIR_DOWN 1
#define DIR_IN 2 

#define DIR_UNKNOW 		2


typedef unsigned int u32;
typedef unsigned char u8;
typedef unsigned short u16;

typedef struct _TPktinfo{//current incoming pkt info
	u8 protype;	
	u8 dir;	
	u16 iplen;
	
	u32 innerip;
	u32 outerip;

	u16 innerport;
	u16 outerport;

	u32 seq;
	u32 ack;

	u16 headerlen;//ipheader+proheader;
	u16 reserve;
//	u16 iphdrlen;
//	u16 prohdrlen;//udp or tcp head len;
	
	u8* payload;        
}TPktinfo;

static inline u16 __nhs(u16 x)
{
	return x<<8 | x>>8;
}
static inline u32 __nhl(u32 x)
{
	return x<<24 | x>>24 |
		(x & (u32)0x0000ff00UL)<<8 |
		(x & (u32)0x00ff0000UL)>>8;
}

#define __hnl(x) __nhl(x)
#define __hns(x) __nhs(x)
#define get_u8(X,O)  (*(u8 *)(X + O))
#define get_u16(X,O)  (*(u16 *)(X + O))
#define get_u32(X,O)  (*(u32 *)(X + O))


//��ʱ��ʾbuffer��==0����ʾ
#define MAX_TEMP_BUFFER 1500
#define COPY_TO_BUFFER(x,len) strncpy(g_TempBuffer,x,len);g_TempBuffer[len]='\0'
#define SHOW_BUFFER DEBUG(D_INFO)("%s\n",g_TempBuffer)
extern u8 g_TempBuffer[MAX_TEMP_BUFFER];

#endif

