#!/bin/bash
# Argument = -d -i ip -m mask -g gateway

usage()
{
cat << EOF
usage: $0 options

This script configure network on a machine.

OPTIONS:
   -d      Use dynamic (DHCP) settings othervise
           use static settings given as -i -im -g arguments
   -i      Specifi IP
   -m      Specifi mask
   -g      Specifi gateway
   -h      Print this help
EOF
}

use_dhcp () {
    echo "
# The loopback network interface
auto lo
iface lo inet loopback

# The primary network interface
auto eth0
iface eth0 inet dhcp
" > /etc/network/interfaces
}

use_static () {
    echo "
# The loopback network interface
auto lo
iface lo inet loopback

# The primary network interface
auto eth0
iface eth0 inet static
    address $IP
    netmask $MASK
    gateway $GW
" > /etc/network/interfaces   
}

net_reload () {
    interfaces=$(ifconfig | awk '/^[a-zA-Z0-9]/ {print $1}')
    for i in $interfaces ; do
        /sbin/ifdown $i
        /sbin/ifconfig $i down
    done

    for i in $interfaces ; do
        /sbin/ifup $i
    done

    /srv/www/aaa/bin/network-hosts-generator.sh   
}

IP=
MASK=
GW=
while getopts “hdi:m:g:” OPTION ; do
    case $OPTION in
        h)
            usage
            exit 1
            ;;
        d)
            use_dhcp
            net_reload
            exit 0
            ;;
        i)
            IP=$OPTARG
            ;;
        m)
            MASK=$OPTARG
            ;;
        g)
            GW=$OPTARG
            ;;
        ?)
            usage
            exit 1
            ;;
    esac
done

if [[ -z $IP ]] || [[ -z $MASK ]] || [[ -z $GW ]] ; then
    usage
    exit 1
else
    use_static
    net_reload
    exit 0
fi

