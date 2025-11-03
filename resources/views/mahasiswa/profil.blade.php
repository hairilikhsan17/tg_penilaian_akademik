@extends('dashboard.mahasiswa')

@section('title', 'Profil Mahasiswa')

@section('page-title', 'Profil Saya')
@section('page-description', 'Lihat dan kelola informasi profil Anda')

@section('content')
<div class="space-y-6" x-data="{ editing: false }">
    <!-- Alert Success/Error -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <p>{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <p>{{ session('error') }}</p>
        </div>
    </div>
    @endif

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
                        @if($mahasiswa->foto_profil)
                            <img src="{{ Storage::url('foto_profil/' . $mahasiswa->foto_profil) }}" 
                                 alt="Foto Profil" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-blue-500">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-full flex items-center justify-center transition-all duration-200 cursor-pointer">
                                <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <label for="upload-foto-view" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full transition-colors" title="Ganti Foto">
                                        <i class="fas fa-camera text-sm"></i>
                                    </label>
                                    <form action="{{ route('mahasiswa.profile.deleteFoto') }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil?');">
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
                                    {{ substr($mahasiswa->nama ?? $user->name ?? 'M', 0, 1) }}
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-full flex items-center justify-center transition-all duration-200">
                                        <i class="fas fa-camera text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    </div>
                                </div>
                            </label>
                        @endif
                        
                        <!-- Form untuk upload foto (hidden) -->
                        <form id="upload-form-view" action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="nama" value="{{ $mahasiswa->nama }}">
                            <input type="hidden" name="nim" value="{{ $mahasiswa->nim }}">
                            <input type="hidden" name="email" value="{{ $mahasiswa->email }}">
                            <input type="hidden" name="user_email" value="{{ $user->email }}">
                            <input type="hidden" name="semester" value="{{ $mahasiswa->semester }}">
                            <input type="file" id="upload-foto-view" name="foto_profil" accept="image/*" onchange="this.form.submit()">
                        </form>
                        <form id="upload-form-empty-view" action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="nama" value="{{ $mahasiswa->nama }}">
                            <input type="hidden" name="nim" value="{{ $mahasiswa->nim }}">
                            <input type="hidden" name="email" value="{{ $mahasiswa->email }}">
                            <input type="hidden" name="user_email" value="{{ $user->email }}">
                            <input type="hidden" name="semester" value="{{ $mahasiswa->semester }}">
                            <input type="file" id="upload-foto-empty-view" name="foto_profil" accept="image/*" onchange="this.form.submit()">
                        </form>
                    </div>
                    <div>
                        <h4 class="text-2xl font-bold text-gray-800">{{ $mahasiswa->nama ?? $user->name }}</h4>
                        <p class="text-gray-500 mt-1">Mahasiswa</p>
                        <p class="text-xs text-gray-400 mt-1">
                            @if($mahasiswa->foto_profil)
                                <i class="fas fa-info-circle mr-1"></i> Hover pada foto untuk mengganti atau menghapus
                            @else
                                <i class="fas fa-info-circle mr-1"></i> Klik avatar untuk upload foto profil
                            @endif
                        </p>
                    </div>
                </div>

                <!-- NIM -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">NIM</label>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800">{{ $mahasiswa->nim ?? '-' }}</p>
                    </div>
                </div>

                <!-- Nama -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Nama Lengkap</label>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800">{{ $mahasiswa->nama ?? $user->name ?? '-' }}</p>
                    </div>
                </div>

                <!-- Email Mahasiswa -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Email Mahasiswa</label>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800">{{ $mahasiswa->email ?? '-' }}</p>
                    </div>
                </div>

                <!-- Email Login -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Email Login</label>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800">{{ $user->email ?? '-' }}</p>
                    </div>
                </div>

                <!-- Semester -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Semester Aktif</label>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800">Semester {{ $mahasiswa->semester ?? '-' }}</p>
                    </div>
                </div>

                <!-- Jurusan -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Jurusan</label>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-800">{{ $mahasiswa->jurusan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Mode -->
        <form method="POST" action="{{ route('mahasiswa.profile.update') }}" enctype="multipart/form-data" x-show="editing" @submit.prevent="editing = false">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Profile Avatar Upload -->
                <div class="md:col-span-2 flex items-center space-x-6 pb-6 border-b border-gray-200">
                    <div class="relative">
                        @if($mahasiswa->foto_profil)
                            <img id="preview-foto" src="{{ Storage::url('foto_profil/' . $mahasiswa->foto_profil) }}" 
                                 alt="Foto Profil" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-blue-500">
                        @else
                            <div id="preview-foto" class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-3xl font-bold border-4 border-blue-500">
                                {{ substr($mahasiswa->nama ?? $user->name ?? 'M', 0, 1) }}
                            </div>
                        @endif
                        <label for="foto_profil" class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 cursor-pointer hover:bg-blue-700 transition-colors">
                            <i class="fas fa-camera text-sm"></i>
                        </label>
                        <input type="file" id="foto_profil" name="foto_profil" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>
                    <div>
                        <h4 class="text-2xl font-bold text-gray-800">{{ $mahasiswa->nama ?? $user->name }}</h4>
                        <p class="text-gray-500 mt-1">Mahasiswa</p>
                        <p class="text-xs text-gray-400 mt-1">Klik ikon kamera untuk mengganti foto</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NIM -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">NIM <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="nim" 
                               value="{{ old('nim', $mahasiswa->nim) }}" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nim') border-red-500 @enderror">
                        @error('nim')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="nama" 
                               value="{{ old('nama', $mahasiswa->nama) }}" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Mahasiswa -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Email Mahasiswa <span class="text-red-500">*</span></label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $mahasiswa->email) }}" 
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

                    <!-- Semester -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Semester Aktif <span class="text-red-500">*</span></label>
                        <select name="semester" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('semester') border-red-500 @enderror">
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('semester', $mahasiswa->semester) == $i ? 'selected' : '' }}>
                                    Semester {{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('semester')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" 
                               name="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Konfirmasi Password Baru</label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                    <button type="button" 
                            @click="editing = false" 
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistics Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Total Mata Kuliah</p>
                    <p class="text-3xl font-bold">{{ $totalMatakuliah }}</p>
                </div>
                <i class="fas fa-book text-4xl text-blue-200"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">IPK</p>
                    <p class="text-3xl font-bold">{{ number_format($ipk, 2) }}</p>
                </div>
                <i class="fas fa-chart-line text-4xl text-green-200"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm mb-1">Semester Aktif</p>
                    <p class="text-3xl font-bold">{{ $mahasiswa->semester ?? '-' }}</p>
                </div>
                <i class="fas fa-calendar text-4xl text-purple-200"></i>
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
                    // Jika div, ganti dengan img
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
