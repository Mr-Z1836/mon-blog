<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Créer une catégorie</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="rounded-lg bg-white p-6 shadow space-y-4">
                @csrf
                @include('admin.categories._form')
                <button class="rounded bg-brand-green px-4 py-2 text-white">Créer</button>
            </form>
        </div>
    </div>
</x-app-layout>
