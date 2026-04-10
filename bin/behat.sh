#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# project root
cd "$(dirname "$DIR")"

php bin/console --env=test doctrine:database:create --no-interaction --if-not-exists
php bin/console --env=test doctrine:migrations:migrate --no-interaction
php bin/console --env=test doctrine:schema:update --complete --force --no-interaction

set -x

APP_ENV="test" php vendor/bin/behat "$@"
