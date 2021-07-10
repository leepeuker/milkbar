.PHONY: build

include .env

# Docker
########
build:
	docker-compose build --no-cache --build-arg USER_ID=${USER_ID}

up:
	docker-compose up -d

down:
	docker-compose down

reup: down up

connect_php_bash:
	docker exec -it nursing-log-php bash

run_cmd_php:
	docker exec -i nursing-log-php bash -c "${CMD}"

run_cmd_mysql:
	docker exec -it nursing-log-mysql bash -c "mysql -uroot -p${MYSQL_ROOT_PASSWORD} -e \"$(QUERY)\""

# Composer
##########
composer_install:
	docker exec nursing-log-php bash -c "composer install"

composer_update:
	docker exec nursing-log-php bash -c "composer update"

# Tests
#######
test: test_psalm test_phpstan

test_phpstan:
	make run_cmd_php CMD="vendor/bin/phpstan analyse src -c ./settings/phpstan.neon --level max"

test_psalm:
	make run_cmd_php CMD="vendor/bin/psalm -c ./settings/psalm.xml --show-info=false"
