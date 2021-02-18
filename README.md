# Full Stack Web Developer Test

This is a basic implementation of full stack web developer test.

Full requirements are available at [REQUIREMENTS.md](REQUIREMENTS.md).

**Your goal is to finish this project:**

1. Create fork of this repository (private).
2. Implement missing functionality (check "what's left to implement" section).
3. Commit your changes.

## What's implemented

* Registration, authentication of users.
* Database structure and importing of currency rates.
* Page showing currency rates for last available date.

## What's left to implement

1. Paging.
2. Base currency selection.
3. Min/max/average rate.
4. History of currency rate.
5. History chart.
6. Unit tests

## Installation

```
# Clone repository.

cp .env.example .env

# Set database connection in ".env" file.

composer install

php artisan migrate

php artisan currency:import

npm install

npm run dev

php artisan serve

# Go to http://localhost:8000/
```
