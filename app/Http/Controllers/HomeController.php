<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function welcome(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return to_route('posts.index');
        }

        return view('welcome-blog', [
            'tagline' => config('blog.tagline'),
            'theme' => config('blog.theme'),
        ]);
    }

    public function index(Request $request): View
    {
        $keyword = $request->string('q')->toString();
        $categorySlug = $request->string('category')->toString();
        $tagSlug = $request->string('tag')->toString();

        $posts = Post::query()
            ->select(['id', 'user_id', 'category_id', 'title', 'slug', 'excerpt', 'image_path', 'video_path', 'content', 'published_at'])
            ->with(['author:id,name', 'category:id,name,slug', 'tags:id,name,slug'])
            ->withCount('views')
            ->withAvg('ratings', 'value')
            ->published()
            ->when($keyword !== '', function ($query) use ($keyword): void {
                $query->where(function ($innerQuery) use ($keyword): void {
                    $innerQuery
                        ->where('title', 'like', "%{$keyword}%")
                        ->orWhere('excerpt', 'like', "%{$keyword}%")
                        ->orWhere('content', 'like', "%{$keyword}%");
                });
            })
            ->when($categorySlug !== '', function ($query) use ($categorySlug): void {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $categorySlug));
            })
            ->when($tagSlug !== '', function ($query) use ($tagSlug): void {
                $query->whereHas('tags', fn ($tagQuery) => $tagQuery->where('slug', $tagSlug));
            })
            ->orderByDesc('is_pinned')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('home', [
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(['id', 'name', 'slug']),
            'tags' => Tag::orderBy('name')->get(['id', 'name', 'slug']),
            'keyword' => $keyword,
            'selectedCategory' => $categorySlug,
            'selectedTag' => $tagSlug,
        ]);
    }
}
