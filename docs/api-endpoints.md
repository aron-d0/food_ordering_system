# REST API Endpoints

Base local URL:

```text
http://127.0.0.1:8000
```

## Authentication

### Login and generate token

```http
POST /api/login
Content-Type: application/json
```

Body:

```json
{
  "username": "admin",
  "password": "admin"
}
```

Response includes:

```json
{
  "token": "generated_token",
  "token_type": "Bearer"
}
```

Use the returned token in Postman:

```text
Authorization: Bearer generated_token
```

### Logout/revoke token

```http
POST /api/logout
Authorization: Bearer generated_token
```

## Protected Food APIs

All `/api/foods` routes require a valid Bearer token.

| Method | Endpoint | Purpose | Role |
| --- | --- | --- | --- |
| GET | `/api/foods` | Retrieve food records | Admin or Customer |
| GET | `/api/foods/{id}` | Retrieve one food record | Admin or Customer |
| POST | `/api/foods` | Create food record | Admin |
| PUT/PATCH | `/api/foods/{id}` | Update food record | Admin |
| DELETE | `/api/foods/{id}` | Delete food record | Admin |

## Example food JSON body

```json
{
  "name": "Burger Steak Meal",
  "category": "Rice Meals",
  "price": 129,
  "description": "Burger steak with rice and gravy.",
  "stock_quantity": 20,
  "is_available": 1,
  "image_url": "images/gravy-chicken-chop-with-plain-rice.png"
}
```

## Demo expectations

Without token:

```text
GET /api/foods
Expected: 401 Unauthenticated
```

With token:

```text
GET /api/foods
Expected: 200 OK with food records
```
