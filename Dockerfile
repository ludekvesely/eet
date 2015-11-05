FROM tutum/lamp:latest
RUN rm -fr /app && git clone https://github.com/ludekvesely/eet.git /app
EXPOSE 80 3306
ENV APPLICATION_ENV production
ENV MYSQL_PASS admin
RUN cp /run.sh /run.tmp && sed '$ d' /run.tmp > /run.sh && rm -f /run.tmp && \
	echo 'mysql -u admin --password=admin < migrations/dump.sql' >> /run.sh && \
	echo 'exec supervisord -n' >> /run.sh
CMD ["/run.sh"]
