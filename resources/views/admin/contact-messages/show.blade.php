<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Message de {{ $contactMessage->name }}</h2>
            <a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-brand-red">← Retour</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6 space-y-4">
            <p><span class="text-gray-500">E-mail :</span> <a href="mailto:{{ $contactMessage->email }}" class="text-brand-red">{{ $contactMessage->email }}</a></p>
            <p><span class="text-gray-500">Reçu le :</span> {{ $contactMessage->created_at->format('d/m/Y à H:i') }}</p>
            <div class="rounded border bg-gray-50 p-4 whitespace-pre-wrap text-gray-800">{{ $contactMessage->message }}</div>
            <form method="POST" action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" onsubmit="return confirm('Supprimer ce message ?')">
                @csrf @method('DELETE')
                <button class="text-sm text-brand-red">Supprimer</button>
            </form>
        </div>
    </div>
</x-app-layout>
