#ifndef __SHAREDMEM_H
#define __SHAREDMEM_H

#include <time.h>

typedef struct _TTime{
        unsigned int curtime;
        struct tm curdate;
}TTime;

int create_sharedmem(unsigned int bufsize);
void destroy_sharedmem(int id);
char* cite_sharedmem(int id);
void uncite_sharedmem(char* buf);
#endif
