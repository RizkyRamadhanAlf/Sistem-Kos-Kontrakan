<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'booking_id',
        'invoice_number',
        'order_id',
        'tenant_name',
        'gross_amount',
        'amount',
        'payment_method',
        'payment_status',
        'snap_token',
        'paid_at',
        'expired_at',
        'payment_date',
        'receipt_path',
        'status',
        'notes',
        'verified_at',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'verified_at' => 'datetime',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'gross_amount' => 'decimal:2',
        'amount' => 'decimal:2',
    ];
}
