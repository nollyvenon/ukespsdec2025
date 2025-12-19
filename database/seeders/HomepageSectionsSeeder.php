<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSection;

class HomepageSectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'section_name' => 'Hero Banner',
                'section_key' => 'hero_banner',
                'is_enabled' => true,
                'display_order' => 1,
            ],
            [
                'section_name' => 'Featured Events',
                'section_key' => 'featured_events',
                'is_enabled' => true,
                'display_order' => 2,
            ],
            [
                'section_name' => 'Featured Jobs',
                'section_key' => 'featured_jobs',
                'is_enabled' => true,
                'display_order' => 3,
            ],
            [
                'section_name' => 'Featured Posts',
                'section_key' => 'featured_posts',
                'is_enabled' => true,
                'display_order' => 4,
            ],
            [
                'section_name' => 'CV Search',
                'section_key' => 'cv_search',
                'is_enabled' => true,
                'display_order' => 5,
            ],
            [
                'section_name' => 'Top Jobs',
                'section_key' => 'top_jobs',
                'is_enabled' => true,
                'display_order' => 6,
            ],
            [
                'section_name' => 'Top Careers',
                'section_key' => 'top_careers',
                'is_enabled' => true,
                'display_order' => 7,
            ],
            [
                'section_name' => 'Features',
                'section_key' => 'features',
                'is_enabled' => true,
                'display_order' => 8,
            ],
            [
                'section_name' => 'Services',
                'section_key' => 'services',
                'is_enabled' => true,
                'display_order' => 9,
            ],
            [
                'section_name' => 'Different Sectors',
                'section_key' => 'different_sectors',
                'is_enabled' => true,
                'display_order' => 10,
            ],
            [
                'section_name' => 'Stats',
                'section_key' => 'stats',
                'is_enabled' => true,
                'display_order' => 11,
            ],
            [
                'section_name' => 'Testimonials',
                'section_key' => 'testimonials',
                'is_enabled' => true,
                'display_order' => 12,
            ],
            [
                'section_name' => 'Call to Action',
                'section_key' => 'call_to_action',
                'is_enabled' => true,
                'display_order' => 13,
            ],
        ];

        foreach ($sections as $section) {
            \App\Models\HomepageSection::updateOrCreate(
                ['section_key' => $section['section_key']],
                $section
            );
        }
    }
}
