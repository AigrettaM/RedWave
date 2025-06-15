@extends('dashboardlayout.app')

@section('page-title', 'Manajemen Lokasi Donor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Data Lokasi Donor</h1>
                <p class="text-gray-600">Kelola semua lokasi PMI untuk donor darah</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                <a href="{{ route('lokasis.create') }}" 
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

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
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

            <div class="bg-white rounded-lg shadow-md p-6">
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

            <div class="bg-white rounded-lg shadow-md p-6">
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

            <div class="bg-white rounded-lg shadow-md p-6">
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Lokasi</label>
                    <div class="relative">
                        <input type="text" 
                               id="searchInput"
                               value="{{ request('search') }}"
                               placeholder="Cari nama PMI, alamat, atau kota..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                    <select id="kotaFilter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Kota</option>
                        <option value="Jakarta Pusat">Jakarta Pusat</option>
                        <option value="Jakarta Utara">Jakarta Utara</option>
                        <option value="Jakarta Selatan">Jakarta Selatan</option>
                        <option value="Jakarta Barat">Jakarta Barat</option>
                        <option value="Jakarta Timur">Jakarta Timur</option>
                    </select>
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

        <!-- Locations Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Gambar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Lokasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Alamat
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kota
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Operasional
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jam Operasional
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kontak
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="lokasiTableBody">
                        @forelse($lokasis as $index => $lokasi)
                            <tr class="hover:bg-gray-50 lokasi-row" 
                                data-kota="{{ $lokasi->kota }}" 
                                data-status="{{ $lokasi->status }}"
                                data-search="{{ strtolower($lokasi->nama . ' ' . $lokasi->alamat . ' ' . $lokasi->kota) }}">
                                
                                <!-- No -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $lokasis->firstItem() + $index }}
                                </td>

                                <!-- Gambar -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($lokasi->gambar)
                                        <img src="{{ asset($lokasi->gambar) }}" alt="{{ $lokasi->nama }}" class="h-12 w-12 object-cover rounded-lg">
                                    @else
                                        <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>

                                <!-- Nama Lokasi -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <i class="fas fa-hospital text-red-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $lokasi->nama }}
                                            </div>
                                            @if($lokasi->kapasitas)
                                                <div class="text-xs text-gray-500">
                                                    Kapasitas: {{ $lokasi->kapasitas }} orang
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Alamat -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ Str::limit($lokasi->alamat, 50) }}
                                    </div>
                                </td>

                                <!-- Kota -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $lokasi->kota }}
                                    </span>
                                </td>

                                <!-- Tanggal Operasional -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $lokasi->tanggal_operasional ? $lokasi->tanggal_operasional->format('d/m/Y') : '-' }}
                                </td>

                                <!-- Jam Operasional -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($lokasi->jam_buka && $lokasi->jam_tutup)
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-gray-400 mr-1"></i>
                                            {{ $lokasi->jam_buka }} - {{ $lokasi->jam_tutup }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Kontak -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($lokasi->kontak)
                                        <div class="flex items-center">
                                            <i class="fas fa-phone text-gray-400 mr-1"></i>
                                            {{ $lokasi->kontak }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="viewLokasi({{ $lokasi->id }})" 
                                                class="text-blue-600 hover:text-blue-900" 
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <a href="{{ route('lokasis.edit', $lokasi->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button onclick="deleteLokasi({{ $lokasi->id }}, '{{ $lokasi->nama }}')" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-map-marker-alt text-4xl mb-4 text-gray-300"></i>
                                    <p class="text-lg mb-2">Belum ada data lokasi donor</p>
                                    <p class="text-sm text-gray-400 mb-4">Mulai tambahkan lokasi PMI untuk donor darah</p>
                                    <a href="{{ route('lokasis.create') }}" 
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
// Filter functionality
document.getElementById('searchInput').addEventListener('input', filterLokasi);
document.getElementById('kotaFilter').addEventListener('change', filterLokasi);
document.getElementById('statusFilter').addEventListener('change', filterLokasi);

function filterLokasi() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const kota = document.getElementById('kotaFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('.lokasi-row');
    
    rows.forEach(row => {
        const matchSearch = row.dataset.search.includes(search);
        const matchKota = !kota || row.dataset.kota === kota;
        const matchStatus = !status || row.dataset.status === status;
        
        if (matchSearch && matchKota && matchStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// View lokasi details
function viewLokasi(lokasiId) {
    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewContent').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';
    
    fetch(`/lokasis/${lokasiId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('viewContent').innerHTML = `<div class="text-center py-8 text-red-600">${data.error}</div>`;
                return;
            }
            
            let html = `
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-red-50 rounded-lg p-4">
                            <h4 class="font-semibold text-red-800 mb-3">Informasi Lokasi</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Nama:</span> ${data.nama}</div>
                                <div><span class="font-medium">Kota:</span> ${data.kota}</div>
                                <div><span class="font-medium">Jenis:</span> ${data.jenis || 'Kota'}</div>
                                <div><span class="font-medium">Status:</span> 
                                    <span class="px-2 py-1 rounded text-xs ${data.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                        ${data.status || 'Aktif'}
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
            document.getElementById('viewContent').innerHTML = '<div class="text-center py-8 text-red-600">Gagal memuat detail lokasi</div>';
        });
}

// Delete lokasi
function deleteLokasi(lokasiId, namaLokasi) {
    document.getElementById('deleteMessage').textContent = `Yakin ingin menghapus lokasi "${namaLokasi}"? Data yang terkait juga akan terhapus!`;
    document.getElementById('deleteForm').action = `/lokasis/${lokasiId}`;
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
</script>
@endsection
