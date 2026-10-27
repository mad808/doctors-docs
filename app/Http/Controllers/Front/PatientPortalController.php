<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PatientPortalController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        $appointments = Appointment::with(['doctorProfile.user', 'clinic'])->where('patient_id', $user->id)->latest('appointment_date')->take(5)->get();
        return view('front.patient_portal.dashboard', compact('user', 'appointments'));
    }

    public function storeAppointment(Request $request): RedirectResponse
    {
        $request->validate([
            'doctor_profile_id' => 'required|exists:doctor_profiles,id',
            'clinic_id' => 'nullable|exists:clinics,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'symptoms_note' => 'nullable|string|max:500',
        ]);

        Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_profile_id' => $request->doctor_profile_id,
            'clinic_id' => $request->clinic_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'symptoms_note' => $request->symptoms_note,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Bellik kabul edildi (Appointment requested).');
    }

    public function storeReview(Request $request): RedirectResponse
    {
        $request->validate([
            'doctor_profile_id' => 'required_without:clinic_id|nullable|exists:doctor_profiles,id',
            'clinic_id' => 'required_without:doctor_profile_id|nullable|exists:clinics,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'doctor_profile_id' => $request->doctor_profile_id,
            'clinic_id' => $request->clinic_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 0, // Pending approval
        ]);

        return back()->with('success', 'Teswiriňiz ugradyldy we barlanýar (Review submitted for approval).');
    }
}