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
        Schema::table('cv_uploads', function (Blueprint $table) {
            $table->json('parsed_skills')->nullable()->after('extracted_skills');
            $table->json('parsed_qualifications')->nullable()->after('parsed_skills');
            $table->json('parsed_experience')->nullable()->after('parsed_qualifications');
            $table->json('parsed_education')->nullable()->after('parsed_experience');
            $table->text('cv_summary')->nullable()->after('parsed_education');
            $table->boolean('auto_parsed')->default(false)->after('cv_summary');
            $table->timestamp('parsed_at')->nullable()->after('auto_parsed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cv_uploads', function (Blueprint $table) {
            //
        });
    }
};
