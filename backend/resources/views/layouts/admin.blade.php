<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin') Phone Collection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#1a2e5a',
                        'navy-dark': '#132244',
                        cyan: { DEFAULT: '#00d4d4', dark: '#00a8a8' },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 h-screen overflow-hidden">

<div class="h-screen">
    {{-- ── Sidebar ── --}}
    <aside class="fixed inset-y-0 left-0 w-48 bg-[#243b83] flex flex-col z-30">

        {{-- Nav --}}
        <nav class="flex-1 pt-8 space-y-1">
            @php
                // Helper untuk merapikan class sidebar agar tidak terlalu panjang di bawah
                $baseLinkClass = "flex items-center w-full px-[18px] py-3 text-white/90 text-base font-bold tracking-wide uppercase transition-all duration-200 ease-in-out rounded-r-xl hover:bg-white/10 hover:text-white";
                $activeClass = "bg-[#5c9da4] text-white";
            @endphp

            <a href="{{ route('admin.dashboard') }}"
               class="{{ $baseLinkClass }} {{ request()->routeIs('admin.dashboard') ? $activeClass : '' }}">
                DASHBOARD
            </a>
            
            <a href="{{ route('admin.rak.index') }}"
               class="{{ $baseLinkClass }} {{ request()->routeIs('admin.rak.*') ? $activeClass : '' }}">
                KELOLA RAK
            </a>
            
            <a href="{{ route('admin.user.index') }}"
               class="{{ $baseLinkClass }} {{ request()->routeIs('admin.user.*') ? $activeClass : '' }}">
                KELOLA USER
            </a>
            
            <a href="{{ route('admin.catat.index') }}"
               class="{{ $baseLinkClass }} {{ request()->routeIs('admin.catat.*') ? $activeClass : '' }}">
                CATAT
            </a>
        </nav>

        {{-- Logout --}}
        <div class="bg-[#c1221b] mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center justify-start w-full text-white px-[18px] py-3 transition-colors duration-200 hover:bg-black/15"
                    title="Keluar"
                    aria-label="Keluar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main Content ── --}}
    <div class="ml-48 h-screen flex flex-col overflow-hidden">
        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between shadow-sm">
            <h1 class="text-sm font-semibold text-gray-700">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#1a2e5a] flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-800">{{ auth()->user()->FullName ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->Role ?? 'admin') }}</p>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-6 pb-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>