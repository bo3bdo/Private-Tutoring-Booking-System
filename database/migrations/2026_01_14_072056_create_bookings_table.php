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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('time_slot_id')->unique()->constrained('teacher_time_slots')->cascadeOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('status')->default('awaiting_payment'); // awaiting_payment, confirmed, cancelled, completed, no_show, rescheduled
            $table->string('lesson_mode'); // online, in_person
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('meeting_provider')->default('none'); // none, custom, zoom, google_meet
            $table->string('meeting_url')->nullable();
            $table->json('meeting_meta')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'start_at']);
            $table->index(['teacher_id', 'start_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
