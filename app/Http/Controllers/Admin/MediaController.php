<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(): View
    {
        return view('admin.media.index', [
            'mediaItems' => Media::with('uploader:id,name')->latest()->paginate(24),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'alt' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $path = $file->store('media/library', 'public');

        Media::create([
            'user_id' => $request->user()->id,
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?? 'image/jpeg',
            'size' => $file->getSize(),
            'alt' => $request->string('alt')->toString() ?: null,
        ]);

        return back()->with('status', 'Média ajouté à la bibliothèque.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        Storage::disk('public')->delete($media->path);
        $media->delete();

        return back()->with('status', 'Média supprimé.');
    }
}
