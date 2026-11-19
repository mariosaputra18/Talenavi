# Talenavi — Product Catalog & Order API

REST API untuk aplikasi Product Catalog & Order yang dibangun menggunakan Laravel 12, PHP, dan PostgreSQL.

## Tech Stack

- PHP 8.2
- Laravel 12
- PostgreSQL 16
- Laravel Sanctum (authentication)
- REST API
- Postman (API testing)

## Requirements

- PHP >= 8.2
- Composer
- PostgreSQL
- Git

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/USERNAME/talenavi.git
cd talenavi
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=talenavi_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 4. Run Migration

```bash
php artisan migrate
```

### 5. Seed Database

```bash
php artisan db:seed
```

Seeder menyediakan data kategori, produk, dan akun pengujian.

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

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/register` | Register user |
| POST | `/api/login` | Login user |
| GET | `/api/me` | Get authenticated user |
| POST | `/api/logout` | Logout user |

### Categories

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/categories` | Get all categories |

### Products

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/products` | Get products with pagination, search, filtering, and sorting |
| GET | `/api/products/{id}` | Get product detail |

Product query parameters:

- `page` — page number
- `limit` — items per page (maximum 50)
- `q` — search by product title
- `category` — filter by category slug
- `sort` — `price_asc`, `price_desc`, or `newest`

### Cart

Requires authentication.

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/cart` | Get user's cart |
| POST | `/api/cart` | Add product to cart |
| PUT | `/api/cart/{id}` | Update cart quantity |
| DELETE | `/api/cart/{id}` | Remove cart item |

### Orders

Requires authentication.

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/checkout` | Checkout cart |
| GET | `/api/orders` | Get user's order history |
| GET | `/api/orders/{id}` | Get order detail |

## Authentication

Protected endpoints use Laravel Sanctum Bearer Token authentication.

Include the token in the request header:

```http
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

## Database

The application uses PostgreSQL with migrations for database schema management.

Run migrations:

```bash
php artisan migrate
```

Populate initial data:

```bash
php artisan db:seed
```

## API Testing

API endpoints can be tested using Postman.

The API supports:

- Authentication and authorization
- Product listing, search, filtering, and sorting
- Cart management
- Checkout and order history
- Request validation and error handling