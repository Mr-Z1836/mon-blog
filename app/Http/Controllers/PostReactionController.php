<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostReactionRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class PostReactionController extends Controller
{
    public function store(StorePostReactionRequest $request, Post $post): RedirectResponse
    {
        $type = $request->validated('type');
        $reaction = $post->reactions()->where('user_id', $request->user()->id)->first();

        if ($reaction && $reaction->type === $type) {
            $reaction->delete();

            return back()->with('status', 'Réaction retirée.');
        }

        $post->reactions()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['type' => $type],
        );

        return back()->with('status', 'Réaction enregistrée.');
    }
}
