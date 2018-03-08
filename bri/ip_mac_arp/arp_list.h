#ifndef __ARP_LIST_H__
#define __ARP_LIST_H__
int write_page_list(char * page);

void log_arp_pack_list( const u8* dest, 
						const u8* source, 
						u16 arpcode, 
						const u8* sendmac,
						const u32* sendip,
						const u8 *targmac,
						const u32* targip);
int init_arp_buffer_list(void);
void uninit_arp_buffer_list(void);
#endif