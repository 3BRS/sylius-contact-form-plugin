#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# project root
cd "$(dirname "$DIR")"

set -x

# to avoid crash [PHPStan\Symfony\XmlContainerNotExistsException]
if [ ! -f tests/Application/var/cache/test/Tests_ThreeBRS_SyliusContactFormPlugin_KernelTestDebugContainer.xml ]; then
    XDEBUG_MODE=off ./bin/php tests/Application/bin/console cache:warmup --no-optional-warmers
fi

./bin/php --no-php-ini --define memory_limit=1G vendor/bin/phpstan analyse \
    --verbose --error-format=table \
    --debug \
    --level=max \
    src tests \
    "$@"
