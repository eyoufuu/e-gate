#include <linux/module.h>
#include <linux/kernel.h>
#include <linux/string.h>
#include <linux/vmalloc.h>
#include <asm/uaccess.h>
#include <linux/list.h>
#include <linux/netdevice.h>
#include <linux/if_ether.h>
#include <linux/if_packet.h>
#include <linux/if_arp.h>
#include <linux/netfilter.h>
#include <linux/netfilter_ipv4.h>
#include <linux/netfilter_arp.h>
#include <linux/netfilter_bridge.h>
//#include "arp_list.h"

MODULE_LICENSE("GPL");

static rwlock_t g_lock_ipmac_List;

#define NIPQUAD_T(addr) \
	((unsigned char *)&addr)[0], \
	((unsigned char *)&addr)[1], \
	((unsigned char *)&addr)[2], \
	((unsigned char *)&addr)[3]
#define MAC_FMAT "%02X:%02X:%02X:%02X:%02X:%02X "
#define IP_FMAT "%d.%d.%d.%d "

#define MAC_IP_FMAT_A MAC_FMAT""MAC_FMAT""MAC_FMAT""MAC_FMAT""IP_FMAT""IP_FMAT" %d\n"

#define INITLOCK_IP_MAC rwlock_init(&g_lock_ipmac_List)
#define LOCK_IP_MAC	write_lock_bh(&g_lock_ipmac_List)
#define UNLOCK_IP_MAC write_unlock_bh(&g_lock_ipmac_List)
#define LOCK_IP_MAC_R 	read_lock_bh(&g_lock_ipmac_List)
#define UNLOCK_IP_MAC_R read_unlock_bh(&g_lock_ipmac_List)
#define MAX_IP   256
#define GETIPHASH(IP) 	  (IP>>24)

enum
{
   LOG_LOG = 1,
   LOG_NF  = 2
};

int g_control = 0;
unsigned long long g_arp_number = 0;
typedef struct _ip_mac{
	struct hlist_node hnode;
	u32 ip;
	u8 mac[6];
}ip_mac;


static struct hlist_head g_iphlist[MAX_IP];

static void init_ipmac_hlist(void)
{
	unsigned int i;
	INITLOCK_IP_MAC;
	for(i=0;i<MAX_IP;i++)
	{
		INIT_HLIST_HEAD(&g_iphlist[i]);
	}
	
}
static inline void uninit_ipmac_hlist(void)
{
    unsigned int i = 0;
    struct hlist_node *pos,*n;
	ip_mac* node;
	LOCK_IP_MAC;
    for(i=0;i<MAX_IP;i++)
    {
     	hlist_for_each_safe(pos,n,&g_iphlist[i])
        {
            node = hlist_entry(pos,ip_mac,hnode);
            hlist_del(pos);
            kfree(node);
        }
    }
	UNLOCK_IP_MAC;
}

int write_ip_mac_list(char * page)
{
    int len = 0;
	struct hlist_node *pos;
	int i = 0;
	ip_mac* node;
	LOCK_IP_MAC_R;

    for(i=0;i<MAX_IP;i++)
    {
     	hlist_for_each(pos,&g_iphlist[i])
        {
            node = hlist_entry(pos,ip_mac,hnode);
			len +=sprintf(page+len,"%d.%d.%d.%d,",NIPQUAD_T(node->ip));
			len +=sprintf(page+len,MAC_FMAT"\n",node->mac[0],node->mac[1],node->mac[2],node->mac[3],node->mac[4],node->mac[5]);
			if(len>4000)
			  break;
        }
    }
	UNLOCK_IP_MAC_R;
	return len;
   
}

static inline int com_ipmac_node(u32 ip,u8* mac)
{
	struct hlist_node* pos;
	ip_mac * node;
	u8 hash  = GETIPHASH(ip); 
	LOCK_IP_MAC_R;
	hlist_for_each(pos,&g_iphlist[hash])
	{
		node = hlist_entry(pos,ip_mac,hnode);
		if(node->ip == ip)
		{
		    if(memcmp(node->mac,mac,6)==0)
			{
			   UNLOCK_IP_MAC_R;
			   return 0;
			}	
			else
			{
			   UNLOCK_IP_MAC_R;
			   return -1;
			}
		}
	}
	UNLOCK_IP_MAC;
	return -1;
}

static inline int ebt_hook(struct sk_buff * skb,int addr_len)
{

#define _s(x) eth->h_source[x]	
#define _s_all _s(0),_s(1),_s(2),_s(3),_s(4),_s(5)
#define _d(x) eth->h_dest[0]
#define _d_all _d(0),_d(1),_d(2),_d(3),_d(4),_d(5)
#define _se(x) sendmac[x]
#define _send_all _se(0),_se(1),_se(2),_se(3),_se(4),_se(5)
#define _ta(x) targmac[x]
#define _targ_all  _ta(0),_ta(1),_ta(2),_ta(3),_ta(4),_ta(5)	
	struct ethhdr *eth = NULL;
	struct arphdr *arp = NULL;
	u8  *arp_ptr;
	u8  *sendmac; 
	u32 *sendip;
	u8  *targmac;
    u32 *targip; 
 //	DECLARE_MAC_BUF(buffer); 
	eth = eth_hdr(skb);
	arp = arp_hdr(skb);	

	if(unlikely(!eth))
	   return NF_ACCEPT;

//	struct in_device *in_dev = in_dev_get(dev);
	g_arp_number++;  

	arp_ptr  = (u8 *)(arp+1);
	sendmac  = arp_ptr;
	arp_ptr += addr_len;
	sendip   = (u32*)arp_ptr;
	arp_ptr += 4;
	targmac  = arp_ptr;
	arp_ptr += addr_len;
	targip   = (u32*)arp_ptr;
	
	
    switch(g_control)
	{
	   case 0:  
		  return NF_ACCEPT;
	   case 1:  //只记录日志//if(g_control & 0x01 == 0x01)
	    printk(KERN_ALERT"__arp "MAC_IP_FMAT_A,_d_all,_s_all,_send_all,_targ_all,NIPQUAD_T(*sendip),NIPQUAD_T(*targip),arp->ar_op);
        return NF_ACCEPT;
       case 2: //只绑定
      	if(com_ipmac_node((*sendip),sendmac)==0)
		{
			return NF_ACCEPT;
		}
		return NF_DROP;
       case 3:	//记录日志并且绑定
	    printk(KERN_ALERT"__arp "MAC_IP_FMAT_A,_d_all,_s_all,_send_all,_targ_all,NIPQUAD_T(*sendip),NIPQUAD_T(*targip),arp->ar_op);
	    if(com_ipmac_node((*sendip),sendmac)==0)
		{
			return NF_ACCEPT;
		}
		return NF_DROP;   
	}
	return NF_ACCEPT;
}



static unsigned int ebt_i_hook(unsigned int hook, struct sk_buff *skb, const struct net_device *in,
   const struct net_device *out, int (*okfn)(struct sk_buff *))
{
    //if(g_control == 0)
    //   return NF_ACCEPT;	
	if(unlikely(!skb))
	   return NF_ACCEPT; 
   // printk(KERN_ALERT"_ip_mac_arp_arp in enter!\n");
	return ebt_hook(skb,skb->dev->addr_len);
}
static unsigned int ebt_o_hook(unsigned int hook, struct sk_buff *skb, const struct net_device *in,
   const struct net_device *out, int (*okfn)(struct sk_buff *))
{
    //if(g_control == 0)
    //   return NF_ACCEPT;	
	if(unlikely(!skb))
	   return NF_ACCEPT; 
   // printk(KERN_ALERT"_ip_mac_arp_arp out!\n");
	return ebt_hook(skb,skb->dev->addr_len);
}

static unsigned int ebt_b_hook(unsigned int hook, struct sk_buff *skb, const struct net_device *in,
   const struct net_device *out, int (*okfn)(struct sk_buff *))
{
	if(unlikely(!skb))
	   return NF_ACCEPT; 
    //printk(KERN_ALERT"_ip_mac_arp_arp through gateway!\n");
	return ebt_hook(skb,skb->dev->addr_len);
/*
	struct net_device *dev;
	struct ethhdr *eth = NULL;
	struct arphdr *arp = NULL;
	u8  *arp_ptr;
	u8  *sendmac; 
	u32 *sendip;
	u8  *targmac;
    u32 *targip;
    if(g_control == 0)
       return NF_ACCEPT;	
	if(unlikely(!skb))
	   return NF_ACCEPT;
	
	
	dev = (struct net_device*)skb->dev;
//	struct in_device *in_dev = in_dev_get(dev);


    eth = eth_hdr(skb);
	arp = arp_hdr(skb);
	if(unlikely(!eth))
	   return NF_ACCEPT;
	g_arp_number++;
	arp_ptr  = (u8 *)(arp+1);
	sendmac  = arp_ptr;
	arp_ptr += dev->addr_len;
	sendip   = (u32*)arp_ptr;
	arp_ptr += 4;
	targmac  = arp_ptr;
	arp_ptr += dev->addr_len;
	targip   = (u32*)arp_ptr;
	//记录所有的arp包

    switch(g_control)
	{
	   case 0:
	        return NF_ACCEPT;
	   case 1: //只记录日志
			log_arp_pack_list(&eth->h_dest[0], &eth->h_source[0], arp->ar_op, sendmac,sendip,targmac,targip);
			return NF_ACCEPT;
	      break;
	   case 2: //绑定 
			if(com_ipmac_node((*sendip),sendmac)==0)
			{
				return NF_ACCEPT;
			}
	        return NF_DROP;
	   case 3: //记录日志又绑定
			log_arp_pack_list(&eth->h_dest[0], &eth->h_source[0], arp->ar_op, sendmac,sendip,targmac,targip);
			if(com_ipmac_node((*sendip),sendmac)==0)
			{
				return NF_ACCEPT;
			}
	        return NF_DROP;
	}
	
	return NF_ACCEPT;*/
}



void add_ipmac_node(u32 ip,char* mac)
{
	struct hlist_node* pos;
	u8 hash = ip>>24;
	ip_mac * pnode;
	LOCK_IP_MAC;
	hlist_for_each(pos,&g_iphlist[hash])
    {
		pnode = hlist_entry(pos,ip_mac,hnode);
		if(pnode->ip == ip)
		{
		   memcpy(pnode->mac,mac,6);
		   UNLOCK_IP_MAC;
		   return ;
		}
	}
    
	pnode = (ip_mac*)kmalloc(sizeof(ip_mac),GFP_KERNEL);
	if(pnode != NULL)
	{
		pnode->ip = ip;
		if(mac !=NULL)
		{
			memcpy(pnode->mac,mac,6);
		}
		hlist_add_head(&pnode->hnode,&g_iphlist[hash]);
	}
	UNLOCK_IP_MAC;
}


static struct nf_hook_ops arp_ops[] = {
   	{
		.hook		= ebt_i_hook,
		.owner		= THIS_MODULE,
		.pf		    = NFPROTO_ARP,
		//.hooknum	= NF_BR_LOCAL_IN,
		.hooknum    = NF_ARP_IN,
		.priority	= NF_BR_PRI_FILTER_BRIDGED,
	},
	{
		.hook		= ebt_b_hook,
		.owner		= THIS_MODULE,
		.pf		    = NFPROTO_ARP,
		.hooknum	= NF_BR_FORWARD,
		.priority	= NF_BR_PRI_FILTER_BRIDGED,
	},
	{
		.hook		= ebt_o_hook,
		.owner		= THIS_MODULE,
		.pf		    = NFPROTO_ARP,
		//.hooknum	= NF_BR_LOCAL_OUT,
		.hooknum    = NF_ARP_OUT,
		.priority	= NF_BR_PRI_FILTER_OTHER,
	}

};

int init_arphook(void)
{
    int ret;
	init_ipmac_hlist();
    ret = nf_register_hooks(arp_ops, ARRAY_SIZE(arp_ops));
    if (ret < 0) {
        printk(KERN_ALERT"http detect:can't register arp_ops hook!\n");
        return ret;
    }
    return 0;
}
void fini_arphook(void)
{
    nf_unregister_hooks(arp_ops, ARRAY_SIZE(arp_ops));
	uninit_ipmac_hlist();
    printk("remove arp_ops hook.\n");
}


#if 0
   //tr  = eth_hdr(skb);
	//DECLARE_MAC_BUF(buffer);
	//printk(KERN_ALERT" bri type:%04x \n",tr->h_proto);
	//printk(KERN_ALERT"dest address:%02x:%02x:%02x:%02x:%02x:%02x",_d(0),_d(1),_d(2),

	/*sysfs_format_mac(buffer,&tr->h_dest[0],6);
	printk(KERN_ALERT"destmac :%s    ",buffer);
	sysfs_format_mac(buffer,&tr->h_source[0],6);
	printk(KERN_ALERT"sourcemac :%s\n",buffer);
	*/

	
	//arp_ptr += 4;
	//arp_ptr += dev->addr_len;
	//memcpy(&tip, arp_ptr, 4);
	
	//printk(KERN_ALERT"sip : %d.%d.%d.%d     ",NIPQUAD_T(sip));
	//printk(KERN_ALERT"dip : %d.%d.%d.%d     \n",NIPQUAD_T(tip));
	//if(tip == 302033088 || sip ==302033088 )
	//   return NF_DROP;
#endif	
	