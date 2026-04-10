.PHONY: run init cache cache-test yarn ci

MAKEFLAGS += --no-print-directory # to disable "make: Entering directory ..." messages

run: init

init:
	which docker > /dev/null || (echo "Please install docker binary" && exit 1)
	if command -v direnv >/dev/null; then \
		[ -f .envrc ] || cp .envrc.dist .envrc; \
		direnv allow; \
	fi
	docker compose up -d
	./bin-docker/composer update --no-interaction
	@make cache
	./bin-docker/php ./bin/console doctrine:database:create --no-interaction --if-not-exists
	./bin-docker/php ./bin/console doctrine:migrations:migrate --no-interaction
	./bin-docker/php ./bin/console doctrine:schema:update --force --complete --no-interaction
	./bin-docker/php ./bin/console doctrine:migration:sync-metadata-storage
	./bin-docker/php ./bin/console assets:install
	./bin-docker/yarn --cwd=tests/Application install --pure-lockfile
	GULP_ENV=prod ./bin-docker/yarn --cwd=tests/Application build

init-tests:
	which docker > /dev/null || (echo "Please install docker binary" && exit 1)
	if command -v direnv >/dev/null; then \
		[ -f .envrc ] || cp .envrc.dist .envrc; \
		direnv allow; \
	fi
	docker compose up -d
	./bin-docker/composer update --no-interaction
	@make cache-test
	./bin-docker/php ./bin/console --env=test doctrine:database:drop --no-interaction --force --if-exists
	./bin-docker/php ./bin/console --env=test doctrine:database:create --no-interaction --if-not-exists
	./bin-docker/php ./bin/console --env=test doctrine:migrations:migrate --no-interaction
	./bin-docker/php ./bin/console --env=test doctrine:schema:update --force --complete --no-interaction
	./bin-docker/php ./bin/console --env=test doctrine:migration:sync-metadata-storage
	./bin-docker/php ./bin/console --env=test assets:install
	./bin-docker/yarn install --pure-lockfile
	./bin-docker/yarn --cwd=tests/Application install --pure-lockfile
	GULP_ENV=prod ./bin-docker/yarn --cwd=tests/Application build

cache:
	docker compose run --rm --user=root --entrypoint=sh php -c 'rm -fr tests/Application/var/cache || (sleep 0.3 && rm -fr tests/Application/var/cache)'
	./bin-docker/php ./bin/console cache:clear --no-warmup

cache-test:
	docker compose run --rm --user=root --entrypoint=sh php -c 'rm -fr tests/Application/var/cache/test || (sleep 0.3 && rm -fr tests/Application/var/cache/test)'
	./bin-docker/php ./bin/console --env=test cache:clear --no-warmup

static: fix static-only

static-only:
	@make ecs
	@make phpstan
	@make composer-lint
	@make symfony-lint
	@make doctrine-lint
	@make say-ok

phpstan:
	./bin-docker/docker-bash bin/phpstan.sh

behat:
	./bin-docker/docker-bash bin/behat.sh

ecs:
	./bin-docker/docker-bash bin/ecs.sh

symfony-lint:
	./bin-docker/docker-bash bin/symfony-lint.sh

composer-lint:
	./bin-docker/composer validate --no-check-lock

doctrine-lint:
	./bin-docker/docker-bash bin/doctrine-lint.sh

lint: symfony-lint composer-lint doctrine-lint

yarn-build:
	./bin-docker/yarn --cwd=tests/Application install --pure-lockfile
	GULP_ENV=prod ./bin-docker/yarn --cwd=tests/Application build

yarn: yarn-build

schema-reset:
	./bin-docker/php ./bin/console doctrine:database:drop --force --if-exists
	./bin-docker/php ./bin/console doctrine:database:create --no-interaction
	./bin-docker/php ./bin/console doctrine:migrations:migrate --no-interaction
	./bin-docker/php ./bin/console doctrine:schema:update --force --complete --no-interaction
	./bin-docker/php ./bin/console doctrine:migration:sync-metadata-storage

fix:
	./bin-docker/docker-bash bin/ecs.sh --fix

bare-fixtures:
	@echo "############\nLoading fixtures: $(SPEED_MESSAGE)\n############"
	./bin-docker/php ./bin/console sylius:fixtures:load --no-interaction

fixtures: schema-reset bare-fixtures cache

tests: static behat

ci: init-tests tests

say-ok:
	@echo "✅ OK ✅"

php-bash:
	./bin-docker/docker-bash

bash: php-bash
