<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kost extends Model
{
    protected $fillable = [
        'owner_id',
        'nama_kost',
        'alamat',
        'deskripsi',
        'harga_mulai',
        'foto',
        'latitude',
        'longitude',
    ];

    public function kamars(): HasMany
    {
        return $this->hasMany(Kamar::class);
    }
}
