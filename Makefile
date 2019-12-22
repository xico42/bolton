DOCKER_COMPOSE ?= docker-compose
PHP ?= $(DOCKER_COMPOSE) exec bolton php
COMPOSER ?= $(DOCKER_COMPOSE) exec bolton composer
BOLTON = $(PHP) bin/console
PHPUNIT = $(PHP) vendor/bin/phpunit
WAIT = $(DOCKER_COMPOSE) run wait

.PHONY: it
setup: files up wait vendor migration quality tests

.PHONY: files
files: docker-compose.yaml .env.local phpunit.xml

docker-compose.yaml: docker-compose.yaml.dist
	@cp docker-compose.yaml.dist docker-compose.yaml

.env.local: .env
	@cp .env .env.local

phpunit.xml: phpunit.xml.dist
	@cp phpunit.xml.dist phpunit.xml

.PHONY: up
up:
	@$(DOCKER_COMPOSE) up -d

.PHONY: wait
wait:
	@$(WAIT) database:3306 -t 0
	@$(WAIT) database_test:3306 -t 0

vendor: composer.json composer.lock
	@$(COMPOSER) install

.PHONY: migration
migration:
	@$(BOLTON) doctrine:migrations:migrate --no-interaction
	@$(BOLTON) doctrine:migrations:migrate --env test --no-interaction

.PHONY: quality
quality:
	@$(PHP) vendor/bin/phpcs -p -n
	@$(PHP) vendor/bin/deptrac analyse depfile.yaml
	@$(PHP) vendor/bin/phpstan analyse

.PHONY: tests
tests:
	@$(PHPUNIT) --testsuite All

.PHONY: unit
unit: phpunit.xml vendor
	@$(PHPUNIT) --testsuite Unit

.PHONY: integration
integration: phpunit.xml vendor
	@$(PHPUNIT) --testsuite Integration

.PHONY: system
system: phpunit.xml vendor
	@$(PHPUNIT) --testsuite System

sync:
	@$(BOLTON) bolton:sync-invoices --no-debug

