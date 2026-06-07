@extends('layouts.blog')

@section('meta_title', 'À propos — Built in Benin by Harry DEDJI')
@section('meta_description', 'Qui est Harry DEDJI, pourquoi Built in Benin existe, et ce que tu y trouveras sur la tech et l\'entrepreneuriat africain.')
@section('canonical_url', route('about'))

@section('content')
    <x-page-shell>
        <div class="text-center">
            <x-brand-logo size="lg" />
            <p class="mt-2 text-sm font-semibold text-brand-yellow">by Harry DEDJI</p>
        </div>

        <h1 class="text-3xl font-black text-slate-800 dark:text-slate-100">À propos</h1>

        <div class="reading-content space-y-4">
            <p>
                <strong class="text-brand-green">Built</strong><strong class="text-brand-yellow"> in </strong><strong class="text-brand-red">Benin</strong> est un blog créé par Harry DEDJI alias Starboy, dédié à la tech et à
                l'entrepreneuriat en Afrique — avec une attention particulière pour le Bénin et
                la jeunesse qui veut coder, entreprendre et saisir les opportunités du continent.
            </p>
            <p>
                Ici, on parle sans jargon inutile : tutoriels de code, retours d'expérience
                startup, finance et crypto expliquées simplement, vie d'étudiant en filière tech,
                et annonces de bourses, concours ou programmes pour les jeunes Africains.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-bold text-brand-green mb-3">Pourquoi ce blog ?</h2>
            <ul class="reading-content space-y-2">
                <li class="flex gap-2"><span class="text-brand-red">→</span> Centraliser des ressources utiles en français pour la diaspora et le continent</li>
                <li class="flex gap-2"><span class="text-brand-yellow">→</span> Montrer que l'innovation tech « se construit » aussi depuis le Bénin</li>
                <li class="flex gap-2"><span class="text-brand-green">→</span> Créer une communauté de lecteurs qui commentent, partagent et progressent ensemble</li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-bold text-brand-red mb-3">Ce que tu y trouveras</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200/80 bg-white/60 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                    <p class="font-semibold text-brand-green">Dev & Code</p>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Tutoriels, stacks, bonnes pratiques et projets concrets.</p>
                </div>
                <div class="rounded-xl border border-slate-200/80 bg-white/60 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                    <p class="font-semibold text-brand-yellow">Entrepreneuriat</p>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Mindset, idées, lancement et croissance de projets.</p>
                </div>
                <div class="rounded-xl border border-slate-200/80 bg-white/60 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                    <p class="font-semibold text-brand-red">Crypto & Finance</p>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Bases, outils et réflexion autour de l'argent digital.</p>
                </div>
                <div class="rounded-xl border border-slate-200/80 bg-white/60 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                    <p class="font-semibold text-brand-red">Vie d'étudiant tech</p>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Parcours, organisation, stages et premiers pas pro.</p>
                </div>
                <div class="rounded-xl border border-slate-200/80 bg-white/60 p-4 sm:col-span-2 dark:border-slate-700 dark:bg-slate-800/50">
                    <p class="font-semibold text-brand-yellow">Opportunités</p>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Bourses, concours, incubateurs et programmes pour les jeunes Africains.</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-brand-yellow/30 bg-brand-yellow/5 p-5 text-center">
            <p class="text-slate-700 dark:text-slate-200">Prêt à explorer ?</p>
            <a href="{{ route('posts.index') }}" class="btn-neon mt-3 inline-flex">Voir les articles</a>
        </div>
    </x-page-shell>
@endsection
