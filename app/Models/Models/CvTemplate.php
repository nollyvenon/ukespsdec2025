<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvTemplate extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cv_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'sections',
        'preview_image',
        'is_active',
        'is_featured',
        'usage_count',
        'customization_options',
        'color_schemes',
        'font_options',
        'layout_options',
        'features',
        'author',
        'rating',
        'rating_count',
        'instructions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sections' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'usage_count' => 'integer',
        'customization_options' => 'array',
        'color_schemes' => 'array',
        'font_options' => 'array',
        'layout_options' => 'array',
        'features' => 'array',
        'rating' => 'float',
        'rating_count' => 'integer',
        'instructions' => 'string',
    ];

    /**
     * Get CV uploads that use this template.
     */
    public function cvUploads()
    {
        return $this->hasMany(CvUpload::class, 'cv_template_id');
    }

    /**
     * Scope to get only active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only featured templates.
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
     * Increment usage count when template is used
     */
    public function incrementUsageCount(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get the preview image URL if available
     */
    public function getPreviewImageUrlAttribute(): ?string
    {
        if ($this->preview_image) {
            return asset('storage/' . $this->preview_image);
        }

        return null;
    }

    /**
     * Get available sections as a formatted array
     */
    public function getFormattedSectionsAttribute(): array
    {
        if (!$this->sections) {
            return [
                'header' => true,
                'summary' => true,
                'education' => true,
                'experience' => true,
                'skills' => true,
                'languages' => true,
                'certifications' => true,
                'interests' => false,
                'references' => false,
            ];
        }

        return $this->sections;
    }

    /**
     * Get available customization options
     */
    public function getCustomizationOptionsArrayAttribute(): array
    {
        if (!$this->customization_options) {
            return [
                'colors' => true,
                'fonts' => true,
                'layouts' => true,
                'sections_order' => true,
                'photo_upload' => true,
            ];
        }

        return $this->customization_options;
    }

    /**
     * Check if template has a specific feature
     */
    public function hasFeature(string $feature): bool
    {
        if (!$this->features) {
            return false;
        }

        return in_array($feature, $this->features);
    }
}
