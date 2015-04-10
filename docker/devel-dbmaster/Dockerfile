#Developer dbmaster machine v0.0.1
FROM percona:5.6

MAINTAINER Antonio Hernández "antonio.hernandez@panamedia.net"

ADD init_structure.sql /init_structure.sql
ADD init_database.sh /init_database.sh
RUN chmod 755 /init_database.sh