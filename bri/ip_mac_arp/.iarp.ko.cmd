cmd_/bri/ip_mac_arp/iarp.ko := ld -r -m elf_i386 -T /usr/src/linux-2.6.32.6/scripts/module-common.lds --build-id -o /bri/ip_mac_arp/iarp.ko /bri/ip_mac_arp/iarp.o /bri/ip_mac_arp/iarp.mod.o
