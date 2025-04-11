.PHONY: all install

all: install

database:
	mkdir -p nginx/php/data
	cp --update=none data/vost.sqlite nginx/php/data/vost.sqlite

install:
	composer update
	mkdir -p nginx/www
	mkdir -p nginx/php
	cp -r src/* nginx/www
	cp -r vendor nginx/php
	cp auth-config.php nginx/php/auth-config.php
	rm -f nginx/php/index.html
