<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path
            ? Storage::disk('public')->url($this->profile_photo_path)
            : 'https://i.pravatar.cc/150?img=12';
    }

    protected static function booted(): void
    {
        static::saved(function (User $user): void {
            if (! Schema::hasTable('roles') || ! Schema::hasTable('model_has_roles')) {
                return;
            }

            $role = match ($user->role) {
                'admin', 'owner', 'tenant' => $user->role,
                'penyewa', 'member' => 'tenant',
                default => null,
            };

            if (! $role) {
                return;
            }

            Role::findOrCreate($role);

            if (($user->wasChanged('role') || ! $user->roles()->exists()) && ! $user->hasExactRoles($role)) {
                $user->syncRoles([$role]);
            }
        });
    }

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function ownedComplaints()
    {
        return $this->hasMany(Complaint::class, 'owner_id');
    }

    public function complaintReplies()
    {
        return $this->hasMany(ComplaintReply::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }
}
