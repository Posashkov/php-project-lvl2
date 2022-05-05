install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src tests bin
	composer exec --verbose phpstan -- --level=8 analyse src tests bin

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 src tests bin

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

