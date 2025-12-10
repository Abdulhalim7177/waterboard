# Complete Vendor API Endpoints Summary

This document provides a quick reference for all vendor-related API endpoints that have been implemented, mapping them to their corresponding web routes.

## Vendor Authentication & Profile Management

| Web Route | API Endpoint | HTTP Method | Description | Authentication |
|-----------|--------------|-------------|-------------|----------------|
| vendor.register | /api/v1/vendor/register | POST | Register a new vendor | None |
| vendor.login | /api/v1/vendor/login | POST | Login vendor and get token | None |
| vendor.logout | /api/v1/vendor/logout | POST | Logout vendor | Bearer token |
| vendor.profile | /api/v1/vendor/profile | GET | Get vendor profile | Bearer token |
| vendor.profile.update | /api/v1/vendor/profile | PUT | Update vendor profile | Bearer token |
| vendor.password.change | /api/v1/vendor/password | POST | Change vendor password | Bearer token |
| vendor.customer.info | /api/v1/vendor/customer/info/{billingId} | GET | Get customer info by billing ID | Bearer token |
| vendor.payment | /api/v1/vendor/payment | POST | Make payment for customer | Bearer token |

## Vendor Payment Management

| Web Route | API Endpoint | HTTP Method | Description | Authentication |
|-----------|--------------|-------------|-------------|----------------|
| vendor.payments.fund | /api/v1/vendor/payments/fund | POST | Fund vendor account | Bearer token |
| vendor.payments.fund.callback | /api/v1/vendor/payments/fund/callback | POST | Process funding callback | Bearer token |
| vendor.payments.initiate | /api/v1/vendor/payments/process | POST | Initiate payment for customer | Bearer token |
| vendor.payments.callback | /api/v1/vendor/payments/callback | GET | Process payment callback | Bearer token |
| vendor.payments.index | /api/v1/vendor/payments | GET | Get payment history | Bearer token |
| vendor.payments.funding | /api/v1/vendor/payments/funding | GET | Get funding history | Bearer token |

## Additional Vendor Payment Endpoints

| API Endpoint | HTTP Method | Description | Authentication |
|--------------|-------------|-------------|----------------|
| /api/v1/vendor/payments/{id} | GET | Get specific payment details | Bearer token |
| /api/v1/vendor/payments/funding/{id} | GET | Get specific funding details | Bearer token |
| /api/v1/vendor/payments/payments/{id} | GET | Get specific payment record | Bearer token |
| /api/v1/vendor/payments/customer/{customerId} | GET | Get customer payment details | Bearer token |
| /api/v1/vendor/payments/statistics | GET | Get payment statistics | Bearer token |

## Staff Vendor Management

| Web Route | API Endpoint | HTTP Method | Description | Authentication |
|-----------|--------------|-------------|-------------|----------------|
| staff.vendors.index | /api/v1/staff/vendors | GET | List all vendors | Sanctum token |
| staff.vendors.store | /api/v1/staff/vendors | POST | Create new vendor | Sanctum token |
| staff.vendors.show | /api/v1/staff/vendors/{vendor} | GET | Show specific vendor | Sanctum token |
| staff.vendors.update | /api/v1/staff/vendors/{vendor} | PUT | Update vendor | Sanctum token |
| staff.vendors.destroy | /api/v1/staff/vendors/{vendor} | DELETE | Delete vendor | Sanctum token |
| staff.vendors.approve | /api/v1/staff/vendors/{vendor}/approve | POST | Approve vendor | Sanctum token |
| staff.vendors.reject | /api/v1/staff/vendors/{vendor}/reject | POST | Reject vendor | Sanctum token |

## Additional Staff Vendor Endpoints

| API Endpoint | HTTP Method | Description | Authentication |
|--------------|-------------|-------------|----------------|
| /api/v1/staff/vendors/filtered | GET | Filter vendors with options | Sanctum token |
| /api/v1/staff/vendors/statistics | GET | Get vendor statistics | Sanctum token |

## Authentication Notes

1. **Vendor endpoints** require `vendor.auth` middleware using Sanctum tokens
2. **Staff endpoints** require `auth:sanctum` middleware using Sanctum tokens
3. **Payment endpoints** also use `restrict.login` middleware for additional security

## Middleware Applied

- **Vendor routes**: `vendor.auth`, `restrict.login`
- **Staff routes**: `auth:sanctum`
- **Payment routes**: `vendor.auth`, `restrict.login`

This comprehensive API mapping ensures that all web-based functionality is available through API endpoints, providing full RESTful access to vendor and staff vendor management features.