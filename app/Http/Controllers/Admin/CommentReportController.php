<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentReportController extends Controller
{
    public function index(Request $request): View
    {
        $commentId = $request->integer('commentaire');

        return view('admin.reports.index', [
            'reports' => CommentReport::query()
                ->with([
                    'comment.post:id,titre,slug',
                    'comment.user:id,name,username',
                    'comment.parent:id,contenu,user_id',
                    'comment.parent.user:id,name,username',
                    'reporter:id,name',
                ])
                ->when($commentId > 0, fn ($query) => $query->where('comment_id', $commentId))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'selectedComment' => $commentId > 0
                ? Comment::query()
                    ->with(['post:id,titre', 'user:id,name,username', 'parent.user:id,name,username'])
                    ->find($commentId)
                : null,
        ]);
    }

    public function update(Request $request, CommentReport $commentReport): RedirectResponse
    {
        $data = $request->validate([
            'statut' => ['required', 'in:en_attente,examine,rejete'],
        ]);

        $commentReport->update(['statut' => $data['statut']]);

        return back()->with('status', 'Signalement mis à jour.');
    }
}
