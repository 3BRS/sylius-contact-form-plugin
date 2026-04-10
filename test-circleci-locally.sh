#!/bin/bash
set -e  # Exit on error

# Usage information
usage() {
    cat <<EOF
Usage: $0 [OPTIONS]

Test the plugin with different version combinations, mimicking CircleCI matrix tests.

OPTIONS:
    -s, --sylius VERSION       Sylius version(s) to test (e.g., "2.1" or "2.1 2.2")
    -y, --symfony VERSION      Symfony version(s) to test (e.g., "7.4")
    -p, --preference PREF      Composer preference(s) (prefer-dist, prefer-lowest, or both)
    -h, --help                 Show this help message

EXAMPLES:
    # Test specific combination (prefer-lowest with Sylius 2.2 and Symfony 7.4)
    $0 --sylius 2.2 --symfony 7.4 --preference prefer-lowest

    # Test all CircleCI matrix combinations (default)
    $0

    # Test Sylius 2.1 with both composer preferences
    $0 --sylius 2.1 --symfony 7.4

    # Test multiple Sylius versions with prefer-dist only
    $0 --sylius "2.1 2.2" --symfony 7.4 --preference prefer-dist

If no options are provided, versions are parsed from .circleci/config.yml
EOF
    exit 0
}

# Backup composer.json and restore it on exit
cleanup() {
    echo ""
    echo "=== Cleanup: Restoring original composer.json ==="
    if [ -f composer.json.backup ]; then
        mv composer.json.backup composer.json
    fi
    rm -f composer.lock
}
trap cleanup EXIT

# Function to run full test suite
run_test_suite() {
    local sylius_version=$1
    local symfony_version=$2
    local composer_preference=$3

    echo ""
    echo "========================================================================"
    echo "=== Testing: Sylius ${sylius_version} + Symfony ${symfony_version} (${composer_preference}) ==="
    echo "========================================================================"
    echo ""

    # Clean up
    rm -f composer.lock
    ./bin-docker/docker-bash -c "rm -fr tests/Application/var/cache/*/*"

    # Set versions
    ./bin-docker/composer require "sylius/sylius:${sylius_version}.*" --no-interaction --no-update --no-scripts
    grep -o -E '"(symfony/[^"]+)"' composer.json | grep -v -E '(symfony/flex|symfony/webpack-encore-bundle|symfony/maker-bundle)' | xargs printf "%s:${symfony_version}.* " | xargs ./bin-docker/composer require --no-interaction --no-update

    # Composer update
    ./bin-docker/composer update --no-interaction --${composer_preference} --no-plugins

    # Clear cache before yarn
    ./bin-docker/docker-bash -c "rm -fr tests/Application/var/cache/*/*"

    # Yarn install and build
    ./bin-docker/yarn --cwd tests/Application install
    GULP_ENV=prod ./bin-docker/yarn --cwd tests/Application build

    # Cache clear and warmup
    ./bin-docker/php bin/console cache:clear --env=test
    ./bin-docker/php bin/console cache:warmup --env=test

    # Run static analysis
    ./bin-docker/docker-bash -c "APP_ENV=dev bin/phpstan.sh"
    ./bin-docker/docker-bash -c "APP_ENV=dev bin/ecs.sh --clear-cache"
    ./bin-docker/docker-bash -c "APP_ENV=dev bin/symfony-lint.sh"

    # Run PHPUnit
    ./bin-docker/docker-bash -c "APP_ENV=dev bin/phpunit"

    # Setup test database
    ./bin-docker/php bin/console --env=test doctrine:database:drop --if-exists -vvv --force
    ./bin-docker/php bin/console --env=test doctrine:database:create -vvv
    ./bin-docker/php bin/console --env=test doctrine:schema:create -vvv

    # Run Behat tests
    ./bin-docker/docker-bash bin/behat.sh

    echo ""
    echo "✓ Passed: Sylius ${sylius_version} + Symfony ${symfony_version} (${composer_preference})"
}

echo "=== Mimicking CircleCI Matrix Tests Locally ==="
echo ""

# Parse command-line arguments
SYLIUS_VERSIONS=()
SYMFONY_VERSIONS=()
COMPOSER_PREFERENCES=()

while [[ $# -gt 0 ]]; do
    case $1 in
        -s|--sylius)
            read -ra SYLIUS_VERSIONS <<< "$2"
            shift 2
            ;;
        -y|--symfony)
            read -ra SYMFONY_VERSIONS <<< "$2"
            shift 2
            ;;
        -p|--preference)
            read -ra COMPOSER_PREFERENCES <<< "$2"
            shift 2
            ;;
        -h|--help)
            usage
            ;;
        *)
            echo "Unknown option: $1"
            usage
            ;;
    esac
done

# Backup original composer.json
echo "Step 0: Backup original composer.json"
cp composer.json composer.json.backup

# If no versions specified, parse from CircleCI config
if [ ${#SYLIUS_VERSIONS[@]} -eq 0 ] || [ ${#SYMFONY_VERSIONS[@]} -eq 0 ]; then
    echo "Step 1: Parsing version matrices from .circleci/config.yml"
    if [ ! -f .circleci/config.yml ]; then
        echo "Error: .circleci/config.yml not found!"
        exit 1
    fi

    # Extract sylius_version array from CircleCI config if not provided
    if [ ${#SYLIUS_VERSIONS[@]} -eq 0 ]; then
        SYLIUS_LINE=$(grep 'sylius_version:' .circleci/config.yml | head -n 1)
        if [ -z "$SYLIUS_LINE" ]; then
            echo "Error: Could not find sylius_version in .circleci/config.yml"
            exit 1
        fi
        SYLIUS_VERSIONS=($(echo "$SYLIUS_LINE" | grep -o '"[0-9.]*"' | tr -d '"'))
    fi

    # Extract symfony_version array from CircleCI config if not provided
    if [ ${#SYMFONY_VERSIONS[@]} -eq 0 ]; then
        SYMFONY_LINE=$(grep 'symfony_version:' .circleci/config.yml | head -n 1)
        if [ -z "$SYMFONY_LINE" ]; then
            echo "Error: Could not find symfony_version in .circleci/config.yml"
            exit 1
        fi
        SYMFONY_VERSIONS=($(echo "$SYMFONY_LINE" | grep -o '"[0-9.]*"' | tr -d '"'))
    fi
fi

# Default composer preferences if not specified
if [ ${#COMPOSER_PREFERENCES[@]} -eq 0 ]; then
    COMPOSER_PREFERENCES=("prefer-dist" "prefer-lowest")
fi

echo "  - Sylius versions: ${SYLIUS_VERSIONS[*]}"
echo "  - Symfony versions: ${SYMFONY_VERSIONS[*]}"
echo "  - Composer preferences: ${COMPOSER_PREFERENCES[*]}"
echo ""

# Run all combinations
for sylius_version in "${SYLIUS_VERSIONS[@]}"; do
    for symfony_version in "${SYMFONY_VERSIONS[@]}"; do
        for composer_preference in "${COMPOSER_PREFERENCES[@]}"; do
            # Restore composer.json for each test
            cp composer.json.backup composer.json

            run_test_suite "$sylius_version" "$symfony_version" "$composer_preference"
        done
    done
done

echo ""
echo "========================================================================"
echo "=== ALL TESTS PASSED! ==="
echo "========================================================================"
echo ""
echo "Summary:"
echo "  - Sylius versions tested: ${SYLIUS_VERSIONS[*]}"
echo "  - Symfony versions tested: ${SYMFONY_VERSIONS[*]}"
echo "  - Composer preferences tested: ${COMPOSER_PREFERENCES[*]}"
echo "  - Total combinations: $((${#SYLIUS_VERSIONS[@]} * ${#SYMFONY_VERSIONS[@]} * ${#COMPOSER_PREFERENCES[@]}))"
echo ""
