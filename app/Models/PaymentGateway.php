<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGateway extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'credentials',
        'description',
        'is_active',
        'supported_currencies',
        'supported_countries',
        'transaction_fee_percent',
        'transaction_fee_fixed',
        'additional_config',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'credentials' => 'array',
        'supported_currencies' => 'array',
        'supported_countries' => 'array',
        'additional_config' => 'array',
    ];

    /**
     * Get the transactions processed by this gateway.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'payment_gateway', 'slug');
    }
}
