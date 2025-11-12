start:
	php artisan serve --host=0.0.0.0 --port=8000

install:
	composer require squizlabs/php_codesniffer
	composer require phpstan/phpstan
	composer require PHPUnit/PHPUnit
	composer install
	composer validate

autoload:
	composer dump-autoload

lint:
	composer exec --verbose phpcbf -- --standard=phpcs.xml ./public ./src
	composer exec --verbose phpcs -- --standard=phpcs.xml ./public ./src

stan:
	composer exec phpstan -- analyze --memory-limit=-1 -c phpstan.neon

test:
	php artisan test	
	
	