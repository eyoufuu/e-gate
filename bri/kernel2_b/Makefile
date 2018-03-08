obj-m +=briflow2.o
#VPATH=/home/fy/develop/libnetfilter_queue-0.0.16/include:/home/fy/linux-2.6.26.8/include
all:
	make -C /usr/src/linux M=$(PWD) modules
#	gcc -o user_test -I$(VPATH) user_test.c
clean:
	make -C /usr/src/linux M=$(PWD) clean
	
install:
	/sbin/insmod briflow2.ko
remove:
	/sbin/rmmod briflow2.ko


