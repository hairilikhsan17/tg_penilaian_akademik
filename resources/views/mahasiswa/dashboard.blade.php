@extends('dashboard.mahasiswa')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')
@section('page-description', 'Ringkasan aktivitas akademik Anda')

@section('content')
<div class="space-y-6">
    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Jumlah Mata Kuliah Aktif Semester Ini -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Mata Kuliah Aktif</p>
                    <p class="text-3xl font-bold">{{ $jumlahMatakuliahAktif ?? 0 }}</p>
                    <p class="text-xs text-blue-200 mt-1">Semester {{ $semesterAktif ?? '-' }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-book text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Nilai Rata-rata Semester Berjalan -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Rata-rata Semester</p>
                    <p class="text-3xl font-bold">{{ number_format($rataRataSemester ?? 0, 2) }}</p>
                    <p class="text-xs text-green-200 mt-1">Semester {{ $semesterAktif ?? '-' }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- IPK -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">IPK</p>
                    <p class="text-3xl font-bold">{{ number_format($ipk ?? 0, 2) }}</p>
                    <p class="text-xs text-purple-200 mt-1">Indeks Prestasi Kumulatif</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-star text-2xl"></i>
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
            <!-- Lihat Nilai -->
            <a href="{{ route('mahasiswa.nilai') }}" class="flex items-center space-x-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all shadow-md hover:shadow-lg">
                <div class="bg-blue-600 text-white p-3 rounded-lg">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Lihat Nilai</p>
                    <p class="text-xs text-gray-600">Lihat nilai akademik Anda</p>
                </div>
            </a>

            <!-- KHS / Transkrip -->
            <a href="{{ route('mahasiswa.khs') }}" class="flex items-center space-x-4 p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all shadow-md hover:shadow-lg">
                <div class="bg-green-600 text-white p-3 rounded-lg">
                    <i class="fas fa-file-invoice text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">KHS / Transkrip</p>
                    <p class="text-xs text-gray-600">Lihat KHS dan transkrip nilai</p>
                </div>
            </a>

            <!-- Cetak Laporan -->
            <a href="{{ route('mahasiswa.cetak.khs') }}" target="_blank" class="flex items-center space-x-4 p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all shadow-md hover:shadow-lg">
                <div class="bg-purple-600 text-white p-3 rounded-lg">
                    <i class="fas fa-file-pdf text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Cetak Laporan</p>
                    <p class="text-xs text-gray-600">Cetak KHS dalam format PDF</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Informasi Mahasiswa -->
    @if($mahasiswa)
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>Informasi Mahasiswa
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">NIM</span>
                    <span class="text-gray-800 font-semibold">{{ $mahasiswa->nim }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Nama</span>
                    <span class="text-gray-800 font-semibold">{{ $mahasiswa->nama }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Semester Aktif</span>
                    <span class="text-gray-800 font-semibold">Semester {{ $mahasiswa->semester ?? '-' }}</span>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Jurusan</span>
                    <span class="text-gray-800 font-semibold">{{ $mahasiswa->jurusan ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Email</span>
                    <span class="text-gray-800 font-semibold">{{ $mahasiswa->email ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Status</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Aktif</span>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
            <p class="text-yellow-800">Data mahasiswa tidak ditemukan. Silakan hubungi administrator.</p>
        </div>
    </div>
    @endif
</div>
@endsection





