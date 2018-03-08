#ifndef _BDBMEM_H_
#define _BDBMEM_H_

#include <db.h>
#include <unistd.h>
#include "globDefine.h"

typedef struct
{
	DB *dbp  ;
	DB_ENV *envp ;
    	DBC *cursorp;
}mem_hash;

#define TCP_CONNECT 1
#define UDP_CONNECT 0

#define RET_BDB_OK 0
#define RET_BDB_FAIL -1


mem_hash * create_hash_mem_info(const char* hash_name,int hash_size);
void close_mem_hash(mem_hash* pmem);


int delete_all_mem_hash(mem_hash* pmem);


unsigned int delete_mem_hash_string(mem_hash* pmem, const char* jhash);
unsigned int delete_mem_hash_uint(mem_hash* pmem, unsigned int *jhash);

unsigned int replace_mem_hash_string(mem_hash* pmem , const char* jhash,void* idata,int len);
unsigned int replace_mem_hash_uint(mem_hash* pmem , unsigned int* jhash,void* idata,int len);

unsigned int record_mem_hash_string(mem_hash* pmem, const char * jhash, void* idata, int len);
unsigned int record_mem_hash_uint(mem_hash* pmem, unsigned int *jhash, void* idata,int len);

unsigned int query_mem_hash_string(mem_hash* pmem, const char* jhash, char *Desc, int len);
unsigned int query_mem_hash_uint(mem_hash* pmem, unsigned int* jhash, char *Desc ,int len);


u_int32 get_first_dbrecord(mem_hash* pmem, u_int64* jhash, char * value, int len);
u_int32 get_next_dbrecord(mem_hash* pmem, u_int64* jhash, char * value, int len);




///////////////////////////////////////////////////////////////////////////////////////////////////////
unsigned long long gethash_value_1(unsigned short tcp_udp,unsigned short sport, unsigned int sip);

unsigned int record_mem_hash_core(mem_hash* pmem, u_int64* jhash, char* value ,int len);
unsigned int replace_mem_hash_core(mem_hash* pmem, u_int64* jhash,char *value, int len);
unsigned int query_mem_hash_core(mem_hash* pmem,u_int64* jhash, char * value, int len);
unsigned int delete_mem_hash_core(mem_hash* pmem,u_int64* jhash); 


#endif




