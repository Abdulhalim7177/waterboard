# Waterboard Management System Documentation

## Overview
The Waterboard Management System is a comprehensive Laravel-based application designed to manage water distribution, billing, and customer information for a water utility company. The system includes multiple user types with role-based access controls and supports billing, payment processing, and location management across zones, districts, LGAs, wards, and areas.

## System Architecture

### Tech Stack
- **Framework**: Laravel 12
- **Frontend**: Bootstrap/Antora UI components
- **Authentication**: Multi-guard authentication (staff, customer, vendor)
- **Database**: Eloquent ORM with PostgreSQL/MySQL support
- **Permissions**: Spatie Laravel-permission package
- **PDF Generation**: Spatie Laravel-PDF
- **Excel Export**: Maatwebsite Excel package

### Database Structure

#### Core Models
1. **User Model** - Basic authentication model for web users
2. **Customer Model** - Water utility customers with billing and payment tracking
3. **Bill Model** - Monthly billing records for customers
4. **Payment Model** - Payment records and transactions
5. **Staff Model** - Water board employees with role-based access
6. **Vendor Model** - Third-party payment agents
7. **Location Models** - Hierarchical structure (Zone → District → LGA → Ward → Area)
8. **Tariff Model** - Pricing structure based on customer categories
9. **Paypoint Model** - Payment locations

## User Types and Authentication

### 1. Staff
- **Guard**: `staff`
- **Login Path**: `/mngr-secure-9374/login`
- **Role-based access**: Permissions for different administrative functions
- **Access scopes**: Defined by assigned paypoint (zone/district level)

### 2. Customers
- **Guard**: `customer`
- **Login Path**: `/customer/login`
- **Self-service**: Profile management, bill viewing, payment initiation
- **Billing ID**: Unique identifier for payments

### 3. Vendors
- **Guard**: `vendor`
- **Login Path**: `/vendor/login`
- **Functionality**: Make payments for customers using billing ID
- **Account management**: Fund account and process payments

## Core Functionality

### Customer Management
- **Registration**: Multi-step form for new customer onboarding
- **Location Assignment**: LGA, Ward, Area, Zone, District hierarchy
- **Category Assignment**: Customer categorized with appropriate tariff
- **Billing ID Generation**: Auto-generated unique billing identifier
- **Account Balance**: Customers can maintain account balance for automatic bill payments

### Billing System
- **Monthly Bill Generation**: Automated billing for customers based on tariffs
- **Approval Workflow**: Bills require approval before customer access
- **Status Tracking**: Payment status (paid, pending, overdue)
- **PDF Generation**: Bills can be downloaded as PDF documents
- **Balance Tracking**: Outstanding amounts are calculated in real-time

### Payment Processing
- **Multiple Methods**: NABRoll, Account Balance, Vendor payments
- **Payment Status Tracking**: Successful, Failed, Pending
- **Automatic Application**: Payments automatically applied to oldest bills first
- **Balance Management**: Customer account balance updates after payments

### Location Hierarchy
- **Zones**: Top-level administrative divisions
- **Districts**: Subdivisions within zones
- **LGAs** (Local Government Areas): Administrative units
- **Wards**: Subdivisions within LGAs
- **Areas**: Smallest geographic units where customers are located
- **Paypoints**: Payment collection points with defined access scopes

### Staff Access Control
- **Role Assignment**: Staff assigned specific roles with defined permissions
- **Hierarchical Access**: Access scoped to zone/district level based on paypoint
- **Audit Trail**: All actions are logged for accountability
- **Pending Approvals**: Staff manage pending customer and bill approvals

### Vendor System
- **Payment Processing**: Vendors collect payments using customer's billing ID
- **Account Funding**: Vendors can fund their accounts for payment facilitation
- **Transaction Tracking**: All vendor transactions are tracked and audited
- **API Access**: Separate API endpoints for vendor integration

## Key Features

### 1. Analytics & Reporting
- **Dashboard**: Comprehensive analytics for staff
- **Export Options**: CSV and Excel exports for data analysis
- **Combined Reports**: Billing and payment reports
- **GIS Integration**: Geographic Information System for mapping customers

### 2. HR Management
- **Staff Records**: Complete employee information management
- **Promotion Tracking**: Career progression and retirement planning
- **Access Management**: Role and permission assignment
- **Import/Export**: Bulk staff data management

### 3. GIS & Mapping
- **Geographic Visualization**: Customer locations on maps
- **Pipe Network**: Water distribution infrastructure
- **Route Planning**: Collection route optimization
- **Geospatial Queries**: Location-based customer searches

### 4. Audit Trail
- **Action Logging**: All significant actions are audited
- **Change Tracking**: Historical records of data modifications
- **Compliance**: Regulatory compliance tracking
- **Accountability**: User-specific action tracking

## Security Features
- **Multi-Guard Authentication**: Separate login systems for different user types
- **Rate Limiting**: Login attempt throttling
- **Role-Based Access Control**: Granular permission system
- **Session Management**: Secure session handling
- **Data Validation**: Input validation and sanitization
- **Password Policies**: Configurable password requirements

## API Endpoints
- **Staff API**: Administrative and management functions
- **Customer API**: Self-service functionality
- **Vendor API**: Payment processing for agents
- **GIS API**: Geographic information access

## Data Flow

### New Customer Registration
1. Customer information collected (personal, address, billing)
2. Location assigned (zone → district → LGA → ward → area)
3. Category and tariff assigned
4. Unique billing ID generated
5. Account created and pending approval
6. Staff approves customer account
7. Customer can log in and access services

### Billing Process
1. Monthly bills generated based on tariffs
2. Bills submitted for approval
3. Staff approves bills
4. Customers notified of bill availability
5. Payment options presented to customers

### Payment Processing
1. Customer or vendor initiates payment
2. Payment processed through selected method
3. Payment linked to customer account
4. Payment applied to oldest outstanding bills
5. Remaining balance (if any) added to customer account

### Account Balance Usage
1. Customer makes payment or receives credit
2. Amount added to customer account balance
3. System checks for outstanding bills
4. Account balance automatically applied to oldest bills
5. Balance updated after each payment application

## System Configuration
- **Authentication**: Configurable timeout and password policies
- **Location Settings**: Hierarchical location management
- **Tariff Configuration**: Category-based pricing structure
- **Permission Management**: Granular role and permission system
- **Audit Configuration**: Customizable audit trail settings

This comprehensive water management system provides all necessary functionality for a modern water utility company to manage customers, billing, payments, and staff operations in an efficient and secure manner.