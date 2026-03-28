@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users')
@section('page-subtitle', 'Manage user accounts and access')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

    {{-- User List (3 cols) --}}
    <div class="lg:col-span-3 space-y-4">

        {{-- Section Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-slate-700">Active Users</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ count($users) }} {{ Str::plural('user', count($users)) }} registered</p>
            </div>
        </div>

        {{-- Empty State --}}
        @if(count($users) === 0)
        <div class="flex flex-col items-center justify-center py-16 px-6 bg-white rounded-2xl border border-slate-200/60 shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <h4 class="text-sm font-semibold text-slate-700">No users found</h4>
            <p class="text-xs text-slate-400 mt-1 text-center max-w-xs">Create your first user account to get started.</p>
        </div>
        @endif

        {{-- User Table --}}
        @if(count($users) > 0)
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full" id="users-table">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-400">User</th>
                            <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-400">UUID</th>
                            <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-wider text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($users as $user)
                        <tr x-data="{ confirmDelete: false }" class="group hover:bg-slate-50/50 transition-colors" id="user-row-{{ $user->id }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar --}}
                                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gradient-to-br from-jellyfin-400 to-cyan-400 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div x-data="{ copied: false }" class="flex items-center gap-1.5">
                                    <code class="text-xs text-slate-400 font-mono bg-slate-50 px-2 py-1 rounded-md truncate max-w-[180px]">{{ $user->uuid }}</code>
                                    <button @click="navigator.clipboard.writeText('{{ $user->uuid }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                            class="p-1 rounded-md text-slate-300 hover:text-jellyfin-500 hover:bg-jellyfin-50 transition-all cursor-pointer"
                                            x-tooltip.top="copied ? 'Copied!' : 'Copy UUID'">
                                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <svg x-show="copied" class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <button @click="confirmDelete = true" id="delete-user-{{ $user->id }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
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
                                             class="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm z-10 text-left">
                                            <div class="flex flex-col items-center text-center">
                                                <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mb-4">
                                                    <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                    </svg>
                                                </div>
                                                <h3 class="text-base font-semibold text-slate-800">Delete user "{{ $user->name }}"?</h3>
                                                <p class="text-sm text-slate-500 mt-2">This action is permanent and cannot be undone.</p>
                                            </div>
                                            <div class="flex gap-3 mt-6">
                                                <button @click="confirmDelete = false" type="button" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">Cancel</button>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 shadow-lg shadow-red-500/25 transition-all cursor-pointer">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Add User Form (2 cols) --}}
    <div class="lg:col-span-2">
        <div class="sticky top-6">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                {{-- Form Header --}}
                <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-blue-500/5 to-cyan-500/5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Add User</h3>
                            <p class="text-xs text-slate-400">Create a new user account</p>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-5" id="add-user-form">
                    @csrf

                    {{-- Name Input --}}
                    <div x-data="{ focused: false }" class="relative">
                        <label for="user-name" class="block text-xs font-semibold text-slate-600 mb-1.5">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="name" id="user-name" required
                                   @focus="focused = true" @blur="focused = false"
                                   :class="focused ? 'border-blue-400 ring-4 ring-blue-500/10' : 'border-slate-200'"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border bg-white text-sm text-slate-800 placeholder:text-slate-300 transition-all duration-200 outline-none"
                                   placeholder="Enter username">
                        </div>
                    </div>

                    {{-- Password Input --}}
                    <div x-data="{ focused: false, showPassword: false }" class="relative">
                        <label for="user-password" class="block text-xs font-semibold text-slate-600 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="user-password" required
                                   @focus="focused = true" @blur="focused = false"
                                   :class="focused ? 'border-blue-400 ring-4 ring-blue-500/10' : 'border-slate-200'"
                                   class="w-full pl-10 pr-12 py-2.5 rounded-xl border bg-white text-sm text-slate-800 placeholder:text-slate-300 transition-all duration-200 outline-none"
                                   placeholder="Enter password">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-300 hover:text-slate-500 transition-colors cursor-pointer">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="create-user-btn"
                            class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transition-all duration-200 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Create User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
