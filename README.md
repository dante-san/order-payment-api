# Order Payment API

CodeIgniter 4 REST API for order and payment management.

## Task Status: ✅ COMPLETED

Built for backend developer assessment. All requirements met.

### Requirements

- API to place orders (PENDING status)
- API to update payment status (SUCCESS/FAILED)
- Payment updates trigger order status changes
- Summary API showing order counts

### Implementation Status

✅ Place order endpoint  
✅ Update payment status endpoint  
✅ Summary endpoint (exact format as requested)  
✅ SQLite database  
✅ Users, orders, payments tables  
✅ Transaction handling  
✅ Validation

## Database Tables

**users**: id, name, created_at  
**orders**: id, user_id, status, created_at  
**payments**: id, order_id, status, created_at

Order statuses: PENDING, COMPLETED, FAILED  
Payment statuses: PENDING, SUCCESS, FAILED

Flow: Payment SUCCESS → Order COMPLETED | Payment FAILED → Order FAILED

## Setup

```bash
# Install dependencies
composer install

# Configure database (already set to SQLite)
# Database file: writable/database.db

# Run migrations
php spark migrate

# Seed test users
php spark db:seed UserSeeder

# Start server
php spark serve
```

API runs at: `http://localhost:8080`

## API Endpoints

### 1. Place Order

**POST** `/api/orders`

Request:

```json
{
  "user_id": 1
}
```

Response (201):

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

### 2. Update Payment Status

**PATCH** `/api/payments/{payment_id}/status`

Request:

```json
{
  "status": "SUCCESS"
}
```

Response (200):

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

### 3. Order Summary

**GET** `/api/orders/summary`

Response (200):

```json
{
  "total_orders": 1200,
  "completed_orders": 950,
  "failed_orders": 180,
  "pending_orders": 70
}
```

## Testing

```bash
# Place order
curl -X POST http://localhost:8080/api/orders \
  -H "Content-Type: application/json" \
  -d '{"user_id": 1}'

# Update payment to SUCCESS
curl -X PATCH http://localhost:8080/api/payments/1/status \
  -H "Content-Type: application/json" \
  -d '{"status": "SUCCESS"}'

# Get summary
curl http://localhost:8080/api/orders/summary
```

## Tech Stack

- CodeIgniter 4
- SQLite
- PHP 8.1+

## Project Structure

```
app/
├── Controllers/Api/
│   ├── Orders.php
│   └── Payments.php
├── Models/
│   ├── UserModel.php
│   ├── OrderModel.php
│   └── PaymentModel.php
├── Database/
│   ├── Migrations/
│   │   ├── *_create_users_table.php
│   │   ├── *_create_orders_table.php
│   │   └── *_create_payments_table.php
│   └── Seeds/
│       └── UserSeeder.php
└── Config/
    ├── Routes.php
    └── Database.php
```

## Notes

- Used database transactions for data consistency
- Order status auto-updates based on payment status
- Input validation on all endpoints
- Error handling with proper HTTP codes

Works as expected.
