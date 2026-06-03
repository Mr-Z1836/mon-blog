<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePostReadHistoryRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostReadHistoryController extends Controller
{
    public function update(UpdatePostReadHistoryRequest $request, Post $post): JsonResponse
    {
        $request->user()->readHistories()->updateOrCreate(
            ['post_id' => $post->id],
            [
                'progress_percent' => $request->validated('progress_percent'),
                'last_read_at' => now(),
            ],
        );

        return response()->json(['ok' => true]);
    }
}
