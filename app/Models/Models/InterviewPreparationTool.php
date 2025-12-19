<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewPreparationTool extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'interview_preparation_tools';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'category',
        'difficulty_level',
        'is_featured',
        'is_published',
        'published_at',
        'view_count',
        'like_count',
        'tags',
        'related_positions',
        'question_examples',
        'answer_templates',
        'resources',
        'video_resources',
        'practice_scenarios',
        'estimated_preparation_time',
        'skills_addressed',
        'interview_types',
        'author',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'like_count' => 'integer',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'tags' => 'array',
        'related_positions' => 'array',
        'question_examples' => 'array',
        'answer_templates' => 'array',
        'resources' => 'array',
        'video_resources' => 'array',
        'practice_scenarios' => 'array',
        'skills_addressed' => 'array',
        'interview_types' => 'array',
    ];

    /**
     * Scope to get only published tools.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get only featured tools.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter by difficulty level.
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * Increment view count.
     */
    public function incrementViews()
    {
        $this->increment('view_count');
    }

    /**
     * Increment like count.
     */
    public function incrementLikes()
    {
        $this->increment('like_count');
    }

    /**
     * Get the estimated reading time in minutes.
     */
    public function getEstimatedReadingTimeInMinutesAttribute(): int
    {
        if (preg_match('/(\d+)\s*min/', $this->estimated_preparation_time, $matches)) {
            return (int)$matches[1];
        }

        return 15; // Default to 15 minutes if not specified
    }

    /**
     * Get related preparation tools.
     */
    public function getRelatedTools(int $limit = 5)
    {
        return self::where('category', $this->category)
                    ->where('id', '!=', $this->id)
                    ->published()
                    ->orderBy('view_count', 'desc')
                    ->limit($limit)
                    ->get();
    }
}
