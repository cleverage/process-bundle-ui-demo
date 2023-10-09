#!/usr/bin/env bash
set -eux

if [[ "${XDEBUG_MODE}" != "off" ]]; then
    docker-php-ext-enable xdebug
fi

[ "$1" == 'bin/console' ] || [ "$1" == 'bash' ] || [ "$1" == 'php' ] && \
  exec gosu www-data docker-php-entrypoint "$@"

exec docker-php-entrypoint "$@"
