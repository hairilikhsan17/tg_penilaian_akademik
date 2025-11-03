@extends('dashboard.dosen')

@section('title', 'Kelola Data Mahasiswa')

@section('page-title', 'Kelola Data Mahasiswa')
@section('page-description', 'Tambah, edit, dan hapus data mahasiswa')

@section('breadcrumb')
    <li><span class="text-gray-500">/</span></li>
    <li class="text-gray-700">Data Mahasiswa</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header dengan Tombol Tambah -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            
        </div>
        <a href="{{ route('mahasiswas.create') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Mahasiswa</span>
        </a>
    </div>

    <!-- Search Box -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="GET" action="{{ route('mahasiswas.index') }}" class="flex gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari berdasarkan NIM atau Nama..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            @if(request('search'))
                <a href="{{ route('mahasiswas.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Mahasiswa -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mahasiswas as $index => $mahasiswa)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">{{ $mahasiswa->nim }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $mahasiswa->nama }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Semester {{ $mahasiswa->semester }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $mahasiswa->jurusan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $mahasiswa->email ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('mahasiswas.password', $mahasiswa) }}" 
                                   class="text-green-600 hover:text-green-900 transition-colors" 
                                   title="Atur Password">
                                    <i class="fas fa-key"></i>
                                </a>
                                <a href="{{ route('mahasiswas.edit', $mahasiswa) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('mahasiswas.destroy', $mahasiswa) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa {{ $mahasiswa->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 transition-colors" 
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-user-graduate text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Tidak ada data mahasiswa</p>
                                <p class="text-sm mt-2">
                                    @if(request('search'))
                                        Tidak ditemukan mahasiswa dengan kata kunci "{{ request('search') }}"
                                    @else
                                        Belum ada mahasiswa terdaftar. Klik tombol "Tambah Mahasiswa" untuk menambahkan data.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($mahasiswas->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $mahasiswas->links() }}
        </div>
        @endif
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
            <div>
                <p class="text-sm text-blue-700">
                    <strong>Total Mahasiswa:</strong> {{ $mahasiswas->total() }} data
                    @if(request('search'))
                        <span class="text-blue-600">(ditemukan {{ $mahasiswas->count() }} hasil untuk "{{ request('search') }}")</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

