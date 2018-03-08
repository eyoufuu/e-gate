#if defined(_WIN32) || defined(_WIN64)  //Ϊ��֧��windowsƽ̨�ϵı���
#include <windows.h>
#endif
#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include "include/mysql.h"  
 
//�������ݿ�����ĺ꣬Ҳ���Բ��������ź���ֱ��д������
#define INSERT_QUERY "insert into instraffic(logtime,up,down) values(%u,%d,%d) "
#define INSERT_QUERY_TOP_IP "insert into instraffic(logtime,ip,up,down,proid) values(%u,%d,%d,%d,%d)"
#define DELETE_QUERY "delete from instraffic where logtime<= %d"



MYSQL mysql,*sock;    //�������ݿ����ӵľ�����������ڼ������е�MySQL����
MYSQL_RES *res;       //��ѯ��������ṹ����
MYSQL_FIELD *fd ;     //�����ֶ���Ϣ�Ľṹ
MYSQL_ROW row ;       //���һ�в�ѯ������ַ�������

char  qbuf[160];      //��Ų�ѯsql����ַ���
inline  void query_insert()
{
	if(mysql_query(sock,qbuf)) 
	{
		fprintf(stderr,"Query failed insert (%s)\n on %s",mysql_error(sock),qbuf);
		exit(1);
	}
}

inline void query_delete(int i)
{
	char buffer[64];
	sprintf(buffer,DELETE_QUERY,i);
	if(mysql_query(sock,buffer)) 
	{
		fprintf(stderr,"Query failed delete (%s)\n on %s",mysql_error(sock),buffer);
		exit(1);
	}
}
int main(int argc, char **argv) //char **argv �൱�� char *argv[]
{
    
    //if (argc != 2) {  //����������
    //    fprintf(stderr,"usage : mysql_select <userid>\n\n");
    //    exit(1);
    //}
    srand( (unsigned)time( NULL ) ); 
    
    mysql_init(&mysql);
    if (!(sock = mysql_real_connect(&mysql,"192.168.1.8","root","123456","baseconfig",0,NULL,0))) {
        fprintf(stderr,"Couldn't connect to engine!\n%s\n\n",mysql_error(&mysql));
        perror("");
        exit(1);
    }
	query_delete(99999999);
    unsigned int i = 100;
	while(1)
	{
		sprintf(qbuf,INSERT_QUERY,i, rand()%200,rand()%500);
		query_insert();
		printf("insert \n");
		i++;
		//ÿ���Ӵ��һ������
		sleep(1);
		int j = 0;
		for(;j<20;j++)
		{
			sprintf(qbuf,INSERT_QUERY_TOP_IP,i,rand()%15,rand()%200,rand()%500,rand()%20);
			query_insert();
		}
		printf("insert ip 20\n");
		sleep(1);
		query_delete(i-5);
    }
/*
    if (!(res=mysql_store_result(sock))) {
        fprintf(stderr,"Couldn't get result from %s\n", mysql_error(sock));
        exit(1);
    }
    
    printf("number of fields returned: %d\n",mysql_num_fields(res));
        
    while (row = mysql_fetch_row(res)) {
        printf("Ther userid #%d 's username is: %s\n", atoi(argv[1]),(((row[0]==NULL)&&(!strlen(row[0]))) ? "NULL" : row[0])) ; 
        puts( "query ok !\n" ) ; 
    } 
  */  
    mysql_free_result(res);
    mysql_close(sock);
    exit(0);
    return 0;   //. Ϊ�˼��ݴ󲿷ֵı������������
}
