#!/bin/bash

#test if need to be run
if [ -f "${0}.done" ] ; then
    echo "Tacacs+ alreade installed."
    exit 0
fi

path=${0%/*}
. $path/../bin/aaa.lib

#mysql install
if ! $path/mysql-configure.sh ; then
    exit 1
fi

#mysql schema
cat $path/tacplus-configure.sql | mysql -u root --password=$pass tacacs

#tacacs configuration
mv /etc/tacacs+ /etc/tacacs+.bfaaa
tar -xzf $path/tacplus-configure/tacacs.tgz -C /etc/


#install and configure pam
cat <<TACPLUS_PRESEED | debconf-set-selections
pam-mysql pam-mysql/config_file_noread boolean true
TACPLUS_PRESEED

#install pam and nss
install libpam-mysql libnss-mysql-bg

#configure nss
cp $path/tacplus-configure/libnss-mysql* /etc/
sed -ri 's/(^[[:space:]]*passwd:.*)/\1 mysql/;s/(^[[:space:]]*group:.*)/\1 mysql/;s/(^[[:space:]]*shadow:.*)/\1 mysql/' /etc/nsswitch.conf

#configure pam
cat > etc/pam.d/common-account <<PAM_CONF
account     sufficient   pam_unix.so
account     required     pam_mysql.so config_file=/etc/tacacs+/pam-mysql.conf
PAM_CONF

cat > /etc/pam.d/common-auth <<PAM_CONF
auth    sufficient   pam_unix.so nullok_secure
auth    required     pam_mysql.so config_file=/etc/tacacs+/pam-mysql.conf
PAM_CONF

cat > /etc/pam.d/common-password <<PAM_CONF
password    sufficient  pam_unix.so nullok
password    required    pam_mysql.so config_file=/etc/tacacs+/pam-mysql.conf
PAM_CONF

cat > /etc/pam.d/common-session <<PAM_CONF
session    required     pam_mysql.so config_file=/etc/tacacs+/pam-mysql.conf
PAM_CONF
sed -ri 's/session[[:space:]]+required[[:space:]]+pam_unix.so/session    sufficient  pam_unix.so/' /etc/pam.d/common-session