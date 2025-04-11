.PHONY: all install

all: install

database:
	mkdir -p config/php/data
	cp --update=none data/vost.sqlite config/php/data/vost.sqlite

install:
	composer update
	mkdir -p config/www
	mkdir -p config/php
	cp -r src/* config/www
	cp -r vendor config/php
	cp auth-config.php config/php/auth-config.php
	rm -f config/php/index.html
