# Setup instructions

# database setup 
create database and add database details in .env file
example:
DB_DATABASE=chat_app
DB_USERNAME=chat_app
DB_PASSWORD=UwaD3@5.6k/xhGtL

#import database
we sent chat_app.sql file for database. which will placed in root folder of project. please import it into the database.

#update composer
run below command:
composer update

# run poject
run below command:
php artisan serve

#demo users
1)User A:
email: user1@dispostable.com
password: 12345678

2)User B:
email: user2@dispostable.com
password: 12345678
