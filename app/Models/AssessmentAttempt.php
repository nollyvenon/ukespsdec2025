<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentAttempt extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'assessment_id',
        'score',
        'max_score',
        'status',
        'attempts',
        'started_at',
        'completed_at',
        'graded_at',
        'graded_by',
        'grade_notes',
        'user_answers',
        'feedback',
        'is_passed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'graded_at' => 'datetime',
        'user_answers' => 'array',
        'feedback' => 'array',
    ];

    /**
     * Get the user who made this attempt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assessment for this attempt.
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the grader of this attempt.
     */
    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
