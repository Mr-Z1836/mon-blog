<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Articles</h2>
            <a href="{{ route('admin.posts.create') }}" class="rounded bg-indigo-600 px-4 py-2 text-white">Nouvel article</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="overflow-hidden rounded-lg bg-white shadow">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Titre</th>
                            <th class="px-4 py-3 text-left">Catégorie</th>
                            <th class="px-4 py-3 text-left">Auteur</th>
                            <th class="px-4 py-3 text-left">Publié</th>
                            <th class="px-4 py-3 text-left">Vues</th>
                            <th class="px-4 py-3 text-left">Note moyenne</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $post->title }}</td>
                                <td class="px-4 py-3">{{ $post->category->name }}</td>
                                <td class="px-4 py-3">{{ $post->author->name }}</td>
                                <td class="px-4 py-3">{{ $post->is_published ? 'Oui' : 'Non' }}</td>
                                <td class="px-4 py-3">{{ $post->views_count }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $post->ratings_avg_value, 1) }}/5</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a class="text-indigo-600" href="{{ route('admin.posts.edit', $post) }}">Modifier</a>
                                    <form class="inline" method="POST" action="{{ route('admin.posts.destroy', $post) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600" onclick="return confirm('Supprimer cet article ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $posts->links() }}</div>
        </div>
    </div>
</x-app-layout>
