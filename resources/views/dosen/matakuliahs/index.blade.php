@extends('dashboard.dosen')

@section('title', 'Kelola Mata Kuliah')

@section('page-title', 'Kelola Mata Kuliah')
@section('page-description', 'Tambah, edit, dan hapus mata kuliah yang Anda ampu')

@section('breadcrumb')
    <li><span class="text-gray-500">/</span></li>
    <li class="text-gray-700">Mata Kuliah</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header dengan Tombol Tambah -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            
        </div>
        <a href="{{ route('matakuliahs.create') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Mata Kuliah</span>
        </a>
    </div>

    <!-- Search & Filter Box -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="GET" action="{{ route('matakuliahs.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari berdasarkan Kode atau Nama Mata Kuliah..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <select name="semester" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Semester</option>
                @for($i = 1; $i <= 14; $i++)
                    <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>
                        Semester {{ $i }}
                    </option>
                @endfor
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            @if(request('search') || request('semester'))
                <a href="{{ route('matakuliahs.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Mata Kuliah -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kode MK</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Mata Kuliah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">SKS</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Dosen Pengampu</th>
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                Semester {{ $matakuliah->semester }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="font-medium">{{ $matakuliah->sks }} SKS</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $matakuliah->dosen->name ?? 'Tidak ada' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($matakuliah->komponenPenilaian)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Siap
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Belum ada komponen
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('komponen.create', $matakuliah) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 transition-colors" 
                                   title="Komponen Penilaian">
                                    <i class="fas fa-clipboard-check"></i>
                                </a>
                                <a href="{{ route('matakuliahs.edit', $matakuliah) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('matakuliahs.destroy', $matakuliah) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah {{ $matakuliah->nama_mk }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 transition-colors" 
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-book text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Tidak ada data mata kuliah</p>
                                <p class="text-sm mt-2">
                                    @if(request('search') || request('semester'))
                                        Tidak ditemukan mata kuliah dengan filter yang dipilih
                                    @else
                                        Belum ada mata kuliah terdaftar. Klik tombol "Tambah Mata Kuliah" untuk menambahkan data.
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
                    <strong>Total Mata Kuliah:</strong> {{ $matakuliahs->total() }} mata kuliah
                    @if(request('search') || request('semester'))
                        <span class="text-blue-600">(filtered)</span>
                    @endif
                </p>
                <p class="text-xs text-blue-600 mt-1">
                    <i class="fas fa-lightbulb"></i> Pastikan setiap mata kuliah memiliki komponen penilaian sebelum input nilai mahasiswa
                </p>
            </div>
        </div>
    </div>
</div>
@endsection







