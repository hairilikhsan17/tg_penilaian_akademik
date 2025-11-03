@extends('dashboard.dosen')

@section('title', 'Input Nilai Mahasiswa')

@section('page-title', 'Input Nilai Mahasiswa')
@section('page-description', 'Pilih mata kuliah untuk input nilai mahasiswa berdasarkan semester dan komponen penilaian')

@section('breadcrumb')
    <li><span class="text-gray-500">/</span></li>
    <li class="text-gray-700">Input Nilai</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Total Mata Kuliah</p>
                    <p class="text-3xl font-bold">{{ $totalMatakuliah }}</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-book text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">Siap Input Nilai</p>
                    <p class="text-3xl font-bold">{{ $siapInputNilai }}</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm mb-1">Belum Siap</p>
                    <p class="text-3xl font-bold">{{ $belumSiap }}</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Box -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="GET" action="{{ route('nilai.list') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari berdasarkan Kode atau Nama Mata Kuliah..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <select name="semester" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Semester</option>
                @foreach($semesters as $semester)
                    <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                        Semester {{ $semester }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="siap" {{ request('status') == 'siap' ? 'selected' : '' }}>Siap Input Nilai</option>
                <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Ada Komponen</option>
                <option value="belum_lengkap" {{ request('status') == 'belum_lengkap' ? 'selected' : '' }}>Komponen Belum Lengkap</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            @if(request('search') || request('semester') || request('status'))
                <a href="{{ route('nilai.list') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Mata Kuliah -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kode MK</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Mata Kuliah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Komponen Penilaian</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($matakuliahs as $index => $matakuliah)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ ($matakuliahs->currentPage() - 1) * $matakuliahs->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">{{ $matakuliah->kode_mk }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $matakuliah->nama_mk }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $matakuliah->sks }} SKS</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                Semester {{ $matakuliah->semester }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($matakuliah->komponenPenilaian)
                                <div class="flex flex-col space-y-1 text-xs">
                                    @if($matakuliah->komponenPenilaian->kehadiran > 0)
                                    <div class="flex items-center justify-center space-x-2">
                                        <span class="text-gray-600">Hadir:</span>
                                        <span class="font-semibold text-blue-600">{{ $matakuliah->komponenPenilaian->kehadiran }}%</span>
                                    </div>
                                    @endif
                                    @if($matakuliah->komponenPenilaian->tugas > 0)
                                    <div class="flex items-center justify-center space-x-2">
                                        <span class="text-gray-600">Tugas:</span>
                                        <span class="font-semibold text-green-600">{{ $matakuliah->komponenPenilaian->tugas }}%</span>
                                    </div>
                                    @endif
                                    @if($matakuliah->komponenPenilaian->kuis > 0)
                                    <div class="flex items-center justify-center space-x-2">
                                        <span class="text-gray-600">Quiz:</span>
                                        <span class="font-semibold text-purple-600">{{ $matakuliah->komponenPenilaian->kuis }}%</span>
                                    </div>
                                    @endif
                                    @if($matakuliah->komponenPenilaian->project > 0)
                                    <div class="flex items-center justify-center space-x-2">
                                        <span class="text-gray-600">Project:</span>
                                        <span class="font-semibold text-indigo-600">{{ $matakuliah->komponenPenilaian->project }}%</span>
                                    </div>
                                    @endif
                                    @if($matakuliah->komponenPenilaian->uts > 0)
                                    <div class="flex items-center justify-center space-x-2">
                                        <span class="text-gray-600">UTS:</span>
                                        <span class="font-semibold text-orange-600">{{ $matakuliah->komponenPenilaian->uts }}%</span>
                                    </div>
                                    @endif
                                    @if($matakuliah->komponenPenilaian->uas > 0)
                                    <div class="flex items-center justify-center space-x-2">
                                        <span class="text-gray-600">UAS:</span>
                                        <span class="font-semibold text-red-600">{{ $matakuliah->komponenPenilaian->uas }}%</span>
                                    </div>
                                    @endif
                                    <div class="pt-1 border-t border-gray-200 mt-1">
                                        <span class="font-bold text-gray-800">Total: {{ $matakuliah->komponenPenilaian->total }}%</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($matakuliah->komponenPenilaian && $matakuliah->komponenPenilaian->total == 100)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Siap
                                </span>
                            @elseif($matakuliah->komponenPenilaian && $matakuliah->komponenPenilaian->total != 100)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Belum Lengkap
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Belum Ada
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            @if($matakuliah->komponenPenilaian && $matakuliah->komponenPenilaian->total == 100)
                                <a href="{{ route('nilai.index', $matakuliah) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow hover:shadow-lg text-sm">
                                    <i class="fas fa-pen-to-square mr-2"></i>
                                    Input Nilai
                                </a>
                            @else
                                <a href="{{ route('komponen.create', $matakuliah) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all shadow text-sm">
                                    <i class="fas fa-clipboard-check mr-2"></i>
                                    Atur Komponen
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-book text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Tidak ada data mata kuliah</p>
                                <p class="text-sm mt-2">
                                    @if(request('search') || request('semester') || request('status'))
                                        Tidak ditemukan mata kuliah dengan filter yang dipilih
                                    @else
                                        Belum ada mata kuliah terdaftar.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($matakuliahs->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $matakuliahs->links() }}
        </div>
        @endif
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
            <div>
                <p class="text-sm text-blue-700">
                    <strong>Penting:</strong> Hanya mata kuliah dengan komponen penilaian lengkap (total = 100%) yang dapat digunakan untuk input nilai. 
                    Total nilai akan dihitung otomatis berdasarkan bobot komponen penilaian yang telah ditentukan.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection




