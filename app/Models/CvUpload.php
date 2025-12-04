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
