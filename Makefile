.PHONY: start stop init tests

start:
	docker-compose up -d

stop:
	docker-compose stop

init:
	docker-compose build
	docker-compose up -d
	docker-compose exec php composer install
	docker-compose exec mysql mysql --user=root --password=megasecret -e "CREATE DATABASE IF NOT EXISTS framey;"
	docker-compose exec php php artisan swagger:generate
	docker-compose exec php php artisan migrate:refresh
	docker-compose exec php php artisan migrate

tests:
	docker-compose exec php php vendor/bin/phpunit
