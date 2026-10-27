<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $setting = Setting::firstOrCreate([], [
            'site_name_tk' => 'ProDoctors',
            'site_name_ru' => 'ProDoctors',
            'site_name_en' => 'ProDoctors',
        ]);

        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'site_name_tk' => 'required|string|max:255',
            'site_name_ru' => 'required|string|max:255',
            'site_name_en' => 'required|string|max:255',
            'phone_1' => 'nullable|string|max:50',
            'phone_2' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'instagram' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'imo' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|mimes:ico,png,jpg,svg|max:1024',
        ]);

        $setting = Setting::firstOrCreate([]);
        $data = $request->except(['logo', 'favicon']);

        if ($request->hasFile('logo')) {
            if ($setting->logo) Storage::disk('public')->delete($setting->logo);
            $data['logo'] = $request->file('logo')->store('site', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($setting->favicon) Storage::disk('public')->delete($setting->favicon);
            $data['favicon'] = $request->file('favicon')->store('site', 'public');
        }

        $setting->update($data);
        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}