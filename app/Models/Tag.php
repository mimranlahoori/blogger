<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    // Relationships
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }

    // Scopes
    public function scopePopular($query, $limit = 10)
    {
        return $query->withCount('posts')
                     ->orderBy('posts_count', 'desc')
                     ->limit($limit);
    }
}
