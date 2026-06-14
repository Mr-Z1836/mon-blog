<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModerateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Support\CommentMentions;
use App\Support\CommentNotifier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $articleId = $request->integer('article');

        return view('admin.comments.index', [
            'comments' => Comment::query()
                ->with(['post:id,titre,slug', 'user:id,name'])
                ->withCount([
                    'reports as pending_reports_count' => fn ($query) => $query->where('statut', 'en_attente'),
                ])
                ->when($articleId > 0, fn ($query) => $query->where('post_id', $articleId))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'articles' => Post::query()
                ->whereHas('comments')
                ->orderBy('titre')
                ->get(['id', 'titre']),
            'selectedArticle' => $articleId > 0 ? $articleId : null,
        ]);
    }

    public function update(ModerateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $wasApproved = $comment->est_approuve;

        $comment->update([
            'est_approuve' => (bool) $request->validated('is_approved'),
        ]);

        if (! $wasApproved && $comment->est_approuve) {
            CommentNotifier::send($comment, CommentMentions::resolveFromContent($comment->contenu));
        }

        return back()->with('status', 'Commentaire mis à jour.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('status', 'Commentaire supprimé.');
    }
}
