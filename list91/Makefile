SRC  := emp.c log2.c policy.c global.c ctl.c cli.c serv.c user.c if.c filetype.c bdbmem.c  \
        readsql.c pop3.c post.c smtp.c keyword.c webcat.c bdb.c bwhost.c imp2.c \
        ./http/httpsearch.c ./sndpkt/pktsend.c \
        ./proanalyse/core_engine.c ./proanalyse/module_config.c ./proanalyse/pro_analyse_http.c \
        ./proanalyse/pro_analyse_im.c ./proanalyse/pro_analyse_game.c ./proanalyse/pro_analyse_ip.c \
        ./proanalyse/pro_analyse_other.c ./proanalyse/pro_analyse_p2p.c ./proanalyse/pro_analyse_stock.c \
        ./proanalyse/core_engine_ftp.c
OBJS := emp.o log2.o policy.o global.o ctl.o cli.o serv.o user.o if.o filetype.o bdbmem.o \
		readsql.o pop3.o post.o smtp.o keyword.o webcat.o bdb.o bwhost.o imp2.o  \
		./http/httpsearch.o ./sndpkt/pktsend.o \
		./proanalyse/core_engine.o ./proanalyse/module_config.o ./proanalyse/pro_analyse_http.o \
        ./proanalyse/pro_analyse_im.o ./proanalyse/pro_analyse_game.o ./proanalyse/pro_analyse_ip.o \
        ./proanalyse/pro_analyse_other.o ./proanalyse/pro_analyse_p2p.o ./proanalyse/pro_analyse_stock.o ./proanalyse/core_engine_ftp.o
LIBS := -lpthread -L./lib/ -lnetfilter_queue  -L./lib/ -lmysqlclient -lm -ldb -L./sharedmem/ -lsharedmem
PARA :=  -g -c -I./mysql/ -I./ -I./proanalyse/

user:$(OBJS)
	gcc -o $@ $^ $(LIBS)
user.o:user.c
	gcc $(PARA) -o $@ $< $(LIBS)
emp.o:emp.c
	gcc $(PARA) -o $@ $<
log2.o:log2.c
	gcc $(PARA) -o $@ $<
policy.o:policy.c
	gcc $(PARA) -o $@ $<
global.o:global.c
	gcc $(PARA) -o $@ $<
if.o:if.c
	gcc $(PARA) -o $@ $< -lnfnetlink
filetype.o:filetype.c
	gcc $(PARA) -o $@ $<
ctl.o:ctl.c
	gcc $(PARA) -o $@ $<
cli.o:cli.c
	gcc $(PARA) -o $@ $<
serv.o:serv.c
	gcc $(PARA) -o $@ $<
bdbmem.o:bdbmem.c
	gcc $(PARA) -o $@ $<
readsql.o:readsql.c
	gcc $(PARA) -o $@ $<
pop3.o:pop3.c
	gcc $(PARA) -o $@ $<
smtp.o:smtp.c
	gcc $(PARA) -o $@ $<
post.o:post.c
	gcc $(PARA) -o $@ $<
keyword.o:keyword.c
	gcc $(PARA) -o $@ $<	
webcat.o:webcat.c
	gcc $(PARA) -o $@ $<
bdb.o:bdb.c
	gcc $(PARA) -o $@ $<
bwhost.o:bwhost.c
	gcc $(PARA) -o $@ $<
imp2.o:imp2.c
	gcc $(PARA) -o $@ $<
httpsearch.o:httpsearch.c
	gcc $(PARA) -o $@ $<
pktsend.o:pktsend.c	
	gcc $(PARA) -o $@ $<
module_config.o:module_config.c
	gcc $(PARA) -o $@ $<
core_engine.o:core_engine.c
	gcc $(PARA) -o $@ $<
core_engine_ftp.o:core_engine_ftp.c
	gcc $(PARA) -o $@ $<
pro_analyse_http.o:pro_analyse_http.c
	gcc $(PARA) -o $@ $<
pro_analyse_im.o:pro_analyse_im.c
	gcc $(PARA) -o $@ $<
pro_analyse_game.o:pro_analyse_game.c
	gcc $(PARA) -o $@ $<
pro_analyse_ip.o:pro_analyse_ip.c
	gcc $(PARA) -o $@ $<
pro_analyse_other.o:pro_analyse_other.c
	gcc $(PARA) -o $@ $<
pro_analyse_p2p.o:pro_analyse_p2p.c
	gcc $(PARA) -o $@ $<
pro_analyse_stock.o:pro_analyse_stock.c
	gcc $(PARA) -o $@ $<
clean:
	rm $(OBJS) user
