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
        // Achievements table
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('color', 7)->default('#3B82F6');
            $table->integer('points')->default(0);
            $table->string('type'); // booking_count, course_completed, review_given, streak, etc.
            $table->integer('threshold')->default(1); // Number required to unlock
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->index('is_active');
        });

        // User achievements (unlocked)
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->integer('progress')->default(0);
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'achievement_id']);
            $table->index(['user_id', 'unlocked_at']);
        });

        // Points history
        Schema::create('points_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('points');
            $table->string('type'); // earned, spent, bonus
            $table->string('source'); // achievement, booking, referral, etc.
            $table->text('description');
            $table->nullableMorphs('pointable'); // Polymorphic relation
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });

        // Badges table
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon');
            $table->string('color', 7)->default('#F59E0B');
            $table->string('tier')->default('bronze'); // bronze, silver, gold, platinum
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // User badges
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
            $table->timestamp('awarded_at');
            $table->timestamps();

            $table->unique(['user_id', 'badge_id']);
        });

        // Leaderboard entries (monthly)
        Schema::create('leaderboard_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->tinyInteger('month');
            $table->integer('points')->default(0);
            $table->integer('bookings_count')->default(0);
            $table->integer('courses_completed')->default(0);
            $table->integer('rank')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'year', 'month']);
            $table->index(['year', 'month', 'points']);
        });

        // Add total_points to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_points')->default(0)->after('last_seen_at');
            $table->integer('current_streak')->default(0)->after('total_points');
            $table->date('last_activity_date')->nullable()->after('current_streak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['total_points', 'current_streak', 'last_activity_date']);
        });

        Schema::dropIfExists('leaderboard_entries');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('points_history');
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
    }
};
