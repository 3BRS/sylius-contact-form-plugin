phpstan:
	APP_ENV=test bin/phpstan.sh

ecs:
	APP_ENV=test bin/ecs.sh --clear-cache

fix:
	APP_ENV=test bin/ecs.sh --fix

install:
	rm -f composer.lock
	composer install --no-interaction --no-scripts
	rm -fr tests/Application/public/media/cache
	mkdir -p tests/Application/public/media/cache
	chmod -R 777 tests/Application/public/media/cache
	rm -fr tests/Application/var
	mkdir -p tests/Application/var
	chmod -R 777 tests/Application/var
	
backend: recreate_db var

frontend:
	APP_ENV=test tests/Application/bin/console assets:install
	(cd tests/Application && yarn install --no-lockfile)
	(cd tests/Application && GULP_ENV=prod yarn build)
	@make var

recreate_db:
	APP_ENV=test tests/Application/bin/console doctrine:database:drop --force --if-exists
	APP_ENV=test tests/Application/bin/console doctrine:database:create --no-interaction
	APP_ENV=test tests/Application/bin/console doctrine:migrations:migrate --no-interaction
	APP_ENV=test tests/Application/bin/console doctrine:schema:update --force --complete --no-interaction
	APP_ENV=test tests/Application/bin/console doctrine:migration:sync-metadata-storage

var:
	rm -fr tests/Application/var
	mkdir -p tests/Application/var/cache
	mkdir -p tests/Application/var/cache/test/profiler
	mkdir -p tests/Application/var/log
	touch tests/Application/var/log/test.log
	chmod -R 777 tests/Application/var

cache:
	APP_ENV=test tests/Application/bin/console cache:clear
	chmod -R 777 tests/Application/var

fixtures:
	@make recreate_db
	APP_ENV=test tests/Application/bin/console sylius:fixtures:load default --no-interaction
	@make var

lint:
	APP_ENV=test bin/symfony-lint.sh
	APP_ENV=test bin/doctrine-lint.sh

behat:
	APP_ENV=test bin/behat.sh

init: install backend frontend fixtures var

tests: phpstan ecs lint behat

static: phpstan ecs lint

ci: init static behat

run:
	docker compose up --detach

php-bash:
	@make run
	docker compose exec --user 1000:1000 php bash

bash: php-bash
