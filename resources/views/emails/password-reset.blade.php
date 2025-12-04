<!-- resources/views/emails/password-reset.blade.php -->
@extends('emails.layout')

@section('title', 'Password Reset Request')

@section('subtitle', 'Reset your account password')

@section('content')
    <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 20px; color: #1f2937;">
        Hello, {{ $user->name }}!
    </h2>

    <p style="margin-bottom: 20px; color: #4b5563;">
        You are receiving this email because we received a password reset request for your account.
    </p>

    <p style="margin-bottom: 30px; color: #4b5563;">
        Click the button below to reset your password. This link will expire in 60 minutes.
    </p>

    <a href="{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}"
       class="btn"
       style="background-color: #3b82f6; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: 600;">
        Reset Password
    </a>

    <p style="margin-top: 30px; margin-bottom: 10px; color: #4b5563;">
        If you did not request a password reset, no further action is required.
    </p>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 30px 0;"></div>

    <p style="font-size: 14px; color: #6b7280;">
        If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
    </p>

    <p style="font-size: 14px; color: #6b7280; background-color: #f3f4f6; padding: 10px; border-radius: 4px; word-break: break-all;">
        {{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}
    </p>
@endsection
