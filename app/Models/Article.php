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
        'featured_image',
        'author',
        'author_title',
        'author_avatar',
        'status',
        'is_featured',
        'category',
        'published_at',
        'tags',
        'views'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'tags' => 'array', // Cast JSON to array
        'views' => 'integer'
    ];

    // Scope untuk artikel yang sudah dipublish
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
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

    // Accessor untuk tags sebagai string (untuk form input)
    public function getTagsStringAttribute()
    {
        return $this->tags ? implode(', ', $this->tags) : '';
    }

    // Check if article is published
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published';
    }
}
