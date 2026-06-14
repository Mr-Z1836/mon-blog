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
        $statut = $request->string('statut')->toString();
        $statut = in_array($statut, ['en_attente', 'approuves'], true) ? $statut : 'tous';

        return view('admin.comments.index', [
            'comments' => Comment::query()
                ->with([
                    'post:id,titre,slug',
                    'user:id,name,username',
                    'parent:id,contenu,user_id',
                    'parent.user:id,name,username',
                    'mentionedUser:id,name,username',
                ])
                ->withCount([
                    'reports as pending_reports_count' => fn ($query) => $query->where('statut', 'en_attente'),
                ])
                ->when($articleId > 0, fn ($query) => $query->where('post_id', $articleId))
                ->when($statut === 'en_attente', fn ($query) => $query->where('est_approuve', false))
                ->when($statut === 'approuves', fn ($query) => $query->where('est_approuve', true))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'articles' => Post::query()
                ->whereHas('comments')
                ->orderBy('titre')
                ->get(['id', 'titre']),
            'selectedArticle' => $articleId > 0 ? $articleId : null,
            'selectedStatut' => $statut,
            'stats' => [
                'total' => Comment::count(),
                'pending' => Comment::where('est_approuve', false)->count(),
                'approved' => Comment::where('est_approuve', true)->count(),
            ],
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
