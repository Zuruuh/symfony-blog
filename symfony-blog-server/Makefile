## ----------------------------
##
## Symfony Blog

##
## -------
## Dev Env
##
##

DOCKER_COMPOSE  = docker-compose -f docker-compose.dev.yaml

EXEC_PHP        = $(DOCKER_COMPOSE) exec php-fpm
REDIS           = $(DOCKER_COMPOSE) exec cache

SYMFONY         = $(EXEC_PHP) bin/console
PHPUNIT			= $(EXEC_PHP) bin/phpunit --coverage-html coverage -v -c ./phpunit.xml.dist
COMPOSER        = $(EXEC_PHP) composer

pull:
	$(DOCKER_COMPOSE) pull --parallel --quiet --ignore-pull-failures 2> /dev/null

build: pull
	$(DOCKER_COMPOSE) build --pull

rebuild: pull
	$(DOCKER_COMPOSE) build --pull --no-cache

kill:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

install: ## Install and start the project
install: build start db

restart: ## Stop the project and restart it using latest docker images
restart: kill install

reset: ## Stop and start a fresh install of the project
reset: kill remove install

remove:
	-rm -rf vendor node_modules var/cache var/log/*.log var/screenshots
	-rm .phpunit.result.cache

start: ## Start the containers
	$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate

stop: ## Stop the containers
	$(DOCKER_COMPOSE) stop

clean: ## Stop the project and remove generated files
clean: kill
	rm -rf vendor

.PHONY: pull build rebuild kill install reset restart start stop clean no-docker remove

##
## -----
## Utils
##
##

db: ## Setup local database and load fake data
db: dd dc
	$(SYMFONY) --env=dev d:m:m --no-interaction --allow-no-migration
	make fixtures

migration: ## Create a new doctrine migration
	$(SYMFONY) make:migration

migrate: ## Migrates db to latest saved migration
	$(SYMFONY) doctrine:migration:migrate --no-interaction

fixtures: ## Loads fixtures
	$(SYMFONY) doctrine:fixtures:load --purge-with-truncate --no-interaction

dd: # Drops database
	-$(SYMFONY) doctrine:database:drop --force --if-exists

dc:
	-$(SYMFONY) --env=dev doctrine:database:create --if-not-exists

db-update-schema: ## Creates a new migrations and runs it
db-update-schema: migration migrate

db-validate-schema: ## Validate the database schema
	$(SYMFONY) doctrine:schema:validate

redis-flush:
	$(REDIS) flushall

.PHONY: db migration migrate db-update-schema db-validate-schema redis-flush

#> Lint >#

lint:
	$(PHP) vendor/bin/phpstan analyse -c phpstan.neon

#< Lint <#
.PHONY: lint


.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## ----------------------------

.PHONY: help
