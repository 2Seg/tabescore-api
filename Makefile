php       = tabescore
testsuite = Small,Medium,Large

status:
	docker-compose ps

up:
	docker-compose up -d --no-deps
build:
	docker-compose build --no-cache  $(arg)
build-up: build up

up-testing:
	docker-compose -f docker-compose.testing.yml up -d

stop:
	docker-compose stop

down:
	docker-compose down --rmi all -v --remove-orphans

shell-php:
	docker exec -it $(php) bash

log-php:
	docker exec -it $(php) sh -c "tail -f -n 100 /home/tabescore-api/storage/logs/*"
log-apache:
	docker exec -it $(php) sh -c "tail -f -n 100 /var/log/apache2/*"

cache-clear:
	docker exec -it $(php) sh -c "php artisan cache:clear"
route-clear:
	docker exec -it $(php) sh -c "php artisan route:clear"
config-clear:
	docker exec -it $(php) sh -c "php artisan config:clear"
all-clear:
	docker exec -it $(php) sh -c "php artisan optimize:clear"

dump-autoload:
	docker exec -it $(php) sh -c "composer dump-autoload"

test:
	docker exec -it $(php) sh -c "docker-php-ext-enable xdebug && vendor/bin/phpunit --testsuite=$(testsuite)"
test-small: testsuite=Small
test-small: test
test-medium: testsuite=Medium
test-medium: test
test-large: testsuite=Large
test-large: test

test-testing:
	docker exec -it $(php) sh -c "php artisan test"

tinker:
	docker exec -it $(php) sh -c "php artisan tinker"