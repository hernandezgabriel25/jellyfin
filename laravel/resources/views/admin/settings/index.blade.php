@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Configure your Jellyfin server')

@section('content')
<div class="max-w-2xl">

    {{-- Settings Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-500/5 to-slate-400/5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center shadow-lg shadow-slate-500/20">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Server Configuration</h3>
                    <p class="text-xs text-slate-400">API keys and integrations</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6 space-y-6" id="settings-form">
            @csrf

            {{-- TMDb Section --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-md bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">TMDb Integration</h4>
                        <p class="text-xs text-slate-400">The Movie Database API for metadata</p>
                    </div>
                </div>

                {{-- API Key Input --}}
                <div x-data="{ focused: false, showKey: false }" class="relative">
                    <label for="tmdb-api-key" class="block text-xs font-semibold text-slate-600 mb-1.5">API Key</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <input :type="showKey ? 'text' : 'password'" name="tmdb_api_key" id="tmdb-api-key"
                               value="{{ $tmdb_api_key }}"
                               @focus="focused = true" @blur="focused = false"
                               :class="focused ? 'border-jellyfin-400 ring-4 ring-jellyfin-500/10' : 'border-slate-200'"
                               class="w-full pl-10 pr-12 py-2.5 rounded-xl border bg-white text-sm text-slate-800 font-mono placeholder:text-slate-300 transition-all duration-200 outline-none"
                               placeholder="Enter your TMDb API key">
                        <button type="button" @click="showKey = !showKey"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-300 hover:text-slate-500 transition-colors cursor-pointer">
                            <svg x-show="!showKey" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showKey" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1.5 text-[11px] text-slate-400">
                        Get your API key from
                        <a href="https://www.themoviedb.org/settings/api" target="_blank" rel="noopener"
                           class="text-jellyfin-500 hover:text-jellyfin-600 font-medium transition-colors">themoviedb.org</a>
                    </p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                <button type="submit" id="save-settings-btn"
                        class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-jellyfin-500 to-jellyfin-600 hover:from-jellyfin-600 hover:to-jellyfin-700 shadow-lg shadow-jellyfin-500/25 hover:shadow-jellyfin-500/40 transition-all duration-200 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
