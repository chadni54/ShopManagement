# ShopDemo API

Production-ready Laravel 12 ecommerce REST API for AMS integration.

## Stack

- Laravel 12 / PHP 8.3+
- MySQL 8 / Redis
- Laravel Sanctum
- L5-Swagger (OpenAPI)
- Domain modules with Repository + Service layers

## Quick start (SQLite local)

```bash
cp .env.example .env
# Ensure:
# APP_NAME=ShopDemo
# DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite
# QUEUE_CONNECTION=sync  (or database)
# DEMO_SEED_SCALE=demo

php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan serve
```

Admin credentials:

- Email: `admin@shopdemo.test`
- Password: `password`

## Docker

```bash
docker compose up -d --build
# MySQL on host port 3308, Redis on 6381
```

## API

Base URL: `/api/v1`

Public:

- `POST /api/v1/login`
- `GET /api/v1/health`
- `GET /health`

Authenticated (Bearer Sanctum token):

- Users, Customers, Categories, Brands, Products
- Inventory, Coupons, Orders (+ cancel), Payments
- Shipping, Invoices, Reviews
- Settings, Notifications

Response envelope:

```json
{
  "success": true,
  "message": "",
  "data": {},
  "meta": {},
  "links": {}
}
```

## AMS support SMS

Seeded endpoint uses `AMS_WEBHOOK_URL` and `AMS_WEBHOOK_SECRET` from the AMS incoming webhook (`shopdemo-support`).

Inbound customer SMS:

```bash
POST /api/v1/support/sms
```

ShopDemo signs the raw JSON body and posts `support.sms.received` to AMS. Agent Public replies come back as `support.sms.sent` on:

```bash
POST /api/v1/ams/support-replies
```

Headers on the outbound call:

- `X-ShopDemo-Event`
- `X-ShopDemo-Signature` (HMAC SHA-256 hex of the raw JSON body)
- `X-AMS-Signature` (`sha256=` plus that same hex)

Run a queue worker for deliveries:

```bash
php artisan queue:work
```

## Seed scale

```env
DEMO_SEED_SCALE=demo   # small dataset (default for testing)
DEMO_SEED_SCALE=full   # 500 products, 5000 customers, 15000 orders, 50000 items
```

## Swagger

```bash
php artisan l5-swagger:generate
# UI: /api/documentation
```

## Tests

```bash
php artisan test
```

## Postman

Import `postman/ShopDemo-API.postman_collection.json`.
