@auth
    @if (! auth()->user()->hasVerifiedEmail())
        <div {{ $attributes->merge(['class' => 'rounded-lg border border-brand-yellow/40 bg-brand-yellow/10 p-4 text-sm text-slate-800 dark:text-slate-200']) }}>
            <p>
                {{ $errors->first('email_verification') ?: 'Veuillez vérifier votre email pour commenter et noter les articles.' }}
            </p>
            <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
                @csrf
                <button type="submit" class="rounded-lg bg-brand-green px-4 py-2 text-sm font-semibold text-white hover:bg-brand-green/90">
                    Renvoyer l'email de confirmation
                </button>
            </form>
            @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-brand-green">Un nouvel email de confirmation a été envoyé.</p>
            @endif
        </div>
    @endif
@endauth
