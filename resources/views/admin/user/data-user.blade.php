@extends('dashboardlayout.app')

@section('title', 'Dashboard - Your App')
@section('page-title', 'Manajemen Data User')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="ml-auto text-red-600 hover:text-red-800" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Header dengan Info Singkat -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Data User</h1>
                    <p class="text-gray-600">Total: <span class="font-semibold text-red-600">{{ $profiles->count() }}</span> pengguna terdaftar</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button onclick="exportToExcel()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Search dan Filter Sederhana -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="Cari nama atau telepon..." 
                               id="searchInput">
                    </div>
                </div>
                
                <!-- Filter Golongan Darah -->
                <div>
                    <select id="bloodFilter" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Golongan Darah</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabel Data User -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="userTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Gol. Darah</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rhesus</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Umur</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($profiles as $index => $profile)
                        <tr class="hover:bg-gray-50 transition-colors table-row">
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium text-gray-900">{{ $index + 1 }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-red-500 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($profile->name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 user-name">{{ $profile->name }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $profile->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800">
                                    {{ $profile->blood_type }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $profile->rhesus == 'POSITIF' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $profile->rhesus == 'POSITIF' ? '+' : '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $profile->gender == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                    <i class="fas {{ $profile->gender == 'Laki-laki' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                    {{ substr($profile->gender, 0, 1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 user-phone">{{ $profile->telephone }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($profile->birth_date)->age }} th</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                @if($profile->donor_code)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-tint mr-1"></i>
                                        Donor
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        <i class="fas fa-user mr-1"></i>
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="viewUser({{ $profile->id }}, '{{ $profile->name }}')" 
                                            class="text-blue-600 hover:text-blue-800 transition-colors p-1" 
                                            title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editUser({{ $profile->id }})" 
                                            class="text-green-600 hover:text-green-800 transition-colors p-1" 
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteUser({{ $profile->id }}, '{{ $profile->name }}')" 
                                            class="text-red-600 hover:text-red-800 transition-colors p-1" 
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyState">
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data</h3>
                                    <p class="text-gray-500">Data user akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Info Footer -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Data terakhir diperbarui: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

<!-- Modal Detail User -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-medium text-gray-900">Detail User</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6" id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const bloodFilter = document.getElementById('bloodFilter');
    const table = document.getElementById('userTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr'));
    const dataRows = rows.filter(row => row.cells.length > 1);

    // Simple search function
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const bloodType = bloodFilter.value;
        let visibleCount = 0;

        dataRows.forEach((row, index) => {
            const nameText = row.querySelector('.user-name').textContent.toLowerCase();
            const phoneText = row.querySelector('.user-phone').textContent.toLowerCase();
            const rowBloodType = row.cells[2].textContent.trim();
            
            const matchesSearch = searchTerm === '' || nameText.includes(searchTerm) || phoneText.includes(searchTerm);
            const matchesBlood = bloodType === '' || rowBloodType === bloodType;
            
            if (matchesSearch && matchesBlood) {
                row.style.display = '';
                visibleCount++;
                // Update row number
                row.cells[0].querySelector('span').textContent = visibleCount;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide empty state
        const emptyState = document.getElementById('emptyState');
        if (emptyState) {
            emptyState.style.display = (visibleCount === 0 && dataRows.length > 0) ? '' : 'none';
        }
    }

    // Event listeners
    searchInput.addEventListener('input', performSearch);
    bloodFilter.addEventListener('change', performSearch);

    // Clear search on Escape
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            performSearch();
        }
    });
});

// User actions
function viewUser(id, name) {
    const modalContent = document.getElementById('modalContent');
    
    // Simple user detail display
    modalContent.innerHTML = `
        <div class="space-y-4">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white font-bold text-xl">${name.charAt(0).toUpperCase()}</span>
                </div>
                <h4 class="text-lg font-medium text-gray-900">${name}</h4>
                <p class="text-sm text-gray-500">User ID: ${id}</p>
            </div>
            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 text-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Detail lengkap dapat dilihat di halaman edit
                </p>
            </div>
            <div class="flex space-x-3">
                <button onclick="editUser(${id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Data
                </button>
                <button onclick="closeModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('userModal').classList.remove('hidden');
}

function editUser(id) {
    window.location.href = `/profiles/${id}/edit`;
}

function deleteUser(id, name) {
    if (confirm(`Yakin ingin menghapus data ${name}?`)) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/profiles/${id}`;
        form.style.display = 'none';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

// Export to Excel (CSV format)
function exportToExcel() {
    // Show loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengexport...';
    button.disabled = true;
    
    // Get visible rows only
    const table = document.getElementById('userTable');
    const visibleRows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
    
    // CSV headers
    let csvContent = 'No,Nama,ID,Golongan Darah,Rhesus,Jenis Kelamin,Telepon,Umur,Status\n';
    
    // Add data rows
    visibleRows.forEach(row => {
        if (row.cells.length > 1) { // Skip empty state row
            const cols = row.cells;
            const rowData = [
                cols[0].textContent.trim(), // No
                cols[1].querySelector('.user-name').textContent.trim(), // Nama
                cols[1].querySelector('.text-gray-500').textContent.replace('ID: ', '').trim(), // ID
                cols[2].textContent.trim(), // Golongan Darah
                cols[3].textContent.trim(), // Rhesus
                cols[4].textContent.includes('fa-mars') ? 'Laki-laki' : 'Perempuan', // Gender
                cols[5].textContent.trim(), // Telepon
                cols[6].textContent.trim(), // Umur
                cols[7].textContent.includes('Donor') ? 'Donor Aktif' : 'User Biasa' // Status
            ];
            
            // Escape quotes and wrap in quotes
            const escapedData = rowData.map(field => `"${field.replace(/"/g, '""')}"`);
            csvContent += escapedData.join(',') + '\n';
        }
    });
    
    // Create and download file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    const fileName = `data_user_${new Date().toISOString().split('T')[0]}.csv`;
    
    link.setAttribute('href', url);
    link.setAttribute('download', fileName);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Reset button
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 1500);
    
    // Show success message
    setTimeout(() => {
        alert(`File ${fileName} berhasil didownload!`);
    }, 500);
}

// Close modal when clicking outside
document.getElementById('userModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

@endsection
