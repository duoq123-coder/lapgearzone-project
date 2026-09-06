<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'message',
        'admin_reply',
        'admin_replied_at',
    ];

    protected $casts = [
        'admin_replied_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
