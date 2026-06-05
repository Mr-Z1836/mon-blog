<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modération des avis</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="rounded-lg bg-white p-4 shadow space-y-4">
                @foreach ($reviews as $review)
                    <div class="border-b pb-4">
                        <p class="text-sm text-gray-500">
                            {{ $review->user->name }} - <a class="text-brand-red" href="{{ route('admin.posts.edit', $review->post) }}">{{ $review->post->title }}</a>
                        </p>
                        <p class="mt-2 text-sm text-gray-700">{{ $review->content }}</p>
                        <div class="mt-3 flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">
                                <button class="rounded bg-brand-green px-3 py-1 text-white text-sm">
                                    {{ $review->is_approved ? 'Masquer' : 'Approuver' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded bg-red-600 px-3 py-1 text-white text-sm">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $reviews->links() }}
        </div>
    </div>
</x-app-layout>
