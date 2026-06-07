@extends('layouts.blog')

@section('meta_title', 'Mentions légales — Built in Benin by Harry DEDJI')
@section('meta_description', 'Mentions légales du blog Built in Benin by Harry DEDJI.')
@section('canonical_url', route('legal'))

@section('content')
    <x-page-shell>
        <h1 class="text-3xl font-black text-slate-800 dark:text-slate-100">Mentions légales</h1>
        <p class="text-sm text-slate-500">Dernière mise à jour : {{ now()->format('d/m/Y') }}</p>

        <section class="reading-content space-y-3">
            <h2 class="text-lg font-bold text-brand-green">1. Éditeur du site</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                Le site <strong>Built in Benin</strong> (by Harry DEDJI) est édité par Harry DEDJI,
                blog personnel consacré à la tech et à l'entrepreneuriat africain.<br>
                Contact : via le <a href="{{ route('contact') }}" class="text-brand-green underline">formulaire de contact</a>.
            </p>
        </section>

        <section class="reading-content space-y-3">
            <h2 class="text-lg font-bold text-brand-yellow">2. Hébergement</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                Le site est hébergé par <strong>Railway Corp.</strong><br>
                548 Market St, PMB 68956, San Francisco, CA 94104, États-Unis.<br>
                Site : <a href="https://railway.com" class="text-brand-red underline" target="_blank" rel="noopener">railway.com</a>
            </p>
        </section>

        <section class="reading-content space-y-3">
            <h2 class="text-lg font-bold text-brand-red">3. Propriété intellectuelle</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                L'ensemble des contenus publiés (textes, images, vidéos, code) est la propriété de l'éditeur,
                sauf mention contraire. Toute reproduction ou diffusion sans autorisation écrite préalable est interdite.
            </p>
        </section>

        <section class="reading-content space-y-3">
            <h2 class="text-lg font-bold text-brand-green">4. Données personnelles</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                Les données collectées via les formulaires (inscription, contact, commentaires) servent uniquement
                au fonctionnement du blog et à la communication avec les utilisateurs. Tu peux demander la suppression
                de tes données en nous contactant.
            </p>
        </section>

        <section class="reading-content space-y-3">
            <h2 class="text-lg font-bold text-brand-yellow">5. Cookies & analytics</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                Le site peut utiliser des cookies techniques (session, préférences) et des outils de mesure d'audience
                (ex. Google Analytics) pour améliorer l'expérience. Tu peux configurer ton navigateur pour refuser les cookies non essentiels.
            </p>
        </section>

        <section class="reading-content space-y-3">
            <h2 class="text-lg font-bold text-brand-red">6. Responsabilité</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                Les informations publiées le sont à titre informatif. L'éditeur s'efforce d'assurer l'exactitude des contenus
                mais ne peut garantir l'absence d'erreurs. L'utilisation du site se fait sous ta propre responsabilité.
            </p>
        </section>
    </x-page-shell>
@endsection
