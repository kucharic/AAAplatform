#!/bin/bash

. ${0%/*}/../bin/aaa.lib

${0%/*}/mysql-configure.sh

mv /etc/freeradius /etc/freeradius.dpkg
tar -xzf ${0%/*}/freeradius/freeradius.tgz -C /etc/

/etc/init.d/freeradius restart
