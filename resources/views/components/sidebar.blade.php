{{-- Sidebar Component --}}
<aside id="sidebar" class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full md:translate-x-0 transition-transform duration-300 bg-gradient-to-b from-blue-900 to-blue-800 text-white">
    <div class="flex h-full flex-col">
        <!-- Logo -->
        <div class="flex h-16 items-center justify-center border-b border-blue-700 px-4">
            <h1 class="text-xl font-bold whitespace-nowrap">SPA</h1>
            <span class="ml-2 text-xs hidden lg:inline">Sistem Penilaian Akademik</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            @yield('sidebar-navigation')
        </nav>

        <!-- User Info -->
        @auth
        <div class="border-t border-blue-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-white font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-200">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-blue-200 hover:bg-blue-700 rounded transition">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
        @endauth
    </div>
</aside>
