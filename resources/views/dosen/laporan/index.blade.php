@extends('dashboard.dosen')

@section('title', 'Laporan Nilai')

@section('page-title', 'Laporan Nilai')
@section('page-description', 'Rekap nilai mahasiswa per mata kuliah')

@section('breadcrumb')
    <li><span class="text-gray-500">/</span></li>
    <li class="text-gray-700">Laporan Nilai</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-filter mr-2 text-blue-600"></i>Filter Laporan
        </h3>
        <form method="GET" action="{{ route('dosen.laporan') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Semester</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                            Semester {{ $semester }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah</label>
                <select name="matakuliah_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Mata Kuliah</option>
                    @foreach($matakuliahs as $matakuliah)
                        <option value="{{ $matakuliah->id }}" {{ request('matakuliah_id') == $matakuliah->id ? 'selected' : '' }}>
                            {{ $matakuliah->kode_mk }} - {{ $matakuliah->nama_mk }} (S{{ $matakuliah->semester }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                @if(request('semester') || request('matakuliah_id'))
                    <a href="{{ route('dosen.laporan') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Export Buttons -->
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 border-l-4 border-blue-500 rounded-lg p-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h4 class="font-semibold text-gray-800">
                    <i class="fas fa-file-export mr-2 text-blue-600"></i>Export Laporan
                </h4>
                <p class="text-sm text-gray-600 mt-1">Cetak atau unduh laporan dalam format PDF atau Excel</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('dosen.laporan.pdf', request()->query()) }}" 
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md hover:shadow-lg">
                    <i class="fas fa-file-pdf mr-2"></i>Cetak PDF
                </a>
                <a href="{{ route('dosen.laporan.excel', request()->query()) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-md hover:shadow-lg">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Tabel Nilai -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <h4 class="text-lg font-semibold">Rekap Nilai Mahasiswa</h4>
            <p class="text-sm text-indigo-100 mt-1">Total data: <strong>{{ $nilai->total() }}</strong> nilai</p>
        </div>

        @if($nilai->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode MK</th>
                            <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Mata Kuliah</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">SMT</th>
                            <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">NIM</th>
                            <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-blue-50">Hadir</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-green-50">Tugas</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-purple-50">Quiz</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-indigo-50">Project</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-orange-50">UTS</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-red-50">UAS</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-yellow-50">Nilai Akhir</th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider bg-yellow-50">Huruf Mutu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($nilai as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-2 py-3 whitespace-nowrap text-center text-sm text-gray-700">
                                    {{ ($nilai->currentPage() - 1) * $nilai->perPage() + $index + 1 }}
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">{{ $item->matakuliah->kode_mk ?? '-' }}</span>
                                </td>
                                <td class="px-2 py-3">
                                    <span class="text-sm font-medium text-gray-900">{{ $item->matakuliah->nama_mk ?? '-' }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-700">{{ $item->matakuliah->semester ?? '-' }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">{{ $item->mahasiswa->nim ?? '-' }}</span>
                                </td>
                                <td class="px-2 py-3">
                                    <span class="text-sm text-gray-900">{{ $item->mahasiswa->nama ?? '-' }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center bg-blue-50">
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($item->kehadiran, 2) }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center bg-green-50">
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($item->tugas, 2) }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center bg-purple-50">
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($item->kuis, 2) }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center bg-indigo-50">
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($item->project, 2) }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center bg-orange-50">
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($item->uts, 2) }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center bg-red-50">
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($item->uas, 2) }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center bg-yellow-50">
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($item->nilai_akhir, 2) }}</span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center bg-yellow-50">
                                    @php
                                        $hurufMutu = $item->huruf_mutu ?? '-';
                                        $hurufMutuClass = 'bg-gray-100 text-gray-800';
                                        if ($hurufMutu != '-') {
                                            switch($hurufMutu) {
                                                case 'A': $hurufMutuClass = 'bg-green-100 text-green-800'; break;
                                                case 'B': $hurufMutuClass = 'bg-blue-100 text-blue-800'; break;
                                                case 'C': $hurufMutuClass = 'bg-yellow-100 text-yellow-800'; break;
                                                case 'D': $hurufMutuClass = 'bg-orange-100 text-orange-800'; break;
                                                case 'E': $hurufMutuClass = 'bg-red-100 text-red-800'; break;
                                            }
                                        }
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $hurufMutuClass }}">
                                        {{ $hurufMutu }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $nilai->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="text-gray-500">
                    <i class="fas fa-file-alt text-4xl mb-4"></i>
                    <p class="text-lg font-medium">Tidak ada data nilai</p>
                    <p class="text-sm mt-2">
                        @if(request('semester') || request('matakuliah_id'))
                            Tidak ada data nilai sesuai filter yang dipilih.
                        @else
                            Belum ada data nilai yang tersedia.
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mr-3 mt-0.5"></i>
            <div class="text-sm text-blue-700">
                <p class="font-semibold mb-2">Informasi Laporan:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Laporan menampilkan semua nilai mahasiswa dari mata kuliah yang Anda ampu</li>
                    <li>Gunakan filter untuk menyaring data berdasarkan semester atau mata kuliah tertentu</li>
                    <li>Anda dapat mencetak atau mengekspor laporan dalam format PDF atau Excel</li>
                    <li>Kolom nilai ditampilkan dengan 2 angka desimal</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

