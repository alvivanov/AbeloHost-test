.PHONY: up

up:
	@test -f .env || cp .env.example .env
	docker compose up --build
