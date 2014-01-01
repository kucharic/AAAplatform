#!/bin/bash
cat > /etc/hosts <<EOF
127.0.0.1   localhost localhost.local
$(ifconfig eth0 | tr '\n' ' ' | sed -r 's/.*inet addr:([0-9.]+) .*/\1/')   aaa.kme.fel.cvut.cz aaa

# The following lines are desirable for IPv6 capable hosts
::1     ip6-localhost ip6-loopback
fe00::0 ip6-localnet
ff00::0 ip6-mcastprefix
ff02::1 ip6-allnodes
ff02::2 ip6-allrouters
EOF