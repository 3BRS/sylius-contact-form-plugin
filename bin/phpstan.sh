#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# project root
cd "$(dirname "$DIR")"

set -x
tests/Application/bin/console cache:warmup
vendor/bin/phpstan analyse \
    --memory-limit 1G \
    --configuration phpstan.neon \
    --debug
    "$@"
