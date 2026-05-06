#!/bin/sh
set -eu

MODULE_NAME="arkonexample"

cd "$PS_FOLDER"

MYSQL_HOST="${MYSQL_HOST:-mysql}"
MYSQL_USER="${MYSQL_USER:-prestashop}"
MYSQL_PASSWORD="${MYSQL_PASSWORD:-prestashop}"
MYSQL_DATABASE="${MYSQL_DATABASE:-prestashop}"

installed=0
count=$(mysql -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" -Nse "SELECT COUNT(*) FROM ps_module WHERE name='${MODULE_NAME}'" 2>/dev/null) || true
if [ -n "$count" ]; then
  installed=$count
fi

if [ "$installed" -ge 1 ]; then
  echo "* [${MODULE_NAME}] already installed in PrestaShop, skipping module install"
else
  echo "* [${MODULE_NAME}] installing the module..."
  php -d memory_limit=-1 bin/console prestashop:module --no-interaction install "$MODULE_NAME"
fi
