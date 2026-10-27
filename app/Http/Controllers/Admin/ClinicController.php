<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClinicController extends Controller
{
    public function index(Request $request): View
    {
        $clinics = Clinic::with('location')->withCount('doctors')->latest()->paginate(15);
        return view('admin.clinics.index', compact('clinics'));
    }

    public function create(): View
    {
        $locations = Location::whereNotNull('parent_id')->where('status', 1)->get(); // Cities
        return view('admin.clinics.create', compact('locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name_tk' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'logo' => 'nullable|image|max:2048',
            'image' => 'nullable|image|max:4096',
        ]);

        $data = $request->except(['logo', 'image']);
        if ($request->hasFile('logo')) $data['logo'] = $request->file('logo')->store('clinics', 'public');
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('clinics', 'public');
        $data['status'] = $request->boolean('status', true) ? 1 : 0;

        Clinic::create($data);
        return redirect()->route('admin.clinics.index')->with('success', 'Clinic created.');
    }

    public function edit(Clinic $clinic): View
    {
        $locations = Location::whereNotNull('parent_id')->where('status', 1)->get();
        return view('admin.clinics.edit', compact('clinic', 'locations'));
    }

    public function update(Request $request, Clinic $clinic): RedirectResponse
    {
        $request->validate(['name_tk' => 'required|string|max:255', 'location_id' => 'required|exists:locations,id']);
        
        $data = $request->except(['logo', 'image']);
        if ($request->hasFile('logo')) {
            if ($clinic->logo) Storage::disk('public')->delete($clinic->logo);
            $data['logo'] = $request->file('logo')->store('clinics', 'public');
        }
        if ($request->hasFile('image')) {
            if ($clinic->image) Storage::disk('public')->delete($clinic->image);
            $data['image'] = $request->file('image')->store('clinics', 'public');
        }
        $data['status'] = $request->boolean('status') ? 1 : 0;

        $clinic->update($data);
        return redirect()->route('admin.clinics.index')->with('success', 'Clinic updated.');
    }

    public function destroy(Clinic $clinic): RedirectResponse
    {
        if ($clinic->logo) Storage::disk('public')->delete($clinic->logo);
        if ($clinic->image) Storage::disk('public')->delete($clinic->image);
        $clinic->delete();
        return redirect()->route('admin.clinics.index')->with('success', 'Clinic deleted.');
    }
}