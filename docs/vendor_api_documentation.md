# Vendor API Documentation

This document provides comprehensive documentation for the vendor API endpoints, including request/response formats, authentication requirements, and sample data.

## Authentication

All vendor API endpoints (except registration and login) require authentication using a Bearer token. The token is obtained after successful login.

## Base URL
```
https://your-domain.com/api/v1
```

## Vendor Authentication Endpoints

### 1. Register Vendor
- **Endpoint**: `POST /vendor/register`
- **Description**: Register a new vendor account
- **Authentication**: None required

#### Request Body
```json
{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "MySecurePassword123",
    "password_confirmation": "MySecurePassword123"
}
```

#### Response (Success 201)
```json
{
    "success": true,
    "message": "Vendor registered successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john.doe@example.com",
        "email_verified_at": null,
        "created_at": "2025-12-09T10:00:00.000000Z",
        "updated_at": "2025-12-09T10:00:00.000000Z"
    }
}
```

#### Response (Error 422)
```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

### 2. Login Vendor
- **Endpoint**: `POST /vendor/login`
- **Description**: Login a vendor and obtain authentication token
- **Authentication**: None required

#### Request Body
```json
{
    "email": "john.doe@example.com",
    "password": "MySecurePassword123"
}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "vendor": {
            "id": 1,
            "name": "John Doe",
            "email": "john.doe@example.com"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"
    }
}
```

### 3. Logout Vendor
- **Endpoint**: `POST /vendor/logout`
- **Description**: Logout the authenticated vendor
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

## Vendor Profile Endpoints

### 4. Get Vendor Profile
- **Endpoint**: `GET /vendor/profile`
- **Description**: Get the authenticated vendor's profile information
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john.doe@example.com",
        "account_balance": 5000.00,
        "created_at": "2025-12-09T10:00:00.000000Z",
        "updated_at": "2025-12-09T10:00:00.000000Z"
    }
}
```

### 5. Update Vendor Profile
- **Endpoint**: `PUT /vendor/profile`
- **Description**: Update the authenticated vendor's profile
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Request Body
```json
{
    "email": "john.newemail@example.com"
}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john.newemail@example.com",
        "account_balance": 5000.00,
        "created_at": "2025-12-09T10:00:00.000000Z",
        "updated_at": "2025-12-09T10:05:00.000000Z"
    }
}
```

### 6. Change Vendor Password
- **Endpoint**: `POST /vendor/password`
- **Description**: Change the authenticated vendor's password
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Request Body
```json
{
    "current_password": "MySecurePassword123",
    "new_password": "MyNewSecurePassword456",
    "new_password_confirmation": "MyNewSecurePassword456"
}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Password changed successfully"
}
```

## Customer Information Endpoints

### 7. Get Customer Info by Billing ID
- **Endpoint**: `GET /vendor/customer/info/{billingId}`
- **Description**: Get customer information using billing ID
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "data": {
        "id": 101,
        "first_name": "Jane",
        "surname": "Smith",
        "billing_id": "BILL-2025-001",
        "tariff": "Residential",
        "category": "Domestic",
        "account_balance": 150.00,
        "total_bill": 200.00,
        "status": "active"
    }
}
```

#### Response (Error 404)
```json
{
    "success": false,
    "message": "Customer not found"
}
```

## Payment Endpoints

### 8. Make Customer Payment
- **Endpoint**: `POST /vendor/payment`
- **Description**: Make a payment for a customer
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Request Body
```json
{
    "billing_id": "BILL-2025-001",
    "amount": 150.00,
    "payment_method": "cash"
}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Payment processed successfully",
    "data": {
        "payment": {
            "id": 50,
            "customer_id": 101,
            "amount": 150.00,
            "payment_date": "2025-12-09T10:00:00.000000Z",
            "method": "cash",
            "status": "successful",
            "transaction_ref": "VENDOR_1234567890",
            "payment_code": "VPC_1234567890"
        },
        "customer": {
            "id": 101,
            "first_name": "Jane",
            "surname": "Smith",
            "billing_id": "BILL-2025-001"
        }
    }
}
```

## Vendor Payment Management Endpoints

### 9. Fund Vendor Account
- **Endpoint**: `POST /vendor/payments/fund`
- **Description**: Fund the vendor's account balance
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Request Body
```json
{
    "amount": 1000.00
}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Funding initiated successfully",
    "data": {
        "payment": {
            "id": 25,
            "vendor_id": 1,
            "amount": 1000.00,
            "status": "pending",
            "transaction_ref": "VENDOR_FUND_20251209123456",
            "payment_code": "VP_123456"
        },
        "payment_url": "https://nabroll.com/payments/123456",
        "transaction_ref": "VENDOR_FUND_20251209123456"
    }
}
```

### 10. Initiate Payment
- **Endpoint**: `POST /vendor/payments/process`
- **Description**: Initiate a payment for a customer
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Request Body
```json
{
    "billing_id": "BILL-2025-001",
    "amount": 200.00,
    "payment_type": "online"
}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Payment initiated successfully",
    "data": {
        "payment": {
            "id": 30,
            "vendor_id": 1,
            "customer_id": 101,
            "billing_id": "BILL-2025-001",
            "amount": 200.00,
            "status": "pending",
            "transaction_ref": "VENDOR_PAYMENT_20251209654321"
        },
        "payment_url": "https://nabroll.com/payments/654321",
        "transaction_ref": "VENDOR_PAYMENT_20251209654321"
    }
}
```

### 11. Get Payment History
- **Endpoint**: `GET /vendor/payments`
- **Description**: Get vendor payment history with optional filtering
- **Authentication**: Bearer token required

#### Query Parameters (Optional)
- `start_date` - Filter by start date (format: YYYY-MM-DD)
- `end_date` - Filter by end date (format: YYYY-MM-DD)
- `status` - Filter by payment status (e.g., "SUCCESSFUL", "FAILED")
- `min_amount` - Minimum amount filter
- `max_amount` - Maximum amount filter
- `per_page` - Number of records per page (default: 10)

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 30,
                "vendor_id": 1,
                "customer_id": 101,
                "billing_id": "BILL-2025-001",
                "amount": 200.00,
                "payment_date": "2025-12-09T10:00:00.000000Z",
                "method": "NABRoll",
                "status": "successful",
                "payment_status": "SUCCESSFUL",
                "created_at": "2025-12-09T10:00:00.000000Z",
                "customer": {
                    "id": 101,
                    "first_name": "Jane",
                    "surname": "Smith",
                    "billing_id": "BILL-2025-001"
                }
            }
        ],
        "links": {
            "first": "https://your-domain.com/api/v1/vendor/payments?page=1",
            "last": "https://your-domain.com/api/v1/vendor/payments?page=1",
            "prev": null,
            "next": null
        },
        "meta": {
            "current_page": 1,
            "from": 1,
            "last_page": 1,
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "https://your-domain.com/api/v1/vendor/payments?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "path": "https://your-domain.com/api/v1/vendor/payments",
            "per_page": 10,
            "to": 1,
            "total": 1
        }
    }
}
```

### 12. Get Funding History
- **Endpoint**: `GET /vendor/payments/funding`
- **Description**: Get vendor account funding history
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 25,
                "vendor_id": 1,
                "amount": 1000.00,
                "payment_date": "2025-12-08T15:30:00.000000Z",
                "method": "NABRoll",
                "status": "successful",
                "payment_status": "SUCCESSFUL",
                "channel": "Vendor Account Funding",
                "created_at": "2025-12-08T15:30:00.000000Z"
            }
        ],
        "links": {
            "first": "https://your-domain.com/api/v1/vendor/payments/funding?page=1",
            "last": "https://your-domain.com/api/v1/vendor/payments/funding?page=1",
            "prev": null,
            "next": null
        },
        "meta": {
            "current_page": 1,
            "from": 1,
            "last_page": 1,
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "https://your-domain.com/api/v1/vendor/payments/funding?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "path": "https://your-domain.com/api/v1/vendor/payments/funding",
            "per_page": 10,
            "to": 1,
            "total": 1
        }
    }
}
```

### 13. Get Specific Payment Details
- **Endpoint**: `GET /vendor/payments/{id}`
- **Description**: Get details for a specific payment
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "data": {
        "id": 30,
        "vendor_id": 1,
        "customer_id": 101,
        "billing_id": "BILL-2025-001",
        "amount": 200.00,
        "payment_date": "2025-12-09T10:00:00.000000Z",
        "method": "NABRoll",
        "status": "successful",
        "payment_status": "SUCCESSFUL",
        "customer": {
            "id": 101,
            "first_name": "Jane",
            "surname": "Smith",
            "billing_id": "BILL-2025-001"
        }
    }
}
```

### 14. Get Customer Payment Details
- **Endpoint**: `GET /vendor/payments/customer/{customerId}`
- **Description**: Get payment details for a specific customer
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "data": [
        {
            "id": 30,
            "vendor_id": 1,
            "customer_id": 101,
            "billing_id": "BILL-2025-001",
            "amount": 200.00,
            "payment_date": "2025-12-09T10:00:00.000000Z",
            "method": "NABRoll",
            "status": "successful",
            "payment_status": "SUCCESSFUL",
            "customer": {
                "id": 101,
                "first_name": "Jane",
                "surname": "Smith",
                "billing_id": "BILL-2025-001"
            }
        }
    ]
}
```

### 15. Get Payment Statistics
- **Endpoint**: `GET /vendor/payments/statistics`
- **Description**: Get payment statistics for the vendor
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "data": {
        "total_payments": 15,
        "total_funding": 8,
        "successful_payments": 14,
        "successful_funding": 8,
        "total_paid_amount": 2500.00,
        "total_funded_amount": 12000.00,
        "recent_payments": [
            {
                "id": 30,
                "vendor_id": 1,
                "customer_id": 101,
                "amount": 200.00,
                "payment_date": "2025-12-09T10:00:00.000000Z",
                "method": "NABRoll",
                "status": "successful",
                "payment_status": "SUCCESSFUL"
            }
        ]
    }
}
```

## Error Responses

All API endpoints follow a consistent error response format:

#### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "field_name": [
            "The field_name field is required."
        ]
    }
}
```

#### Not Found Error (404)
```json
{
    "success": false,
    "message": "Payment not found"
}
```

#### Unauthenticated Error (401)
```json
{
    "message": "Unauthenticated."
}
```

#### Server Error (500)
```json
{
    "success": false,
    "message": "Payment processing failed: Error details here"
}
```

## Sample API Usage

### Example: Making a payment for a customer
```bash
curl -X POST "https://your-domain.com/api/v1/vendor/payment" \
  -H "Authorization: Bearer your-vendor-token" \
  -H "Content-Type: application/json" \
  -d '{
    "billing_id": "BILL-2025-001",
    "amount": 150.00,
    "payment_method": "cash"
  }'
```

### Example: Getting customer info
```bash
curl -X GET "https://your-domain.com/api/v1/vendor/customer/info/BILL-2025-001" \
  -H "Authorization: Bearer your-vendor-token"
```