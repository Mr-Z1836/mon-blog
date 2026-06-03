<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class PostBookmarkController extends Controller
{
    public function store(Post $post): RedirectResponse
    {
        request()->user()->bookmarks()->firstOrCreate(['post_id' => $post->id]);

        return back()->with('status', 'Article ajouté à vos favoris.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        request()->user()->bookmarks()->where('post_id', $post->id)->delete();

        return back()->with('status', 'Article retiré de vos favoris.');
    }
}
