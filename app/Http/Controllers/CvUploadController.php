<?php

namespace App\Http\Controllers;

use App\Models\CvUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CvUploadController extends Controller
{
    /**
     * Display the CV upload form.
     */
    public function create()
    {
        // Check if user can upload CVs
        $this->authorize('uploadCv', CvUpload::class);

        return view('cv.upload');
    }

    /**
     * Store a newly uploaded CV.
     */
    public function store(Request $request)
    {
        $this->authorize('uploadCv', CvUpload::class);

        $validator = Validator::make($request->all(), [
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get the file
        $file = $request->file('cv_file');

        // Store the file
        $filename = 'cv_' . Auth::id() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('cvs', $filename, 'public');

        // Create the CV upload record
        $cvUpload = CvUpload::create([
            'user_id' => Auth::id(),
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'is_public' => $request->boolean('is_public', false),
            'is_featured' => $request->boolean('is_featured', false),
            'featured_until' => $request->boolean('is_featured', false) ? now()->addDays(30) : null, // 30 days for featured status
            'location' => $request->location,
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')->with('success', 'CV uploaded successfully!');
    }

    /**
     * Display the user's CV.
     */
    public function show(CvUpload $cvUpload)
    {
        $this->authorize('view', $cvUpload);

        return view('cv.show', compact('cvUpload'));
    }

    /**
     * Display form to edit CV details.
     */
    public function edit(CvUpload $cvUpload)
    {
        $this->authorize('update', $cvUpload);

        return view('cv.edit', compact('cvUpload'));
    }

    /**
     * Update CV details.
     */
    public function update(Request $request, CvUpload $cvUpload)
    {
        $this->authorize('update', $cvUpload);

        $request->validate([
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'location' => 'nullable|string|max:255',
            'desired_salary' => 'nullable|string|max:255',
        ]);

        $cvUpload->update([
            'is_public' => $request->boolean('is_public', false),
            'is_featured' => $request->boolean('is_featured', false),
            'featured_until' => $request->boolean('is_featured', false) ? now()->addDays(30) : null, // Renew featured status
            'location' => $request->location,
            'desired_salary' => $request->desired_salary,
        ]);

        return redirect()->route('cv.show', $cvUpload)->with('success', 'CV updated successfully!');
    }

    /**
     * Delete a CV.
     */
    public function destroy(CvUpload $cvUpload)
    {
        $this->authorize('delete', $cvUpload);

        // Delete the file from storage
        if (Storage::disk('public')->exists($cvUpload->file_path)) {
            Storage::disk('public')->delete($cvUpload->file_path);
        }

        $cvUpload->delete();

        return redirect()->route('dashboard')->with('success', 'CV deleted successfully.');
    }

    /**
     * Download a CV file.
     */
    public function download(CvUpload $cvUpload)
    {
        $this->authorize('download', $cvUpload);

        // Increment view count if accessed by a recruiter
        if (auth()->user()->hasRole('recruiter') || auth()->user()->hasRole('employer')) {
            $cvUpload->incrementViewCount();
        }

        $filePath = storage_path('app/public/' . $cvUpload->file_path);

        return response()->download($filePath, $cvUpload->original_name);
    }

    /**
     * Search for CVs (for recruiters only).
     */
    public function search(Request $request)
    {
        $this->authorize('searchCvs', CvUpload::class);

        $query = CvUpload::public();

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('extracted_skills', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('work_experience', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('education', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('summary', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->input('location') . '%');
        }

        if ($request->filled('skills')) {
            $skills = explode(',', $request->input('skills'));
            foreach ($skills as $skill) {
                $skill = trim($skill);
                if (!empty($skill)) {
                    $query->where(function($q) use ($skill) {
                        $q->whereJsonContains('extracted_skills', $skill)
                          ->orWhereRaw("JSON_EXTRACT(extracted_skills, '$[*]') LIKE ?", ["%{$skill}%"]);
                    });
                }
            }
        }

        $cvs = $query->with('user')
                     ->orderBy('is_featured', 'desc')
                     ->orderBy('updated_at', 'desc')
                     ->paginate(15);

        return view('cv.search', compact('cvs'));
    }

    /**
     * Display the user's CVs.
     */
    public function index()
    {
        // Get the user's own CVs
        $cvUploads = CvUpload::where('user_id', Auth::id())
                              ->with('user')
                              ->orderBy('created_at', 'desc')
                              ->paginate(15);

        return view('cv.index', compact('cvUploads'));
    }

    /**
     * Display search page for recruiters.
     */
    public function searchIndex()
    {
        $this->authorize('searchCvs', CvUpload::class);

        $cvs = CvUpload::public()
                       ->with('user')
                       ->orderBy('is_featured', 'desc')
                       ->orderBy('updated_at', 'desc')
                       ->paginate(15);

        return view('cv.search-index', compact('cvs'));
    }

    /**
     * Admin: Display all CVs.
     */
    public function adminIndex()
    {
        $this->authorize('viewAny', CvUpload::class);

        $cvs = CvUpload::with('user')
                       ->orderBy('updated_at', 'desc')
                       ->paginate(20);

        return view('admin.cv.index', compact('cvs'));
    }

    /**
     * Admin: Show a specific CV.
     */
    public function adminShow(CvUpload $cv)
    {
        $this->authorize('view', $cv);

        return view('admin.cv.show', compact('cv'));
    }

    /**
     * Admin: Delete a CV.
     */
    public function adminDestroy(CvUpload $cv)
    {
        $this->authorize('delete', $cv);

        $cv->delete();

        return redirect()->route('admin.cvs.index')->with('success', 'CV deleted successfully.');
    }

    /**
     * Admin: Toggle CV public status.
     */
    public function adminTogglePublic(CvUpload $cv)
    {
        $this->authorize('update', $cv);

        $cv->update(['is_public' => !$cv->is_public]);

        return redirect()->back()->with('success', 'CV public status updated.');
    }
}
