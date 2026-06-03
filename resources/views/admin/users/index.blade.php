<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Utilisateurs</h2></x-slot>
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))<p class="mb-4 text-green-700">{{ session('status') }}</p>@endif
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50"><tr><th class="p-3 text-left">Nom</th><th class="p-3 text-left">Email</th><th class="p-3">Admin</th><th class="p-3"></th></tr></thead>
                <tbody>
                @foreach ($users as $user)
                    <tr class="border-t">
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3 text-center">{{ $user->is_admin ? 'Oui' : 'Non' }}</td>
                        <td class="p-3">
                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline-flex gap-2 items-center">
                                @csrf @method('PUT')
                                <input name="username" value="{{ $user->username }}" placeholder="pseudo" class="rounded border-gray-300 text-xs w-24">
                                <label class="text-xs"><input type="checkbox" name="is_admin" value="1" @checked($user->is_admin)> admin</label>
                                <button class="text-indigo-600 text-xs">OK</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</x-app-layout>
