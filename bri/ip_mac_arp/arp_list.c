#include <linux/module.h>
#include <linux/kernel.h>
#include <linux/init.h>
#include <linux/if_arp.h>
#include <net/arp.h>
#include <linux/skbuff.h>
#include <linux/ip.h>
#include <linux/netdevice.h>
#include <linux/if_ether.h>
#include <linux/if_packet.h>
#include <linux/list.h>
#include <linux/jiffies.h>

MODULE_LICENSE("GPL");

static rwlock_t g_lock_logb_List;
#define INITLOCK_LOG rwlock_init(&g_lock_logb_List)
#define LOCK_LOG	write_lock_bh(&g_lock_logb_List)
#define UNLOCK_LOG  write_unlock_bh(&g_lock_logb_List)
#define LOCK_LOG_R 	read_lock_bh(&g_lock_logb_List)
#define UNLOCK_LOG_R read_unlock_bh(&g_lock_logb_List)



#define LIST_BUFFER_NUMBER 1000
static LIST_HEAD(arp_log_list); //这个定义所有节点的头，总共要插入1000个节点
static struct list_head* write_point; //读指针
static struct list_head* read_point;  //写指针
typedef struct _arp_pack
{
        struct list_head list;
		u8  dest[6];
		u8  source[6];
        u8  send_mac[6];
		u32 send_ip;
		u8  target_mac[6];
		u32 target_ip;
		u16 opcode;
		unsigned long stamp;
}arp_pack;

#define MAC_FMAT "%02X:%02X:%02X:%02X:%02X:%02X\n"
#define IP_FMAT "%d.%d.%d.%d\n"
#define IP_FMAT_T(addr) \
	((unsigned char *)&addr)[0], \
	((unsigned char *)&addr)[1], \
	((unsigned char *)&addr)[2], \
	((unsigned char *)&addr)[3]
#define MAC_FMAT_BUF_D(p,q) sprintf(p,MAC_FMAT, q->dest[0],q->dest[1],q->dest[2],q->dest[3],q->dest[4],q->dest[5])
#define MAC_FMAT_BUF_S(p,q) sprintf(p,MAC_FMAT,q->source[0],q->source[1],q->source[2],q->source[3],q->source[4],q->source[5])
#define MAC_FMAT_S_MAC(p,q) sprintf(p,MAC_FMAT,q->send_mac[0],q->send_mac[1],q->send_mac[2],q->send_mac[3],q->send_mac[4],q->send_mac[5])
#define MAC_FMAT_S_IP(p,q) sprintf(p,IP_FMAT,IP_FMAT_T(q->send_ip))
#define MAC_FMAT_D_MAC(p,q) sprintf(p,MAC_FMAT,q->target_mac[0],q->target_mac[0],q->target_mac[0],q->target_mac[0],q->target_mac[0],q->target_mac[0])
#define MAC_FMAT_D_IP(p,q) sprintf(p,IP_FMAT,IP_FMAT_T(q->target_ip))
#define OP_FMT_1(p) sprintf(p,"op:0\n")
#define OP_FMT_2(p) sprintf(p,"op:1\n")

/*
static inline arp_pack*  mylist_entry(struct list_head * p)
{
   return (struct arp_pack*)p;
}*/
#define mylist_entry(p) (arp_pack*)p

int write_page_list(char * page)
{
   int len =0;
   struct list_head * lnext;
   arp_pack* q_next;
   arp_pack* q_now;
   LOCK_LOG;
   
   q_now  = mylist_entry(read_point);
   if(list_is_last(read_point,&arp_log_list))
     lnext = (&arp_log_list)->next;
   else 
     lnext = read_point->next;
   q_next = mylist_entry(lnext);
   if(time_before(q_now->stamp,q_next->stamp)) // 下一个可读
   {
      read_point = lnext;
	  len  = MAC_FMAT_BUF_D(page,q_next);
	  len += MAC_FMAT_BUF_S(page,q_next);
	  len += MAC_FMAT_S_MAC(page,q_next);
	  len += MAC_FMAT_S_IP (page,q_next);
	  len += MAC_FMAT_D_MAC(page,q_next);
	  len += MAC_FMAT_D_IP (page,q_next);
	  switch(q_next->opcode)
	  {
	     case 1:
		    len += OP_FMT_1(page);
			break;
		 case 2:
		    len += OP_FMT_2(page);
			break;
	  }
   }
   else
   {
      len +=sprintf(page,"%s\n","no arp packet log");
   }
   UNLOCK_LOG;     
   return len;
}

//
void log_arp_pack_list( const u8* dest, 
						const u8* source, 
						u16 arpcode, 
						const u8* sendmac,
						const u32* sendip,
						const u8 *targmac,
						const u32* targip)
{
	arp_pack * pack = NULL;
	LOCK_LOG;
	if(list_is_last(write_point,&arp_log_list))
	   write_point = (&arp_log_list)->next;
	else
       write_point = write_point->next;	
	pack = mylist_entry(write_point);
	
	memcpy(pack->dest,dest,6);
	memcpy(pack->source ,source,6);
	pack->opcode = arpcode; // 执行指令
	pack->stamp  = jiffies;
	memcpy(&pack->send_mac[0],sendmac,6);
	memcpy(&pack->send_ip,sendip,4);
	memcpy(&pack->target_mac[0],targmac,6);
	memcpy(&pack->target_ip,targip,4);
	UNLOCK_LOG;
	

}

//1000个循环缓冲
int init_arp_buffer_list(void)
{
  int i =0;
  unsigned long jstamp = jiffies;
  INITLOCK_LOG;
  INIT_LIST_HEAD(&arp_log_list);

  for(i=0;i<LIST_BUFFER_NUMBER;i++) //1000个循环缓冲区
  {
     arp_pack * pack = kmalloc(sizeof(arp_pack),GFP_KERNEL);
     if(pack == NULL)
	 {
	    printk(KERN_ALERT "kmalloc error when for arplist!");
		break;
	    //return -1;
	 }
	 else
	 {
	    memset(pack,0,sizeof(arp_pack));
	    pack->stamp = jstamp;
		list_add(&pack->list,&arp_log_list);
	 }
  }
  
  //没有分配足够的空间，
  if(i < LIST_BUFFER_NUMBER)
  {
    
    arp_pack* pack = NULL;
	struct list_head *pos = NULL;
	struct list_head *q   = NULL; 
    list_for_each_safe(pos, q, &arp_log_list)
	{
		pack= list_entry(pos, arp_pack, list);
		list_del(pos);
		kfree(pack);
	}
	printk(KERN_ALERT"not enough memory!\n");
	return -1;
  }
  write_point = (&arp_log_list)->next;//让写指针往下移动一格。
  read_point  = (&arp_log_list)->next;
  return 0;
}
void uninit_arp_buffer_list(void)
{
	struct list_head *pos = NULL;
	struct list_head *q   = NULL; 
	arp_pack* pack;
	LOCK_LOG;
    list_for_each_safe(pos, q, &arp_log_list)
	{
		pack= list_entry(pos, arp_pack, list);
		
		list_del(pos);
		kfree(pack);
	}
	UNLOCK_LOG;
}


#if 0
	//DECLARE_MAC_BUF(buffer);
	
	//printk(KERN_ALERT" bri type:%04x \n",tr->h_proto);
	//printk(KERN_ALERT"dest address:%02x:%02x:%02x:%02x:%02x:%02x",_d(0),_d(1),_d(2),

	//sysfs_format_mac(buffer,&tr->h_dest[0],6);
	//printk(KERN_ALERT"destmac :%s    ",buffer);
	//sysfs_format_mac(buffer,&tr->h_source[0],6);
	//printk(KERN_ALERT"sourcemac :%s\n",buffer);
	
	
	//arp_ptr += dev->addr_len;
	//memcpy(&sip, arp_ptr, 4);
	//arp_ptr += 4;
	//arp_ptr += dev->addr_len;
	//memcpy(&tip, arp_ptr, 4);
	
	//printk(KERN_ALERT"sip : %d.%d.%d.%d     ",NIPQUAD_T(sip));
	//printk(KERN_ALERT"dip : %d.%d.%d.%d     \n",NIPQUAD_T(tip));
#endif