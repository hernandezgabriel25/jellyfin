@extends('layouts.admin')

@section('title', 'Edit ' . $item->name)
@section('page-title', 'Edit Media')
@section('page-subtitle', $item->name)

@section('content')

{{-- Breadcrumbs --}}
<nav class="mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center gap-1.5 text-sm">
        <li>
            <a href="{{ route('admin.libraries.index') }}" class="text-slate-400 hover:text-jellyfin-600 transition-colors font-medium">Libraries</a>
        </li>
        <li>
            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </li>
        <li>
            <span class="text-slate-700 font-semibold">{{ $item->name }}</span>
        </li>
    </ol>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Poster & Info Sidebar --}}
    <div class="lg:col-span-1">
        <div class="sticky top-6 space-y-5">
            {{-- Poster Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="aspect-[2/3] bg-slate-100 overflow-hidden">
                    @if($item->poster_path)
                    <img src="https://image.tmdb.org/t/p/w400{{ $item->poster_path }}"
                         class="w-full h-full object-cover"
                         alt="{{ $item->name }}">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                        <svg class="w-16 h-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-base font-bold text-slate-800">{{ $item->name }}</h3>
                    @if($item->overview)
                    <p class="mt-2 text-xs text-slate-500 leading-relaxed line-clamp-6">{{ $item->overview }}</p>
                    @endif
                    <div class="flex items-center gap-2 mt-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-semibold uppercase tracking-wider bg-jellyfin-50 text-jellyfin-600">
                            {{ $item->type }}
                        </span>
                        @if($item->tmdb_id)
                        <a href="https://www.themoviedb.org/{{ $item->type == 'movie' ? 'movie' : 'tv' }}/{{ $item->tmdb_id }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold uppercase tracking-wider bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors">
                            TMDb
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Form --}}
    <div class="lg:col-span-2">
        <form action="{{ route('admin.media.update', $item->id) }}" method="POST" class="space-y-6" id="edit-media-form">
            @csrf
            @method('PUT')

            {{-- Media Sources Section --}}
            <div x-data="{
                sources: [
                    @foreach($item->mediaSources as $i => $source)
                    { url: '{{ $source->url }}', audio_url: '{{ $source->audio_url }}', type: '{{ $source->type }}', existing: true },
                    @endforeach
                ],
                addSource() {
                    this.sources.push({ url: '', audio_url: '', type: 'm3u8', existing: false });
                },
                removeSource(index) {
                    this.sources.splice(index, 1);
                }
            }" class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                {{-- Section Header --}}
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-violet-500/5 to-purple-500/5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center shadow-lg shadow-violet-500/20">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800">Media Sources</h3>
                                <p class="text-[11px] text-slate-400">HLS (m3u8) or DASH (mpd) streams</p>
                            </div>
                        </div>
                        <button type="button" @click="addSource()" id="add-source-btn"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-violet-600 bg-violet-50 hover:bg-violet-100 transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Source
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-4" id="sources-container">
                    <template x-for="(source, index) in sources" :key="index">
                        <div class="relative p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3 group">
                            {{-- Remove Button --}}
                            <button type="button" @click="removeSource(index)"
                                    class="absolute top-3 right-3 p-1 rounded-md text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Source</span>
                                <span class="text-[10px] font-bold text-slate-300" x-text="'#' + (index + 1)"></span>
                            </div>

                            {{-- URL --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Stream URL</label>
                                <input type="text" :name="'urls[' + index + '][url]'" x-model="source.url"
                                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 placeholder:text-slate-300 focus:border-violet-400 focus:ring-4 focus:ring-violet-500/10 transition-all outline-none font-mono"
                                       placeholder="https://example.com/stream.m3u8">
                            </div>

                            {{-- Audio URL --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Audio URL <span class="font-normal text-slate-400">(optional)</span></label>
                                <input type="text" :name="'urls[' + index + '][audio_url]'" x-model="source.audio_url"
                                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 placeholder:text-slate-300 focus:border-violet-400 focus:ring-4 focus:ring-violet-500/10 transition-all outline-none font-mono"
                                       placeholder="https://example.com/audio.m3u8">
                            </div>

                            {{-- Type Select --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Format</label>
                                <div class="flex gap-2">
                                    <label class="flex-1 relative cursor-pointer">
                                        <input type="radio" :name="'urls[' + index + '][type]'" value="m3u8" x-model="source.type" class="peer sr-only">
                                        <div class="flex items-center justify-center px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-600 peer-checked:border-violet-400 peer-checked:bg-violet-50 peer-checked:text-violet-700 transition-all">
                                            HLS (m3u8)
                                        </div>
                                    </label>
                                    <label class="flex-1 relative cursor-pointer">
                                        <input type="radio" :name="'urls[' + index + '][type]'" value="mpd" x-model="source.type" class="peer sr-only">
                                        <div class="flex items-center justify-center px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-600 peer-checked:border-violet-400 peer-checked:bg-violet-50 peer-checked:text-violet-700 transition-all">
                                            DASH (mpd)
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <div x-show="sources.length === 0" class="flex flex-col items-center py-8 text-center">
                        <svg class="w-10 h-10 text-slate-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-slate-400">No media sources added yet</p>
                        <button type="button" @click="addSource()" class="mt-2 text-xs font-medium text-violet-600 hover:text-violet-700 cursor-pointer">+ Add your first source</button>
                    </div>
                </div>
            </div>

            {{-- Subtitles Section --}}
            <div x-data="{
                subtitles: [
                    @foreach($item->subtitles as $i => $sub)
                    { url: '{{ $sub->url }}', language: '{{ $sub->language }}', existing: true },
                    @endforeach
                ],
                addSubtitle() {
                    this.subtitles.push({ url: '', language: 'eng', existing: false });
                },
                removeSubtitle(index) {
                    this.subtitles.splice(index, 1);
                }
            }" class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                {{-- Section Header --}}
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-teal-500/5 to-emerald-500/5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800">Subtitles</h3>
                                <p class="text-[11px] text-slate-400">External subtitle tracks</p>
                            </div>
                        </div>
                        <button type="button" @click="addSubtitle()" id="add-subtitle-btn"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-teal-600 bg-teal-50 hover:bg-teal-100 transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Add Subtitle
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-4" id="subtitles-container">
                    <template x-for="(sub, index) in subtitles" :key="index">
                        <div class="relative p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3 group">
                            {{-- Remove Button --}}
                            <button type="button" @click="removeSubtitle(index)"
                                    class="absolute top-3 right-3 p-1 rounded-md text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Track</span>
                                <span class="text-[10px] font-bold text-slate-300" x-text="'#' + (index + 1)"></span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                {{-- Subtitle URL --}}
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Subtitle URL</label>
                                    <input type="text" :name="'subtitles[' + index + '][url]'" x-model="sub.url"
                                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 placeholder:text-slate-300 focus:border-teal-400 focus:ring-4 focus:ring-teal-500/10 transition-all outline-none font-mono"
                                           placeholder="https://example.com/subs.srt">
                                </div>

                                {{-- Language --}}
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Language</label>
                                    <input type="text" :name="'subtitles[' + index + '][language]'" x-model="sub.language"
                                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 placeholder:text-slate-300 focus:border-teal-400 focus:ring-4 focus:ring-teal-500/10 transition-all outline-none"
                                           placeholder="eng">
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <div x-show="subtitles.length === 0" class="flex flex-col items-center py-8 text-center">
                        <svg class="w-10 h-10 text-slate-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        <p class="text-xs text-slate-400">No subtitles added yet</p>
                        <button type="button" @click="addSubtitle()" class="mt-2 text-xs font-medium text-teal-600 hover:text-teal-700 cursor-pointer">+ Add your first subtitle</button>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex items-center justify-end">
                <button type="submit" id="save-media-btn"
                        class="flex items-center gap-2 px-8 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-jellyfin-500 to-jellyfin-600 hover:from-jellyfin-600 hover:to-jellyfin-700 shadow-xl shadow-jellyfin-500/25 hover:shadow-jellyfin-500/40 transition-all duration-200 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
