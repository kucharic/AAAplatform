#!/bin/bash

service=$1
name=$2
if ret=$(pgrep $service  && netstat -pvlentu | grep -qc $service) ; then
    echo "$name: OK"
else
    echo "$name: ERR"
fi