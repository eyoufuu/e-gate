// bdbmem.cpp : Defines the entry point for the console application.
//


#include <stdio.h>
#include <stdlib.h>
#include "db.h"
#include "bdb.h"
#include <string.h>
/*
unsigned int QueryBDB(DB *dbp,unsigned int *jhash)
{
	DBT key, data;
	unsigned int ret=-1;
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	key.data = jhash;
	key.size = 4;

	data.data = &ret;
	data.ulen = 4;
	data.flags = DB_DBT_USERMEM;
	dbp->get(dbp,NULL,&key,&data,0);
	return ret;
}*/
static DB* dbp = NULL;
int openbdb(/*DB *dbp*/)
{
	int ret;           /* function return value */
	if(dbp!=NULL)
	{
		dbp->close(dbp,0);
		dbp = NULL;
	}

	u_int32_t flags;   /* database open flags */

	/* Initialize the structure. This
	* database is not opened in an environment, 
	* so the environment pointer is NULL. */
	ret = db_create(&dbp, NULL, 0);
	if (ret != 0) {
		printf("something error here!\n");
		//AfxMessageBox("something error here!");
		return 0;
	}

	/* Database open flags */
	flags = DB_CREATE;    /* If the database does not exist, 
						  * create it.*/

	/* open the database */
	ret = dbp->open(dbp,        /* DB structure pointer */
		NULL,       /* Transaction pointer */
		"dbfile", /* On-disk file that holds the database. */
		"db",       /* Optional logical database name */
		DB_BTREE,   /* Database access method */
		flags,      /* Open flags */
		0);         /* File mode (using defaults) */
	if (ret != 0) 
	{
		return 0;
	}
	return 1;
}



unsigned char querybdb(/*DB *dbp,*/unsigned int *jhash)
{
	DBT key, data;
	unsigned char ret= 0;
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	key.data = jhash;
	key.size = 4;

	data.data = &ret;
	data.ulen = 1;
	data.flags = DB_DBT_USERMEM;
	dbp->get(dbp,NULL,&key,&data,0);
	return ret;

}



int recordbdb(/*DB* dbp,*/unsigned int* jhash, int *idata,int overwrite)
{
	DBT key, data;
    int ret;
	/* Database open omitted for clarity */

	/* Zero out the DBTs before using them. */
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	key.data = jhash;
	key.size = 4;

	data.data = idata;
	data.size = 4; 
    if(!overwrite)
	{
		ret = dbp->put(dbp, NULL, &key, &data, DB_NOOVERWRITE);

		if (ret == DB_KEYEXIST) 
		{
			return -1;
		}
	}
	else
	{
		ret = dbp->put(dbp,NULL,&key,&data,0);
	}
	return 0;
}


int replacebdb(/*DB* dbp,*/unsigned int ukey,int unew_value)
{

	DBC *cursorp;
	DBT key, data;
	int ret;
	/* Initialize our DBTs. */
	memset(&key, 0, sizeof(DBT));
	memset(&data, 0, sizeof(DBT));

	/* Set up our DBTs */
	key.data = &ukey;
	key.size = 4;

	/* Database open omitted */

	/* Get the cursor */
	dbp->cursor(dbp, NULL, &cursorp, 0);

	/* Position the cursor */
	ret = cursorp->get(cursorp, &key, &data, DB_SET);
	if (ret == 0) {
		data.data = &unew_value;
		data.size = 4;
		cursorp->put(cursorp, &key, &data, DB_CURRENT);
	}

	/* Cursors must be closed */
	if (cursorp != NULL) 
		cursorp->close(cursorp); 

	if (dbp != NULL)
		dbp->close(dbp, 0);
	return 0;
}
void closedb()
{
	unsigned ret_t;
	if (dbp != NULL) {
		ret_t = dbp->close(dbp, 0);
		if (ret_t != 0) {
			fprintf(stderr, "%s database close failed.\n",
				db_strerror(ret_t));
		}
	}
}
