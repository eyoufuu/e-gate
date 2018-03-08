
#include "global.h"

extern void emp_insertipemployeenode(u32 ip);
extern void emp_delipemployeenode(u32 ip);
extern void* emp_getipemployeenode(u32 ip);
extern void emp_updateipemployeenode(void* p);
extern void emp_initipemployeelist();

extern void log_insertippronode(u32 ip);
extern void log_delippronode(u32 ip);
extern void* log_getippronode(u32 ip);
extern void log_updateprolognode(void* p,u32 proid,u32 dir,u32 size,u32 block);
extern void log_initipprolist();
int main()
{
	emp_initipemployeelist();
	emp_insertipemployeenode(1895934144);
	emp_insertipemployeenode(1895999680);
	void* pEm = emp_getipemployeenode(1895934144);
	if( pEm!=NULL)
	{
		emp_updateipemployeenode(pEm);
	}
	
	log_initipprolist();
	void* pLog = log_getippronode(1895934144);
	if(pLog == NULL)
	{
		log_insertippronode(1895934144);
	}
	void* pLog1 = log_getippronode(1895934144);
	log_updateprolognode(pLog1,2,3,10,0);	
	log_updateprolognode(pLog1,2,3,10,0);	
	return 0;
}
