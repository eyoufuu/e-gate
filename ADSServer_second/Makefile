SRC  := ADSServer.c DPClient.c FileLog.c getParse.c global_time.c mysqlHandle.c pop3Parse.c postParse.c smtpParse.c String.c bdbmem.c  timetick.c webmail.c pktsend.c
OBJS := ADSServer.o DPClient.o FileLog.o getParse.o global_time.o mysqlHandle.o pop3Parse.o postParse.o smtpParse.o String.o bdbmem.o  timetick.o webmail.o pktsend.o
LIBS := -lpthread -L./mysql/ -lmysqlclient -lm -ldb -L./sharedmem/ -lsharedmem  
PARA :=  -g -c -I./mysql/include -I./

adserver:$(OBJS)
	gcc -o $@ $^ $(LIBS)
ADSServer.o:ADSServer.c
	gcc $(PARA) -o $@ $< $(LIBS)
DPClient.o:DPClient.c
	gcc $(PARA) -o $@ $<
FileLog.o:FileLog.c
	gcc $(PARA) -o $@ $<
getParse.o:getParse.c
	gcc $(PARA) -o $@ $<
global_time.o:global_time.c
	gcc $(PARA) -o $@ $<
mysqlHandle.o:mysqlHandle.c
	gcc $(PARA) -o $@ $< -lnfnetlink
pop3Parse.o:pop3Parse.c
	gcc $(PARA) -o $@ $<
postParse.o:postParse.c
	gcc $(PARA) -o $@ $<
smtpParse.o:smtpParse.c
	gcc $(PARA) -o $@ $<
String.o:String.c
	gcc $(PARA) -o $@ $<
bdbmem.o:bdbmem.c
	gcc $(PARA) -o $@ $<
timetick.o:timetick.c
	gcc $(PARA) -o $@ $<
webmail.o:webmail.c
	gcc $(PARA) -o $@ $<
pktsend.o:pktsend.c
	gcc $(PARA) -o $@ $<
clean:
	rm $(OBJS) adserver

