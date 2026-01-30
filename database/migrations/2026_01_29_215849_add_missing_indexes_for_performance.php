<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add critical missing indexes for foreign keys and frequently queried columns

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('paid_at');
            $table->index(['student_id', 'status', 'paid_at'], 'payments_student_status_paid_at');
        });

        // Bookings table indexes
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('location_id');
            $table->index('subject_id');
            $table->index(['teacher_id', 'status', 'start_at'], 'bookings_teacher_status_start_at');
            $table->index(['student_id', 'status', 'start_at'], 'bookings_student_status_start_at');
        });

        // Teacher availabilities table indexes
        Schema::table('teacher_availabilities', function (Blueprint $table) {
            $table->index('teacher_id');
        });

        // Teacher time slots table indexes
        Schema::table('teacher_time_slots', function (Blueprint $table) {
            $table->index('subject_id');
            $table->index('created_by');
            $table->index(['teacher_id', 'start_at', 'end_at'], 'time_slots_teacher_times');
        });

        // Booking histories table indexes
        Schema::table('booking_histories', function (Blueprint $table) {
            $table->index('actor_id');
        });

        // Conversations table indexes
        Schema::table('conversations', function (Blueprint $table) {
            $table->index('booking_id');
        });

        // Support tickets table indexes
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->index('reviewed_by');
        });

        // Teacher requests table indexes
        Schema::table('teacher_requests', function (Blueprint $table) {
            $table->index('reviewed_by');
        });

        // Payment intents table indexes
        Schema::table('payment_intents', function (Blueprint $table) {
            $table->index('payment_id');
            $table->index('course_id');
            $table->index('student_id');
        });

        // Discount usages table indexes
        Schema::table('discount_usages', function (Blueprint $table) {
            $table->index('payment_id');
        });

        // Course enrollments table indexes
        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->index('enrolled_at');
        });

        // Course purchases table indexes
        Schema::table('course_purchases', function (Blueprint $table) {
            $table->index('purchased_at');
        });

        // Courses table indexes
        Schema::table('courses', function (Blueprint $table) {
            $table->index('published_at');
            $table->index(['teacher_id', 'is_published', 'published_at'], 'courses_teacher_published');
        });

        // Teacher profiles table indexes
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->index('is_active');
        });

        // Subjects table indexes
        Schema::table('subjects', function (Blueprint $table) {
            $table->index('is_active');
        });

        // Notification logs table indexes
        Schema::table('notification_logs', function (Blueprint $table) {
            $table->index(['user_id', 'status', 'created_at'], 'notification_logs_user_status_created');
        });

        // Reviews table indexes
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['reviewable_type', 'reviewable_id', 'is_approved', 'created_at'], 'reviews_polymorphic_approved');
        });

        // Lesson progress table indexes
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->index('lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop all indexes in reverse order

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex(['lesson_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_polymorphic_approved');
        });

        Schema::table('notification_logs', function (Blueprint $table) {
            $table->dropIndex('notification_logs_user_status_created');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['published_at']);
            $table->dropIndex('courses_teacher_published');
        });

        Schema::table('course_purchases', function (Blueprint $table) {
            $table->dropIndex(['purchased_at']);
        });

        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->dropIndex(['enrolled_at']);
        });

        Schema::table('discount_usages', function (Blueprint $table) {
            $table->dropIndex(['payment_id']);
        });

        Schema::table('payment_intents', function (Blueprint $table) {
            $table->dropIndex(['payment_id']);
            $table->dropIndex(['course_id']);
            $table->dropIndex(['student_id']);
        });

        Schema::table('teacher_requests', function (Blueprint $table) {
            $table->dropIndex(['reviewed_by']);
        });

        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropIndex(['reviewed_by']);
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndex(['booking_id']);
        });

        Schema::table('booking_histories', function (Blueprint $table) {
            $table->dropIndex(['actor_id']);
        });

        Schema::table('teacher_time_slots', function (Blueprint $table) {
            $table->dropIndex(['subject_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex('time_slots_teacher_times');
        });

        Schema::table('teacher_availabilities', function (Blueprint $table) {
            $table->dropIndex(['teacher_id']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['location_id']);
            $table->dropIndex(['subject_id']);
            $table->dropIndex('bookings_teacher_status_start_at');
            $table->dropIndex('bookings_student_status_start_at');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
            $table->dropIndex(['paid_at']);
            $table->dropIndex('payments_student_status_paid_at');
        });
    }
};
