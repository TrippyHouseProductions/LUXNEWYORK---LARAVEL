<<<<<<< HEAD
# LUXNEWYORK Ecommerce API Routes

## Guest (Public) APIs

| Method | Endpoint              | Description                      |
|--------|-----------------------|----------------------------------|
| GET    | /api/products         | List all products                |
| GET    | /api/products/{id}    | Get product details by ID        |
| GET    | /api/categories       | List all categories              |
| GET    | /api/categories/{id}  | Get category details by ID       |
| POST   | /api/login            | User/admin login, returns token  |

---

## Customer APIs *(Requires Bearer token; user must be logged in)*

| Method | Endpoint               | Description                        |
|--------|------------------------|------------------------------------|
| GET    | /api/cart              | Get current user's cart            |
| POST   | /api/cart              | Add item to cart                   |
| DELETE | /api/cart/{itemId}     | Remove item from cart by item ID   |
| DELETE | /api/cart              | Clear all items from cart          |
| GET    | /api/wishlist          | Get current user's wishlist        |
| POST   | /api/wishlist          | Add product to wishlist            |
| DELETE | /api/wishlist/{itemId} | Remove item from wishlist by ID    |
| GET    | /api/orders            | Get current user's order history   |
| POST   | /api/orders            | Place new order (checkout)         |
| POST   | /api/logout            | Logout (revoke token)              |
| GET    | /api/user              | Get current authenticated user     |
| GET    | /api/sanctum-test      | Sanctum test endpoint (auth check) |

---

## Admin APIs *(Requires Bearer token; user_type must be 'admin')*

### Products
| Method | Endpoint                    | Description                          |
|--------|-----------------------------|--------------------------------------|
| GET    | /api/admin/products         | List all products (admin view)       |
| GET    | /api/admin/products/{id}    | Get product details (admin)          |
| POST   | /api/admin/products         | Create a new product                 |
| PUT    | /api/admin/products/{id}    | Update a product by ID               |
| DELETE | /api/admin/products/{id}    | Delete a product by ID               |

### Categories
| Method | Endpoint                     | Description                         |
|--------|------------------------------|-------------------------------------|
| GET    | /api/admin/categories        | List all categories (admin view)    |
| GET    | /api/admin/categories/{id}   | Get category details (admin)        |
| POST   | /api/admin/categories        | Create a new category               |
| PUT    | /api/admin/categories/{id}   | Update a category by ID             |
| DELETE | /api/admin/categories/{id}   | Delete a category by ID             |

### Orders
| Method | Endpoint                    | Description                            |
|--------|-----------------------------|----------------------------------------|
| GET    | /api/admin/orders           | List all orders                        |
| POST   | /api/admin/orders/{id}      | Update order status (confirm/cancel)   |
| DELETE | /api/admin/orders/{id}      | Delete an order                        |

### Users
| Method | Endpoint                    | Description                |
|--------|-----------------------------|----------------------------|
| GET    | /api/admin/users            | List all registered users  |
| DELETE | /api/admin/users/{id}       | Delete a user by ID        |

---

> **Note:**  
> - All `/api/admin/*` endpoints require authentication and admin privileges (`user_type = admin`).
> - All `/api/*` endpoints (except guest/public) require a valid Bearer token.
> - Example tokens are returned by `/api/login`.

---
=======
# LUXNEWYORK Ecommerce API Routes

## Guest (Public) APIs

| Method | Endpoint              | Description                      |
|--------|-----------------------|----------------------------------|
| GET    | /api/products         | List all products                |
| GET    | /api/products/{id}    | Get product details by ID        |
| GET    | /api/categories       | List all categories              |
| GET    | /api/categories/{id}  | Get category details by ID       |
| POST   | /api/login            | User/admin login, returns token  |

---

## Customer APIs *(Requires Bearer token; user must be logged in)*

| Method | Endpoint               | Description                        |
|--------|------------------------|------------------------------------|
| GET    | /api/cart              | Get current user's cart            |
| POST   | /api/cart              | Add item to cart                   |
| DELETE | /api/cart/{itemId}     | Remove item from cart by item ID   |
| DELETE | /api/cart              | Clear all items from cart          |
| GET    | /api/wishlist          | Get current user's wishlist        |
| POST   | /api/wishlist          | Add product to wishlist            |
| DELETE | /api/wishlist/{itemId} | Remove item from wishlist by ID    |
| GET    | /api/orders            | Get current user's order history   |
| POST   | /api/orders            | Place new order (checkout)         |
| POST   | /api/logout            | Logout (revoke token)              |
| GET    | /api/user              | Get current authenticated user     |
| GET    | /api/sanctum-test      | Sanctum test endpoint (auth check) |

---

## Admin APIs *(Requires Bearer token; user_type must be 'admin')*

### Products
| Method | Endpoint                    | Description                          |
|--------|-----------------------------|--------------------------------------|
| GET    | /api/admin/products         | List all products (admin view)       |
| GET    | /api/admin/products/{id}    | Get product details (admin)          |
| POST   | /api/admin/products         | Create a new product                 |
| PUT    | /api/admin/products/{id}    | Update a product by ID               |
| DELETE | /api/admin/products/{id}    | Delete a product by ID               |

### Categories
| Method | Endpoint                     | Description                         |
|--------|------------------------------|-------------------------------------|
| GET    | /api/admin/categories        | List all categories (admin view)    |
| GET    | /api/admin/categories/{id}   | Get category details (admin)        |
| POST   | /api/admin/categories        | Create a new category               |
| PUT    | /api/admin/categories/{id}   | Update a category by ID             |
| DELETE | /api/admin/categories/{id}   | Delete a category by ID             |

### Orders
| Method | Endpoint                    | Description                            |
|--------|-----------------------------|----------------------------------------|
| GET    | /api/admin/orders           | List all orders                        |
| POST   | /api/admin/orders/{id}      | Update order status (confirm/cancel)   |
| DELETE | /api/admin/orders/{id}      | Delete an order                        |

### Users
| Method | Endpoint                    | Description                |
|--------|-----------------------------|----------------------------|
| GET    | /api/admin/users            | List all registered users  |
| DELETE | /api/admin/users/{id}       | Delete a user by ID        |

---

> **Note:**  
> - All `/api/admin/*` endpoints require authentication and admin privileges (`user_type = admin`).
> - All `/api/*` endpoints (except guest/public) require a valid Bearer token.
> - Example tokens are returned by `/api/login`.

---
>>>>>>> 0013a21 (all files first commit)
