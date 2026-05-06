.PHONY: phpstan analyse cs-fix cs-check docker-start docker-refresh

phpstan analyse:
	docker compose run --rm phpstan

cs-fix:
	docker compose run --rm php-cs-fixer

cs-check:
	cd prestashop/modules/arkonexample && composer install --no-interaction --no-progress && vendor/bin/php-cs-fixer fix --dry-run --diff --config="$(CURDIR)/.php-cs-fixer.dist.php"

docker-start:
	docker compose up

docker-refresh:
	docker compose down -v --remove-orphans
	mkdir -p prestashop/modules/arkonexample
	@if [ -d prestashop ]; then \
		find prestashop -mindepth 1 -maxdepth 1 ! -name modules -exec rm -rf {} +; \
	fi
	@if [ -d prestashop/modules ]; then \
		find prestashop/modules -mindepth 1 -maxdepth 1 ! -name arkonexample -exec rm -rf {} +; \
	fi
	docker compose up
