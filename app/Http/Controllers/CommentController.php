<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
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

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $request->validated('parent_id'),
            'mentioned_user_id' => $mentionedUsers->first()?->id,
            'contenu' => $content,
            'est_approuve' => true,
        ]);

        CommentNotifier::send($comment, $mentionedUsers);

        return back()->with('status', 'Commentaire publié.');
    }

    public function update(UpdateCommentRequest $request, Post $post, Comment $comment): RedirectResponse
    {
        $content = $request->validated('comment_content');
        $mentionedUsers = CommentMentions::resolveFromContent($content);
        $oldContent = $comment->contenu;

        $comment->update([
            'contenu' => $content,
            'mentioned_user_id' => $mentionedUsers->first()?->id,
        ]);

        if ($oldContent !== $content) {
            CommentNotifier::send($comment->fresh(), $mentionedUsers);
        }

        return back()->with('status', 'Commentaire modifié.');
    }

    public function destroy(Post $post, Comment $comment): RedirectResponse
    {
        abort_unless($comment->post_id === $post->id, 404);
        abort_unless($comment->user_id === auth()->id(), 403);

        $comment->delete();

        return back()->with('status', 'Commentaire supprimé.');
    }
}
