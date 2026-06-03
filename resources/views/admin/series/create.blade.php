<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Créer une série</h2></x-slot>
    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.series.store') }}" class="bg-white p-6 shadow rounded-lg space-y-4">
            @csrf
            <div><label class="block text-sm font-medium">Titre</label><input name="title" required class="mt-1 w-full rounded-md border-gray-300"></div>
            <div><label class="block text-sm font-medium">Slug</label><input name="slug" class="mt-1 w-full rounded-md border-gray-300"></div>
            <div><label class="block text-sm font-medium">Description</label><textarea name="description" rows="3" class="mt-1 w-full rounded-md border-gray-300"></textarea></div>
            <button class="rounded bg-indigo-600 px-4 py-2 text-white">Créer</button>
        </form>
    </div>
</x-app-layout>
