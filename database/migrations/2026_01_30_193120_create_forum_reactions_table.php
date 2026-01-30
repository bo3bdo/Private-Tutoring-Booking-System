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
        if (Schema::hasTable('forum_reactions')) {
            return;
        }

        Schema::create('forum_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('reactable', 'reactable_type'); // reactable_id, reactable_type
            $table->enum('reaction_type', ['upvote', 'downvote', 'like']);
            $table->timestamps();

            $table->unique(['user_id', 'reactable_id', 'reactable_type', 'reaction_type']);
            $table->index(['reactable_id', 'reactable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_reactions');
    }
};
