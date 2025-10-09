# Waterboard Management System API Documentation

## Overview

The Waterboard Management System API provides RESTful endpoints for managing customers, billing, payments, and staff operations. The API uses token-based authentication with different authentication guards for staff, customers, and vendors.

## Authentication

The API uses Laravel Sanctum for authentication. Different user types require different authentication methods:

- **Staff**: Authenticate using `auth:sanctum` guard with `staff` provider
- **Vendors**: Authenticate using custom `vendor.auth` middleware
- **Customers**: Authenticate using `auth:sanctum` guard with `customer` provider (though not fully implemented in the API routes)

### Authentication Endpoints

#### Staff Authentication

`POST /api/v1/staff/login`
- **Description**: Authenticate staff user and return API token
- **Request Body**:
  ```json
  {
    "email": "staff@example.com",
    "password": "password123"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Login successful",
    "data": {
      "staff": { /* staff object */ },
      "token": "token_string",
      "token_type": "Bearer"
    }
  }
  ```
- **Error Response (401)**:
  ```json
  {
    "success": false,
    "message": "Invalid credentials"
  }
  ```

`POST /api/v1/staff/register`
- **Description**: Register new staff user (requires appropriate permissions)
- **Request Body**:
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone_number": "1234567890",
    "lga_id": 1,
    "ward_id": 1,
    "area_id": 1
  }
  ```
- **Response** (201):
  ```json
  {
    "success": true,
    "message": "Registration successful",
    "data": {
      "staff": { /* staff object */ },
      "token": "token_string",
      "token_type": "Bearer"
    }
  }
  ```

`POST /api/v1/staff/logout`
- **Description**: Logout authenticated staff user
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Logout successful"
  }
  ```

`GET /api/v1/staff/user`
- **Description**: Get authenticated staff user details
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "data": { /* staff object */ }
  }
  ```

#### Vendor Authentication

`POST /api/v1/vendor/register`
- **Description**: Register new vendor account
- **Request Body**:
  ```json
  {
    "name": "Vendor Name",
    "email": "vendor@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }
  ```
- **Response** (201):
  ```json
  {
    "success": true,
    "message": "Vendor registered successfully",
    "data": { /* vendor object */ }
  }
  ```
- **Error Response (422)**:
  ```json
  {
    "success": false,
    "message": "Validation errors",
    "errors": { /* validation errors */ }
  }
  ```

`POST /api/v1/vendor/login`
- **Description**: Authenticate vendor and return API token
- **Request Body**:
  ```json
  {
    "email": "vendor@example.com",
    "password": "password123"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Login successful",
    "data": {
      "vendor": { /* vendor object */ },
      "token": "token_string"
    }
  }
  ```

`POST /api/v1/vendor/logout`
- **Description**: Logout authenticated vendor
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Logged out successfully"
  }
  ```

`GET /api/v1/vendor/profile`
- **Description**: Get authenticated vendor profile
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "data": { /* vendor object */ }
  }
  ```

## Staff Customer Management API

### Get All Customers

`GET /api/v1/staff/customers`
- **Description**: List all customers with pagination and filtering
- **Headers**: `Authorization: Bearer {token}`
- **Query Parameters**:
  - `search_customer`: Search by name, email, or billing ID
  - `lga_filter`: Filter by LGA ID
  - `ward_filter`: Filter by Ward ID
  - `area_filter`: Filter by Area ID
  - `category_filter`: Filter by Category ID
  - `tariff_filter`: Filter by Tariff ID
  - `status_filter`: Filter by status (e.g., pending, approved)
  - `page`: Page number (default: 1)
  - `per_page`: Items per page (default: 10)
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "current_page": 1,
      "data": [ /* array of customer objects */ ],
      "first_page_url": "...",
      "from": 1,
      "last_page": 2,
      "last_page_url": "...",
      "links": [ /* pagination links */ ],
      "next_page_url": "...",
      "path": "...",
      "per_page": 10,
      "prev_page_url": null,
      "to": 10,
      "total": 15
    }
  }
  ```

### Create Customer

`POST /api/v1/staff/customers`
- **Description**: Create a new customer
- **Headers**: `Authorization: Bearer {token}`
- **Request Body**:
  ```json
  {
    "first_name": "John",
    "surname": "Doe",
    "email": "johndoe@example.com",
    "phone_number": "1234567890",
    "password": "password123",
    "area_id": 1,
    "lga_id": 1,
    "ward_id": 1,
    "category_id": 1,
    "tariff_id": 1,
    "street_name": "Main Street",
    "house_number": "123",
    "landmark": "Near Post Office",
    "delivery_code": "DEL001",
    "billing_condition": "Metered",
    "water_supply_status": "Functional",
    "latitude": 6.4567,
    "longitude": 3.4567,
    "altitude": 100.5
  }
  ```
- **Response** (201):
  ```json
  {
    "success": true,
    "message": "Customer created successfully and is pending approval.",
    "data": { /* customer object */ }
  }
  ```
- **Error Response (422)**:
  ```json
  {
    "success": false,
    "message": "Validation errors",
    "errors": { /* validation errors */ }
  }
  ```

### Get Customer

`GET /api/v1/staff/customers/{id}`
- **Description**: Get a specific customer
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "data": { /* customer object */ }
  }
  ```

### Update Customer

`PUT /api/v1/staff/customers/{id}`
- **Description**: Update customer details (creates pending update for approval)
- **Headers**: `Authorization: Bearer {token}`
- **Request Body**: Any customer field to update
- **Response**:
  ```json
  {
    "success": true,
    "message": "Update submitted for approval."
  }
  ```

### Delete Customer

`DELETE /api/v1/staff/customers/{id}`
- **Description**: Delete a customer
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Customer deleted successfully"
  }
  ```

### Approve Customer

`POST /api/v1/staff/customers/{id}/approve`
- **Description**: Approve a pending customer
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Customer approved successfully"
  }
  ```

### Reject Customer

`POST /api/v1/staff/customers/{id}/reject`
- **Description**: Reject a customer application
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Customer rejected successfully"
  }
  ```

### Get Pending Customers

`GET /api/v1/staff/customers/pending-customers`
- **Description**: List all pending customer applications
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "data": { /* paginated list of pending customers */ }
  }
  ```

### Get Pending Updates

`GET /api/v1/staff/customers/pending-updates`
- **Description**: List all pending customer updates
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "data": { /* paginated list of pending updates */ }
  }
  ```

### Approve Pending Update

`POST /api/v1/staff/customers/pending/{updateId}/approve`
- **Description**: Approve a pending customer update
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Update approved successfully."
  }
  ```

### Reject Pending Update

`POST /api/v1/staff/customers/pending/{updateId}/reject`
- **Description**: Reject a pending customer update
- **Headers**: `Authorization: Bearer {token}`
- **Response**:
  ```json
  {
    "success": true,
    "message": "Update rejected successfully."
  }
  ```

## Vendor Payment API

### Make Payment

`POST /api/v1/vendor/payment`
- **Description**: Make payment for a customer using billing ID
- **Headers**: `Authorization: Bearer {token}`
- **Request Body**:
  ```json
  {
    "billing_id": "1234567890",  // Customer's billing ID
    "amount": 5000.00,          // Payment amount
    "payment_method": "cash"    // cash, pos, or transfer
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Payment processed successfully",
    "data": {
      "payment": { /* payment object */ },
      "customer": { /* customer object */ }
    }
  }
  ```
- **Error Response (404)**:
  ```json
  {
    "success": false,
    "message": "Customer not found"
  }
  ```

## Common Response Format

All API responses follow a consistent format:

```json
{
  "success": true/false,
  "message": "Human-readable message",
  "data": { /* actual data */ },
  "errors": { /* validation errors when applicable */ }
}
```

## HTTP Status Codes

- `200` - Success (for GET requests)
- `201` - Created (for POST requests)
- `401` - Unauthorized
- `404` - Resource not found
- `422` - Validation error
- `500` - Server error

## Error Handling

Error responses follow this format:
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

This API documentation outlines all the available endpoints for the Waterboard Management System, including authentication, customer management, and payment processing capabilities.