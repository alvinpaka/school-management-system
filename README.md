# EduManage Pro - School Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-red" alt="Laravel Version">
  <img src="https://img.shields.io/badge/Vue.js-3.x-green" alt="Vue Version">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-blue" alt="TailwindCSS Version">
  <img src="https://img.shields.io/badge/PHP-8.x-purple" alt="PHP Version">
  <img src="https://img.shields.io/badge/MySQL-8.x-orange" alt="Database">
</p>

<p align="center">
  <strong>A comprehensive school management platform built with Laravel, Vue.js, and Inertia.js</strong>
</p>

## 📋 Table of Contents

- [Features](#-features)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [User Roles & Permissions](#-user-roles--permissions)
- [System Architecture](#-system-architecture)
- [API Documentation](#-api-documentation)
- [Deployment](#-deployment)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

### 🎓 Core Modules
- **Student Management**: Enrollment, profiles, attendance tracking, and academic records
- **Teacher Management**: Staff profiles, class assignments, and performance tracking
- **Parent Portal**: Child monitoring, communication, and fee payments
- **Academic Management**: Subjects, class schedules, exams, and grading
- **Financial Management**: Fee collection, invoicing, and financial reporting
- **Library System**: Book catalog, borrowing, and inventory management
- **Transport Management**: Route planning, vehicle tracking, and safety monitoring
- **Communication Hub**: Announcements, messaging, and email integration

### 🛡️ Security & Access Control
- Role-based access control (RBAC) with granular permissions
- Secure authentication with Laravel Sanctum
- Data encryption and security best practices
- Audit logging for all critical operations

### 📊 Analytics & Reporting
- Real-time dashboards for administrators
- Academic performance analytics
- Financial reporting and insights
- Custom report generation
- Data visualization charts

### 🎨 User Experience
- Modern, responsive UI with Tailwind CSS
- Dark mode support
- Mobile-friendly design
- Real-time notifications
- Intuitive navigation

## 🚀 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 18.x or higher
- MySQL 8.0 or MariaDB 10.3+
- Redis (optional, for caching and queues)

### Step 1: Clone Repository
```bash
git clone https://github.com/your-username/school-management-system.git
cd school-management-system
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 3: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file with your database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 4: Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE school_management;

# Run migrations and seeders
php artisan migrate:fresh --seed
```

### Step 5: Frontend Build
```bash
# Install and build assets
npm install
npm run build
```

### Step 6: Start Application
```bash
# Start Laravel server
php artisan serve

# In another terminal, start Vite development server
npm run dev
```

Visit `http://localhost:8000` in your browser.

## ⚙️ Configuration

### Environment Variables
Key environment variables to configure:

```env
# Application
APP_NAME="EduManage Pro"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_management
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Redis (optional)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### File Permissions
```bash
# Set proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 👥 User Roles & Permissions

### Role Hierarchy
1. **Super Admin** - Full system control
2. **Admin** - School administration
3. **Teacher** - Academic management
4. **Accountant** - Financial operations
5. **Librarian** - Library management
6. **Receptionist** - Front desk operations
7. **Parent** - Child monitoring
8. **Student** - Personal access

### Permission Matrix

| Module | Admin | Teacher | Parent | Student | Accountant | Librarian |
|--------|-------|---------|--------|---------|------------|-----------|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Students | ✅ | 👁 | 👁 | 👁 | ✅ | ✅ |
| Teachers | ✅ | 👁 | 👁 | 👁 | ✅ | ✅ |
| Parents | ✅ | 👁 | 👁 | ❌ | ✅ | ❌ |
| Library | ✅ | 👁 | 👁 | 👁 | ❌ | ✅ |
| Account | ✅ | ❌ | 👁 | 👁 | ✅ | ❌ |
| Classes | ✅ | 👁 | 👁 | 👁 | ✅ | ❌ |
| Subjects | ✅ | 👁 | 👁 | 👁 | ❌ | ❌ |
| Attendance | ✅ | ➕ | 👁 | 👁 | ❌ | ❌ |
| Exams | ✅ | ➕ | 👁 | 👁 | ❌ | ❌ |
| Grades | ✅ | ➕ | 👁 | ❌ | ❌ | ❌ |
| Notice | ✅ | ➕ | 👁 | 👁 | ❌ | ✅ |
| Transport | ✅ | ❌ | 👁 | 👁 | ✅ | ❌ |
| Map | ✅ | 👁 | 👁 | 👁 | ❌ | ❌ |
| Report Cards | ✅ | ➕ | 👁 | 👁 | ✅ | ❌ |

**Legend**: ✅ Full Access | 👁 View Only | ➕ Create/Manage | ❌ No Access

## 🏗️ System Architecture

### Technology Stack
- **Backend**: Laravel 10.x (PHP 8.x)
- **Frontend**: Vue 3.x + Inertia.js
- **Styling**: TailwindCSS 3.x
- **Database**: MySQL 8.0 / MariaDB 10.3+
- **Authentication**: Laravel Sanctum
- **File Storage**: Local / S3
- **Queue**: Redis / Database
- **Cache**: Redis / File

### Directory Structure
```
├── app/
│   ├── Http/Controllers/          # API and Web Controllers
│   ├── Models/                    # Eloquent Models
│   ├── Policies/                  # Authorization Policies
│   └── Services/                  # Business Logic Services
├── database/
│   ├── migrations/                # Database Schema
│   ├── seeders/                   # Sample Data
│   └── factories/                 # Model Factories
├── resources/
│   ├── js/
│   │   ├── Components/            # Vue Components
│   │   ├── Layouts/               # Page Layouts
│   │   └── Pages/                 # Inertia Pages
│   └── views/                     # Blade Templates
├── routes/
│   ├── api.php                    # API Routes
│   ├── web.php                    # Web Routes
│   └── channels.php               # WebSocket Channels
└── storage/
    ├── app/                       # Application Files
    └── framework/                 # Laravel Framework Files
```

### Key Components
- **Sidebar.vue**: Dynamic navigation based on user roles
- **Welcome.vue**: Landing page with feature showcase
- **Dashboard.vue**: Role-specific dashboard views
- **Permission System**: Granular access control
- **Notification System**: Real-time alerts and updates

## 📚 API Documentation

### Authentication Endpoints
```http
POST /api/login
POST /api/logout
POST /api/register
GET  /api/user
```

### Student Management
```http
GET    /api/students           # List students
POST   /api/students           # Create student
GET    /api/students/{id}      # Show student
PUT    /api/students/{id}      # Update student
DELETE /api/students/{id}      # Delete student
```

### Attendance Management
```http
GET    /api/attendance         # List attendance records
POST   /api/attendance         # Mark attendance
GET    /api/attendance/report  # Attendance reports
```

### Financial Management
```http
GET    /api/fees              # List fee structures
POST   /api/payments          # Process payment
GET    /api/invoices          # List invoices
```

## 🚀 Deployment

### Production Setup
1. **Server Requirements**
   - Ubuntu 20.04+ / CentOS 8+
   - Nginx or Apache
   - PHP 8.1+ with required extensions
   - MySQL 8.0+ or MariaDB 10.3+
   - Redis (recommended)

2. **Deployment Steps**
```bash
# Clone repository
git clone https://github.com/your-username/school-management-system.git
cd school-management-system

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install --production

# Environment setup
cp .env.example .env
php artisan key:generate

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Build assets
npm run build

# Set permissions
chmod -R 755 storage bootstrap/cache
```

3. **Nginx Configuration**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/school-management-system/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Environment Optimization
```bash
# Queue worker setup
php artisan queue:work --daemon

# Schedule tasks
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1

# Cache optimization
php artisan optimize
```

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter UserTest

# Generate coverage report
php artisan test --coverage
```

### Test Coverage
- Unit tests for business logic
- Feature tests for API endpoints
- Browser tests for critical user flows
- Database tests for data integrity

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation
- Use conventional commit messages

## 📝 Changelog

### Version 1.0.0 (2024-01-15)
- Initial release
- Core school management features
- Role-based access control
- Responsive UI design
- API documentation

## 🐛 Bug Reporting

If you encounter any issues:

1. Check existing [issues](https://github.com/your-username/school-management-system/issues)
2. Create a new issue with:
   - Detailed description
   - Steps to reproduce
   - Environment details
   - Screenshots (if applicable)

## 🔒 Security

If you discover a security vulnerability, please send an email to security@edumanage.pro instead of using the issue tracker.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
- [Vue.js](https://vuejs.org) - The Progressive JavaScript Framework
- [TailwindCSS](https://tailwindcss.com) - A utility-first CSS framework
- [Inertia.js](https://inertiajs.com) - The modern monolith for SPAs

## 📞 Support

- Email: support@edumanage.pro
- Documentation: https://docs.edumanage.pro
- Community Forum: https://community.edumanage.pro

---

<p align="center">
  Made with ❤️ by the EduManage Pro Team
</p>
