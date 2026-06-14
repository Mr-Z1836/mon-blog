<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostView;
use App\Support\ArticleContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Request $request, Post $post): View
    {
        abort_unless($post->est_publie && $post->publie_le !== null && $post->publie_le->isPast(), 404);

        PostView::create([
            'post_id' => $post->id,
            'user_id' => $request->user()?->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $post->load([
            'author:id,name,username',
            'category:id,nom,slug',
            'tags:id,nom,slug',
            'series:id,title,slug',
            'comments' => fn ($query) => $query
                ->where('est_approuve', true)
                ->whereNull('parent_id')
                ->with([
                    'user:id,name,username',
                    'mentionedUser:id,name,username',
                    'children' => fn ($childQuery) => $childQuery
                        ->where('est_approuve', true)
                        ->with(['user:id,name,username', 'mentionedUser:id,name,username'])
                        ->latest(),
                ])
                ->latest(),
        ])->loadCount('views')->loadAvg('ratings', 'value');

        $user = $request->user();

        $seriesPosts = $post->post_series_id
            ? Post::query()
                ->published()
                ->where('post_series_id', $post->post_series_id)
                ->orderBy('series_part')
                ->get(['id', 'titre', 'slug', 'series_part'])
            : collect();

        $similarPosts = Post::query()
            ->published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->with(['category:id,nom', 'tags:id,nom'])
            ->withCount('views')
            ->latest('publie_le')
            ->limit(3)
            ->get(['id', 'titre', 'slug', 'resume', 'image_path', 'publie_le']);

        $contentHtml = ArticleContent::renderHtml($post->contenu, $post->content_html);
        $tableOfContents = ArticleContent::tableOfContents($post->contenu);
        $youtubeId = ArticleContent::extractYoutubeId($post->youtube_url);

        return view('posts.show', [
            'post' => $post,
            'contentHtml' => $contentHtml,
            'tableOfContents' => $tableOfContents,
            'youtubeId' => $youtubeId,
            'averageRating' => round((float) $post->ratings_avg_value, 1),
            'viewsCount' => $post->views_count,
            'userRating' => $user ? $post->ratings()->where('user_id', $user->id)->value('value') : null,
            'seriesPosts' => $seriesPosts,
            'similarPosts' => $similarPosts,
        ]);
    }
}
