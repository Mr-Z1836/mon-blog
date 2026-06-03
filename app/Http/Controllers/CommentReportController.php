<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentReportRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class CommentReportController extends Controller
{
    public function store(StoreCommentReportRequest $request, Comment $comment): RedirectResponse
    {
        $comment->reports()->firstOrCreate(
            ['user_id' => $request->user()->id],
            ['reason' => $request->validated('reason'), 'status' => 'pending'],
        );

        return back()->with('status', 'Signalement envoyé. Merci pour votre vigilance.');
    }
}
