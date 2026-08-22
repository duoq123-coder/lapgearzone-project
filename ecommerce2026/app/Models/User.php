<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'address', 'avatar', 'cccd', 'must_change_password', 'total_spent'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Quan hệ với Cart
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // Quan hệ với Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Quan hệ với Order được phân công giao hàng
    public function assignedOrders()
    {
        return $this->hasMany(Order::class, 'delivery_staff_id');
    }

    // Quan hệ với Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Quan hệ với Wishlist
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return null; // Return null if no avatar, UI will handle default icon
        }
        
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        if (str_starts_with($this->avatar, 'uploads/')) {
            return asset($this->avatar);
        }

        return asset('storage/' . $this->avatar);
    }
}
