#!/bin/bash

#test if need to be run
if [ -f "${0}.done" ] ; then
    echo "Mysql alreade installed."
    exit 0
fi

. ${0%/*}/../bin/aaa.lib

#configure installation
cat <<MYSQL_PRESEED | debconf-set-selections
mysql-server/root_password_again password pass
mysql-server/root_password password pass
mysql-server-5.5/postrm_remove_databases boolean false
mysql-server-5.5/start_on_boot boolean true
MYSQL_PRESEED

#install
install mysql-common mysql-client-5.5 mysql-server mysql-server-5.5

#disable using
touch "${0}.done"