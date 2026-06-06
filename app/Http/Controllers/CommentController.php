<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;
use App\Support\CommentMentions;
use App\Support\CommentNotifier;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $content = $request->validated('comment_content');
        $mentionedUsers = CommentMentions::resolveFromContent($content);
        $requiresModeration = (bool) config('blog.comments_require_moderation', false);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $request->validated('parent_id'),
            'mentioned_user_id' => $mentionedUsers->first()?->id,
            'content' => $content,
            'is_approved' => ! $requiresModeration,
        ]);

        if ($comment->is_approved) {
            CommentNotifier::send($comment, $mentionedUsers);
        }

        $status = $requiresModeration
            ? 'Commentaire envoyé. Il sera visible après modération.'
            : 'Commentaire publié.';

        return back()->with('status', $status);
    }
}
