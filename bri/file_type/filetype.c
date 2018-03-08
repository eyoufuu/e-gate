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

MODULE_AUTHOR("qianbo");
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
static u32 call_filecheck(struct sk_buff *pskb);//1


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
			 check_function = call_filecheck;
		     break;

		  default:
             break;		  
       }	   
	}
	printk(KERN_ALERT"receive control %d\n",value);
    return len;	


}


int proc_init_filecheck(void)
{
    proc_entry  = create_proc_entry("file_check", 0,NULL);
	if(proc_entry == NULL)
	{
	   printk(KERN_ALERT"create proc file_check error!");
	   return -1;
	}
	else
	{
	   proc_entry->read_proc  = control_read;
       proc_entry->write_proc = control_write;
       printk(KERN_INFO "file_check: Module loaded.\n");
	   return 0;
	}
}

void un_proc_filecheck(void)
{
     remove_proc_entry("file_check", NULL);
}

/*
Wps:
Ppt:
Wd: D0  CF  11  E0  A1  B1  1A  E1  00  00  00  00  00  00  00  00
Xls: D0  CF  11  E0  A1  B1  1A  E1  00  00  00  00  00  00  00  00  
Rar : 52  61  72  21  1A  07  00  CF  90  73  00  00  0D  00  00  00
ZIP: 50  4B  03  04  0A  00  00  00  00  00
Pdf: 25  50  44  46  2D  31  2E  *  0D 25  E2  E3  CF  D3  0D  0A
 Jpg: FF  D8  FF  E0  00 10 4A 46 49 00 01 01 01 00 
*/
u8 word_flag[]={0xd0,0xcf,0x11,0xe0,0xa1,0xb1,0x1a,0xe1,0x00,0x00};
u8 rar_flag[]={0x52,0x61,0x72,0x21,0x1A,0x07,0x00,0xcf,0x90,0x73,0x00,0x00,0x0d,0x00,0x00,0x00};
static void *memmem(const void *haystack, size_t haystack_len,

            const void *needle, size_t needle_len)

{/*{{{*/
    const char *begin;
    const char *const last_possible
        = (const char *) haystack + haystack_len - needle_len;
//    if (needle_len == 0)
//        return (void *) haystack;
    /* Sanity check, otherwise the loop might search through the whole
       memory.  */
    if (__builtin_expect(haystack_len < needle_len, 0))
        return NULL;
    for (begin = (const char *) haystack; begin <= last_possible;++begin)
        if (begin[0] == ((const char *) needle)[0]
            && !memcmp((const void *) &begin[1],
                   (const void *) ((const char *) needle + 1),
                   needle_len - 1))
            return (void *) begin;
    return NULL;
}/*}}}*/


///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////



static inline struct iphdr* get_iph_para(struct sk_buff *pskb,int* p_iphlen,u32* ipf, u32* ipt)
{
    struct iphdr * iph ;
	iph = (struct iphdr*)pskb->network_header;//ip层	
	
	*p_iphlen    = iph->ihl * 4;
	*ipf         = iph->saddr;
	*ipt         = iph->daddr;
	return iph;
}


static inline u32 do_packet_select(struct sk_buff* pskb,struct iphdr* iph,int iphlen)
{
    struct tcphdr* tcp;
	struct udphdr* udp;
	int tcphlen;
	int datalen;
	u8* payload;
	switch(iph->protocol)
	{
		case IPPROTO_TCP:
			{
				tcp = (struct tcphdr*)((unsigned char*)(iph)+iphlen);
                if(tcp->syn||tcp->rst||tcp->fin)
                {
                    return NF_ACCEPT;
                }
		    	tcphlen = tcp->doff*4;
				//struct nf_bridge_info	*nf_bridge;
				//nf_bridge = pskb->nf_bridge;
		    	datalen = pskb->len-iphlen-tcphlen;
				
			    if(datalen<=100)
	            {
                    return NF_ACCEPT;
	            }
				payload = ((u8*)iph)+iphlen+tcphlen;
				if(tcp->dest==20480)
				{
					printk(KERN_ALERT"check start\n");
					if(memmem(payload,datalen,word_flag,10)!=NULL)
					{
						return NF_DROP;
					}
					if(memmem(payload,datalen,rar_flag,16)!=NULL)
						return NF_DROP;
				}
			}				
			return NF_ACCEPT;
		default:
			return NF_ACCEPT;			
	}
}

//二个回调函数
//这一个就是不启用filecheck直接放行
static u32 call_bypass(struct sk_buff *pskb)
{
    return NF_ACCEPT;
}
//1
static u32 call_filecheck(struct sk_buff *pskb)
{
   	struct iphdr * iph;
	int iphlen  = 0;
	u32 ipf;
	u32 ipt;
#if 1
	if (!pskb) return NF_ACCEPT;
#endif
    iph = get_iph_para(pskb, &iphlen,&ipf,&ipt);
	
	if(((iph->daddr)&65535) ==((iph->saddr)&65535)) //ip层检测局域网包直接通过
	{
		return NF_ACCEPT;
	}
	
	
	return do_packet_select(pskb,iph,iphlen);
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
	.priority = NF_BR_PRI_FIRST-1,
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
	//init_ipmac_table();
	if(proc_init_filecheck()!=0)
	   return -1;
	check_function = call_filecheck;
	return nf_register_hook(&imp2_ops);
}

static void __exit fini(void)
{
	un_proc_filecheck();
	nf_unregister_hook(&imp2_ops);
	un_init_ipmac_table();
	/*if(g_nlfd)
	{
		sock_release(g_nlfd->sk_socket);
	}*/
}

module_init(init);
module_exit(fini);

