<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdCampaign extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'budget',
        'start_date',
        'end_date',
        'target_audience',
        'status',
        'campaign_type',
        'campaign_goal',
        'impressions',
        'clicks',
        'conversions',
        'cost_per_click',
        'total_cost',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'cost_per_click' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'impressions' => 'integer',
        'clicks' => 'integer',
        'conversions' => 'integer',
        'status' => 'string',
    ];

    /**
     * Get all ads for the campaign.
     */
    public function ads()
    {
        return $this->hasMany(Ad::class, 'ad_campaign_id');
    }
}