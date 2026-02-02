# Order Payment API

A small REST API built with **CodeIgniter 4.7** to handle orders and payments.

## What it does

- Creates orders with a default `PENDING` status
- Updates payment status (`SUCCESS` / `FAILED`)
- Keeps order status in sync with payment outcome
- Provides a simple order summary

## Flow

- Payment `SUCCESS` → Order `COMPLETED`
- Payment `FAILED` → Order `FAILED`

## Data

- **users**: id, name, created_at
- **orders**: id, user_id, status, created_at
- **payments**: id, order_id, status, created_at

## Endpoints

### Create Order

**POST** `/api/orders`

```json
{ "user_id": 1 }
```

Response:

```json
{
  "success": true,
  "message": "Order placed successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "status": "PENDING",
    "created_at": "2026-02-02 10:00:00"
  }
}
```

### Update Payment Status

**PATCH** `/api/payments/{payment_id}/status`

```json
{ "status": "SUCCESS" }
```

Response:

```json
{
  "success": true,
  "message": "Payment status updated successfully",
  "data": {
    "id": 1,
    "order_id": 1,
    "status": "SUCCESS",
    "created_at": "2026-02-02 10:00:00"
  }
}
```

### Order Summary

**GET** `/api/orders/summary`

```json
{
  "total_orders": 1200,
  "completed_orders": 950,
  "failed_orders": 180,
  "pending_orders": 70
}
```

## Setup

```bash
composer install
php spark migrate
php spark db:seed UserSeeder
php spark serve
```

## Stack

- PHP 8.1+
- CodeIgniter 4.7
- SQLite

Notes: Uses transactions where updates affect multiple tables. Logic is kept in the service layer and explicit.
