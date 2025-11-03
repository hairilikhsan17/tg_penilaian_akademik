@extends('dashboard.mahasiswa')

@section('title', 'Nilai Akademik')

@section('page-title', 'Nilai Akademik')
@section('page-description', 'Lihat daftar nilai mata kuliah Anda')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-filter text-blue-600 mr-2"></i>Filter Nilai
        </h3>
        <form method="GET" action="{{ route('mahasiswa.nilai') }}" class="flex items-end space-x-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Berdasarkan Semester</label>
                <select name="semester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Semester</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                            Semester {{ $semester }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            @if(request('semester'))
                <a href="{{ route('mahasiswa.nilai') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Nilai -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-list text-purple-600 mr-2"></i>Daftar Nilai Mata Kuliah
            </h3>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                Total: {{ $nilai->count() }} mata kuliah
            </span>
        </div>

        @if($nilai->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase">No</th>
                            <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kode MK</th>
                            <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Mata Kuliah</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase">SMT</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase">SKS</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase bg-blue-50">Hadir</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase bg-green-50">Tugas</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase bg-purple-50">Quiz</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase bg-indigo-50">Project</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase bg-orange-50">UTS</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase bg-red-50">UAS</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase bg-yellow-50">Nilai Akhir</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase bg-yellow-50">Huruf Mutu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($nilai as $index => $item)
                            @php
                                $hurufMutu = $item->huruf_mutu ?? '-';
                                $nilaiAkhir = $item->nilai_akhir ?? 0;
                                
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
                                <td class="px-2 py-3 text-center text-sm text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">{{ $item->matakuliah->kode_mk ?? '-' }}</span>
                                </td>
                                <td class="px-2 py-3">
                                    <span class="text-sm font-medium text-gray-900">{{ $item->matakuliah->nama_mk ?? '-' }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center text-sm text-gray-700">{{ $item->matakuliah->semester ?? '-' }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-center text-sm text-gray-700">{{ $item->matakuliah->sks ?? '-' }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-center text-sm text-gray-700 bg-blue-50">{{ number_format($item->kehadiran ?? 0, 2) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-center text-sm text-gray-700 bg-green-50">{{ number_format($item->tugas ?? 0, 2) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-center text-sm text-gray-700 bg-purple-50">{{ number_format($item->kuis ?? 0, 2) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-center text-sm text-gray-700 bg-indigo-50">{{ number_format($item->project ?? 0, 2) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-center text-sm text-gray-700 bg-orange-50">{{ number_format($item->uts ?? 0, 2) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-center text-sm text-gray-700 bg-red-50">{{ number_format($item->uas ?? 0, 2) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-center font-semibold text-gray-900 bg-yellow-50">
                                    {{ $nilaiAkhir > 0 ? number_format($nilaiAkhir, 2) : '-' }}
                                </td>
                                <td class="px-2 py-3 text-center bg-yellow-50">
                                    @if($hurufMutu != '-')
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $gradeBg }} {{ $gradeText }}">
                                            {{ $hurufMutu }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                            -
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-clipboard-list text-5xl mb-4"></i>
                <p class="text-lg font-medium mb-2">Belum ada data nilai</p>
                <p class="text-sm">Nilai akan ditampilkan setelah dosen menginput nilai untuk Anda</p>
            </div>
        @endif
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-0.5"></i>
            <div>
                <p class="text-blue-800 font-medium mb-1">Informasi Nilai Akademik</p>
                <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                    <li>Nilai ditampilkan berdasarkan mata kuliah yang telah diinput oleh dosen</li>
                    <li>Gunakan filter semester untuk menyaring data berdasarkan semester tertentu</li>
                    <li>Huruf mutu: A (≥85), B (75-84), C (65-74), D (55-64), E (<55)</li>
                    <li>Nilai akhir dihitung berdasarkan komponen penilaian yang ditetapkan dosen</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
