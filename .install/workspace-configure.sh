#!/bin/bash

. ${0%/*}/../bin/aaa.lib

#mysql install
if ! $path/mysql-configure.sh ; then
    exit 1
fi

#openssh server & sudo
install openssh-server sudo

#basrc
ln -sf /srv/www/aaa/.install/workspace-configure/bash.bashrc /etc/bash.bashrc

#local.rc
sed -r "/^[[:space:]]*exit 0/q" /etc/rc.local | grep -v '/srv/www/aaa/bin/issue.sh' | head -n-1 > /etc/rc.local.new
echo '/srv/www/aaa/bin/issue.sh > /etc/issue' >> /etc/rc.local.new
echo '/srv/www/aaa/bin/network-hosts-generator.sh' >> /etc/rc.local.new
sed -r "/^[[:space:]]*exit 0/q" /etc/rc.local | tail -n-1 >> /etc/rc.local.new
mv /etc/rc.local.new /etc/rc.local

#hostname
echo 'aaa' > /etc/hostname

#dhcp-client conf
cat > /etc/dhcp/dhclient.conf <<EOF
option rfc3442-classless-static-routes code 121 = array of unsigned integer 8;

send host-name = gethostname();
request subnet-mask, broadcast-address, time-offset, routers,
    domain-name-servers,
    netbios-name-servers, netbios-scope, interface-mtu,
    rfc3442-classless-static-routes, ntp-servers;
EOF

#hosts settings
${0%/*}/../bin/network-hosts-generator.sh 

#rsyslog to mysql
cat <<RSYSLOGMYSQL_PRESEED | debconf-set-selections
rsyslog-mysql rsyslog-mysql/mysql/app-pass password syslog
rsyslog-mysql rsyslog-mysql/app-password-confirm: password syslog
rsyslog-mysql rsyslog-mysql/password-confirm password pass
rsyslog-mysql rsyslog-mysql/mysql/admin-pass password pass
rsyslog-mysql rsyslog-mysql/remote/port string 3306
rsyslog-mysql rsyslog-mysql/database-type string mysql
rsyslog-mysql rsyslog-mysql/db/dbname string Syslog
rsyslog-mysql rsyslog-mysql/mysql/admin-user string root
rsyslog-mysql rsyslog-mysql/remote/newhost string localhost
rsyslog-mysql rsyslog-mysql/mysql/method string tcp/ip
rsyslog-mysql rsyslog-mysql/internal/skip-preseed string false
rsyslog-mysql rsyslog-mysql/remote/host string localhost
rsyslog-mysql rsyslog-mysql/dbconfig-install boolean true
rsyslog-mysql rsyslog-mysql/upgrade-backup boolean true
rsyslog-mysql rsyslog-mysql/dbconfig-reinstall boolean false
rsyslog-mysql rsyslog-mysql/purge boolean false
rsyslog-mysql rsyslog-mysql/db/app-user string rsyslog
rsyslog-mysql rsyslog-mysql/internal/reconfiguring boolean false
rsyslog-mysql rsyslog-mysql/dbconfig-upgrade string true
RSYSLOGMYSQL_PRESEED
install rsyslog-mysql

cat > /etc/rsyslog.d/mysql.conf <<EOF
### Configuration file for rsyslog-mysql
### Changes are preserved

$ModLoad ommysql

$WorkDirectory /tmp/rsyslog # default location for work (spool) files

$ActionQueueType LinkedList # use asynchronous processing
$ActionQueueFileName dbq    # set file name, also enables disk mode
$ActionResumeRetryCount 2  # infinite retries on insert failure

*.* :ommysql:localhost,Syslog,rsyslog,syslog
EOF

#pass='pass'
#echo "DROP DATABASE IF EXISTS Syslog; CREATE DATABASE Syslog; ALTER DATABASE `Syslog` COLLATE utf8_bin; GRANT ALL ON Syslog.* TO 'rsyslog'@'localhost' IDENTIFIED BY 'syslog'" | mysql -u root --password=$pass
#cat /usr/share/dbconfig-common/data/rsyslog-mysql/install/mysql | mysql -u root --password=$pass Syslog

/etc/init.d/rsyslog restart
