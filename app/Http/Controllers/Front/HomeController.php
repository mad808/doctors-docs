<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Clinic;
use App\Models\DoctorProfile;
use App\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $banners = Banner::where('status', 1)->orderBy('order')->get();
        
        $top_doctors = DoctorProfile::with(['user', 'specialties'])
            ->where('status', 1)->where('is_verified', true)
            ->orderByDesc('rating')->take(8)->get();

        $specialties = Specialty::where('status', 1)->withCount('doctors')->orderByDesc('doctors_count')->take(12)->get();
        $clinics = Clinic::where('status', 1)->with('location')->take(6)->get();

        return view('front.home', compact('banners', 'top_doctors', 'specialties', 'clinics'));
    }

    public function setLocale(string $locale): RedirectResponse
    {
        if (in_array($locale, ['tk', 'ru', 'en'])) session()->put('locale', $locale);
        return back();
    }
}