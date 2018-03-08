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

//ʱ��ι���
enum{
	GATE_CLOSE=0,
	GATE_OPEN =1
};



struct time_seg
{
    __u8 _gate;    //Ĭ��ȫ���л���ȫ���
    __u8 _access ; //ʱ����Ƿ���
    __u8 _weekly ; //0��1��,��λ����
    __u8 _reserve; //���λ����
    __u16 _times1 ;
    __u16 _timee1 ;
    __u16 _times2 ;
    __u16 _timee2 ;
};//12���ֽ�


//(1)������ַ 4�ֽ����α�ʾ������ 4�ֽ����α�ʾIP��1�ֽ�char��ʾ �������      ����������������
//(2)��ط�Χ 4�ֽ����α�ʾ������ 4�ֽ����α�ʾIPStart��4�ֽ����α�ʾIPEnd�� ����������������
//(3)ʱ���   4�ֽڱ�ʾ�Ƿ�����ʱ��Σ�0��1������2�ֽ����α�ʾʱ�俪ʼ(TimeStart����2�ֽ����α�ʾʱ�����(TimeEnd)��2�ֽڱ�ʾʱ���2��ʼ��2�ֽڱ�ʾʱ���2����
//(4��Ĭ����ϻ��Ƿ���

#endif
