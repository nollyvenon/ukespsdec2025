<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CvSearchLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cv_search_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'search_query',
        'search_filters',
        'cvs_accessed',
        'credits_used',
        'search_type',
        'searched_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'search_filters' => 'array',
        'cvs_accessed' => 'integer',
        'credits_used' => 'integer',
        'searched_at' => 'datetime',
    ];

    /**
     * Get the user that performed the search.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
