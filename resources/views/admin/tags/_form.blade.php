@if ($errors->any())
    <div class="mb-4 rounded bg-red-100 p-3 text-red-700">
        <ul class="list-disc ps-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Nom</label>
        <input name="name" value="{{ old('name', $tag->name ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium">Slug</label>
        <input name="slug" value="{{ old('slug', $tag->slug ?? '') }}" class="mt-1 w-full rounded-md border-gray-300">
    </div>
</div>
