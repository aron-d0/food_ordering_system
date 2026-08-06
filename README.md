# Online Food Ordering Management System

A Laravel-based food ordering application for ELEC1. It includes food CRUD, customer ordering, role-based middleware, Google OAuth hooks, and public REST APIs for straightforward Postman testing.

Live demo-ready deployment is configured for Railway.

## Demo accounts

After running the seeders:

| Role | Username | Password |
| --- | --- | --- |
| Admin | `admin` | `admin` |
| Customer | `customer` | `customer` |

## Run locally

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

## Main web routes

| Page | URL |
| --- | --- |
| Login | `/login` |
| Register | `/register` |
| Admin dashboard | `/admin/dashboard` |
| Admin food CRUD | `/admin/foods` |
| Admin order management | `/admin/orders` |
| Customer kiosk menu | `/menu` |
| Customer tray/cart | `/cart` |
| Customer checkout station | `/checkout` |
| Order success screen | `/order-success` |
| Customer orders | `/my-orders` |

## API routes

See [API endpoints](docs/api-endpoints.md).

Main Postman flow:

1. Pick the API method.
2. Paste the `/api/foods` URL.
3. Add a JSON body for create/update requests.
4. Send the request directly.

Postman collection:

```text
docs/postman/food_ordering_system.postman_collection.json
```

## Database documentation

See [database structure](docs/database-structure.md).

## Video/demo guide

See [demo guide](docs/demo-guide.md).

## Google OAuth setup

Add Google credentials in `.env`:

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

The login page includes a Google login button. Without valid Google credentials, normal username/password login still works.
