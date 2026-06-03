<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class RatingController extends Controller
{
    public function store(StoreRatingRequest $request, Post $post): RedirectResponse
    {
        $post->ratings()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['value' => $request->validated('value')],
        );

        return back()->with('status', 'Votre note a été enregistrée.');
    }
}
