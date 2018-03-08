#ifndef __IMP2_H__
#define __IMP2_H__

#define IMP2_U_PID   0
#define IMP2_K_MSG   1
#define IMP2_CLOSE   2
#define IMP2_SPECIAL_IP 3
#define IMP2_MONITOR_SCOPE 4
#define IMP2_TIME_SEG 5

#define IMP2_IPMAC_STATUS 6
#define IMP2_IP_MAC 7
#define NL_IMP2      31

struct packet_info
{
  __u32 src;
  __u32 dest;
};

//时间段管理
enum{
	GATE_CLOSE=0,
	GATE_OPEN =1
};



struct time_seg
{
    __u8 _gate;    //默认全放行还是全阻断
    __u8 _access ; //时间段是否开启
    __u8 _weekly ; //0断1开,八位星期
    __u8 _reserve; //后八位空着
    __u16 _times1 ;
    __u16 _timee1 ;
    __u16 _times2 ;
    __u16 _timee2 ;
};//12个字节


//(1)特例网址 4字节整形表示个数， 4字节整形表示IP，1字节char表示 放行阻断      。。。。。。。。
//(2)监控范围 4字节整形表示段数， 4字节整形表示IPStart，4字节整形表示IPEnd， 。。。。。。。。
//(3)时间段   4字节表示是否启用时间段（0断1开），2字节整形表示时间开始(TimeStart），2字节整形表示时间结束(TimeEnd)，2字节表示时间段2开始，2字节表示时间段2结束
//(4）默认阻断还是放行

#endif
