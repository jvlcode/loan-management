
## Requirements
    - composer:latest
    - php:8.0
    - mysql 

## Installing instructions

    - clone this repository
    - run "composer install" command in the project directory
    - open .env file and update DB_DATABASE,DB_USERNAME and DB_PASSWORD values
    - make sure the database connection is running
    - run "php artisan migrate:fresh --seed" command in the project directory
    - run "php artisan serve" to run the application on localhost
    - copy the development url (eg:http://127.0.0.1:8000)
    - open Postman app 
    - In Postman, import collections from "Loan Management.postman_collection.json" file or from url https://www.getpostman.com/collections/a7c3da6ed11f1b47deba
    - you can change base_url variable value if you have a different base_url url. 
