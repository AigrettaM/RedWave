{{-- resources/views/admin/donor/history.blade.php --}}
@extends('dashboardlayout.app')

@section('page-title', 'Riwayat Donor Darah')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Riwayat Donor Darah</h1>
                <p class="text-gray-600">Lihat semua aktivitas donor darah Anda</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('donor.index') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Donor Baru
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Donor Selesai</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $donors->where('status', 'completed')->count() }}</p>
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
                        <p class="text-2xl font-bold text-gray-800">{{ $donors->where('status', 'approved')->count() }}</p>
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
                        <p class="text-2xl font-bold text-gray-800">{{ $donors->where('status', 'pending')->count() }}</p>
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
                        <p class="text-2xl font-bold text-gray-800">{{ $donors->where('status', 'rejected')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Donation Info -->
        @php
            $nextEligibleDonor = $donors->where('status', 'completed')
                                       ->where('next_eligible_date', '>', now())
                                       ->sortBy('next_eligible_date')
                                       ->first();
        @endphp

        @if($nextEligibleDonor)
            <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-6 mb-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">Donor Berikutnya</h3>
                        <p class="text-green-700">
                            Anda dapat mendonor lagi pada tanggal 
                            <span class="font-bold">{{ $nextEligibleDonor->next_eligible_date->format('d F Y') }}</span>
                            ({{ $nextEligibleDonor->next_eligible_date->diffForHumans() }})
                        </p>
                    </div>
                </div>
            </div>
        @elseif($donors->where('status', 'completed')->count() > 0)
            <div class="bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200 rounded-lg p-6 mb-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-heart text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-800">Siap Donor</h3>
                        <p class="text-blue-700">Anda sudah dapat mendonorkan darah lagi. Mari berbagi kebaikan!</p>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('donor.index') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Donor Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filter & Search -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari berdasarkan kode donor</label>
                    <div class="relative">
                        <input type="text" 
                               id="searchInput"
                               placeholder="Masukkan kode donor..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                    <select id="statusFilter" 
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Disetujui</option>
                        <option value="completed">Selesai</option>
                        <option value="rejected">Ditolak</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select id="sortFilter" 
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="code">Kode Donor</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Donor History List -->
        @if($donors->count() > 0)
            <div class="space-y-4" id="donorList">
                @foreach($donors as $donor)
                    <div class="bg-white rounded-lg shadow-md p-6 donor-item" 
                         data-status="{{ $donor->status }}" 
                         data-code="{{ $donor->donor_code }}"
                         data-date="{{ $donor->created_at->timestamp }}">
                        
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <!-- Left Content -->
                            <div class="flex-1">
                                <div class="flex items-start space-x-4">
                                    <!-- Status Icon -->
                                    <div class="flex-shrink-0 mt-1">
                                        @if($donor->status === 'completed')
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                            </div>
                                        @elseif($donor->status === 'approved')
                                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-thumbs-up text-blue-600 text-xl"></i>
                                            </div>
                                        @elseif($donor->status === 'rejected')
                                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                                            </div>
                                        @elseif($donor->status === 'pending')
                                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                                            </div>
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-ban text-gray-600 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Main Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                {{ $donor->donor_code }}
                                            </h3>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                @if($donor->status === 'completed') bg-green-100 text-green-800
                                                @elseif($donor->status === 'approved') bg-blue-100 text-blue-800
                                                @elseif($donor->status === 'rejected') bg-red-100 text-red-800
                                                @elseif($donor->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                
                                                @if($donor->status === 'completed') 
                                                    ✅ Donor Selesai
                                                @elseif($donor->status === 'approved') 
                                                    👍 Disetujui - Siap Donor
                                                @elseif($donor->status === 'rejected') 
                                                    ❌ Ditolak
                                                @elseif($donor->status === 'pending') 
                                                    ⏳ Dalam Proses
                                                @elseif($donor->status === 'cancelled') 
                                                    🚫 Dibatalkan
                                                @else 
                                                    {{ ucfirst($donor->status) }} 
                                                @endif
                                            </span>
                                            
                                            @if($donor->is_eligible)
                                                <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                                                    ✅ Layak Donor
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-red-50 text-red-700 rounded-full text-xs font-medium">
                                                    ❌ Tidak Layak
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Details Grid -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div>
                                                <i class="fas fa-calendar-plus mr-2 text-gray-400"></i>
                                                <span class="font-medium">Daftar:</span>
                                                {{ $donor->created_at->format('d M Y H:i') }}
                                            </div>
                                            
                                            @if($donor->donation_date)
                                                <div>
                                                    <i class="fas fa-tint mr-2 text-red-400"></i>
                                                    <span class="font-medium">Donor:</span>
                                                    {{ $donor->donation_date->format('d M Y') }}
                                                </div>
                                            @endif
                                            
                                            @if($donor->next_eligible_date)
                                                <div>
                                                    <i class="fas fa-calendar-check mr-2 text-green-400"></i>
                                                    <span class="font-medium">Berikutnya:</span>
                                                    {{ $donor->next_eligible_date->format('d M Y') }}
                                                </div>
                                            @endif
                                        </div>

                                        @if($donor->notes)
                                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                                <p class="text-sm text-gray-700">
                                                    <i class="fas fa-sticky-note mr-2 text-gray-400"></i>
                                                    <span class="font-medium">Catatan:</span>
                                                    {{ $donor->notes }}
                                                </p>
                                            </div>
                                        @endif

                                        @if($donor->rejection_reason)
                                            <div class="mt-3 p-3 bg-red-50 rounded-lg border border-red-200">
                                                <p class="text-sm text-red-700">
                                                    <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                                                    <span class="font-medium">Alasan penolakan:</span>
                                                    {{ $donor->rejection_reason }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-4 lg:mt-0 lg:ml-6 flex flex-wrap gap-2">
                                <button onclick="showDetail('{{ $donor->id }}')" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    Detail
                                </button>

                                {{-- SERTIFIKAT HANYA UNTUK STATUS 'COMPLETED' --}}
                                @if($donor->status === 'completed')
                                    <a href="{{ route('donor.certificate', $donor->id) }}" 
                                       target="_blank"
                                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-certificate mr-1"></i>
                                        Sertifikat
                                    </a>
                                @elseif($donor->status === 'approved')
                                    <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg text-sm font-medium">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        Menunggu Proses Donor
                                    </span>
                                @endif

                                @if($donor->status === 'pending')
                                    <a href="{{ route('donor.questions', 1) }}" 
                                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-edit mr-1"></i>
                                        Lanjutkan
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $donors->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-tint text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Riwayat Donor</h3>
                <p class="text-gray-600 mb-6">Anda belum pernah melakukan pendaftaran donor darah</p>
                <a href="{{ route('donor.index') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Mulai Donor Sekarang
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Detail Donor</h3>
                    <button onclick="closeDetail()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="detailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    filterDonors();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    filterDonors();
});

document.getElementById('sortFilter').addEventListener('change', function() {
    sortDonors();
});

function filterDonors() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const donorItems = document.querySelectorAll('.donor-item');

    donorItems.forEach(item => {
        const code = item.dataset.code.toLowerCase();
        const status = item.dataset.status;
        
        const matchesSearch = code.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function sortDonors() {
    const sortBy = document.getElementById('sortFilter').value;
    const donorList = document.getElementById('donorList');
    const donorItems = Array.from(document.querySelectorAll('.donor-item'));

    donorItems.sort((a, b) => {
        switch(sortBy) {
            case 'newest':
                return parseInt(b.dataset.date) - parseInt(a.dataset.date);
            case 'oldest':
                return parseInt(a.dataset.date) - parseInt(b.dataset.date);
            case 'code':
                return a.dataset.code.localeCompare(b.dataset.code);
            default:
                return 0;
        }
    });

    donorItems.forEach(item => donorList.appendChild(item));
}

// Detail modal functions
function showDetail(donorId) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailContent').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';
    
    fetch(`/donor/detail/${donorId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('detailContent').innerHTML = `<div class="text-center py-8 text-red-600">${data.error}</div>`;
                return;
            }
            
            let statusBadge = '';
            let statusIcon = '';
            
            switch(data.donor.status) {
                case 'completed':
                    statusBadge = '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">✅ Donor Selesai</span>';
                    statusIcon = '<i class="fas fa-check-circle text-green-600"></i>';
                    break;
                case 'approved':
                    statusBadge = '<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">👍 Disetujui - Siap Donor</span>';
                    statusIcon = '<i class="fas fa-thumbs-up text-blue-600"></i>';
                    break;
                case 'rejected':
                    statusBadge = '<span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">❌ Ditolak</span>';
                    statusIcon = '<i class="fas fa-times-circle text-red-600"></i>';
                    break;
                case 'pending':
                    statusBadge = '<span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">⏳ Dalam Proses</span>';
                    statusIcon = '<i class="fas fa-hourglass-half text-yellow-600"></i>';
                    break;
                default:
                    statusBadge = `<span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">${data.donor.status}</span>`;
                    statusIcon = '<i class="fas fa-question-circle text-gray-600"></i>';
            }
            
            let html = `
                <div class="space-y-6">
                    <div class="text-center pb-6 border-b">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            ${statusIcon}
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">${data.donor.donor_code}</h4>
                        ${statusBadge}
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Informasi Pengguna
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Nama:</span> ${data.donor.user.name}</div>
                                <div><span class="font-medium">Email:</span> ${data.donor.user.email}</div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-tint mr-2 text-red-500"></i>
                                Informasi Donor
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Kode:</span> ${data.donor.donor_code}</div>
                                <div><span class="font-medium">Layak:</span> ${data.donor.is_eligible ? 'Ya' : 'Tidak'}</div>
                                <div><span class="font-medium">Tanggal Daftar:</span> ${new Date(data.donor.created_at).toLocaleDateString('id-ID')}</div>
                                ${data.donor.donation_date ? `<div><span class="font-medium">Tanggal Donor:</span> ${new Date(data.donor.donation_date).toLocaleDateString('id-ID')}</div>` : ''}
                                ${data.donor.next_eligible_date ? `<div><span class="font-medium">Donor Berikutnya:</span> ${new Date(data.donor.next_eligible_date).toLocaleDateString('id-ID')}</div>` : ''}
                            </div>
                        </div>
                    </div>
                    
                    ${data.donor.notes ? `
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                                <i class="fas fa-sticky-note mr-2"></i>
                                Catatan
                            </h4>
                            <p class="text-sm text-blue-700">${data.donor.notes}</p>
                        </div>
                    ` : ''}
                    
                    ${data.donor.rejection_reason ? `
                        <div class="bg-red-50 rounded-lg p-4">
                            <h4 class="font-semibold text-red-800 mb-2 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Alasan Penolakan
                            </h4>
                            <p class="text-sm text-red-700">${data.donor.rejection_reason}</p>
                        </div>
                    ` : ''}
                    
                    ${data.donor.status === 'completed' ? `
                        <div class="text-center">
                            <a href="/donor/certificate/${data.donor.id}" target="_blank" 
                               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                                <i class="fas fa-certificate mr-2"></i>
                                Lihat Sertifikat
                            </a>
                        </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('detailContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('detailContent').innerHTML = '<div class="text-center py-8 text-red-600">Gagal memuat detail</div>';
        });
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetail();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetail();
    }
});
</script>
@endsection
