<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\DoctorProfile;
use App\Models\Specialty;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $query = DoctorProfile::with(['user', 'specialties', 'clinics']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $doctors = $query->latest()->paginate(15);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function schedule(Request $request, DoctorProfile $doctor): JsonResponse
    {
        $date = $request->input('date', date('Y-m-d'));

        $appointments = Appointment::with(['patient', 'clinic'])
            ->where('doctor_profile_id', $doctor->id)
            ->where('appointment_date', $date)
            ->orderBy('appointment_time')
            ->get();

        return response()->json([
            'success' => true,
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->user?->name,
                'phone' => $doctor->user?->phone,
                'avatar' => $doctor->user?->avatar ? asset('storage/' . $doctor->user->avatar) : null,
            ],
            'date' => $date,
            'appointments' => $appointments
        ]);
    }

    public function create(): View
    {
        $specialties = Specialty::where('status', 1)->get();
        $clinics = Clinic::where('status', 1)->get();
        return view('admin.doctors.create', compact('specialties', 'clinics'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|max:2048',
            'title_tk' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
            'education_tk' => 'nullable|string',
            'education_ru' => 'nullable|string',
            'education_en' => 'nullable|string',
            'bio_tk' => 'nullable|string',
            'bio_ru' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'specialties' => 'required|array',
            'clinics' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request) {
            $avatarPath = $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : null;

            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'avatar' => $avatarPath,
                'role' => 'doctor',
                'status' => $request->boolean('status', true) ? 1 : 0,
            ]);

            $profile = DoctorProfile::create([
                'user_id' => $user->id,
                'title_tk' => $request->title_tk,
                'title_ru' => $request->title_ru,
                'title_en' => $request->title_en,
                'experience_years' => $request->experience_years,
                'consultation_fee' => $request->consultation_fee,
                'education_tk' => $request->education_tk,
                'education_ru' => $request->education_ru,
                'education_en' => $request->education_en,
                'bio_tk' => $request->bio_tk,
                'bio_ru' => $request->bio_ru,
                'bio_en' => $request->bio_en,
                'is_verified' => $request->boolean('is_verified'),
                'status' => $request->boolean('status', true) ? 1 : 0,
            ]);

            $profile->specialties()->sync($request->specialties);
            if ($request->has('clinics')) {
                $profile->clinics()->sync($request->clinics);
            }
        });

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully.');
    }

    public function edit(DoctorProfile $doctor): View
    {
        $doctor->load(['user', 'specialties', 'clinics']);
        $specialties = Specialty::where('status', 1)->get();
        $clinics = Clinic::where('status', 1)->get();
        return view('admin.doctors.edit', compact('doctor', 'specialties', 'clinics'));
    }

    public function update(Request $request, DoctorProfile $doctor): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone,' . $doctor->user_id,
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image|max:2048',
            'title_tk' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
            'education_tk' => 'nullable|string',
            'education_ru' => 'nullable|string',
            'education_en' => 'nullable|string',
            'bio_tk' => 'nullable|string',
            'bio_ru' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'specialties' => 'required|array',
            'clinics' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $doctor) {
            $userData = ['name' => $request->name, 'phone' => $request->phone, 'status' => $request->boolean('status') ? 1 : 0];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('avatar')) {
                if ($doctor->user->avatar) {
                    Storage::disk('public')->delete($doctor->user->avatar);
                }
                $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $doctor->user->update($userData);

            $doctor->update([
                'title_tk' => $request->title_tk,
                'title_ru' => $request->title_ru,
                'title_en' => $request->title_en,
                'experience_years' => $request->experience_years,
                'consultation_fee' => $request->consultation_fee,
                'education_tk' => $request->education_tk,
                'education_ru' => $request->education_ru,
                'education_en' => $request->education_en,
                'bio_tk' => $request->bio_tk,
                'bio_ru' => $request->bio_ru,
                'bio_en' => $request->bio_en,
                'is_verified' => $request->boolean('is_verified'),
                'status' => $request->boolean('status') ? 1 : 0,
            ]);

            $doctor->specialties()->sync($request->specialties);
            $doctor->clinics()->sync($request->clinics ?? []);
        });

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroy(DoctorProfile $doctor): RedirectResponse
    {
        if ($doctor->user->avatar) {
            Storage::disk('public')->delete($doctor->user->avatar);
        }
        $doctor->user->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully.');
    }
}