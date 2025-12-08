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
        Schema::table('ads', function (Blueprint $table) {
            // Add target_pages column if it doesn't exist
            if (!Schema::hasColumn('ads', 'target_pages')) {
                $table->json('target_pages')->nullable()->after('position');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            if (Schema::hasColumn('ads', 'target_pages')) {
                $table->dropColumn('target_pages');
            }
        });
    }
};
