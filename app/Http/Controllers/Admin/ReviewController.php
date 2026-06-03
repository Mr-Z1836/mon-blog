<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModerateReviewRequest;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.reviews.index', [
            'reviews' => Review::with(['post:id,title,slug', 'user:id,name'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function update(ModerateReviewRequest $request, Review $review): RedirectResponse
    {
        $review->update([
            'is_approved' => (bool) $request->validated('is_approved'),
        ]);

        return back()->with('status', 'Avis mis à jour.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('status', 'Avis supprimé.');
    }
}
