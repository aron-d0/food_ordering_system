# REST API Endpoints

The food API is public for straightforward Postman demonstration. No login or Authorization header is required.

Base local URL:

```text
http://127.0.0.1:8000
```

Production URL:

```text
https://foodorderingsystem-production-654c.up.railway.app
```

## Food APIs

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/foods` | Retrieve all food records |
| GET | `/api/foods/{id}` | Retrieve one food record |
| POST | `/api/foods` | Create a food record |
| PUT/PATCH | `/api/foods/{id}` | Update a food record |
| DELETE | `/api/foods/{id}` | Delete a food record |

## Example food JSON body

Use this body for POST and PUT/PATCH requests:

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

## Postman demo flow

1. Set the method.
2. Paste the endpoint URL.
3. For POST or PUT/PATCH, add the JSON body.
4. Click Send.

Example:

```http
GET https://foodorderingsystem-production-654c.up.railway.app/api/foods
```

Expected:

```text
200 OK
Food records returned
```
