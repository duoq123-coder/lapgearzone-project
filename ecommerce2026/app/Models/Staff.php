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
}
