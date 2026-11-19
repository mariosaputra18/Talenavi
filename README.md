# Talenavi — Product Catalog & Order API

REST API for a Product Catalog and Order Management application built with Laravel 12, PHP, and PostgreSQL.

## Tech Stack

- PHP 8.2
- Laravel 12
- PostgreSQL 16
- Laravel Sanctum (Authentication)
- REST API
- Postman (API Testing)

## Requirements

- PHP >= 8.2
- Composer
- PostgreSQL
- Git

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/mariosaputra18/Talenavi.git
cd Talenavi
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy the environment configuration file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure the database connection in the `.env` file:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=talenavi_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 4. Run Migration

Create the database tables:

```bash
php artisan migrate
```

### 5. Seed Database

Populate the database with initial data:

```bash
php artisan db:seed
```

The seeders provide:

- 5 product categories
- 30 products
- 1 test user

The seeders can be executed again without creating duplicate initial category and product records.

### 6. Run Application

```bash
php artisan serve
```

Base URL:

```text
http://127.0.0.1:8000
```

API Base URL:

```text
http://127.0.0.1:8000/api
```

---

## API Endpoints

### Authentication

| Method | Endpoint | Description | Authentication |
|---|---|---|---|
| POST | `/api/register` | Register a new user | No |
| POST | `/api/login` | Authenticate user | No |
| GET | `/api/me` | Get authenticated user | Required |
| POST | `/api/logout` | Logout authenticated user | Required |

### Categories

| Method | Endpoint | Description | Authentication |
|---|---|---|---|
| GET | `/api/categories` | Get all categories | No |

### Products

| Method | Endpoint | Description | Authentication |
|---|---|---|---|
| GET | `/api/products` | Get products with pagination, search, filtering, and sorting | No |
| GET | `/api/products/{id}` | Get product detail | No |

#### Product Query Parameters

| Parameter | Description |
|---|---|
| `page` | Page number |
| `limit` | Number of items per page (maximum 50) |
| `q` | Search by product title |
| `category` | Filter by category slug |
| `sort` | Sort by `price_asc`, `price_desc`, or `newest` |

Example:

```http
GET /api/products?page=1&limit=10&q=mouse&sort=price_asc
```

### Cart

All cart endpoints require authentication.

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/cart` | Get authenticated user's cart |
| POST | `/api/cart` | Add a product to cart |
| PUT | `/api/cart/{id}` | Update cart item quantity |
| DELETE | `/api/cart/{id}` | Remove cart item |

### Orders

All order endpoints require authentication.

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/checkout` | Checkout cart and create an order |
| GET | `/api/orders` | Get authenticated user's order history |
| GET | `/api/orders/{id}` | Get order detail |

---

## Authentication

Protected endpoints use Laravel Sanctum Bearer Token authentication.

Include the token in the request header:

```http
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

A valid authentication token is required to access protected endpoints. Requests without a valid token will be rejected.

---

## API Request Examples

### 1. Register

Endpoint:

```http
POST /api/register
```

Request body:

```json
{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### 2. Login

Endpoint:

```http
POST /api/login
```

Request body:

```json
{
    "email": "test@example.com",
    "password": "password123"
}
```

Use the authentication token returned by login for protected endpoints.

### 3. Get Products

Endpoint:

```http
GET /api/products?page=1&limit=10
```

This endpoint supports pagination, product title search, category filtering, and sorting.

### 4. Add to Cart

Endpoint:

```http
POST /api/cart
```

Request body:

```json
{
    "product_id": 1,
    "quantity": 2
}
```

Requires a valid Bearer Token.

### 5. Update Cart Item

Endpoint:

```http
PUT /api/cart/{id}
```

Request body:

```json
{
    "quantity": 3
}
```

Requires a valid Bearer Token.

### 6. Checkout

Endpoint:

```http
POST /api/checkout
```

Requires a valid Bearer Token.

Checkout processes the authenticated user's cart, creates an order and order items, updates product stock, and clears the cart upon successful completion.

---

## Response & Error Handling

The API uses HTTP status codes to indicate the result of a request.

| Status Code | Description |
|---|---|
| `200 OK` | Request successful |
| `201 Created` | Resource successfully created |
| `401 Unauthorized` | Authentication required or invalid authentication |
| `404 Not Found` | Resource not found |
| `422 Unprocessable Entity` | Request validation failed |
| `500 Internal Server Error` | Unexpected server error |

### Validation Error

Example of a validation error response:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ]
    }
}
```

### Unauthorized Request

Protected endpoints require a valid authentication token.

Requests without valid authentication are rejected with:

```http
401 Unauthorized
```

### Resource Not Found

When the requested resource does not exist or is not accessible to the authenticated user, the API returns:

```http
404 Not Found
```

### Checkout Error

Checkout validates the cart and product stock before completing the transaction. Checkout cannot be completed when the cart is empty or product stock is insufficient.

---

## Database

The application uses PostgreSQL 16 as its relational database management system.

### Database Justification

PostgreSQL was selected because it provides relational data management, foreign key constraints, transaction support, and data integrity. These capabilities are important for a product catalog and order system, particularly when managing product stock, shopping carts, and checkout transactions.

### Database Structure

| Table | Description |
|---|---|
| `users` | Stores user accounts and authentication data |
| `categories` | Stores product categories |
| `products` | Stores product information, prices, and stock |
| `cart_items` | Stores products and quantities in users' shopping carts |
| `orders` | Stores order information and total prices |
| `order_items` | Stores products, quantities, and price snapshots for each order |

### Database Relationships

- One user can have multiple cart items.
- One user can have multiple orders.
- One category can have multiple products.
- One product can appear in multiple cart items.
- One order can contain multiple order items.
- One product can appear in multiple order items.

### Database Migration

Create the database schema using Laravel migrations:

```bash
php artisan migrate
```

### Database Seeding

Populate the database with initial testing data:

```bash
php artisan db:seed
```

---

## API Testing

API endpoints can be tested using Postman.

The API supports:

- User registration and authentication
- Authentication and authorization using Bearer Tokens
- Product listing and pagination
- Product title search
- Category filtering
- Product sorting
- Product detail
- Cart management
- Checkout and order creation
- Order history and order detail
- Request validation and error handling
- Product stock validation during checkout

---

## Project Structure

The application follows Laravel's project structure and separates API routes, controllers, models, database migrations, seeders, and business logic.

- `app/` — Application logic, controllers, models, and services
- `routes/` — API route definitions
- `database/migrations/` — Database schema definitions
- `database/seeders/` — Initial database data
- `postman/` — Local Postman collection resources

---

## Repository

GitHub Repository:

https://github.com/mariosaputra18/Talenavi.git