#ifndef _WEBCAT_H
#define _WEBCAT_H

#include "global.h"

void webcat_init();
void webcat_uninit();
u8 webcat_getwebcat(u32 jhash);
u32 webcat_handle(u32 polid,u8* get,u16 getlen,u8* host,u16 hostlen,u8* mac);

#endif