<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostSeries;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostSeriesController extends Controller
{
    public function index(): View
    {
        return view('admin.series.index', [
            'series' => PostSeries::withCount('posts')->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.series.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        PostSeries::create([
            'title' => $data['title'],
            'slug' => Str::slug($data['slug'] ?: $data['title']),
            'description' => $data['description'] ?? null,
        ]);

        return to_route('admin.series.index')->with('status', 'Série créée.');
    }

    public function edit(PostSeries $series): View
    {
        return view('admin.series.edit', ['series' => $series]);
    }

    public function update(Request $request, PostSeries $series): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $series->update([
            'title' => $data['title'],
            'slug' => Str::slug($data['slug'] ?: $data['title']),
            'description' => $data['description'] ?? null,
        ]);

        return to_route('admin.series.index')->with('status', 'Série mise à jour.');
    }

    public function destroy(PostSeries $series): RedirectResponse
    {
        $series->posts()->update(['post_series_id' => null, 'series_part' => null]);
        $series->delete();

        return to_route('admin.series.index')->with('status', 'Série supprimée.');
    }
}
