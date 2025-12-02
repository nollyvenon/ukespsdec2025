<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'url',
        'ad_type',
        'target_audience',
        'position',
        'status',
        'start_date',
        'end_date',
        'daily_budget',
        'impressions',
        'clicks',
        'image_url',
        'video_url',
        'ad_campaign_id',
        'priority',
        'placement_order',
        'slider_title',
        'slider_description',
        'is_slider_featured',
        'slider_order',
        'slider_metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'daily_budget' => 'decimal:2',
        'impressions' => 'integer',
        'clicks' => 'integer',
        'priority' => 'integer',
        'placement_order' => 'integer',
        'is_slider_featured' => 'boolean',
        'slider_order' => 'integer',
        'slider_metadata' => 'array',
    ];

    /**
     * Get the campaign that owns the ad.
     */
    public function campaign()
    {
        return $this->belongsTo(AdCampaign::class, 'ad_campaign_id');
    }

    /**
     * Scope to get active ads for a specific position.
     */
    public function scopeForPosition($query, $position)
    {
        return $query->where('position', $position)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('priority', 'desc')
            ->orderBy('placement_order', 'asc');
    }
}