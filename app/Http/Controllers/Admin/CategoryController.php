<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()->latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Category::create([
            'name' => $data['name'],
            'slug' => $this->resolveUniqueSlug($data['slug'] ?? null, $data['name']),
            'description' => $data['description'] ?? null,
        ]);

        return to_route('admin.categories.index')->with('status', 'Catégorie créée.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        $category->update([
            'name' => $data['name'],
            'slug' => $this->resolveUniqueSlug($data['slug'] ?? null, $data['name'], $category->id),
            'description' => $data['description'] ?? null,
        ]);

        return to_route('admin.categories.index')->with('status', 'Catégorie mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return to_route('admin.categories.index')->with('status', 'Catégorie supprimée.');
    }

    private function resolveUniqueSlug(?string $slug, string $name, ?int $ignoreCategoryId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $base = $base !== '' ? $base : 'categorie';
        $candidate = $base;
        $suffix = 1;

        while (
            Category::query()
                ->where('slug', $candidate)
                ->when($ignoreCategoryId !== null, fn ($query) => $query->where('id', '!=', $ignoreCategoryId))
                ->exists()
        ) {
            $candidate = "{$base}-{$suffix}";
            $suffix++;
        }

        return $candidate;
    }
}
