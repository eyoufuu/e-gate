#include <db.h>
#include <unistd.h>
typedef struct _mem_hash
{
	DB *dbp  ;
	DB_ENV *envp ;
    DBC *cursorp;
}mem_hash;





#define RET_BDB_OK 0
#define RET_BDB_FAIL -1

#define CORE_TYPE unsigned long long


mem_hash * create_hash_mem_info(const char* hash_name,int hash_size);
void close_mem_hash(mem_hash* pmem);


int delete_all_mem_hash(mem_hash* pmem);


unsigned int delete_mem_hash_string(mem_hash* pmem, const char* jhash);
unsigned int delete_mem_hash_uint(mem_hash* pmem, unsigned int *jhash);

unsigned int replace_mem_hash_string(mem_hash* pmem , const char* jhash,void* value,int len);
unsigned int replace_mem_hash_uint(mem_hash* pmem , unsigned int* jhash,void* value,int len);
unsigned int replace_mem_hash_string_2(mem_hash* pmem , const char* jhash,int hash_len, void* value,int len);

unsigned int record_mem_hash_string(mem_hash* pmem, const char * jhash, void* value, int len);
unsigned int record_mem_hash_uint(mem_hash* pmem, unsigned int *jhash, void* value,int len);
unsigned int record_mem_hash_string_2(mem_hash* pmem, const char * jhash, int hash_len,void *value, int len);


unsigned int query_mem_hash_string(mem_hash* pmem, const char* jhash, char *value, int len);
unsigned int query_mem_hash_uint(mem_hash* pmem, unsigned int* jhash, char *value ,int len);
unsigned int query_mem_hash_string_2(mem_hash* pmem, const char *jhash,int hash_len, void* value, int len);






///////////////////////////////////////////////////////////////////////////////////////////////////////
unsigned int record_mem_hash_core(mem_hash* pmem, CORE_TYPE* jhash, char* value ,int len);
unsigned int replace_mem_hash_core(mem_hash* pmem, CORE_TYPE* jhash,char *value, int len);
unsigned int query_mem_hash_core(mem_hash* pmem,CORE_TYPE* jhash, char * value, int len);
unsigned int delete_mem_hash_core(mem_hash* pmem,CORE_TYPE* jhash); 


void* get_first_dbrecord(mem_hash* pmem);
void* get_next_dbrecord(mem_hash* pmem);




