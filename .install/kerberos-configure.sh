#!/bin/bash

#test if need to be run
if [ -f "${0}.done" ] ; then
    echo "Kerberos alreade installed."
    exit 0
fi

. ${0%/*}/../bin/aaa.lib

#configure installation
cat <<KRB_PRESEED | debconf-set-selections
krb5-admin-server krb5-admin-server/kadmind boolean true
krb5-admin-server krb5-admin-server/newrealm boolean true
krb5-config krb5-config/add_servers_realm string KME.FEL.CVUT.CZ
krb5-config krb5-config/read_conf boolean true
krb5-config krb5-config/kerberos_servers string aaa
krb5-config krb5-config/default_realm string KME.FEL.CVUT.CZ
krb5-config krb5-config/add_servers boolean true
krb5-config krb5-config/admin_server string aaa
krb5-kdc krb5-kdc/debconf boolean true
krb5-kdc krb5-kdc/purge_data_too boolean false
KRB_PRESEED

#install
install krb5-admin-server krb5-config krb5-kdc

echo -e "pass\npass\n" | krb5_newrealm

for i in /etc/krb5* ; do
    mv $i $i.bfaaa
done
tar -xzf $path/kerberos-configure/kerberos.tgz -C /etc/
cp /etc/krb5kdc.bfaaa/stash /etc/krb5kdc/

/etc/init.d/krb5-admin-server restart
/etc/init.d/krb5-kdc restart

#policies
kadmin.local -q 'add_policy -minlength 4 -minclasses 1 admin'
kadmin.local -q 'add_policy -minlength 3 -minclasses 1 host'
kadmin.local -q 'add_policy -minlength 3 -minclasses 1 service'
kadmin.local -q 'add_policy -minlength 3 -minclasses 1 user'

#disable using
touch "${0}.done"
exit 0