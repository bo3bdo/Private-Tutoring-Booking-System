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
        // Bookings table indexes
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('start_at', 'bookings_start_at_index');
            $table->index('status', 'bookings_status_index');
            $table->index(['status', 'start_at'], 'bookings_status_start_at_index');
            $table->index('created_at', 'bookings_created_at_index');
        });

        // Teacher time slots indexes
        Schema::table('teacher_time_slots', function (Blueprint $table) {
            $table->index('status', 'tts_status_index');
            $table->index(['teacher_id', 'status', 'start_at'], 'tts_teacher_status_start_index');
            $table->index('start_at', 'tts_start_at_index');
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('last_seen_at', 'users_last_seen_at_index');
            $table->index(['is_active', 'created_at'], 'users_active_created_index');
        });

        // Courses table indexes
        Schema::table('courses', function (Blueprint $table) {
            $table->index(['is_published', 'created_at'], 'courses_published_created_index');
            $table->index('slug', 'courses_slug_index');
        });

        // Reviews table indexes
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['reviewable_type', 'reviewable_id', 'created_at'], 'reviews_poly_created_index');
            $table->index('is_approved', 'reviews_approved_index');
        });

        // Messages table indexes
        Schema::table('messages', function (Blueprint $table) {
            $table->index(['conversation_id', 'created_at'], 'messages_conversation_created_index');
            $table->index('is_read', 'messages_read_index');
        });

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'payments_status_created_index');
            $table->index('transaction_id', 'payments_transaction_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_start_at_index');
            $table->dropIndex('bookings_status_index');
            $table->dropIndex('bookings_status_start_at_index');
            $table->dropIndex('bookings_created_at_index');
        });

        Schema::table('teacher_time_slots', function (Blueprint $table) {
            $table->dropIndex('tts_status_index');
            $table->dropIndex('tts_teacher_status_start_index');
            $table->dropIndex('tts_start_at_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_last_seen_at_index');
            $table->dropIndex('users_active_created_index');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_published_created_index');
            $table->dropIndex('courses_slug_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_poly_created_index');
            $table->dropIndex('reviews_approved_index');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_conversation_created_index');
            $table->dropIndex('messages_read_index');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_status_created_index');
            $table->dropIndex('payments_transaction_id_index');
        });
    }
};
