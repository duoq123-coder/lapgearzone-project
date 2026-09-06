<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'image',
        'summary',
        'content',
        'author_name',
        'read_time',
        'views_count',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /**
     * Scope only published news
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Accessor for full image URL
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('images/news_ai_laptop.jpg');
        }

        if (Str::startsWith($this->image, ['http://', 'https://', 'images/'])) {
            return asset($this->image);
        }

        return asset('storage/' . $this->image);
    }

    /**
     * Format publication date
     */
    public function getFormattedDateAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;
        return $date ? $date->format('d/m/Y') : now()->format('d/m/Y');
    }
}
