<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicController extends Controller
{
    public function index(Request $request): View
    {
        $query = Clinic::with(['location'])->where('status', 1);

        if ($request->filled('q')) {
            $query->where('name_tk', 'ilike', "%{$request->q}%")
                  ->orWhere('name_ru', 'ilike', "%{$request->q}%");
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $clinics = $query->latest()->paginate(12)->withQueryString();
        $locations = Location::whereNotNull('parent_id')->where('status', 1)->get();

        return view('front.clinics.index', compact('clinics', 'locations'));
    }

    public function show(Clinic $clinic): View
    {
        abort_if($clinic->status !== 1, 404);
        $clinic->load(['location', 'doctors.user', 'doctors.specialties']);
        return view('front.clinics.show', compact('clinic'));
    }
}