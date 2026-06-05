<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Bibliothèque médias</h2></x-slot>
    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('status'))<p class="text-green-700">{{ session('status') }}</p>@endif
        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="bg-white p-4 shadow rounded-lg flex flex-wrap gap-3 items-end">
            @csrf
            <div><label class="block text-sm">Fichier</label><input type="file" name="file" accept="image/*" required></div>
            <div><label class="block text-sm">Alt</label><input name="alt" class="rounded-md border-gray-300"></div>
            <button class="rounded bg-brand-green px-4 py-2 text-white text-sm">Uploader</button>
        </form>
        <div class="grid gap-4 grid-cols-2 md:grid-cols-4">
            @foreach ($mediaItems as $media)
                <div class="bg-white p-2 shadow rounded">
                    <img src="{{ url(\Illuminate\Support\Facades\Storage::url($media->path)) }}" alt="{{ $media->alt }}" class="h-32 w-full object-cover rounded">
                    <p class="mt-1 text-xs truncate">{{ $media->filename }}</p>
                    <form method="POST" action="{{ route('admin.media.destroy', $media) }}" class="mt-1">
                        @csrf @method('DELETE')
                        <button class="text-xs text-brand-red">Supprimer</button>
                    </form>
                </div>
            @endforeach
        </div>
        {{ $mediaItems->links() }}
    </div>
</x-app-layout>
