# Video Demo Guide

Recommended flow for a 10–20 minute presentation:

1. Introduce the project: Online Food Ordering Management System.
2. Show technologies used: Laravel, Blade/Tailwind, SQLite, Sanctum, Socialite.
3. Explain migrations for users, foods, and orders.
4. Explain relationships: users and foods both have many orders.
5. Login as admin: `admin / admin`.
6. Demonstrate food CRUD: add, view, edit, delete/unavailable.
7. Show order management and middleware-protected admin pages.
8. Login/register as customer: `customer / customer`.
9. Browse the kiosk menu, add items to the tray, review the tray, checkout, show the order number screen, then check order history.
10. Show Google OAuth routes/button and explain Google credentials in `.env`.
11. Open Postman:
    - call `/api/foods` without token and show unauthenticated response.
    - call `/api/login` and copy the token.
    - set Authorization to Bearer Token.
    - demonstrate GET, POST, PUT/PATCH, and DELETE food APIs.
12. Final walkthrough of admin/customer role separation.
