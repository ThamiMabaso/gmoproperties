# GMO Properties - Test Credentials

## Overview
This document contains test credentials for all portals in the GMO Properties system.

---

## 🔐 Service Provider Admin Portal
**Purpose:** System-wide administration by GMO (Good Morning Properties)

**URL:** `http://127.0.0.1:8000/admin/dashboard`

**Credentials:**
- **Email:** `admin@gmo-properties.co.za`
- **Password:** `admin123`

**Capabilities:**
- Register & manage property companies
- Assign subscription plans
- Control feature access
- View system-wide analytics
- Financial overview (platform revenue)
- Financial view per company
- Logs & audit trails
- AI usage monitoring

---

## 🏢 Company 1: Premium Properties
**Company Slug:** `premium-properties`  
**URL:** `http://127.0.0.1:8000/premium-properties/dashboard`

### Company Admin
**Email:** `admin@premiumproperties.co.za`  
**Password:** `company123`

**Capabilities:**
- Full access to company data
- Manage buildings & units
- Tenant management
- Contracts & leases
- Invoices & payments
- Maintenance tickets
- Expenses & profit tracking
- Financial reports

### Property Manager
**Email:** `manager@premiumproperties.co.za`  
**Password:** `manager123`

**Capabilities:**
- View buildings & units
- View tenants
- View contracts
- Create invoices
- View payments
- Create payments
- Manage maintenance tickets
- View expenses
- Create expenses

### Tenant
**Email:** `tenant@premiumproperties.co.za`  
**Password:** `tenant123`

**URL:** `http://127.0.0.1:8000/tenant/dashboard`

**Capabilities:**
- Application submission
- Profile management
- View invoices & payments
- Submit maintenance tickets
- View maintenance tickets
- View contracts
- Sign contracts
- Download documents

---

## 🎓 Company 2: Student Housing SA
**Company Slug:** `student-housing-sa`  
**URL:** `http://127.0.0.1:8000/student-housing-sa/dashboard`

### Company Admin
**Email:** `admin@studenthousing.co.za`  
**Password:** `company123`

**Capabilities:**
- Full access to company data
- Manage buildings & units
- Tenant management
- Contracts & leases
- Invoices & payments
- Maintenance tickets
- Expenses & profit tracking
- Financial reports

### Tenant (Student)
**Email:** `student@studenthousing.co.za`  
**Password:** `tenant123`

**URL:** `http://127.0.0.1:8000/tenant/dashboard`

**Capabilities:**
- Application submission (student form)
- Profile management
- View invoices & payments
- Submit maintenance tickets
- View maintenance tickets
- View contracts
- Sign contracts
- Download documents

---

## 📝 Notes

1. **All passwords are:** Simple test passwords for development
2. **Company URLs:** Use the company slug in the URL path (e.g., `/premium-properties/dashboard`)
3. **Tenant Portal:** All tenants use the same `/tenant/dashboard` URL regardless of company
4. **Service Provider Admin:** Can access all companies via `/admin/companies`

---

## 🚀 Quick Start

1. Run migrations and seeders:
   ```bash
   php artisan migrate --force
   php artisan db:seed --class=RolesAndPermissionsSeeder
   php artisan db:seed --class=TestUsersSeeder
   ```

2. Start the development server:
   ```bash
   php artisan serve
   ```

3. Navigate to `http://127.0.0.1:8000` and login with any of the credentials above.

---

## 🔄 Resetting Test Data

To reset all test data:
```bash
php artisan migrate:fresh --seed
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=TestUsersSeeder
```
