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

CONFIG=$(find "/config" -maxdepth 1 -name "*config.php" | head -n 1);
if [ -f "$CONFIG" ]; then
    cat "$CONFIG" > "/app/config/mocking.development.config.php";
fi

API=$(find "/api" -maxdepth 1 -name "*.yaml" -o -name "*.yml" -o -name "*.json" | head -n 1);
if ! [ -f "$API" ]; then
    echo "Your OpenAPI must be mounted in $DIRECTORY";
    exit 1;
fi

if ! /app/bin/validate-user-api $API; then
  exit 1;
fi

if ! /app/bin/codegen $API; then
  exit 1;
fi

if ! /app/bin/migrate; then
  exit 1;
fi

chmod -R 777 /app/storage/

# Start php-fpm in the background
php-fpm &

# Get the PID of the php-fpm process
PHP_FPM_PID=$!

# Start nginx in the foreground with daemon off
nginx -g 'daemon off;' &

# Get the PID of the nginx process
NGINX_PID=$!

# Function to handle SIGINT signal
trap 'echo "Caught SIGINT signal"; kill -TERM $PHP_FPM_PID; wait $PHP_FPM_PID 2>/dev/null; kill -TERM $NGINX_PID; wait $NGINX_PID 2>/dev/null; exit 0' SIGINT

# Wait for all background processes to complete
wait $NGINX_PID
