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
        Schema::create('live_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('provider'); // zoom, google_meet, microsoft_teams
            $table->string('meeting_id')->nullable();
            $table->string('meeting_url');
            $table->string('join_url')->nullable(); // For Zoom - alternative join URL
            $table->string('host_url')->nullable(); // For teachers/host
            $table->string('password')->nullable();
            $table->timestamp('scheduled_at');
            $table->integer('duration_minutes')->default(60);
            $table->json('metadata')->nullable(); // Provider-specific data
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->string('recording_url')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'provider']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_meetings');
    }
};
