## Introduction
This repository contains a REST API built with Laravel, designed to manage a collection of stores and products. 
The API allows users to perform CRUD operations on stores and products, including creating, reading, updating, and deleting data.

You can find me on:
- [LinkedIn](https://www.linkedin.com/in/jorge-cortes-dev/)
- [Manfred](https://mnf.red/jorgecortes/timeline)

## Requirements

- Docker Desktop
- Composer v2.0 or higher

## Installation
Clone repository:
```sh
git clone https://github.com/jcortesa/laravel-store-rest-api
cd laravel-store-rest-api
```

Create a `.env` file by copying the example file:
```sh
cp .env.example .env
```

Install dependencies and start the application using Laravel Sail:
```sh
composer install
./vendor/bin/sail up -d
```

In browser, navigate to `http://localhost` to access the API.

## API Endpoints
The API provides the following endpoints:

- `POST /api/stores`: Create a new store. Pass a collection of products in the request body to associate them with the store.
- `GET /api/stores`: Retrieve a list of all stores.
- `GET /api/stores/{id}`: Retrieve a specific store by ID.
- `PUT /api/stores/{id}`: Update an existing store by ID.
- `DELETE /api/stores/{id}`: Delete a store by ID.
- `PUT /api/stores/{storeId}/products/{productId}/sell`: Sell a product from a store. This endpoint will update the stock of the product and return the updated product details.

See specification on `docs/api.yaml`. 
Test it also with `docs/api.http` using [HTTP Client](https://www.jetbrains.com/help/phpstorm/http-client-in-product-code-editor.html) by JetBrains.

## Testing
To run the tests, use the following command:

```sh
./vendor/bin/sail artisan test
```

## Code static analysis
Making use of [PHPStan](https://phpstan.org/) to analyze the code quality, run the following command:

```sh
./vendor/bin/sail bin phpstan analyze
```

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details
