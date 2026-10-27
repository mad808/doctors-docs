<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['user', 'doctorProfile.user', 'clinic']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $reviews = $query->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review): View
    {
        $review->load(['user', 'doctorProfile.user', 'clinic']);
        return view('admin.reviews.show', compact('review'));
    }

    public function updateStatus(Request $request, Review $review): RedirectResponse
    {
        $request->validate(['status' => 'required|in:0,1,2']);
        $review->update(['status' => $request->status]);
        
        // Optionally trigger recalculation of doctor/clinic average rating here if status became 1
        
        return back()->with('success', 'Review status updated.');
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        $request->validate(['admin_reply' => 'required|string']);
        $review->update(['admin_reply' => $request->admin_reply]);
        return back()->with('success', 'Reply added.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }
}