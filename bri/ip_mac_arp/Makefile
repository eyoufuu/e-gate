obj-m :=iarp.o
iarp-objs :=arp_hook.o ip_mac_arp.o
all:
	make -C /usr/src/linux M=$(PWD) modules
clean:
	make -C /usr/src/linux M=$(PWD) clean
	
install:
	/sbin/insmod iarp.ko
remove:
	/sbin/rmmod iarp.ko 