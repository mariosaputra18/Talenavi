# Talenavi — Product Catalog & Order API

REST API untuk aplikasi katalog produk dan pengelolaan pesanan yang dibangun menggunakan Laravel 12, PHP 8.2, dan PostgreSQL 16.

## Teknologi yang Digunakan

- PHP 8.2
- Laravel 12
- PostgreSQL 16
- Laravel Sanctum (Authentication)
- REST API
- Postman (API Testing)

### Alasan Pemilihan Teknologi

- **Laravel 12 & PHP 8.2** — digunakan untuk membangun REST API dengan struktur aplikasi yang terorganisasi, routing, controller, validasi request, migration, dan pengelolaan business logic.
- **PostgreSQL 16** — dipilih sebagai database relasional karena mendukung foreign key, transaksi, integritas data, dan konsistensi data. Fitur tersebut diperlukan untuk mengelola kategori, produk, keranjang, pesanan, stok, dan proses checkout.
- **Laravel Sanctum** — digunakan untuk autentikasi API menggunakan Bearer Token.
- **Postman** — digunakan untuk menguji endpoint API, request, authentication, dan response.

## Persyaratan

Pastikan perangkat telah memiliki:

- PHP >= 8.2
- Composer
- PostgreSQL 16
- Git

## Instalasi dan Konfigurasi

### 1. Clone Repository

```bash
git clone https://github.com/mariosaputra18/Talenavi.git
cd Talenavi
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file environment:

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
DB_PASSWORD=
```

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan konfigurasi PostgreSQL lokal.

### 4. Setup Database

Buat database PostgreSQL bernama `talenavi_db`.

Menggunakan PostgreSQL command line:

```sql
CREATE DATABASE talenavi_db;
```

Pastikan database telah dibuat dan konfigurasi koneksi pada `.env` sudah sesuai.

Project ini menggunakan PostgreSQL lokal dan tidak menggunakan Supabase.

### 5. Jalankan Database Migration

Buat struktur tabel database berdasarkan migration yang tersedia:

```bash
php artisan migrate
```

### 6. Jalankan Database Seeder

Masukkan data awal ke database:

```bash
php artisan db:seed
```

Seeder menyediakan:

- 5 kategori produk
- 30 produk
- 1 akun pengguna untuk pengujian

Seeder kategori dan produk dirancang agar dapat dijalankan kembali tanpa membuat duplikasi data awal.

### 7. Jalankan Aplikasi

Jalankan development server Laravel:

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

### Authentication (Autentikasi)

| Method | Endpoint | Deskripsi | Autentikasi |
|---|---|---|---|
| POST | `/api/register` | Mendaftarkan pengguna baru | Tidak |
| POST | `/api/login` | Melakukan autentikasi pengguna | Tidak |
| GET | `/api/me` | Mendapatkan data pengguna yang sedang login | Wajib |
| POST | `/api/logout` | Logout pengguna | Wajib |

### Categories (Kategori)

| Method | Endpoint | Deskripsi | Autentikasi |
|---|---|---|---|
| GET | `/api/categories` | Mendapatkan seluruh kategori | Tidak |

### Products (Produk)

| Method | Endpoint | Deskripsi | Autentikasi |
|---|---|---|---|
| GET | `/api/products` | Mendapatkan daftar produk dengan pagination, pencarian, filter, dan pengurutan | Tidak |
| GET | `/api/products/{id}` | Mendapatkan detail produk | Tidak |

#### Parameter Query Produk

| Parameter | Deskripsi |
|---|---|
| `page` | Nomor halaman |
| `limit` | Jumlah item per halaman (maksimal 50) |
| `q` | Mencari berdasarkan judul produk |
| `category` | Memfilter berdasarkan slug kategori |
| `sort` | Mengurutkan berdasarkan `price_asc`, `price_desc`, atau `newest` |

Contoh request:

```http
GET /api/products?page=1&limit=10&q=mouse&sort=price_asc
```

### Cart (Keranjang)

Seluruh endpoint keranjang memerlukan autentikasi.

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/cart` | Mendapatkan keranjang pengguna yang sedang login |
| POST | `/api/cart` | Menambahkan produk ke keranjang |
| PUT | `/api/cart/{id}` | Memperbarui jumlah produk dalam keranjang |
| DELETE | `/api/cart/{id}` | Menghapus item dari keranjang |

### Orders (Pesanan)

Seluruh endpoint pesanan memerlukan autentikasi.

| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/api/checkout` | Memproses checkout keranjang dan membuat pesanan |
| GET | `/api/orders` | Mendapatkan riwayat pesanan pengguna |
| GET | `/api/orders/{id}` | Mendapatkan detail pesanan |

## Autentikasi

Endpoint yang dilindungi menggunakan Laravel Sanctum Bearer Token.

Sertakan token pada request header:

```http
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

Token autentikasi yang valid diperlukan untuk mengakses endpoint yang dilindungi. Request tanpa token valid akan ditolak.

Token diperoleh melalui endpoint login dan digunakan pada endpoint yang memerlukan autentikasi.

## Contoh Request API

### 1. Register (Pendaftaran)

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

Gunakan token autentikasi yang diperoleh dari login untuk mengakses endpoint yang memerlukan autentikasi.

### 3. Mendapatkan Daftar Produk

Endpoint:

```http
GET /api/products?page=1&limit=10
```

Endpoint ini mendukung pagination, pencarian judul produk, filter kategori, dan pengurutan produk.

### 4. Menambahkan Produk ke Keranjang

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

Memerlukan Bearer Token yang valid.

### 5. Memperbarui Item Keranjang

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

Memerlukan Bearer Token yang valid.

### 6. Checkout

Endpoint:

```http
POST /api/checkout
```

Memerlukan Bearer Token yang valid.

Proses checkout mengambil data keranjang pengguna yang sedang login, membuat pesanan dan item pesanan, memperbarui stok produk, serta mengosongkan keranjang setelah transaksi berhasil.

## Response & Error Handling

API menggunakan HTTP status code untuk menunjukkan hasil request.

| Status Code | Deskripsi |
|---|---|
| `200 OK` | Request berhasil |
| `201 Created` | Resource berhasil dibuat |
| `401 Unauthorized` | Autentikasi diperlukan atau autentikasi tidak valid |
| `404 Not Found` | Resource tidak ditemukan |
| `422 Unprocessable Entity` | Validasi request gagal |
| `500 Internal Server Error` | Terjadi kesalahan server yang tidak terduga |

### Validation Error (Kesalahan Validasi)

Contoh response ketika validasi gagal:

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

### Unauthorized Request (Request Tidak Terautentikasi)

Endpoint yang dilindungi memerlukan token autentikasi yang valid.

Request tanpa autentikasi yang valid akan ditolak dengan status:

```http
401 Unauthorized
```

### Resource Not Found (Data Tidak Ditemukan)

Ketika resource yang diminta tidak tersedia atau tidak dapat diakses oleh pengguna yang sedang login, API mengembalikan:

```http
404 Not Found
```

### Checkout Error (Kesalahan Checkout)

Checkout melakukan validasi terhadap keranjang dan stok produk sebelum menyelesaikan transaksi. Checkout tidak dapat diselesaikan apabila keranjang kosong atau stok produk tidak mencukupi.

## Database

Aplikasi menggunakan PostgreSQL 16 sebagai sistem manajemen basis data relasional.

### Alasan Pemilihan Database

PostgreSQL dipilih karena mendukung pengelolaan data relasional, foreign key constraints, transaksi, dan integritas data. Fitur-fitur tersebut penting untuk sistem katalog produk dan pesanan, khususnya dalam mengelola stok produk, keranjang belanja, dan transaksi checkout.

### Struktur Database

| Tabel | Deskripsi |
|---|---|
| `users` | Menyimpan akun pengguna dan data autentikasi |
| `categories` | Menyimpan kategori produk |
| `products` | Menyimpan informasi produk, harga, dan stok |
| `cart_items` | Menyimpan produk dan jumlahnya dalam keranjang pengguna |
| `orders` | Menyimpan informasi pesanan dan total harga |
| `order_items` | Menyimpan produk, jumlah, dan snapshot harga pada setiap pesanan |

### Relasi Database

- Satu pengguna dapat memiliki beberapa item keranjang.
- Satu pengguna dapat memiliki beberapa pesanan.
- Satu kategori dapat memiliki beberapa produk.
- Satu produk dapat terdapat dalam beberapa item keranjang.
- Satu pesanan dapat memiliki beberapa item pesanan.
- Satu produk dapat terdapat dalam beberapa item pesanan.

### Database Migration

Struktur tabel database dibuat menggunakan Laravel migrations:

```bash
php artisan migrate
```

### Database Seeding

Data awal untuk pengujian dimasukkan menggunakan Laravel seeders:

```bash
php artisan db:seed
```

## Pengujian dan Dokumentasi API menggunakan Postman

API dapat diuji menggunakan Postman dengan file Collection dan Environment yang tersedia di repository.

### File Postman

File Postman tersedia di:

```text
postman/exports/
├── Talenavi_Product_Catalog_Order_API.postman_collection.json
└── Talenavi_Local.postman_environment.json
```

### Cara Membuka Postman Collection

1. Jalankan aplikasi Talenavi menggunakan `php artisan serve`.
2. Buka Postman.
3. Klik **Import**.
4. Pilih file `Talenavi_Product_Catalog_Order_API.postman_collection.json`.
5. Import file `Talenavi_Local.postman_environment.json` dengan cara yang sama.
6. Pilih environment **Talenavi Local** pada environment selector Postman.
7. Pastikan variabel `base_url` mengarah ke:

   ```text
   http://127.0.0.1:8000/api
   ```

8. Jalankan request **Login** untuk mendapatkan token autentikasi.
9. Gunakan request API lainnya untuk menguji fitur yang tersedia.

Collection mengelompokkan request berdasarkan:

- Auth
- Catalog
- Cart
- Orders

Environment menyediakan variabel `base_url` dan `token` untuk konfigurasi request API lokal dan autentikasi.

### Fitur yang Dapat Diuji

- Registrasi dan autentikasi pengguna
- Autentikasi dan otorisasi menggunakan Bearer Token
- Daftar produk dan pagination
- Pencarian judul produk
- Filter kategori
- Pengurutan produk
- Detail produk
- Pengelolaan keranjang
- Checkout dan pembuatan pesanan
- Riwayat pesanan dan detail pesanan
- Validasi request dan penanganan error
- Validasi stok produk saat checkout

## Struktur Project

Aplikasi menggunakan struktur project Laravel dengan pemisahan route API, controller, model, migration database, seeder, dan business logic.

| Direktori | Deskripsi |
|---|---|
| `app/` | Logika aplikasi, controller, model, dan service |
| `routes/` | Definisi route API |
| `database/migrations/` | Definisi struktur database |
| `database/seeders/` | Data awal database |
| `postman/` | Postman Collection lokal dan file export JSON |

## Repository

GitHub Repository:

https://github.com/mariosaputra18/Talenavi.git