# Project variables
APP_CONTAINER=app
DB_CONTAINER=db
WEB_CONTAINER=web

# Default target
.PHONY: help
help:
	@echo "Available commands:"
	@echo "  make setup         Initialize Laravel (.env + app key)"
	@echo "  make up            Build and start containers"
	@echo "  make down          Stop containers"
	@echo "  make test          Run tests with Pest"


.PHONY: setup
setup:
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo ".env file created"; \
		docker-compose restart $(APP_CONTAINER); \
		docker-compose exec $(APP_CONTAINER) php artisan key:generate; \
		docker-compose exec $(APP_CONTAINER) php artisan migrate --force; \
	else \
		echo ".env file already exists"; \
	fi

# Build and start containers
.PHONY: up
up:
	docker-compose up -d --build

# Stop containers
.PHONY: down
down:
	docker-compose down

# Run tests
.PHONY: test
test:
	docker-compose exec $(APP_CONTAINER) ./vendor/bin/pest

