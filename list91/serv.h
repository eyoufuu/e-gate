#ifndef _SERV_H
#define _SERV_H

#include "global.h"

#define EVT_CLI_REGISTER 			0

#define EVT_NETSEG_MODIFY 		    1
#define EVT_IP_MODIFY		 		2

#define EVT_POL_MODIFY 			    3

#define EVT_GBL_LOGINMODE           4
#define EVT_GBL_MODIFY              5
#define EVT_SPE_IP     			    6
#define EVT_SPE_HOST                7


#define EVT_GBL_REMINDPAGE      8
//#define EVT_INS_IP 				9

#define EVT_IPMAC					10


//#define EVT_ADMIN_LOGIN 			11

#define EVT_ACCOUNT_LOGIN 		    12

#define EVT_SYS_IP_CHANGE		    13
//#define EVT_ACCOUNT_NOEXIST 		14
//#define EVT_ACCOUNT_PASSWD 		15
//#define EVT_ACCOUNT_LOGINOK 		16
//#define EVT_ACCOUNT_IP 			17

#define EVT_CARD_MODIFY 			18
#define EVT_PRO_FEATHER			    19
#define EVT_INS_SUM				    20

#define EVT_ARP_IPMACBIND                           21

#define EVT_FILEOUT_FILETYPE                       22
#define EVT_FILEOUT_MAIL                                 23
#define EVT_FILEOUT_BBS                                 24
#define EVT_FILEOUT_IM                                      25
#define EVT_FILEOUT_NETDISK                        26
#define EVT_FILEOUT_FTP                                  27
#define EVT_FILEOUT_TFTP                                28
#define EVT_FILEOUT_BLOG                              29



extern int g_servsockfd;
extern struct sockaddr_un g_cliaddr;
extern u32 g_clilen;

void serv_init();

#endif
