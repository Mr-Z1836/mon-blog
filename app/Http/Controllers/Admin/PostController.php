<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostSeries;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.posts.index', [
            'posts' => Post::with(['category:id,nom', 'author:id,name'])
                ->withCount('views')
                ->withAvg('ratings', 'value')
                ->latest()
                ->paginate(12),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.posts.create', [
            'categories' => Category::orderBy('nom')->get(['id', 'nom']),
            'tags' => Tag::orderBy('nom')->get(['id', 'nom']),
            'seriesList' => PostSeries::orderBy('title')->get(['id', 'title']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $post = Post::create([
            'user_id' => $request->user()->id,
            'category_id' => $data['category_id'],
            'titre' => $data['title'],
            'slug' => $this->resolveUniqueSlug($data['slug'] ?? null, $data['title']),
            'resume' => $data['excerpt'] ?? null,
            'image_path' => $request->hasFile('image')
                ? $request->file('image')->store('posts/images', 'public')
                : null,
            'video_path' => $request->hasFile('video')
                ? $request->file('video')->store('posts/videos', 'public')
                : null,
            ...$this->editorFields($data),
            'est_publie' => (bool) ($data['is_published'] ?? false),
            'publie_le' => ($data['is_published'] ?? false) ? ($data['published_at'] ?? now()) : null,
            'autosaved_at' => now(),
        ]);

        $post->tags()->sync($data['tags'] ?? []);

        return to_route('admin.posts.index')->with('status', 'Article créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'post' => $post->load('tags:id'),
            'categories' => Category::orderBy('nom')->get(['id', 'nom']),
            'tags' => Tag::orderBy('nom')->get(['id', 'nom']),
            'seriesList' => PostSeries::orderBy('title')->get(['id', 'title']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();

        if (($data['remove_image'] ?? false) && ! $request->hasFile('image')) {
            return back()
                ->withErrors(['image' => 'Tu dois sélectionner une nouvelle image avant de supprimer la couverture actuelle.'])
                ->withInput();
        }

        $imagePath = $post->image_path;
        $videoPath = $post->video_path;

        if (($data['remove_image'] ?? false) && $imagePath) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('posts/images', 'public');
        }

        if (($data['remove_video'] ?? false) && $videoPath) {
            Storage::disk('public')->delete($videoPath);
            $videoPath = null;
        }

        if ($request->hasFile('video')) {
            if ($videoPath) {
                Storage::disk('public')->delete($videoPath);
            }
            $videoPath = $request->file('video')->store('posts/videos', 'public');
        }

        $post->update([
            'category_id' => $data['category_id'],
            'titre' => $data['title'],
            'slug' => $this->resolveUniqueSlug($data['slug'] ?? null, $data['title'], $post->id),
            'resume' => $data['excerpt'] ?? null,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            ...$this->editorFields($data),
            'est_publie' => (bool) ($data['is_published'] ?? false),
            'publie_le' => ($data['is_published'] ?? false) ? ($data['published_at'] ?? $post->publie_le ?? now()) : null,
            'autosaved_at' => now(),
        ]);

        $post->tags()->sync($data['tags'] ?? []);

        return to_route('admin.posts.index')->with('status', 'Article mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        if ($post->video_path) {
            Storage::disk('public')->delete($post->video_path);
        }

        $post->delete();

        return to_route('admin.posts.index')->with('status', 'Article supprimé.');
    }

    private function resolveUniqueSlug(?string $slug, string $title, ?int $ignorePostId = null): string
    {
        $base = Str::slug($slug ?: $title);
        $base = $base !== '' ? $base : 'article';
        $candidate = $base;
        $suffix = 1;

        while (
            Post::query()
                ->where('slug', $candidate)
                ->when($ignorePostId !== null, fn ($query) => $query->where('id', '!=', $ignorePostId))
                ->exists()
        ) {
            $candidate = "{$base}-{$suffix}";
            $suffix++;
        }

        return $candidate;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function editorFields(array $data): array
    {
        return [
            'contenu' => $data['content'],
            'content_html' => $data['content_html'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'cta_title' => filled($data['cta_title'] ?? null) ? $data['cta_title'] : null,
            'cta_text' => filled($data['cta_text'] ?? null) ? $data['cta_text'] : null,
            'cta_url' => filled($data['cta_url'] ?? null) ? $data['cta_url'] : null,
            'youtube_url' => $data['youtube_url'] ?? null,
            'post_series_id' => $data['post_series_id'] ?? null,
            'series_part' => $data['series_part'] ?? null,
            'est_epingle' => (bool) ($data['is_pinned'] ?? false),
            'est_a_la_une' => (bool) ($data['is_featured'] ?? false),
        ];
    }
}
