default: list

list:
	@LC_ALL=C $(MAKE) -pRrq -f $(firstword $(MAKEFILE_LIST)) : 2>/dev/null | awk -v RS= -F: '/(^|\n)# Files(\n|$$)/,/(^|\n)# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1}}' | sort | grep -E -v -e '^[^[:alnum:]]' -e '^$@$$'

ecs_fix:
	./vendor/bin/ecs --fix

phpstan:
	./vendor/bin/phpstan analyse app src tests

test:
	./vendor/bin/phpunit --testdox

infection:
	./vendor/bin/infection --show-mutations --threads=4

deptrac:
	./vendor/bin/deptrac --report-uncovered

check: ecs_fix phpstan deptrac test

reset_dev_db:
	bin/console doctrine:database:drop -f
	bin/console doctrine:database:create
	bin/console doctrine:migrations:migrate --no-interaction

reset_test_db:
	bin/console doctrine:database:drop -f -etest
	bin/console doctrine:database:create -etest
	bin/console doctrine:migrations:migrate --no-interaction -etest

resetdb: reset_dev_db reset_test_db

init:
	composer install
	@make resetdb
	@make check
	@make infection
	docker-compose up -d

entrypoint:
	php bin/console ca:cl
	php bin/console ca:wa
	php -S 0.0.0.0:44444 /var/www/public/index.php
