<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Abonnés newsletter</h2>
    </x-slot>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <p class="mb-4 text-green-700">{{ session('status') }}</p>
        @endif

        <p class="mb-4 text-sm text-gray-600">{{ $totalActive }} abonné(s) actif(s)</p>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">E-mail</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Source</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Inscrit le</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($subscribers as $subscriber)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $subscriber->email }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $subscriber->source ?: '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $subscriber->abonne_le->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('admin.newsletter-subscribers.destroy', $subscriber) }}" onsubmit="return confirm('Désinscrire cet abonné ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-brand-red hover:text-brand-red">Désinscrire</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">Aucun abonné pour l'instant.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $subscribers->links() }}</div>
    </div>
</x-app-layout>
