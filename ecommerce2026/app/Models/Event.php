<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'headline',
        'description',
        'banner_image',
        'display_type',
        'banner_link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $baseSlug = Str::slug($event->name);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $event->slug = $slug;
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('name') && empty($event->slug)) {
                $baseSlug = Str::slug($event->name);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $event->slug = $slug;
            }
        });
    }

    /**
     * Scope for active events ordered by sort_order
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }

    /**
     * Relationship: Event has many Products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'event_product')
            ->withPivot('sort_order')
            ->orderBy('event_product.sort_order', 'asc');
    }

    /**
     * Accessor for full banner image URL
     */
    public function getBannerUrlAttribute()
    {
        if (empty($this->banner_image)) {
            return null;
        }
        if (filter_var($this->banner_image, FILTER_VALIDATE_URL)) {
            return $this->banner_image;
        }
        if (file_exists(public_path($this->banner_image))) {
            return asset($this->banner_image);
        }
        return asset('storage/' . $this->banner_image);
    }
}
