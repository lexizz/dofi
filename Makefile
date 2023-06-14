SHELL=/bin/bash
DCC=docker compose

.DEFAULT_GOAL := help

init: composer-install schema-update ## Run containers, composer install and migrate

up: ## Run containers
	$(DCC) up -d --remove-orphans

up-force: ## Run containers with --force
	$(DCC) up -d --force-recreate --remove-orphans --timeout 5

stop: ## Stop containers
	$(DCC) stop --timeout 5

down: stop ## Down containers
	$(DCC) down

build: ## Build containers with --pull
	$(DCC) build --pull

composer-install: ## Run composer install
	$(DCC) exec php composer i

schema-update: ## Run containers and run migrate/up
	$(DCC) exec php ./yii migrate/up --interactive=0

bash: ## Run exec bash
	$(DCC) exec php bash

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
