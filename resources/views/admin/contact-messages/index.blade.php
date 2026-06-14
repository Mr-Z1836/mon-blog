<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Messages de contact</h2>
    </x-slot>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))<p class="mb-4 text-green-700">{{ session('status') }}</p>@endif
        <div class="bg-white shadow rounded-lg divide-y">
            @forelse ($messages as $message)
                <a href="{{ route('admin.contact-messages.show', $message) }}" class="block p-4 hover:bg-gray-50">
                    <div class="flex justify-between gap-4">
                        <div>
                            <p class="font-semibold {{ $message->lu_le ? 'text-gray-700' : 'text-brand-red' }}">
                                {{ $message->nom }}
                                @if (! $message->lu_le)
                                    <span class="ml-2 rounded bg-brand-yellow/20 px-2 py-0.5 text-xs text-brand-yellow">Nouveau</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">{{ $message->email }}</p>
                            <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $message->message }}</p>
                        </div>
                        <p class="text-xs text-gray-400 shrink-0">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </a>
            @empty
                <p class="p-6 text-gray-500 text-sm">Aucun message pour l'instant.</p>
            @endforelse
        </div>
        {{ $messages->links() }}
    </div>
</x-app-layout>
