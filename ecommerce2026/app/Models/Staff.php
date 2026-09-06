<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staffs';

    protected $fillable = [
        'name',
        'role',
        'avatar',
        'phone',
        'cccd',
        'address',
        'status',
    ];
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        if (str_starts_with($this->avatar, 'uploads/')) {
            return asset($this->avatar);
        }

        if (str_starts_with($this->avatar, 'storage/')) {
            return asset($this->avatar);
        }

        return asset('storage/' . ltrim($this->avatar, '/'));
    }
}
