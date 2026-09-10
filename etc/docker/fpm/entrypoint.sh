#!/usr/bin/env bash

# Work around https://bugs.php.net/bug.php?id=71880 by streaming FPM's
# error log through a FIFO instead of attaching it directly to stdout.
mkfifo /tmp/stdout
chmod 777 /tmp/stdout

php-fpm --pid /tmp/php-fpm.pid -d error_log=/tmp/stdout &
exec tail -f /tmp/stdout
