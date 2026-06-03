<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostReaction;
use App\Models\PostView;
use App\Support\ArticleContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Request $request, Post $post): View
    {
        abort_unless($post->is_published && $post->published_at !== null && $post->published_at->isPast(), 404);

        $view = PostView::create([
            'post_id' => $post->id,
            'user_id' => $request->user()?->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $post->load([
            'author:id,name,username',
            'category:id,name,slug',
            'tags:id,name,slug',
            'series:id,title,slug',
            'comments' => fn ($query) => $query
                ->where('is_approved', true)
                ->whereNull('parent_id')
                ->with([
                    'user:id,name,username',
                    'mentionedUser:id,name,username',
                    'children' => fn ($childQuery) => $childQuery
                        ->where('is_approved', true)
                        ->with(['user:id,name,username', 'mentionedUser:id,name,username'])
                        ->latest(),
                ])
                ->latest(),
            'reviews' => fn ($query) => $query
                ->where('is_approved', true)
                ->with('user:id,name')
                ->latest(),
        ])->loadCount('views')->loadAvg('ratings', 'value');

        $reactionCounts = $post->reactions()
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $user = $request->user();
        $userReaction = $user
            ? $post->reactions()->where('user_id', $user->id)->value('type')
            : null;
        $isBookmarked = $user
            ? $user->bookmarks()->where('post_id', $post->id)->exists()
            : false;

        $seriesPosts = $post->post_series_id
            ? Post::query()
                ->published()
                ->where('post_series_id', $post->post_series_id)
                ->orderBy('series_part')
                ->get(['id', 'title', 'slug', 'series_part'])
            : collect();

        $similarPosts = Post::query()
            ->published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->with(['category:id,name', 'tags:id,name'])
            ->withCount('views')
            ->latest('published_at')
            ->limit(3)
            ->get(['id', 'title', 'slug', 'excerpt', 'image_path', 'published_at']);

        $contentHtml = ArticleContent::renderHtml($post->content, $post->content_html);
        $tableOfContents = ArticleContent::tableOfContents($post->content);
        $youtubeId = ArticleContent::extractYoutubeId($post->youtube_url);

        return view('posts.show', [
            'post' => $post,
            'contentHtml' => $contentHtml,
            'tableOfContents' => $tableOfContents,
            'youtubeId' => $youtubeId,
            'averageRating' => round((float) $post->ratings_avg_value, 1),
            'viewsCount' => $post->views_count,
            'userRating' => $user ? $post->ratings()->where('user_id', $user->id)->value('value') : null,
            'reactionCounts' => $reactionCounts,
            'userReaction' => $userReaction,
            'isBookmarked' => $isBookmarked,
            'seriesPosts' => $seriesPosts,
            'similarPosts' => $similarPosts,
            'reactionTypes' => PostReaction::TYPES,
            'viewId' => $view->id,
        ]);
    }
}
