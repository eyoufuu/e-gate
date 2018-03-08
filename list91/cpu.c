#include<stdlib.h>   
#include<stdio.h>   
#include<unistd.h>   
#define  CPU_FILE_PROC_STAT "/proc/stat"  
#define  MEM_FILE_PROC_MEMINFO "/proc/meminfo" 
/*the utilization of CPU */  
struct mem_usage_struct
{
	unsigned long mem_total;
	unsigned long mem_free;
	unsigned long active;
	unsigned long unactive;
};

struct cpu_usage_struct   
{
	unsigned int cpu_num;
	char cup_info[4][100];
	unsigned long cpu_user;   
	unsigned long cpu_sys;   
	unsigned long cpu_nice;   
	unsigned long cpu_idle;   
};   
double get_cpu_use_rate(const struct cpu_usage_struct *cur,   
                const struct cpu_usage_struct *old)   
{   
    double user,sys,nice,idle,total;   
    double cpu_rate;   
    user = (double)(cur->cpu_user - old->cpu_user);   
    sys = (double)(cur->cpu_sys - old->cpu_sys);   
    nice = (double)(cur->cpu_nice - old->cpu_nice);   
    idle = (double)(cur->cpu_idle - old->cpu_idle);   
    total = user + sys + nice + idle;   
    cpu_rate = (1-idle/total)*100;   
    return cpu_rate;   
}   
double get_cpu_free_rate(const struct cpu_usage_struct *cur,   
                const struct cpu_usage_struct *old)   
{   
    double user,sys,nice,idle,total;   
    double free_rate;   
    user = (double)(cur->cpu_user - old->cpu_user);   
    sys = (double)(cur->cpu_sys - old->cpu_sys);   
    nice = (double)(cur->cpu_nice - old->cpu_nice);   
    idle = (double)(cur->cpu_idle - old->cpu_idle);   
    total = user + sys + nice + idle;   
    free_rate = (idle /total)*100;   
    return free_rate;    
}   
int get_cpuinfo_from_proc_cpuinfo(struct cpu_usage_struct *usage)
{
	FILE *fp = NULL;
	fp = fopen(CPU_FILE_PROC_CPUINFO,"r");
	
}
int get_cpuinfo_from_proc_stat(struct cpu_usage_struct *usage)   
{   
    FILE *fp = NULL;   
    char tmp[10];   
    fp = fopen(CPU_FILE_PROC_STAT,"r");   
    if(fp == NULL)   
    {   
        perror("fopen");   
        return -1;   
    }   
 //   printf("%s,%d\n",__FILE__,__LINE__);   
    fscanf(fp,"%s %lu %lu %lu %lu",tmp,&(usage->cpu_user),&(usage->cpu_sys),   
                    &(usage->cpu_nice),&(usage->cpu_idle));   
 //   printf("%s %d\n",__FILE__,__LINE__);   
    fclose(fp);   
 //   printf("%s %d\n",__FILE__,__LINE__);   
    return 1;   
}
int get_meminfo_from_proc_meminfo(struct mem_usage_struct* usage)
{
	FILE *fp = NULL;
	char tmp1[50];
	char tmp2[10];
	fp = fopen(MEM_FILE_PROC_MEMINFO,"r");
	if(fp == NULL)
	{
		perror("fopen");
		return -1;
	}
	fscanf(fp,"%s %lu %s",tmp1,&(usage->mem_total),tmp2);
//	fseek(fp,2,SEEK_SET);
	fscanf(fp,"%s %lu %s",tmp1,&(usage->mem_free),tmp2);
	fclose(fp);	
	return 1;
}   
int main()   
{   
    struct cpu_usage_struct *cur,*old;   
    struct mem_usage_struct *cur1,*old1;
    double use_rate,free_rate;   
    unsigned long use_mem = 0;
    old = (struct cpu_usage_struct*)malloc(sizeof(struct cpu_usage_struct));   
    if(old == NULL)   
    {   
        perror("malloc error");   
        return -1;   
    }   
    cur = (struct cpu_usage_struct*)malloc(sizeof(struct cpu_usage_struct));   
    if(cur == NULL)   
    {   
        perror("malloc error");   
        return -1;   
    }
    old1 = (struct mem_usage_struct*)malloc(sizeof(struct mem_usage_struct));   
    if(old == NULL)   
    {   
        perror("malloc error");   
        return -1;   
    }   
    cur1 = (struct mem_usage_struct*)malloc(sizeof(struct mem_usage_struct));   
    if(cur == NULL)   
    {   
        perror("malloc error");   
        return -1;   
    }

while(1)
{   
    get_cpuinfo_from_proc_stat(old);
//  get_meminfo_from_proc_meminfo(old1);
   
    sleep(3);   
    get_cpuinfo_from_proc_stat(cur);   
    get_meminfo_from_proc_meminfo(cur1);
    use_mem = cur1->mem_total - cur1->mem_free;
//	printf("%u,%u\n",cur1->mem_total,cur1->mem_free);
    use_rate = get_cpu_use_rate(cur,old);   
    free_rate = (get_cpu_free_rate(cur,old)*10.0+0.5)/10.0;   
    printf("use_rate:%.1f,free_rate:%.1f,%u\n",use_rate,free_rate,use_mem);   
}
    return 1;   
}
