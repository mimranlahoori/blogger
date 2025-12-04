<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService
{
    public function create(array $data, User $user): Post
    {
        $slug = $this->generateSlug($data['title']);

        $post = Post::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] ?? $this->generateExcerpt($data['content']),
            'status' => $data['status'] ?? 'draft',
            'featured' => $data['featured'] ?? false,
            'meta_title' => $data['meta_title'] ?? $data['title'],
            'meta_description' => $data['meta_description'] ?? $this->generateExcerpt($data['content'], 160),
            'meta_keywords' => $data['meta_keywords'],
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        // Handle image upload
        if (isset($data['image'])) {
            $this->uploadImage($post, $data['image']);
        }

        // Attach categories and tags
        if (isset($data['categories'])) {
            $post->categories()->attach($data['categories']);
        }

        if (isset($data['tags'])) {
            $post->tags()->attach($data['tags']);
        }

        // Log activity using our ActivityLog model
        ActivityLog::log('post_created', "Created post: {$post->title}", $user->id, [
            'post_id' => $post->id,
            'post_title' => $post->title
        ]);

        return $post;
    }

    public function update(Post $post, array $data): Post
    {
        // Update slug if title changed
        if (isset($data['title']) && $data['title'] !== $post->title) {
            $data['slug'] = $this->generateSlug($data['title'], $post->id);
        }

        // Update published_at if status changed to published
        if (isset($data['status']) && $data['status'] === 'published' && $post->status !== 'published') {
            $data['published_at'] = now();
        }

        $post->update($data);

        // Handle image upload
        if (isset($data['image'])) {
            $this->uploadImage($post, $data['image']);
        }

        // Sync categories and tags
        if (isset($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        // Log activity
        ActivityLog::log('post_updated', "Updated post: {$post->title}", Auth::id(), [
            'post_id' => $post->id,
            'post_title' => $post->title
        ]);

        return $post;
    }

    public function delete(Post $post): bool
    {
        // Delete image if exists
        if ($post->image) {
            Storage::delete('public/posts/' . $post->image);
        }

        // Log activity before deletion
        ActivityLog::log('post_deleted', "Deleted post: {$post->title}", Auth::id(), [
            'post_id' => $post->id,
            'post_title' => $post->title
        ]);

        return $post->delete();
    }

    public function incrementViews(Post $post): void
    {
        $post->increment('views');
    }

    public function toggleLike(Post $post, User $user): array
    {
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
            $message = 'Post unliked';
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
            $message = 'Post liked';

            // Log activity
            ActivityLog::log('post_liked', "Liked post: {$post->title}", $user->id, [
                'post_id' => $post->id,
                'post_title' => $post->title
            ]);
        }

        $likesCount = $post->likes()->count();

        return [
            'liked' => $liked,
            'likes_count' => $likesCount,
            'message' => $message
        ];
    }

    public function toggleBookmark(Post $post, User $user): array
    {
        $bookmark = $post->bookmarks()->where('user_id', $user->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            $bookmarked = false;
            $message = 'Post removed from bookmarks';

            // Log activity
            ActivityLog::log('post_unbookmarked', "Removed bookmark from post: {$post->title}", $user->id, [
                'post_id' => $post->id,
                'post_title' => $post->title
            ]);
        } else {
            $bookmark = $post->bookmarks()->create(['user_id' => $user->id]);
            $bookmarked = true;
            $message = 'Post added to bookmarks';

            // Log activity
            ActivityLog::log('post_bookmarked', "Bookmarked post: {$post->title}", $user->id, [
                'post_id' => $post->id,
                'post_title' => $post->title
            ]);
        }

        return [
            'bookmarked' => $bookmarked,
            'message' => $message
        ];
    }

    private function generateSlug(string $title, int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        $query = Post::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $query = Post::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            $counter++;
        }

        return $slug;
    }

    private function generateExcerpt(string $content, int $length = 150): string
    {
        $excerpt = strip_tags($content);
        $excerpt = preg_replace('/\s+/', ' ', $excerpt);

        if (strlen($excerpt) > $length) {
            $excerpt = substr($excerpt, 0, $length);
            $excerpt = substr($excerpt, 0, strrpos($excerpt, ' ')) . '...';
        }

        return $excerpt;
    }

    private function uploadImage(Post $post, $image): void
    {

        // Delete old image if exists
        if ($post->image) {
            Storage::disk('public')->delete('public/posts/' . $post->image);
        }

        $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('posts', $filename, 'public');

        $post->update(['image' => $filename]);
    }
}
