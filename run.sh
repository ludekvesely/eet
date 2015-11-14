#!/bin/bash

VOLUME_HOME="/var/lib/mysql"

sed -ri -e "s/^upload_max_filesize.*/upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE}/" \
    -e "s/^post_max_size.*/post_max_size = ${PHP_POST_MAX_SIZE}/" /etc/php5/apache2/php.ini
if [[ ! -d $VOLUME_HOME/mysql ]]; then
    echo "=> An empty or uninitialized MySQL volume is detected in $VOLUME_HOME"
    echo "=> Installing MySQL ..."
    mysql_install_db > /dev/null 2>&1
    echo "=> Creating admin user ..."
    /create_mysql_admin_user.sh
    echo "=> Importing dump.sql ..."
	mysql -u admin --password=admin --default-character-set=utf8 < /app/data/dump.sql
    echo "=> Done!"
else
    echo "=> Using an existing volume of MySQL"
fi

echo "=> Importing dump.sql ..."
/usr/bin/mysqld_safe > /dev/null 2>&1 &
RET=1
while [[ RET -ne 0 ]]; do
	echo "=> Waiting for confirmation of MySQL service startup"
	sleep 5
	mysql -uroot -e "status" > /dev/null 2>&1
	RET=$?
done
mysql -u admin --password=admin --default-character-set=utf8 < /app/data/dump.sql
mysqladmin -uroot shutdown
echo "=> Done!"

exec supervisord -n
