<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @php
        $blogAuthor = 'by Harry DEDJI';
        $blogSiteName = 'Built in Benin '.$blogAuthor;
        $seoTitle = trim($__env->yieldContent('meta_title') ?: $blogSiteName.' — '.config('blog.theme'));
        $seoDescription = trim($__env->yieldContent('meta_description') ?: config('blog.meta_description'));
        $seoCanonical = trim($__env->yieldContent('canonical_url') ?: url()->current());
        $seoOgType = trim($__env->yieldContent('og_type') ?: 'website');
        $seoOgImage = $__env->hasSection('og_image')
            ? trim($__env->yieldContent('og_image'))
            : \App\Support\Seo::defaultOgImage();
        $seoRobots = trim($__env->yieldContent('meta_robots'));
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    @if ($seoRobots !== '')
        <meta name="robots" content="{{ $seoRobots }}">
    @endif
    <link rel="canonical" href="{{ $seoCanonical }}">
    <meta property="og:type" content="{{ $seoOgType }}">
    <meta property="og:site_name" content="{{ $blogSiteName }}">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:image" content="{{ $seoOgImage }}">
    @if ($seoOgType === 'article')
        @if ($__env->hasSection('article_published_time'))
            <meta property="article:published_time" content="{{ trim($__env->yieldContent('article_published_time')) }}">
        @endif
        @if ($__env->hasSection('article_modified_time'))
            <meta property="article:modified_time" content="{{ trim($__env->yieldContent('article_modified_time')) }}">
        @endif
        @if ($__env->hasSection('article_author'))
            <meta property="article:author" content="{{ trim($__env->yieldContent('article_author')) }}">
        @endif
        @if ($__env->hasSection('article_section'))
            <meta property="article:section" content="{{ trim($__env->yieldContent('article_section')) }}">
        @endif
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoOgImage }}">
    <x-google-analytics />
    @stack('head')
    @stack('structured_data')
    <x-site-fonts />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-aurora min-h-screen font-sans antialiased text-slate-800 dark:text-slate-100">
    <header class="sticky top-0 z-50 border-b border-brand-red/15 bg-white/80 backdrop-blur-xl dark:border-slate-700/70 dark:bg-slate-900/80">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-4">
            <a href="{{ route('home') }}" class="group flex flex-col leading-tight">
                <x-brand-logo size="sm" />
                <span class="mt-0.5 text-xs font-medium text-brand-yellow group-hover:text-brand-green transition-colors">{{ $blogAuthor }}</span>
            </a>
            <nav class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
                <a href="{{ route('posts.index') }}" class="nav-link">Articles</a>
                <a href="{{ route('about') }}" class="nav-link">À propos</a>
                <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                <a href="{{ route('legal') }}" class="nav-link hidden sm:inline">Mentions légales</a>
                <button
                    type="button"
                    x-data="themeToggle"
                    @click="toggle()"
                    class="rounded-lg border border-brand-red/20 px-2.5 py-1.5 text-slate-600 hover:bg-brand-red/10 dark:border-slate-600 dark:text-slate-300"
                    :aria-label="dark ? 'Mode clair' : 'Mode sombre'"
                >
                    <span x-show="!dark">🌙</span>
                    <span x-show="dark" x-cloak>☀️</span>
                </button>
                @auth
                    @if (auth()->user()->est_administrateur)
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="nav-link">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-brand-red hover:text-brand-red/80 text-sm">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-neon text-xs">Inscription</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-12 border-t border-brand-green/15 bg-white/50 py-8 dark:bg-slate-900/50">
        <div class="mx-auto flex max-w-7xl flex-col items-center gap-2 px-6 text-center text-sm text-slate-600 dark:text-slate-400">
            <x-brand-logo size="sm" />
            <p>{{ $blogAuthor }} · {{ config('blog.theme') }}</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('about') }}" class="hover:text-brand-red">À propos</a>
                <a href="{{ route('contact') }}" class="hover:text-brand-yellow">Contact</a>
                <a href="{{ route('legal') }}" class="hover:text-brand-green">Mentions légales</a>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
