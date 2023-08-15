FROM mysql:5.7.16

# Permite conexiones externas
#RUN echo 'bind-address = *' >> /etc/mysql/my.cnf
RUN echo 'bind-address = 0.0.0.0' >> /etc/mysql/mysql.conf.d/mysqld.cnf

# Cambia el puerto por defecto
#RUN echo 'port = 3307' >> /etc/mysql/my.cnf
RUN echo 'port = 3307' >> /etc/mysql/mysql.conf.d/mysqld.cnf

RUN echo 'tls_version = TLSv1,TLSv1.1,TLSv1.2' >> /etc/mysql/mysql.conf.d/mysqld.cnf