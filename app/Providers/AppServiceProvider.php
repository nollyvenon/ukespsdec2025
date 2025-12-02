<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Ad;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Share ads for all views
        view()->composer('*', function ($view) {
            $positions = ['top', 'below_header', 'above_footer', 'left_sidebar', 'right_sidebar', 'top_slider'];
            foreach ($positions as $position) {
                $ads = \App\Models\Ad::where('position', $position)
                    ->where('status', 'active')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->orderBy('created_at', 'desc')
                    ->get();
                $view->with("ads_{$position}", $ads);
            }
        });

        // Share site settings with all views
        view()->composer('*', function ($view) {
            $siteName = \App\Models\SiteSetting::get('site_name') ?: config('app.name', 'Ukesps');
            $siteDescription = \App\Models\SiteSetting::get('site_description') ?: 'Default site description';
            $siteLogo = \App\Models\SiteSetting::get('site_logo') ?: null;

            $view->with('siteName', $siteName);
            $view->with('siteDescription', $siteDescription);
            $view->with('siteLogoPath', $siteLogo ? asset('storage/' . $siteLogo) : null);
        });
    }
}
