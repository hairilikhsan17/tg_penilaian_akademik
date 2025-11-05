@extends('dashboard.dosen')

@section('title', 'Atur Password Mahasiswa')

@section('page-title', 'Atur Password Mahasiswa')
@section('page-description', 'Atur atau reset password untuk login mahasiswa')

@section('breadcrumb')
    <li><span class="text-gray-500">/</span></li>
    <li><a href="{{ route('mahasiswas.index') }}" class="hover:text-blue-600">Data Mahasiswa</a></li>
    <li><span class="text-gray-500">/</span></li>
    <li class="text-gray-700">Atur Password</li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Info Mahasiswa -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-4">
                    {{ substr($mahasiswa->nama, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $mahasiswa->nama }}</h3>
                    <p class="text-sm text-gray-600">NIM: {{ $mahasiswa->nim }} | {{ $mahasiswa->jurusan }}</p>
                    @if($mahasiswa->user)
                        <p class="text-xs text-blue-600 mt-1">
                            <i class="fas fa-check-circle"></i> User account sudah tersedia
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('mahasiswas.password.update', $mahasiswa) }}" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               minlength="8"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-10"
                               placeholder="Minimal 8 karakter">
                        <button type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Password minimal 8 karakter</p>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required
                               minlength="8"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-10"
                               placeholder="Ulangi password">
                        <button type="button" 
                                onclick="togglePassword('password_confirmation')"
                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password_confirmation-icon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Penting -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 mr-3 mt-0.5"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Informasi Penting:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Password ini akan digunakan mahasiswa untuk login ke sistem</li>
                            <li>Email untuk login: <strong>{{ $mahasiswa->user->email ?? ($mahasiswa->email ?? $mahasiswa->nim . '@example.com') }}</strong></li>
                            <li>Pastikan untuk memberikan informasi login ini kepada mahasiswa</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('mahasiswas.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-key mr-2"></i>Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection







