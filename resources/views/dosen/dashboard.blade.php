@extends('dashboard.dosen')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')
@section('page-description', 'Ringkasan aktivitas dan statistik akademik')

@section('content')
<div class="space-y-6">
    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Jumlah Mahasiswa -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Mahasiswa Terdaftar</p>
                    <p class="text-3xl font-bold">{{ $jumlahMahasiswa }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Jumlah Mata Kuliah -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Mata Kuliah Diampu</p>
                    <p class="text-3xl font-bold">{{ $jumlahMatakuliah }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-book text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Nilai Tertinggi -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Nilai Tertinggi</p>
                    <p class="text-3xl font-bold">
                        {{ $tertinggi ? number_format($tertinggi->nilai_akhir, 2) : '-' }}
                    </p>
                    @if($tertinggi)
                        <p class="text-xs text-purple-100 mt-1">{{ $tertinggi->mahasiswa->nama ?? '-' }}</p>
                    @endif
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-arrow-up text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Nilai Terendah -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">Nilai Terendah</p>
                    <p class="text-3xl font-bold">
                        {{ $terendah ? number_format($terendah->nilai_akhir, 2) : '-' }}
                    </p>
                    @if($terendah)
                        <p class="text-xs text-orange-100 mt-1">{{ $terendah->mahasiswa->nama ?? '-' }}</p>
                    @endif
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-arrow-down text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Cepat -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-bolt text-blue-600 mr-2"></i>Menu Cepat
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('nilai.list') }}" class="flex items-center space-x-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all shadow-md hover:shadow-lg">
                <div class="bg-blue-600 text-white p-3 rounded-lg">
                    <i class="fas fa-pen-to-square text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Input Nilai</p>
                    <p class="text-xs text-gray-600">Input nilai mahasiswa</p>
                </div>
            </a>

            <a href="{{ route('komponen.index') }}" class="flex items-center space-x-4 p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all shadow-md hover:shadow-lg">
                <div class="bg-green-600 text-white p-3 rounded-lg">
                    <i class="fas fa-clipboard-check text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Komponen Penilaian</p>
                    <p class="text-xs text-gray-600">Atur komponen penilaian</p>
                </div>
            </a>

            <a href="{{ route('matakuliahs.index') }}" class="flex items-center space-x-4 p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all shadow-md hover:shadow-lg">
                <div class="bg-purple-600 text-white p-3 rounded-lg">
                    <i class="fas fa-book text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Kelola Mata Kuliah</p>
                    <p class="text-xs text-gray-600">Kelola data mata kuliah</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Daftar Mata Kuliah -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-list text-purple-600 mr-2"></i>Daftar Mata Kuliah
        </h3>
        @if($matakuliahs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kode MK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama Mata Kuliah</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Semester</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">SKS</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Jumlah Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($matakuliahs as $index => $matakuliah)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $matakuliah->kode_mk }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $matakuliah->nama_mk }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-700">{{ $matakuliah->semester }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-700">{{ $matakuliah->sks }}</td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        {{ $matakuliah->nilai_mahasiswas_count }} mahasiswa
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-book text-4xl mb-2"></i>
                <p>Belum ada mata kuliah yang diampu</p>
            </div>
        @endif
    </div>
</div>
@endsection
