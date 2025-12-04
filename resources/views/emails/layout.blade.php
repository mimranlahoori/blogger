<!-- resources/views/emails/layout.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #3b82f6;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .email-header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .email-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .email-content {
            padding: 40px 30px;
        }

        .email-footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
        }

        .email-footer a {
            color: #3b82f6;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }

        .btn:hover {
            background-color: #2563eb;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 30px 0;
        }

        .social-links {
            margin-top: 20px;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #6b7280;
            text-decoration: none;
        }

        .social-links a:hover {
            color: #3b82f6;
        }

        @media (max-width: 600px) {
            .email-content {
                padding: 20px 15px;
            }

            .email-header {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>{{ config('app.name') }}</h1>
            <p>{{ $subtitle ?? 'A powerful blogging platform' }}</p>
        </div>

        <!-- Content -->
        <div class="email-content">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="{{ route('home') }}">Visit Website</a> |
                <a href="{{ route('profile.edit') }}">Account Settings</a> |
                <a href="{{ route('unsubscribe') }}">Unsubscribe</a>
            </p>

            <div class="social-links">
                <a href="{{ SiteSetting::getValue('social_facebook', '#') }}">Facebook</a>
                <a href="{{ SiteSetting::getValue('social_twitter', '#') }}">Twitter</a>
                <a href="{{ SiteSetting::getValue('social_instagram', '#') }}">Instagram</a>
            </div>
        </div>
    </div>
</body>
</html>
