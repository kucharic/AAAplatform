#!/bin/bash

path=${0%/*}
. $path/../bin/aaa.lib

if ! $path/mysql-configure.sh ; then
    exit 1
fi

if ! $path/psql-configure.sh ; then
    exit 1
fi

#config files
mv /etc/freeDiameter /etc/freeDiameter.dpkg
tar -xzf $path/freediameter-configure/freediameter.tgz -C /etc/

#daemon script
ln -sf /etc/freeDiameter/freediameter-daemon /etc/init.d/freediameter-daemon

#db for accounting
createdb -O root app_acct
psql app_acct < $path/freediameter-configure.pgsql

#db for diameap
pass='pass'
echo "DROP DATABASE IF EXISTS diameap; CREATE DATABASE diameap; GRANT ALL ON diameap.* TO 'diameter'@'localhost' IDENTIFIED BY 'diameter'" | mysql -u root --password=$pass
ccat $path/freediameter-configure.sql | mysql -u root --password=$pass diameap

/etc/init.d/freediameter-daemon stop
/etc/init.d/freediameter-daemon start
