<!-- resources/views/emails/welcome.blade.php -->
@extends('emails.layout')

@section('title', 'Welcome to ' . config('app.name'))

@section('subtitle', 'Get started with your new account')

@section('content')
    <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 20px; color: #1f2937;">
        Welcome, {{ $user->name }}!
    </h2>

    <p style="margin-bottom: 20px; color: #4b5563;">
        Thank you for joining {{ config('app.name') }}. We're excited to have you on board!
    </p>

    <p style="margin-bottom: 20px; color: #4b5563;">
        Your account has been successfully created. You can now start:
    </p>

    <ul style="margin-bottom: 30px; padding-left: 20px; color: #4b5563;">
        <li>Reading and commenting on posts</li>
        <li>Bookmarking your favorite content</li>
        <li>Following other users</li>
        <li>Creating your own posts (if you have permission)</li>
    </ul>

    @if(!$user->email_verified)
        <p style="margin-bottom: 30px; color: #4b5563;">
            Please verify your email address to unlock all features:
        </p>

        <a href="{{ route('verification.verify', ['id' => $user->id, 'hash' => sha1($user->email)]) }}"
           class="btn"
           style="background-color: #3b82f6; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: 600;">
            Verify Email Address
        </a>
    @else
        <a href="{{ route('home') }}"
           class="btn"
           style="background-color: #3b82f6; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: 600;">
            Start Exploring
        </a>
    @endif

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 30px 0;"></div>

    <p style="font-size: 14px; color: #6b7280;">
        If you have any questions, please don't hesitate to contact our support team at
        <a href="mailto:{{ SiteSetting::getValue('admin_email', 'support@example.com') }}"
           style="color: #3b82f6; text-decoration: none;">
            {{ SiteSetting::getValue('admin_email', 'support@example.com') }}
        </a>
    </p>
@endsection
