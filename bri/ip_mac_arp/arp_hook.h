#ifndef __ARP_HOOK_H__
#define __ARP_HOOK_H__
int init_arphook(void);
void fini_arphook(void);
void add_ipmac_node(u32 ip,char* mac);
int write_ip_mac_list(char * page);
extern int g_control;
extern unsigned long long g_arp_number;
#endif