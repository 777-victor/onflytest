## Instructions to run the project

composer install

config your .env project

I personally you mailtrap to test mails
and database to queue connection.

php artisan migrate

## running

php artisan serve

open other terminal and run:

php artisan queue:work
