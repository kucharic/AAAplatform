#!/bin/bash

cat > /etc/apache2/sites-enabled/001-dsm <<EOF
<VirtualHost *:80>
   ServerName dsm

   DocumentRoot /srv/www/aaa/

   Alias /robots.txt /srv/www/aaa/static/robots.txt
   Alias /favicon.ico /srv/www/aaa/static/favicon.ico
   Alias /static /srv/www/aaa/static

   ErrorLog /srv/www/aaa/logs/error.log
   CustomLog /srv/www/aaa/logs/access.log combined
</VirtualHost>
EOF

rm /etc/apache2/sites-enabled/000-default

cat > /etc/sudoers.d/AAAplatform <<EOF
www-data    ALL=(ALL:ALL) NOPASSWD: ALL

EOF

a2enmod rewrite

/etc/init.d/apache2 restart

touch "${0}.done"