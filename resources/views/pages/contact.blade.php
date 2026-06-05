@extends('layouts.blog')

@section('meta_title', 'Contact — Built in Benin')
@section('meta_description', 'Contacte Starboy via Built in Benin : questions, collaborations, suggestions d\'articles.')
@section('canonical_url', route('contact'))

@section('content')
    <x-page-shell>
        <h1 class="text-3xl font-black text-slate-800 dark:text-slate-100">Contact</h1>
        <p class="reading-content">
            Une question, une idée d'article, une proposition de collaboration ou un retour sur le blog ?
            Envoie un message — je lis tout et je réponds dès que possible.
        </p>

        @if (session('status'))
            <div class="rounded-xl border border-brand-green/40 bg-brand-green/10 p-4 text-brand-green dark:text-brand-green">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nom</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-brand-red focus:ring-brand-red/30 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                />
                @error('name')
                    <p class="mt-1 text-sm text-brand-red">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-mail</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-brand-red focus:ring-brand-red/30 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                />
                @error('email')
                    <p class="mt-1 text-sm text-brand-red">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Message</label>
                <textarea
                    id="message"
                    name="message"
                    rows="6"
                    required
                    placeholder="Écris ton message ici (10 caractères minimum)…"
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-brand-red focus:ring-brand-red/30 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-brand-red">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn-neon px-6 py-2.5">Envoyer le message</button>
        </form>
    </x-page-shell>
@endsection
