<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::with('parent')->withCount('clinics')->latest()->paginate(15);
        return view('admin.locations.index', compact('locations'));
    }

    public function create(): View
    {
        $parents = Location::whereNull('parent_id')->get();
        return view('admin.locations.create', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name_tk' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
        ]);

        Location::create([
            'name_tk' => $request->name_tk,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'parent_id' => $request->parent_id,
            'status' => $request->boolean('status', true) ? 1 : 0,
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'Location created successfully.');
    }

    public function edit(Location $location): View
    {
        $parents = Location::whereNull('parent_id')->where('id', '!=', $location->id)->get();
        return view('admin.locations.edit', compact('location', 'parents'));
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $request->validate([
            'name_tk' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
        ]);

        $location->update([
            'name_tk' => $request->name_tk,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'parent_id' => $request->parent_id,
            'status' => $request->boolean('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();
        return redirect()->route('admin.locations.index')->with('success', 'Location deleted successfully.');
    }
}