#!/bin/sh
/app/bin/codegen
/app/bin/migrate

echo 'starting php-fpm in background'
php-fpm &

echo 'starting nginx'
nginx -g 'daemon off;'
