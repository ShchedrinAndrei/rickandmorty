bash:
	docker exec -it app bash
up:
	docker compose up -d
down:
	docker compose down
build:
	docker compose up -d --build

migrate:
	docker exec app php bin/console doctrine:migrations:migrate

codestyle:
	docker exec app vendor/bin/php-cs-fixer check src --allow-risky=yes
fix-codestyle:
	docker exec app vendor/bin/php-cs-fixer fix src --allow-risky=yes
static-analysis:
	docker exec app vendor/bin/phpstan analyse src