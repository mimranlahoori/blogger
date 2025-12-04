<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'slug', 'content', 'excerpt', 'image',
        'status', 'featured', 'views', 'likes_count', 'comments_count',
        'meta_title', 'meta_description', 'meta_keywords', 'published_at'
    ];

    protected $casts = [
        'featured' => 'integer',
        'published_at' => 'datetime',
        'views' => 'integer',
        'likes_count' => 'integer',
        'comments_count' => 'integer'
    ];

    protected $dates = ['published_at'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_categories');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200);
        return $minutes . ' min read';
    }

    public function getFeaturedImageAttribute()
    {
        if ($this->image) {
            return asset('storage/posts/' . $this->image);
        }
        return asset('images/default-post.jpg');
    }
}
