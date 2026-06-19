<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Utilisateurs</h2>
            <p class="mt-1 text-sm text-gray-500">Comptes inscrits, pseudos et droits d'administration.</p>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (session('status'))
            <div class="rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="rounded bg-red-100 p-3 text-red-800">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-600">
                    <tr>
                        <th class="p-3 font-medium">Pseudo</th>
                        <th class="p-3 font-medium">Email</th>
                        <th class="p-3 font-medium">Inscrit le</th>
                        <th class="p-3 font-medium text-center">Commentaires</th>
                        <th class="p-3 font-medium text-center">Admin</th>
                        <th class="p-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-t align-top">
                            <td class="p-3">
                                <span class="font-medium text-brand-red">{{ $user->publicHandle() }}</span>
                                @if ($user->id === auth()->id())
                                    <span class="ms-1 rounded bg-brand-green/10 px-1.5 py-0.5 text-xs text-brand-green">toi</span>
                                @endif
                            </td>
                            <td class="p-3 text-gray-600">{{ $user->email }}</td>
                            <td class="p-3 text-gray-600 whitespace-nowrap">
                                {{ $user->created_at->locale(app()->getLocale())->format('d/m/Y') }}
                                <span class="block text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="p-3 text-center">
                                @if ($user->comments_count > 0)
                                    <a
                                        href="{{ route('admin.comments.index', ['utilisateur' => $user->id]) }}"
                                        class="font-medium text-brand-red underline"
                                        title="Voir les commentaires"
                                    >
                                        {{ $user->comments_count }}
                                    </a>
                                @else
                                    <span class="text-gray-400">0</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                @if ($user->est_administrateur)
                                    <span class="rounded-full bg-brand-green/10 px-2 py-0.5 text-xs font-medium text-brand-green">Oui</span>
                                @else
                                    <span class="text-gray-400">Non</span>
                                @endif
                            </td>
                            <td class="p-3 min-w-[10rem]">
                                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-2">
                                    @csrf
                                    @method('PUT')
                                    <label class="flex items-center gap-1.5 text-xs text-gray-700">
                                        <input type="checkbox" name="is_admin" value="1" @checked($user->est_administrateur)>
                                        Administrateur
                                    </label>
                                    <button type="submit" class="rounded bg-brand-green px-3 py-1 text-xs text-white">
                                        Enregistrer
                                    </button>
                                </form>

                                @if ($user->id !== auth()->id())
                                    @php
                                        $deleteLabel = $user->publicHandle();
                                        $deleteImpact = collect([
                                            $user->comments_count > 0 ? $user->comments_count.' commentaire(s)' : null,
                                            $user->posts_count > 0 ? $user->posts_count.' article(s)' : null,
                                        ])->filter()->implode(', ');
                                        $deleteConfirm = $deleteImpact !== ''
                                            ? "Supprimer {$deleteLabel} ? {$deleteImpact} seront aussi supprimés."
                                            : "Supprimer {$deleteLabel} ?";
                                    @endphp
                                    <form
                                        method="POST"
                                        action="{{ route('admin.users.destroy', $user) }}"
                                        class="mt-2"
                                        onsubmit="return confirm(@js($deleteConfirm))"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:underline">
                                            Supprimer
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
</x-app-layout>
