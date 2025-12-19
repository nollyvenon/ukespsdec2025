<?php

namespace App\Http\Controllers;

use App\Models\CvTemplate;
use App\Models\CvUpload;
use App\Services\CvAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CvBuilderController extends Controller
{
    protected $cvAnalysisService;

    public function __construct(CvAnalysisService $cvAnalysisService)
    {
        $this->cvAnalysisService = $cvAnalysisService;
    }

    /**
     * Display the CV builder interface
     */
    public function index(Request $request)
    {
        $templates = CvTemplate::active()->get();
        $userCvs = Auth::user()->cvUploads()->get();

        return view('cv-builder.index', compact('templates', 'userCvs'));
    }

    /**
     * Show the CV creation form with a selected template
     */
    public function create($templateSlug = null)
    {
        $template = null;

        if ($templateSlug) {
            $template = CvTemplate::where('slug', $templateSlug)->where('is_active', true)->firstOrFail();
        }

        // Get default templates if no specific template was selected
        $availableTemplates = CvTemplate::active()->limit(6)->get();

        return view('cv-builder.create', compact('template', 'availableTemplates'));
    }

    /**
     * Show existing CV for editing in the builder
     */
    public function edit($id)
    {
        $cv = CvUpload::findOrFail($id);

        // Check if user has permission to edit this CV
        if ($cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to edit this CV');
        }

        // Load available templates
        $templates = CvTemplate::active()->get();

        return view('cv-builder.edit', compact('cv', 'templates'));
    }

    /**
     * Show CV preview
     */
    public function preview($id)
    {
        $cv = CvUpload::findOrFail($id);

        // Check if user has permission to view this CV
        if ($cv->user_id !== Auth::id() && !Auth::user()?->can('administrate')) {
            abort(403, 'Unauthorized to view this CV');
        }

        return view('cv-builder.preview', compact('cv'));
    }

    /**
     * Save CV data from the builder
     */
    public function save(Request $request, $id = null)
    {
        $request->validate([
            'cv_data' => 'required|array',
            'cv_data.personal_info' => 'required|array',
            'cv_data.education' => 'required|array',
            'cv_data.experience' => 'required|array',
            'cv_data.skills' => 'required|array',
            'template_id' => 'nullable|exists:cv_templates,id',
            'cv_title' => 'required|string|max:255',
            'cv_description' => 'nullable|string|max:500',
        ]);

        if ($id) {
            $cv = CvUpload::findOrFail($id);

            // Check if user has permission to edit this CV
            if ($cv->user_id !== Auth::id()) {
                abort(403, 'Unauthorized to edit this CV');
            }
        } else {
            $cv = new CvUpload();
            $cv->user_id = Auth::id();
        }

        // Update CV with builder data
        $cv->update([
            'cv_builder_data' => $request->cv_data,
            'cv_template_id' => $request->template_id,
            'is_cv_builder_template_used' => (bool) $request->template_id,
            'is_cv_builder_active' => true,
            'cv_sections_order' => $request->cv_data['sections_order'] ?? [],
            'cv_customizations' => $request->cv_data['customizations'] ?? [],
            'cv_education_history' => $request->cv_data['education'] ?? [],
            'cv_work_history' => $request->cv_data['experience'] ?? [],
            'cv_skills' => $request->cv_data['skills'] ?? [],
            'cv_languages' => $request->cv_data['languages'] ?? [],
            'cv_certifications' => $request->cv_data['certifications'] ?? [],
            'cv_interests' => $request->cv_data['interests'] ?? [],
            'cv_references' => $request->cv_data['references'] ?? [],
            'cv_additional_sections' => $request->cv_data['additional_sections'] ?? [],
            'title' => $request->cv_title, // Assuming we also have a title field
            'summary' => $request->cv_description, // Assuming we use description as summary
        ]);

        return response()->json([
            'success' => true,
            'message' => 'CV saved successfully',
            'data' => [
                'cv_id' => $cv->id,
                'cv_path' => $cv->file_path,
            ],
        ]);
    }

    /**
     * Get CV templates
     */
    public function getTemplates(Request $request)
    {
        $query = CvTemplate::active();

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $templates = $query->orderBy('is_featured', 'desc')
                          ->orderBy('usage_count', 'desc')
                          ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $templates,
        ]);
    }

    /**
     * Get a specific template
     */
    public function getTemplate($id)
    {
        $template = CvTemplate::findOrFail($id);

        if (!$template->is_active) {
            abort(404, 'Template not available');
        }

        return response()->json([
            'success' => true,
            'data' => $template,
        ]);
    }

    /**
     * Export CV to PDF
     */
    public function exportPdf($id)
    {
        $cv = CvUpload::findOrFail($id);

        // Check if user has permission to export this CV
        if ($cv->user_id !== Auth::id() && !Auth::user()?->can('administrate')) {
            abort(403, 'Unauthorized to export this CV');
        }

        // In a real application, you would generate a PDF here
        // For now, we'll just return the CV data
        if ($cv->cv_builder_data) {
            // Use the builder data to generate the CV
            $cvData = $cv->cv_builder_data;
        } else {
            // Use the existing CV file
            if (Storage::exists($cv->file_path)) {
                $fileContent = Storage::get($cv->file_path);
                return response($fileContent)
                    ->header('Content-Type', $cv->file_type)
                    ->header('Content-Disposition', 'attachment; filename="' . $cv->original_name . '"');
            } else {
                abort(404, 'CV file not found');
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'CV export functionality would generate PDF here',
            'data' => $cv,
        ]);
    }

    /**
     * Apply a template to a CV
     */
    public function applyTemplate(Request $request, $cvId, $templateId)
    {
        $cv = CvUpload::findOrFail($cvId);
        $template = CvTemplate::findOrFail($templateId);

        // Check if user has permission to edit this CV
        if ($cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to modify this CV');
        }

        if (!$template->is_active) {
            abort(404, 'Template not available');
        }

        // Update CV with template information
        $cv->update([
            'cv_template_id' => $templateId,
            'is_cv_builder_template_used' => true,
        ]);

        // Increment template usage count
        $template->incrementUsageCount();

        return response()->json([
            'success' => true,
            'message' => 'Template applied successfully',
            'data' => [
                'cv' => $cv,
                'template' => $template,
            ],
        ]);
    }

    /**
     * Get CV customization options
     */
    public function getCustomizationOptions($templateId = null)
    {
        if ($templateId) {
            $template = CvTemplate::findOrFail($templateId);
            $options = $template->customization_options_array;
        } else {
            // Default customization options
            $options = [
                'colors' => true,
                'fonts' => true,
                'layouts' => true,
                'sections_order' => true,
                'photo_upload' => true,
                'header_style' => true,
                'section_visibility' => true,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $options,
        ]);
    }

    /**
     * Customize CV section
     */
    public function customizeSection(Request $request, $cvId)
    {
        $cv = CvUpload::findOrFail($cvId);

        // Check if user has permission to customize this CV
        if ($cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to customize this CV');
        }

        $request->validate([
            'section' => 'required|string|in:header,summary,education,experience,skills,languages,certifications,interests,references,additional_sections',
            'customization' => 'required|array',
        ]);

        // Get existing customizations
        $customizations = $cv->cv_customizations ?: [];

        // Update the specific section customization
        $customizations[$request->section] = $request->customization;

        $cv->update([
            'cv_customizations' => $customizations,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section customized successfully',
            'data' => [
                'section' => $request->section,
                'customization' => $request->customization,
            ],
        ]);
    }

    /**
     * Update section order
     */
    public function updateSectionOrder(Request $request, $cvId)
    {
        $cv = CvUpload::findOrFail($cvId);

        // Verify user ownership
        if ($cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to modify this CV');
        }

        $request->validate([
            'section_order' => 'required|array',
            'section_order.*' => 'string',
        ]);

        $cv->update([
            'cv_sections_order' => $request->section_order,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section order updated successfully',
            'data' => [
                'new_order' => $request->section_order,
            ],
        ]);
    }

    /**
     * Get all CV data for builder
     */
    public function getCvData($id)
    {
        $cv = CvUpload::findOrFail($id);

        // Check if user has permission to view this CV
        if ($cv->user_id !== Auth::id() && !Auth::user()?->can('administrate')) {
            abort(403, 'Unauthorized to view this CV');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'cv' => $cv,
                'builder_data' => $cv->cv_builder_data,
                'template' => $cv->template,
                'sections_order' => $cv->cv_sections_order,
                'customizations' => $cv->cv_customizations,
                'education_history' => $cv->cv_education_history,
                'work_history' => $cv->cv_work_history,
                'skills' => $cv->cv_skills,
                'languages' => $cv->cv_languages,
                'certifications' => $cv->cv_certifications,
                'interests' => $cv->cv_interests,
                'references' => $cv->cv_references,
                'additional_sections' => $cv->cv_additional_sections,
            ],
        ]);
    }
}
