<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'featured_image',
        'author_name',
        'author_title',
        'author_avatar',
        'is_published',
        'is_featured',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Scope untuk artikel yang sudah dipublish
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope untuk artikel featured
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Accessor untuk format tanggal
    public function getFormattedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d F Y') : $this->created_at->format('d F Y');
    }

    // Accessor untuk URL gambar
    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    public function getAuthorAvatarUrlAttribute()
    {
        return $this->author_avatar ? asset('storage/' . $this->author_avatar) : null;
    }
}
