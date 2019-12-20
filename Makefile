PHP_BIN ?= docker-compose exec bolton php

.PHONY: quality
quality:
	@$(PHP_BIN) vendor/bin/phpmd src/,tests/ text phpmd.xml
	@$(PHP_BIN) vendor/bin/phpcs -p -n
	@$(PHP_BIN) vendor/bin/deptrac analyse depfile.yaml
