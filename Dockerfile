FROM tutum/lamp:latest
EXPOSE 80 3306
ENV APPLICATION_ENV production
ENV MYSQL_PASS admin
RUN rm -fr /app && git clone https://github.com/ludekvesely/eet.git /app
RUN cd /app && chmod 777 log && php -r "readfile('https://getcomposer.org/installer');" | php && php composer.phar install && rm composer*
ADD run.sh /run.sh
RUN chmod 755 /*.sh
CMD ["/run.sh"]
