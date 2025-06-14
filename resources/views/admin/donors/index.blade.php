{{-- resources/views/admin/donors/index.blade.php --}}
@extends('dashboardlayout.app')

@section('page-title', 'Manajemen Donor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Donor Darah</h1>
                <p class="text-gray-600">Kelola semua data donor darah</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                <a href="{{ route('admin.donors.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export Excel
                </a>
                <button onclick="refreshData()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Donor</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalDonors }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $pendingCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-thumbs-up text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Disetujui</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $approvedCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Selesai</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $completedCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ditolak</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $rejectedCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Donor</label>
                    <div class="relative">
                        <input type="text" 
                               id="searchInput"
                               placeholder="Kode donor atau nama..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="statusFilter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Disetujui</option>
                        <option value="completed">Selesai</option>
                        <option value="rejected">Ditolak</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelayakan</label>
                    <select id="eligibilityFilter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua</option>
                        <option value="1">Layak</option>
                        <option value="0">Tidak Layak</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" 
                           id="dateFilter"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Donors Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Donor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelayakan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="donorsTableBody">
                        @forelse($donors as $donor)
                            <tr class="hover:bg-gray-50 donor-row" 
                                data-status="{{ $donor->status }}" 
                                data-eligible="{{ $donor->is_eligible ? '1' : '0' }}"
                                data-date="{{ $donor->created_at->format('Y-m-d') }}"
                                data-search="{{ strtolower($donor->donor_code . ' ' . $donor->user->name) }}">
                                
                                <!-- Donor Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $donor->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $donor->donor_code }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($donor->status === 'completed') bg-green-100 text-green-800
                                        @elseif($donor->status === 'approved') bg-blue-100 text-blue-800
                                        @elseif($donor->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($donor->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        
                                        @if($donor->status === 'completed') ✅ Selesai
                                        @elseif($donor->status === 'approved') 👍 Disetujui
                                        @elseif($donor->status === 'pending') ⏳ Pending
                                        @elseif($donor->status === 'rejected') ❌ Ditolak
                                        @else {{ ucfirst($donor->status) }} @endif
                                    </span>
                                </td>

                                <!-- Eligibility -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($donor->is_eligible)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            ✅ Layak
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            ❌ Tidak Layak
                                        </span>
                                    @endif
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ $donor->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $donor->created_at->format('H:i') }}</div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="viewDonor({{ $donor->id }})" 
                                                class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        @if($donor->status === 'approved')
                                            <button onclick="completeDonor({{ $donor->id }})" 
                                                    class="text-green-600 hover:text-green-900" 
                                                    title="Tandai Selesai">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        @endif
                                        
                                        @if($donor->status === 'pending')
                                            <button onclick="approveDonor({{ $donor->id }})" 
                                                    class="text-blue-600 hover:text-blue-900" 
                                                    title="Setujui">
                                                <i class="fas fa-thumbs-up"></i>
                                            </button>
                                            <button onclick="rejectDonor({{ $donor->id }})" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Tolak">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        @endif
                                        
                                        @if($donor->status === 'completed')
                                            <a href="{{ route('donor.certificate', $donor->id) }}" 
                                               target="_blank"
                                               class="text-green-600 hover:text-green-900" 
                                               title="Lihat Sertifikat">
                                                <i class="fas fa-certificate"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-4"></i>
                                    <p>Belum ada data donor</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($donors->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $donors->links() }}
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
                    <h3 class="text-xl font-semibold text-gray-800">Detail Donor</h3>
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

<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tandai Donor Selesai</h3>
                <form id="completeForm">
                    <input type="hidden" id="completeDonorId">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Donor</label>
                        <input type="date" 
                               id="donationDate" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               value="{{ date('Y-m-d') }}" 
                               required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea id="completeNotes" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeModal('completeModal')" 
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Tandai Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tolak Donor</h3>
                <form id="rejectForm">
                    <input type="hidden" id="rejectDonorId">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                        <textarea id="rejectReason" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Jelaskan alasan penolakan..."
                                  required></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeModal('rejectModal')" 
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Tolak Donor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.getElementById('searchInput').addEventListener('input', filterDonors);
document.getElementById('statusFilter').addEventListener('change', filterDonors);
document.getElementById('eligibilityFilter').addEventListener('change', filterDonors);
document.getElementById('dateFilter').addEventListener('change', filterDonors);

function filterDonors() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const eligible = document.getElementById('eligibilityFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    const rows = document.querySelectorAll('.donor-row');
    
    rows.forEach(row => {
        const matchSearch = row.dataset.search.includes(search);
        const matchStatus = !status || row.dataset.status === status;
        const matchEligible = !eligible || row.dataset.eligible === eligible;
        const matchDate = !date || row.dataset.date === date;
        
        if (matchSearch && matchStatus && matchEligible && matchDate) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// View donor details
function viewDonor(donorId) {
    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewContent').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';
    
    fetch(`/admin/donors/${donorId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('viewContent').innerHTML = `<div class="text-center py-8 text-red-600">${data.error}</div>`;
                return;
            }
            
            let html = `
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Informasi Pengguna</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Nama:</span> ${data.donor.user.name}</div>
                                <div><span class="font-medium">Email:</span> ${data.donor.user.email}</div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Informasi Donor</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Kode:</span> ${data.donor.donor_code}</div>
                                <div><span class="font-medium">Status:</span> ${data.donor.status}</div>
                                <div><span class="font-medium">Layak:</span> ${data.donor.is_eligible ? 'Ya' : 'Tidak'}</div>
                                <div><span class="font-medium">Tanggal Daftar:</span> ${new Date(data.donor.created_at).toLocaleDateString('id-ID')}</div>
                            </div>
                        </div>
                    </div>
                    
                    ${data.donor.health_answers ? `
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-800 mb-2">Jawaban Kesehatan</h4>
                            <div class="text-sm text-blue-700">
                                ${Object.entries(JSON.parse(data.donor.health_answers)).map(([key, value]) => 
                                    `<div><strong>${key}:</strong> ${value}</div>`
                                ).join('')}
                            </div>
                        </div>
                    ` : ''}
                    
                    ${data.donor.notes ? `
                        <div class="bg-green-50 rounded-lg p-4">
                            <h4 class="font-semibold text-green-800 mb-2">Catatan</h4>
                            <p class="text-sm text-green-700">${data.donor.notes}</p>
                        </div>
                    ` : ''}
                    
                    ${data.donor.rejection_reason ? `
                        <div class="bg-red-50 rounded-lg p-4">
                            <h4 class="font-semibold text-red-800 mb-2">Alasan Penolakan</h4>
                            <p class="text-sm text-red-700">${data.donor.rejection_reason}</p>
                        </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('viewContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('viewContent').innerHTML = '<div class="text-center py-8 text-red-600">Gagal memuat detail</div>';
        });
}

// Approve donor
function approveDonor(donorId) {
    if (confirm('Apakah Anda yakin ingin menyetujui donor ini?')) {
        updateDonorStatus(donorId, 'approved');
    }
}

// Complete donor
function completeDonor(donorId) {
    document.getElementById('completeDonorId').value = donorId;
    document.getElementById('completeModal').classList.remove('hidden');
}

// Reject donor
function rejectDonor(donorId) {
    document.getElementById('rejectDonorId').value = donorId;
    document.getElementById('rejectModal').classList.remove('hidden');
}

// Handle complete form
document.getElementById('completeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const donorId = document.getElementById('completeDonorId').value;
    const donationDate = document.getElementById('donationDate').value;
    const notes = document.getElementById('completeNotes').value;
    
    fetch(`/admin/donors/${donorId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            donation_date: donationDate,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal menandai donor selesai');
        }
    });
});

// Handle reject form
document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const donorId = document.getElementById('rejectDonorId').value;
    const reason = document.getElementById('rejectReason').value;
    
    updateDonorStatus(donorId, 'rejected', reason);
});

// Update donor status
function updateDonorStatus(donorId, status, reason = null) {
    const data = { status: status };
    if (reason) data.rejection_reason = reason;
    
    fetch(`/admin/donors/${donorId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengupdate status');
        }
    });
}

// Close modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Export data
function exportData() {
    window.location.href = '/admin/donors/export';
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
