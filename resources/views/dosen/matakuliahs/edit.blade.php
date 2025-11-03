@extends('dashboard.dosen')

@section('title', 'Edit Mata Kuliah')

@section('page-title', 'Edit Mata Kuliah')
@section('page-description', 'Ubah informasi mata kuliah')

@section('breadcrumb')
    <li><span class="text-gray-500">/</span></li>
    <li><a href="{{ route('matakuliahs.index') }}" class="hover:text-blue-600">Mata Kuliah</a></li>
    <li><span class="text-gray-500">/</span></li>
    <li class="text-gray-700">Edit</li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="POST" action="{{ route('matakuliahs.update', $matakuliah) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Mata Kuliah -->
                <div>
                    <label for="kode_mk" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kode Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="kode_mk" 
                           name="kode_mk" 
                           value="{{ old('kode_mk', $matakuliah->kode_mk) }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Contoh: MK001, TIF101">
                    <p class="mt-1 text-xs text-gray-500">Kode unik untuk mata kuliah</p>
                    @error('kode_mk')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Mata Kuliah -->
                <div>
                    <label for="nama_mk" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_mk" 
                           name="nama_mk" 
                           value="{{ old('nama_mk', $matakuliah->nama_mk) }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Masukkan nama mata kuliah">
                    @error('nama_mk')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Semester -->
                <div>
                    <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select id="semester" 
                            name="semester" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Pilih Semester</option>
                        @for($i = 1; $i <= 14; $i++)
                            <option value="{{ $i }}" {{ old('semester', $matakuliah->semester) == $i ? 'selected' : '' }}>
                                Semester {{ $i }}
                            </option>
                        @endfor
                    </select>
                    @error('semester')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SKS -->
                <div>
                    <label for="sks" class="block text-sm font-semibold text-gray-700 mb-2">
                        SKS (Sistem Kredit Semester) <span class="text-red-500">*</span>
                    </label>
                    <select id="sks" 
                            name="sks" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Pilih SKS</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('sks', $matakuliah->sks) == $i ? 'selected' : '' }}>
                                {{ $i }} SKS
                            </option>
                        @endfor
                    </select>
                    @error('sks')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dosen Pengampu (Read-only) -->
                <div class="md:col-span-2">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Dosen Pengampu
                        </label>
                        <p class="text-sm text-gray-700">
                            <i class="fas fa-user-tie mr-2"></i>
                            <strong>{{ $matakuliah->dosen->name ?? 'Tidak ada' }}</strong>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Dosen pengampu tidak dapat diubah</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('matakuliahs.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection





