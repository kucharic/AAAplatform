#!/bin/bash

#test if need to be run
if [ -f "${0}.done" ] ; then
    echo "PotgreSql alreade installed."
    exit 0
fi

. ${0%/*}/../bin/aaa.lib

#install
install postgresql
if (( $? != 0 )) ; then
    echo "Resolve issue and install again!"
    exit 1
fi

echo 'local all all trust' >> /etc/postgresql/*/main/pg_hba.conf
/etc/init.d/postgresql restart

su - postgres -c "psql template1 < $path/psql-configure.pgsql"

#disable using
touch "${0}.done"
exit 0