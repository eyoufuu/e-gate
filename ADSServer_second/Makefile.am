AUTOMAKE_OPTIONS = foreign
INCLUDES = -I ../base  -I ../base/mysql
#LIBS = -L /usr/lib/mysql -lmysqlclient -lpthread -ldb -lm
LIBS = -L /usr/local/mysql/lib/mysql  -L /home/fy/list91/lib -lmysqlclient -lpthread -ldb -lm -lsharedmem
bin_PROGRAMS=ADSServer
ADSServer_LDADD= ../base/libbase.a 
ADSServer_SOURCES=ADSServer.c postParse.c DPClient.c smtpParse.c pop3Parse.c timetick.c global_time.c flowParse.c getParse.c
