# GMO Properties - Frontend Application

A modern, responsive frontend application for GMO Properties built with Laravel and Tailwind CSS.

## Features

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
- MySQL (or compatible database)

## Installation

1. Clone the repository:
```bash
cd /home/user-1/Desktop/My\ Buuild\ /gmoproperties
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

7. Build assets:
```bash
npm run build
```

Or for development with hot reload:
```bash
npm run dev
```

8. Start the development server:
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Project Structure

```
gmoproperties/
├── app/
│   └── Http/
│       └── Controllers/     # Page controllers
├── resources/
│   ├── views/               # Blade templates
│   │   ├── layouts/         # Layout templates
│   │   └── *.blade.php      # Page views
│   ├── css/                 # CSS files
│   └── js/                  # JavaScript files
├── routes/
│   └── web.php              # Web routes
└── public/                  # Public assets
```

## Routes

- `/` - Homepage
- `/about` - About Us
- `/project` - Our Projects
- `/vision` - Vision & Mission
- `/objective` - Objectives
- `/portfolio` - Portfolio
- `/team` - Meet Our Team
- `/contact` - Contact Us
- `/thank-you` - Thank You

## Technologies

- **Laravel 10** - PHP framework
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Build tool
- **Blade** - Templating engine

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

This project follows PSR-12 coding standards and Laravel best practices.

## License

MIT
