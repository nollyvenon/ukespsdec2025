<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerAdvice extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'career_advices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'author',
        'topic_category',
        'career_level',
        'is_featured',
        'is_published',
        'published_at',
        'views',
        'tags',
        'action_steps',
        'external_sources',
        'estimated_reading_time',
        'seo_title',
        'seo_description',
        'meta_keywords',
        'related_articles',
        'author_social_links',
        'allows_comments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'allows_comments' => 'boolean',
        'tags' => 'array',
        'action_steps' => 'array',
        'external_sources' => 'array',
        'meta_keywords' => 'array',
        'related_articles' => 'array',
        'author_social_links' => 'array',
    ];

    /**
     * Scope to get only published articles
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get featured articles
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter by topic category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('topic_category', $category);
    }

    /**
     * Scope to filter by career level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('career_level', $level);
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
