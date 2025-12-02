<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
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
        'type',
        'duration',
        'total_points',
        'requirements',
        'instructions',
        'tags',
        'status',
        'created_by',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'requirements' => 'array',
        'instructions' => 'array',
        'tags' => 'array',
    ];

    /**
     * Get the user who created this assessment.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the questions for this assessment.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the attempts for this assessment.
     */
    public function assessmentAttempts()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }

    /**
     * Get the certificates issued for this assessment.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
