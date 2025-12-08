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
        Schema::table('affiliated_courses', function (Blueprint $table) {
            // Add is_featured column if it doesn't exist
            if (!Schema::hasColumn('affiliated_courses', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliated_courses', function (Blueprint $table) {
            if (Schema::hasColumn('affiliated_courses', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};
