# API Contract

Base URL: `http://localhost:8000/api`

## Response Envelope

All responses follow this format:

**Success:**
```json
{
  "success": true,
  "data": { ... },
  "message": "..."
}
```

**Error:**
```json
{
  "success": false,
  "message": "...",
  "errors": { ... } | null
}
```

**Validation Error (422):**
```json
{
  "success": false,
  "message": "Validation failed.",
  "errors": { "field": ["error message"] }
}
```

**Paginated Lists:**
```json
{
  "success": true,
  "data": {
    "items": [ ... ],
    "meta": {
      "current_page": 1,
      "last_page": 3,
      "per_page": 15,
      "total": 42
    }
  },
  "message": "..."
}
```

---

## Authentication

### POST /api/auth/register
Create a new user account.

**Body (JSON):**
| Field | Type | Required | Rules |
|-------|------|----------|-------|
| name | string | yes | max:255 |
| email | string | yes | valid email, unique |
| password | string | yes | min:3, confirmed |
| password_confirmation | string | yes | must match password |

**Response:** `201`
```json
{
  "success": true,
  "data": {
    "user": { "id", "name", "email", "created_at", "updated_at" },
    "token": "plaintext-token"
  },
  "message": "Registration successful."
}
```

### POST /api/auth/login
Authenticate and receive a token.

**Body (JSON):**
| Field | Type | Required |
|-------|------|----------|
| email | string | yes |
| password | string | yes |

**Response:** `200`
```json
{
  "success": true,
  "data": {
    "user": { ... },
    "token": "plaintext-token"
  },
  "message": "Login successful."
}
```

**Error:** `401` — Invalid credentials.

### POST /api/auth/logout
**Auth:** Bearer token required.

**Response:** `200`
```json
{ "success": true, "data": null, "message": "Logged out successfully." }
```

---

## Public Endpoints (No Auth)

### GET /api/categories
List all categories.

**Response:** `200`
```json
{
  "success": true,
  "data": [
    { "id", "name", "slug", "image": "absolute-url | null", "created_at", "updated_at" }
  ],
  "message": "Categories retrieved."
}
```

### GET /api/products
List products with filters and pagination.

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| search | string | Search name/description |
| category | int | Filter by category_id |
| min_price | numeric | Minimum price |
| max_price | numeric | Maximum price |
| sort | string | `price_asc`, `price_desc`, `latest` (default) |
| page | int | Page number |
| per_page | int | Items per page (default: 15) |

**Response:** `200` — Paginated envelope with `items` + `meta`.

Each product:
```json
{
  "id", "category_id", "category": { ... },
  "name", "slug", "description", "price", "stock",
  "image": "absolute-url | null",
  "average_rating": 4.2,
  "reviews_count": 10,
  "created_at", "updated_at"
}
```

### GET /api/products/{idOrSlug}
Get a single product by ID or slug.

**Response:** `200`
```json
{ "success": true, "data": { ...product }, "message": "Product retrieved." }
```

### GET /api/products/{id}/reviews
List reviews for a product (paginated).

**Query:** `page`, `per_page`

**Response:** `200` — Paginated envelope.

Each review:
```json
{ "id", "user_id", "user_name", "product_id", "rating", "comment", "created_at" }
```

---

## Protected Endpoints (Bearer Token Required)

### Wishlist

#### GET /api/wishlist
**Response:** `200`
```json
{
  "success": true,
  "data": [
    { "id", "product": { ... }, "created_at" }
  ],
  "message": "Wishlist retrieved."
}
```

#### POST /api/wishlist
**Body:** `{ "product_id": 1 }`

**Response:** `201` — Wishlist item with product.
**Error:** `409` — Already in wishlist.

#### DELETE /api/wishlist/{productId}
**Response:** `200`
**Error:** `404` — Not in wishlist.

---

### Cart

#### GET /api/cart
**Response:** `200`
```json
{
  "success": true,
  "data": {
    "items": [
      { "id", "product": { ... }, "quantity", "subtotal": "79.98" }
    ],
    "total": "159.96"
  },
  "message": "Cart retrieved."
}
```

#### POST /api/cart
**Body:** `{ "product_id": 1, "quantity": 2 }`

**Response:** `201` (new item) or `200` (quantity incremented).

#### PUT /api/cart/{itemId}
**Body:** `{ "quantity": 3 }`

**Response:** `200` — Updated cart item.

#### DELETE /api/cart/{itemId}
**Response:** `200`

---

### Checkout

#### POST /api/checkout
Creates an order from the user's cart. Runs in a DB transaction: snapshots unit prices, computes total, decrements stock, clears cart.

**Body:** None.

**Response:** `201`
```json
{
  "success": true,
  "data": {
    "id", "user_id", "total", "status": "pending",
    "items": [
      { "id", "product_id", "product": { ... }, "quantity", "unit_price", "subtotal" }
    ],
    "created_at", "updated_at"
  },
  "message": "Order placed successfully."
}
```

**Errors:**
- `422` — Cart is empty.
- `422` — Insufficient stock.

---

### Orders

#### GET /api/orders
Own orders, paginated.

**Query:** `page`, `per_page`

**Response:** `200` — Paginated envelope with order items included.

#### GET /api/orders/{id}
Single order detail (own only).

**Response:** `200`
**Error:** `403` — Not the owner.

---

### Profile

#### PUT /api/profile
**Body:** `{ "name": "New Name", "email": "new@email.com" }`

**Response:** `200`
```json
{ "success": true, "data": { ...user }, "message": "Profile updated." }
```

#### PUT /api/profile/password
**Body:**
```json
{
  "current_password": "old",
  "password": "new",
  "password_confirmation": "new"
}
```

**Response:** `200`
```json
{ "success": true, "data": null, "message": "Password updated." }
```

---

### Reviews

#### POST /api/products/{id}/reviews
**Auth required.**

**Body:** `{ "rating": 5, "comment": "Great product!" }`

| Field | Type | Required | Rules |
|-------|------|----------|-------|
| rating | int | yes | 1-5 |
| comment | string | no | max:1000 |

**Response:** `201`
**Error:** `409` — Already reviewed.

---

## Admin Panel (Web, Session Auth)

- `GET /admin/login` — Login page
- `POST /admin/login` — Authenticate
- `POST /admin/logout` — Logout
- `GET /admin/` — Dashboard (counts + sales stats)
- `GET /admin/categories` — List categories
- `GET /admin/categories/create` — Create form
- `POST /admin/categories` — Store category
- `GET /admin/categories/{id}/edit` — Edit form
- `PUT /admin/categories/{id}` — Update category
- `DELETE /admin/categories/{id}` — Delete category
- `GET /admin/products` — List products
- `GET /admin/products/create` — Create form
- `POST /admin/products` — Store product
- `GET /admin/products/{id}/edit` — Edit form
- `PUT /admin/products/{id}` — Update product
- `DELETE /admin/products/{id}` — Delete product
- `GET /admin/orders` — List orders
- `GET /admin/orders/{id}` — View order detail
- `GET /admin/users` — List users

All admin routes require session auth + admin role.
