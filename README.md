# Private Tutoring Booking System

A complete production-ready private tutoring booking system built with Laravel 12, featuring role-based access control, payment processing, and automated notifications.

## Features

- **Role-Based Access Control**: Admin, Teacher, and Student roles with granular permissions
- **Booking Management**: Complete booking lifecycle with status tracking and audit trail
- **Time Slot Management**: Generate slots from availability, block/unblock slots
- **Payment Integration**: Stripe and BenefitPay payment gateways with webhook support
- **Notifications**: Email notifications for booking events and reminders
- **Concurrency Safety**: Database-level locking prevents double bookings
- **Audit Trail**: Complete history of all booking changes
- **Responsive UI**: TailwindCSS-based interface with grid and list views

## Requirements

- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM
- Laravel 12

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd cc
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Update `.env` with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tutoring_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Configure timezone**
   ```env
   APP_TIMEZONE=Asia/Bahrain
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed database**
   ```bash
   php artisan db:seed
   ```

   This creates:
   - Roles and permissions
   - Demo users (admin@example.com, teacher@example.com, student@example.com)
   - Sample subjects and locations
   - Password for all demo users: `password`

8. **Build assets**
   ```bash
   npm run build
   ```

## Configuration

### Payment Gateways

#### Stripe
Add to `.env`:
```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

#### BenefitPay
Add to `.env`:
```env
BENEFITPAY_API_URL=https://api.benefitpay.com
BENEFITPAY_MERCHANT_ID=your_merchant_id
BENEFITPAY_API_KEY=your_api_key
BENEFITPAY_WEBHOOK_SECRET=your_webhook_secret
```

### Queue Configuration
```env
QUEUE_CONNECTION=database
```

Run queue worker:
```bash
php artisan queue:work
```

### Scheduler
Add to crontab or run:
```bash
php artisan schedule:work
```

The scheduler runs hourly to send booking reminders (24h and 1h before lessons).

## Quick Start (Development)

### Quick Login Buttons

In local environment, the login page includes quick login buttons:
- Login as Admin
- Login as Teacher  
- Login as Student

These are automatically hidden in production.

### Demo Accounts

- **Admin**: admin@example.com / password
- **Teacher**: teacher@example.com / password
- **Student**: student@example.com / password

## Development

### Running the Application

```bash
# Start server, queue worker, and Vite
composer run dev

# Or separately:
php artisan serve
php artisan queue:work
npm run dev
```

### Running Tests

```bash
php artisan test
```

### Code Formatting

```bash
vendor/bin/pint
```

## Project Structure

```
app/
├── Console/Commands/        # Scheduled commands
├── Enums/                  # PHP enums for statuses
├── Http/
│   ├── Controllers/        # Controllers organized by role
│   ├── Middleware/         # Custom middleware
│   └── Requests/           # Form request validation
├── Models/                 # Eloquent models
├── Notifications/          # Email notifications
├── Policies/               # Authorization policies
└── Services/               # Business logic services
    └── Gateways/           # Payment gateway implementations

database/
├── factories/              # Model factories
├── migrations/             # Database migrations
└── seeders/                # Database seeders

resources/
└── views/                  # Blade templates
    ├── layouts/            # Layout files
    ├── student/            # Student views
    ├── teacher/            # Teacher views
    └── slots/              # Slot display partials
```

## Key Features Explained

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

### Notifications

Notifications are queued and sent for:
- Booking created
- Payment confirmed
- Booking cancelled
- Booking rescheduled
- Reminders (24h and 1h before)

## API Endpoints

### Webhooks

- `POST /webhooks/stripe` - Stripe payment webhook
- `POST /webhooks/benefitpay` - BenefitPay payment webhook

## Security

- All routes protected by authentication
- Role-based authorization using policies
- CSRF protection on all forms
- SQL injection protection via Eloquent
- XSS protection via Blade escaping
- Payment webhook signature verification

## Troubleshooting

### Queue Not Processing
```bash
php artisan queue:work
```

### Scheduler Not Running
```bash
php artisan schedule:work
# Or add to crontab: * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Payment Webhooks Not Working
- Ensure webhook URLs are configured in payment provider dashboard
- Check webhook secret in `.env`
- Verify signature verification logic

## License

This project is proprietary software.

## Support

For issues and questions, please contact the development team.

---

**Note**: See `IMPLEMENTATION_GUIDE.md` for detailed implementation of controllers, views, and routes.
