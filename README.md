# Private Tutoring Booking System

A complete production-ready private tutoring booking system built with Laravel 12, featuring role-based access control, payment processing, automated notifications, reviews & ratings, messaging system, file resources, and support tickets.

## 🚀 Features

### Core Features
- **Role-Based Access Control**: Admin, Teacher, and Student roles with granular permissions using Spatie Laravel Permission
- **Booking Management**: Complete booking lifecycle with status tracking and audit trail
- **Time Slot Management**: Generate slots from availability, block/unblock slots
- **Payment Integration**: Stripe and BenefitPay payment gateways with webhook support
- **Notifications**: Email notifications for booking events and reminders
- **Concurrency Safety**: Database-level locking prevents double bookings
- **Audit Trail**: Complete history of all booking changes
- **Responsive UI**: TailwindCSS v3-based interface with grid and list views

### Reviews & Ratings System
- **Multi-Entity Reviews**: Rate teachers, bookings, and courses
- **Advanced Rating Categories**: Detailed ratings for teaching style, communication, and punctuality
- **Review Images**: Upload images with reviews for better feedback
- **Teacher Responses**: Teachers can respond to student reviews
- **Review Approval**: Admin approval workflow for reviews
- **Rating Aggregation**: Automatic calculation of average ratings and review counts
- **Review Badges**: Visual indicators for bookings that need review or have been reviewed
- **Review Notifications**: Automatic prompts for students to review completed bookings

### Messaging System
- **Real-time Chat**: Direct messaging between students and teachers
- **Booking-Linked Conversations**: Messages automatically linked to bookings
- **Unread Message Count**: Badge notifications in navigation bar
- **Message Attachments**: Support for file attachments in messages
- **Conversation Management**: Organized conversation threads

### File Resources System
- **Resource Management**: Teachers can upload files and resources
- **Booking-Specific Resources**: Attach resources to specific bookings
- **Student Access**: Students can view and download resources for their bookings
- **Resource Categories**: Organize resources by type and subject

### Support Tickets System
- **Ticket Creation**: Students can create support tickets
- **Ticket Management**: Admin can assign, update status, and reply to tickets
- **Ticket Tracking**: Unique ticket numbers and status tracking
- **Priority Levels**: Set priority levels for tickets
- **Ticket Replies**: Threaded conversation system for tickets
- **Unread Ticket Badges**: Notification badges for pending tickets

### Course Management
- **Recorded Courses**: Teachers can create and publish video courses
- **Course Lessons**: Multiple lessons per course with video support
- **Course Enrollment**: Students can enroll in courses
- **Course Purchase**: Paid courses with payment integration
- **Progress Tracking**: Track student progress through lessons
- **Course Reviews**: Students can review courses

### Notification System
- **Laravel Notify Integration**: Modern notification system with toast notifications
- **Real-time Broadcasting**: Laravel Broadcasting for instant notifications
- **Real-time Notifications**: Live updates for booking events and system notifications
- **Flash Messages**: Replaced old session-based messages with modern notifications
- **Notification Badges**: Navigation bar badges for unread messages, pending bookings, and support tickets
- **Review Reminders**: Automatic notifications for unreviewed completed bookings
- **Email Notifications**: Queued email notifications for all booking events

### Dashboard Features
- **Role-Specific Dashboards**: Customized dashboards for Admin, Teacher, and Student
- **Statistics**: Booking counts, revenue, upcoming lessons, etc.
- **Admin Dashboard Enhancements**: 
  - Monthly statistics with revenue charts
  - Today's bookings overview
  - Interactive charts using Chart.js
  - Revenue tracking and growth metrics
- **Quick Actions**: Easy access to common tasks
- **Recent Activity**: Display recent bookings, messages, and updates

### Calendar System
- **Advanced Calendar View**: Interactive calendar using FullCalendar.js
- **Multi-View Support**: Month, week, and day views
- **Role-Based Events**: Different calendar views for Admin, Teacher, and Student
- **Booking Display**: Visual representation of all bookings and time slots
- **Event Click Navigation**: Direct navigation to booking details
- **Dark Mode Support**: Full dark mode compatibility
- **RTL Support**: Right-to-left layout for Arabic language

### Discounts & Promotions System
- **Discount Codes**: Create and manage discount codes
- **Multiple Discount Types**: Percentage and fixed amount discounts
- **Usage Limits**: Set maximum uses per code and per user
- **Validity Periods**: Start and expiration dates for discounts
- **Minimum Amount Requirements**: Set minimum purchase amounts
- **Max Discount Caps**: Limit maximum discount amounts
- **Status Management**: Activate/deactivate discounts
- **Usage Tracking**: Track discount usage by users

### Analytics & Reports System
- **Revenue Reports**: Track revenue over time with interactive charts
- **Teacher Performance Reports**: Analyze teacher performance metrics
- **Student Progress Reports**: Track student progress and completion rates
- **Monthly Statistics**: Comprehensive monthly analytics
- **Interactive Charts**: Chart.js integration for data visualization
- **Date Range Filtering**: Filter reports by custom date ranges
- **Export Capabilities**: Export report data for further analysis

### Internationalization (i18n)
- **Multi-language Support**: Full support for English and Arabic
- **RTL Support**: Complete right-to-left layout for Arabic language
- **Language Switcher**: Easy language switching with session persistence
- **Localized Content**: All UI elements, notifications, and messages are translated
- **Font Support**: Cairo font for Arabic, Figtree for English
- **Dynamic Layout**: Automatic direction switching based on selected language

## 📋 Requirements

- **PHP**: 8.4.11+
- **Laravel**: 12.0
- **Database**: MySQL 8.0+ or MariaDB 10.3+
- **Composer**: Latest version
- **Node.js**: 18+ and NPM
- **Extensions**: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML

## 🔧 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/bo3bdo/Private-Tutoring-Booking-System.git
cd Private-Tutoring-Booking-System
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure Database

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tutoring_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Configure Application Settings

```env
APP_NAME="Tutoring System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Bahrain
APP_URL=http://localhost:8000
```

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Seed Database

```bash
php artisan db:seed
```

This creates:
- Roles and permissions (admin, teacher, student)
- Demo users:
  - **Admin**: admin@example.com / password
  - **Teacher**: teacher@example.com / password
  - **Student**: student@example.com / password
- Sample subjects and locations
- Demo data for testing

### 9. Build Frontend Assets

```bash
npm run build
```

Or for development:

```bash
npm run dev
```

### 10. Start Development Server

```bash
# Option 1: Use the dev script (starts server, queue, and vite)
composer run dev

# Option 2: Start services separately
php artisan serve
php artisan queue:work
npm run dev
```

## ⚙️ Configuration

### Payment Gateways

#### Stripe Configuration

Add to `.env`:

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

Configure webhook endpoint in Stripe Dashboard:
- URL: `https://yourdomain.com/webhooks/stripe`
- Events: `payment_intent.succeeded`, `payment_intent.payment_failed`

#### BenefitPay Configuration

Add to `.env`:

```env
BENEFITPAY_API_URL=https://api.benefitpay.com
BENEFITPAY_MERCHANT_ID=your_merchant_id
BENEFITPAY_API_KEY=your_api_key
BENEFITPAY_WEBHOOK_SECRET=your_webhook_secret
```

### Broadcasting Configuration

For real-time notifications, configure broadcasting in `.env`:

```env
BROADCAST_DRIVER=pusher
# or for local development
BROADCAST_DRIVER=log

# If using Pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1
```

Install broadcasting dependencies:

```bash
composer require pusher/pusher-php-server
# or for local development, use log driver
```

### Queue Configuration

For production, use Redis or database queues:

```env
QUEUE_CONNECTION=database
# or
QUEUE_CONNECTION=redis
```

Run queue worker:

```bash
php artisan queue:work
```

For production with supervisor:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-project/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path-to-project/storage/logs/worker.log
stopwaitsecs=3600
```

### Scheduler Configuration

Add to crontab (production):

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Or for development:

```bash
php artisan schedule:work
```

The scheduler runs:
- Hourly: Send booking reminders (24h and 1h before lessons)
- Daily: Cleanup old notifications and logs

### Mail Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tutoringsystem.com
MAIL_FROM_NAME="${APP_NAME}"
```

### File Storage

```env
FILESYSTEM_DISK=local
# or for production
FILESYSTEM_DISK=s3

# If using S3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### Language Configuration

The application supports multiple languages (English and Arabic). Configure in `.env`:

```env
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
```

Users can switch languages using the language switcher in the navigation bar. The selected language is stored in the session and persists across requests.

**Supported Languages:**
- English (en) - Default
- Arabic (ar) - Full RTL support

**Translation Files:**
- `lang/en/common.php` - English translations
- `lang/ar/common.php` - Arabic translations

All UI elements, notifications, and messages are fully translated.

## 🎯 Quick Start (Development)

### Quick Login

In local environment, the login page includes quick login buttons:
- **Login as Admin** - Direct access to admin dashboard
- **Login as Teacher** - Direct access to teacher dashboard
- **Login as Student** - Direct access to student dashboard

These are automatically hidden in production.

### Demo Accounts

All demo accounts use password: `password`

- **Admin**: admin@example.com
- **Teacher**: teacher@example.com
- **Student**: student@example.com

### Language Switching

Users can switch between English and Arabic using the language switcher in the navigation bar:
- Click the language dropdown in the top navigation
- Select your preferred language (English/Arabic)
- The interface will automatically switch with RTL support for Arabic
- Your language preference is saved in the session

## 🧪 Testing

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Suite

```bash
# Feature tests
php artisan test --testsuite=Feature

# Unit tests
php artisan test --testsuite=Unit

# Specific test file
php artisan test tests/Feature/BookingServiceTest.php

# Filter by test name
php artisan test --filter=BookingService
```

### Test Coverage

The project includes comprehensive tests with **165 tests** and **387 assertions**, all passing:

- ✅ Authentication & Authorization
- ✅ Booking Management (including concurrency safety)
- ✅ Student Booking Flow
- ✅ Teacher Booking Management
- ✅ Course Management
- ✅ Payment Processing & Webhooks
- ✅ Reviews & Ratings
- ✅ Messaging System
- ✅ Support Tickets
- ✅ Notifications
- ✅ Dashboard Statistics
- ✅ Admin CRUD Operations
- ✅ Slot Generation
- ✅ Teacher Availability Management

**Test Status**: All tests passing ✅

## 🎨 Code Quality

### Code Formatting

```bash
# Format all files
vendor/bin/pint

# Format only changed files
vendor/bin/pint --dirty
```

### Code Standards

- Follows PSR-12 coding standards
- Uses Laravel Pint for code formatting
- Type hints for all methods and properties
- PHPDoc blocks for complex methods
- Pest PHP for testing

## 📁 Project Structure

```
app/
├── Console/
│   └── Commands/          # Scheduled commands (reminders, etc.)
├── Enums/                 # PHP enums (BookingStatus, PaymentStatus, etc.)
├── Events/                # Event classes (BookingCreated, etc.)
├── Http/
│   ├── Controllers/       # Controllers organized by role
│   │   ├── Admin/        # Admin controllers
│   │   │   ├── DiscountController.php
│   │   │   └── ReportController.php
│   │   ├── Api/          # API controllers
│   │   │   └── CalendarApiController.php
│   │   ├── Student/      # Student controllers
│   │   ├── Teacher/      # Teacher controllers
│   │   │   └── ReviewController.php (with response functionality)
│   │   ├── CalendarController.php
│   │   └── Dev/          # Development-only controllers
│   ├── Middleware/        # Custom middleware
│   └── Requests/         # Form request validation
├── Models/                # Eloquent models
│   ├── Discount.php
│   └── DiscountUsage.php
├── Notifications/         # Email and broadcast notifications
│   └── RealTimeNotification.php
├── Policies/              # Authorization policies
└── Services/              # Business logic services
    ├── DiscountService.php
    ├── ReportService.php
    └── Gateways/         # Payment gateway implementations

database/
├── factories/             # Model factories
├── migrations/            # Database migrations
└── seeders/              # Database seeders

resources/
├── css/                  # TailwindCSS styles
├── js/                   # JavaScript/Alpine.js
└── views/                # Blade templates
    ├── layouts/          # Layout files
    ├── components/       # Reusable components
    ├── calendar/         # Calendar views
    ├── student/          # Student views
    ├── teacher/          # Teacher views
    └── admin/            # Admin views
        ├── discounts/   # Discount management views
        └── reports/     # Reports and analytics views

routes/
├── web.php               # Web routes
└── console.php           # Console routes

tests/
├── Feature/              # Feature tests
└── Unit/                 # Unit tests
```

## 🔐 Security Features

- **Authentication**: Laravel Breeze with email verification
- **Authorization**: Role-based access control with Spatie Permission
- **CSRF Protection**: All forms protected with CSRF tokens
- **SQL Injection Protection**: Eloquent ORM prevents SQL injection
- **XSS Protection**: Blade templating automatically escapes output
- **Password Hashing**: Bcrypt password hashing
- **Rate Limiting**: API and route rate limiting
- **Webhook Security**: Signature verification for payment webhooks
- **File Upload Security**: Validated file types and sizes

## 📊 Key Features Explained

### Booking Flow

1. **Student browses subjects** → Views available teachers
2. **Selects time slot** → Creates booking request
3. **Booking created** → Status: `AwaitingPayment` (if payment required)
4. **Payment processed** → Stripe/BenefitPay integration
5. **Webhook confirms payment** → Status: `Confirmed`
6. **Notifications sent** → Email to student and teacher
7. **Lesson completed** → Status: `Completed`
8. **Review prompt** → Student can review the lesson

### Concurrency Safety

Bookings use database transactions with row-level locking (`lockForUpdate()`) to prevent race conditions. The `time_slot_id` has a unique constraint to ensure no double bookings.

### Payment Flow

1. Student creates booking → Status: `AwaitingPayment`
2. Student pays via Stripe/BenefitPay
3. Webhook confirms payment → Status: `Confirmed`
4. Notifications sent to student and teacher
5. Meeting link revealed (if payment required)

### Slot Generation

Teachers set weekly availability (e.g., Monday 9am-5pm). The system generates individual time slots based on:
- Availability windows
- Date range
- Duration (e.g., 60 minutes)

### Notification System

- **Laravel Notify**: Modern toast notifications
- **Laravel Broadcasting**: Real-time notifications via WebSockets
- **Email Notifications**: Queued email notifications for:
  - Booking created
  - Payment confirmed
  - Booking cancelled
  - Booking rescheduled
  - Reminders (24h and 1h before)
- **Real-time Updates**: Live notification updates for:
  - New bookings
  - Booking status changes
  - Payment confirmations
  - System announcements
- **Navigation Badges**: Real-time badge counts for:
  - Unread messages
  - Pending bookings
  - Support tickets
  - Pending reviews (admin)

## 🌐 API Endpoints

### Webhooks

- `POST /webhooks/stripe` - Stripe payment webhook
- `POST /webhooks/benefitpay` - BenefitPay payment webhook

### Calendar API

- `GET /api/calendar/events` - Get calendar events (bookings and time slots)
  - Query parameters: `start` (ISO date), `end` (ISO date)
  - Returns: JSON array of calendar events formatted for FullCalendar

### Authentication

- `GET /login` - Login page
- `POST /login` - Authenticate user
- `POST /logout` - Logout user
- `GET /register` - Registration page
- `POST /register` - Register new user

### Admin Routes

- `GET /admin/discounts` - List all discounts
- `POST /admin/discounts` - Create new discount
- `GET /admin/discounts/create` - Discount creation form
- `GET /admin/discounts/{id}/edit` - Edit discount form
- `PUT /admin/discounts/{id}` - Update discount
- `DELETE /admin/discounts/{id}` - Delete discount
- `GET /admin/reports` - View analytics and reports

## 🐛 Troubleshooting

### Queue Not Processing

```bash
# Check queue connection
php artisan queue:work

# Clear failed jobs
php artisan queue:flush

# Retry failed jobs
php artisan queue:retry all
```

### Scheduler Not Running

```bash
# Test scheduler
php artisan schedule:list

# Run scheduler manually
php artisan schedule:run

# For development
php artisan schedule:work
```

### Payment Webhooks Not Working

1. Ensure webhook URLs are configured in payment provider dashboard
2. Check webhook secret in `.env`
3. Verify signature verification logic
4. Check logs: `storage/logs/laravel.log`

### Assets Not Loading

```bash
# Rebuild assets
npm run build

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database Issues

```bash
# Reset database
php artisan migrate:fresh --seed

# Check migrations
php artisan migrate:status
```

## 📦 Dependencies

### PHP Packages

- **laravel/framework**: ^12.0
- **spatie/laravel-permission**: ^6.24 - Role and permission management
- **stripe/stripe-php**: ^19.1 - Stripe payment integration
- **mckenziearts/laravel-notify**: ^3.1 - Toast notifications
- **laravel/breeze**: ^2.3 - Authentication scaffolding
- **pestphp/pest**: ^4.3 - Testing framework

### JavaScript Packages

- **tailwindcss**: ^3.1.0 - Utility-first CSS framework
- **alpinejs**: ^3.4.2 - Lightweight JavaScript framework
- **vite**: ^7.0.7 - Build tool
- **axios**: ^1.11.0 - HTTP client
- **fullcalendar**: ^6.1.15 - Calendar component (via CDN)
- **chart.js**: Chart library for analytics (via CDN)

## 📝 License

This project is proprietary software. All rights reserved.

## Support

For issues, questions, or feature requests, please contact the development team or create an issue in the repository.

## 📚 Additional Documentation

- **PROJECT_STATUS.md**: Current project status and completed features
- **TEST_COVERAGE_REPORT.md**: Test coverage report
- **AGENTS.md**: Development guidelines and best practices

## 🆕 Recent Updates

### Advanced Features (Latest)
- ✅ **Calendar System**: Interactive calendar with FullCalendar.js integration
  - Multi-view support (month, week, day)
  - Role-based event display
  - Dark mode and RTL support
  - Direct navigation to booking details

- ✅ **Real-time Notifications**: Laravel Broadcasting integration
  - Live notification updates
  - Broadcast events for booking changes
  - Real-time notification badges

- ✅ **Advanced Reviews**: Enhanced review system
  - Detailed rating categories (teaching style, communication, punctuality)
  - Image uploads in reviews
  - Teacher response functionality
  - Enhanced review display

- ✅ **Discounts System**: Complete discount management
  - Discount code creation and management
  - Multiple discount types (percentage/fixed)
  - Usage limits and tracking
  - Validity period management

- ✅ **Analytics & Reports**: Comprehensive reporting system
  - Revenue reports with charts
  - Teacher performance analytics
  - Student progress tracking
  - Monthly statistics dashboard

- ✅ **Admin Dashboard Improvements**: Enhanced admin interface
  - Monthly statistics cards
  - Interactive revenue charts
  - Today's bookings overview
  - Growth metrics and KPIs

### Internationalization (i18n)
- ✅ Full Arabic language support with RTL layout
- ✅ Language switcher with session persistence
- ✅ All UI elements translated (English & Arabic)
- ✅ Localized notifications and messages
- ✅ Dynamic font loading (Cairo for Arabic, Figtree for English)
- ✅ Translations for all new features (Calendar, Discounts, Reports, etc.)

### Testing
- ✅ Comprehensive test suite with 165 tests
- ✅ All tests passing (387 assertions)
- ✅ Full coverage of core features
- ✅ Payment webhook testing
- ✅ Booking concurrency testing
- ✅ Authorization policy testing

### Code Quality
- ✅ Laravel Pint code formatting
- ✅ PSR-12 compliance
- ✅ Type hints for all methods
- ✅ PHPDoc documentation

---

**Built with Laravel 12**
