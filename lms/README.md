# 🎓 Learning Management System (LMS)

<img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&amp;logo=laravel&amp;logoColor=white" alt="Laravel"> <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&amp;logo=php&amp;logoColor=white" alt="PHP"> <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">

A comprehensive, feature-rich Learning Management System built with Laravel 12, designed to facilitate online education and course management for administrators, instructors, and students.

---

## 📋 Table of Contents

-   [Features](#-features)
-   [Tech Stack](#-tech-stack)
-   [System Requirements](#-system-requirements)
-   [Installation](#-installation)
-   [Configuration](#-configuration)
-   [Usage](#-usage)
-   [Project Structure](#-project-structure)
-   [User Roles](#-user-roles)
-   [Contributing](#-contributing)
-   [License](#-license)
-   [Support](#-support)

---

## ✨ Features

### 🔐 Authentication & Authorization

-   Multi-role authentication system (Admin, Instructor, User)
-   Role-based access control with Spatie Laravel Permission
-   Secure login/logout with Laravel Breeze
-   Email verification
-   Password reset functionality

### 👨‍💼 Admin Panel

-   Complete dashboard with analytics
-   User management
-   Course management and approval
-   Category and subcategory management
-   Coupon management system
-   Order and payment tracking
-   Blog management
-   System settings configuration
-   SMTP settings
-   Report generation and analytics
-   Active user monitoring

### 👨‍🏫 Instructor Panel

-   Dedicated instructor dashboard
-   Course creation and management
-   Course sections and lectures
-   Upload course materials
-   Student enrollment tracking
-   Q&A management
-   Revenue analytics

### 👤 User/Student Panel

-   User dashboard
-   Course browsing and enrollment
-   Wishlist functionality
-   Shopping cart system
-   Course reviews and ratings
-   Progress tracking
-   Certificate generation
-   Blog access

### 📚 Course Management

-   Comprehensive course creation tools
-   Course categories and subcategories
-   Course goals and objectives
-   Multi-section course structure
-   Video lectures support
-   Course prerequisites
-   Pricing and discount management
-   Course approval workflow

### 🛒 E-Commerce Features

-   Shopping cart functionality
-   Wishlist system
-   Coupon code application
-   Multiple payment gateways
-   Order management
-   Invoice generation (PDF)

### 📝 Content Management

-   Blog system with categories
-   Blog posts and comments
-   Rich text editor
-   Image upload and management

### 💬 Communication

-   Real-time chat system (Pusher integration)
-   Q&A section for courses
-   Review and rating system
-   Notifications

### 📊 Advanced Features

-   Excel import/export functionality
-   PDF generation for reports and certificates
-   Image optimization with Intervention Image
-   Real-time notifications
-   Search functionality
-   Responsive design

---

## 🛠 Tech Stack

### Backend

-   **Framework:** Laravel 12.0
-   **PHP Version:** 8.2+
-   **Authentication:** Laravel Breeze
-   **Permissions:** Spatie Laravel Permission

### Frontend

-   **CSS Framework:** Tailwind CSS
-   **Build Tool:** Vite
-   **JavaScript:** Vanilla JS & Alpine.js (via Breeze)

### Database

-   MySQL / MariaDB

### Key Packages

-   **PDF Generation:** barryvdh/laravel-dompdf
-   **Image Processing:** intervention/image
-   **Excel Import/Export:** maatwebsite/excel
-   **Shopping Cart:** anayarojo/shoppingcart
-   **Real-time:** pusher/pusher-php-server

---

## 💻 System Requirements

-   PHP >= 8.2
-   Composer
-   Node.js >= 18.x
-   NPM or Yarn
-   MySQL >= 8.0 or MariaDB >= 10.3
-   Apache or Nginx web server
-   XAMPP/WAMP/MAMP (for local development)

---

## 📥 Installation

### 1\. Clone the Repository

```bash
git clone https://github.com/AriyaArKa/Learning-MS.git
cd Learning-MS/lms
```

### 2\. Install PHP Dependencies

```bash
composer install
```

### 3\. Install Node Dependencies

```bash
npm install
```

### 4\. Environment Setup

```bash
# Copy the example environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 5\. Configure Environment Variables

Edit `.env` file and configure the following:

```env
APP_NAME="Learning Management System"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_database
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Pusher Configuration (for real-time features)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
```

### 6\. Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE lms_database;
exit;

# Run migrations
php artisan migrate

# (Optional) Seed sample data
php artisan db:seed
```

### 7\. Storage Link

```bash
php artisan storage:link
```

### 8\. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 9\. Start Development Server

```bash
# Start Laravel server
php artisan serve

# In another terminal, start Vite
npm run dev

# (Optional) Start queue worker
php artisan queue:work
```

Visit: `http://127.0.0.1:8000`

---

## ⚙ Configuration

### File Permissions

Ensure the following directories are writable:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Cache Optimization (Production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Clear Cache (Development)

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 🚀 Usage

### Default Credentials (if seeded)

**Admin:**

-   Email: admin@admin.com
-   Password: password

**Instructor:**

-   Email: instructor@instructor.com
-   Password: password

**User:**

-   Email: user@user.com
-   Password: password

### Creating Your First Course

1. Register as an Instructor or use admin to approve instructor applications
2. Login to the Instructor dashboard
3. Navigate to "Add Course"
4. Fill in course details (title, description, category, price)
5. Add course sections and lectures
6. Submit for admin approval

### User Registration Flow

1. Users can register via `/register`
2. Email verification (if enabled)
3. Role-based redirect after login:
    - Admin → `/admin/dashboard`
    - Instructor → `/instructor/dashboard`
    - User → `/user/dashboard`

---

## 📁 Project Structure

```
lms/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/              # Authentication controllers
│   │   │   ├── Backend/           # Admin & backend controllers
│   │   │   └── Frontend/          # Public-facing controllers
│   │   ├── Middleware/
│   │   │   └── Role.php          # Role-based middleware
│   │   └── Requests/
│   ├── Models/                    # Eloquent models
│   ├── Services/                  # Business logic services
│   └── Notifications/            # Custom notifications
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── public/
│   ├── backend/                  # Admin panel assets
│   ├── frontend/                 # Frontend assets
│   └── upload/                   # Uploaded files
├── resources/
│   ├── views/
│   │   ├── admin/               # Admin views
│   │   ├── instructor/          # Instructor views
│   │   ├── user/                # User views
│   │   ├── frontend/            # Public views
│   │   └── auth/                # Authentication views
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                  # Web routes
│   ├── api.php                  # API routes
│   └── auth.php                 # Authentication routes
└── composer.json                # PHP dependencies
```

---

## 👥 User Roles

### Admin

-   Full system access
-   User management
-   Course approval and management
-   System configuration
-   Reports and analytics
-   Blog management

### Instructor

-   Course creation and management
-   Student interaction
-   Q&A management
-   Revenue tracking
-   Profile management

### User (Student)

-   Course enrollment
-   Progress tracking
-   Reviews and ratings
-   Wishlist and cart
-   Certificate access
-   Profile management

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards

-   Follow PSR-12 coding standards
-   Write meaningful commit messages
-   Add comments for complex logic
-   Update documentation as needed

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

## 🐛 Bug Reports & Feature Requests

If you discover a bug or have a feature request, please create an issue on GitHub:

[Create Issue](https://github.com/AriyaArKa/Learning-MS/issues)

---

## 📞 Support

For support and questions:

-   **GitHub Issues:** [https://github.com/AriyaArKa/Learning-MS/issues](https://github.com/AriyaArKa/Learning-MS/issues)
-   **Email:** your-email@example.com

---

## 🙏 Acknowledgments

-   Laravel Framework
-   Tailwind CSS
-   All package contributors
-   The open-source community

---

## 📊 Project Status

🚧 **Status:** Active Development

---

Made with ❤️ by <a href="https://github.com/AriyaArKa">AriyaArKa</a>

<sub>Built with Laravel 12</sub>
