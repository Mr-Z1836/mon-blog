<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="font-semibold text-lg mb-4">Mes commentaires & notes</h3>
                <ul class="space-y-2 text-sm mb-4">
                    @foreach ($comments as $comment)
                        <li><a class="text-brand-red" href="{{ route('posts.show', $comment->post) }}">{{ $comment->post->titre }}</a> — {{ \Illuminate\Support\Str::limit($comment->contenu, 60) }}</li>
                    @endforeach
                </ul>
                <ul class="space-y-2 text-sm">
                    @foreach ($ratings as $rating)
                        <li><a class="text-brand-red" href="{{ route('posts.show', $rating->post) }}">{{ $rating->post->titre }}</a> — {{ $rating->value }}/5</li>
                    @endforeach
                </ul>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
