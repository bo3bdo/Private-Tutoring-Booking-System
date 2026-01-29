# Private Tutoring Booking System

A production-ready Laravel 12 application for managing private tutoring: bookings, live and recorded courses, payments, messaging, reviews, and support—with role-based dashboards for Admin, Teacher, and Student.

---

## What It Does

- **Bookings** — Students book time slots with teachers; payments via Stripe or BenefitPay; status lifecycle and reminders.
- **Courses** — Teachers create recorded courses with lessons; students purchase, enroll, and track progress.
- **Messaging** — Direct chat between students and teachers, linked to bookings, with attachments.
- **Reviews & ratings** — Rate teachers, bookings, and courses; categories, images, and teacher replies.
- **Support** — Ticket system with priorities, replies, and admin assignment.
- **Admin** — Users, subjects, locations, discounts, analytics, reports, and approval workflows.
- **UX** — Responsive UI (Tailwind v3), dark mode, English/Arabic with RTL, calendar view, and real-time-style notifications.

---

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- MySQL 8.0+ or MariaDB 10.3+

---

## Installation

### 1. Clone and install dependencies

```bash
git clone https://github.com/bo3bdo/Private-Tutoring-Booking-System.git
cd Private-Tutoring-Booking-System

composer install
npm install
```

### 2. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set at least:

```env
APP_NAME="Tutoring System"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tutoring_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Database

```bash
php artisan migrate
php artisan db:seed
```

The seeder creates roles and demo users:

| Role    | Email               | Password  |
|---------|---------------------|-----------|
| Admin   | admin@example.com   | password  |
| Teacher | teacher@example.com | password |
| Student | student@example.com | password |

### 4. Frontend and run

```bash
npm run build
php artisan serve
```

Open **http://localhost:8000**. For development with hot reload:

```bash
npm run dev
php artisan queue:work
```

(Optional) Start scheduler for reminders and cleanup:

```bash
php artisan schedule:work
```

---

## Optional configuration

- **Payments** — Set `STRIPE_*` or `BENEFITPAY_*` in `.env` and configure webhooks to your app URL.
- **Mail** — Configure `MAIL_*` in `.env` for booking emails and reminders.
- **Queue** — Use `QUEUE_CONNECTION=database` (or `redis`) and run `php artisan queue:work` in production.
- **Broadcasting** — Set `BROADCAST_DRIVER` and Pusher (or similar) for real-time notifications.

---

## Testing

```bash
php artisan test
```

Run a subset:

```bash
php artisan test --compact tests/Feature/BookingServiceTest.php
php artisan test --compact --filter=BookingService
```

---

## Tech stack

- **Backend:** Laravel 12, PHP 8.2+, Spatie Permission, Stripe, Laravel Notify
- **Frontend:** Blade, Tailwind CSS v3, Alpine.js, Vite, FullCalendar, Chart.js
- **Testing:** Pest 4

---

## License

Proprietary. All rights reserved.
