.PHONY: cleanup-tests prepare-tests run-all-tests run-stateless-tests compile-assets

cleanup-tests: ## Remove test database
	php bin/console doctrine:database:drop --env=test --if-exists --force

prepare-tests: ## Create test database
	php bin/console doctrine:database:create --env=test --if-not-exists
	php bin/console doctrine:migrations:migrate --env=test --no-interaction --allow-no-migration

run-all-tests: ## Run full PHPUnit Test Suite
	php bin/phpunit --testdox

run-stateless-tests: ## Run only tests, which do not require a database reset
	php bin/phpunit --testdox --exclude-group stateful

test: cleanup-tests prepare-tests run-all-tests

compile-assets:
	php bin/console asset-map:compile --env=prod

.PHONY : help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: start
start: ## Start the docker environment
	docker compose up -d

.PHONY: stop
stop: ## Stop the docker environment
	docker compose stop

.PHONY: cli
cli: ## Enter PHP container
	docker compose exec dev bash