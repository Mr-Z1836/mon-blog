<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier un article</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data" class="rounded-lg bg-white p-6 shadow space-y-4">
                @csrf
                @method('PUT')
                @include('admin.posts._form')
                <button class="rounded bg-indigo-600 px-4 py-2 text-white">Enregistrer</button>
            </form>
        </div>
    </div>
    @push('scripts')
        @vite(['resources/js/admin-editor.js'])
    @endpush
</x-app-layout>
