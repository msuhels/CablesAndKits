# Setup instructions

### database setup 
create database and add database details in `.env` file.<br />
example:<br />
DB_DATABASE=chat_app<br />
DB_USERNAME=chat_app<br />
DB_PASSWORD=UwaD3@5.6k/xhGtL<br />

### import database
we sent `chat_app.sql` file for database. which will placed in root folder of project. please import it into the database.<br />

### update composer
run below command.<br />
`composer update`

### run poject
run below command.<br />
`php artisan serve`
Next, open this link in the browser. <br />
[http://localhost:8000/login](http://localhost:8000/login)

### demo users
1)User A.<br />
email: user1@dispostable.com<br />
password: 12345678<br />

2)User B.<br />
email: user2@dispostable.com<br />
password: 12345678<br />

### steup cron (optional)
if we run this project on server then setup cron link <br />
[http://host_name/delete-message-cron](http://host_name/delete-message-cron)  <br />

OR <br />

if we run this project on local server then you can hit below link manually <br />
[http://localhost:8000/delete-message-cron](http://localhost:8000/delete-message-cron)  <br />

