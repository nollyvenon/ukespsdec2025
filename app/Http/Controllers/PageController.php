<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $aboutContent = SiteSetting::get('about_us') ?: 'Default about us content.';
        
        return view('pages.about', compact('aboutContent'));
    }

    public function services()
    {
        $servicesContent = SiteSetting::get('services_info') ?: 'Default services information.';
        
        return view('pages.services', compact('servicesContent'));
    }
}