@extends('dashboard.dosen')

@section('title', 'Input Nilai Mahasiswa')

@section('page-title', 'Input Nilai Mahasiswa')
@section('page-description', 'Input nilai untuk mata kuliah ' . $matakuliah->nama_mk)

@section('breadcrumb')
    <li><span class="text-gray-500">/</span></li>
    <li><a href="{{ route('nilai.list') }}" class="hover:text-blue-600">Input Nilai</a></li>
    <li><span class="text-gray-500">/</span></li>
    <li class="text-gray-700">{{ $matakuliah->nama_mk }}</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Info Mata Kuliah -->
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 border-l-4 border-blue-500 rounded-lg p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800">{{ $matakuliah->nama_mk }}</h3>
                <p class="text-sm text-gray-600 mt-1">
                    <span class="font-semibold">Kode:</span> {{ $matakuliah->kode_mk }} | 
                    <span class="font-semibold">Semester:</span> {{ $matakuliah->semester }} | 
                    <span class="font-semibold">SKS:</span> {{ $matakuliah->sks }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @php
                    $komponen = $matakuliah->komponenPenilaian;
                @endphp
                @if($komponen)
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <p class="text-xs text-gray-500 mb-1">Komponen Penilaian</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            @if($komponen->kehadiran > 0)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">Hadir: {{ $komponen->kehadiran }}%</span>
                            @endif
                            @if($komponen->tugas > 0)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Tugas: {{ $komponen->tugas }}%</span>
                            @endif
                            @if($komponen->kuis > 0)
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded">Quiz: {{ $komponen->kuis }}%</span>
                            @endif
                            @if($komponen->project > 0)
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded">Project: {{ $komponen->project }}%</span>
                            @endif
                            @if($komponen->uts > 0)
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded">UTS: {{ $komponen->uts }}%</span>
                            @endif
                            @if($komponen->uas > 0)
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded">UAS: {{ $komponen->uas }}%</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(!$komponen || $komponen->total != 100)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <div>
                    <p class="text-red-700 font-medium">Komponen penilaian belum lengkap atau total ≠ 100%</p>
                    <p class="text-red-600 text-sm mt-1">Silakan atur komponen penilaian terlebih dahulu sebelum input nilai.</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('komponen.create', $matakuliah) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-clipboard-check mr-2"></i>Atur Komponen Penilaian
                </a>
            </div>
        </div>
    @else
        <!-- Form Input Nilai -->
        <form method="POST" action="{{ route('nilai.store', $matakuliah) }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                    <h4 class="text-lg font-semibold">Input Nilai Mahasiswa</h4>
                    <p class="text-sm text-indigo-100 mt-1">Masukkan nilai untuk setiap komponen penilaian (0-100)</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider sticky left-12 bg-gray-50 z-10">NIM</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider sticky left-32 bg-gray-50 z-10 min-w-[200px]">Nama</th>
                                @if($komponen->kehadiran > 0)
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex flex-col items-center">
                                            <span>Kehadiran</span>
                                            <span class="text-blue-600 font-bold">{{ $komponen->kehadiran }}%</span>
                                        </div>
                                    </th>
                                @endif
                                @if($komponen->tugas > 0)
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex flex-col items-center">
                                            <span>Tugas</span>
                                            <span class="text-green-600 font-bold">{{ $komponen->tugas }}%</span>
                                        </div>
                                    </th>
                                @endif
                                @if($komponen->kuis > 0)
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex flex-col items-center">
                                            <span>Quiz</span>
                                            <span class="text-purple-600 font-bold">{{ $komponen->kuis }}%</span>
                                        </div>
                                    </th>
                                @endif
                                @if($komponen->project > 0)
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex flex-col items-center">
                                            <span>Project</span>
                                            <span class="text-indigo-600 font-bold">{{ $komponen->project }}%</span>
                                        </div>
                                    </th>
                                @endif
                                @if($komponen->uts > 0)
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex flex-col items-center">
                                            <span>UTS</span>
                                            <span class="text-orange-600 font-bold">{{ $komponen->uts }}%</span>
                                        </div>
                                    </th>
                                @endif
                                @if($komponen->uas > 0)
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex flex-col items-center">
                                            <span>UAS</span>
                                            <span class="text-red-600 font-bold">{{ $komponen->uas }}%</span>
                                        </div>
                                    </th>
                                @endif
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-yellow-50">
                                    <div class="flex flex-col items-center">
                                        <span>Nilai Akhir</span>
                                        <span class="text-gray-600 text-xs">(Otomatis)</span>
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-yellow-50">
                                    <div class="flex flex-col items-center">
                                        <span>Huruf Mutu</span>
                                        <span class="text-gray-600 text-xs">(Otomatis)</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="nilai-table-body">
                            @forelse($mahasiswas as $index => $mahasiswa)
                                @php
                                    $nilai = $nilaiMap[$mahasiswa->id] ?? null;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors nilai-row" data-mahasiswa-id="{{ $mahasiswa->id }}">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 sticky left-0 bg-white">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap sticky left-12 bg-white">
                                        <span class="text-sm font-semibold text-gray-900">{{ $mahasiswa->nim }}</span>
                                    </td>
                                    <td class="px-4 py-3 sticky left-32 bg-white min-w-[200px]">
                                        <span class="text-sm font-medium text-gray-900">{{ $mahasiswa->nama }}</span>
                                    </td>
                                    
                                    @if($komponen->kehadiran > 0)
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <input type="number" 
                                                   name="entries[{{ $mahasiswa->id }}][kehadiran]"
                                                   value="{{ old("entries.{$mahasiswa->id}.kehadiran", $nilai->kehadiran ?? '') }}"
                                                   min="0" 
                                                   max="100" 
                                                   step="0.01"
                                                   oninput="calculateNilai({{ $mahasiswa->id }})"
                                                   class="nilai-input w-20 px-2 py-1 border border-gray-300 rounded text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </td>
                                    @endif
                                    @if($komponen->tugas > 0)
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <input type="number" 
                                                   name="entries[{{ $mahasiswa->id }}][tugas]"
                                                   value="{{ old("entries.{$mahasiswa->id}.tugas", $nilai->tugas ?? '') }}"
                                                   min="0" 
                                                   max="100" 
                                                   step="0.01"
                                                   oninput="calculateNilai({{ $mahasiswa->id }})"
                                                   class="nilai-input w-20 px-2 py-1 border border-gray-300 rounded text-center focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        </td>
                                    @endif
                                    @if($komponen->kuis > 0)
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <input type="number" 
                                                   name="entries[{{ $mahasiswa->id }}][kuis]"
                                                   value="{{ old("entries.{$mahasiswa->id}.kuis", $nilai->kuis ?? '') }}"
                                                   min="0" 
                                                   max="100" 
                                                   step="0.01"
                                                   oninput="calculateNilai({{ $mahasiswa->id }})"
                                                   class="nilai-input w-20 px-2 py-1 border border-gray-300 rounded text-center focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                        </td>
                                    @endif
                                    @if($komponen->project > 0)
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <input type="number" 
                                                   name="entries[{{ $mahasiswa->id }}][project]"
                                                   value="{{ old("entries.{$mahasiswa->id}.project", $nilai->project ?? '') }}"
                                                   min="0" 
                                                   max="100" 
                                                   step="0.01"
                                                   oninput="calculateNilai({{ $mahasiswa->id }})"
                                                   class="nilai-input w-20 px-2 py-1 border border-gray-300 rounded text-center focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </td>
                                    @endif
                                    @if($komponen->uts > 0)
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <input type="number" 
                                                   name="entries[{{ $mahasiswa->id }}][uts]"
                                                   value="{{ old("entries.{$mahasiswa->id}.uts", $nilai->uts ?? '') }}"
                                                   min="0" 
                                                   max="100" 
                                                   step="0.01"
                                                   oninput="calculateNilai({{ $mahasiswa->id }})"
                                                   class="nilai-input w-20 px-2 py-1 border border-gray-300 rounded text-center focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        </td>
                                    @endif
                                    @if($komponen->uas > 0)
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <input type="number" 
                                                   name="entries[{{ $mahasiswa->id }}][uas]"
                                                   value="{{ old("entries.{$mahasiswa->id}.uas", $nilai->uas ?? '') }}"
                                                   min="0" 
                                                   max="100" 
                                                   step="0.01"
                                                   oninput="calculateNilai({{ $mahasiswa->id }})"
                                                   class="nilai-input w-20 px-2 py-1 border border-gray-300 rounded text-center focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        </td>
                                    @endif
                                    
                                    <td class="px-4 py-3 whitespace-nowrap text-center bg-yellow-50">
                                        <span class="text-sm font-bold text-gray-900 nilai-akhir" data-mahasiswa-id="{{ $mahasiswa->id }}">
                                            {{ $nilai ? number_format($nilai->nilai_akhir, 2) : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center bg-yellow-50">
                                        @php
                                            $hurufMutu = $nilai ? $nilai->huruf_mutu : null;
                                            $hurufMutuClass = 'bg-gray-100 text-gray-800';
                                            if ($hurufMutu) {
                                                switch($hurufMutu) {
                                                    case 'A': $hurufMutuClass = 'bg-green-100 text-green-800'; break;
                                                    case 'B': $hurufMutuClass = 'bg-blue-100 text-blue-800'; break;
                                                    case 'C': $hurufMutuClass = 'bg-yellow-100 text-yellow-800'; break;
                                                    case 'D': $hurufMutuClass = 'bg-orange-100 text-orange-800'; break;
                                                    case 'E': $hurufMutuClass = 'bg-red-100 text-red-800'; break;
                                                }
                                            }
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded huruf-mutu {{ $hurufMutuClass }}" data-mahasiswa-id="{{ $mahasiswa->id }}">
                                            {{ $hurufMutu ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-user-graduate text-4xl mb-4"></i>
                                            <p class="text-lg font-medium">Tidak ada mahasiswa pada semester ini</p>
                                            <p class="text-sm mt-2">Tidak ada mahasiswa yang terdaftar di semester {{ $matakuliah->semester }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($mahasiswas) > 0)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-2"></i>
                            Total mahasiswa: <strong>{{ count($mahasiswas) }}</strong> | 
                            Nilai akan dihitung otomatis berdasarkan bobot komponen penilaian
                        </p>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('nilai.list') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i>Simpan Nilai
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </form>
    @endif

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mr-3 mt-0.5"></i>
            <div class="text-sm text-blue-700">
                <p class="font-semibold mb-2">Cara Input Nilai:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Masukkan nilai untuk setiap komponen penilaian (0-100)</li>
                    <li>Nilai akhir akan dihitung otomatis berdasarkan bobot komponen yang telah ditentukan</li>
                    <li>Huruf mutu akan ditentukan otomatis: A (≥85), B (75-84), C (65-74), D (55-64), E (<55)</li>
                    <li>Anda dapat menyimpan sebagian nilai saja, tidak harus semua sekaligus</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@php
    $komponen = $matakuliah->komponenPenilaian;
@endphp

<script>
    // Bobot komponen penilaian
    const bobot = {
        kehadiran: {{ $komponen->kehadiran ?? 0 }},
        tugas: {{ $komponen->tugas ?? 0 }},
        kuis: {{ $komponen->kuis ?? 0 }},
        project: {{ $komponen->project ?? 0 }},
        uts: {{ $komponen->uts ?? 0 }},
        uas: {{ $komponen->uas ?? 0 }}
    };

    function calculateNilai(mahasiswaId) {
        const row = document.querySelector(`tr[data-mahasiswa-id="${mahasiswaId}"]`);
        if (!row) return;

        const inputs = row.querySelectorAll('.nilai-input');
        let totalNilai = 0;
        let hasAnyValue = false;

        // Hitung total nilai berdasarkan rumus: Σ(nilai_komponen × bobot_komponen) / 100
        inputs.forEach(input => {
            // Ambil komponen penilaian dari name attribute
            // Format: entries[ID][komponen] -> ambil komponen
            const nameMatch = input.name.match(/\[(\d+)\]\[(\w+)\]$/);
            if (nameMatch && nameMatch[2]) {
                const komponen = nameMatch[2];
                const nilaiStr = String(input.value || '').trim();
                
                // Cek apakah input memiliki nilai
                if (nilaiStr !== '' && nilaiStr !== null && nilaiStr !== undefined) {
                    const nilai = parseFloat(nilaiStr);
                    
                    // Jika nilai valid (termasuk 0)
                    if (!isNaN(nilai) && nilai >= 0) {
                        hasAnyValue = true;
                        
                        // Hitung kontribusi nilai ini terhadap total berdasarkan bobot
                        if (bobot[komponen] !== undefined && bobot[komponen] > 0) {
                            // Rumus: nilai × (bobot / 100)
                            // Contoh: 100 × (10 / 100) = 10
                            totalNilai += (nilai * bobot[komponen]) / 100;
                        }
                    }
                }
            }
        });

        // Update nilai akhir
        const nilaiAkhirEl = document.querySelector(`.nilai-akhir[data-mahasiswa-id="${mahasiswaId}"]`);
        if (nilaiAkhirEl) {
            if (hasAnyValue) {
                nilaiAkhirEl.textContent = totalNilai.toFixed(2);
            } else {
                nilaiAkhirEl.textContent = '-';
            }
        }

        // Update huruf mutu berdasarkan nilai akhir
        const hurufMutuEl = document.querySelector(`.huruf-mutu[data-mahasiswa-id="${mahasiswaId}"]`);
        if (hurufMutuEl) {
            let huruf = '-';
            let bgColor = 'bg-gray-100 text-gray-800';
            
            // Jika ada nilai yang diinput, hitung huruf mutu
            if (hasAnyValue) {
                // Pastikan totalNilai valid
                if (!isNaN(totalNilai) && totalNilai >= 0) {
                    if (totalNilai >= 85) {
                        huruf = 'A';
                        bgColor = 'bg-green-100 text-green-800';
                    } else if (totalNilai >= 75) {
                        huruf = 'B';
                        bgColor = 'bg-blue-100 text-blue-800';
                    } else if (totalNilai >= 65) {
                        huruf = 'C';
                        bgColor = 'bg-yellow-100 text-yellow-800';
                    } else if (totalNilai >= 55) {
                        huruf = 'D';
                        bgColor = 'bg-orange-100 text-orange-800';
                    } else {
                        huruf = 'E';
                        bgColor = 'bg-red-100 text-red-800';
                    }
                }
            }
            
            // SELALU update huruf mutu
            hurufMutuEl.textContent = huruf;
            hurufMutuEl.className = `px-2 py-1 text-xs font-semibold rounded ${bgColor}`;
        }
    }

    // Fungsi untuk inisialisasi perhitungan
    function initCalculation() {
        const rows = document.querySelectorAll('.nilai-row');
        rows.forEach(row => {
            const mahasiswaId = row.getAttribute('data-mahasiswa-id');
            if (mahasiswaId) {
                // Hitung dari nilai yang sudah ada di input field
                calculateNilai(mahasiswaId);
            }
        });
    }

    // Calculate all on page load - Pastikan semua nilai dihitung
    function startCalculation() {
        // Tunggu beberapa saat untuk memastikan semua elemen sudah ter-render
        setTimeout(() => {
            initCalculation();
        }, 100);
        
        setTimeout(() => {
            initCalculation();
        }, 300);
        
        setTimeout(() => {
            initCalculation();
        }, 600);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startCalculation);
    } else {
        // DOM sudah siap, langsung hitung
        startCalculation();
    }

    // Pastikan juga hitung saat window fully loaded
    window.addEventListener('load', function() {
        setTimeout(() => {
            initCalculation();
        }, 200);
    });
</script>
@endsection

