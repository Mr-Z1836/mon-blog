<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Signalements</h2></x-slot>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('status'))<p class="text-green-700">{{ session('status') }}</p>@endif

        @if ($selectedComment)
            <div class="rounded-lg bg-white p-4 shadow text-sm text-gray-600">
                Filtre actif : commentaire de <strong>{{ $selectedComment->user->name }}</strong>
                sur « {{ $selectedComment->post->titre }} ».
                <a href="{{ route('admin.reports.index') }}" class="ml-2 text-brand-red underline">Voir tous les signalements</a>
            </div>
        @endif

        @forelse ($reports as $report)
            <div class="bg-white p-4 shadow rounded-lg">
                <p class="text-sm text-gray-500">
                    Article :
                    <a class="text-brand-red" href="{{ route('posts.show', $report->comment->post) }}">{{ $report->comment->post->titre }}</a>
                </p>
                <p class="mt-2 text-sm text-gray-800">{{ $report->comment->contenu }}</p>
                <p class="mt-2 text-xs text-gray-500">
                    Signalé par
                    @if ($report->reporter)
                        {{ $report->reporter->name }}
                    @else
                        un invité
                    @endif
                    — {{ $report->created_at->format('d/m/Y H:i') }}
                </p>
                <p class="mt-1 text-sm text-gray-700"><span class="font-medium">Motif :</span> {{ $report->motif }}</p>
                <form method="POST" action="{{ route('admin.reports.update', $report) }}" class="mt-3 flex flex-wrap gap-2">
                    @csrf @method('PATCH')
                    <select name="statut" class="rounded-md border-gray-300 text-sm">
                        <option value="en_attente" @selected($report->statut === 'en_attente')>En attente</option>
                        <option value="examine" @selected($report->statut === 'examine')>Traité</option>
                        <option value="rejete" @selected($report->statut === 'rejete')>Rejeté</option>
                    </select>
                    <button class="text-sm text-brand-red">Mettre à jour</button>
                </form>
            </div>
        @empty
            <p class="rounded-lg bg-white p-4 text-sm text-gray-600 shadow">
                @if ($selectedComment)
                    Aucun signalement pour ce commentaire.
                @else
                    Aucun signalement pour le moment.
                @endif
            </p>
        @endforelse

        {{ $reports->links() }}
    </div>
</x-app-layout>
