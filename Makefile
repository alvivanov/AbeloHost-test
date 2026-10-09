-include .env
export

TEST_ENV_FLAGS := $(shell grep -v -e '^\#' -e '^$$' .env.test | sed 's/^/-e /')

.PHONY: up init-db seed recreate-db init-test-db cs cs-check test

up:
	@test -f .env || cp .env.example .env
	@echo "==> Starting containers..."
	docker compose up --build -d
	@echo "==> Containers are up."
	$(MAKE) init-db

init-db:
	@echo "==> Creating database schema from database/tables.sql..."
	docker compose exec -T db mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) < database/tables.sql
	@echo "==> Schema created."

seed:
	@echo "==> Seeding database from database/seed.sql..."
	docker compose exec -T db mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) < database/seed.sql
	@echo "==> Database seeded."

recreate-db:
	@echo "==> Dropping and recreating database '$(DB_DATABASE)'..."
	docker compose exec -T db mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) -e "DROP DATABASE IF EXISTS \`$(DB_DATABASE)\`; CREATE DATABASE \`$(DB_DATABASE)\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
	@echo "==> Database recreated."

init-test-db:
	@echo "==> Creating test database schema from database/tables.sql..."
	docker compose exec -T test-db mysql -u$(TEST_DB_USERNAME) -p$(TEST_DB_PASSWORD) $(TEST_DB_DATABASE) < database/tables.sql
	@echo "==> Test database schema created."

cs:
	@echo "==> Fixing code style..."
	docker compose exec app composer cs
	@echo "==> Code style fixed."

cs-check:
	@echo "==> Checking code style..."
	docker compose exec app composer cs:check

test: init-test-db
	@echo "==> Running tests..."
	@docker compose exec $(TEST_ENV_FLAGS) app composer test; status=$$?; \
	exit $$status

