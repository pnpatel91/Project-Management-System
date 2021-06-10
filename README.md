# Laravel Admin

This is laravel admin project sample include below list

- roles and permission
- image uploading
- Eloquent: Relationships
  - One To Many
  - Many To Many
  - Polymorphic Relationships

## Dependencies and Plugins

- [spatie/laravel-permission](https://github.com/spatie/laravel-permission)
- [AdminLTE3](https://adminlte.io/themes/v3/)

## Installation

- git clone
- composer install
- cp .env.example .env
- php artisan key:generate
- setup database in .env
- php artisan migrate --seed
- php artisan serve

The site will run localhost:8000

## Default Users

```cmd
// Admin User
username - admin@gmail.com
password - password

// Management User
username - management@gmail.com
password - password

// Staff User
username - staff@gmail.com
password - password

// Accounting User
username - accounting@gmail.com
password - password
```
## Create New Module

- $ php artisan make:model MODELNAME --all
- Update database/migrations, database/seeds & database/factories files
- Add this model seed in DatabaseSeeder
- Add this model Permission in app/http/Permission.php
- Creat validation in app/http/Requests folder
- Update model, view, controller 

## License

MIT
