<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvUpload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
        'summary',
        'extracted_skills',
        'work_experience',
        'education',
        'location',
        'desired_salary',
        'is_public',
        'is_featured',
        'featured_until',
        'view_count',
        'status',
        'last_viewed_at',
        'overall_score',
        'match_scores',
        'relevance_score',
        'parsed_data',
        'contact_info',
        'languages',
        'cv_completeness_score',
        'last_parsed_at',
        'auto_parse_enabled',
        'last_position_applied',
        'application_count',
        'last_application_at',
        'parsed_skills',
        'parsed_qualifications',
        'parsed_experience',
        'parsed_education',
        'cv_summary',
        'auto_parsed',
        'parsed_at',
        'skill_match_scores',
        'qualification_match_scores',
        'experience_match_scores',
        'total_match_score',
        'recommended_jobs',
        'job_recommendation_scores',
        'cover_letter_path',
        'cover_letter_content',
        'cover_letter_keywords',
        'video_cv_path',
        'video_cv_thumbnail',
        'video_cv_duration',
        'video_cv_enabled',
        'cv_builder_data',
        'is_cv_builder_template_used',
        'cv_template_id',
        'cv_sections_order',
        'cv_customizations',
        'cv_education_history',
        'cv_work_history',
        'cv_skills',
        'cv_languages',
        'cv_certifications',
        'cv_interests',
        'cv_references',
        'cv_additional_sections',
        'is_cv_builder_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'extracted_skills' => 'array',
        'work_experience' => 'array',
        'education' => 'array',
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'featured_until' => 'datetime',
        'last_viewed_at' => 'datetime',
        'file_size' => 'integer',
        'view_count' => 'integer',
        'overall_score' => 'float',
        'match_scores' => 'array',
        'relevance_score' => 'integer',
        'parsed_data' => 'array',
        'contact_info' => 'array',
        'languages' => 'array',
        'cv_completeness_score' => 'float',
        'last_parsed_at' => 'datetime',
        'auto_parse_enabled' => 'boolean',
        'application_count' => 'integer',
        'last_application_at' => 'datetime',
        'parsed_skills' => 'array',
        'parsed_qualifications' => 'array',
        'parsed_experience' => 'array',
        'parsed_education' => 'array',
        'auto_parsed' => 'boolean',
        'parsed_at' => 'datetime',
        'skill_match_scores' => 'array',
        'qualification_match_scores' => 'array',
        'experience_match_scores' => 'array',
        'video_cv_duration' => 'integer',
        'video_cv_enabled' => 'boolean',
        'total_match_score' => 'float',
        'recommended_jobs' => 'array',
        'job_recommendation_scores' => 'array',
        'cover_letter_keywords' => 'array',
        'cv_builder_data' => 'array',
        'cv_sections_order' => 'array',
        'cv_customizations' => 'array',
        'cv_education_history' => 'array',
        'cv_work_history' => 'array',
        'cv_skills' => 'array',
        'cv_languages' => 'array',
        'cv_certifications' => 'array',
        'cv_interests' => 'array',
        'cv_references' => 'array',
        'cv_additional_sections' => 'array',
        'is_cv_builder_template_used' => 'boolean',
        'is_cv_builder_active' => 'boolean',
    ];

    /**
     * Get the user who uploaded the CV.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only public CVs that are active.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true)
                     ->where('status', 'active');
    }

    /**
     * Scope to get only featured CVs.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                     ->where('featured_until', '>', now())
                     ->where('status', 'active');
    }

    /**
     * Increase the view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
        $this->last_viewed_at = now();
        $this->save();
    }

    /**
     * Check if the CV is currently featured.
     */
    public function isCurrentlyFeatured(): bool
    {
        return $this->is_featured &&
               (!$this->featured_until || $this->featured_until->isFuture()) &&
               $this->status === 'active';
    }
}
