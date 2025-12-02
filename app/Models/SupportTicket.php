<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'support_tickets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'support_category_id',
        'subject',
        'description',
        'priority',
        'status',
        'assigned_to',
        'resolved_at',
        'closed_at',
        'view_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'priority' => 'string',
            'status' => 'string',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
            'view_count' => 'integer',
        ];
    }

    /**
     * Get the user that owns the ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assigned user for the ticket.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the category of the ticket.
     */
    public function category()
    {
        return $this->belongsTo(SupportCategory::class, 'support_category_id');
    }

    /**
     * Get the replies for the ticket.
     */
    public function replies()
    {
        return $this->hasMany(SupportReply::class, 'support_ticket_id');
    }

    /**
     * Get the latest reply for the ticket.
     */
    public function latestReply()
    {
        return $this->hasOne(SupportReply::class, 'support_ticket_id')->latestOfMany();
    }
}
