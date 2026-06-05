<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modération des commentaires</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="rounded-lg bg-white p-4 shadow space-y-4">
                @foreach ($comments as $comment)
                    <div class="border-b pb-4">
                        <p class="text-sm text-gray-500">
                            {{ $comment->user->name }} - <a class="text-brand-red" href="{{ route('admin.posts.edit', $comment->post) }}">{{ $comment->post->title }}</a>
                        </p>
                        <p class="mt-2 text-sm text-gray-700">{{ $comment->content }}</p>
                        <div class="mt-3 flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.comments.update', $comment) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_approved" value="{{ $comment->is_approved ? 0 : 1 }}">
                                <button class="rounded bg-brand-green px-3 py-1 text-white text-sm">
                                    {{ $comment->is_approved ? 'Masquer' : 'Approuver' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded bg-red-600 px-3 py-1 text-white text-sm">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $comments->links() }}
        </div>
    </div>
</x-app-layout>
