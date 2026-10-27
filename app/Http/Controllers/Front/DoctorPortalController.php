<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DoctorPortalController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        $profile = $user->doctorProfile;
        abort_unless($profile, 403);

        $appointments = Appointment::with(['patient', 'clinic'])
            ->where('doctor_profile_id', $profile->id)
            ->latest('appointment_date')
            ->paginate(15);

        return view('front.doctor_portal.dashboard', compact('profile', 'appointments'));
    }

    public function updateAppointment(Request $request, Appointment $appointment): RedirectResponse
    {
        $profile = Auth::user()->doctorProfile;
        abort_unless($profile && $appointment->doctor_profile_id === $profile->id, 403);

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'Appointment status updated.');
    }
}