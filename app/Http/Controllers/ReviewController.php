<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Post $post): RedirectResponse
    {
        $post->reviews()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'content' => $request->validated('review_content'),
                'is_approved' => false,
            ],
        );

        return back()->with('status', 'Votre avis a été envoyé. Il sera publié après modération.');
    }
}
