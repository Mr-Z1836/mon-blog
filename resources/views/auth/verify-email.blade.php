<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Merci pour votre inscription sur <strong>Built in Benin by Harry DEDJI</strong>.
        Avant de commenter ou noter les articles, confirmez votre adresse email en cliquant sur le lien que nous venons de vous envoyer.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Un nouvel email de confirmation a été envoyé à l'adresse indiquée lors de l'inscription.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Renvoyer l'email de confirmation
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>
