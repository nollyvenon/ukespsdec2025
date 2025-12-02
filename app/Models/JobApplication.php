<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'applicant_id',
        'cover_letter',
        'resume_path',
        'application_status',
        'applied_position',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'application_date' => 'datetime',
    ];

    /**
     * Get the job listing for this application.
     */
    public function job()
    {
        return $this->belongsTo(JobListing::class);
    }

    /**
     * Get the applicant who submitted this application.
     */
    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }
}
