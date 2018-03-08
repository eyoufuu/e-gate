
#ifndef __KERNEL__
#define __KERNEL__
#endif

#ifndef MODULE
#define MODULE
#endif

#include <linux/module.h>
#include <linux/kernel.h>
#include <linux/proc_fs.h>
#include <linux/string.h>
#include <linux/vmalloc.h>
#include <linux/byteorder/generic.h>
#include <asm/uaccess.h>
//#include "arp_list.h"
#include "arp_hook.h"

MODULE_LICENSE("GPL");
MODULE_DESCRIPTION("PROC FILE IPMAC");
MODULE_AUTHOR("qianbo-0423");



static struct proc_dir_entry *proc_entry;
static struct proc_dir_entry *proc_entry_control;
static struct proc_dir_entry *proc_arp_number;

//这里也是0断1开 0：不启用 1：启用
//ip mac -bind function 
//ip mac -bind function 


///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
/*
unsigned int get_ip_netadd(char *ip)
{
	 int i = 0;
	 char buf[8];
	 char *tmp = (char*)ip;
	 u32 uip = 0;
	 int n;

	 while(1)
	 {

		 if(*tmp != '.')
		 {
			 tmp++;
			 if(*tmp != '\0')
			 {
                 continue;
			 }
		 }
         n = (int)(tmp-ip);

		 memcpy(buf,ip,n);
		 buf[n]='\0';

		 if(*tmp!='\0')
			ip = ++tmp;
		 uip += simple_strtoul(buf,NULL,10)<<(i*8);
		 i++;
		 if(i==4 || *tmp=='\0' )
			 break;
	 }
	 if(i!=4)
	   return 0;
	 return uip;
}

void swap(char *a ,char *b)
{
   char s;
   s = *a ;
   *a = *b;
   *b = s;
}*/
u32 get_ip_netadd2(const char * ip)
{
   u32 uip = simple_strtoul(ip,NULL,10);
   return htonl(uip);
  /* char * a0 = ((u8*)&ip)[0];
   char * a1 = ((u8*)&ip)[1];
   char * a2 = ((u8*)&ip)[2];
   char * a3 = ((u8*)&ip)[3];
   swap(a0,a3);
   swap(a1,a2)   */
}
/*

int get_mac_netadd(char *mac, char* copymac)
{
   char *tmp = mac;
   char buf[3];   
   int i = 0;
   int n =0;
   while(1)
   {
      if(*tmp!=':')
	  {
	     tmp++;
		 if(*tmp !='\0')
		    continue;
	  }
	  n = (int)(tmp-mac);
	  if(n!=2)
	     return -1;
	  memcpy(buf,mac,2);
	  buf[2]='\0';
	  copymac[i]= (char)simple_strtoul(buf,NULL,16);//输入的是16
	  i++;
	  if(*tmp!='\0')
	     mac =++tmp;
	  if(i==6 || (*tmp) == '\0')
        break;	  
	}
    return 0;
}
*/
int check_mac(char value)
{

	int ret = -1;
	  if(value<='9' && value>='0')
	  {
		  ret = 1;
		  goto OK;
	  }
	  if('a'<=value && value<='f')
	  {
		  ret = 1;
		  goto OK;
	  }
	  if('A'<=value && value<='F')
	  {
		  ret = 1;
		  goto OK;
	  }
OK:
	  return ret;
}

int get_mac_netadd2(char *mac, char* copymac)
{
	  int i = 0;
	  char buf[3];
	  for(i=0;i<6;i++)
	  {
		  memcpy(buf,mac+i*3,2);
		  buf[2]='\0';
		  if((check_mac(buf[0])==1) && (check_mac(buf[1])==1))
			  copymac[i] = (u8)simple_strtoul(buf,NULL,16);
		  else
			  return -1;
	  }
	  return 0;
}

//把分割符号变为'\0'
char *get_mac_pos(char *buffer,int len)
{
	int i = 0;
	char c;
	c = buffer[0];
	while(i<len)
	{
		switch(c)
		{
		  //空格或者, ;
			case ',':
			case ' ':
			case ':':
			case ';':
				buffer[i] = '\0';
				return buffer+i+1;
		    default:
                i++;			
		}
		c = buffer[i];
	}
    return 0;
}





int arp_read( char *page, char **start, off_t off,
                   int count, int *eof, void *data )
{
    return write_ip_mac_list(page);
  //return write_page_list(page);
} 



//arp_write 是为了接收用户态ip-mac绑定的消息
ssize_t arp_write( struct file *filp, const char __user *buff,
                        unsigned long len, void *data )
{
  char buffer[34];
  char * ip;
  char * mac;
  u32 uip;
  char umac[6];

  if(len>33)
     return 0;
  if (copy_from_user( &buffer[0], buff, len )) 
  {
    return -EFAULT;
  }
  buffer[len]='\0';
  ip  = &buffer[0];
  mac = get_mac_pos(&buffer[0],len);
  if(!mac)
  {
     printk(KERN_ALERT"wrong ip mac!\n");
     return len;
  }
  
  uip = get_ip_netadd2(ip);
   if(uip==0)
  {
     printk(KERN_ALERT"the ip is not right\n");
     return 0;
  }
  if(get_mac_netadd2(mac,&umac[0])==0)
  {
	 printk(KERN_ALERT"the ip is %u\n",uip);
        printk(KERN_ALERT"the mac is %d:%d:%d:%d:%d:%d\n",umac[0],umac[1],umac[2],umac[3],umac[4],umac[5]);
        printk(KERN_ALERT"the mac is %02X:%02X:%02X:%02X:%02X:%02X\n",umac[0],umac[1],umac[2],umac[3],umac[4],umac[5]);
     //正常
	 add_ipmac_node(uip,umac); 
  }
  else
  {
     printk(KERN_ALERT"the mac is not right!\n");
  }
  return len;
}
////////////////////////////////////////////////////////////////
int arp_n_read( char *page, char **start, off_t off,
                   int count, int *eof, void *data )
{
   return sprintf(page,"%llu\n",g_arp_number);
}

//////////////////////////////////////////////////
int control_read( char *page, char **start, off_t off,
                   int count, int *eof, void *data )
{
     int len;
     len = sprintf(page,"%d\n", g_control); 
	 return len;
}
ssize_t control_write( struct file *filp, const char __user *buff,
                        unsigned long len, void *data )
{
    char value;
   	if(copy_from_user(&value,buff,1))
	{
	   return -EFAULT;
	}
	else
	{
	   switch(value)
       {
	      case '0':
		  case 0:
		     g_control = 0;
		     break;
		  case '1':
		  case 1:
		     g_control = 1;
		     break;
		  case '2':
		  case 2:
		     g_control = 2;
		     break;
          case '3':
		  case 3:
		     g_control = 3;
             break;		  
		  default:
             break;		  
       }	   
	}
	printk(KERN_ALERT"receive control %d\n",value);
    return len;	
}
/////////////////////////////////////////////////
int init_arp_module( void )
{
    	int ret = 0;
   //这个hash表是为了绑定arp - ip 防止arp欺骗

    //先要初始化arp list列表缓存
	//使用printk日志系统，将自己的缓冲区放弃使用，但是保留以作后备
/*    if(init_arp_buffer_list()!=0)
	{
	   ret = -ENOMEM;
	   printk(KERN_ALERT "arplist alloc memory error\n");
	}*/
       if(init_arphook()!=0)
	{
	   ret = -ENOMEM;
	   printk(KERN_ALERT "init arphook error!\n");
	}

  
       proc_entry         = create_proc_entry("ipmac_arp", 0666,NULL);
	proc_entry_control = create_proc_entry("ipmac_arp_c",0666,NULL);
	proc_arp_number    = create_proc_entry("ipmac_arp_n",0666,NULL);
	if(proc_entry_control == NULL)
	{
	    ret = -ENOMEM;
        printk(KERN_INFO "ipmac_arp_c: Couldn't create proc entry\n");
    }
	else
	{
       proc_entry_control->read_proc  = control_read;
       proc_entry_control->write_proc = control_write;
       printk(KERN_INFO "ipmac_arp_c: Module loaded.\n");
	}
	
    if (proc_entry == NULL)
    {
        ret = -ENOMEM;
        printk(KERN_INFO "ipmac_arp: Couldn't create proc entry\n");
    } 
    else
    {
       proc_entry->read_proc = arp_read;
       proc_entry->write_proc = arp_write;
       printk(KERN_INFO "ipmac_arp: Module loaded.\n");
    }
	if(proc_arp_number == NULL)
	{
        ret = -ENOMEM;
        printk(KERN_INFO "ipmac_arp_n: Couldn't create proc entry\n");
	}
	else
	{
	   proc_arp_number->read_proc = arp_n_read;
       printk(KERN_INFO "ipmac_arp_n: load module\n");
	}
	
	return ret;
}


void cleanup_arp_module( void )
{  
  g_control = 0;
  fini_arphook();
  
 //使用printk日志系统，将自己的缓冲区放弃使用，但是保留以作后备 
 // uninit_arp_buffer_list();
  remove_proc_entry("ipmac_arp", NULL);
  remove_proc_entry("ipmac_arp_c",NULL);
  remove_proc_entry("ipmac_arp_n",NULL);

  //vfree(DEMO_buffer);
  printk(KERN_INFO "ipmac_arp: Module unloaded.\n");
}


module_init( init_arp_module );
module_exit( cleanup_arp_module );



