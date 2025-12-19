<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSection;

class HomepageSectionController extends Controller
{
    /**
     * Display a listing of the homepage sections.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $sections = HomepageSection::orderBy('display_order')->get();

        return view('admin.homepage-sections.index', compact('sections'));
    }

    /**
     * Update the homepage sections.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $sectionData = $request->input('sections', []);

        foreach ($sectionData as $sectionId => $data) {
            $section = HomepageSection::findOrFail($sectionId);
            $section->update([
                'is_enabled' => isset($data['is_enabled']) ? true : false,
                'display_order' => $data['display_order'] ?? $section->display_order,
            ]);
        }

        return redirect()->route('admin.homepage-sections.index')
            ->with('success', 'Homepage sections updated successfully.');
    }
}
