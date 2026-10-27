<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\Location;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $query = DoctorProfile::with(['user', 'specialties', 'clinics.location'])->where('status', 1);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('specialty_id')) {
            $query->whereHas('specialties', function ($q) use ($request) {
                $q->where('specialties.id', $request->specialty_id);
            });
        }

        if ($request->filled('location_id')) {
            $query->whereHas('clinics', function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'rating' => $query->orderByDesc('rating'),
                'experience' => $query->orderByDesc('experience_years'),
                default => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $doctors = $query->paginate(12)->withQueryString();
        $specialties = Specialty::where('status', 1)->get();
        $locations = Location::whereNotNull('parent_id')->where('status', 1)->get();

        return view('front.doctors.index', compact('doctors', 'specialties', 'locations'));
    }

    public function show(DoctorProfile $doctor): View
    {
        abort_if($doctor->status !== 1, 404);
        $doctor->load(['user', 'specialties', 'clinics.location', 'reviews' => function($q) {
            $q->where('status', 1)->with('user')->latest();
        }]);

        return view('front.doctors.show', compact('doctor'));
    }

    public function getBookedSlots(Request $request, DoctorProfile $doctor): JsonResponse
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $bookedTimes = Appointment::where('doctor_profile_id', $doctor->id)
            ->where('appointment_date', $request->date)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->pluck('appointment_time')
            ->map(function ($time) {
                return substr($time, 0, 5);
            })
            ->toArray();

        return response()->json([
            'success' => true,
            'booked_slots' => $bookedTimes
        ]);
    }
}