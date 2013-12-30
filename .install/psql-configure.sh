#!/bin/bash

#test if need to be run
if [ -f "${0}.done" ] ; then
    echo "PotgreSql alreade installed."
    exit 0
fi

. ${0%/*}/../bin/aaa.lib

#install
install postgresql
if ! $? ; then
    echo "Resolve issue and install again!"
    exit 1
fi

echo 'local all all trust' >> /etc/postgresql/*/main/pg_hba.conf
su - postgres
echo "CREATE USER root WITH PASSWORD 'pass';" | psql template1
logout

#disable using
touch "${0}.done"
exit 0