test:
	php artisan test
test-coverage:
	php artisan test --coverage-clover build/logs/clover.xml
setup:
	composer install
	cp -n .env.example .env || true
	php artisan key:generate
	touch database/database.sqlite
	php artisan migrate
	php artisan passport:install
seed:
	php artisan db:seed
docs:
	php artisan ide-helper:generate
	php artisan ide-helper:models -n
	php artisan ide-helper:meta
lint:
	composer exec phpcs -v
lint-fix:
	composer exec phpcbf -v
clear:
	php artisan route:clear
	php artisan view:clear
	php artisan cache:clear
	php artisan config:clear
