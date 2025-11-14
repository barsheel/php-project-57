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
	composer exec --verbose phpcbf -- --standard=PSR12 app routes database tests bootstrap/app.php
	composer exec --verbose phpcs -- --standard=PSR12 app routes database tests bootstrap/app.php

stan:
	composer exec phpstan -- analyze --memory-limit=-1 -c phpstan.neon

test:
	php artisan test	
	
	