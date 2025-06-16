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
                        <p class="text-2xl font-bold text-gray-800">{{ $lokasis->total() }}</p>
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
                        <p class="text-2xl font-bold text-gray-800">{{ $lokasis->where('status', 'aktif')->count() }}</p>
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
                        <p class="text-2xl font-bold text-gray-800">{{ $lokasis->where('jenis', 'kota')->count() }}</p>
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
                        <p class="text-2xl font-bold text-gray-800">{{ $lokasis->sum('kapasitas') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Locations Table -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold">
                        <i class="fas fa-table text-xs"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-800 font-medium">
                            <i class="fas fa-list text-red-600 mr-2"></i>
                            Daftar Lokasi Donor
                        </h3>
                        <p class="text-sm text-gray-600">Total: {{ $lokasis->total() }} lokasi terdaftar</p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lokasi
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="lokasiTableBody">
                        @forelse($lokasis as $index => $lokasi)
                            <tr class="hover:bg-gray-50 lokasi-row" 
                                data-status="{{ $lokasi->status }}"
                                data-search="{{ strtolower($lokasi->nama . ' ' . $lokasi->alamat . ' ' . $lokasi->kota) }}">
                                
                                <!-- No -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $lokasis->firstItem() + $index }}
                                </td>

                                <!-- Lokasi -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if($lokasi->gambar)
                                                <img src="{{ asset($lokasi->gambar) }}" 
                                                     alt="{{ $lokasi->nama }}" 
                                                     class="h-12 w-12 object-cover rounded-lg">
                                            @else
                                                <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-hospital text-red-600"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 lokasi-nama">
                                                {{ $lokasi->nama }}
                                            </div>
                                            @if($lokasi->kapasitas)
                                                <div class="text-xs text-gray-500">
                                                    <i class="fas fa-users mr-1"></i>
                                                    Kapasitas: {{ $lokasi->kapasitas }} orang
                                                </div>
                                            @endif
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
                                            {{ $lokasi->kontak }}
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
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded" 
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <a href="{{ route('admin.lokasis.edit', $lokasi->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 p-1 rounded" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button onclick="deleteLokasi({{ $lokasi->id }}, '{{ $lokasi->nama }}')" 
                                                class="text-red-600 hover:text-red-900 p-1 rounded" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyState">
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
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
                    {{ $lokasis->appends(request()->query())->links() }}
                </div>
            @endif
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
                    <button onclick="closeModal('viewModal')" class="text-gray-400 hover:text-gray-600">
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
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Hapus Lokasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    document.getElementById('searchInput').addEventListener('input', filterLokasi);
    document.getElementById('statusFilter').addEventListener('change', filterLokasi);
});

function filterLokasi() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('.lokasi-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const matchSearch = search === '' || row.dataset.search.includes(search);
        const matchStatus = status === '' || row.dataset.status === status;
        
        if (matchSearch && matchStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Show/hide empty state
    const emptyState = document.getElementById('emptyState');
    if (emptyState) {
        emptyState.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }
}

// View lokasi details
function viewLokasi(lokasiId) {
    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewContent').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';
    
    fetch(`/admin/lokasis/${lokasiId}`)
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
                            <img src="${data.gambar}" alt="${data.nama}" class="max-w-full h-48 object-cover rounded-lg mx-auto">
                        </div>
                    ` : ''}
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-red-50 rounded-lg p-4">
                            <h4 class="font-semibold text-red-800 mb-3">Informasi Lokasi</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Nama:</span> ${data.nama}</div>
                                <div><span class="font-medium">Kota:</span> ${data.kota}</div>
                                <div><span class="font-medium">Jenis:</span> ${data.jenis || 'Kota'}</div>
                                <div><span class="font-medium">Status:</span> 
                                    <span class="px-2 py-1 rounded text-xs ${data.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                        ${data.status === 'aktif' ? 'Aktif' : 'Tidak Aktif'}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-800 mb-3">Kontak & Operasional</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Kontak:</span> ${data.kontak || '-'}</div>
                                <div><span class="font-medium">Kapasitas:</span> ${data.kapasitas ? data.kapasitas + ' orang' : '-'}</div>
                                <div><span class="font-medium">Jam Buka:</span> ${data.jam_buka || '-'}</div>
                                <div><span class="font-medium">Jam Tutup:</span> ${data.jam_tutup || '-'}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-3">Alamat Lengkap</h4>
                        <p class="text-sm text-gray-700">${data.alamat}</p>
                        ${data.latitude && data.longitude ? `
                            <div class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                Koordinat: ${data.latitude}, ${data.longitude}
                            </div>
                        ` : ''}
                    </div>
                    
                    ${data.deskripsi ? `
                        <div class="bg-green-50 rounded-lg p-4">
                            <h4 class="font-semibold text-green-800 mb-2">Deskripsi</h4>
                            <p class="text-sm text-green-700">${data.deskripsi}</p>
                        </div>
                    ` : ''}
                    
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h4 class="font-semibold text-yellow-800 mb-2">Tanggal Operasional</h4>
                        <p class="text-sm text-yellow-700">
                            ${data.tanggal_operasional ? new Date(data.tanggal_operasional).toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            }) : 'Belum ditentukan'}
                        </p>
                    </div>
                </div>
            `;
            
            document.getElementById('viewContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('viewContent').innerHTML = '<div class="text-center py-8 text-red-600">Gagal memuat detail lokasi: ' + error.message + '</div>';
        });
}

// Delete lokasi
function deleteLokasi(lokasiId, namaLokasi) {
    document.getElementById('deleteMessage').textContent = `Yakin ingin menghapus lokasi "${namaLokasi}"? Data yang terkait juga akan terhapus!`;
    document.getElementById('deleteForm').action = `/admin/lokasis/${lokasiId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Close modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Refresh data
function refreshData() {
    location.reload();
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
</script>
@endsection
