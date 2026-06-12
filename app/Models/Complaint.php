<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_RESOLVED = 'resolved';

    public const STATUS_REJECTED = 'rejected';

    public const PRIORITY_LOW = 'low';

    public const PRIORITY_MEDIUM = 'medium';

    public const PRIORITY_HIGH = 'high';

    public const PRIORITY_URGENT = 'urgent';

    protected $fillable = [
        'ticket_number',
        'user_id',
        'owner_id',
        'booking_id',
        'category',
        'priority',
        'title',
        'description',
        'status',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Ditinjau',
            self::STATUS_IN_PROGRESS => 'Diproses',
            self::STATUS_RESOLVED => 'Selesai',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    public static function priorities(): array
    {
        return [
            self::PRIORITY_LOW => 'Rendah',
            self::PRIORITY_MEDIUM => 'Sedang',
            self::PRIORITY_HIGH => 'Tinggi',
            self::PRIORITY_URGENT => 'Mendesak',
        ];
    }

    public static function categories(): array
    {
        return [
            'facility_damage' => 'Kerusakan Fasilitas',
            'cleanliness' => 'Kebersihan',
            'neighbor_disturbance' => 'Gangguan Tetangga',
            'security' => 'Keamanan',
            'payment' => 'Pembayaran',
            'electricity' => 'Listrik',
            'water' => 'Air',
            'internet' => 'Internet/WiFi',
            'other' => 'Lainnya',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? ucfirst($this->status);
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::priorities()[$this->priority] ?? ucfirst($this->priority);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::categories()[$this->category] ?? ucfirst($this->category);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function images()
    {
        return $this->hasMany(ComplaintImage::class);
    }

    public function replies()
    {
        return $this->hasMany(ComplaintReply::class);
    }
}
