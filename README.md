Installation
------------

1. Run `composer install`
2. Up your DBMS schema with `./vendor/bin/doctrine orm:schema:up --force`
3. Start PHP built-in web server: `composer serve`

Testing
-------

Run `./vendor/bin/phpunit`
