# Online Food Ordering Management System Database

## Tables

### users

Stores account and authentication information.

| Column | Purpose |
| --- | --- |
| id | Primary key |
| name | User/customer/admin name |
| username | Unique login username |
| email | Optional email saved for Google sign-in accounts |
| password | Hashed password, nullable for OAuth accounts |
| role | `admin` or `customer` |
| google_id | Google OAuth account identifier |
| avatar | OAuth profile image URL |

### foods

Stores food menu information.

| Column | Purpose |
| --- | --- |
| id | Primary key |
| name | Food name |
| category | Food category |
| price | Food price |
| description | Menu description |
| stock_quantity | Available stock |
| is_available | Availability status |
| image_url | Optional image path |

### orders

Stores customer orders.

| Column | Purpose |
| --- | --- |
| id | Primary key |
| user_id | Customer who placed the order |
| food_id | Food ordered |
| quantity | Quantity ordered |
| total_price | Price × quantity |
| status | Pending, Preparing, Completed, Cancelled |
| order_date | Date/time the order was placed |

## Relationships

```text
users 1 ─── many orders
foods 1 ─── many orders
orders many ─── 1 users
orders many ─── 1 foods
```

The `orders` table connects customers to the foods they ordered. Admin users manage food records and order status; customer users browse available food and create orders.
