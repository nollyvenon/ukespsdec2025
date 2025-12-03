<?php

namespace Database\Seeders;

use App\Models\SubscriptionPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if packages already exist to avoid duplicates
        if (SubscriptionPackage::count() > 0) {
            return;
        }

        // Student packages
        SubscriptionPackage::create([
            'name' => 'Basic Student',
            'role_type' => 'student',
            'type' => 'one_time',
            'price' => 9.99,
            'features' => json_encode([
                'access_to_all_courses',
                'event_registration',
                'job_applications'
            ]),
            'description' => 'Access to all courses and events for 30 days',
            'duration_days' => 30,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        SubscriptionPackage::create([
            'name' => 'Premium Student',
            'role_type' => 'student',
            'type' => 'monthly',
            'price' => 19.99,
            'features' => json_encode([
                'access_to_all_courses',
                'event_registration',
                'job_applications',
                'premium_support',
                'analytics_dashboard'
            ]),
            'description' => 'Monthly access to all features',
            'duration_days' => 30,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Recruiter packages
        SubscriptionPackage::create([
            'name' => 'Basic Recruiter',
            'role_type' => 'recruiter',
            'type' => 'monthly',
            'price' => 49.99,
            'features' => json_encode([
                'post_jobs',
                'view_applications',
                'premium_support'
            ]),
            'description' => 'Post unlimited jobs with basic features',
            'duration_days' => 30,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        SubscriptionPackage::create([
            'name' => 'Premium Recruiter',
            'role_type' => 'recruiter',
            'type' => 'monthly',
            'price' => 99.99,
            'features' => json_encode([
                'post_jobs',
                'view_applications',
                'analytics_dashboard',
                'promoted_content',
                'premium_support'
            ]),
            'description' => 'Full recruiter suite with analytics',
            'duration_days' => 30,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // University packages
        SubscriptionPackage::create([
            'name' => 'Basic University',
            'role_type' => 'university_manager',
            'type' => 'yearly',
            'price' => 499.99,
            'features' => json_encode([
                'create_courses',
                'manage_students',
                'host_events',
                'analytics_dashboard'
            ]),
            'description' => 'Basic university package with course creation',
            'duration_days' => 365,
            'is_active' => true,
            'sort_order' => 5,
        ]);

        SubscriptionPackage::create([
            'name' => 'Premium University',
            'role_type' => 'university_manager',
            'type' => 'yearly',
            'price' => 999.99,
            'features' => json_encode([
                'create_courses',
                'manage_students',
                'host_events',
                'analytics_dashboard',
                'promoted_content',
                'premium_support'
            ]),
            'description' => 'Complete university platform with advanced features',
            'duration_days' => 365,
            'is_active' => true,
            'sort_order' => 6,
        ]);

        // Event manager packages
        SubscriptionPackage::create([
            'name' => 'Event Manager Basic',
            'role_type' => 'event_hoster',
            'type' => 'monthly',
            'price' => 29.99,
            'features' => json_encode([
                'host_events',
                'register_attendees',
                'analytics_dashboard'
            ]),
            'description' => 'Host events with basic analytics',
            'duration_days' => 30,
            'is_active' => true,
            'sort_order' => 7,
        ]);

        SubscriptionPackage::create([
            'name' => 'Event Manager Pro',
            'role_type' => 'event_hoster',
            'type' => 'monthly',
            'price' => 59.99,
            'features' => json_encode([
                'host_events',
                'register_attendees',
                'analytics_dashboard',
                'promoted_content',
                'premium_support'
            ]),
            'description' => 'Professional event hosting with premium features',
            'duration_days' => 30,
            'is_active' => true,
            'sort_order' => 8,
        ]);
    }
}
