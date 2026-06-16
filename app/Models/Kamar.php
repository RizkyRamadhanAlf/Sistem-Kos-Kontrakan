<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kamar extends Model
{
    protected $fillable = [
        'kost_id',
        'nomor_kamar',
        'harga',
        'kapasitas',
        'status',
        'fasilitas',
        'foto',
    ];

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class);
    }
}
