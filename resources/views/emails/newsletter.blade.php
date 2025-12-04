<!-- resources/views/emails/newsletter.blade.php -->
@extends('emails.layout')

@section('title', 'Weekly Newsletter')

@section('subtitle', 'Latest posts and updates from ' . config('app.name'))

@section('content')
    <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 20px; color: #1f2937;">
        This Week's Top Posts
    </h2>

    <p style="margin-bottom: 30px; color: #4b5563;">
        Hi {{ $user->name }}, here are the most popular posts from this week:
    </p>

    @foreach($posts as $post)
        <div style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #1f2937;">
                <a href="{{ route('posts.show', $post->slug) }}"
                   style="color: #1f2937; text-decoration: none;">
                    {{ $post->title }}
                </a>
            </h3>

            <p style="margin-bottom: 10px; color: #4b5563; font-size: 14px;">
                {{ Str::limit($post->excerpt, 150) }}
            </p>

            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: #6b7280;">
                <span>
                    By {{ $post->user->name }} • {{ $post->reading_time }}
                </span>
                <a href="{{ route('posts.show', $post->slug) }}"
                   style="color: #3b82f6; text-decoration: none;">
                    Read More →
                </a>
            </div>
        </div>
    @endforeach

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('home') }}"
           class="btn"
           style="background-color: #3b82f6; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: 600;">
            View All Posts
        </a>
    </div>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 30px 0;"></div>

    <p style="font-size: 14px; color: #6b7280; text-align: center;">
        You're receiving this email because you subscribed to our newsletter.
        <br>
        <a href="{{ route('profile.edit') }}" style="color: #3b82f6; text-decoration: none;">
            Update your preferences
        </a> or
        <a href="{{ route('unsubscribe', ['email' => $user->email, 'token' => $unsubscribeToken]) }}"
           style="color: #3b82f6; text-decoration: none;">
            unsubscribe
        </a>
    </p>
@endsection
