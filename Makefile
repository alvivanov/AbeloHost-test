include .env
export

.PHONY: up init-db seed

up:
	@test -f .env || cp .env.example .env
	docker compose up --build

init-db:
	docker compose exec -T mysql mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) < database/tables.sql

seed:
	docker compose exec -T mysql mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) < database/seed.sql

