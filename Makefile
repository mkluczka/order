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
	./vendor/bin/infection --show-mutations

check: ecs_fix phpstan tests infection

resetdb:
	bin/console doctrine:database:drop -f
	bin/console doctrine:database:drop -f -etest

	bin/console doctrine:database:create
	bin/console doctrine:database:create -etest

	bin/console doctrine:migrations:migrate --no-interaction
	bin/console doctrine:migrations:migrate --no-interaction -etest
