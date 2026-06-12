<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'kos_name',
        'room_type',
        'location',
        'tenant_name',
        'booking_date',
        'duration_months',
        'price_per_month',
        'admin_fee',
        'total_amount',
        'status',
        'check_in_date',
        'check_out_date',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'duration_months' => 'integer',
        'price_per_month' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_COMPLETED = 'completed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PAID => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_EXPIRED => 'secondary',
            self::STATUS_COMPLETED => 'info',
            default => 'light',
        };
    }
}
