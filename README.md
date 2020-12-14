# Laravel passport application

Demo setup: **[Setup video](https://www.youtube.com/watch?v=UKSQdg1uPbQ)**.

## Laravel setup

### Install composer dependencies

```
cd laravel-app
composer install
```

### Database Migrations

After installing composer dependencies, add your database credentials in `.env` file, run database migrations and seed database. Also setup a mail server. For testing purpose, i will be using mailtrap.

```
php artisan passport:install
```
```
php artisan migrate
```
Seed the database to create the different report types, admin account and guest user account

```
php artisan db:seed
```


Now, in the terminal run


```
php artisan serve
```
This command will run the application at `http://127.0.0.1:8000` URL, and that URL path used in the Vue.js app.

```
php artisan websockets:serve
```
This will run pusher websockets at `http://127.0.0.1:6001`

```
php artisan queue:work
```
This will allow us to add emails to a queue.



Admin account credentials

```
email: admin@prms.com
password: prmsadmin
```

## Vue.js Project setup

```
cd vue-app
npm install
```

### Compiles and hot-reloads for development

```
npm run dev
```
