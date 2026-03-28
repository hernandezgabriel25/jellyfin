<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Jellyfin Laravel Admin Dashboard - Manage your media server">
    <title>@yield('title', 'Dashboard') — Jellyfin Admin</title>

    {{-- Pines UI Dependencies: Alpine.js + Tailwind CSS --}}
    <style>[x-cloak]{display:none}</style>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        jellyfin: {
                            50:  '#f0f0ff',
                            100: '#e0e0ff',
                            200: '#c4b5fd',
                            300: '#a78bfa',
                            400: '#8b5cf6',
                            500: '#7c3aed',
                            600: '#6d28d9',
                            700: '#5b21b6',
                            800: '#4c1d95',
                            900: '#2e1065',
                        },
                        surface: {
                            50:  '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            700: '#1e293b',
                            800: '#0f172a',
                            900: '#020617',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .sidebar-link {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-link:hover {
            transform: translateX(4px);
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.15), rgba(139, 92, 246, 0.08));
            border-left: 3px solid #8b5cf6;
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }
        .gradient-border {
            background: linear-gradient(135deg, #7c3aed, #06b6d4);
            padding: 1px;
            border-radius: 0.75rem;
        }
        .gradient-border > * {
            border-radius: calc(0.75rem - 1px);
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0); opacity: 1; }
        }
        .toast-enter {
            animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes fadeInUp {
            from { transform: translateY(10px); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }
        .fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-effect {
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
        }
    </style>
</head>
<body class="h-full bg-surface-50 text-slate-800 antialiased">

    <div x-data="{ sidebarOpen: true, mobileMenuOpen: false }" class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="hidden lg:flex lg:flex-col bg-surface-900 text-white transition-all duration-300 ease-in-out relative z-20"
        >
            {{-- Logo / Brand --}}
            <div class="flex items-center gap-3 px-5 py-6 border-b border-white/5">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-jellyfin-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-jellyfin-500/25">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                </div>
                <div x-show="sidebarOpen" x-transition.opacity.duration.200ms class="overflow-hidden">
                    <h1 class="text-base font-bold tracking-tight bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">Jellyfin</h1>
                    <p class="text-[10px] font-medium text-slate-500 uppercase tracking-widest">Admin Panel</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                <p x-show="sidebarOpen" x-transition.opacity class="px-3 mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Management</p>

                <a href="{{ route('admin.libraries.index') }}" id="nav-libraries"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->routeIs('admin.libraries.*') || request()->routeIs('admin.media.*') ? 'active text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity.duration.200ms>Libraries</span>
                </a>

                <a href="{{ route('admin.users.index') }}" id="nav-users"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->routeIs('admin.users.*') ? 'active text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity.duration.200ms>Users</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" id="nav-settings"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->routeIs('admin.settings.*') ? 'active text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity.duration.200ms>Settings</span>
                </a>
            </nav>

            {{-- Collapse Toggle --}}
            <div class="px-3 py-4 border-t border-white/5">
                <button @click="sidebarOpen = !sidebarOpen" id="sidebar-toggle"
                        class="flex items-center justify-center w-full px-3 py-2 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
                    <svg :class="sidebarOpen ? 'rotate-0' : 'rotate-180'" class="w-5 h-5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity.duration.200ms class="ml-3 text-sm font-medium">Collapse</span>
                </button>
            </div>
        </aside>

        {{-- Mobile Sidebar Overlay --}}
        <div x-show="mobileMenuOpen" x-transition.opacity class="fixed inset-0 z-30 bg-black/60 lg:hidden" @click="mobileMenuOpen = false" x-cloak></div>

        {{-- Mobile Sidebar --}}
        <aside
            x-show="mobileMenuOpen"
            x-transition:enter="transform transition-transform duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition-transform duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-40 w-64 bg-surface-900 text-white lg:hidden"
            x-cloak
        >
            <div class="flex items-center justify-between px-5 py-6 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-jellyfin-500 to-cyan-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base font-bold">Jellyfin</h1>
                        <p class="text-[10px] font-medium text-slate-500 uppercase tracking-widest">Admin Panel</p>
                    </div>
                </div>
                <button @click="mobileMenuOpen = false" class="text-slate-400 hover:text-white cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="px-3 py-5 space-y-1">
                <a href="{{ route('admin.libraries.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->routeIs('admin.libraries.*') || request()->routeIs('admin.media.*') ? 'active text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                    <span>Libraries</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->routeIs('admin.users.*') ? 'active text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span>Users</span>
                </a>
                <a href="{{ route('admin.settings.index') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ request()->routeIs('admin.settings.*') ? 'active text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Top Bar --}}
            <header class="flex items-center justify-between px-6 py-4 bg-white/80 glass-effect border-b border-slate-200/60 z-10">
                <div class="flex items-center gap-4">
                    <button @click="mobileMenuOpen = true" class="lg:hidden text-slate-600 hover:text-slate-900 cursor-pointer">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs text-slate-400">@yield('page-subtitle', 'Manage your Jellyfin server')</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Server Online
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">

                {{-- Toast Notification (Pines-style) --}}
                @if(session('success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-transition:enter="transform transition-all duration-400"
                     x-transition:enter-start="translate-x-full opacity-0"
                     x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transform transition-all duration-300"
                     x-transition:leave-start="translate-x-0 opacity-100"
                     x-transition:leave-end="translate-x-full opacity-0"
                     class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-4 bg-white rounded-xl shadow-2xl shadow-emerald-500/10 border border-emerald-100"
                     x-cloak
                >
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Success</p>
                        <p class="text-xs text-slate-500">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="ml-4 text-slate-300 hover:text-slate-500 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                @endif

                <div class="fade-in-up">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
