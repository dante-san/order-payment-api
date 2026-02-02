# Order Payment API

A RESTful API built with **CodeIgniter 4.7** for managing orders and payments.

## Overview

This API allows users to place orders, update payment statuses, and retrieve order summaries.  
Order status is automatically updated based on payment results.

## Features

- Create orders with default `PENDING` status
- Update payment status (`SUCCESS` / `FAILED`)
- Automatic order status update based on payment outcome
- Order summary with total, completed, failed, and pending counts
- SQLite database with transactional consistency
- Input validation and proper HTTP responses

## Order & Payment Flow

- Payment `SUCCESS` → Order `COMPLETED`
- Payment `FAILED` → Order `FAILED`

## Database Schema

- **users**: id, name, created_at
- **orders**: id, user_id, status, created_at
- **payments**: id, order_id, status, created_at

## API Endpoints

### Create Order

**POST** `/api/orders`

```json
{ "user_id": 1 }
```
