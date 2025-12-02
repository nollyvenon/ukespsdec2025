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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, etc.
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default settings
        \DB::table('site_settings')->insert([
            [
                'key' => 'site_name',
                'value' => 'Your Site Name',
                'type' => 'text',
                'label' => 'Site Name',
                'description' => 'The name of your website',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'site_description',
                'value' => 'A description of your site',
                'type' => 'textarea',
                'label' => 'Site Description',
                'description' => 'A brief description of your website',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'image',
                'label' => 'Site Logo',
                'description' => 'Upload your site logo',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'about_us',
                'value' => 'Information about our company',
                'type' => 'textarea',
                'label' => 'About Us',
                'description' => 'Content for the About Us page',
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'services_info',
                'value' => 'Information about our services',
                'type' => 'textarea',
                'label' => 'Services Information',
                'description' => 'Content for the Services page',
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
