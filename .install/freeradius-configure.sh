#!/bin/bash

path=${0%/*}
. $path/../bin/aaa.lib

if ! $path/mysql-configure.sh ; then
    exit 1
fi

mv /etc/freeradius-configure /etc/freeradius.dpkg
tar -xzf $path/freeradius/freeradius.tgz -C /etc/

pass='pass'
echo 'DROP DATABASE radius; CREATE DATABASE radius; ALTER DATABASE `radius` COLLATE utf8_bin;' | mysql -u root --password=$pass
cat /etc/freeradius/sql/mysql/schema.sql | mysql -u root --password=$pass radius 
cat /etc/freeradius/sql/mysql/nas.sql | mysql -u root --password=$pass radius
cat $path/freeradius-configure.sql | mysql -u root --password=$pass radius


/etc/init.d/freeradius restart
