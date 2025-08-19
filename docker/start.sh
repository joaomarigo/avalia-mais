#!/usr/bin/env sh
set -eu
: "${PORT:=8080}"
if grep -q "Listen 80" /etc/apache2/ports.conf; then
  sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
fi
exec apache2-foreground
