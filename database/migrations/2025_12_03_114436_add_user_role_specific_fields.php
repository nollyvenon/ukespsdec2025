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
        Schema::table('user_profiles', function (Blueprint $table) {
            // University-specific fields
            $table->string('university_name')->nullable()->after('resume_path');
            $table->text('university_description')->nullable()->after('university_name');
            $table->string('university_logo')->nullable()->after('university_description');
            $table->string('university_website')->nullable()->after('university_logo');
            $table->string('university_contact_person')->nullable()->after('university_website');
            $table->string('university_contact_email')->nullable()->after('university_contact_person');
            $table->string('university_contact_phone')->nullable()->after('university_contact_email');
            $table->string('university_address')->nullable()->after('university_contact_phone');

            // Recruiter/Company-specific fields
            $table->string('company_name')->nullable()->after('university_address');
            $table->text('company_description')->nullable()->after('company_name');
            $table->string('company_logo')->nullable()->after('company_description');
            $table->string('company_website')->nullable()->after('company_logo');
            $table->string('company_industry')->nullable()->after('company_website');
            $table->string('company_size')->nullable()->after('company_industry');
            $table->string('company_contact_person')->nullable()->after('company_size');
            $table->string('company_contact_email')->nullable()->after('company_contact_person');
            $table->string('company_contact_phone')->nullable()->after('company_contact_email');
            $table->string('company_address')->nullable()->after('company_contact_phone');

            // Event manager fields
            $table->string('organization_name')->nullable()->after('company_address');
            $table->text('organization_description')->nullable()->after('organization_name');
            $table->string('organization_logo')->nullable()->after('organization_description');
            $table->string('organization_website')->nullable()->after('organization_logo');
            $table->string('organization_contact_person')->nullable()->after('organization_website');
            $table->string('organization_contact_email')->nullable()->after('organization_contact_person');
            $table->string('organization_contact_phone')->nullable()->after('organization_contact_email');
            $table->string('organization_address')->nullable()->after('organization_contact_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'university_name', 'university_description', 'university_logo', 'university_website',
                'university_contact_person', 'university_contact_email', 'university_contact_phone', 'university_address',
                'company_name', 'company_description', 'company_logo', 'company_website',
                'company_industry', 'company_size', 'company_contact_person', 'company_contact_email',
                'company_contact_phone', 'company_address',
                'organization_name', 'organization_description', 'organization_logo', 'organization_website',
                'organization_contact_person', 'organization_contact_email', 'organization_contact_phone', 'organization_address'
            ]);
        });
    }
};
