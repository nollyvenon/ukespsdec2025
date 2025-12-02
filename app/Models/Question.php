<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assessment_id',
        'question_text',
        'question_description',
        'type',
        'options',
        'correct_answer',
        'points',
        'order',
        'is_required',
        'explanation',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'array',
        'explanation' => 'array',
    ];

    /**
     * Get the assessment this question belongs to.
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
