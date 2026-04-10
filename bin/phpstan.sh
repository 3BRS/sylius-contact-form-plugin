#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# project root
cd "$(dirname "$DIR")"

# Extract containerXmlPath from phpstan config (supports both key formats)
CONTAINER_XML=$(sed -n 's/^[[:space:]]*container\(_xml_path\|XmlPath\):[[:space:]]*//p' \
    phpstan.dist.neon phpstan.neon.dist phpstan.neon 2>/dev/null | head -1 | tr -d ' ' || true)

if [ -n "$CONTAINER_XML" ] && [ ! -f "$CONTAINER_XML" ]; then
    # Detect env from the path (e.g. cache/dev/ or cache/test/)
    CACHE_ENV=$(echo "$CONTAINER_XML" | sed -n 's|.*cache/\([^/]*\)/.*|\1|p')
    php bin/console --env="${CACHE_ENV:-dev}" cache:warmup --no-optional-warmers
fi

set -x
XDEBUG_MODE=off php --no-php-ini --define memory_limit=1G vendor/bin/phpstan analyse \
    --debug \
    --level max \
    src tests \
    "$@"
