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
            $table->foreignId('university_id')->nullable()->constrained('universities')->onDelete('set null');
            // Remove old columns since we're now using the university relationship
            if (Schema::hasColumn('affiliated_courses', 'university_name')) {
                $table->dropColumn('university_name');
            }
            if (Schema::hasColumn('affiliated_courses', 'university_logo')) {
                $table->dropColumn('university_logo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliated_courses', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropColumn('university_id');
            $table->string('university_name');
            $table->string('university_logo')->nullable();
        });
    }
};
