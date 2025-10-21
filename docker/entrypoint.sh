#!/bin/sh
# mount your api file to <location>
# processors and routes can be generated from this

# Docker run \
#      -v my-openapi.json:/app/mocking.json
#      -p 8080:8080 -p 8081:8081 (mapping the ports image:local)
#      membrane-mockserver

# Inside this script (or anything it executes) we need:
# - figure out if they passed yaml or json
#   - complain at them if they didn't
#   - validate it with membrane
#     - complain at them if it fails
#   - then run codegen for router and processors
# - run migrations

/app/bin/codegen
if ! [ -f /api.json || -f /api.yml  || -f /api.yaml]; then
    echo 'Your OpenAPI must be mounted to /api.json or /api.yaml';
    exit 1;
fi

/app/bin/migrate

echo 'starting php-fpm in background'
php-fpm &

echo 'starting nginx'
nginx -g 'daemon off;'
