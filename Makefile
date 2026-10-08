include .env
export

.PHONY: up init-db seed recreate-db cs cs-check

up:
	@test -f .env || cp .env.example .env
	@echo "==> Starting containers..."
	docker compose up --build -d
	@echo "==> Containers are up."

init-db:
	@echo "==> Creating database schema from database/tables.sql..."
	docker compose exec -T mysql mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) < database/tables.sql
	@echo "==> Schema created."

seed:
	@echo "==> Seeding database from database/seed.sql..."
	docker compose exec -T mysql mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) < database/seed.sql
	@echo "==> Database seeded."

recreate-db:
	@echo "==> Dropping and recreating database '$(DB_DATABASE)'..."
	docker compose exec -T mysql mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) -e "DROP DATABASE IF EXISTS \`$(DB_DATABASE)\`; CREATE DATABASE \`$(DB_DATABASE)\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
	@echo "==> Database recreated."

cs:
	@echo "==> Fixing code style..."
	docker compose exec php composer cs
	@echo "==> Code style fixed."

cs-check:
	@echo "==> Checking code style..."
	docker compose exec php composer cs:check

