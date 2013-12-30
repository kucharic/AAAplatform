#!/bin/bash

. ${0%/*}/../bin/aaa.lib

#openssh server & sudo
install openssh-server sudo

#basrc
ln -sf /srv/www/aaa/.install/workspace-configure/bash.bashrc /etc/bash.bashrc

#local.rc
sed -r "/^[[:space:]]*exit 0/q" /etc/rc.local | grep -v '/srv/www/aaa/bin/issue.sh' | head -n-1 > /etc/rc.local.new
echo '/srv/www/aaa/bin/issue.sh > /etc/issue' >> /etc/rc.local.new
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

