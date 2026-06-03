<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('meta_title', config('app.name', "Harry's Blog"))</title>
    <meta name="description" content="@yield('meta_description', 'Blog personnel avec articles, catégories, notes et avis.')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ config('app.name', "Harry's Blog") }}">
    <meta property="og:title" content="@yield('meta_title', config('app.name', "Harry's Blog"))">
    <meta property="og:description" content="@yield('meta_description', 'Blog personnel avec articles, catégories, notes et avis.')">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="@yield('og_image')">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-aurora min-h-screen font-sans antialiased text-slate-900 dark:text-slate-100">
    <header class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/70 backdrop-blur-xl dark:border-slate-700/70 dark:bg-slate-900/70">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="text-lg font-bold tracking-wide">
                <span class="bg-gradient-to-r from-cyan-500 via-indigo-500 to-fuchsia-500 bg-clip-text text-transparent">{{ config('app.name', "Harry's Blog") }}</span>
            </a>
            <nav class="flex items-center gap-4 text-sm">
                <button
                    type="button"
                    x-data="themeToggle"
                    @click="toggle()"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-slate-600 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800"
                    :aria-label="dark ? 'Activer le mode clair' : 'Activer le mode sombre'"
                >
                    <span x-show="!dark">🌙</span>
                    <span x-show="dark" x-cloak>☀️</span>
                </button>
                <a href="{{ route('posts.index') }}" class="text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Articles</a>
                @auth
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Admin</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-rose-500 hover:text-rose-600">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-neon text-xs">Inscription</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
