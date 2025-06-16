@extends('dashboardlayout.app')

@section('page-title', 'Manajemen Lokasi Donor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="text-center md:text-left">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto md:mx-0 mb-4">
                        <i class="fas fa-map-marker-alt text-red-600 text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Manajemen Lokasi Donor</h1>
                    <p class="text-gray-600">Kelola semua lokasi PMI untuk donor darah</p>
                </div>
                <div class="mt-4 md:mt-0 flex gap-2">
                    <a href="{{ route('admin.lokasis.create') }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Lokasi
                    </a>
                    <button onclick="refreshData()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-map-marker-alt text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Lokasi</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $lokasis->total() ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lokasi Aktif</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $lokasis->where('status', 'aktif')->count() ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-building text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">PMI Kota</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $lokasis->where('jenis', 'kota')->count() ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-users text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Kapasitas</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $lokasis->sum('kapasitas') ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Filter & Search -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-start space-x-4 mb-4">
                <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-1">
                    <i class="fas fa-filter text-xs"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-gray-800 font-medium mb-3">
                        <i class="fas fa-search text-red-600 mr-2"></i>
                        Filter & Pencarian Data
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Lokasi</label>
                            <div class="relative">
                                <input type="text" 
                                       id="searchInput"
                                       placeholder="Cari nama PMI, alamat, atau kota..."
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="statusFilter" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                            <select id="jenisFilter" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Semua Jenis</option>
                                <option value="kota">PMI Kota</option>
                                <option value="kabupaten">PMI Kabupaten</option>
                                <option value="provinsi">PMI Provinsi</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex gap-2">
                        <button onclick="clearFilters()" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                            <i class="fas fa-eraser mr-1"></i>
                            Clear Filter
                        </button>
                        <button onclick="exportData()" 
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                            <i class="fas fa-download mr-1"></i>
                            Export Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Locations Table -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold">
                            <i class="fas fa-table text-xs"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-800 font-medium">
                                <i class="fas fa-list text-red-600 mr-2"></i>
                                Daftar Lokasi Donor
                            </h3>
                            <p class="text-sm text-gray-600">Total: <span id="totalCount">{{ $lokasis->total() ?? 0 }}</span> lokasi terdaftar</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <select id="perPageSelect" onchange="changePerPage()" 
                                class="text-sm border border-gray-300 rounded px-2 py-1">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per halaman</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per halaman</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per halaman</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per halaman</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" 
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('nama')">
                                Lokasi <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Alamat & Kota
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Operasional
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kontak
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('status')">
                                Status <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="lokasiTableBody">
                        @forelse($lokasis as $index => $lokasi)
                            <tr class="hover:bg-gray-50 lokasi-row transition-colors" 
                                data-status="{{ $lokasi->status }}"
                                data-jenis="{{ $lokasi->jenis ?? 'kota' }}"
                                data-search="{{ strtolower($lokasi->nama . ' ' . $lokasi->alamat . ' ' . $lokasi->kota) }}">
                                
                                <!-- Checkbox -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="selected_lokasi[]" value="{{ $lokasi->id }}" 
                                           class="lokasi-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500">
                                </td>

                                <!-- No -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $lokasis->firstItem() + $index }}
                                </td>

                                <!-- Lokasi -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if($lokasi->gambar)
                                                @if(str_starts_with($lokasi->gambar, 'lokasi-images/'))
                                                    {{-- Gambar baru --}}
                                                    <img src="{{ asset('storage/' . $lokasi->gambar) }}" 
                                                        alt="{{ $lokasi->nama }}" 
                                                        class="h-12 w-12 object-cover rounded-lg border border-gray-200"
                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                @else
                                                    {{-- Gambar lama --}}
                                                    <img src="{{ asset($lokasi->gambar) }}" 
                                                        alt="{{ $lokasi->nama }}" 
                                                        class="h-12 w-12 object-cover rounded-lg border border-gray-200"
                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                @endif
                                                
                                                {{-- Fallback jika gambar tidak bisa dimuat --}}
                                                <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center" style="display: none;">
                                                    <i class="fas fa-hospital text-red-600"></i>
                                                </div>
                                            @else
                                                {{-- Default icon jika tidak ada gambar --}}
                                                <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-hospital text-red-600"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 lokasi-nama">
                                                {{ $lokasi->nama }}
                                            </div>
                                            <div class="text-xs text-gray-500 flex items-center space-x-3">
                                                @if($lokasi->kapasitas)
                                                    <span>
                                                        <i class="fas fa-users mr-1"></i>
                                                        {{ $lokasi->kapasitas }} orang
                                                    </span>
                                                @endif
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                                    {{ ucfirst($lokasi->jenis ?? 'kota') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Alamat & Kota -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 lokasi-alamat">
                                        {{ Str::limit($lokasi->alamat, 60) }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $lokasi->kota }}
                                        @if($lokasi->latitude && $lokasi->longitude)
                                            <a href="https://maps.google.com/?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}" 
                                               target="_blank" 
                                               class="ml-2 text-blue-600 hover:text-blue-800" 
                                               title="Lihat di Google Maps">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <!-- Operasional -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($lokasi->tanggal_operasional)
                                        <div class="text-sm text-gray-900">
                                            <i class="fas fa-calendar mr-1 text-gray-400"></i>
                                            {{ $lokasi->tanggal_operasional->format('d/m/Y') }}
                                        </div>
                                    @endif
                                    @if($lokasi->jam_buka && $lokasi->jam_tutup)
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $lokasi->jam_buka }} - {{ $lokasi->jam_tutup }}
                                        </div>
                                    @endif
                                    @if(!$lokasi->tanggal_operasional && (!$lokasi->jam_buka || !$lokasi->jam_tutup))
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Kontak -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($lokasi->kontak)
                                        <div class="flex items-center">
                                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                                            <a href="tel:{{ $lokasi->kontak }}" 
                                               class="text-blue-600 hover:text-blue-800 hover:underline">
                                                {{ $lokasi->kontak }}
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full lokasi-status
                                        @if($lokasi->status === 'aktif') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($lokasi->status === 'aktif') 
                                            <i class="fas fa-check-circle mr-1"></i> Aktif
                                        @else 
                                            <i class="fas fa-times-circle mr-1"></i> Tidak Aktif
                                        @endif
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="viewLokasi({{ $lokasi->id }})" 
                                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors" 
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <a href="{{ route('admin.lokasis.edit', $lokasi->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50 transition-colors" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button onclick="toggleStatus({{ $lokasi->id }}, '{{ $lokasi->status }}')" 
                                                class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-50 transition-colors" 
                                                title="Toggle Status">
                                            <i class="fas fa-toggle-{{ $lokasi->status === 'aktif' ? 'on' : 'off' }}"></i>
                                        </button>
                                        
                                        <button onclick="deleteLokasi({{ $lokasi->id }}, '{{ $lokasi->nama }}')" 
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyState">
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-map-marker-alt text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-lg font-medium">Belum ada data lokasi donor</p>
                                    <p class="text-sm text-gray-400 mb-4">Mulai tambahkan lokasi PMI untuk donor darah</p>
                                    <a href="{{ route('admin.lokasis.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Lokasi Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($lokasis->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $lokasis->firstItem() ?? 0 }} sampai {{ $lokasis->lastItem() ?? 0 }} 
                            dari {{ $lokasis->total() ?? 0 }} lokasi
                        </div>
                        <div>
                            {{ $lokasis->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bulk Actions -->
            <div id="bulkActions" class="hidden px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        <span id="selectedCount">0</span> lokasi dipilih
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="bulkToggleStatus('aktif')" 
                                class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                            <i class="fas fa-check mr-1"></i>Aktifkan
                        </button>
                        <button onclick="bulkToggleStatus('tidak_aktif')" 
                                class="bg-yellow-600 text-white px-3 py-1 rounded text-sm hover:bg-yellow-700">
                            <i class="fas fa-pause mr-1"></i>Non-aktifkan
                        </button>
                        <button onclick="bulkDelete()" 
                                class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="bg-blue-50 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">💡 Panduan Lokasi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700">
                <div>
                    <h4 class="font-semibold mb-2">Status Lokasi:</h4>
                    <ul class="space-y-1">
                        <li>• <strong>Aktif:</strong> Lokasi beroperasi normal</li>
                        <li>• <strong>Tidak Aktif:</strong> Lokasi sementara tutup</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Aksi yang Tersedia:</h4>
                    <ul class="space-y-1">
                        <li>• <i class="fas fa-eye text-blue-600"></i> Lihat detail lengkap lokasi</li>
                        <li>• <i class="fas fa-edit text-yellow-600"></i> Edit informasi lokasi</li>
                        <li>• <i class="fas fa-toggle-on text-purple-600"></i> Toggle status aktif/non-aktif</li>
                        <li>• <i class="fas fa-trash text-red-600"></i> Hapus lokasi (hati-hati!)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Detail Lokasi Donor</h3>
                    <button onclick="closeModal('viewModal')" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="viewContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-600">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
                
                <p class="text-gray-700 mb-6" id="deleteMessage"></p>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeModal('deleteModal')" 
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-1"></i>Hapus Lokasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
            <span class="text-gray-700">Memproses...</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize filters
    document.getElementById('searchInput').addEventListener('input', debounce(filterLokasi, 300));
    document.getElementById('statusFilter').addEventListener('change', filterLokasi);
    document.getElementById('jenisFilter').addEventListener('change', filterLokasi);
    
    // Initialize checkbox handlers
    document.querySelectorAll('.lokasi-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
    
    updateBulkActions();
});

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Filter functionality
function filterLokasi() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const jenis = document.getElementById('jenisFilter').value;
    
    const rows = document.querySelectorAll('.lokasi-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const matchSearch = search === '' || row.dataset.search.includes(search);
        const matchStatus = status === '' || row.dataset.status === status;
        const matchJenis = jenis === '' || row.dataset.jenis === jenis;
        
        if (matchSearch && matchStatus && matchJenis) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update count
    document.getElementById('totalCount').textContent = visibleCount;

    // Show/hide empty state
    const emptyState = document.getElementById('emptyState');
    if (emptyState) {
        emptyState.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }
}

// Clear all filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('jenisFilter').value = '';
    filterLokasi();
}

// Export data functionality
function exportData() {
    const params = new URLSearchParams({
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value,
        jenis: document.getElementById('jenisFilter').value,
        export: 'excel'
    });
    
    window.location.href = `{{ route('admin.lokasis.index') }}?${params.toString()}`;
}

// Change items per page
function changePerPage() {
    const perPage = document.getElementById('perPageSelect').value;
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page
    window.location.href = url.toString();
}

// Sort table functionality
let sortDirection = {};
function sortTable(column) {
    const tbody = document.getElementById('lokasiTableBody');
    const rows = Array.from(tbody.querySelectorAll('.lokasi-row:not([style*="display: none"])'));
    
    // Toggle sort direction
    sortDirection[column] = sortDirection[column] === 'asc' ? 'desc' : 'asc';
    
    rows.sort((a, b) => {
        let aVal, bVal;
        
        if (column === 'nama') {
            aVal = a.querySelector('.lokasi-nama').textContent.trim();
            bVal = b.querySelector('.lokasi-nama').textContent.trim();
        } else if (column === 'status') {
            aVal = a.querySelector('.lokasi-status').textContent.trim();
            bVal = b.querySelector('.lokasi-status').textContent.trim();
        }
        
        if (sortDirection[column] === 'asc') {
            return aVal.localeCompare(bVal);
        } else {
            return bVal.localeCompare(aVal);
        }
    });
    
    // Reorder rows
    rows.forEach(row => tbody.appendChild(row));
    
    // Update sort indicators
    document.querySelectorAll('th .fa-sort').forEach(icon => {
        icon.className = 'fas fa-sort ml-1';
    });
    
    const currentIcon = document.querySelector(`th[onclick="sortTable('${column}')"] .fa-sort`);
    if (currentIcon) {
        currentIcon.className = `fas fa-sort-${sortDirection[column] === 'asc' ? 'up' : 'down'} ml-1`;
    }
}

// Select all functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.lokasi-checkbox');
    
    checkboxes.forEach(checkbox => {
        const row = checkbox.closest('.lokasi-row');
        if (row.style.display !== 'none') {
            checkbox.checked = selectAll.checked;
        }
    });
    
    updateBulkActions();
}

// Update bulk actions visibility
function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.lokasi-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkedBoxes.length > 0) {
        bulkActions.classList.remove('hidden');
        selectedCount.textContent = checkedBoxes.length;
    } else {
        bulkActions.classList.add('hidden');
    }
    
    // Update select all checkbox
    const allCheckboxes = document.querySelectorAll('.lokasi-checkbox');
    const visibleCheckboxes = Array.from(allCheckboxes).filter(cb => 
        cb.closest('.lokasi-row').style.display !== 'none'
    );
    const checkedVisibleBoxes = visibleCheckboxes.filter(cb => cb.checked);
    
    const selectAll = document.getElementById('selectAll');
    selectAll.checked = visibleCheckboxes.length > 0 && checkedVisibleBoxes.length === visibleCheckboxes.length;
    selectAll.indeterminate = checkedVisibleBoxes.length > 0 && checkedVisibleBoxes.length < visibleCheckboxes.length;
}

// View lokasi details
function viewLokasi(lokasiId) {
    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewContent').innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <p class="mt-2 text-gray-500">Memuat data...</p>
        </div>
    `;
    
    fetch(`/admin/lokasis/${lokasiId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        let html = `
            <div class="space-y-6">
                ${data.gambar ? `
                    <div class="text-center">
                        <img src="${data.gambar}" alt="${data.nama}" class="max-w-full h-48 object-cover rounded-lg mx-auto border border-gray-200">
                    </div>
                ` : `
                    <div class="text-center">
                        <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center mx-auto">
                            <i class="fas fa-hospital text-gray-400 text-4xl"></i>
                        </div>
                    </div>
                `}
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-red-50 rounded-lg p-4">
                        <h4 class="font-semibold text-red-800 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>Informasi Lokasi
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex">
                                <span class="font-medium w-20">Nama:</span> 
                                <span class="flex-1">${data.nama || '-'}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-20">Kota:</span> 
                                <span class="flex-1">${data.kota || '-'}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-20">Jenis:</span> 
                                <span class="flex-1">${data.jenis ? data.jenis.charAt(0).toUpperCase() + data.jenis.slice(1) : 'Kota'}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-20">Status:</span> 
                                <span class="flex-1">
                                    <span class="px-2 py-1 rounded text-xs ${data.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                        <i class="fas ${data.status === 'aktif' ? 'fa-check-circle' : 'fa-times-circle'} mr-1"></i>
                                        ${data.status === 'aktif' ? 'Aktif' : 'Tidak Aktif'}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-clock mr-2"></i>Kontak & Operasional
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex">
                                <span class="font-medium w-20">Kontak:</span> 
                                <span class="flex-1">${data.kontak ? `<a href="tel:${data.kontak}" class="text-blue-600 hover:underline">${data.kontak}</a>` : '-'}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-20">Kapasitas:</span> 
                                <span class="flex-1">${data.kapasitas ? data.kapasitas + ' orang' : '-'}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-20">Jam Buka:</span> 
                                <span class="flex-1">${data.jam_buka || '-'}</span>
                            </div>
                            <div class="flex">
                                <span class="font-medium w-20">Jam Tutup:</span> 
                                <span class="flex-1">${data.jam_tutup || '-'}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>Alamat Lengkap
                    </h4>
                    <p class="text-sm text-gray-700">${data.alamat || 'Alamat tidak tersedia'}</p>
                    ${data.latitude && data.longitude ? `
                        <div class="mt-3 p-2 bg-white rounded border">
                            <div class="text-xs text-gray-500 mb-1">
                                <i class="fas fa-globe mr-1"></i>Koordinat GPS:
                            </div>
                            <div class="text-sm font-mono text-gray-700">
                                ${data.latitude}, ${data.longitude}
                            </div>
                            <a href="https://maps.google.com/?q=${data.latitude},${data.longitude}" 
                               target="_blank" 
                               class="inline-flex items-center mt-2 text-xs text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt mr-1"></i>Buka di Google Maps
                            </a>
                        </div>
                    ` : ''}
                </div>
                
                ${data.deskripsi ? `
                    <div class="bg-green-50 rounded-lg p-4">
                        <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                            <i class="fas fa-file-alt mr-2"></i>Deskripsi
                        </h4>
                        <p class="text-sm text-green-700">${data.deskripsi}</p>
                    </div>
                ` : ''}
                
                <div class="bg-yellow-50 rounded-lg p-4">
                    <h4 class="font-semibold text-yellow-800 mb-2 flex items-center">
                        <i class="fas fa-calendar mr-2"></i>Tanggal Operasional
                    </h4>
                    <p class="text-sm text-yellow-700">
                        ${data.tanggal_operasional ? 
                            new Date(data.tanggal_operasional).toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            }) : 'Belum ditentukan'}
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-2 pt-4 border-t border-gray-200">
                    <a href="/admin/lokasis/${data.id}/edit" 
                       class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors flex items-center">
                        <i class="fas fa-edit mr-2"></i>Edit Lokasi
                    </a>
                    <button onclick="closeModal('viewModal')" 
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        `;
        
        document.getElementById('viewContent').innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('viewContent').innerHTML = `
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-red-600 mb-2">Gagal Memuat Data</h3>
                <p class="text-sm text-red-500 mb-4">${error.message}</p>
                <button onclick="viewLokasi(${lokasiId})" 
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-redo mr-2"></i>Coba Lagi
                </button>
            </div>
        `;
    });
}

// Toggle status
function toggleStatus(lokasiId, currentStatus) {
    const newStatus = currentStatus === 'aktif' ? 'tidak_aktif' : 'aktif';
    const statusText = newStatus === 'aktif' ? 'mengaktifkan' : 'menonaktifkan';
    
    if (confirm(`Yakin ingin ${statusText} lokasi ini?`)) {
        showLoading();
        
        fetch(`/admin/lokasis/${lokasiId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification('Status berhasil diubah!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Gagal mengubah status!', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showNotification('Terjadi kesalahan!', 'error');
        });
    }
}

// Delete lokasi
function deleteLokasi(lokasiId, namaLokasi) {
    document.getElementById('deleteMessage').textContent = `Yakin ingin menghapus lokasi "${namaLokasi}"? Data yang terkait juga akan terhapus!`;
    document.getElementById('deleteForm').action = `/admin/lokasis/${lokasiId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Bulk operations
function bulkToggleStatus(status) {
    const checkedBoxes = document.querySelectorAll('.lokasi-checkbox:checked');
    const ids = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Pilih lokasi terlebih dahulu!');
        return;
    }
    
    const statusText = status === 'aktif' ? 'mengaktifkan' : 'menonaktifkan';
    if (confirm(`Yakin ingin ${statusText} ${ids.length} lokasi yang dipilih?`)) {
        showLoading();
        
        fetch('/admin/lokasis/bulk-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: ids, status: status })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification(`${data.count} lokasi berhasil diubah statusnya!`, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Gagal mengubah status!', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showNotification('Terjadi kesalahan!', 'error');
        });
    }
}

function bulkDelete() {
    const checkedBoxes = document.querySelectorAll('.lokasi-checkbox:checked');
    const ids = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Pilih lokasi terlebih dahulu!');
        return;
    }
    
    if (confirm(`PERINGATAN: Yakin ingin menghapus ${ids.length} lokasi yang dipilih? Tindakan ini tidak dapat dibatalkan!`)) {
        showLoading();
        
        fetch('/admin/lokasis/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification(`${data.count} lokasi berhasil dihapus!`, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Gagal menghapus lokasi!', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showNotification('Terjadi kesalahan!', 'error');
        });
    }
}

// Utility functions
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function refreshData() {
    location.reload();
}

function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-transform duration-300 translate-x-full`;
    
    if (type === 'success') {
        notification.classList.add('bg-green-600');
        notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
    } else if (type === 'error') {
        notification.classList.add('bg-red-600');
        notification.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
    } else {
        notification.classList.add('bg-blue-600');
        notification.innerHTML = `<i class="fas fa-info-circle mr-2"></i>${message}`;
    }
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Close modals when clicking outside
document.querySelectorAll('.fixed').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.fixed:not(.hidden)').forEach(modal => {
            modal.classList.add('hidden');
        });
    }
});

// Handle form submission for delete
document.getElementById('deleteForm').addEventListener('submit', function(e) {
    showLoading();
});
</script>
@endsection
