<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Séries d'articles</h2>
            <a href="{{ route('admin.series.create') }}" class="rounded bg-brand-green px-3 py-1 text-white text-sm">Nouvelle série</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))<p class="mb-4 text-green-700">{{ session('status') }}</p>@endif
        <div class="bg-white shadow rounded-lg divide-y">
            @foreach ($series as $item)
                <div class="p-4 flex justify-between">
                    <div>
                        <p class="font-semibold">{{ $item->title }}</p>
                        <p class="text-sm text-gray-500">{{ $item->posts_count }} article(s)</p>
                    </div>
                    <a href="{{ route('admin.series.edit', $item) }}" class="text-brand-red text-sm">Modifier</a>
                </div>
            @endforeach
        </div>
        {{ $series->links() }}
    </div>
</x-app-layout>
