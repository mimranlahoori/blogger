<?php
// Create SettingController
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->groupBy(function($item) {
            if (str_starts_with($item->setting_key, 'social_')) {
                return 'social';
            } elseif (str_starts_with($item->setting_key, 'admin_email')) {
                return 'email';
            } elseif (str_starts_with($item->setting_key, 'seo_')) {
                return 'seo';
            } else {
                return 'general';
            }
        });

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = $request->except('_token');

        foreach ($settings as $key => $value) {
            SiteSetting::setValue($key, $value);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
