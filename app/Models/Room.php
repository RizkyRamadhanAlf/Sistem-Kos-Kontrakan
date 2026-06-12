<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';

    public const STATUS_BOOKED = 'booked';

    public const STATUS_OCCUPIED = 'occupied';

    protected $fillable = [
        'property_id',
        'room_number',
        'room_type',
        'capacity',
        'price_per_month',
        'image_url',
        'description',
        'status',
    ];

    protected $casts = [
        'price_per_month' => 'decimal:2',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
