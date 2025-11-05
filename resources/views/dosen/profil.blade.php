@extends('dashboard.dosen')

@section('title', 'Profil Dosen')

@section('page-title', 'Profil Saya')
@section('page-description', 'Lihat dan kelola informasi profil dosen Anda')

@section('breadcrumb')
    <li><span class="text-gray-500">/</span></li>
    <li class="text-gray-700">Profil</li>
@endsection

@section('content')
<div class="space-y-6" x-data="{ editing: false }">
    <!-- Profile Information Card -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">Informasi Profil</h3>
            <button @click="editing = !editing" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                <i class="fas fa-edit mr-1"></i> 
                <span x-text="editing ? 'Batal Edit' : 'Edit Profil'"></span>
            </button>
        </div>
        
        <!-- View Mode -->
        <div x-show="!editing">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profile Avatar -->
                <div class="md:col-span-2 flex items-center space-x-6 pb-6 border-b border-gray-200">
                    <div class="relative group">
                        @if($dosen->foto_profil)
                            <img src="{{ Storage::url('foto_profil/' . $dosen->foto_profil) }}" 
                                 alt="Foto Profil" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-blue-500">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-full flex items-center justify-center transition-all duration-200 cursor-pointer">
                                <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <label for="upload-foto-view" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full transition-colors" title="Ganti Foto">
                                        <i class="fas fa-camera text-sm"></i>
                                    </label>
                                    <form action="{{ route('dosen.profil.deleteFoto') }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition-colors" title="Hapus Foto">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <label for="upload-foto-empty-view" class="cursor-pointer">
                                <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-3xl font-bold border-4 border-blue-500 relative group">
                                    {{ substr($dosen->nama ?? $user->name ?? 'U', 0, 1) }}
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-full flex items-center justify-center transition-all duration-200">
                                        <i class="fas fa-camera text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    </div>
                                </div>
                            </label>
                        @endif
                        
                        <!-- Form untuk upload foto (hidden) -->
                        <form id="upload-form-view" action="{{ route('dosen.profil.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="nama" value="{{ $dosen->nama }}">
                            <input type="hidden" name="nip" value="{{ $dosen->nip }}">
                            <input type="hidden" name="email" value="{{ $dosen->email }}">
                            <input type="hidden" name="user_email" value="{{ $user->email }}">
                            <input type="file" id="upload-foto-view" name="foto_profil" accept="image/*" onchange="this.form.submit()">
                        </form>
                        <form id="upload-form-empty-view" action="{{ route('dosen.profil.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="nama" value="{{ $dosen->nama }}">
                            <input type="hidden" name="nip" value="{{ $dosen->nip }}">
                            <input type="hidden" name="email" value="{{ $dosen->email }}">
                            <input type="hidden" name="user_email" value="{{ $user->email }}">
                            <input type="file" id="upload-foto-empty-view" name="foto_profil" accept="image/*" onchange="this.form.submit()">
                        </form>
                    </div>
                    <div>
                        <h4 class="text-2xl font-bold text-gray-800">{{ $dosen->nama ?? $user->name }}</h4>
                        <p class="text-gray-500 mt-1">Dosen</p>
                        <p class="text-xs text-gray-400 mt-1">
                            @if($dosen->foto_profil)
                                <i class="fas fa-info-circle mr-1"></i> Hover pada foto untuk mengganti atau menghapus
                            @else
                                <i class="fas fa-info-circle mr-1"></i> Klik avatar untuk upload foto profil
                            @endif
                        </p>
                    </div>
                </div>

                <!-- NIP -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">NIP</label>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800">{{ $dosen->nip ?? '-' }}</p>
                    </div>
                </div>

                <!-- Nama -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Nama Lengkap</label>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800">{{ $dosen->nama ?? $user->name ?? '-' }}</p>
                    </div>
                </div>

                <!-- Email Dosen -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Email Dosen</label>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800">{{ $dosen->email ?? '-' }}</p>
                    </div>
                </div>

                <!-- Email Login -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Email Login</label>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800">{{ $user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Mode -->
        <form method="POST" action="{{ route('dosen.profil.update') }}" enctype="multipart/form-data" x-show="editing">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Profile Avatar Upload -->
                <div class="md:col-span-2 flex items-center space-x-6 pb-6 border-b border-gray-200">
                    <div class="relative">
                        @if($dosen->foto_profil)
                            <img id="preview-foto" src="{{ Storage::url('foto_profil/' . $dosen->foto_profil) }}" 
                                 alt="Foto Profil" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-blue-500">
                        @else
                            <div id="preview-foto" class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-3xl font-bold border-4 border-blue-500">
                                {{ substr($dosen->nama ?? $user->name ?? 'U', 0, 1) }}
                            </div>
                        @endif
                        <label for="foto_profil" class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 cursor-pointer hover:bg-blue-700 transition-colors">
                            <i class="fas fa-camera text-sm"></i>
                        </label>
                        <input type="file" id="foto_profil" name="foto_profil" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>
                    <div>
                        <h4 class="text-2xl font-bold text-gray-800">{{ $dosen->nama ?? $user->name }}</h4>
                        <p class="text-gray-500 mt-1">Dosen</p>
                        <p class="text-xs text-gray-400 mt-1">Klik ikon kamera untuk mengganti foto</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NIP -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">NIP <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="nip" 
                               value="{{ old('nip', $dosen->nip) }}" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nip') border-red-500 @enderror">
                        @error('nip')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="nama" 
                               value="{{ old('nama', $dosen->nama) }}" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Dosen -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Email Dosen <span class="text-red-500">*</span></label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $dosen->email) }}" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Login -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Email Login <span class="text-red-500">*</span></label>
                        <input type="email" 
                               name="user_email" 
                               value="{{ old('user_email', $user->email) }}" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_email') border-red-500 @enderror">
                        @error('user_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Password Baru (opsional)</label>
                        <input type="password" 
                               name="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Kosongkan jika tidak ingin mengubah">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Konfirmasi Password</label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Konfirmasi password baru">
                    </div>
                </div>

                <!-- Button -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" 
                            @click="editing = false" 
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistics Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Mata Kuliah</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $dosen->matakuliahs->count() ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Mahasiswa</p>
                    <p class="text-2xl font-bold text-gray-800">
                        @php
                            $mahasiswaIds = collect();
                            foreach($dosen->matakuliahs ?? [] as $mk) {
                                foreach($mk->nilaiMahasiswas ?? [] as $nilai) {
                                    $mahasiswaIds->push($nilai->mahasiswa_id);
                                }
                            }
                            $totalMahasiswa = $mahasiswaIds->unique()->count();
                        @endphp
                        {{ $totalMahasiswa }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-graduate text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Status Akun</p>
                    <p class="text-lg font-bold text-green-600">Aktif</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview-foto');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Replace div with img
                const img = document.createElement('img');
                img.id = 'preview-foto';
                img.src = e.target.result;
                img.className = 'w-24 h-24 rounded-full object-cover border-4 border-blue-500';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
