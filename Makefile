.PHONY: phpstan ecs fix install backend frontend recreate_db var cache fixtures lint behat init tests static ci run php-bash bash composer yarn php

phpstan:
	APP_ENV=test bin/phpstan.sh

ecs:
	APP_ENV=test bin/ecs.sh --clear-cache

fix:
	APP_ENV=test bin/ecs.sh --fix

install:
	cp --update=none .env.dist .env
	bin/composer install --no-interaction --no-scripts
	APP_ENV=test bin/php tests/Application/bin/console lexik:jwt:generate-keypair --skip-if-exists --quiet

backend: recreate_db

frontend:
	APP_ENV=test bin/php tests/Application/bin/console assets:install
	bin/yarn --cwd=tests/Application install --pure-lockfile
	GULP_ENV=prod bin/yarn --cwd=tests/Application build

recreate_db:
	APP_ENV=test bin/php tests/Application/bin/console doctrine:database:drop --force --if-exists
	APP_ENV=test bin/php tests/Application/bin/console doctrine:database:create --no-interaction
	APP_ENV=test bin/php tests/Application/bin/console doctrine:migrations:migrate --no-interaction
	APP_ENV=test bin/php tests/Application/bin/console doctrine:schema:update --force --no-interaction
	APP_ENV=test bin/php tests/Application/bin/console doctrine:migration:sync-metadata-storage

var:
	rm -fr tests/Application/var
	mkdir -p tests/Application/var/cache
	mkdir -p tests/Application/var/log
	touch tests/Application/var/log/test.log
	chmod -R 777 tests/Application/var
	mkdir -p tests/Application/public/media/cache && chmod -R 777 tests/Application/public/media

cache:
	APP_ENV=test bin/php tests/Application/bin/console cache:clear
	@make var

fixtures:
	@make recreate_db
	APP_ENV=test bin/php tests/Application/bin/console sylius:fixtures:load default --no-interaction

lint:
	APP_ENV=test bin/symfony-lint.sh
	APP_ENV=test bin/doctrine-lint.sh

behat:
	APP_ENV=test bin/behat.sh

init: install backend frontend

tests: static behat

static: phpstan ecs lint

ci: init static behat

run:
	docker compose up --detach

php-bash:
	@make run
	docker compose exec --user 1000:1000 php bash

bash: php-bash

composer:
	bin/composer

yarn:
	bin/yarn

php:
	bin/php
