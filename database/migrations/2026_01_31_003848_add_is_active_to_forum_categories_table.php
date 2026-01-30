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
        if (! Schema::hasColumn('forum_categories', 'is_active')) {
            Schema::table('forum_categories', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
                $table->index('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('forum_categories', 'is_active')) {
            Schema::table('forum_categories', function (Blueprint $table) {
                $table->dropIndex(['is_active']);
                $table->dropColumn('is_active');
            });
        }
    }
};
