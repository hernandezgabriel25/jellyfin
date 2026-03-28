@extends('layouts.admin')

@section('title', ($parent ? $parent->name : $library->name) . ' — Media')
@section('page-title', $parent ? $parent->name : $library->name)
@section('page-subtitle', 'Browse and manage media items')

@section('content')

{{-- Breadcrumbs (Pines-style) --}}
<nav class="mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center gap-1.5 text-sm">
        <li>
            <a href="{{ route('admin.libraries.index') }}" class="flex items-center gap-1.5 text-slate-400 hover:text-jellyfin-600 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
                <span class="font-medium">Libraries</span>
            </a>
        </li>
        <li>
            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </li>
        <li>
            <a href="{{ route('admin.media.index', $library->id) }}"
               class="{{ $parent ? 'text-slate-400 hover:text-jellyfin-600' : 'text-slate-700 font-semibold' }} transition-colors">
                {{ $library->name }}
            </a>
        </li>
        @if($parent)
        <li>
            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </li>
        <li>
            <span class="text-slate-700 font-semibold">{{ $parent->name }}</span>
        </li>
        @endif
    </ol>
</nav>

@if(!$parent)
{{-- ===== Top-Level Media View (Movies/Series) ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-4 gap-8">

    {{-- Search Panel --}}
    <div class="xl:col-span-1 order-2 xl:order-1">
        <div class="sticky top-6 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                {{-- Search Header --}}
                <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-amber-500/5 to-orange-500/5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Search TMDb</h3>
                            <p class="text-[11px] text-slate-400">Find and add media</p>
                        </div>
                    </div>
                </div>

                {{-- Search Input --}}
                <div x-data="{
                    query: '',
                    results: [],
                    loading: false,
                    searched: false,
                    @if($library->type === 'mixed')
                    mediaType: 'movie',
                    @else
                    mediaType: '{{ $library->type == 'tvshows' ? 'tv' : 'movie' }}',
                    @endif
                    async search() {
                        if (!this.query.trim()) return;
                        this.loading = true;
                        this.searched = true;
                        try {
                            const res = await fetch(`/admin/media/search?query=${encodeURIComponent(this.query)}&type=${this.mediaType}`);
                            this.results = await res.json();
                        } catch(e) { this.results = []; }
                        this.loading = false;
                    }
                }" class="p-4">
                    {{-- Mixed library type toggle --}}
                    @if($library->type === 'mixed')
                    <div class="flex gap-1.5 mb-3 p-1 rounded-xl bg-slate-100">
                        <button type="button" @click="mediaType = 'movie'; results = []; searched = false;"
                                :class="mediaType === 'movie' ? 'bg-white shadow text-amber-600 font-semibold' : 'text-slate-400 hover:text-slate-600'"
                                class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-xs transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>
                            Movie
                        </button>
                        <button type="button" @click="mediaType = 'tv'; results = []; searched = false;"
                                :class="mediaType === 'tv' ? 'bg-white shadow text-blue-600 font-semibold' : 'text-slate-400 hover:text-slate-600'"
                                class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-xs transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            TV Show
                        </button>
                    </div>
                    @endif

                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" x-model="query" @keydown.enter="search()" id="search-query"
                                   class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 placeholder:text-slate-300 focus:border-amber-400 focus:ring-4 focus:ring-amber-500/10 transition-all outline-none"
                                   placeholder="Search...">
                        </div>
                        <button @click="search()" id="search-btn"
                                class="flex-shrink-0 px-3 py-2 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-white hover:from-amber-500 hover:to-orange-600 shadow-lg shadow-amber-500/20 transition-all cursor-pointer"
                                :disabled="loading">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" x-cloak>
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Search Results --}}
                    <div class="mt-3 space-y-1.5 max-h-[400px] overflow-y-auto" id="search-results">
                        <template x-for="res in results" :key="res.id">
                            <form :action="'{{ route('admin.media.store', $library->id) }}'" method="POST">
                                @csrf
                                <input type="hidden" name="tmdb_id" :value="res.id">
                                <input type="hidden" name="type" :value="mediaType === 'tv' ? 'series' : 'movie'">
                                <button type="submit"
                                        class="w-full flex items-center gap-3 p-2.5 rounded-xl text-left hover:bg-amber-50 transition-all group cursor-pointer border border-transparent hover:border-amber-200">
                                    <div class="flex-shrink-0 w-10 h-14 rounded-lg overflow-hidden bg-slate-100">
                                        <img x-show="res.poster_path" :src="'https://image.tmdb.org/t/p/w92' + res.poster_path" class="w-full h-full object-cover" :alt="res.title || res.name">
                                        <div x-show="!res.poster_path" class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-slate-700 group-hover:text-amber-700 truncate" x-text="res.title || res.name"></p>
                                        <p class="text-[11px] text-slate-400" x-text="(res.release_date || res.first_air_date || '').substring(0, 4)"></p>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 group-hover:text-amber-500 transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </form>
                        </template>

                        {{-- Empty Search State --}}
                        <div x-show="searched && results.length === 0 && !loading" class="flex flex-col items-center py-8 text-center">
                            <svg class="w-10 h-10 text-slate-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p class="text-xs text-slate-400">No results found</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Media Grid --}}
    <div class="xl:col-span-3 order-1 xl:order-2">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-semibold text-slate-700">Media Items</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ count($items) }} {{ Str::plural('item', count($items)) }}</p>
            </div>
        </div>

        @if(count($items) === 0)
        <div class="flex flex-col items-center justify-center py-20 px-6 bg-white rounded-2xl border border-slate-200/60 shadow-sm">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-50 flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
            </div>
            <h4 class="text-sm font-semibold text-slate-700">No media yet</h4>
            <p class="text-xs text-slate-400 mt-1.5 text-center max-w-sm">Use the search panel to find media on TMDb and add it to this library.</p>
        </div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($items as $item)
            <div class="group card-hover" id="media-item-{{ $item->id }}" x-data="{ confirmDelete: false }">
                <div class="bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden">
                    {{-- Poster --}}
                    <a href="{{ $item->type == 'series' ? route('admin.media.index', [$library->id, $item->id]) : route('admin.media.edit', $item->id) }}"
                       class="block relative aspect-[2/3] bg-slate-100 overflow-hidden">
                        @if($item->poster_path)
                        <img src="https://image.tmdb.org/t/p/w300{{ $item->poster_path }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             alt="{{ $item->name }}" loading="lazy">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                            <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif

                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end p-3">
                            <div class="flex gap-1.5 w-full">
                                @if($item->type == 'series')
                                <span class="flex-1 flex items-center justify-center gap-1 px-2 py-1.5 rounded-lg bg-white/20 backdrop-blur-sm text-white text-[11px] font-medium">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Seasons
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Type Badge --}}
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-0.5 rounded-md bg-black/50 backdrop-blur-sm text-white text-[10px] font-semibold uppercase tracking-wider">
                                {{ $item->type }}
                            </span>
                        </div>
                    </a>

                    {{-- Info --}}
                    <div class="p-3">
                        <h4 class="text-xs font-semibold text-slate-800 truncate group-hover:text-jellyfin-600 transition-colors">{{ $item->name }}</h4>
                        <div class="flex items-center gap-2 mt-2">
                            @if($item->type == 'series')
                            <a href="{{ route('admin.media.index', [$library->id, $item->id]) }}"
                               class="flex-1 flex items-center justify-center gap-1 px-2 py-1.5 rounded-lg text-[11px] font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                Seasons
                            </a>
                            @endif
                            <a href="{{ route('admin.media.edit', $item->id) }}"
                               class="flex-1 flex items-center justify-center gap-1 px-2 py-1.5 rounded-lg text-[11px] font-medium text-amber-600 bg-amber-50 hover:bg-amber-100 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                Edit
                            </a>
                            <button @click="confirmDelete = true"
                                    class="flex items-center justify-center p-1.5 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-colors cursor-pointer"
                                    title="Delete">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

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
                                <h3 class="text-base font-semibold text-slate-800">Delete "{{ $item->name }}"?</h3>
                                <p class="text-sm text-slate-500 mt-2">
                                    @if($item->type == 'series')
                                    This will permanently remove the series along with all its seasons and episodes.
                                    @else
                                    This will permanently remove this item. This cannot be undone.
                                    @endif
                                </p>
                            </div>
                            <div class="flex gap-3 mt-6">
                                <button @click="confirmDelete = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">Cancel</button>
                                <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 shadow-lg shadow-red-500/25 transition-all cursor-pointer">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            @endforeach
        </div>
    </div>
</div>

@else
{{-- ===== Child Items View (Seasons / Episodes) ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

    {{-- Add Child Form --}}
    <div class="lg:col-span-2 order-2 lg:order-1">
        <div class="sticky top-6">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-cyan-500/5 to-blue-500/5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Add {{ $parent->type == 'series' ? 'Season' : 'Episode' }}</h3>
                            <p class="text-[11px] text-slate-400">Add to {{ $parent->name }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.media.store', [$library->id, $parent->id]) }}" method="POST" class="p-5 space-y-4" id="add-child-form">
                    @csrf
                    <input type="hidden" name="type" value="{{ $parent->type == 'series' ? 'season' : 'episode' }}">

                    {{-- Name Input --}}
                    <div x-data="{ focused: false }">
                        <label for="child-name" class="block text-xs font-semibold text-slate-600 mb-1.5">Name</label>
                        <input type="text" name="name" id="child-name" required
                               @focus="focused = true" @blur="focused = false"
                               :class="focused ? 'border-cyan-400 ring-4 ring-cyan-500/10' : 'border-slate-200'"
                               class="w-full px-4 py-2.5 rounded-xl border bg-white text-sm text-slate-800 placeholder:text-slate-300 transition-all duration-200 outline-none"
                               placeholder="{{ $parent->type == 'series' ? 'Season 1' : 'Episode 1' }}">
                    </div>

                    {{-- Number Input --}}
                    <div x-data="{ focused: false }">
                        <label for="child-number" class="block text-xs font-semibold text-slate-600 mb-1.5">Number</label>
                        <input type="number" name="index_number" id="child-number" required
                               @focus="focused = true" @blur="focused = false"
                               :class="focused ? 'border-cyan-400 ring-4 ring-cyan-500/10' : 'border-slate-200'"
                               class="w-full px-4 py-2.5 rounded-xl border bg-white text-sm text-slate-800 placeholder:text-slate-300 transition-all duration-200 outline-none"
                               placeholder="1" min="0">
                    </div>

                    <button type="submit" id="add-child-btn"
                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 shadow-lg shadow-cyan-500/25 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add {{ $parent->type == 'series' ? 'Season' : 'Episode' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Items List --}}
    <div class="lg:col-span-3 order-1 lg:order-2 space-y-4">
        <div>
            <h3 class="text-base font-semibold text-slate-700">{{ $parent->type == 'series' ? 'Seasons' : 'Episodes' }}</h3>
            <p class="text-xs text-slate-400 mt-0.5">{{ count($items) }} {{ Str::plural('item', count($items)) }}</p>
        </div>

        @if(count($items) === 0)
        <div class="flex flex-col items-center justify-center py-16 px-6 bg-white rounded-2xl border border-slate-200/60 shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-100 to-cyan-50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h4 class="text-sm font-semibold text-slate-700">No items yet</h4>
            <p class="text-xs text-slate-400 mt-1 text-center max-w-xs">Use the form to add {{ $parent->type == 'series' ? 'seasons' : 'episodes' }}.</p>
        </div>
        @endif

        <div class="space-y-2">
            @foreach($items as $item)
            <div class="group card-hover bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden" id="child-item-{{ $item->id }}" x-data="{ confirmDelete: false }">
                <div class="flex items-center justify-between px-5 py-4">
                    <div class="flex items-center gap-4">
                        {{-- Number Badge --}}
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-slate-100 to-slate-50 flex items-center justify-center">
                            <span class="text-sm font-bold text-slate-600">{{ $item->index_number }}</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-800">{{ $item->name }}</h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider bg-slate-50 text-slate-400 mt-1">
                                {{ $item->type }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($item->type == 'season')
                        <a href="{{ route('admin.media.index', [$library->id, $item->id]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                            Episodes
                        </a>
                        @endif
                        <a href="{{ route('admin.media.edit', $item->id) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-amber-600 bg-amber-50 hover:bg-amber-100 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Edit
                        </a>
                        <button @click="confirmDelete = true"
                                class="p-2 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all cursor-pointer"
                                title="Delete">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>

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
                                <h3 class="text-base font-semibold text-slate-800">Delete "{{ $item->name }}"?</h3>
                                <p class="text-sm text-slate-500 mt-2">
                                    @if($item->type == 'season')
                                    This will permanently remove this season and all its episodes.
                                    @else
                                    This will permanently remove this episode. This cannot be undone.
                                    @endif
                                </p>
                            </div>
                            <div class="flex gap-3 mt-6">
                                <button @click="confirmDelete = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">Cancel</button>
                                <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 shadow-lg shadow-red-500/25 transition-all cursor-pointer">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
