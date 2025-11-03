@extends('dashboard.mahasiswa')

@section('title', 'KHS / Transkrip Nilai')

@section('page-title', 'KHS / Transkrip Nilai')
@section('page-description', 'Lihat dan cetak Kartu Hasil Studi (KHS) Anda')

@section('content')
<div class="space-y-6">
    <!-- Header dengan Tombol Cetak -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Kartu Hasil Studi (KHS)</h3>
                <p class="text-sm text-gray-600 mt-1">Nama: <span class="font-semibold">{{ $mahasiswa->nama }}</span> | NIM: <span class="font-semibold">{{ $mahasiswa->nim }}</span></p>
            </div>
            <a href="{{ route('mahasiswa.cetak.khs') }}" target="_blank" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium flex items-center space-x-2">
                <i class="fas fa-file-pdf"></i>
                <span>Cetak PDF</span>
            </a>
        </div>

        <!-- Info IPK -->
        @if($nilai->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <p class="text-sm text-blue-100 mb-1">Total SKS</p>
                <p class="text-2xl font-bold">{{ $totalSKS }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white">
                <p class="text-sm text-green-100 mb-1">IPK (Indeks Prestasi Kumulatif)</p>
                <p class="text-2xl font-bold">{{ number_format($ipk, 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <p class="text-sm text-purple-100 mb-1">Total Mata Kuliah</p>
                <p class="text-2xl font-bold">{{ $nilai->count() }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Tabel KHS -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-list text-purple-600 mr-2"></i>Daftar Mata Kuliah
        </h3>

        @if($nilai->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kode MK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Mata Kuliah</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Semester</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">SKS</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Nilai Akhir</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Huruf Mutu</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Bobot</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($nilai as $index => $item)
                            @php
                                $hurufMutu = $item->huruf_mutu ?? '-';
                                $nilaiAkhir = $item->nilai_akhir ?? 0;
                                $sks = $item->matakuliah->sks ?? 0;
                                
                                // Konversi huruf mutu ke bobot
                                $bobot = 0;
                                if ($hurufMutu == 'A') $bobot = 4;
                                elseif ($hurufMutu == 'B') $bobot = 3;
                                elseif ($hurufMutu == 'C') $bobot = 2;
                                elseif ($hurufMutu == 'D') $bobot = 1;
                                elseif ($hurufMutu == 'E') $bobot = 0;
                                else {
                                    if ($nilaiAkhir >= 85) $bobot = 4;
                                    elseif ($nilaiAkhir >= 75) $bobot = 3;
                                    elseif ($nilaiAkhir >= 65) $bobot = 2;
                                    elseif ($nilaiAkhir >= 55) $bobot = 1;
                                    else $bobot = 0;
                                }
                                
                                $nilaiKualitas = $sks * $bobot;
                                
                                // Warna berdasarkan huruf mutu
                                $gradeBg = '';
                                $gradeText = '';
                                if ($hurufMutu != '-') {
                                    switch($hurufMutu) {
                                        case 'A': $gradeBg = 'bg-green-100'; $gradeText = 'text-green-800'; break;
                                        case 'B': $gradeBg = 'bg-blue-100'; $gradeText = 'text-blue-800'; break;
                                        case 'C': $gradeBg = 'bg-yellow-100'; $gradeText = 'text-yellow-800'; break;
                                        case 'D': $gradeBg = 'bg-orange-100'; $gradeText = 'text-orange-800'; break;
                                        case 'E': $gradeBg = 'bg-red-100'; $gradeText = 'text-red-800'; break;
                                        default: $gradeBg = 'bg-gray-100'; $gradeText = 'text-gray-800';
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $item->matakuliah->kode_mk ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->matakuliah->nama_mk ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-700">{{ $item->matakuliah->semester ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-700">{{ $sks }}</td>
                                <td class="px-4 py-3 text-sm text-center font-semibold text-gray-900">
                                    {{ $nilaiAkhir > 0 ? number_format($nilaiAkhir, 2) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($hurufMutu != '-')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $gradeBg }} {{ $gradeText }}">
                                            {{ $hurufMutu }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-700">
                                    {{ $nilaiKualitas > 0 ? number_format($nilaiKualitas, 2) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                        <!-- Total Row -->
                        <tr class="bg-gray-50 font-semibold">
                            <td colspan="4" class="px-4 py-3 text-sm text-gray-900 text-right">TOTAL</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-900">{{ $totalSKS }}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-900">-</td>
                            <td class="px-4 py-3 text-center">-</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-900">
                                {{ number_format($nilai->sum(function($item) {
                                    $sks = $item->matakuliah->sks ?? 0;
                                    $hurufMutu = $item->huruf_mutu ?? '-';
                                    $nilaiAkhir = $item->nilai_akhir ?? 0;
                                    
                                    $bobot = 0;
                                    if ($hurufMutu == 'A') $bobot = 4;
                                    elseif ($hurufMutu == 'B') $bobot = 3;
                                    elseif ($hurufMutu == 'C') $bobot = 2;
                                    elseif ($hurufMutu == 'D') $bobot = 1;
                                    elseif ($hurufMutu == 'E') $bobot = 0;
                                    else {
                                        if ($nilaiAkhir >= 85) $bobot = 4;
                                        elseif ($nilaiAkhir >= 75) $bobot = 3;
                                        elseif ($nilaiAkhir >= 65) $bobot = 2;
                                        elseif ($nilaiAkhir >= 55) $bobot = 1;
                                        else $bobot = 0;
                                    }
                                    
                                    return $sks * $bobot;
                                }), 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-clipboard-list text-5xl mb-4"></i>
                <p class="text-lg font-medium mb-2">Belum ada data KHS</p>
                <p class="text-sm">Data KHS akan ditampilkan setelah ada nilai yang diinput oleh dosen</p>
            </div>
        @endif
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-0.5"></i>
            <div>
                <p class="text-blue-800 font-medium mb-1">Informasi KHS / Transkrip Nilai</p>
                <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                    <li>KHS menampilkan semua nilai mata kuliah yang telah Anda ambil</li>
                    <li>IPK dihitung berdasarkan bobot nilai (A=4, B=3, C=2, D=1, E=0) dikalikan dengan SKS</li>
                    <li>Anda dapat mencetak KHS dalam format PDF untuk keperluan administrasi</li>
                    <li>Klik tombol "Cetak PDF" untuk mengunduh atau mencetak KHS</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
