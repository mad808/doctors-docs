<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpecialtyController extends Controller
{
    public function index(): View
    {
        $specialties = Specialty::withCount('doctors')->latest()->paginate(15);
        return view('admin.specialties.index', compact('specialties'));
    }

    public function create(): View
    {
        return view('admin.specialties.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name_tk' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
        ]);

        Specialty::create([
            'name_tk' => $request->name_tk,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'icon' => $request->icon,
            'status' => $request->boolean('status', true) ? 1 : 0,
        ]);

        return redirect()->route('admin.specialties.index')->with('success', 'Specialty created successfully.');
    }

    public function edit(Specialty $specialty): View
    {
        return view('admin.specialties.edit', compact('specialty'));
    }

    public function update(Request $request, Specialty $specialty): RedirectResponse
    {
        $request->validate([
            'name_tk' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
        ]);

        $specialty->update([
            'name_tk' => $request->name_tk,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'icon' => $request->icon,
            'status' => $request->boolean('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.specialties.index')->with('success', 'Specialty updated successfully.');
    }

    public function destroy(Specialty $specialty): RedirectResponse
    {
        $specialty->delete();
        return redirect()->route('admin.specialties.index')->with('success', 'Specialty deleted successfully.');
    }
}