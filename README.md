# GMO Properties - Property Management System

Property management system, AI driven and bring innovation to property business.

A comprehensive property management platform built with Laravel 10, featuring three distinct portals:
- **Service Provider Admin Portal** - Manage companies, subscriptions, and system-wide analytics
- **Client Admin Portal (Company Portal)** - Manage properties, units, tenants, contracts, invoices, and maintenance
- **Tenant Portal** - Apply for properties, view contracts, pay invoices, and submit maintenance requests

## Features

### Service Provider Admin Portal
- Company management and subscription plans (Basic, Professional, Enterprise)
- Feature access control per company
- System-wide analytics and financial reporting
- Company approval and activation workflow
- **Platform updates report** (`/admin/reports/updates`) — cross-portal activity timeline (companies, users, applications, contracts, invoices, maintenance, payments) with date and company filters, CSV/PDF export, and export audit rows in `admin_update_reports`

### Client Admin Portal (Company Portal)
- Building and unit management
- Tenant application processing
- Contract management and digital signing
- Invoice generation and payment tracking
- Maintenance ticket management
- Financial reporting and analytics
- Messaging system
- **Announcements** — company admins and property managers can create building-scoped or company-wide posts; optional schedule (`publish_at`) and expiry; drafts visible to author (managers: own drafts only); published items visible to staff; `php artisan announcements:publish-scheduled` (also registered in the scheduler) publishes due items
- **Notifications** — Laravel database notifications plus optional email per category; in-app **notification center** and **email preferences** (applications, contracts, invoices, maintenance, announcements)
- **Event-driven alerts** — staff notified on new tenant applications and new maintenance tickets; assignees on ticket assignment; tenants on application decisions, invoices, contract signature milestones, and maintenance status updates

### Tenant Portal
- Property browsing and application submission
- Contract viewing and digital signing
- Invoice viewing and payment tracking
- Maintenance request submission
- Messaging system
- **Announcements** — read company-published posts for their company
- **Notifications** and **email preferences** — same categories as the company portal where permissions apply

### Frontend Application
- **Homepage** - Company profile introduction with hero section
- **About Us** - Company information and background
- **Our Project** - Project portfolio and services
- **Vision & Mission** - Company vision and mission statements
- **Objectives** - Short, medium, and long-term objectives
- **Portfolio** - Image gallery of properties
- **Meet Our Team** - Team member profiles
- **Contact** - Contact information and form
- **Thank You** - Thank you page after form submission

## Design

The application features a clean, modern design with:
- Gold (#D4AF37), Black, and White color scheme
- Geometric diagonal overlays and frames
- Responsive layout for all devices
- Smooth transitions and hover effects

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js and npm
- SQLite (for development) or MySQL/PostgreSQL (for production)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/ThamiMabaso/gmoproperties.git
cd gmoproperties
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your `.env` file with database credentials and other settings.

7. Run migrations and seeders:
```bash
php artisan migrate --force
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=TestUsersSeeder
```

New migrations (from 2026-04-30 onward) add the `notifications` table, `announcements` / `announcement_reads`, `notification_preferences`, `admin_update_reports`, and Spatie permissions for reports, announcements, and notifications. Existing environments should run `migrate` once; roles receive the new permissions via the migration grant step (and the seeder on fresh installs).

If you see **Unknown column `published_at` on `announcements`**, an older `announcements` table may exist without the full schema. Run `php artisan migrate` so `2026_04_30_130000_ensure_announcements_table_columns` can add any missing columns. In code, prefer `Announcement::query()->publishedVisible()` instead of hand-written `where('expires_at', '>', now())`, which hides rows with a null expiry.

8. Build assets:
```bash
npm run build
```

Or for development with hot reload:
```bash
npm run dev
```

9. Start the development server:
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

### Scheduler (scheduled announcements)

Scheduled announcements are published when `publish_at` is in the past and the row is still unpublished. Register Laravel’s scheduler on the server (example: run `* * * * * cd /path-to-app && php artisan schedule:run >> /dev/null 2>&1`). For local testing you can run `php artisan announcements:publish-scheduled` manually.

## Test Credentials

See `CREDENTIALS.md` for test user credentials for all portals.

## Project Structure

```
gmoproperties/
├── app/
│   ├── Console/Commands/       # Artisan commands (e.g. scheduled announcements)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Service Provider Admin controllers
│   │   │   ├── Company/        # Client Admin Portal controllers
│   │   │   └── Tenant/         # Tenant Portal controllers
│   │   └── Middleware/         # Custom middleware
│   ├── Models/                 # Eloquent models
│   ├── Notifications/        # Laravel notifications (database + mail)
│   ├── Services/               # Domain services (reports, announcements)
│   └── Support/                # Small helpers (e.g. staff recipient lists)
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/                  # Blade templates
│   │   ├── admin/              # Service Provider Admin views
│   │   ├── company/            # Client Admin Portal views
│   │   ├── tenant/             # Tenant Portal views
│   │   └── layouts/            # Layout templates
│   ├── css/                    # CSS files
│   └── js/                     # JavaScript files
├── routes/
│   └── web.php                 # Web routes
└── public/                     # Public assets
```

## Routes

### Public Routes
- `/` - Homepage
- `/about` - About Us
- `/project` - Our Projects
- `/vision` - Vision & Mission
- `/objective` - Objectives
- `/portfolio` - Portfolio
- `/team` - Meet Our Team
- `/contact` - Contact Us
- `/thank-you` - Thank You

### Authentication
- `/login` - Login page
- `/register` - Registration page
- `/portal/dashboard` - Authenticated “portal” announcements preview (optional; uses `layouts.app`)

### Service Provider Admin Portal
- `/admin/dashboard` - Admin dashboard
- `/admin/companies` - Company management
- `/admin/reports/updates` - Platform updates report (filters, table)
- `/admin/reports/updates/export/csv` - CSV export
- `/admin/reports/updates/export/pdf` - PDF export

### Client Admin Portal
- `/{company}/dashboard` - Company dashboard
- `/{company}/buildings` - Building management
- `/{company}/units` - Unit management
- `/{company}/applications` - Tenant applications
- `/{company}/contracts` - Contract management
- `/{company}/invoices` - Invoice management
- `/{company}/maintenance` - Maintenance tickets
- `/{company}/financial` - Financial reports
- `/{company}/messages` - Messaging
- `/{company}/announcements` - Announcements (list, create, show, edit)
- `/{company}/notifications` - Notification center
- `/{company}/notification-preferences` - Email notification preferences

### Tenant Portal
- `/tenant/dashboard` - Tenant dashboard
- `/tenant/applications` - Property applications
- `/tenant/contracts` - Contracts
- `/tenant/invoices` - Invoices
- `/tenant/maintenance` - Maintenance requests
- `/tenant/messages` - Messaging
- `/tenant/announcements` - Announcements
- `/tenant/notifications` - Notification center
- `/tenant/notification-preferences` - Email notification preferences

## Technologies

- **Laravel 10** - PHP framework
- **Laravel Sanctum** - API authentication
- **Spatie Laravel Permission** - Role and permission management
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Build tool
- **Blade** - Templating engine
- **SQLite** - Development database

## Development

### Building Assets

For production:
```bash
npm run build
```

For development with hot reload:
```bash
npm run dev
```

### Code Style

This project follows:
- PSR-12 coding standards
- Laravel best practices
- Vaimo coding standards (where applicable)
- PHP 8+ features (strict types, constructor promotion, etc.)

## Author

**Thami Mabaso**
- Email: thamiherris@gmail.com

## License

MIT
 