install:
	@make setup-env
	@make up-d
	docker compose exec laravel.test composer install
	docker compose exec laravel.test php artisan key:generate
	@make migrate-seed
	@make horizon-install
	@make horizon-run
re-install:
	@make destroy
	@make install
restart:
	@make down
	@make up
up:
	docker compose up
up-d:
	docker compose up -d
destroy:
	docker compose down --rmi all --volumes --remove-orphans
	rm -rf vendor/
	rm -rf .env
down:
	docker compose down --remove-orphans
migrate:
	docker compose exec laravel.test php artisan migrate
migrate-seed:
	docker compose exec laravel.test php artisan migrate --seed
run-tests:
	docker compose exec laravel.test composer test
horizon-install:
	docker compose exec laravel.test php artisan horizon:install
horizon-run:
	docker compose exec laravel.test php artisan horizon
setup-env:
	if ! [ -f .env ];then cp .env.example .env; fi
