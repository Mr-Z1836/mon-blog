@if ($errors->any())
    <div class="mb-4 rounded bg-red-100 p-3 text-red-700">
        <ul class="list-disc ps-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-4">
    <div>
        <label class="block text-sm font-medium">Titre</label>
        <input name="title" value="{{ old('title', $post->title ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required />
    </div>
    <div>
        <label class="block text-sm font-medium">Slug</label>
        <input name="slug" value="{{ old('slug', $post->slug ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" />
    </div>
    <div>
        <label class="block text-sm font-medium">Catégorie</label>
        <select name="category_id" class="mt-1 w-full rounded-md border-gray-300" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $post->category_id ?? 0) === $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium">Tags</label>
        @php
            $selectedTags = collect(old('tags', isset($post) ? $post->tags->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
        @endphp
        <select name="tags[]" multiple class="mt-1 w-full rounded-md border-gray-300">
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTags, true))>{{ $tag->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium">Extrait</label>
        <textarea name="excerpt" rows="2" class="mt-1 w-full rounded-md border-gray-300">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium">Image de couverture (obligatoire)</label>
        <input type="file" name="image" accept="image/*" @required(!isset($post) || empty($post->image_path)) class="mt-1 w-full rounded-md border-gray-300" />
        @if (!empty($post?->image_path))
            <div class="mt-2">
                <img src="{{ url(\Illuminate\Support\Facades\Storage::url($post->image_path)) }}" alt="Aperçu image" class="max-h-40 rounded border" />
                <label class="mt-2 inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remove_image" value="1" />
                    Remplacer l'image actuelle (nécessite d'en sélectionner une nouvelle)
                </label>
            </div>
        @endif
    </div>
    <div>
        <label class="block text-sm font-medium">Vidéo (optionnelle)</label>
        <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime" class="mt-1 w-full rounded-md border-gray-300" />
        @if (!empty($post?->video_path))
            <div class="mt-2 space-y-2">
                <video controls class="max-h-48 rounded border">
                    <source src="{{ url(\Illuminate\Support\Facades\Storage::url($post->video_path)) }}">
                </video>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remove_video" value="1" />
                    Supprimer la vidéo actuelle
                </label>
            </div>
        @endif
    </div>
    <div>
        <label class="block text-sm font-medium">Contenu (éditeur riche)</label>
        <input type="hidden" name="content_html" value="{{ old('content_html', $post->content_html ?? '') }}">
        <div id="tiptap-editor" class="mt-1 min-h-[200px] rounded-md border border-gray-300 bg-white p-3 prose max-w-none"></div>
        <textarea name="content" rows="8" class="mt-2 w-full rounded-md border-gray-300" required>{{ old('content', $post->content ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium">URL YouTube (embed)</label>
        <input name="youtube_url" value="{{ old('youtube_url', $post->youtube_url ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" placeholder="https://www.youtube.com/watch?v=..." />
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium">Meta title (SEO)</label>
            <input name="meta_title" value="{{ old('meta_title', $post->meta_title ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" />
        </div>
        <div>
            <label class="block text-sm font-medium">Meta description</label>
            <input name="meta_description" value="{{ old('meta_description', $post->meta_description ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" />
        </div>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium">Série</label>
            <select name="post_series_id" class="mt-1 w-full rounded-md border-gray-300">
                <option value="">Aucune</option>
                @foreach ($seriesList ?? [] as $serie)
                    <option value="{{ $serie->id }}" @selected((int) old('post_series_id', $post->post_series_id ?? 0) === $serie->id)>{{ $serie->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">Partie n°</label>
            <input type="number" name="series_part" min="1" value="{{ old('series_part', $post->series_part ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" />
        </div>
    </div>
    <div class="flex flex-wrap items-center gap-4">
        <label class="inline-flex items-center gap-2">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" id="is_published" name="is_published" value="1" @checked((bool) old('is_published', $post->is_published ?? true))>
            <span>Publier</span>
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="hidden" name="is_pinned" value="0">
            <input type="checkbox" name="is_pinned" value="1" @checked((bool) old('is_pinned', $post->is_pinned ?? false))>
            <span>Épingler</span>
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" name="is_featured" value="1" @checked((bool) old('is_featured', $post->is_featured ?? false))>
            <span>À la une</span>
        </label>
    </div>
    <div>
        <label class="block text-sm font-medium">Date de publication</label>
        <input
            type="datetime-local"
            name="published_at"
            value="{{ old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
            class="mt-1 w-full rounded-md border-gray-300"
        />
    </div>
</div>
