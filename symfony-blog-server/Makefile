## ----------------------------
##
## Todo & Co

##
## -------
## Dev Env
##
##

DOCKER_COMPOSE  = docker-compose -f docker-compose.yaml

EXEC_PHP        = $(DOCKER_COMPOSE) exec -T php-fpm /entrypoint

SYMFONY         = $(EXEC_PHP) bin/console
PHPUNIT			= $(EXEC_PHP) bin/phpunit --coverage-html coverage -v -c ./phpunit.xml.dist
COMPOSER        = $(EXEC_PHP) composer
YARN        	= $(EXEC_JS) yarn

APP_ENV         = dev

build:
	$(DOCKER_COMPOSE) pull --parallel --quiet --ignore-pull-failures 2> /dev/null
	$(DOCKER_COMPOSE) build --pull

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

no-docker:
	$(eval DOCKER_COMPOSE := \#)
	$(eval EXEC_PHP := )
	$(eval EXEC_JS := )

.PHONY: build kill install reset restart start stop clean no-docker remove

##
## -----
## Utils
##
##

db: ## Setup local database and load fake data
db: vendor
	-$(SYMFONY) --env=$(APP_ENV) doctrine:database:drop --if-exists --force
	-$(SYMFONY) --env=$(APP_ENV) doctrine:database:create --if-not-exists
	$(SYMFONY) --env=$(APP_ENV) d:m:m --no-interaction --allow-no-migration
	$(SYMFONY) --env=$(APP_ENV) d:f:l --no-interaction --purge-with-truncate

migration: ## Create a new doctrine migration
migration:
	$(SYMFONY) doctrine:migrations:diff

migrate: ## Migrates db to latest saved migration
migrate:
	$(SYMFONY) doctrine:migration:migrate --no-interaction

db-update-schema: ## Creates a new migrations and runs it
db-update-schema: migration migrate

db-validate-schema: ## Validate the database schema
db-validate-schema: vendor
	$(SYMFONY) doctrine:schema:validate

redis-flush:
	$(REDIS) flushall

#> Dependencies >#

composer.lock: composer.json
	$(COMPOSER) update

vendor: composer.lock
	$(COMPOSER) install

#< Dependencies <#

.PHONY: db migration migrate db-update-schema db-validate-schema redis-flush

##
## -----
## Tests
##
##

test-env:
	$(eval APP_ENV := test)

toggle-env:
	$(EXEC_PHP) php /srv/scripts/EnvModifier.php --env test

test-init: test-env toggle-env db redis-flush

unit: ## Run all unit tests
unit: test-init
	-$(PHPUNIT) --group unit
	$(EXEC_PHP) php /srv/scripts/EnvModifier.php --env dev

e2e: ## Run all end-to-end tests
e2e: test-init
	-$(PHPUNIT) --group e2e
	$(EXEC_PHP) php /srv/scripts/EnvModifier.php --env dev

test: ## Run all tests in the tests/ folder
test: toggle-env test-init
	-$(PHPUNIT)
	$(EXEC_PHP) php /srv/scripts/EnvModifier.php --env dev


.PHONY: unit e2e test test-init test-env toggle-env

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## ----------------------------

.PHONY: help
