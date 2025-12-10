# Staff Vendor Management API Documentation

This document provides comprehensive documentation for the staff vendor management API endpoints, including request/response formats, authentication requirements, and sample data.

## Authentication

All staff vendor management API endpoints require authentication using Sanctum tokens via Bearer authentication.

## Base URL
```
https://your-domain.com/api/v1
```

## Staff Vendor Management Endpoints

### 1. Get All Vendors
- **Endpoint**: `GET /staff/vendors`
- **Description**: Get paginated list of all vendors
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Query Parameters (Optional)
- `page` - Page number for pagination (default: 1)
- `per_page` - Number of vendors per page (default: 10)

#### Response (Success 200)
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john.doe@example.com",
                "account_balance": 5000.00,
                "approved": true,
                "created_at": "2025-12-01T10:00:00.000000Z",
                "updated_at": "2025-12-01T10:00:00.000000Z"
            },
            {
                "id": 2,
                "name": "Jane Smith",
                "email": "jane.smith@example.com",
                "account_balance": 3500.00,
                "approved": false,
                "created_at": "2025-12-02T15:30:00.000000Z",
                "updated_at": "2025-12-02T15:30:00.000000Z"
            }
        ],
        "links": {
            "first": "https://your-domain.com/api/v1/staff/vendors?page=1",
            "last": "https://your-domain.com/api/v1/staff/vendors?page=2",
            "prev": null,
            "next": "https://your-domain.com/api/v1/staff/vendors?page=2"
        },
        "meta": {
            "current_page": 1,
            "from": 1,
            "last_page": 2,
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "https://your-domain.com/api/v1/staff/vendors?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": "https://your-domain.com/api/v1/staff/vendors?page=2",
                    "label": "2",
                    "active": false
                },
                {
                    "url": "https://your-domain.com/api/v1/staff/vendors?page=2",
                    "label": "Next &raquo;",
                    "active": true
                }
            ],
            "path": "https://your-domain.com/api/v1/staff/vendors",
            "per_page": 2,
            "to": 2,
            "total": 4
        }
    }
}
```

### 2. Get Filtered Vendors
- **Endpoint**: `GET /staff/vendors/filtered`
- **Description**: Get paginated list of vendors with filtering options
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Query Parameters (Optional)
- `status` - Filter by approval status ("approved", "pending")
- `search` - Search by vendor name, email, or vendor code
- `page` - Page number for pagination (default: 1)
- `per_page` - Number of vendors per page (default: 10)

#### Response (Success 200)
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john.doe@example.com",
                "account_balance": 5000.00,
                "approved": true,
                "created_at": "2025-12-01T10:00:00.000000Z",
                "updated_at": "2025-12-01T10:00:00.000000Z"
            }
        ],
        "links": {
            "first": "https://your-domain.com/api/v1/staff/vendors/filtered?page=1",
            "last": "https://your-domain.com/api/v1/staff/vendors/filtered?page=1",
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
                    "url": "https://your-domain.com/api/v1/staff/vendors/filtered?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "path": "https://your-domain.com/api/v1/staff/vendors/filtered",
            "per_page": 10,
            "to": 1,
            "total": 1
        }
    }
}
```

### 3. Get Vendor Statistics
- **Endpoint**: `GET /staff/vendors/statistics`
- **Description**: Get vendor statistics
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
        "total_vendors": 15,
        "approved_vendors": 12,
        "pending_vendors": 3
    }
}
```

### 4. Create New Vendor
- **Endpoint**: `POST /staff/vendors`
- **Description**: Create a new vendor account
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
Content-Type: application/json
```

#### Request Body
```json
{
    "name": "New Vendor Name",
    "email": "new.vendor@example.com",
    "password": "SecurePassword123",
    "password_confirmation": "SecurePassword123",
    "street_name": "123 Main Street",
    "vendor_code": "VENDOR-001",
    "lga_id": 1,
    "ward_id": 2,
    "area_id": 3
}
```

#### Response (Success 201)
```json
{
    "success": true,
    "message": "Vendor created successfully",
    "data": {
        "id": 3,
        "name": "New Vendor Name",
        "email": "new.vendor@example.com",
        "account_balance": 0.00,
        "street_name": "123 Main Street",
        "vendor_code": "VENDOR-001",
        "lga_id": 1,
        "ward_id": 2,
        "area_id": 3,
        "approved": true,
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
        ],
        "vendor_code": [
            "The vendor code has already been taken."
        ]
    }
}
```

### 5. Get Specific Vendor
- **Endpoint**: `GET /staff/vendors/{vendorId}`
- **Description**: Get details of a specific vendor
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
        "street_name": "456 Oak Avenue",
        "vendor_code": "VENDOR-001",
        "lga_id": 1,
        "ward_id": 2,
        "area_id": 3,
        "approved": true,
        "created_at": "2025-12-01T10:00:00.000000Z",
        "updated_at": "2025-12-01T10:00:00.000000Z"
    }
}
```

### 6. Update Vendor
- **Endpoint**: `PUT /staff/vendors/{vendorId}`
- **Description**: Update a specific vendor's information
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
Content-Type: application/json
```

#### Request Body
```json
{
    "name": "Updated Vendor Name",
    "email": "updated.vendor@example.com",
    "street_name": "789 Updated Street",
    "vendor_code": "VENDOR-001-UPDATED",
    "lga_id": 1,
    "ward_id": 2,
    "area_id": 3,
    "password": "NewSecurePassword123",
    "password_confirmation": "NewSecurePassword123"
}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Vendor updated successfully",
    "data": {
        "id": 1,
        "name": "Updated Vendor Name",
        "email": "updated.vendor@example.com",
        "account_balance": 5000.00,
        "street_name": "789 Updated Street",
        "vendor_code": "VENDOR-001-UPDATED",
        "lga_id": 1,
        "ward_id": 2,
        "area_id": 3,
        "approved": true,
        "created_at": "2025-12-01T10:00:00.000000Z",
        "updated_at": "2025-12-09T10:30:00.000000Z"
    }
}
```

### 7. Delete Vendor
- **Endpoint**: `DELETE /staff/vendors/{vendorId}`
- **Description**: Delete a specific vendor
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Vendor deleted successfully"
}
```

### 8. Approve Vendor
- **Endpoint**: `POST /staff/vendors/{vendorId}/approve`
- **Description**: Approve a vendor account
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Vendor approved successfully",
    "data": {
        "id": 2,
        "name": "Jane Smith",
        "email": "jane.smith@example.com",
        "approved": true,
        "updated_at": "2025-12-09T11:00:00.000000Z"
    }
}
```

### 9. Reject Vendor
- **Endpoint**: `POST /staff/vendors/{vendorId}/reject`
- **Description**: Reject a vendor account
- **Authentication**: Bearer token required

#### Headers
```
Authorization: Bearer {your-token-here}
```

#### Response (Success 200)
```json
{
    "success": true,
    "message": "Vendor rejected successfully",
    "data": {
        "id": 3,
        "name": "New Vendor Name",
        "email": "new.vendor@example.com",
        "approved": false,
        "updated_at": "2025-12-09T11:05:00.000000Z"
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
    "message": "Vendor not found"
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
    "message": "Server error occurred"
}
```

## Sample API Usage

### Example: Creating a new vendor
```bash
curl -X POST "https://your-domain.com/api/v1/staff/vendors" \
  -H "Authorization: Bearer your-staff-token" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "New Vendor Name",
    "email": "new.vendor@example.com",
    "password": "SecurePassword123",
    "password_confirmation": "SecurePassword123",
    "street_name": "123 Main Street",
    "vendor_code": "VENDOR-001",
    "lga_id": 1,
    "ward_id": 2,
    "area_id": 3
  }'
```

### Example: Approving a vendor
```bash
curl -X POST "https://your-domain.com/api/v1/staff/vendors/2/approve" \
  -H "Authorization: Bearer your-staff-token"
```

### Example: Getting filtered vendors
```bash
curl -X GET "https://your-domain.com/api/v1/staff/vendors/filtered?status=approved&search=john" \
  -H "Authorization: Bearer your-staff-token"
```