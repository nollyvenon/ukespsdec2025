<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'address',
        'date_of_birth',
        'skills',
        'interests',
        'education_level',
        'resume_path',
        // University-specific fields
        'university_name',
        'university_description',
        'university_logo',
        'university_website',
        'university_contact_person',
        'university_contact_email',
        'university_contact_phone',
        'university_address',
        // Company/Recruiter-specific fields
        'company_name',
        'company_description',
        'company_logo',
        'company_website',
        'company_industry',
        'company_size',
        'company_contact_person',
        'company_contact_email',
        'company_contact_phone',
        'company_address',
        // Organization/Event-specific fields
        'organization_name',
        'organization_description',
        'organization_logo',
        'organization_website',
        'organization_contact_person',
        'organization_contact_email',
        'organization_contact_phone',
        'organization_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'skills' => 'array',
        'interests' => 'array',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
