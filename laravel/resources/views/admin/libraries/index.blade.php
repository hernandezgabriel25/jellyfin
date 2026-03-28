@extends('layouts.admin')

@section('title', 'Libraries')
@section('page-title', 'Libraries')
@section('page-subtitle', 'Manage your media libraries')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

    {{-- Library List (3 cols) --}}
    <div class="lg:col-span-3 space-y-4">

        {{-- Section Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-slate-700">Your Libraries</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ count($libraries) }} {{ Str::plural('library', count($libraries)) }} configured</p>
            </div>
        </div>

        {{-- Empty State --}}
        @if(count($libraries) === 0)
        <div class="flex flex-col items-center justify-center py-16 px-6 bg-white rounded-2xl border border-slate-200/60 shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-jellyfin-100 to-jellyfin-50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-jellyfin-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
            </div>
            <h4 class="text-sm font-semibold text-slate-700">No libraries yet</h4>
            <p class="text-xs text-slate-400 mt-1 text-center max-w-xs">Create your first library to start organizing your media collection.</p>
        </div>
        @endif

        {{-- Library Cards --}}
        <div class="space-y-3">
            @foreach($libraries as $library)
            <div x-data="{ confirmDelete: false }" class="group card-hover bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4">
                    <a href="{{ route('admin.media.index', $library->id) }}" id="library-{{ $library->id }}" class="flex items-center gap-4 flex-1 min-w-0">
                        {{-- Library Icon --}}
                        <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center
                            {{ $library->type === 'movies' ? 'bg-amber-50 text-amber-500' : ($library->type === 'tvshows' ? 'bg-blue-50 text-blue-500' : 'bg-emerald-50 text-emerald-500') }}"
                        >
                            @if($library->type === 'movies')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                            </svg>
                            @elseif($library->type === 'tvshows')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            @else
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            @endif
                        </div>

                        {{-- Library Info --}}
                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold text-slate-800 group-hover:text-jellyfin-600 transition-colors truncate">{{ $library->name }}</h4>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider
                                    {{ $library->type === 'movies' ? 'bg-amber-50 text-amber-600' : ($library->type === 'tvshows' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600') }}">
                                    {{ $library->type }}
                                </span>
                            </div>
                        </div>
                    </a>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.media.index', $library->id) }}"
                           class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 hover:text-jellyfin-600 hover:bg-jellyfin-50 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Browse
                        </a>

                        {{-- Delete with Confirmation (Pines Modal-style) --}}
                        <button @click="confirmDelete = true" id="delete-library-{{ $library->id }}"
                                class="p-2 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                        {{-- Delete Confirmation Modal --}}
                        <template x-teleport="body">
                            <div x-show="confirmDelete" x-transition.opacity class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
                                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="confirmDelete = false"></div>
                                <div x-show="confirmDelete"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm z-10">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mb-4">
                                            <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-base font-semibold text-slate-800">Delete "{{ $library->name }}"?</h3>
                                        <p class="text-sm text-slate-500 mt-2">This will permanently remove this library and all its media items. This cannot be undone.</p>
                                    </div>
                                    <div class="flex gap-3 mt-6">
                                        <button @click="confirmDelete = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">Cancel</button>
                                        <form action="{{ route('admin.libraries.destroy', $library->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 shadow-lg shadow-red-500/25 transition-all cursor-pointer">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Add Library Form (2 cols) --}}
    <div class="lg:col-span-2">
        <div class="sticky top-6">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                {{-- Form Header --}}
                <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-jellyfin-500/5 to-cyan-500/5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-jellyfin-500 to-jellyfin-600 flex items-center justify-center shadow-lg shadow-jellyfin-500/20">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Add Library</h3>
                            <p class="text-xs text-slate-400">Create a new media collection</p>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <form action="{{ route('admin.libraries.store') }}" method="POST" class="p-6 space-y-5" id="add-library-form">
                    @csrf

                    {{-- Pines-style Text Input --}}
                    <div x-data="{ focused: false, filled: false }" class="relative">
                        <label for="library-name" class="block text-xs font-semibold text-slate-600 mb-1.5">Library Name</label>
                        <input type="text" name="name" id="library-name" required
                               @focus="focused = true" @blur="focused = false; filled = $el.value.length > 0"
                               :class="focused ? 'border-jellyfin-400 ring-4 ring-jellyfin-500/10' : 'border-slate-200'"
                               class="w-full px-4 py-2.5 rounded-xl border bg-white text-sm text-slate-800 placeholder:text-slate-300 transition-all duration-200 outline-none"
                               placeholder="e.g. My Movies">
                    </div>

                    {{-- Pines-style Select --}}
                    <div x-data="{ open: false, selected: 'movies', options: [{value: 'movies', label: 'Movies', icon: '🎬'}, {value: 'tvshows', label: 'TV Shows', icon: '📺'}, {value: 'mixed', label: 'Mixed', icon: '📦'}] }" class="relative">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Library Type</label>
                        <input type="hidden" name="type" :value="selected">
                        <button type="button" @click="open = !open" @click.outside="open = false"
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 transition-all duration-200 hover:border-slate-300 cursor-pointer"
                                :class="open ? 'border-jellyfin-400 ring-4 ring-jellyfin-500/10' : ''">
                            <span class="flex items-center gap-2">
                                <span x-text="options.find(o => o.value === selected)?.icon"></span>
                                <span x-text="options.find(o => o.value === selected)?.label"></span>
                            </span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute z-10 mt-1.5 w-full bg-white rounded-xl border border-slate-200 shadow-xl shadow-slate-200/50 py-1 overflow-hidden" x-cloak>
                            <template x-for="option in options" :key="option.value">
                                <button type="button" @click="selected = option.value; open = false"
                                        class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-jellyfin-50 hover:text-jellyfin-700 transition-colors cursor-pointer"
                                        :class="selected === option.value ? 'bg-jellyfin-50 text-jellyfin-700 font-medium' : ''">
                                    <span x-text="option.icon"></span>
                                    <span x-text="option.label"></span>
                                    <svg x-show="selected === option.value" class="w-4 h-4 ml-auto text-jellyfin-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="create-library-btn"
                            class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-jellyfin-500 to-jellyfin-600 hover:from-jellyfin-600 hover:to-jellyfin-700 shadow-lg shadow-jellyfin-500/25 hover:shadow-jellyfin-500/40 transition-all duration-200 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Library
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
