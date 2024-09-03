.PHONY: test

test: run-tests.php
	php run-tests.php -P  -q --no-progress --offline --show-diff --show-slow 1000 --set-timeout 120 -g FAIL,BORK,LEAK,XLEAK tests/

run-tests.php:
	wget https://raw.githubusercontent.com/php/php-src/master/run-tests.php
