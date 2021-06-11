# Project Management System Admin

This is laravel admin project sample include below list

- user
- roles and permission
- image uploading
- company and branch
- attendance (punch-in & punch-out)
- Eloquent: Relationships
  - One To Many
  - Many To Many
  - Polymorphic Relationships

## Dependencies and Plugins

- [spatie/laravel-permission](https://github.com/spatie/laravel-permission)
- [AdminLTE3](https://adminlte.io/themes/v3/)
- [stevebauman/location](https://github.com/stevebauman/location)

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

- ```cmd 
  $ php artisan make:model Model_name --all
  ```
- Update database/migrations, database/seeds & database/factories files
- Add this model seed in DatabaseSeeder
- Add this model Permission in app/http/Permission.php
- Creat Requests file in app/http/Requests folder for validation
- Update model, view, controller 

## License

MIT
