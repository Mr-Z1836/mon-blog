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
            ->select(['id', 'user_id', 'category_id', 'titre', 'slug', 'resume', 'image_path', 'video_path', 'contenu', 'publie_le'])
            ->with(['author:id,name', 'category:id,nom,slug', 'tags:id,nom,slug'])
            ->withCount('views')
            ->withAvg('ratings', 'value')
            ->published()
            ->when($keyword !== '', function ($query) use ($keyword): void {
                $query->where(function ($innerQuery) use ($keyword): void {
                    $innerQuery
                        ->where('titre', 'like', "%{$keyword}%")
                        ->orWhere('resume', 'like', "%{$keyword}%")
                        ->orWhere('contenu', 'like', "%{$keyword}%");
                });
            })
            ->when($categorySlug !== '', function ($query) use ($categorySlug): void {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $categorySlug));
            })
            ->when($tagSlug !== '', function ($query) use ($tagSlug): void {
                $query->whereHas('tags', fn ($tagQuery) => $tagQuery->where('slug', $tagSlug));
            })
            ->orderByDesc('est_epingle')
            ->latest('publie_le')
            ->paginate(9)
            ->withQueryString();

        return view('home', [
            'posts' => $posts,
            'categories' => Category::orderBy('nom')->get(['id', 'nom', 'slug']),
            'tags' => Tag::orderBy('nom')->get(['id', 'nom', 'slug']),
            'keyword' => $keyword,
            'selectedCategory' => $categorySlug,
            'selectedTag' => $tagSlug,
        ]);
    }
}
