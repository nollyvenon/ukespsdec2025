<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLevel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
        'is_active',
    ];

    /**
     * Get the affiliated courses with this level.
     */
    public function affiliatedCourses()
    {
        return $this->hasMany(AffiliatedCourse::class);
    }
}
