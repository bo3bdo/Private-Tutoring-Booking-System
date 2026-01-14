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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('reviewable'); // reviewable_id, reviewable_type (Booking, Course, TeacherProfile)
            $table->unsignedTinyInteger('rating')->default(1); // 1-5 stars
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'reviewable_type']);
            $table->unique(['user_id', 'reviewable_id', 'reviewable_type']); // One review per user per item
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
