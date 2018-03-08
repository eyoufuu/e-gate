#include <db.h>
int openbdb();
unsigned char querybdb(unsigned int *jhash);
int recordbdb(unsigned int* jhash, int *idata,int overwrite);
int replacebdb(unsigned int ukey,int unew_value);
void closedb();