// bdbmem.cpp : Defines the entry point for the console application.
//
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <db.h>
#include "bdbmem.h"
#include <pthread.h>

/*
static unsigned int QueryBDB(DB *dbp,my_hash_type *jhash)
{
	DBT key, data;
	unsigned int ret=-1;
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	key.data = jhash;
	key.size = sizeof(my_hash_type);

	data.data = &ret;
	data.ulen = 4;
	data.flags = DB_DBT_USERMEM;
	if(DB_NOTFOUND==dbp->get(dbp,NULL,&key,&data,0))
		return RET_BDB_FAIL;
	return ret;	
}
*/
static unsigned int QueryBDB_Len(DB* dbp, void* hash_key, int key_len, void *value ,int value_len)
{
	DBT key, data;
	unsigned int ret=RET_BDB_FAIL;
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));
	key.data = hash_key;
	key.size = key_len ;

	data.data = value;
	data.ulen = value_len;
	data.flags = DB_DBT_USERMEM;
	if(DB_NOTFOUND==dbp->get(dbp,NULL,&key,&data,0))
		return RET_BDB_FAIL;
	return RET_BDB_OK;
}

/*
static unsigned int RecordBDB(DB* dbp,my_hash_type* jhash, void *idata, int len)
{
	DBT key, data;
    	int ret;
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	key.data = jhash;
	key.size = sizeof(my_hash_type);

	data.data = idata;
	data.size = len; 

	ret = dbp->put(dbp, NULL, &key, &data, DB_NOOVERWRITE);
	if (ret == DB_KEYEXIST) {
			return -1;
	}
	return 0;
}*/
static unsigned int RecordBDB_Len(DB* dbp, void* hash_key, int key_len, void* value, int value_len)
{
	DBT key, data;
    	int ret;
	/* Database open omitted for clarity */

	/* Zero out the DBTs before using them. */
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	key.data = hash_key;
	key.size = key_len;

	data.data = value;
	data.size = value_len; 

	ret = dbp->put(dbp, NULL, &key, &data, DB_NOOVERWRITE);
	if (ret == DB_KEYEXIST) {
			return RET_BDB_FAIL;
	}
	return RET_BDB_OK;

}

static unsigned int DeleteBDB_Len(DB* dbp,void *hash_key, int key_len)
{
	DBT key;
        memset(&key,0,sizeof(DBT));
	key.data = hash_key;
	key.size = key_len;
	dbp->del(dbp,NULL,&key,0);
}

static unsigned int ReplaceBDB_Len(DB* dbp,void *ukey, int key_len,void* new_data, int len)
{

	DBC *cursorp;
	DBT key, data;
	int ret;
	/* Initialize our DBTs. */
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	/* Set up our DBTs */
	key.data = ukey;
	key.size = key_len;//sizeof(my_hash_type);

	/* Database open omitted */

	/* Get the cursor */
	dbp->cursor(dbp, NULL, &cursorp, 0);
	if(NULL == cursorp)
	{
		printf("get the curs failed.\n");
		return -1;
	}

	/* Position the cursor */
	ret = cursorp->get(cursorp, &key, &data, DB_SET);
	if (ret == 0) {
		data.data = new_data;
		data.size = len;
		cursorp->put(cursorp, &key, &data, DB_CURRENT);
	}
        else
	{
		if(cursorp != NULL)
			cursorp->close(cursorp);
		return -1;
	}

	/* Cursors must be closed */
	if (cursorp != NULL) 
		cursorp->close(cursorp); 
	return 0;
}
unsigned int query_mem_hash_uint(mem_hash* pmem, unsigned int* jhash, char *Desc ,int len)
{
	return QueryBDB_Len(pmem->dbp, jhash,4, Desc ,len);
}
unsigned int query_mem_hash_string(mem_hash* pmem, const char* jhash, char *Desc, int len)
{
	return QueryBDB_Len(pmem->dbp,(void *)jhash,strlen(jhash),Desc,len);
}

unsigned int record_mem_hash_uint(mem_hash* pmem, unsigned int *jhash, void* idata,int len)
{
	return RecordBDB_Len(pmem->dbp, jhash,4,idata,len);
}
unsigned int record_mem_hash_string(mem_hash* pmem, const char * jhash, void* idata, int len)
{
	return RecordBDB_Len(pmem->dbp,(void *)jhash,strlen(jhash),idata,len);
}
unsigned int replace_mem_hash_uint(mem_hash* pmem , unsigned int* jhash,void* idata,int len)
{
	return ReplaceBDB_Len(pmem->dbp,jhash,4, idata,len);
}
unsigned int replace_mem_hash_string(mem_hash* pmem , const char* jhash,void* idata,int len)
{
	return ReplaceBDB_Len(pmem->dbp,(void *)jhash,strlen(jhash), idata,len);
}

unsigned int delete_mem_hash_uint(mem_hash* pmem, unsigned int *jhash)
{
	DeleteBDB_Len(pmem->dbp ,jhash,4);
}

unsigned int delete_mem_hash_string(mem_hash* pmem, const char* jhash)
{
	DeleteBDB_Len(pmem->dbp ,(void *)jhash,strlen(jhash));
}
unsigned int record_mem_hash_core(mem_hash* pmem, u_int64* jhash, char* value ,int len)
{
	return RecordBDB_Len(pmem->dbp, jhash,sizeof(u_int64),value,len);
}
unsigned int replace_mem_hash_core(mem_hash* pmem, u_int64* jhash,char *value, int len)
{
	return ReplaceBDB_Len(pmem->dbp,jhash,sizeof(u_int64), value,len);
}
unsigned int query_mem_hash_core(mem_hash* pmem,u_int64* jhash, char * value, int len)
{
	return QueryBDB_Len(pmem->dbp, jhash,sizeof(u_int64), value ,len);
}
unsigned int delete_mem_hash_core(mem_hash* pmem,u_int64* jhash)
{
	DeleteBDB_Len(pmem->dbp ,jhash,sizeof(u_int64));
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
int delete_all_mem_hash(mem_hash* pmem)
{
	int ret;
	unsigned int count;
	pmem->dbp->truncate(pmem->dbp,NULL,&count,0);
	return count;
}


void close_mem_hash(mem_hash* pmem)
{
   	// Close our database handle, if it was opened. 
    int ret_t = 0;
    if(pmem->cursorp!=NULL)
	{
		ret_t = pmem->cursorp->close(pmem->cursorp);
                if(ret_t!=0)
			fprintf(stderr, "%s database cursor close failed.\n",
				db_strerror(ret_t));
			
	}
	if (pmem->dbp != NULL) {
		ret_t = pmem->dbp->close(pmem->dbp, 0);
		if (ret_t != 0) {
			fprintf(stderr, "%s database close failed.\n",
				db_strerror(ret_t));
			//ret = ret_t;
		}
	}

	// Close our environment, if it was opened. 
	if (pmem->envp != NULL) {
		ret_t = pmem->envp->close(pmem->envp, 0);
		if (ret_t != 0) {
			fprintf(stderr, "environment close failed: %s\n",
				db_strerror(ret_t));
			//ret = ret_t;
		}
	}
}

u_int32 get_first_dbrecord(mem_hash* pmem, u_int64* jhash, char * value, int len)
{
/*	if(pmem->cursorp!=NULL)
	{
		pmem->cursorp->close(pmem->cursorp);
		pmem->cursorp = NULL;
	}
*/	
	pmem->dbp->cursor(pmem->dbp,NULL,&pmem->cursorp,0);
       DBT key, data;
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	int ret;
	ret = pmem->cursorp->get(pmem->cursorp, &key, &data, DB_NEXT);
	if(ret==0)
	{
		*jhash = *(u_int64 *)(key.data);
		memcpy(value, (char *)data.data, len);
//		value = data.data ;
//		printf("the bdb data:%u\n", *jhash);
//		return (void*)data.data;
		return RET_BDB_OK;
	}
	if(pmem->cursorp!=NULL)
	{
		pmem->cursorp->close(pmem->cursorp);
		pmem->cursorp = NULL;
	}
	return RET_BDB_FAIL;
}
u_int32 get_next_dbrecord(mem_hash* pmem, u_int64* jhash, char * value, int len)
{
	int ret;
  	DBT key, data;
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	if(pmem->cursorp!=NULL)
	{
		ret = pmem->cursorp->get(pmem->cursorp, &key, &data, DB_NEXT);		
	}
	if(ret==0)
	{
		*jhash = *(u_int64 *)(key.data);
		memcpy(value, (char *)data.data, len);
		return RET_BDB_OK;
	}
	if(pmem->cursorp!=NULL)
	{
		pmem->cursorp->close(pmem->cursorp);
		pmem->cursorp = NULL;
	}
	return RET_BDB_FAIL;	
}

int whilenext(DB* dbp)
{
	DBC *cursorp;
	DBT key, data;
	int ret;

	dbp->cursor(dbp, NULL, &cursorp, 0);

	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	/* Iterate over the database, retrieving each record in turn. */
	while ((ret = cursorp->get(cursorp, &key, &data, DB_NEXT)) == 0) 
	{
		printf("get %u-%d",*((unsigned int*)key.data),*((int*)data.data));
	}
        
	if (ret != DB_NOTFOUND) {
		/* Error handling goes here */
	}

/* Cursors must be closed */
	if (cursorp != NULL) 
		cursorp->close(cursorp); 
    return 0;
}

/*
void* threadproc(void* param)
{
	DB* dbp = (DB*)param;
	while(1)
	{
		whilenext(dbp);
		printf("--------------------\n");
		usleep(1000);
	}
	return 0;
}
void* threadprocdel(void * param)
{
	DB* dbp = (DB*)param;
	my_hash_type i = 0;
	while(1)
	{
		DBT key;
        for(i = 0;i<10;i++)
		{
			memset(&key, 0, sizeof(DBT));

			key.data = &i;
			key.size = sizeof(my_hash_type);
			dbp->del(dbp, NULL, &key, 0);
			usleep(1000000);
		}
	
	}
}
*/

mem_hash * create_hash_mem_info(const char* hash_name,int hash_size)
{
	mem_hash * rethash = (mem_hash *)malloc(sizeof(mem_hash));
	//rethash->dbp  = NULL;
	//rethash->envp = NULL;
	//DB *dbp	      = rethash->dbp;
	//DB_ENV *envp  = rethash->envp;
	
	DB_MPOOLFILE *mpf = NULL;

	int ret, ret_t; 
	u_int32_t open_flags;

	// Create the environment 
	ret = db_env_create(&rethash->envp, 0);
	if (ret != 0) {
		fprintf(stderr, "Error creating environment handle: %s\n",
			db_strerror(ret));
		goto err;
	}

	open_flags =DB_CREATE| DB_INIT_MPOOL|DB_THREAD|DB_PRIVATE;
		//DB_CREATE     |  /* Create the environment if it does not exist */
		//DB_INIT_LOCK  |  /* Initialize the locking subsystem */
		//DB_INIT_LOG   |  /* Initialize the logging subsystem */
		//DB_INIT_MPOOL |  /* Initialize the memory pool (in-memory cache) */
		//DB_INIT_TXN   |
		//DB_SYSTEM_MEM;      /* Region files are not backed by the filesystem. 
					//	 * Instead, they are backed by heap memory.  */

	// Specify in-memory logging 
	ret = rethash->envp->set_flags(rethash->envp, DB_LOG_INMEMORY, 1);
	if (ret != 0) {
		fprintf(stderr, "Error setting log subsystem to in-memory: %s\n",
			db_strerror(ret));
		goto err;
	}
	// 
	// Specify the size of the in-memory log buffer. 
	//
	//ret = envp->set_lg_bsize(envp, 1 * 1024 * 1024);
	//if (ret != 0) {
	//	fprintf(stderr, "Error increasing the log buffer size: %s\n",
	//		db_strerror(ret));
	//	goto err;
	//}

	 
	//* Specify the size of the in-memory cache. 
	if(hash_size ==0)
	{
        	ret =rethash->envp->set_cachesize(rethash->envp, 0, 1 * 1024 * 1024, 1);
	}
	else
	{
		ret = rethash->envp->set_cachesize(rethash->envp, 0, hash_size * 1024 * 1024, 1);
	}
	if (ret != 0) {
		fprintf(stderr, "Error increasing the cache size: %s\n",
			db_strerror(ret));
		goto err;
	}

	/* 
	* Now actually open the environment. Notice that the environment home
	* directory is NULL. This is required for an in-memory only
	* application. 
	*/
	ret = rethash->envp->open(rethash->envp, NULL, open_flags, 0);
	if (ret != 0) {
		fprintf(stderr, "Error opening environment: %s\n",
			db_strerror(ret));
		goto err;
	}


	/* Initialize the DB handle */
	ret = db_create(&rethash->dbp, rethash->envp, 0);
	if (ret != 0) {
		rethash->envp->err(rethash->envp, ret,
			"Attempt to create db handle failed.");
		goto err;
	}


	/* 
	* Set the database open flags. Autocommit is used because we are 
	* transactional. 
	*/
	open_flags = DB_CREATE;// | DB_AUTO_COMMIT;
	ret = rethash->dbp->open(rethash->dbp,         /* Pointer to the database */
		NULL,        /* Txn pointer */
		NULL,        /* File name -- Must be NULL for inmemory! */
		hash_name,     /* Logical db name */
		DB_HASH,    /* Database type (using btree) */
		open_flags,  /* Open flags */
		0);          /* File mode. Using defaults */

	if (ret != 0) {
		rethash->envp->err(rethash->envp, ret,
			"Attempt to open db failed.");
		goto err;
	}

	// Configure the cache file 
	mpf = rethash->dbp->get_mpf(rethash->dbp);
	ret = mpf->set_flags(mpf, DB_MPOOL_NOFILE, 1);

	if (ret != 0) {
		rethash->envp->err(rethash->envp, ret,
			"Attempt failed to configure for no backing of temp files.");
		goto err;
	}

    return rethash;

err:
	// Close our database handle, if it was opened. 
	if (rethash->dbp != NULL) {
		ret_t = rethash->dbp->close(rethash->dbp, 0);
		if (ret_t != 0) {
			fprintf(stderr, "%s database close failed.\n",
				db_strerror(ret_t));
			ret = ret_t;
		}
	}

	// Close our environment, if it was opened. 
	if (rethash->envp != NULL) {
		ret_t = rethash->envp->close(rethash->envp, 0);
		if (ret_t != 0) {
			fprintf(stderr, "environment close failed: %s\n",
				db_strerror(ret_t));
			ret = ret_t;
		}
	}
	// Final status message and return.
	return NULL;
	//return (ret == 0 ? EXIT_SUCCESS : EXIT_FAILURE);

}

unsigned long long gethash_value_2(unsigned short dport,unsigned short sport,unsigned int dip)
{
	unsigned int x = dport;
        x = x<<16;
        x += sport; 
	unsigned long long y = x;
	y = y<<32;
	y += dip;
	return y;
}


unsigned long long gethash_value_1(unsigned short tcp_udp,unsigned short sport, unsigned int sip)
{
	unsigned int x = tcp_udp;
	x = x<<16;
	x += sport;
	unsigned long long y =x;
	y = y <<32;
	y +=sip;
	return y;
}
#if 0
int main(void)
{
	// Initialize our handles 
        //daemon(1,0);

	mem_hash * mem = create_hash_mem_info("test",1);
	//unsigned long long t1 = gethash(1,1,1);
        unsigned long long t1 = 1;
	unsigned int i = 0;
	unsigned long long t2;
	unsigned int x =2;
	pthread_t pt1,pt2;
        pthread_create(&pt1,NULL,threadproc,mem->dbp);
	pthread_create(&pt2,NULL,threadprocdel,mem->dbp);
	while(1)
	{
		//x=1;
                i = 0;
		for( t2= 0;i<10;i++,t2++)
		{
			x++;
			record_mem_hash(mem,&t2, &x,4);
			usleep(1000);
		}
 	}   
      /*  unsigned int * pi = (unsigned int*)get_first_dbrecord(mem);
	while(pi!=NULL)
	{
		printf("%u\n",*pi);
		pi = (unsigned int*)get_next_dbrecord(mem);
	}*/
                
	close_mem_hash(mem);
	//printf("this is %u",query_mem_hash(mem,&t1));
	return 1;
///////////////////////////////////////////////////////////
#if 0
	DB *dbp = NULL;
	DB_ENV *envp = NULL;
	DB_MPOOLFILE *mpf = NULL;

	int ret, ret_t; 
	const char *db_name = "in_mem_db1";
	u_int32_t open_flags;

	// Create the environment 
	ret = db_env_create(&envp, 0);
	if (ret != 0) {
		fprintf(stderr, "Error creating environment handle: %s\n",
			db_strerror(ret));
		goto err;
	}

	open_flags = DB_CREATE| DB_INIT_CDB| DB_INIT_MPOOL|DB_THREAD|DB_PRIVATE; //DB_SYSTEM_MEM;
		//DB_CREATE     |  /* Create the environment if it does not exist */
		//DB_INIT_LOCK  |  /* Initialize the locking subsystem */
		//DB_INIT_LOG   |  /* Initialize the logging subsystem */
		//DB_INIT_MPOOL |  /* Initialize the memory pool (in-memory cache) */
		//DB_INIT_TXN   |
		//DB_PRIVATE;
		//DB_SYSTEM_MEM;      /* Region files are not backed by the filesystem. 
					//	 * Instead, they are backed by heap memory.  */

	// Specify in-memory logging 
	ret = envp->set_flags(envp, DB_LOG_INMEMORY, 1);
	if (ret != 0) {
		fprintf(stderr, "Error setting log subsystem to in-memory: %s\n",
			db_strerror(ret));
		goto err;
	}
	// 
	// Specify the size of the in-memory log buffer. 
	//
	ret = envp->set_lg_bsize(envp, 1 * 1024 * 1024);
	if (ret != 0) {
		fprintf(stderr, "Error increasing the log buffer size: %s\n",
			db_strerror(ret));
		goto err;
	}

	 
	//* Specify the size of the in-memory cache. 
	
	ret = envp->set_cachesize(envp, 0, 10 * 1024 * 1024, 1);
	if (ret != 0) {
		fprintf(stderr, "Error increasing the cache size: %s\n",
			db_strerror(ret));
		goto err;
	}

	/* 
	* Now actually open the environment. Notice that the environment home
	* directory is NULL. This is required for an in-memory only
	* application. 
	*/
	ret = envp->open(envp, NULL, open_flags, 0);
	if (ret != 0) {
		fprintf(stderr, "Error opening environment: %s\n",
			db_strerror(ret));
		goto err;
	}


	/* Initialize the DB handle */
	ret = db_create(&dbp, envp, 0);
	if (ret != 0) {
		envp->err(envp, ret,
			"Attempt to create db handle failed.");
		goto err;
	}


	/* 
	* Set the database open flags. Autocommit is used because we are 
	* transactional. 
	*/
	open_flags = DB_CREATE;//|DB_AUTO_COMMIT;
	ret = dbp->open(dbp,         /* Pointer to the database */
		NULL,        /* Txn pointer */
		NULL,        /* File name -- Must be NULL for inmemory! */
		db_name,     /* Logical db name */
		DB_BTREE,    /* Database type (using btree) */
		open_flags,  /* Open flags */
		0);          /* File mode. Using defaults */

	if (ret != 0) {
		envp->err(envp, ret,
			"Attempt to open db failed.");
		goto err;
	}

	// Configure the cache file 
	mpf = dbp->get_mpf(dbp);
	ret = mpf->set_flags(mpf, DB_MPOOL_NOFILE, 1);

	if (ret != 0) {
		envp->err(envp, ret,
			"Attempt failed to configure for no backing of temp files.");
		goto err;
	}
    	//CreateThread(NULL,0,(LPTHREAD_START_ROUTINE)threadproc,dbp,0,NULL);
	//CreateThread(NULL,0,(LPTHREAD_START_ROUTINE)threadprocdel,dbp,0,NULL);
	unsigned int i =100;
	unsigned long long xx = gethash(333,222,33333);
	
	RecordBDB(dbp,&xx,&i,4);
        
	printf("%llu->%d",xx,QueryBDB(dbp,&xx));
	/*for( i = 1;i<10000;i++)
	{
		int y          = i*2-1;
		RecordBDB(dbp,&i,&y,4);
	}
	for(i = 1;i<10000;i++)
	{
	
		printf("%u->%u\n",i,QueryBDB(dbp,&i));
	}*/
            

err:
	// Close our database handle, if it was opened. 
	if (dbp != NULL) {
		ret_t = dbp->close(dbp, 0);
		if (ret_t != 0) {
			fprintf(stderr, "%s database close failed.\n",
				db_strerror(ret_t));
			ret = ret_t;
		}
	}

	// Close our environment, if it was opened. 
	if (envp != NULL) {
		ret_t = envp->close(envp, 0);
		if (ret_t != 0) {
			fprintf(stderr, "environment close failed: %s\n",
				db_strerror(ret_t));
			ret = ret_t;
		}
	}

	// Final status message and return.
	printf("I'm all done.\n");
	return (ret == 0 ? EXIT_SUCCESS : EXIT_FAILURE);
#endif
} 
#endif 
