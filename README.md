## Currency Converter Service

RESTful web service that exposes one endpoint to convert an arbitrary amount of a given base currency into a specified target currency.

## How to set up the project

- composer install
- cp .env.example .env
- php artisan key:generate
- ./vendor/bin/sail up (I have used port:80 for sail service and port:3306 for mysql database.)

## Swagger
Link: {base_url}/api/documentation

## fastFOREX Conversion API
- Documentation: https://fastforex.readme.io/reference/introduction
- Trial key will expire => 16th Jun 2023 (Thur)
- If this key expires, please sign in https://console.fastforex.io/auth/signin to generate a new one, and update "EXCHANGE_RATE_API_KEY" at .env file with the new key
