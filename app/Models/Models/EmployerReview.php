<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerReview extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employer_reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'company_name',
        'company_location',
        'job_title',
        'review_headline',
        'review_text',
        'overall_rating',
        'work_life_balance_rating',
        'salary_benefits_rating',
        'job_security_rating',
        'management_rating',
        'culture_rating',
        'pros_cons_available',
        'pros',
        'cons',
        'is_anonymous',
        'is_current_employee',
        'employment_start_date',
        'employment_end_date',
        'is_verified',
        'is_approved',
        'review_date',
        'upvotes',
        'downvotes',
        'reviewer_display_name',
        'review_tags',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'overall_rating' => 'decimal:2',
        'work_life_balance_rating' => 'decimal:2',
        'salary_benefits_rating' => 'decimal:2',
        'job_security_rating' => 'decimal:2',
        'management_rating' => 'decimal:2',
        'culture_rating' => 'decimal:2',
        'pros_cons_available' => 'boolean',
        'is_anonymous' => 'boolean',
        'is_current_employee' => 'boolean',
        'employment_start_date' => 'date',
        'employment_end_date' => 'date',
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'review_date' => 'date',
        'upvotes' => 'integer',
        'downvotes' => 'integer',
        'review_tags' => 'array',
    ];

    /**
     * Get the user who wrote the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company associated with the review.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Scope to get only approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get only verified reviews.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope to filter by company name.
     */
    public function scopeByCompany($query, $companyName)
    {
        return $query->where('company_name', 'LIKE', "%{$companyName}%");
    }

    /**
     * Scope to filter by rating.
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('overall_rating', '>=', $rating);
    }

    /**
     * Calculate the helpfulness score based on votes
     */
    public function getHelpfulnessAttribute(): float
    {
        $totalVotes = $this->upvotes + $this->downvotes;
        return $totalVotes > 0 ? ($this->upvotes / $totalVotes) * 100 : 50;
    }

    /**
     * Calculate the sentiment score from the review text
     */
    public function getSentimentAttribute(): float
    {
        // In a real implementation, this would use NLP to calculate sentiment
        // For now, we'll return a simple calculation based on ratings
        $positiveRatings = 0;
        $totalRatings = 0;

        $ratings = [
            $this->overall_rating,
            $this->work_life_balance_rating,
            $this->salary_benefits_rating,
            $this->job_security_rating,
            $this->management_rating,
            $this->culture_rating
        ];

        foreach ($ratings as $rating) {
            if ($rating !== null) {
                $positiveRatings += $rating;
                $totalRatings++;
            }
        }

        return $totalRatings > 0 ? ($positiveRatings / $totalRatings / 5) * 100 : 50; // Convert to percentage out of 100
    }
}
