<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
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
        'start_date',
        'end_date',
        'location',
        'max_participants',
        'registration_deadline',
        'event_image',
        'event_status',
        'created_by',
        'is_premium',
        'premium_fee',
        'premium_expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'is_premium' => 'boolean',
        'premium_fee' => 'decimal:2',
        'premium_expires_at' => 'datetime',
        'event_image' => 'string',
    ];

    /**
     * Get the user who created the event.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the registrations for this event.
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }

    /**
     * Scope a query to only include premium events.
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope a query to only include non-premium events.
     */
    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    /**
     * Check if the event is currently premium and valid.
     */
    public function isCurrentlyPremium(): bool
    {
        return $this->is_premium && (!$this->premium_expires_at || $this->premium_expires_at->isFuture());
    }
}
