<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'content_type',
        'content_url',
        'youtube_url',
        'button_text',
        'button_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope to get active hero contents ordered by their order field
     */
    public function scopeActiveOrdered($query)
    {
        return $query->where('is_active', true)
                     ->orderBy('order', 'asc');
    }
}
