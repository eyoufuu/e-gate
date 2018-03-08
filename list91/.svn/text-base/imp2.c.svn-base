#include "imp2.h"


#define SKFD_OK 1
#define SKFD_NOTOK 0


static int skfd;
static int skfdInitizlize = SKFD_NOTOK;
struct sockaddr_nl local;
struct sockaddr_nl kpeer;
u32 kpeerlen = sizeof(struct sockaddr_nl);

static void sig_int(int signo)
{
	  struct sockaddr_nl kpeer;
	  msg_to_kernel message;
	  memset(&message, 0, sizeof(message));
	  message.hdr.nlmsg_len = NLMSG_LENGTH(0);
	  message.hdr.nlmsg_flags = 0;
	  message.hdr.nlmsg_type = IMP2_CLOSE;
	  message.hdr.nlmsg_pid = getpid();

	  sendto(skfd, &message, message.hdr.nlmsg_len, 0, (struct sockaddr *)(&kpeer), sizeof(kpeer));
	  close(skfd);
}

void InitializeKernelMessage(msg_to_kernel* message)
{
	  skfd = socket(PF_NETLINK, SOCK_RAW, NL_IMP2);
	  if(skfd < 0)
	  {
	         printf("can not create a netlink socket\n");
	  }

	  memset(&kpeer, 0, sizeof(kpeer));
	  kpeer.nl_family = AF_NETLINK;
	  kpeer.nl_pid = 0;
	  kpeer.nl_groups = 0;

	  memset(&local, 0, sizeof(local));
	  local.nl_family = AF_NETLINK;
	  local.nl_pid = getpid();
	  local.nl_groups = 0;
	  if(bind(skfd, (struct sockaddr*)&local, sizeof(local)) != 0)
	  {
	      return ;
	  }
	  message->hdr.nlmsg_len   = NLMSG_LENGTH(0);
	  message->hdr.nlmsg_flags = 0;
	  message->hdr.nlmsg_type  = IMP2_U_PID;
	  message->hdr.nlmsg_pid   = local.nl_pid;
	  sendto(skfd, message, message->hdr.nlmsg_len, 0,(struct sockaddr*)&kpeer, sizeof(kpeer));
          signal(SIGINT, sig_int);
	  skfdInitizlize = SKFD_OK;
}
void SendKernelMessage (char* buf, char type, char cmd, u16 len)
{

	  int kpeerlen;
	  msg_to_kernel message;

	  struct in_addr addr;

  	  //memset(&message, 0, sizeof(message));
  	  if(skfdInitizlize ==SKFD_NOTOK)
  	 {
  	  	     printf("Initialize Send Kernel Message!\n");
		     InitializeKernelMessage(&message);
  	 }
	  if(skfdInitizlize!=SKFD_OK)
	 {
	  	    printf("can't create sock skfd!\n");
		    return ;
	 }
	 if(len<MAX_PAYLOAD)
	  {
	  	  memcpy(message.pay_load,buf,len);
		  message.hdr.nlmsg_len = NLMSG_LENGTH(len);
		  message.hdr.nlmsg_flags = 0;
		  message.hdr.nlmsg_type =  cmd;
		  message.hdr.nlmsg_pid = local.nl_pid;	
		  sendto(skfd,&message,message.hdr.nlmsg_len,0,(struct sockaddr*)&kpeer,sizeof(kpeer));
	  }
	  else
	  {
	  	printf("Too big payload SpecialIP!\n");
	  }
            
	  /*while(1)
	  {
	      kpeerlen = sizeof(struct sockaddr_nl);
	      rcvlen = recvfrom(skfd, &info, sizeof(struct u_packet_info),
				0, (struct sockaddr*)&kpeer, &kpeerlen);

	      addr.s_addr = info.icmp_info.src;
	      printf("src: %s, ", inet_ntoa(addr));
	      addr.s_addr = info.icmp_info.dest;
	      printf("dest: %s\n", inet_ntoa(addr));
	  }*/
	  return ;
}
int RecvKernelMessage(u_packet_info* info,u32 infolen)
{
	return recvfrom(skfd,info,infolen,0,(struct sockaddr*)&kpeer,&kpeerlen);
}
