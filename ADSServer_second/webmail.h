#ifndef __WEB_MAIL_H__
#define __WEB_MAIL_H__
#ifndef u16
#define u16 unsigned short
#endif//u16

#ifndef u32
#define u32 unsigned int
#endif//u32

#ifndef u8
#define u8 unsigned char
#endif//u8
int handle_webmail( u32 seq, u32 ack,u32 sip,u32 dip,u16 sport,u16 dport,
                                               const char * host ,const int hostlen,int mail_pos,const char * body, const int bodylen);

#endif
