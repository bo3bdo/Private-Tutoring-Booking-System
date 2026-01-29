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
        // AI recommendations table
        Schema::create('ai_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // teacher, course, time_slot, subject
            $table->json('recommendation_data'); // Stores the recommended items with scores
            $table->json('context')->nullable(); // Why these were recommended
            $table->string('algorithm_version')->default('v1.0');
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index('generated_at');
        });

        // User preferences for AI
        Schema::create('user_learning_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('preferred_subjects')->nullable(); // Array of subject IDs
            $table->json('preferred_times')->nullable(); // Array of preferred time slots
            $table->string('preferred_lesson_mode')->nullable(); // online, in_person, both
            $table->json('learning_goals')->nullable();
            $table->integer('budget_per_hour')->nullable();
            $table->json('learning_style')->nullable(); // visual, auditory, kinesthetic, etc.
            $table->timestamps();

            $table->unique('user_id');
        });

        // Student activity insights for AI
        Schema::create('student_learning_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->integer('total_bookings')->default(0);
            $table->integer('completed_lessons')->default(0);
            $table->integer('courses_completed')->default(0);
            $table->decimal('average_rating_given', 2, 1)->nullable();
            $table->decimal('engagement_score', 5, 2)->default(0); // 0-100
            $table->json('subject_interests')->nullable(); // Weighted subject preferences
            $table->json('teacher_preferences')->nullable(); // Preferred teacher characteristics
            $table->timestamp('last_analyzed_at')->nullable();
            $table->timestamps();

            $table->unique('student_id');
            $table->index('engagement_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_learning_insights');
        Schema::dropIfExists('user_learning_preferences');
        Schema::dropIfExists('ai_recommendations');
    }
};
