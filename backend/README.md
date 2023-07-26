## Instructions to run the project

composer install

config your .env project

I personally use mailtrap to test mails
and database to queue connection.

php artisan migrate:refresh --seed

## running

php artisan serve

open other terminal and run:

php artisan queue:work

## login credentials

User: victorboaventcampos@gmail.com or victor@admin.com
password: 12345678

PS: Certificate that url in .env front is equal to the url of the serve
