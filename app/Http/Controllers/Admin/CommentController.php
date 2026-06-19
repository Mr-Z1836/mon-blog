<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModerateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
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
        $userId = $request->integer('utilisateur');

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
                ->when($userId > 0, fn ($query) => $query->where('user_id', $userId))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'articles' => Post::query()
                ->whereHas('comments')
                ->orderBy('titre')
                ->get(['id', 'titre']),
            'selectedArticle' => $articleId > 0 ? $articleId : null,
            'selectedUser' => $userId > 0
                ? User::query()->find($userId, ['id', 'name', 'username'])
                : null,
        ]);
    }

    public function update(ModerateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $visible = (bool) $request->validated('visible');

        $comment->update([
            'est_approuve' => $visible,
        ]);

        $status = $visible ? 'Commentaire affiché sur le blog.' : 'Commentaire masqué.';

        return back()->with('status', $status);
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
