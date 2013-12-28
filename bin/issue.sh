#!/bin/bash

echo "Debian GNU/Linux 7.0 \n \l" > /etc/issue

echo "Debian GNU/Linux 7.0" > /etc/issue.net

for i in /etc/issue* ; do
    echo "
 _______ _______ _______         __         __    ___                      
|   _   |   _   |   _   |.-----.|  |.---.-.|  |_.'  _|.-----.----.--------.
|       |       |       ||  _  ||  ||  _  ||   _|   _||  _  |   _|        |
|___|___|___|___|___|___||   __||__||___._||____|__|  |_____|__| |__|__|__|
                         |__|                                              
To login use

u: root
p: pass

or direct your web browser to:
http://$(ifconfig eth0 | awk '/inet addr/ {print $2}' | cut -f2 -d:)/
    " >> $i
done
