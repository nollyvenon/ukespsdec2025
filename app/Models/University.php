<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'acronym',
        'logo',
        'location',
        'country_id',
        'description',
        'website',
        'email',
        'phone',
        'is_active',
        'rating',
        'programs',
        'admissions',
        'facilities',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'decimal:2',
        'programs' => 'array',
        'admissions' => 'array',
        'facilities' => 'array',
    ];

    /**
     * Get the country the university belongs to.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the affiliated courses for this university.
     */
    public function affiliatedCourses()
    {
        return $this->hasMany(AffiliatedCourse::class);
    }

    /**
     * Scope to filter universities by country.
     */
    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }
}
