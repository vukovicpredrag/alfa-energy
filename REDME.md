# Simple web application - CLIENTS MANAGEMENT

Clients information and management

## Technologies
- Programing language: PHP
- Framework: Laravel 8.75

## Server Requirements

```
● PHP >= 7.3
● MYSQL
● PDO PHP Extension
● JSON PHP Extension
● Fileinfo PHP Extension
● Tokenizer PHP Extension

```


## Installation

 Install Composer Dependencies

```bash
composer install
```


Create a copy of your .env file

```bash
cp .env.example .env

*insert your database information (DB_PORT; DB_DATABASE; DB_USERNAME;DB_PASSWORD)
```
*insert your database information (DB_PORT; DB_DATABASE; DB_USERNAME;DB_PASSWORD)



Generate an app encryption key

```bash
php artisan key:generate
```
Run database migrations

```bash
php artisan migrate
```

Style
```bash
npm install

// Run all Mix tasks...
npm run dev
```
## Usage
Manage clients and manage data for usage

Users are able to:
```
● Register / Ligin into application
● Get all clients
● Create citeis
● Create countries
● Manage content and industy types
● Manage clients
● etc


```
