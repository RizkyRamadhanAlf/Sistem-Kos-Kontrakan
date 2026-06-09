<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'location',
        'city',
        'province',
        'latitude',
        'longitude',
        'image_url',
        'rating',
        'review_count',
        'facilities',
        'rules',
        'status',
    ];

    protected $casts = [
        'facilities' => 'array',
        'rules' => 'array',
        'rating' => 'decimal:2',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
