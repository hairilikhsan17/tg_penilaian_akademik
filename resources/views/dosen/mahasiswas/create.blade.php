@extends('dashboard.dosen')

@section('title', 'Tambah Mahasiswa')

@section('page-title', 'Tambah Mahasiswa Baru')
@section('page-description', 'Tambah data mahasiswa baru ke dalam sistem')

@section('breadcrumb')
    <li><span class="text-gray-500">/</span></li>
    <li><a href="{{ route('mahasiswas.index') }}" class="hover:text-blue-600">Data Mahasiswa</a></li>
    <li><span class="text-gray-500">/</span></li>
    <li class="text-gray-700">Tambah</li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="POST" action="{{ route('mahasiswas.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NIM -->
                <div class="md:col-span-2">
                    <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">
                        NIM (Nomor Induk Mahasiswa) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nim" 
                           name="nim" 
                           value="{{ old('nim') }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Masukkan NIM">
                    @error('nim')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama -->
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama') }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Masukkan nama lengkap">
                    @error('nama')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Semester -->
                <div>
                    <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="semester" 
                           name="semester" 
                           value="{{ old('semester') }}" 
                           required
                           min="1" 
                           max="14"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="1-14">
                    @error('semester')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jurusan -->
                <div>
                    <label for="jurusan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Jurusan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="jurusan" 
                           name="jurusan" 
                           value="{{ old('jurusan') }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Masukkan jurusan">
                    @error('jurusan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('mahasiswas.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection





