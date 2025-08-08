#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# project root
cd "$(dirname "$DIR")"

./bin/console --no-interaction lint:yaml src
./bin/console --no-interaction lint:container
./bin/console --no-interaction lint:twig src
