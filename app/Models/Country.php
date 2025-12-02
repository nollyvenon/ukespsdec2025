<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'continent',
        'is_active',
    ];

    /**
     * Get the universities in this country.
     */
    public function universities()
    {
        return $this->hasMany(University::class);
    }

    /**
     * Get the affiliated courses through universities in this country.
     */
    public function affiliatedCourses()
    {
        return $this->hasManyThrough(
            AffiliatedCourse::class,
            University::class,
            'country_id', // Foreign key on universities table
            'university_id' // Foreign key on affiliated_courses table
        );
    }
}
