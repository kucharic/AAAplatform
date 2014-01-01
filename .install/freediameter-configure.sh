#!/bin/bash

#test if need to be run
if [ -f "${0}.done" ] ; then
    echo "Mysql alreade installed."
    exit 0
fi

path=${0%/*}
. $path/../bin/aaa.lib

if ! $path/mysql-configure.sh ; then
    exit 1
fi

if ! $path/psql-configure.sh ; then
    exit 1
fi

#config files
mv /etc/freeDiameter /etc/freeDiameter.bfaaa
tar -xzf $path/freediameter-configure/freediameter.tgz -C /etc/

#daemon script
ln -sf /etc/freeDiameter/freediameter-daemon /etc/init.d/freediameter-daemon

#db for accounting
if createdb -O root app_acct ; then 
    psql app_acct < $path/freediameter-configure.pgsql
fi

#db for diameap
pass='pass'
echo "DROP DATABASE IF EXISTS diameap; CREATE DATABASE diameap; GRANT ALL ON diameap.* TO 'diameter'@'localhost' IDENTIFIED BY 'diameter'" | mysql -u root --password=$pass
cat $path/freediameter-configure.sql | mysql -u root --password=$pass diameap

insserv freediameter-daemon

/etc/init.d/freediameter-daemon stop
/etc/init.d/freediameter-daemon start

touch "${0}.done"