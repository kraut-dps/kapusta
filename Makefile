SHELL := /bin/bash
# какая цель будет выполняться если запущен просто "make"
.DEFAULT_GOAL := up

build: docker-build composer-install

up:
	docker-compose up -d

down:
	docker-compose down

root-bash: up
	docker-compose exec kapusta bash

bash: up
	docker-compose exec -u www-user kapusta bash

test: up
	docker-compose exec kapusta ./vendor/bin/phpunit tests

coverage: up
	docker-compose exec kapusta php -dxdebug.mode=coverage ./vendor/bin/phpunit --coverage-html ./coverage --coverage-filter ./src ./tests

docker-build:
	docker-compose build

composer-install: up
	docker-compose exec -u www-user kapusta composer install