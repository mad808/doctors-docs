<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\DoctorProfile;
use App\Models\Review;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'doctors_count' => DoctorProfile::count(),
            'patients_count' => User::where('role', 'patient')->count(),
            'clinics_count' => Clinic::count(),
            'appointments_count' => Appointment::count(),
            'pending_reviews' => Review::where('status', 0)->count(),
        ];

        $latest_appointments = Appointment::with(['patient', 'doctorProfile.user', 'clinic'])->latest()->take(5)->get();
        $latest_reviews = Review::with(['user', 'doctorProfile.user', 'clinic'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latest_appointments', 'latest_reviews'));
    }
}