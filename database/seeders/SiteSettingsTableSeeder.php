<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsTableSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['site_name', 'Blogger Platform', 'string', true],
            ['site_description', 'A powerful blogging platform for sharing ideas', 'string', true],
            ['site_url', 'http://blogger.test', 'string', true],
            ['admin_email', 'admin@example.com', 'string', false],
            ['posts_per_page', '10', 'number', true],
            ['comments_enabled', 'true', 'boolean', true],
            ['registration_enabled', 'true', 'boolean', true],
            ['default_user_role', 'user', 'string', false],
            ['social_facebook', 'https://facebook.com', 'string', true],
            ['social_twitter', 'https://twitter.com', 'string', true],
            ['social_instagram', 'https://instagram.com', 'string', true],
            ['seo_meta_description', 'A modern blogging platform', 'string', true],
            ['seo_meta_keywords', 'blog, platform, writing, articles', 'string', true],
        ];

        foreach ($settings as $setting) {
            SiteSetting::setValue($setting[0], $setting[1], $setting[2], $setting[3]);
        }
    }
}
