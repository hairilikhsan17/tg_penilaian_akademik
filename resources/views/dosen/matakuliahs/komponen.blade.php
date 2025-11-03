@extends('dashboard.dosen')

@section('title', 'Komponen Penilaian')

@section('page-title', 'Atur Komponen Penilaian')
@section('page-description', 'Tentukan bobot penilaian untuk mata kuliah')

@section('breadcrumb')
    <li><span class="text-gray-500">/</span></li>
    <li><a href="{{ route('matakuliahs.index') }}" class="hover:text-blue-600">Mata Kuliah</a></li>
    <li><span class="text-gray-500">/</span></li>
    <li class="text-gray-700">Komponen Penilaian</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Info Mata Kuliah -->
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 border-l-4 border-blue-500 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-800">{{ $matakuliah->nama_mk }}</h3>
                <p class="text-sm text-gray-600 mt-1">
                    <span class="font-semibold">Kode:</span> {{ $matakuliah->kode_mk }} | 
                    <span class="font-semibold">Semester:</span> {{ $matakuliah->semester }} | 
                    <span class="font-semibold">SKS:</span> {{ $matakuliah->sks }}
                </p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-xs text-gray-500 mb-1">Total Bobot</p>
                    <p id="total-display" class="text-2xl font-bold text-blue-600">
                        {{ ($komponen->kehadiran ?? 0) + ($komponen->tugas ?? 0) + ($komponen->kuis ?? 0) + ($komponen->project ?? 0) + ($komponen->uts ?? 0) + ($komponen->uas ?? 0) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="POST" action="{{ route('komponen.store', $matakuliah) }}" class="space-y-6" id="komponen-form">
            @csrf

            <!-- Warning jika total tidak 100% -->
            @if($errors->has('total'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700 font-medium">{{ $errors->first('total') }}</p>
                    </div>
                </div>
            @endif

            <!-- Info Penting -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mr-3 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Ketentuan:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Total bobot semua komponen harus <strong>tepat 100%</strong></li>
                            <li>Nilai minimum per komponen: 0%, maksimum: 100%</li>
                            <li>Komponen penilaian ini akan digunakan untuk menghitung nilai akhir mahasiswa</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Hadir (Kehadiran) -->
                <div class="bg-gray-50 rounded-lg p-5 border-2 border-gray-200">
                    <label for="kehadiran" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-check text-blue-600 mr-2"></i>Hadir <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="number" 
                               id="kehadiran" 
                               name="kehadiran" 
                               value="{{ old('kehadiran', $komponen->kehadiran ?? 10) }}" 
                               required
                               min="0"
                               max="100"
                               oninput="updateTotal()"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-lg font-semibold text-center">
                        <span class="text-gray-600 font-medium text-lg">%</span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Bobot untuk penilaian kehadiran mahasiswa</p>
                    @error('kehadiran')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tugas -->
                <div class="bg-gray-50 rounded-lg p-5 border-2 border-gray-200">
                    <label for="tugas" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tasks text-green-600 mr-2"></i>Tugas <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="number" 
                               id="tugas" 
                               name="tugas" 
                               value="{{ old('tugas', $komponen->tugas ?? 20) }}" 
                               required
                               min="0"
                               max="100"
                               oninput="updateTotal()"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all text-lg font-semibold text-center">
                        <span class="text-gray-600 font-medium text-lg">%</span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Bobot untuk penilaian tugas mahasiswa</p>
                    @error('tugas')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quiz -->
                <div class="bg-gray-50 rounded-lg p-5 border-2 border-gray-200">
                    <label for="kuis" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-question-circle text-purple-600 mr-2"></i>Quiz <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="number" 
                               id="kuis" 
                               name="kuis" 
                               value="{{ old('kuis', $komponen->kuis ?? 10) }}" 
                               required
                               min="0"
                               max="100"
                               oninput="updateTotal()"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-lg font-semibold text-center">
                        <span class="text-gray-600 font-medium text-lg">%</span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Bobot untuk penilaian kuis/quiz</p>
                    @error('kuis')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project -->
                <div class="bg-gray-50 rounded-lg p-5 border-2 border-gray-200">
                    <label for="project" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-project-diagram text-indigo-600 mr-2"></i>Project <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="number" 
                               id="project" 
                               name="project" 
                               value="{{ old('project', $komponen->project ?? 20) }}" 
                               required
                               min="0"
                               max="100"
                               oninput="updateTotal()"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-lg font-semibold text-center">
                        <span class="text-gray-600 font-medium text-lg">%</span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Bobot untuk penilaian project</p>
                    @error('project')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mid (UTS) -->
                <div class="bg-gray-50 rounded-lg p-5 border-2 border-gray-200">
                    <label for="uts" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-clipboard-list text-orange-600 mr-2"></i>Mid (UTS) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="number" 
                               id="uts" 
                               name="uts" 
                               value="{{ old('uts', $komponen->uts ?? 20) }}" 
                               required
                               min="0"
                               max="100"
                               oninput="updateTotal()"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-lg font-semibold text-center">
                        <span class="text-gray-600 font-medium text-lg">%</span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Bobot untuk Ujian Tengah Semester</p>
                    @error('uts')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Final (UAS) -->
                <div class="bg-gray-50 rounded-lg p-5 border-2 border-gray-200 md:col-span-2">
                    <label for="uas" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-graduation-cap text-red-600 mr-2"></i>Final (UAS) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-3 max-w-md">
                        <input type="number" 
                               id="uas" 
                               name="uas" 
                               value="{{ old('uas', $komponen->uas ?? 20) }}" 
                               required
                               min="0"
                               max="100"
                               oninput="updateTotal()"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all text-lg font-semibold text-center">
                        <span class="text-gray-600 font-medium text-lg">%</span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Bobot untuk Ujian Akhir Semester</p>
                    @error('uas')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Total Display (Mobile) -->
            <div class="md:hidden bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium">Total Bobot:</span>
                    <span id="total-display-mobile" class="text-2xl font-bold">
                        {{ ($komponen->kehadiran ?? 0) + ($komponen->tugas ?? 0) + ($komponen->kuis ?? 0) + ($komponen->project ?? 0) + ($komponen->uts ?? 0) + ($komponen->uas ?? 0) }}%
                    </span>
                </div>
                <div id="total-status" class="mt-2 text-xs">
                    @php
                        $currentTotal = ($komponen->kehadiran ?? 0) + ($komponen->tugas ?? 0) + ($komponen->kuis ?? 0) + ($komponen->project ?? 0) + ($komponen->uts ?? 0) + ($komponen->uas ?? 0);
                    @endphp
                    @if($currentTotal == 100)
                        <span class="text-green-200"><i class="fas fa-check-circle"></i> Total sudah tepat 100%</span>
                    @elseif($currentTotal < 100)
                        <span class="text-yellow-200"><i class="fas fa-exclamation-triangle"></i> Kurang {{ 100 - $currentTotal }}%</span>
                    @else
                        <span class="text-red-200"><i class="fas fa-times-circle"></i> Lebih {{ $currentTotal - 100 }}%</span>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('matakuliahs.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        id="submit-btn"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-save mr-2"></i>Simpan Komponen Penilaian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateTotal() {
        const kehadiran = parseInt(document.getElementById('kehadiran').value) || 0;
        const tugas = parseInt(document.getElementById('tugas').value) || 0;
        const kuis = parseInt(document.getElementById('kuis').value) || 0;
        const project = parseInt(document.getElementById('project').value) || 0;
        const uts = parseInt(document.getElementById('uts').value) || 0;
        const uas = parseInt(document.getElementById('uas').value) || 0;
        
        const total = kehadiran + tugas + kuis + project + uts + uas;
        
        // Update display
        document.getElementById('total-display').textContent = total + '%';
        document.getElementById('total-display-mobile').textContent = total + '%';
        
        // Update status
        const statusEl = document.getElementById('total-status');
        const submitBtn = document.getElementById('submit-btn');
        
        if (total === 100) {
            statusEl.innerHTML = '<span class="text-green-200"><i class="fas fa-check-circle"></i> Total sudah tepat 100%</span>';
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else if (total < 100) {
            statusEl.innerHTML = '<span class="text-yellow-200"><i class="fas fa-exclamation-triangle"></i> Kurang ' + (100 - total) + '%</span>';
            submitBtn.disabled = false;
        } else {
            statusEl.innerHTML = '<span class="text-red-200"><i class="fas fa-times-circle"></i> Lebih ' + (total - 100) + '%</span>';
            submitBtn.disabled = false;
        }
    }

    // Validasi sebelum submit
    document.getElementById('komponen-form').addEventListener('submit', function(e) {
        const kehadiran = parseInt(document.getElementById('kehadiran').value) || 0;
        const tugas = parseInt(document.getElementById('tugas').value) || 0;
        const kuis = parseInt(document.getElementById('kuis').value) || 0;
        const project = parseInt(document.getElementById('project').value) || 0;
        const uts = parseInt(document.getElementById('uts').value) || 0;
        const uas = parseInt(document.getElementById('uas').value) || 0;
        
        const total = kehadiran + tugas + kuis + project + uts + uas;
        
        if (total !== 100) {
            e.preventDefault();
            alert('Total bobot harus tepat 100%. Saat ini: ' + total + '%');
            return false;
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
    });
</script>
@endsection




