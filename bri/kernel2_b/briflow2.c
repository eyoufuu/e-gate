#ifndef __KERNEL__
#define __KERNEL__
#endif

#ifndef MODULE
#define MODULE
#endif

#include <linux/module.h>
#include <linux/kernel.h>
#include <linux/init.h>
#include <linux/types.h>
#include <linux/netdevice.h>
#include <linux/skbuff.h>
#include <linux/netfilter.h>
#include <linux/netfilter_bridge.h>
#include <linux/netfilter_ipv4.h>
#include <linux/inet.h>
#include <linux/in.h>
#include <linux/ip.h>
#include <linux/netlink.h>
#include <linux/spinlock.h>
#include <linux/tcp.h>
#include <linux/udp.h>
//#include <asm/semaphore.h>
#include <net/sock.h>
#include <net/ip.h>
#include <linux/list.h>
#include <net/net_namespace.h>

#include <linux/fs.h>
#include <linux/errno.h>
#include <linux/types.h>
#include <linux/fcntl.h>
#include <linux/cdev.h>
#include <linux/version.h>
#include <linux/vmalloc.h>
#include <linux/ctype.h>
#include <linux/pagemap.h>

#include <linux/sched.h>   //wake_up_process()
#include <linux/kthread.h> //kthread_create()、kthread_run()
#include "imp2.h"


#include <linux/proc_fs.h>
#include <linux/string.h>
#include <linux/vmalloc.h>
#include <asm/uaccess.h>

MODULE_AUTHOR("fuyou");
MODULE_LICENSE("GPL");


#define MAX_IP   256
#define MAX_SEG 4
#define GET_IP_HASH(IP) 	  (IP>>24)
#define GET_NETSEG_HASH(IP) (((IP>>16)&(0xff))%MAX_SEG)


typedef struct _ip_mac{
	u32 ip;
	char mac[6];
	u16  reserve;
	struct hlist_node ipmac_node;
}ip_mac;


static u32 call_bypass(struct sk_buff *pskb);   //0
static u32 call_notbypass(struct sk_buff *pskb);//1
static u32 call_bypass_ipmac_check(struct sk_buff *pskb);//2
static u32 call_notbypass_ipmac_check(struct sk_buff *pskb);//3

static struct sock *g_nlfd;
static struct hlist_head g_iplist[MAX_SEG][MAX_IP];
static struct proc_dir_entry *proc_entry;
unsigned int g_control_bypass = 1;
typedef u32 (*callback_check)(struct sk_buff *);
callback_check check_function;

/////////////////////////////////////////
//ip_mac hash table
void init_ipmac_table(void)
{
	unsigned int i;
	unsigned int j;
	for(i=0;i<MAX_SEG;i++)
	{
		for(j=0;j<MAX_IP;j++)
		{
			INIT_HLIST_HEAD(&g_iplist[i][j]);
		}		
	}
}
inline void un_init_ipmac_table(void)
{
    unsigned int i = 0;
    unsigned int j = 0;
    struct hlist_node *pos,*n;
	ip_mac* node;
    for(i=0;i<MAX_SEG;i++)
    {
        for(j=0;j<MAX_IP;j++)
        {
        	hlist_for_each_safe(pos,n,&g_iplist[i][j])
            {
                node = hlist_entry(pos,ip_mac,ipmac_node);
                hlist_del(pos);
                kfree(node);
            }
        }
    }
}
inline ip_mac* get_ipnode(unsigned int ip)
{
	u8 hash ;
	u8 hash_seg;
	struct hlist_node* pos,*n;
	hash     = GET_IP_HASH(ip);
	hash_seg = GET_NETSEG_HASH(ip);
	hlist_for_each_safe(pos,n,&g_iplist[hash_seg][hash])
	{
		ip_mac *node = hlist_entry(pos,ip_mac,ipmac_node);
		if(node->ip == ip)
		{
			return node;
		}
	}
	return NULL;
}
inline void add_ipnode(unsigned int ip,char* mac)
{
	u8 hash ;
	u8 hash_seg;
	ip_mac* node = (ip_mac*)kmalloc(sizeof(ip_mac),GFP_KERNEL);
	if(node != NULL)
	{
		node->ip = ip;
		if(mac !=NULL)
		{
			memcpy(node->mac,mac,6);
		}
		hash     = GET_IP_HASH(ip);
		hash_seg = GET_NETSEG_HASH(ip);
		hlist_add_head(&node->ipmac_node,&g_iplist[hash_seg][hash]);
	}
}
/////////////////////////////////////////
//ip_mac hash table


/*
void kernel_data_rev(struct sk_buff *__skb)
{  
	struct sk_buff  *skb; 
	struct nlmsghdr *nlh;  
	unsigned short iphash;
	unsigned char  netseghash;
	TIpmac *pmac;
	skb = skb_get(__skb);  

	if(skb->len >= NLMSG_SPACE(0)) 
	{
		nlh = nlmsg_hdr(skb);
#if 1		
		if(nlh->nlmsg_type==IMP2_IP_MAC)
		{
			unsigned int ip  = 0;
			int nData  = 0; 
			
			unsigned char *pData = NULL;
			//printk(KERN_ALERT"user space special ip\n");

			nData  = nlh->nlmsg_len-NLMSG_SPACE(0);
			pData   = (char*)NLMSG_DATA(nlh);
			ip  = *((unsigned int*)pData);
			iphash = GETIPHASH(ip);
			netseghash = GETNETSEGHASH(ip);
			pmac = get_ipnode(ip);
		
            printk(KERN_ALERT" %d:%d:%d:%d:%d:%d\n",pData[4],pData[5],pData[6],pData[7],pData[8],pData[9]);
			if(pmac == NULL)
			{
				add_ipnode(ip,iphash,netseghash,pData+4);
			}			
			else
			{
				memcpy(pmac->mac,pData+4,6);
			}
		}
		else if(nlh->nlmsg_type == IMP2_IPMAC_STATUS)
		{
			char *pData = NULL;
			pData = (char*)NLMSG_DATA(nlh);
			g_ipmac = *((unsigned int*)pData);
			printk(KERN_ALERT"IMP2_IPMAC_STATUS %d\n",g_ipmac);		
		}
#endif
	}  
	return;
}
*/
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
//proc伪文件系统


//if 0 bypass
//if 1 not bypass
int control_read( char *page, char **start, off_t off,
                   int count, int *eof, void *data )
{
     int len;
     len = sprintf(page,"%u\n", g_control_bypass); 
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
		     g_control_bypass = 0;
			 check_function = call_bypass;
		     break;
		  case '1':
		  case 1:
		     g_control_bypass = 1;
			 check_function = call_notbypass;
		     break;
		  case '2':
          case 2:
             g_control_bypass = 2;
			 check_function = call_bypass_ipmac_check;
			 break;
		  case '3':
          case 3:
             g_control_bypass = 3;
			 check_function = call_notbypass_ipmac_check;
		  default:
             break;		  
       }	   
	}
	printk(KERN_ALERT"receive control %d\n",value);
    return len;	


}


int proc_init_bypass(void)
{
    proc_entry  = create_proc_entry("bridge_bypass",0666,NULL);
	if(proc_entry == NULL)
	{
	   printk(KERN_ALERT"create proc bridge_bypass error!");
	   return -1;
	}
	else
	{
	   proc_entry->read_proc  = control_read;
       proc_entry->write_proc = control_write;
       printk(KERN_INFO "ipmac_arp_c: Module loaded.\n");
	   return 0;
	}
}

void un_proc_bypass(void)
{
     remove_proc_entry("bridge_bypass", NULL);
}



///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////



static inline struct iphdr* get_iph_para(struct sk_buff *pskb,int* p_iphlen)
{
    struct iphdr * iph ;
	//iph = ip_hdr(pskb);	
	iph = (struct iphdr*)pskb->network_header;//ip层	
	*p_iphlen    = iph->ihl * 4;
	//iphlen    = ip_hdrlen(pskb); //ip头长度
	return iph;
}
static inline u32 do_mac_ip_check(struct sk_buff * pskb,u32 ip)
{
#define _SMAC &eth->h_source[0]
	struct nf_bridge_info	*nf_bridge;
	struct ethhdr *eth = NULL; 
    nf_bridge = pskb->nf_bridge;
	eth       = eth_hdr(pskb);
	if (nf_bridge->physindev->name[3]!='0') // 上行包 ，如果indev的名字为eth0，则肯定为下行包,eth0是wan口
    { 
	    ip_mac * pmac;
        pmac = get_ipnode(ip);
        if(pmac==NULL)
        {
            return NF_DROP;                            
        }
        if(0 != memcmp(pmac->mac,_SMAC,6))
		{
			return NF_DROP;
		}                       
    }
    return NF_ACCEPT;	
}

static inline u32 do_packet_select(struct sk_buff* pskb,struct iphdr* iph,int iphlen)
{
    struct tcphdr* tcp;
	struct udphdr* udp;
	int tcphlen;
	int datalen;
	switch(iph->protocol)
	{
		case IPPROTO_TCP:
			{
				tcp = (struct tcphdr*)((unsigned char*)(iph)+iphlen);
				//注意下面两句话都是错的，因为此时还没有transport_header
				//tcp = (struct tcphdr*)pskb->transport_header;//tcp头部
				//tcp =  tcp_hdr(pskb);
				
				
                if(tcp->rst||tcp->fin)
                {
                    return NF_ACCEPT;
                }
		    	tcphlen = tcp->doff*4;
				//注意下面这句话还是错的！
			    //tcphlen = tcp_hdrlen(pskb);
				//注意下面这句话也是对的；
				//ippacklen = __nhs(iph->tot_len);--->pskb->len
				struct nf_bridge_info	*nf_bridge;
				nf_bridge = pskb->nf_bridge;
	
                        
		    	datalen = pskb->len-iphlen-tcphlen;
                
			    if(datalen==0)
	            {
    				if(nf_bridge->physindev->name[3]=='1')
    				{
                        if(tcp->ack || tcp->syn)
                        {
    					    pskb->mark=21;//352321536;//21 ack 标签
                        }
    				}
                    return NF_ACCEPT;
	            }
			    if(datalen >1480)//長度為零的Fin Rst放過 ,巨帧模式的包也放过了
			    {
				    return NF_ACCEPT;
			    }
			}				
			return NF_QUEUE;
		case IPPROTO_UDP:
			{
				udp = (struct udphdr*)((unsigned char*)(iph)+iphlen);
				if(udp->source == 13568 || udp->dest == 13568)//53端口
				{
					return NF_ACCEPT;				
				}
			}			
			return NF_QUEUE;
        case IPPROTO_ICMP://icmp
            pskb->mark = 22;//369098752;//22 icmp 标签
           return NF_ACCEPT;
		default:
			return NF_ACCEPT;			
	}
   
}

//四个回调函数
static u32 call_bypass(struct sk_buff *pskb)
{
    return NF_ACCEPT;
}
//1
static u32 call_notbypass(struct sk_buff *pskb)
{
   	struct iphdr * iph;
	int iphlen  = 0;
#if 1
	if (!pskb) return NF_ACCEPT;
#endif
    iph = get_iph_para(pskb, &iphlen);
	
	if(((iph->daddr)&65535) ==((iph->saddr)&65535)) //ip层检测局域网包直接通过
	{
		return NF_ACCEPT;
	}
	return do_packet_select(pskb,iph,iphlen);
}


//2
//bypass 但是仍然检测ip-mac检测，防止内网ip欺骗 2
static u32 call_bypass_ipmac_check(struct sk_buff *pskb)
{
    struct iphdr * iph;
	
#if 1
	if (!pskb) return NF_ACCEPT;
#endif
	iph = (struct iphdr*)pskb->network_header;//ip层	
	if(((iph->daddr)&65535) ==((iph->saddr)&65535)) //ip层检测局域网包直接通过
	{
		return NF_ACCEPT;
	}
	/*然后再检测ip_mac绑定*/
    return do_mac_ip_check(pskb,iph->saddr);
}


//3
static u32 call_notbypass_ipmac_check(struct sk_buff *pskb)
{
    struct iphdr * iph = NULL;
	int iphlen  = 0;
	
#if 1
	if (!pskb) return NF_ACCEPT;
#endif
    iph = get_iph_para(pskb, &iphlen);
	if(((iph->daddr)&65535) ==((iph->saddr)&65535)) //ip层检测局域网包直接通过
	{
		return NF_ACCEPT;
	}
	if(do_mac_ip_check(pskb,iph->saddr)==NF_ACCEPT)
	   return NF_ACCEPT;

	return do_mac_ip_check(pskb,iph->saddr);
}

static u32 get_package(unsigned int hook,
						struct sk_buff *pskb,
						const struct net_device *in,
						const struct net_device *out,
						int (*okfn)(struct sk_buff *))
{
    return check_function(pskb);
}


static struct nf_hook_ops imp2_ops =
{
	.hook = get_package,
	.pf = PF_INET,
	//.hooknum = NF_INET_PRE_ROUTING,
	.hooknum  = NF_BR_PRE_ROUTING,
	//.hooknum  = NF_BR_FORWARD,
	.priority = NF_BR_PRI_FIRST,
	//.priority = NF_BR_PRI_BRNF,
	//.priority = NF_IP_PRI_FILTER -1,
};

static int __init init(void)
{
	/*	g_nlfd = netlink_kernel_create(&init_net,NL_IMP2,0, kernel_data_rev,NULL,THIS_MODULE);
	if(!g_nlfd)
	{
		printk(KERN_ALERT"can not create a netlink socket\n");
		return -1;
	}*/	
	init_ipmac_table();
	if(proc_init_bypass()!=0)
	   return -1;
	check_function = call_notbypass;
	return nf_register_hook(&imp2_ops);
}

static void __exit fini(void)
{
	un_proc_bypass();
	nf_unregister_hook(&imp2_ops);
	un_init_ipmac_table();
	/*if(g_nlfd)
	{
		sock_release(g_nlfd->sk_socket);
	}*/
}

module_init(init);
module_exit(fini);

