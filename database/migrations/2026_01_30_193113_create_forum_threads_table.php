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
        if (Schema::hasTable('forum_threads')) {
            return;
        }

        Schema::create('forum_threads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('reply_count')->default(0);
            $table->unsignedBigInteger('best_answer_post_id')->nullable();
            $table->timestamp('last_post_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('forum_categories')->cascadeOnDelete();
            $table->foreign('best_answer_post_id')->references('id')->on('forum_posts')->nullOnDelete();

            $table->index(['category_id', 'is_pinned', 'last_post_at']);
            $table->index('user_id');
            $table->index('last_post_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_threads');
    }
};
