<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier un tag</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="rounded-lg bg-white p-6 shadow space-y-4">
                @csrf
                @method('PUT')
                @include('admin.tags._form')
                <button class="rounded bg-brand-green px-4 py-2 text-white">Enregistrer</button>
            </form>
        </div>
    </div>
</x-app-layout>
