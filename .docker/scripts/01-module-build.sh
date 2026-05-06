#!/bin/sh
set -eu

MODULE_NAME="arkonexample"
MODULE_DIR="${PS_FOLDER}/modules/${MODULE_NAME}"

cd "$MODULE_DIR"
if [ ! -f composer.lock ]; then
  echo "! [${MODULE_NAME}] composer.lock missing, skipping Composer"
else
  echo "* [${MODULE_NAME}] checking Composer dependencies..."
  composer install --no-interaction --no-progress
fi
