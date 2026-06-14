<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Catégories</h2>
            <a href="{{ route('admin.categories.create') }}" class="rounded bg-brand-green px-4 py-2 text-white">Nouvelle catégorie</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="rounded-lg bg-white p-4 shadow">
                @foreach ($categories as $category)
                    <div class="flex items-center justify-between border-b py-3">
                        <div>
                            <p class="font-medium">{{ $category->nom }}</p>
                            <p class="text-sm text-gray-500">{{ $category->slug }}</p>
                        </div>
                        <div class="space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-red">Modifier</a>
                            <form class="inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">{{ $categories->links() }}</div>
        </div>
    </div>
</x-app-layout>
