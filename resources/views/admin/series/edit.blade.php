<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Modifier la série</h2></x-slot>
    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.series.update', $series) }}" class="bg-white p-6 shadow rounded-lg space-y-4">
            @csrf @method('PUT')
            <div><label class="block text-sm font-medium">Titre</label><input name="title" value="{{ old('title', $series->title) }}" required class="mt-1 w-full rounded-md border-gray-300"></div>
            <div><label class="block text-sm font-medium">Slug</label><input name="slug" value="{{ old('slug', $series->slug) }}" class="mt-1 w-full rounded-md border-gray-300"></div>
            <div><label class="block text-sm font-medium">Description</label><textarea name="description" rows="3" class="mt-1 w-full rounded-md border-gray-300">{{ old('description', $series->description) }}</textarea></div>
            <button class="rounded bg-brand-green px-4 py-2 text-white">Enregistrer</button>
        </form>
    </div>
</x-app-layout>
