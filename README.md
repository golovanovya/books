# Books Library

## Description:

Create a simple application calls Books Library. It should contain data from the provided XML file (500000 records)
Each book must have a name, image (book cover) with images size "200x400" and must be cropped.
You need to have a form where we can upload XML file and it should proceed after upload the test file. Use laravel queue jobs to import this file in background.

Here the fields for the books table:
- Title: string, not null, 190 limit
- Image: string, null
- ISBN: string, not null, unique
- Description: longtext

### Parser:

We have XML with books data. You need to write a simple parser which will parse all of the items from the XML and will create the records in the database. Write a parser which will import all data to the database. It must check that the book doesn't exists in the batabase based on ISBN (titles can be duplicated), download image from URL and store it to the `public/storage/{year}/{month}/{date}/{unique filename}.jpg`. If book already exists it must skip this book and continue work with other items. If image not set for the book it must store book and skip the image saving process.

### FRONTEND:

Use any popular CSS framework (bootstrap, talwind or any other) and show full list of the books which you have in the database with pagination (Show 100 books on one page). Pagination should works, user should able to search data by ISBN or title via one field. We don't care about frontend part and we need just to see that you know how to use vue.js.

After completing the test task share your code for review to the bitbucket or github with installment instructions in README.MD file. We will install it locally and will check how it works.

## Notes:

- Make sure that you have separated business logic. Don't use controllers or models for the business logic.
- Not needed to comment the code. Make sure that names of the methods answer on question what they are do.
- PHP 7.3
- Laravel 7 or 8

## Requirements

- PHP >= 7.3
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Fileinfo Extension
- GD Library (>=2.0) … or …
- Imagick PHP extension (>=6.5.7)

## Installation

    git clone https://github.com/golovanovya/books.git
    composer install
    cp .env.example .env

Configure connection to database and queue and run migrations

    php artisan migrate
    php artisan serve

And open in browser http://localhost:8000

Populate database with fake data

    php artisan db:seed

## Run tests

    php artisan test
