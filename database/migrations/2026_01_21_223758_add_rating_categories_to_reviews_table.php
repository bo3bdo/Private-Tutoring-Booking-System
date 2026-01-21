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
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedTinyInteger('teaching_style_rating')->nullable()->after('rating');
            $table->unsignedTinyInteger('communication_rating')->nullable()->after('teaching_style_rating');
            $table->unsignedTinyInteger('punctuality_rating')->nullable()->after('communication_rating');
            $table->json('images')->nullable()->after('punctuality_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['teaching_style_rating', 'communication_rating', 'punctuality_rating', 'images']);
        });
    }
};
