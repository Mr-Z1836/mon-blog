<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.tags.index', [
            'tags' => Tag::query()->latest()->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Tag::create([
            'name' => $data['name'],
            'slug' => $this->resolveUniqueSlug($data['slug'] ?? null, $data['name']),
        ]);

        return to_route('admin.tags.index')->with('status', 'Tag créé.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Tag $tag): View
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $data = $request->validated();

        $tag->update([
            'name' => $data['name'],
            'slug' => $this->resolveUniqueSlug($data['slug'] ?? null, $data['name'], $tag->id),
        ]);

        return to_route('admin.tags.index')->with('status', 'Tag mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return to_route('admin.tags.index')->with('status', 'Tag supprimé.');
    }

    private function resolveUniqueSlug(?string $slug, string $name, ?int $ignoreTagId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $base = $base !== '' ? $base : 'tag';
        $candidate = $base;
        $suffix = 1;

        while (
            Tag::query()
                ->where('slug', $candidate)
                ->when($ignoreTagId !== null, fn ($query) => $query->where('id', '!=', $ignoreTagId))
                ->exists()
        ) {
            $candidate = "{$base}-{$suffix}";
            $suffix++;
        }

        return $candidate;
    }
}
