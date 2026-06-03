<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModerateCommentRequest;
use App\Models\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.comments.index', [
            'comments' => Comment::with(['post:id,title,slug', 'user:id,name'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function update(ModerateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $comment->update([
            'is_approved' => (bool) $request->validated('is_approved'),
        ]);

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
