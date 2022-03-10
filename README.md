## Import App

This project is for import csv file into DB from cli command or api endpoint.

## Requirments

PHP 7.3 or higher
Mysql 5.7 or higher
Composer
Redis
Git
PHP extension php_zip enabled
PHP extension php_xml enabled
PHP extension php_gd2 enabled
PHP extension php_iconv enabled
PHP extension php_simplexml enabled
PHP extension php_xmlreader enabled
PHP extension php_zlib enabled

## Installation

1- clone this repo on your local machine.
2- cd into project directory (cd ./Acid21).
3- copy .env.example into .env file (cp .env.example .env).
4- enter Database name, user & password into .env
5- run: composer install
6- run: php artisan migrate

## To import csv file using command line

php artisan import:articles {filename}

note: file must be inside storage/app/public/files directory.

## To import file using Api endpoint

php artisan serve

from postman send POST request to 127.0.0.1:8000/articles with articles parameter type file.
