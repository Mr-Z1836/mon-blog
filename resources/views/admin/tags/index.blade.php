<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tags</h2>
            <a href="{{ route('admin.tags.create') }}" class="rounded bg-brand-green px-4 py-2 text-white">Nouveau tag</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="rounded-lg bg-white p-4 shadow">
                @foreach ($tags as $tag)
                    <div class="flex items-center justify-between border-b py-3">
                        <div>
                            <p class="font-medium">{{ $tag->name }}</p>
                            <p class="text-sm text-gray-500">{{ $tag->slug }}</p>
                        </div>
                        <div class="space-x-2">
                            <a href="{{ route('admin.tags.edit', $tag) }}" class="text-brand-red">Modifier</a>
                            <form class="inline" method="POST" action="{{ route('admin.tags.destroy', $tag) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600" onclick="return confirm('Supprimer ce tag ?')">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">{{ $tags->links() }}</div>
        </div>
    </div>
</x-app-layout>
