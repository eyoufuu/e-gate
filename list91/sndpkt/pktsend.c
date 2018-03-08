#include "pktsend.h"

static inline unsigned short __nhs(unsigned short x)
{
	return x<<8 | x>>8;
}
static inline unsigned int __nhl(unsigned int x)
{
	return x<<24 | x>>24 |
		(x & (u32)0x0000ff00UL)<<8 |
		(x & (u32)0x00ff0000UL)>>8;
}
#ifndef __hnl
#define __hnl(x) __nhl(x)
#endif

#ifndef __hns
#define __hns(x) __nhs(x)
#endif
typedef struct pseudoheader
{
  u_int32_t src;
  u_int32_t dst;
  u_char zero;
  u_char protocol;
  u_int16_t tcplen;
} tcp_phdr_t;

#define TCPSYN_LEN 20
int g_rawsocket=0;

const char* g_ReDirectString = "HTTP/1.0 302 Moved Temporarily\r\nServer:%s\r\nLocation: http://%s\r\nContent-Type: text/html\r\n\r\n";

/*unsigned short CalcIPSum(unsigned short * w, int blen)
{
	unsigned int cksum;

	 IP must be >= 20 bytes 
	cksum  = w[0];
	cksum += w[1];
	cksum += w[2];
	cksum += w[3];
	cksum += w[4];
	cksum += w[5];
	cksum += w[6];
	cksum += w[7];
	cksum += w[8];
	cksum += w[9];

	blen  -= 20;
	w     += 10;

	while( blen ) // IP-hdr must be an integral number of 4 byte words 
	{
		cksum += w[0];
		cksum += w[1];
		w     += 2;
		blen  -= 4;
	}

	cksum  = (cksum >> 16) + (cksum & 0x0000ffff);
	cksum += (cksum >> 16);

	return (unsigned short) (~cksum);
}*/


unsigned short CalcTCPSum(unsigned short *h, unsigned short * d, int dlen)
{
	unsigned int cksum;
	unsigned short answer=0;

	/* PseudoHeader must have 12 bytes */
	cksum  = h[0];
	cksum += h[1];
	cksum += h[2];
	cksum += h[3];
	cksum += h[4];
	cksum += h[5];

	/* TCP hdr must have 20 hdr bytes */
	cksum += d[0];
	cksum += d[1];
	cksum += d[2];
	cksum += d[3];
	cksum += d[4];
	cksum += d[5];
	cksum += d[6];
	cksum += d[7];
	cksum += d[8];
	cksum += d[9];

	dlen  -= 20; /* bytes   */
	d     += 10; /* short's */ 

	while(dlen >=32)
	{
		cksum += d[0];
		cksum += d[1];
		cksum += d[2];
		cksum += d[3];
		cksum += d[4];
		cksum += d[5];
		cksum += d[6];
		cksum += d[7];
		cksum += d[8];
		cksum += d[9];
		cksum += d[10];
		cksum += d[11];
		cksum += d[12];
		cksum += d[13];
		cksum += d[14];
		cksum += d[15];
		d     += 16;
		dlen  -= 32;
	}

	while(dlen >=8)  
	{
		cksum += d[0];
		cksum += d[1];
		cksum += d[2];
		cksum += d[3];
		d     += 4;   
		dlen  -= 8;
	}

	while(dlen > 1)
	{
		cksum += *d++;
		dlen  -= 2;
	}

	if( dlen == 1 ) 
	{ 
		/* printf("new checksum odd byte-packet\n"); */
		*(unsigned char*)(&answer) = (*(unsigned char*)d);

		/* cksum += (u_int16_t) (*(u_int8_t*)d); */

		cksum += answer;
	}

	cksum  = (cksum >> 16) + (cksum & 0x0000ffff);
	cksum += (cksum >> 16);

	return (unsigned short)(~cksum);
}


/* This piece of code has been used many times in a lot of differents tools. */
/* I haven't been able to determine the author of the code but it looks like */
/* this is a public domain implementation of the checksum algorithm */
unsigned short in_cksum(unsigned short *addr,int len)
{
    
	register int sum = 0;
	unsigned short answer = 0;
	register unsigned short *w = addr;
	register int nleft = len;
	    
	/*
	* Our algorithm is simple, using a 32-bit accumulator (sum),
	* we add sequential 16-bit words to it, and at the end, fold back 
	* all the carry bits from the top 16 bits into the lower 16 bits. 
	*/
	    
	while (nleft > 1)
	{
		sum += *w++;
		nleft -= 2;
	}

	/* mop up an odd byte, if necessary */
	if (nleft == 1)
	{
		*(unsigned char *)(&answer) = *(unsigned char*)w ;
		sum += answer;
	}

	/* add back carry outs from top 16 bits to low 16 bits */
	sum = (sum >> 16) + (sum &0xffff); /* add hi 16 to low 16 */
	sum += (sum >> 16); /* add carry */
	answer = ~sum; /* truncate to 16 bits */
	return(answer);

}



int sndpkt_createrawsocket()
{
	int one=1; 
	if ((g_rawsocket = socket(AF_INET, SOCK_RAW, IPPROTO_TCP)) < 0)
	 {
	       return 0; 
        }
	  
	  /* We need to tell the kernel that we'll be adding our own IP header */
	  /* Otherwise the kernel will create its own. The ugly "one" variable */
	  /* is a bit obscure but R.Stevens says we have to do it this way ;-) */
	  if(setsockopt(g_rawsocket, IPPROTO_IP, IP_HDRINCL, &one, sizeof(one)) < 0)
	  {
	  	return 0;
	  }
	  return 1; 
}

void sndpkt_closerawsocket()
{
	close(g_rawsocket);
}


int TCP_RST_send2(unsigned int seq,unsigned int src_ip, unsigned int dst_ip, unsigned short src_prt, unsigned short dst_prt)
{

  static int i=0;
  int one=1; /* R.Stevens says we need this variable for the setsockopt call */ 

  /* Raw socket file descriptor */ 
  int rawsocket=0;  
  
  /* Buffer for the TCP/IP SYN Packets */
  char packet[ sizeof(struct tcphdr) + sizeof(struct iphdr) +1 ];   

  /* It will point to start of the packet buffer */  
  struct iphdr *ipheader = (struct iphdr *)packet;   
  
  /* It will point to the end of the IP header in packet buffer */  
  struct tcphdr *tcpheader = (struct tcphdr *) (packet + sizeof(struct iphdr)); 
  
  /* TPC Pseudoheader (used in checksum)    */
  tcp_phdr_t pseudohdr;            

  /* TCP Pseudoheader + TCP actual header used for computing the checksum */
  char tcpcsumblock[ sizeof(tcp_phdr_t) + TCPSYN_LEN ];

  /* Although we are creating our own IP packet with the destination address */
  /* on it, the sendto() system call requires the sockaddr_in structure */
  struct sockaddr_in dstaddr;  
  
  memset(&pseudohdr,0,sizeof(tcp_phdr_t));
  memset(&packet, 0, sizeof(packet));
  memset(&dstaddr, 0, sizeof(dstaddr));   
    
  dstaddr.sin_family = AF_INET;     /* Address family: Internet protocols */
  dstaddr.sin_port = dst_prt;      /* Leave it empty */
  dstaddr.sin_addr.s_addr = dst_ip; /* Destination IP */



  /* Get a raw socket to send TCP packets */   
 if ((rawsocket = socket(AF_INET, SOCK_RAW, IPPROTO_TCP)) < 0)
 {
        perror("TCP_RST_send():socket()"); 
        exit(1);
  }
  
  /* We need to tell the kernel that we'll be adding our own IP header */
  /* Otherwise the kernel will create its own. The ugly "one" variable */
  /* is a bit obscure but R.Stevens says we have to do it this way ;-) */
  if(setsockopt(rawsocket, IPPROTO_IP, IP_HDRINCL, &one, sizeof(one)) < 0)
  {
        perror("TCP_RST_send():setsockopt()"); 
        exit(1);
   }
 
	
  /* IP Header */
  ipheader->ihl = 5;     /* Header lenght in octects                       */
  ipheader->version = 4;      /* Ip protocol version (IPv4)                     */
  ipheader->tos = 0;    /* Type of Service (Usually zero)                 */
  ipheader->tot_len = htons( sizeof (struct iphdr) + sizeof (struct tcphdr) );         
  ipheader->frag_off = 0;    /* Fragment offset. We'll not use this            */
  ipheader->ttl = 64;   /* Time to live: 64 in Linux, 128 in Windows...   */
  ipheader->protocol = 6;      /* Transport layer prot. TCP=6, UDP=17, ICMP=1... */
  ipheader->check= 0;    /* Checksum. It has to be zero for the moment     */
  ipheader->id = htons(1337); 
  ipheader->saddr = src_ip;  /* Source IP address                    */
  ipheader->daddr = dst_ip;  /* Destination IP address               */

  /* TCP Header */   
  tcpheader->seq = seq;        /* Sequence Number                         */
  tcpheader->ack_seq = htonl(1);   /* Acknowledgement Number                  */
  tcpheader->res1 = 0;           /* Variable in 4 byte blocks. (Deprecated) */
  tcpheader->doff = 5;		  /* Segment offset (Lenght of the header)   */
  tcpheader->fin = 0;
  tcpheader->syn = 0;
  tcpheader->rst = 1;
  tcpheader->psh = 0;
  tcpheader->ack = 0;
  tcpheader->urg = 0;
  tcpheader->ece = 0;
  tcpheader->cwr = 0;   /* TCP Flags. We set the Reset Flag        */
  tcpheader->window = htons(4500) + rand()%1000;/* Window size               */
  tcpheader->urg_ptr = 0;          /* Urgent pointer.                         */
  tcpheader->source = src_prt;  /* Source Port                             */
  tcpheader->dest = dst_prt;  /* Destination Port                        */
  tcpheader->check=0;            /* Checksum. (Zero until computed)         */
  
  /* Fill the pseudoheader so we can compute the TCP checksum*/
  pseudohdr.src = ipheader->saddr;
  pseudohdr.dst = ipheader->daddr;
  pseudohdr.zero = 0;
  pseudohdr.protocol = ipheader->protocol;
  pseudohdr.tcplen = htons( sizeof(struct tcphdr) );

  /* Copy header and pseudoheader to a buffer to compute the checksum */  
  memcpy(tcpcsumblock, &pseudohdr, sizeof(tcp_phdr_t));   
  memcpy(tcpcsumblock+sizeof(tcp_phdr_t),tcpheader, sizeof(struct tcphdr));
    
  /* Compute the TCP checksum as the standard says (RFC 793) */
  tcpheader->check = in_cksum((unsigned short *)(tcpcsumblock), sizeof(tcpcsumblock)); 

  /* Compute the IP checksum as the standard says (RFC 791) */
  ipheader->check = in_cksum((unsigned short *)ipheader, sizeof(struct iphdr));
    
  /* Send it through the raw socket */    
  if(sendto(rawsocket, packet, ntohs(ipheader->tot_len), 0,(struct sockaddr *) &dstaddr, sizeof (dstaddr)) < 0)
  {		
	printf("sendto faild\n");
        return -1;                     
  }
//	printf("sendto ok\n");
/*
  printf("Sent RST Packet:\n");
  printf("   SRC: %s:%d\n", inet_ntoa(ipheader->ip_src), ntohs(tcpheader->th_sport));
  printf("   DST: %s:%d\n", inet_ntoa(ipheader->ip_dst), ntohs(tcpheader->th_dport));
  printf("   Seq=%u\n", ntohl(tcpheader->th_seq));
  printf("   Ack=%d\n", ntohl(tcpheader->th_ack));
  printf("   TCPsum: %02x\n",  tcpheader->th_sum);
  printf("   IPsum: %02x\n", ipheader->ip_sum);
  */  
  close(rawsocket);

return 0;
  
  
}
int sndpkt_tcp_rst(u32 seq,u32 src_ip, u32  dst_ip, u16 src_prt, u16 dst_prt)
{
        static int i=0;
        int one=1; /* R.Stevens says we need this variable for the setsockopt call */ 
        int tcphdrlen = sizeof(struct tcphdr);
        int iphdrlen = sizeof(struct iphdr);
        /* Raw socket file descriptor */ 
        //  int rawsocket=0;  

        /* Buffer for the TCP/IP SYN Packets */
        char packet[tcphdrlen+iphdrlen +1 ];   

        /* It will point to start of the packet buffer */  
        struct iphdr *ipheader = (struct iphdr *)packet;   

        /* It will point to the end of the IP header in packet buffer */  
        struct tcphdr *tcpheader = (struct tcphdr *) (packet +iphdrlen); 

        /* TPC Pseudoheader (used in checksum)    */
        tcp_phdr_t pseudohdr;            

        /* TCP Pseudoheader + TCP actual header used for computing the checksum */
        char tcpcsumblock[ sizeof(tcp_phdr_t) + TCPSYN_LEN ];

        /* Although we are creating our own IP packet with the destination address */
        /* on it, the sendto() system call requires the sockaddr_in structure */
        struct sockaddr_in dstaddr;  

        //  memset(&pseudohdr,0,sizeof(tcp_phdr_t));
        // memset(&packet, 0, sizeof(packet));
        // memset(&dstaddr, 0, sizeof(dstaddr));   

        dstaddr.sin_family = AF_INET;     /* Address family: Internet protocols */
        dstaddr.sin_port = dst_prt;      /* Leave it empty */
        dstaddr.sin_addr.s_addr = dst_ip; /* Destination IP */

#if 0
        /* Get a raw socket to send TCP packets */   
        if ((rawsocket = socket(AF_INET, SOCK_RAW, IPPROTO_TCP)) < 0)
        {
        perror("TCP_RST_send():socket()"); 
        exit(1);
        }

        /* We need to tell the kernel that we'll be adding our own IP header */
        /* Otherwise the kernel will create its own. The ugly "one" variable */
        /* is a bit obscure but R.Stevens says we have to do it this way ;-) */
        if(setsockopt(rawsocket, IPPROTO_IP, IP_HDRINCL, &one, sizeof(one)) < 0)
        {
        perror("TCP_RST_send():setsockopt()"); 
        exit(1);
        }
#endif

        /* IP Header */
        ipheader->ihl = 5;     /* Header lenght in octects                       */
        ipheader->version = 4;      /* Ip protocol version (IPv4)                     */
        ipheader->tos = 0;    /* Type of Service (Usually zero)                 */
        ipheader->tot_len = __hns(iphdrlen +tcphdrlen);
        ipheader->frag_off = 0;    /* Fragment offset. We'll not use this            */
        ipheader->ttl = 64;   /* Time to live: 64 in Linux, 128 in Windows...   */
        ipheader->protocol = 6;      /* Transport layer prot. TCP=6, UDP=17, ICMP=1... */
        ipheader->check= 0;    /* Checksum. It has to be zero for the moment     */
        ipheader->id = __hns(1337); 
        ipheader->saddr = src_ip;  /* Source IP address                    */
        ipheader->daddr = dst_ip;  /* Destination IP address               */

        /* TCP Header */   
        tcpheader->seq = seq;        /* Sequence Number                         */
        tcpheader->ack_seq = __hnl(1);   /* Acknowledgement Number                  */
        tcpheader->res1 = 0;           /* Variable in 4 byte blocks. (Deprecated) */
        tcpheader->doff = 5;		  /* Segment offset (Lenght of the header)   */
        tcpheader->fin = 0;
        tcpheader->syn = 0;
        tcpheader->rst = 1;
        tcpheader->psh = 0;
        tcpheader->ack = 0;
        tcpheader->urg = 0;
        tcpheader->ece = 0;
        tcpheader->cwr = 0;   /* TCP Flags. We set the Reset Flag        */
        tcpheader->window = __hns(5000);/* Window size               */
        tcpheader->urg_ptr = 0;          /* Urgent pointer.                         */
        tcpheader->source = src_prt;  /* Source Port                             */
        tcpheader->dest = dst_prt;  /* Destination Port                        */
        tcpheader->check=0;            /* Checksum. (Zero until computed)         */

        /* Fill the pseudoheader so we can compute the TCP checksum*/
        pseudohdr.src = ipheader->saddr;
        pseudohdr.dst = ipheader->daddr;
        pseudohdr.zero = 0;
        pseudohdr.protocol = ipheader->protocol;
        pseudohdr.tcplen = __hns(tcphdrlen);

        /* Copy header and pseudoheader to a buffer to compute the checksum */  
        //  memcpy(tcpcsumblock, &pseudohdr, sizeof(tcp_phdr_t));   
        //  memcpy(tcpcsumblock+sizeof(tcp_phdr_t),tcpheader, sizeof(struct tcphdr));

        tcpheader->check = CalcTCPSum((unsigned short *)&pseudohdr,(unsigned short *)tcpheader,tcphdrlen);

        /* Compute the TCP checksum as the standard says (RFC 793) */
        // tcpheader->check = in_cksum((unsigned short *)(tcpcsumblock), sizeof(tcpcsumblock)); 

        /* Compute the IP checksum as the standard says (RFC 791) */
        ipheader->check = in_cksum((unsigned short *)ipheader,iphdrlen);

        /* Send it through the raw socket */    
        if(sendto(g_rawsocket , packet, __nhs(ipheader->tot_len), 0,(struct sockaddr *) &dstaddr, sizeof(dstaddr)) < 0)
        {		
                printf("Reset Faild\n");
                fprintf(stderr, "Recv Error %s\n", strerror(errno));
                return -1;                     
        }
        //  WADEBUG(D_WARNING)("Reset OK\n");
        /*
        printf("Sent RST Packet:\n");
        printf("   SRC: %s:%d\n", inet_ntoa(ipheader->ip_src), ntohs(tcpheader->th_sport));
        printf("   DST: %s:%d\n", inet_ntoa(ipheader->ip_dst), ntohs(tcpheader->th_dport));
        printf("   Seq=%u\n", ntohl(tcpheader->th_seq));
        printf("   Ack=%d\n", ntohl(tcpheader->th_ack));
        printf("   TCPsum: %02x\n",  tcpheader->th_sum);
        printf("   IPsum: %02x\n", ipheader->ip_sum);
        */
#if 0
        close(rawsocket);
#endif
        return 1;  
} /* End of IP_Id_send() */

//2009
//author : qianbo

int sndpkt_redirecturl(/*const char *ServerAddress,*/const char* HttpData,u32 seq_number,u32 ack_number,u32 src_ip, u32  dst_ip, u16 src_prt, u16 dst_prt)
{
         static int LengthHeader = sizeof(struct iphdr) + sizeof(struct tcphdr);
	  static char packet[ 40 + 256];   
	  struct iphdr *ipheader = (struct iphdr *)packet;   
	  struct tcphdr *tcpheader = (struct tcphdr *) (packet + sizeof(struct iphdr)); 
         int HttpLen = strlen(HttpData);  
	  struct sockaddr_in dstaddr;  
	  /* TCP Pseudoheader (used in checksum)    */
  	  tcp_phdr_t pseudohdr ;            

  	  ipheader->ihl = 5;     /* Header lenght in octects                       */
	  ipheader->version = 4;      /* Ip protocol version (IPv4)                     */
	  ipheader->tos = 0;    /* Type of Service (Usually zero)                 */
	  ipheader->tot_len = htons( LengthHeader + HttpLen);         
	  ipheader->frag_off = 0;    /* Fragment offset. We'll not use this            */
	  ipheader->ttl = 64;   /* Time to live: 64 in Linux, 128 in Windows...   */
	  ipheader->protocol = 6;      /* Transport layer prot. TCP=6, UDP=17, ICMP=1... */
	  ipheader->check= 0;    /* Checksum. It has to be zero for the moment     */
	  ipheader->id = htons(1337); 
	  ipheader->saddr = dst_ip;   /*Make the fake source ip address so the client want to , Source IP address */
	  ipheader->daddr = src_ip;   /* Destination IP address is our client ip address              */

	   /* TCP Header */   
	  tcpheader->seq = ack_number;        /* Sequence Number is the client's ack                        */
	  tcpheader->ack_seq =htonl (ntohl (seq_number) + HttpLen);                 /*  Acknowledgement Number                  */
	  tcpheader->res1 = 0;           /* Variable in 4 byte blocks. (Deprecated) */
	  tcpheader->doff = 5;		  /* Segment offset (Lenght of the header)   */
	  tcpheader->fin = 1;
	  tcpheader->syn = 0;
	  tcpheader->rst  = 0;
	  tcpheader->psh = 0;
	  tcpheader->ack = 1;
	  tcpheader->urg = 0;
	  tcpheader->ece = 0;
	  tcpheader->cwr = 0;   
	  tcpheader->window = htons(5000);/* Window size               */
	  tcpheader->urg_ptr = 0;          /* Urgent pointer.                         */
	  tcpheader->source = dst_prt;  /* Source Port is the desination port                             */
	  tcpheader->dest = src_prt;     /* Destination Port  is the source port                      */
	  tcpheader->check=0;            /* Checksum. (Zero until computed)         */

         memcpy(packet+LengthHeader,HttpData,HttpLen);
	  /* Fill the pseudoheader so we can compute the TCP checksum*/
	  pseudohdr.src = ipheader->saddr;
	  pseudohdr.dst = ipheader->daddr;
	  pseudohdr.zero = 0;
	  pseudohdr.protocol = ipheader->protocol;
	  pseudohdr.tcplen = htons( sizeof(struct tcphdr) + HttpLen);
	  	    
	  /* Compute the TCP checksum as the standard says (RFC 793) */
	  tcpheader->check = CalcTCPSum((unsigned short *)&pseudohdr, 
		(unsigned short *)tcpheader,
		sizeof(struct tcphdr) + HttpLen);
	  /* Compute the IP checksum as the standard says (RFC 791) */
	  ipheader->check = in_cksum((unsigned short *)ipheader, sizeof(struct iphdr));
  
//  	  memset(&dstaddr, 0, sizeof(dstaddr));   
    
	  dstaddr.sin_family = AF_INET;     /* Address family: Internet protocols */
	  dstaddr.sin_port    = src_prt;      
	  dstaddr.sin_addr.s_addr = src_ip; /* Destination IP */
	   if(sendto(g_rawsocket, packet, ntohs(ipheader->tot_len), 0,(struct sockaddr *) &dstaddr, sizeof (dstaddr)) < 0)
	  {		
		 printf("sendto faild\n");
	        return -1;                     
	  }
	  return 1;
}





