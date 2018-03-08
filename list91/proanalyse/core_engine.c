#include <stdio.h>
#include <stddef.h>
#include <stdlib.h>
#include <dlfcn.h>
#include <string.h>
#include <pthread.h>
#include "core_engine.h"
#include "module_config.h"
#include "../list.h"
#include "../sharedmem/sharedmem.h"
#include "pro_analyse_global.h"
#include "pro_analyse_game.h"
#include "pro_analyse_http.h"
#include "pro_analyse_im.h"
#include "pro_analyse_ip.h"
#include "pro_analyse_other.h"
#include "pro_analyse_p2p.h"
#include "pro_analyse_stock.h"
#include "core_engine_ftp.h"




//0x74746820 - > 20 68 74 74  this is in the memory little endian 

//qianbo 2009-10-15
//
#define get_ip_hash(ip) (ip>>16)

#define MATRIX_IP    65536
#define MATRIX_PORT  65536


#define MATRIX_IP_G 4
#define MATRIX_IP_H  256
#define MATRIX_PORT_H 1024

#define GOOD_NUMBER if(ret>=2) return ret
#define GOOD_RES ret>=2
#define UNTOUCHED_NUMBER return UNTOUCHED

 pthread_t g_checkbuffer_tid;

LIST_HEAD(ctrl_function_list);
static struct hlist_head g_core_pro_portt[MATRIX_PORT] ;//protocol tree of tcp
static struct hlist_head g_core_pro_portu[MATRIX_PORT];//protocol tree of udp
static struct hlist_head g_core_pro_ip[MATRIX_IP]     ;//tree of ip


//static struct hlist_head g_core_matrix_t[MATRIX_IP_H*MATRIX_PORT_H];//buffer link save
//static struct hlist_head g_core_matrix_u[MATRIX_IP_H*MATRIX_PORT_H];//buffer link save
static struct hlist_head g_core_matrix_t[MATRIX_IP_G][MATRIX_IP_H][MATRIX_PORT_H];//buffer link save
static struct hlist_head g_core_matrix_u[MATRIX_IP_G][MATRIX_IP_H][MATRIX_PORT_H];//buffer link save

static pthread_mutex_t g_list_mutex[MATRIX_IP_G][MATRIX_IP_H];
#define LOCK_LOCK(i,j)  pthread_mutex_lock(&g_list_mutex[i][j])
#define LOCK_UNLOCK(i,j)  pthread_mutex_unlock(&g_list_mutex[i][j]) 


#define get_core_matrix_hash(innerip, innerport) (((innerip>>16)%MATRIX_IP_H)  * ( innerport%MATRIX_PORT_H))
#define get_core_matrix_hash_ip(innerip) (innerip>>24)
#define get_core_matrix_hash_gip(innerip) (((innerip>>16)& 0x00ff)%MATRIX_IP_G) 
#define get_core_matrix_hash_port(port) (port%MATRIX_PORT_H)

#define MAX_BYTE_COUNT 2
#define MAX_BYTE_CORE 12





struct core_matrix
{
//
	struct hlist_node hashnode;
	u32 ip;
	u16 port;
	u16 reverse;
	u32 proid;
	u32 time;
	//
	u16 sensitive_count;
	//
	u16 count;
	//log the byte
	u8   byte_res[MAX_BYTE_COUNT][MAX_BYTE_CORE];
};






struct module_list
{
    struct list_head list_node;
    char module_name[32];	
    void* handle;
};


struct core_ip
{
	struct hlist_node hashnode;
	unsigned int proid;
	unsigned int outerip;
};

struct core_port_tu
{
	struct hlist_node hashnode;
	unsigned short outerport;  
	unsigned char dir;
	unsigned char reserve;//优先级别
	func_pro function;
	const char * module_name;

	unsigned int outterip;
	unsigned int proid_ftp_sip_h323;//连接跟踪标志

	
};

void analyse_clear_buffer_2();



static void _add_core_ip(const unsigned int ip,const unsigned int proid)
{
	struct core_ip* n = (struct core_ip*)malloc(sizeof(struct core_ip));
	if(n!=NULL)
	{
		n->outerip = ip;
		n->proid = proid;
		hlist_add_head(&n->hashnode,&g_core_pro_ip[get_ip_hash(ip)]);
	}
}



static void _add_module_init()
{
	INIT_LIST_HEAD(&ctrl_function_list);
}




static inline int _add_core_tu_priority(struct core_port_tu* n, struct hlist_head* head)
{
   	const struct core_port_tu * coretu = NULL;
	unsigned int ret = 0;
	struct hlist_node  *pos;
	struct hlist_node  *last;
	hlist_for_each(pos,head)
	{
		coretu = (struct core_port_tu*)hlist_entry(pos, struct core_port_tu, hashnode);
	       //printf("start to analyse in core_iterate the coretu->dir is %d and the package dir is %d\n", coretu->dir, dir);
	       	
		if(n->reserve<=coretu->reserve)
		{
			   hlist_add_before(&n->hashnode, pos);
			   return 0;
		}
		last = pos;
      	}
       hlist_add_after(last,&n->hashnode);
	return 0;
}

static int _add_core_tu(const char * module_name,func_pro func, unsigned char dir,unsigned char  pri,unsigned char protype, unsigned short outerport)
{
	struct core_port_tu* n = malloc(sizeof(struct core_port_tu));
	if(n==NULL)
		return -1;
	n->outerport = outerport;
	n->dir= dir;
	n->reserve    = pri; 
	n->function      = func;
	n->module_name = module_name;
	n->proid_ftp_sip_h323 = 0;
	n->outterip =0;
	if(protype == IP_TCP)
	{
	      struct hlist_head * sh = &g_core_pro_portt[outerport];
	       if(hlist_empty(sh))
			hlist_add_head(&n->hashnode,sh);
		else
			_add_core_tu_priority(n,sh);
		return IP_TCP;
	}
	else//IP_UDP
	{
	      struct hlist_head * sh = &g_core_pro_portu[outerport];
	       if(hlist_empty(sh))
			hlist_add_head(&n->hashnode,sh);
		else
			_add_core_tu_priority(n,sh);
		return IP_UDP;
	}
}

//在核心树上面临时加的端口要删除
static void _del_core_tu_self_define(struct hlist_head* table,unsigned int outterip,unsigned short outterport)
{
	struct hlist_node* pos ,*q;
	struct core_port_tu *ptu;
	hlist_for_each_safe(pos,q,table)
	{
		ptu = hlist_entry(pos,struct core_port_tu,hashnode);
		if(ptu->outterip == outterip)
		{
						//printf("detect the buffer time out ,delete  !\n");
	    		 hlist_del(pos);
	     		 free(ptu);
		        return;		 
		}
	}

}
//在核心协议分析树上面临时加端口
static u32 func_null(u8* dir, u8* payload,u32 plen)
{
   	return UNTOUCHED;
}
static int _add_core_tu_self_define(const char * module_name, unsigned char dir,unsigned char  pri,unsigned char protype,
	                        					unsigned int outterip,
									unsigned short outerport,unsigned int proid)
{
//因为没有写在动态库中的pri都是0，所以直接加就行了
	struct core_port_tu* n = malloc(sizeof(struct core_port_tu));
	if(n==NULL)
		return -1;
	n->outerport = outerport;
	n->dir= dir;
	n->reserve    = pri;
	n->function      = (func_pro)func_null;
	n->module_name = module_name;
       n->outterip = outterip;
	n->proid_ftp_sip_h323 = proid;
	if(protype == IP_TCP)
	{
	       //printf("add_core_rule_tu tcp port %d\n", outerport);
		hlist_add_head(&n->hashnode,&g_core_pro_portt[outerport]);
		return IP_TCP;
	}
	else//IP_UDP
	{
	       //printf("add_core_rule_tu udp port %d\n", outerport);
		hlist_add_head(&n->hashnode,&g_core_pro_portu[outerport]);
		return IP_UDP;
	}
}


static void initialize_core_matrix()
{

	int i = 0;
	int j = 0;
	int k= 0;
	_add_module_init();
	for(i =0;i<MATRIX_IP;i++)
	{
		INIT_HLIST_HEAD(&g_core_pro_ip[i]);
	}

	for(i =0;i<MATRIX_PORT;i++)
	{
		INIT_HLIST_HEAD(&g_core_pro_portu[i]);
		INIT_HLIST_HEAD(&g_core_pro_portt[i]);
	}

	for(i=0;i<MATRIX_IP_G;i++)
		for(j=0;j<MATRIX_IP_H;j++)
			for(k=0;k<MATRIX_PORT_H;k++)
			{
				INIT_HLIST_HEAD(&g_core_matrix_t[i][j][k]);
				INIT_HLIST_HEAD(&g_core_matrix_u[i][j][k]);
			}


}


static void uninitialize_core_matrix()
{
	int i =0;
	int j = 0;
	int k=0;
	struct hlist_node* pos ,*q;
	struct core_ip *n;
	struct core_port_tu *ptu;
       struct core_matrix *matrix;
	struct list_head * pos_module;
	struct module_list* ml;
	list_for_each(pos_module, &ctrl_function_list)
	{
		ml=list_entry(pos_module,struct module_list, list_node);
	 	list_del(pos_module);
		free(ml);
	}
 	for(i =0;i<MATRIX_IP;i++)
 	{
		hlist_for_each_safe(pos,q,&g_core_pro_ip[i])
		{
			n= hlist_entry(pos,struct core_ip ,hashnode);
			hlist_del(pos);
			free(n);
		}
 	}

	pos = NULL;
	q = NULL;
	for(i =0;i<MATRIX_PORT;i++)
 	{
		hlist_for_each_safe(pos,q,&g_core_pro_portt[i])
		{
			ptu= hlist_entry(pos,struct core_port_tu , hashnode);
			hlist_del(pos);
			free(ptu);
		}
 	}
	
	pos = NULL;
	q = NULL;
	for(i =0;i<MATRIX_PORT;i++)
 	{
		hlist_for_each_safe(pos,q,&g_core_pro_portu[i])
		{
			ptu= hlist_entry(pos,struct core_port_tu , hashnode);
			hlist_del(pos);
			free(ptu);
		}
 	}
	pos = NULL;
	q = NULL;
	for(i=0;i<MATRIX_IP_G;i++)
		for(j=0;j<MATRIX_IP_H;j++)
			for(k=0;k<MATRIX_PORT_H;k++)
			{
				hlist_for_each_safe(pos,q,&g_core_matrix_t[i][j][k])
				{
					matrix = hlist_entry(pos,struct core_matrix,hashnode);
					hlist_del(pos);
					free(matrix);
				}
			}
	pos = NULL;
	q = NULL;
	for(i=0;i<MATRIX_IP_G;i++)
		for(j=0;j<MATRIX_IP_H;j++)
			for(k=0;k<MATRIX_PORT_H;k++)
			{
				hlist_for_each_safe(pos,q,&g_core_matrix_u[i][j][k])
				{
					matrix = hlist_entry(pos,struct core_matrix,hashnode);
					hlist_del(pos);
					free(matrix);
				}
			}
		
}





static inline u32 core_iterate_ip(u32 ip,   u32 id)
{
	struct hlist_node * pos=NULL;
	struct core_ip * n=NULL;
	hlist_for_each(pos, &g_core_pro_ip[id]) 
	{
		n = (struct core_ip*)hlist_entry(pos, struct core_ip, hashnode);
		
		if (n->outerip == ip) 
		{
			return n->proid ;
		}
	}
       return UNTOUCHED;
}





//in the matrix ,we will find the 
static inline u32 core_iterate( struct hlist_head* table, u8 dir,u8* payload, u32 plen,u32 outterip, u16 outerport)
{
	const struct core_port_tu * coretu = NULL;
	unsigned int ret = 0;
	struct hlist_node  *pos;
      //printf("start to tree analyse id port is :%d\n",__nhs(outerport));
	hlist_for_each(pos,table)
	{
		coretu = (struct core_port_tu*)hlist_entry(pos, struct core_port_tu, hashnode);
	       //printf("start to analyse in core_iterate the coretu->dir is %d and the package dir is %d\n", coretu->dir, dir);
	       	
		if(coretu->dir== DIR_IN||coretu->dir== dir )
		{
		       //printf("start to analyse in core_iterate name :%s!\n", coretu->module_name);
		       
			ret = coretu->function(dir,payload,plen);
			if(GOOD_RES)
				return ret;
		}
              if( outterip == coretu->outterip)
              {
	                return coretu->proid_ftp_sip_h323;
              }
	
	}
       return UNTOUCHED;
}


//skypy and other must be find here 
static inline u32 analyse_in_matrix_action(struct core_matrix* cm,u32 timer,u32 plen)
{
#define MAX_PER_COUNT 1000 
	int i  = 0;
	int n = 0;
	cm->count++;
	if(plen>=MAX_PER_COUNT)
	{
		cm->sensitive_count++;
	}
	if(cm->count>MAX_BYTE_COUNT)
	{
		if(cm->sensitive_count*2>=cm->count)
		{
			cm->proid = PRO_P2P_LARGE;
			return PRO_P2P_LARGE;
		}
	}
	return NO_MATCH_YET;

}

static inline struct core_matrix * analyse_in_matrix_search(struct hlist_head* table, u32 innerip,u16 innerport,u32 plen)
{
	struct hlist_node * pos=NULL;
	struct hlist_node * n=NULL;
	struct core_matrix* cm = NULL;
	//printf("start to analyse cache !\n");
	hlist_for_each(pos,table)
	{
		cm = hlist_entry(pos,struct core_matrix,hashnode);
		//printf("the save cache ip is %d.%d.%d.%d - > %d\n",NIPQUAD(cm->ip),__nhs(cm->port) );
		if((cm->ip==innerip) && (cm->port == innerport))
		{
		     //printf("buffer ip %d.%d.%d.%d ->innerport %d hit the cache\n",NIPQUAD(innerip),__nhs(innerport));
		      return cm;
		}
	}
     //printf("buffer ip %d.%d.%d.%d ->innerport %d not hit the cache\n",NIPQUAD(innerip),__nhs(innerport));
	return NULL;
}




static inline u32 analyse_in_tree_matrix_1(struct hlist_head* table,u32 outerip,u16 outerport,u8 dir,u8* payload, u32 plen)
{


	u32 ret                   = UNTOUCHED ;
	//if the ip address can be recongnized ,we can find in ip matrix
	ret = core_iterate_ip(outerip,get_ip_hash(outerip));
       GOOD_NUMBER;
       ret = core_iterate(table,dir,payload,plen,outerip,outerport);
	GOOD_NUMBER;

	UNTOUCHED_NUMBER;

}

static inline u32 analyse_in_tree_matrix_2(struct hlist_head * table, u8 dir,u8* payload,u32 plen)
{
       u32 ret = core_iterate(table,dir,payload,plen,(u32)-1,0);
	GOOD_NUMBER;
	UNTOUCHED_NUMBER;
}

//create node for link buffer in matrix
static inline struct core_matrix* create_mem_for_matrix(struct hlist_head* table,u32 innerip, u16 innerport, u32 proid,u32 timer)
{
		struct core_matrix * ma = malloc(sizeof(struct core_matrix));
		ma->ip = innerip;
		ma->port = innerport;
		ma->proid = proid;
		ma->time  = timer;
		ma->reverse = 0;
		ma->sensitive_count=0;	
		ma->count = 1;
		hlist_add_head(&ma->hashnode, table);
		return ma;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//initialize the module link table



static void add_module_tolist(void *handle, const char *name)
{
	struct module_list * tmp;
	struct list_head *pos = NULL;
       char * sname;
       pos = NULL;
	tmp = NULL;
	struct module_list * new_mo = malloc(sizeof(struct module_list ));
	if(new_mo!=NULL)
	{
                new_mo->handle = handle;
		  sname = new_mo->module_name;
		  strncpy(sname,name,(size_t)31);
       	  list_add_1(&new_mo->list_node,&ctrl_function_list);//now it is install at the end;
	}	
}



//delete all module
void trunc_module()
{
	struct list_head *pos = NULL;
	struct list_head *q   = NULL; 
	struct module_list * tmp;
	list_for_each_safe(pos, q, &ctrl_function_list)
	{
		tmp= list_entry(pos, struct module_list, list_node);
              dlclose(tmp->handle);
		list_del(pos);
		free(tmp);
	}
}



// this is for analyse protocol module in here ,not in dll 
int load_ip_in_self()
{
    u32 ip;
    u32 pro_id =  get_first_ip(&ip);
    	 
    while(pro_id!=0)
    {
             _add_core_ip(ip, pro_id);
    		pro_id = get_next_ip(&ip);
    }
}


int load_ip(void * handle , const struct module_register*  _register)
{
	func_pro_ip main_ip;
	u32 ipcount;
	u8* ipstart = NULL;
	int i = 0;
	char * error;
	unsigned int ip;
	unsigned int proid;
	main_ip = (func_pro_ip)dlsym(handle,_register->func_name);
	if((error=dlerror())!=NULL)
	{
		fprintf(stderr,"%s\n",error);
       	return -1; 
	}
	if(main_ip!=NULL)
	{
             ipstart = main_ip(&ipcount); 
	      if(ipstart == NULL || ipcount <= 0)
		  	return -1;
	      for(i=0;i<ipcount;i++)
	      	{
	      		ip =  *((u32*)(ipstart));
			proid = *((u32*)(ipstart+4));	
			//transfer network ip order,so we do not need the htonl
			_add_core_ip(ip, proid);
			ipstart = ipstart+8;
	      	}
	}
	
	return 0;
}
int load_ip_group_in_self()
{
//58.61.39.208 -58.61.39.231 
//61.183.55.213-61.183.55.225 
//封这两段它就找不到候选资源了
/*      if(hip<=977086439 && hip>=977086416)
	  	return PRO_XUNLEI;
	if(hip<= 1035417569 && hip>=1035417557)
		return PRO_XUNLEI;*/
	u32 i ;
	for(i = 977086416u;i<=977086439u;i++)
	{
		_add_core_ip(i,PRO_XUNLEI);	
	}
	for(i=1035417557u;i<1035417569u;i++)
	{
		_add_core_ip(i,PRO_XUNLEI );
	}
}
int load_ip_group(void * handle , const struct module_register*  _register)
{
	func_pro_ipgroup main_ipg;
	char * error;
	unsigned int  ipf;
	unsigned int  ipt;
	unsigned int  proid;
	main_ipg = (func_pro_ipgroup)dlsym(handle,_register->func_name);
	if((error=dlerror())!=NULL)
	{
		fprintf(stderr,"%s\n",error);
       	return -1; 
	}
	if(main_ipg!=NULL)
	{
	//this is the group
     	    proid =main_ipg(&ipf,&ipt);
	    for(;ipf<= ipt;ipf++)
			_add_core_ip(__hnl(ipf), proid);
	}
	
	return 0;
}

int load_rule_in_self()
{
	//port is the host order byte
	int i,func_num = 0;
	const struct module_register *mr = (const struct module_register *)get_module_info_http80(&func_num);
       for(i =0;i<func_num;i++)
       {
		if(_add_core_tu((const char*)mr[i].func_name,mr[i].function,mr[i].func_dir,mr[i].func_pri,mr[i].func_protype,__hns(mr[i].func_port))==-1)
		{
			fprintf(stderr,"error add module function:%d\n",__hns(mr[i].func_port));
		}
       }   

	mr =  (const struct module_register *)get_module_info_game(&func_num);
       for(i =0;i<func_num;i++)
       {
		if(_add_core_tu((const char*)mr[i].func_name,mr[i].function,mr[i].func_dir,mr[i].func_pri,mr[i].func_protype,__hns(mr[i].func_port))==-1)
		{
			fprintf(stderr,"error add module function:%s\n","load_rule_in_module");
		}
       }

	mr =  (const struct module_register *)get_module_info_p2p(&func_num);
       for(i =0;i<func_num;i++)
       {
       
		if(_add_core_tu((const char*)mr[i].func_name,mr[i].function,mr[i].func_dir,mr[i].func_pri,mr[i].func_protype,__hns(mr[i].func_port))==-1)
		{
			fprintf(stderr,"error add module function:%s\n","load_rule_in_module");
		}
       }



	mr =  (const struct module_register *)get_module_info_im(&func_num);
       for(i =0;i<func_num;i++)
       {
		if(_add_core_tu((const char*)mr[i].func_name,mr[i].function,mr[i].func_dir,mr[i].func_pri,mr[i].func_protype,__hns(mr[i].func_port))==-1)
		{
			fprintf(stderr,"error add module function:%s\n","load_rule_in_module");
		}
		
       }   

	mr =  (const struct module_register *)get_module_info_stock(&func_num);
       for(i =0;i<func_num;i++)
       {
       
		if(_add_core_tu((const char*)mr[i].func_name,mr[i].function,mr[i].func_dir,mr[i].func_pri,mr[i].func_protype,__hns(mr[i].func_port))==-1)
		{
			fprintf(stderr,"error add module function:%s\n","load_rule_in_module");
		}
       }


	mr = (const struct module_register *)get_module_info_other(&func_num);
       for(i =0;i<func_num;i++)
       {
       
		if(_add_core_tu((const char*)mr[i].func_name,mr[i].function,mr[i].func_dir,mr[i].func_pri,mr[i].func_protype,__hns(mr[i].func_port))==-1)
		{
			fprintf(stderr,"error add module function:%s\n","load_rule_in_module");
		}
       }   
	   
	   
	
}
int load_rule(void * handle , const struct module_register*  _register)
{
  	func_pro main_1;
	main_1 = (func_pro)dlsym(handle,_register->func_name);
	char * error ;
	if((error=dlerror())!=NULL)
	{
		//dlclose(handle);
		fprintf(stderr,"%s\n",error);
       	return -1; 
	}
	if(main_1!=NULL)
	{
//	      printf("load rule start\n");
	       if(_add_core_tu((const char*)_register->func_name,main_1,_register->func_dir,
		   					    _register->func_pri,
		   					    _register->func_protype,
		   					    __hns(_register->func_port))==-1)
       	{
	
			fprintf(stderr,"error add module function:%s\n",_register->func_name);
       	}
//	      printf("load rule end\n");
	}
}
int load_module(const char* module_name)
{
	void* handle;
  	char *error;
   	int i = 0;
 	struct module_register*  _register; 
 	const struct module_register * reg;
	f_register_mod_func register_mod_func;
	struct list_head *pos = NULL;
	struct module_list * tmp;

/////////////////////////////////////////////////

//
/* linux directory can not hold the same filename ,so we do not need this code now
   list_for_each(pos,&ctrl_function_list)
	{
	     tmp = list_entry(pos,struct module_list,list_node);
	     if(strcmp(tmp->module_name,module_name)==0)
	     {	
			fprintf(stderr,"error has same module!\n");
			return -1;
	     }
	} 
*/	
/////////////////////////////////////////////////
   handle =dlopen(module_name,RTLD_NOW);
   if(!handle)
	{
		fprintf(stderr,"%s\n",dlerror());
		return -1;			
	}	

	//
	add_module_tolist(handle,module_name);
	dlerror();

   register_mod_func = (f_register_mod_func)dlsym(handle,"register_mod");
	if((error=dlerror())!=NULL)
   	{
		dlclose(handle);
		fprintf(stderr,"%s\n",error);
              return -1; 
	   		
   	}
       else
       {
       	int nfun = register_mod_func(&_register);
	      if(_register==NULL)
	       {
	       	fprintf(stderr,"this is error for register!\n");
			return -1;
	       }
		
		for(i = 0;i<nfun;i++)
		{
		   dlerror();
		   reg = &_register[i];
			switch(reg->func_type)
			{
				case FUNC_IP:
					load_ip(handle,reg);
					break;
				case FUNC_IP_GROUP:
					load_ip_group(handle, reg);
					break;
				case FUNC_RULE:
					load_rule(handle, reg);
					break;
			}


		}
		if(nfun<0)
		{
	      fprintf(stderr,"this is error for register of func number!\n");
			dlclose(handle);
			return -1;
		}
		   
       }


	return 1;

}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//pay attention to this , the plen must the application data 's length
/*unsigned int analyse_protocol(u32 innerip,u16 innerport,u32 outerip,u16 outerport,u8 protype,u8 dir, u8*payload, u32 plen,u32 timer)
{
//这里没有加这种情况，多次判决之后仍然没有决断出来，就放弃，这里是每个包必须判断，需要修改!
	u8 hash_gip = get_core_matrix_hash_gip(innerip);
	u16 hash_ip = get_core_matrix_hash_ip(innerip);
	u16 hash_port = get_core_matrix_hash_port(innerport);
	
	//printf("start the hash is %d,%d,%d\n", hash_gip,hash_ip,hash_port);
	struct core_matrix* cm_buffer;
	struct hlist_head * head_buffer_link ;
	struct hlist_head * head_pro_tree;
       struct hlist_head * head_pro_tree_0;
	u32 ret = 0;
	switch(protype)
	{
	   case  IP_TCP:
		head_buffer_link   = &g_core_matrix_t[hash_gip][hash_ip][hash_port];
		head_pro_tree	      = &g_core_pro_portt[outerport];
		head_pro_tree_0   = &g_core_pro_portt[0];
	       break;
	   case IP_UDP:
		head_buffer_link   = &g_core_matrix_u[hash_gip][hash_ip][hash_port];
		head_pro_tree       = &g_core_pro_portu[outerport];
		head_pro_tree_0   = &g_core_pro_portu[0];
	      break;
	}
	//the first step , is to find the source ip source port in matrix cache buffer
	//here must lock 
	LOCK_LOCK(hash_gip,hash_ip);
	cm_buffer = analyse_in_matrix_search(head_buffer_link,innerip,innerport,plen);
	if(cm_buffer!=NULL)
	{
	//hit the cache 
	      cm_buffer->time = timer;
		ret = cm_buffer->proid;   
		if(GOOD_RES)
			goto GOOD_RES_OUT;
	      if(ret==UNTOUCHED)
	      	{
	      		ret = analyse_in_matrix_action(cm_buffer,timer,plen);
	      	}
		if(GOOD_RES)
		{
		     cm_buffer->proid = ret;
			goto GOOD_RES_OUT;
		}
	}
	else
	{
	   //create the node
	   //printf("create cache for %d.%d.%d.%d->%d\n",NIPQUAD(innerip),__nhs(innerport));
		cm_buffer = create_mem_for_matrix(head_buffer_link,innerip,innerport,0,timer);
	}

	//second step : analyse with port
	//if we can't 
	
	//printf("start to analyse matrix_1 : analyse port tree tcp udp: %d\ outerport is %d \n",protype,__hns(outerport));
	ret = analyse_in_tree_matrix_1(head_pro_tree,outerip,outerport,dir,payload,plen);
	if(GOOD_RES)
	{
	       switch(ret)
	      	{
	      	    case PRO_GET:
		   // case PRO_POST:
			 cm_buffer->proid = PRO_HTTP;
			 break;
	           default:
			cm_buffer->proid = ret;//save the value
			break;
			   	
	      	}
		goto GOOD_RES_OUT;
	}
	//third step : analyse with 0 port
	//printf("start to analyse matrix_1 : analyse port tree tcp udp: %d\ outerport is 0 \n",protype,__hns(outerport));
	ret = analyse_in_tree_matrix_2(head_pro_tree_0,dir,payload,plen);
	if(GOOD_RES)
	{
		cm_buffer->proid = ret ;//save the value
		goto GOOD_RES_OUT;
	}

	
GOOD_RES_OUT:
	LOCK_UNLOCK(hash_gip,hash_ip);
	return ret;
	
}*/


u32 analyse_protocol_ftp(TPktinfo* pkt, int plen)
{
    	unsigned int ip;
	unsigned short port;
	unsigned int proid;
	proid = tcp_ftp(pkt->dir,pkt->payload,plen,&ip,&port);
	
  	if(ip!=0)
	{
	        _add_core_tu_self_define("ftp_find",pkt->dir,0,IP_TCP,ip,port,proid);
	}
	return proid;
}




u32 analyse_protocol_2(TPktinfo* pkt,u32 timer)
{
//这里没有加这种情况，多次判决之后仍然没有决断出来，就放弃，这里是每个包必须判断，需要修改!
	u8 hash_gip = get_core_matrix_hash_gip(pkt->innerip);
	u16 hash_ip = get_core_matrix_hash_ip(pkt->innerip);
	u16 hash_port = get_core_matrix_hash_port(pkt->innerport);
	u32 plen = pkt->iplen-pkt->headerlen;
	//printf("start the hash is %d,%d,%d\n", hash_gip,hash_ip,hash_port);
	struct core_matrix* cm_buffer;
	struct hlist_head * head_buffer_link ;
	struct hlist_head * head_pro_tree;
       struct hlist_head * head_pro_tree_0;
	u32 ret = 0;
	switch(pkt->protype)
	{
	   case  IP_TCP:
		head_buffer_link   = &g_core_matrix_t[hash_gip][hash_ip][hash_port];
		head_pro_tree	      = &g_core_pro_portt[pkt->outerport];
		head_pro_tree_0   = &g_core_pro_portt[0];
	       break;
	   case IP_UDP:
		head_buffer_link   = &g_core_matrix_u[hash_gip][hash_ip][hash_port];
		head_pro_tree       = &g_core_pro_portu[pkt->outerport];
		head_pro_tree_0   = &g_core_pro_portu[0];
	      break;
	}
	//the first step , is to find the source ip source port in matrix cache buffer
	//here must lock 
	LOCK_LOCK(hash_gip,hash_ip);
	cm_buffer = analyse_in_matrix_search(head_buffer_link,pkt->innerip,pkt->innerport,plen);
	if(cm_buffer!=NULL)
	{
	//hit the cache 在缓存系统中
	    cm_buffer->time = timer;
		ret = cm_buffer->proid;   
        switch(ret)
        {   
            case 0:
            case 1:
                if(__nhs(pkt->outerport)>=1024)//TCP/IP reserved port range
                {
	      		    ret = analyse_in_matrix_action(cm_buffer,timer,plen);
                }
                break;
            case PRO_GET:
                switch(pkt->payload[0])
                {
                    case 'P':
                        ret = PRO_POST;
                        break;
                    case 'G':
                        ret = PRO_GET; 
                        break;
                    default:
                        ret = PRO_HTTP;
                        break;
                }                 
                goto GOOD_RES_OUT;
                break;            
            default:
                goto GOOD_RES_OUT;
                break;
        }
		if(GOOD_RES)
		{
		     cm_buffer->proid = ret;
			goto GOOD_RES_OUT;
		}
	}
	else
	{
	   //create the node
	   //printf("create cache for %d.%d.%d.%d->%d\n",NIPQUAD(innerip),__nhs(innerport));
		cm_buffer = create_mem_for_matrix(head_buffer_link,pkt->innerip,pkt->innerport,0,timer);
	}

	//second step : analyse with port
	//if we can't 
	
	//printf("start to analyse matrix_1 : analyse port tree tcp udp: %d\ outerport is %d \n",protype,__hns(outerport));
	ret = analyse_in_tree_matrix_1(head_pro_tree,pkt->outerip,pkt->outerport,pkt->dir,pkt->payload,plen);
	if(GOOD_RES)
	{
	        
		cm_buffer->proid = ret;//save the value
              switch(ret)
              {
              	   case PRO_FTP_FILE_DOWN:
			   case PRO_FTP_FILE_UP:
			   	_del_core_tu_self_define(head_pro_tree, pkt->outerip,pkt->outerport);
			   	break;
              }
		goto GOOD_RES_OUT;
	}
	//third step : analyse with 0 port
	//printf("start to analyse matrix_1 : analyse port tree tcp udp: %d\ outerport is 0 \n",protype,__hns(outerport));
	ret = analyse_in_tree_matrix_2(head_pro_tree_0,pkt->dir,pkt->payload,plen);
	if(GOOD_RES)
	{
		cm_buffer->proid = ret ;//save the value
		goto GOOD_RES_OUT;
	}

	
GOOD_RES_OUT:
	switch(ret)
	{
	     case PRO_FTP:
              ret = analyse_protocol_ftp(pkt,plen);
	 	break;
	  
	}	

	LOCK_UNLOCK(hash_gip,hash_ip);
	return ret;
	
}

//
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////


static void *thr_fn(void *arg)
{
	 while(1)
	 	{
			analyse_clear_buffer_2();
			sleep(1);
	 	}
  	return ((void *)0);
}
void initialize_lock_thread()
{
//static pthread_mutex_t g_list_mutex[MATRIX_IP_G][MATRIX_IP_H];
      int i ,j;
      int err;
	for(i=0;i<MATRIX_IP_G;i++)
	{
		for(j=0;j<MATRIX_IP_H;j++)
		{
			if(0 != pthread_mutex_init(&(g_list_mutex[i][j]),NULL))
			{
				fprintf(stderr,"init core_engine.c mutex error \n");
			}
		}		
	}
	err = pthread_create(&g_checkbuffer_tid,NULL,thr_fn,NULL);
	if(err!=0)
	{
		fprintf(stderr,"error create thread in core_engine.c\n");
	}
}

void analyse_clear_buffer_2()
{
	static int flag  = 0;
	flag = !flag;
	
	struct hlist_head * link_table;
	struct hlist_node * pos=NULL;
	struct hlist_node * n=NULL;
	static struct core_matrix* cm = NULL; 
	int i,j,k ;
/*	switch(flag)
	{
	    case 0:
		link_table = g_core_matrix_t[0][0][0];
		break;
	    case 1:
		link_table = g_core_matrix_u[0][0][0];
		break;
	}*/
        for(i = 0;i<MATRIX_IP_G;i++)
	 for(j=0;j<MATRIX_IP_H;j++)
	 {
		u32 tt_t = g_ptm->curtime;
       	 for(k=0;k<MATRIX_PORT_H;k++)
              {
              	pos = NULL;n = NULL;
			struct hlist_head * h ;	
			if(flag == 0)
				 h = &g_core_matrix_t[i][j][k];	
			else
				h = &g_core_matrix_u[i][j][k];
			hlist_for_each_safe(pos,n,h)
			{
       			LOCK_LOCK(i,j); //this is for the littre or middle network , not for the big network,or let it out here!!! qianbo 2010-5-27
				cm = hlist_entry(pos,struct core_matrix,hashnode);
				if(tt_t - cm->time >30)
				{
					//printf("detect the buffer time out ,delete  !\n");
			    		 hlist_del(pos);
			     		 free(cm);
				}
		       	LOCK_UNLOCK(i,j);
			}
       	 }
	       usleep(15000);
	 }
}



//this timer is not right,if we use it , we must modify it!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//30seconds
//atention : this is for the main test function ,
/*
static void analyse_clear_buffer(u8 ipg , u16 ip)
{
	static int tcp_udp = IP_TCP;
	struct hlist_head * link_table_t;
	struct hlist_head * link_table_u;
	struct hlist_node * pos=NULL;
	struct hlist_node * n=NULL;
	static struct core_matrix* cm = NULL; 
	int i ;
	
       for(i =0;i<MATRIX_PORT_H;i++)
      {
             pos = NULL;n = NULL;
		link_table_t = 	&g_core_matrix_t[ipg][ip][i];
		link_table_u = &g_core_matrix_u[ipg][ip][i];
		hlist_for_each_safe(pos,n,link_table_t)
		{
			cm = hlist_entry(pos,struct core_matrix,hashnode);
       		hlist_del(pos);
			free(cm);
		}
		hlist_for_each_safe(pos,n,link_table_u)
		{
			cm = hlist_entry(pos,struct core_matrix,hashnode);
       		hlist_del(pos);
			free(cm);
		}
      }
}
void analyse_clear_buffer_all(timer)
{
      int i,j;
	for(i=0;i<MATRIX_IP_G;i++)
	{ 

		for(j=0;j<MATRIX_IP_H;j++)
		{	   
			analyse_clear_buffer(i,j,timer);
		}
	}
}
*/
#if 0
 u32 test( )
{
	const struct core_port_tu * coretu = NULL;
	unsigned int ret = 0;
	int i=20480;
	struct hlist_node  *pos=NULL;
      char *payload = "GET 1234567";
      struct hlist_head *table = NULL;
      printf("start to test \n");

     for(i = 0;i<65536;i++)
     	{
	      table = (struct hlist_head*)&g_core_pro_portt[i];
		hlist_for_each(pos,table)
		{
			      printf("test start now ! the port is %d\n",__hns(i));
				coretu = (struct core_port_tu*)hlist_entry(pos, struct core_port_tu, hashnode);
			       printf("start to analyse in core_iterate the coretu->dir is %d \n", coretu->dir);
				{
				       printf("start to analyse in core_iterate!\n");
					ret = coretu->function(0,payload,11);
					if(ret >=3)
					{
						   printf("the ret is %d --------------\n",ret);
					}
				}
		}

	      table = (struct hlist_head*)&g_core_pro_portu[i];
		hlist_for_each(pos,table)
		{
			      printf("test start now ! the port is %d\n",__hns(i));
				coretu = (struct core_port_tu*)hlist_entry(pos, struct core_port_tu, hashnode);
			       printf("start to analyse in core_iterate the coretu->dir is %d \n", coretu->dir);
				{
				       printf("start to analyse in core_iterate!\n");
					ret = coretu->function(0,payload,11);
					if(ret >=3)
					{
						   printf("the ret is %d --------------\n",ret);
					}
				}
		}
		
     	}
	printf("test end\n");
       return UNTOUCHED;
}
#endif
void analyse_initialize()
{	
      initialize_core_matrix();
      load_ip_in_self();
      load_ip_group_in_self();
      load_rule_in_self();

	initialize_lock_thread();
	//test();
	//from 2 to 3 version
	//this func is prohebited by now!
//	printf("start to read dllcache!\n");
	read_module_config_3(load_module);
//	printf("end to read dllcache!\n");
}
void analyse_uninitialize()
{
	uninitialize_core_matrix();
	trunc_module();
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////

//int load_ip_in_module(u32 start, pro_id);
//int load_ip_group_in_module(u32* ip_pro_start,u32 proid,u32 num);
//int load_rule_in_module(func_pro func, u8 dir, u8 pri, u32 proid, u16 port);
//now we must load the function here






////////////////////////////////////////////////////////////////////////////////////////////////////////////

#if 0
static void __add_core_ip(void)
{

	/*_add_core_ip((u32)2439782106,(u32)PRO_SINA_IGAME);//218.30.108.145
	_add_core_ip((u32)3559341370,(u32)PRO_XUNLEI);//58.61.39.212
	_add_core_ip((u32)3509009722,(u32)PRO_XUNLEI);//58.61.39.209
	_add_core_ip((u32)3542564154,(u32)PRO_XUNLEI);//58.61.39.211
	_add_core_ip((u32)269754074,(u32)PRO_YUANHANG);//218.30.20.16
	_add_core_ip((u32)439626206,(u32)PRO_YUANHANG);//222.41.52.26
	_add_core_ip((u32)622075610,(u32)PRO_YUANHANG);//218.30.20.37
	_add_core_ip((u32)655630042,(u32)PRO_YUANHANG);//218.30.20.39*/
}

#endif 
#if 0
#define GET_UP_INNER_IP(data)		(*(__u32*)(data+12))
#define GET_UP_OUTTER_IP(data)        (*(__u32*)(data+16))

#define GET_DOWN_INNER_IP(data)     (*(__u32*)(data+16))
#define GET_DOWN_OUTTER_IP(data)     (*(__u32*)(data+12))


#define GET_UP_INNER_PORT(ihl,data)   (data[ihl]*256+data[ihl+1])
#define GET_UP_OUTTER_PORT(ihl,data)  (data[ihl+2]*256+data[ihl+3])

#define GET_DOWN_INNER_PORT(ihl,data)  GET_UP_OUTTER_PORT(ihl,data)
#define GET_DOWN_OUTTER_PORT(ihl,data) GET_UP_INNER_PORT(ihl,data)
static inline int get_ip_pack_h_len(const unsigned char *data)
{
	return 4*(data[0] & 0x0f);
}

static inline void get_conntrack_info(const unsigned char *data, 
									 char protocol ,
									 int up_down,
									 unsigned int * pinner_ip,
									 unsigned short* pinner_port,
									 unsigned int * poutter_ip,
									 unsigned short * poutter_port
									 ) 
{

	int ip_hl = get_ip_pack_h_len(data);//get the ip package length

	if(up_down == DIR_UP)
	{
		*pinner_ip    = GET_UP_INNER_IP(data);
		*poutter_ip   = GET_UP_OUTTER_IP(data);
		*pinner_port  = GET_UP_INNER_PORT(ip_hl,data);
		*poutter_port = GET_UP_OUTTER_PORT(ip_hl,data);
	}
	else
	{
		*pinner_ip    = GET_DOWN_INNER_IP(data);
		*poutter_ip   = GET_DOWN_OUTTER_IP(data);
		*pinner_port  = GET_DOWN_INNER_PORT(ip_hl,data);
		*poutter_port = GET_DOWN_OUTTER_PORT(ip_hl,data);
	}
}

static inline int app_data_offset(const unsigned char *data,char ip_protocol)
{
	int ip_hl = get_ip_pack_h_len(data);

	if(ip_protocol == IPPROTO_TCP){
		// 12 == offset into TCP header for the header length field.
		int tcp_hl = 4*(data[ip_hl + 12]>>4);
		return ip_hl + tcp_hl;
	}
	else if(ip_protocol == IPPROTO_UDP)//udp
		return ip_hl+8;
	else //we can not regonize the package is tcp or udp
		return ip_hl+8;
}
#endif 

