phpstan:
	APP_ENV=test bin/phpstan.sh

ecs:
	APP_ENV=test bin/ecs.sh

behat-js:
	APP_ENV=test APP_SUPPRESS_DEPRECATED_ERRORS=1 bin/behat --colors --strict --no-interaction -vvv -f progress

install:
	composer install --no-interaction --no-scripts

backend:
	APP_ENV=test tests/Application/bin/console doctrine:database:create --if-not-exists --no-interaction
	APP_ENV=test tests/Application/bin/console doctrine:migrations:sync-metadata-storage --no-interaction
	APP_ENV=test tests/Application/bin/console doctrine:schema:update --force --complete --no-interaction
	APP_ENV=test tests/Application/bin/console sylius:install --no-interaction
	APP_ENV=test tests/Application/bin/console sylius:fixtures:load default --no-interaction

frontend:
	(cd tests/Application && yarn install --pure-lockfile)
	(cd tests/Application && GULP_ENV=prod yarn build)

behat:
	APP_ENV=test bin/behat --colors --strict --no-interaction -vvv -f progress

lint:
	APP_ENV=test bin/symfony-lint.sh

init: install backend frontend

ci: init phpstan ecs behat lint

integration: init behat

static: install phpstan ecs lint
