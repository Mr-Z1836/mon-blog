<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $post->comments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $request->validated('parent_id'),
            'mentioned_user_id' => $request->validated('mentioned_user_id'),
            'content' => $request->validated('comment_content'),
            'is_approved' => false,
        ]);

        return back()->with('status', 'Commentaire envoyé. Il sera visible après modération.');
    }
}
