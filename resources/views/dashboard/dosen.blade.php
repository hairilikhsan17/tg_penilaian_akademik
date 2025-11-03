<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SPA Sistem Penilaian Akademik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Left side: Logo & Menu Toggle -->
                <div class="flex items-center space-x-4">
                    <button id="sidebarToggle" class="text-gray-600 hover:text-gray-900 focus:outline-none lg:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-2 rounded-lg">
                            <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">SPA Sistem Penilaian Akademik</h1>
                            <p class="text-xs text-gray-500">Dashboard Dosen</p>
                        </div>
                    </div>
                </div>

                <!-- Right side: User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                            @php
                                $dosen = auth()->user()->dosen ?? null;
                                $fotoProfil = $dosen && $dosen->foto_profil ? Storage::url('foto_profil/' . $dosen->foto_profil) : null;
                            @endphp
                            @if($fotoProfil)
                                <img src="{{ $fotoProfil }}"
                                     alt="Foto Profil"
                                     class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                            @else
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr(auth()->user()->name ?? 'D', 0, 1) }}
                                </div>
                            @endif
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name ?? 'Dosen' }}</p>
                                <p class="text-xs text-gray-500">{{ $dosen->nip ?? 'NIP' }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
                            <a href="{{ route('dosen.profil') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Profil Saya
                            </a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') ?? '#' }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-black text-white pt-20 z-40 sidebar-transition transform lg:translate-x-0 -translate-x-full overflow-y-auto">
        <div class="px-4 py-6 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dosen.dashboard') ?? '#' }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-800 transition-colors {{ request()->routeIs('dosen.dashboard') ? 'bg-blue-600' : '' }}">
                <i class="fas fa-home text-lg w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Kelola Data Mahasiswa -->
            <a href="{{ route('mahasiswas.index') ?? '#' }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-800 transition-colors {{ request()->routeIs('mahasiswas.*') ? 'bg-blue-600' : '' }}">
                <i class="fas fa-user-graduate text-lg w-5"></i>
                <span class="font-medium">Kelola Data Mahasiswa</span>
            </a>

            <!-- Kelola Mata Kuliah -->
            <a href="{{ route('matakuliahs.index') ?? '#' }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-800 transition-colors {{ request()->routeIs('matakuliahs.*') ? 'bg-blue-600' : '' }}">
                <i class="fas fa-book text-lg w-5"></i>
                <span class="font-medium">Kelola Mata Kuliah</span>
            </a>

            <!-- Komponen Penilaian -->
            <a href="{{ route('komponen.index') ?? '#' }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-800 transition-colors {{ request()->routeIs('komponen.*') ? 'bg-blue-600' : '' }}">
                <i class="fas fa-clipboard-check text-lg w-5"></i>
                <span class="font-medium">Komponen Penilaian</span>
            </a>

            <!-- Input Nilai -->
            <a href="{{ route('nilai.list') ?? '#' }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-800 transition-colors {{ request()->routeIs('nilai.*') ? 'bg-blue-600' : '' }}">
                <i class="fas fa-pen-to-square text-lg w-5"></i>
                <span class="font-medium">Input Nilai</span>
            </a>

            <!-- Laporan Nilai -->
            <a href="{{ route('dosen.laporan') ?? '#' }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-800 transition-colors {{ request()->routeIs('dosen.laporan*') ? 'bg-blue-600' : '' }}">
                <i class="fas fa-file-alt text-lg w-5"></i>
                <span class="font-medium">Laporan Nilai</span>
            </a>

            <hr class="my-4 border-gray-700">

            <!-- Profil Dosen -->
            <a href="{{ route('dosen.profil') ?? '#' }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-800 transition-colors {{ request()->routeIs('dosen.profil*') ? 'bg-blue-600' : '' }}">
                <i class="fas fa-user-circle text-lg w-5"></i>
                <span class="font-medium">Profil Saya</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') ?? '#' }}">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-red-600 transition-colors text-left">
                    <i class="fas fa-sign-out-alt text-lg w-5"></i>
                    <span class="font-medium">Dosen Keluar</span>
                </button>
            </form>
        </div>

        <!-- Info Dosen Card -->
        <div class="mx-4 my-6 bg-gray-900 bg-opacity-50 rounded-lg p-4 border border-gray-700">
            <div class="flex items-center space-x-3 mb-3">
                @if($fotoProfil ?? null)
                    <img src="{{ $fotoProfil }}" alt="Foto Profil" class="w-12 h-12 rounded-full object-cover border-2 border-gray-400">
                @else
                    <div class="w-12 h-12 bg-gradient-to-r from-gray-600 to-gray-700 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(auth()->user()->name ?? 'D', 0, 1) }}
                    </div>
                @endif
                <div class="flex-1">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Dosen' }}</p>
                    <p class="text-xs text-gray-400">{{ $dosen->nip ?? 'NIP' }}</p>
                </div>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-gray-400">Mata Kuliah:</span>
                    <span class="text-white font-semibold">{{ $dosen ? $dosen->matakuliahs()->count() : 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Sidebar -->
        <div class="px-4 py-4 border-t border-gray-700">
            <div class="text-xs text-gray-400 text-center">
                <p> 2025 SIAKAD</p>
                <p class="mt-1">Version 1.0</p>
            </div>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black opacity-50 z-30 hidden lg:hidden"></div>

    <!-- Main Content -->
    <main class="lg:ml-64 pt-20 min-h-screen">
        <div class="p-6">
            <!-- Page Title -->
            @if(View::hasSection('page-title'))
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">@yield('page-title')</h2>
                @if(View::hasSection('page-description'))
                <p class="text-gray-600 text-sm mt-1">@yield('page-description')</p>
                @endif
            </div>
            @endif

            <!-- Breadcrumb -->
            @if(View::hasSection('breadcrumb'))
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('dosen.dashboard') ?? '#' }}" class="hover:text-blue-600"><i class="fas fa-home"></i></a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
            @endif

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if(isset($errors) && $errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-red-700 font-medium mb-2">Terdapat kesalahan:</p>
                        <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Content -->
            @yield('content')
        </div>
    </main>

    <!-- Alpine.js for dropdowns -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Sidebar Toggle Script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        }

        sidebarToggle?.addEventListener('click', toggleSidebar);
        sidebarOverlay?.addEventListener('click', toggleSidebar);

        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    @yield('scripts')
</body>
</html>
