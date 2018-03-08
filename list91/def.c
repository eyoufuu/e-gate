#define __u8 unsigned char
#define __u16 unsigned short 
#define __u32 unsigned int

static inline __u16 __nhs(__u16 x)
{
	return x<<8 | x>>8;
}
static inline __u32 __nhl(__u32 x)
{
	return x<<24 | x>>24 |
		(x & (__u32)0x0000ff00UL)<<8 |
		(x & (__u32)0x00ff0000UL)>>8;
}

#define __hnl(x) __nhl(x)
#define __hns(x) __nhs(x)

#define ___constant_swab16(x) \
	((__u16)( \
		(((__u16)(x) & (__u16)0x00ffU) << 8) | \
		(((__u16)(x) & (__u16)0xff00U) >> 8) ))
#define ___constant_swab32(x) \
	((__u32)( \
		(((__u32)(x) & (__u32)0x000000ffUL) << 24) | \
		(((__u32)(x) & (__u32)0x0000ff00UL) <<  8) | \
		(((__u32)(x) & (__u32)0x00ff0000UL) >>  8) | \
		(((__u32)(x) & (__u32)0xff000000UL) >> 24) ))

#define __constant_htonl(x) (___constant_swab32(x))
#define __constant_ntohl(x) (___constant_swab32(x))
#define __constant_htons(x) (___constant_swab16(x))
#define __constant_ntohs(x) (___constant_swab16(x))


#define get_u8(X,O)  (*(__u8 *)(X + O))
#define get_u16(X,O)  (*(__u16 *)(X + O))
#define get_u32(X,O)  (*(__u32 *)(X + O))

#define GET_UP_INNER_IP(data)		(*(__u32*)(data+12))
#define GET_UP_OUTTER_IP(data)        (*(__u32 int*)(data+16))

#define GET_DOWN_INNER_IP(data)     (*(__u32*)(data+16))
#define GET_DOWN_OUTTER_IP(data)     (*(__u32*)(data+12))


#define GET_UP_INNER_PORT(ihl,data)   (data[ihl]*256+data[ihl+1])
#define GET_UP_OUTTER_PORT(ihl,data)  (data[ihl+2]*256+data[ihl+3])

#define GET_DOWN_INNER_PORT(ihl,data)  GET_UP_OUTTER_PORT(ihl,data)
#define GET_DOWN_OUTTER_PORT(ihl,data) GET_UP_INNER_PORT(ihl,data)

//得到tcp包和udp包的偏移量
inline get_ip_pack_h_len(const unsigned char *data)
{
	return 4*(data[0] & 0x0f);
}

int app_data_offset(const unsigned char *data,char ip_protocol)
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
