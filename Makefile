.PHONY: help build up down restart logs clean setup dev test migrate seed fresh

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build Docker containers
	docker-compose build

up: ## Start Docker containers
	docker-compose up -d

down: ## Stop Docker containers
	docker-compose down

restart: ## Restart Docker containers
	docker-compose restart

logs: ## View Docker logs
	docker-compose logs -f

clean: ## Clean up Docker resources
	docker-compose down -v
	docker system prune -f

setup: ## Initial setup (copy env, build, start, install deps, migrate)
	@if [ ! -f .env ]; then cp docker/env.example .env; fi
	docker-compose build
	docker-compose up -d
	@echo "Waiting for containers to be ready..."
	@sleep 10
	docker-compose exec app composer install
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan migrate
	docker-compose exec node npm install
	docker-compose exec node npm run build

dev: ## Start development environment
	docker-compose up -d
	docker-compose exec node npm run dev

test: ## Run tests
	docker-compose exec app php artisan test

migrate: ## Run database migrations
	docker-compose exec app php artisan migrate

seed: ## Seed the database
	docker-compose exec app php artisan db:seed

fresh: ## Fresh migration with seeding
	docker-compose exec app php artisan migrate:fresh --seed

artisan: ## Run artisan command (usage: make artisan cmd="list")
	docker-compose exec app php artisan $(cmd)

npm: ## Run npm command (usage: make npm cmd="run dev")
	docker-compose exec node npm $(cmd)

composer: ## Run composer command (usage: make composer cmd="install")
	docker-compose exec app composer $(cmd)

shell: ## Access app container shell
	docker-compose exec app bash

node-shell: ## Access node container shell
	docker-compose exec node sh

db-shell: ## Access database shell
	docker-compose exec db mysql -u sales_tracker -p sales_tracker 