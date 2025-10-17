.PHONY:build

include .env

# Container management
######################
up:
	docker compose up -d

up_mysql:
	docker compose -f docker-compose.yml -f docker-compose.mysql.yml up -d

up_development:
	docker compose -f docker-compose.yml -f docker-compose.development.yml up -d

up_development_mysql:
	docker compose -f docker-compose.yml -f docker-compose.development.yml -f docker-compose.mysql.yml up -d

down:
	docker compose \
	 -f docker-compose.yml \
	 -f docker-compose.mysql.yml \
	 down

logs:
	docker compose logs -f

build_development:
	docker compose -f docker-compose.yml -f docker-compose.development.yml build --no-cache
	make up_development
	make composer_install
	make app_database_migrate

# Container interaction
#######################
exec_app_bash:
	docker compose exec app bash

exec_app_cmd:
	docker compose exec app bash -c "${CMD}"

exec_mysql_cli:
	docker compose exec mysql sh -c "mysql -u${DB_USER} -p${DB_PASSWORD} ${DATABASE_MYSQL_NAME}"

exec_mysql_query:
	docker compose exec mysql bash -c "mysql -uroot -p${DATABASE_MYSQL_ROOT_PASSWORD} -e \"$(QUERY)\""

# Composer
##########
composer_install:
	make exec_app_cmd CMD="composer install"

composer_update:
	make exec_app_cmd CMD="composer update"

composer_test:
	make exec_app_cmd CMD="composer test"

# Database
##########
db_mysql_create_database:
	make exec_mysql_query QUERY="CREATE DATABASE IF NOT EXISTS $(DATABASE_MYSQL_NAME)"
	make exec_mysql_query QUERY="GRANT ALL PRIVILEGES ON $(DATABASE_MYSQL_NAME).* TO $(DATABASE_MYSQL_USER)@'%'"
	make exec_mysql_query QUERY="FLUSH PRIVILEGES;"
	make app_database_migrate

db_mysql_import:
	docker cp storage/dump.sql milkbar-mysql-1:/tmp/dump.sql
	docker compose exec mysql bash -c 'mysql -uroot -p${DATABASE_MYSQL_ROOT_PASSWORD} milkbar < /tmp/dump.sql'

db_mysql_export:
	docker compose exec mysql bash -c 'mysqldump --databases --add-drop-database -uroot -p$(DATABASE_MYSQL_ROOT_PASSWORD) $(DATABASE_MYSQL_NAME) > /tmp/dump.sql'
	docker cp milkbar-mysql-1:/tmp/dump.sql storage/dump.sql
	chown $(USER_ID):$(USER_ID) storage/dump.sql

db_migration_create:
	make exec_app_cmd CMD="vendor/bin/phinx create Migration -c ./settings/phinx.php"

# App commands
##############
app_database_migrate:
	make exec_app_cmd CMD="php bin/console.php database:migration:migrate"

app_database_rollback:
	make exec_app_cmd CMD="php bin/console.php database:migration:rollback"

app_database_status:
	make exec_app_cmd CMD="php bin/console.php database:migration:status"

app_user_create_test:
	make exec_app_cmd CMD="php bin/console.php user:create test@test.de test"

app_user_udpate_password:
	make exec_app_cmd CMD="php bin/console.php user:create test@test.de test1"

# Shortcuts
php: exec_app_bash
test: composer_test
