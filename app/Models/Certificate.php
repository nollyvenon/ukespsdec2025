<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'certificate_id',
        'user_id',
        'assessment_id',
        'assessment_attempt_id',
        'title',
        'description',
        'score',
        'percentage',
        'grade',
        'issued_at',
        'valid_until',
        'is_verified',
        'verified_at',
        'verified_by',
        'certificate_path',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issued_at' => 'datetime',
        'valid_until' => 'datetime',
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the user who earned this certificate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assessment this certificate is for.
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the assessment attempt this certificate is for.
     */
    public function assessmentAttempt()
    {
        return $this->belongsTo(AssessmentAttempt::class, 'assessment_attempt_id');
    }

    /**
     * Get the verifier of this certificate.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
