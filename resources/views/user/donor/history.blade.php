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
                                <button type="button" 
                                        onclick="showDetailModal({{ $donor->id }})" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    Detail
                                </button>

                                {{-- QR CODE BUTTON - UNTUK YANG ELIGIBLE DAN APPROVED/COMPLETED --}}
                                @if($donor->is_eligible && in_array($donor->status, ['approved', 'completed']))
                                    <button type="button" 
                                            onclick="showQRModal('{{ $donor->donor_code }}', '{{ addslashes($donor->user->name) }}', '{{ $donor->created_at->format('d M Y H:i') }}', '{{ $donor->status }}')" 
                                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-qrcode mr-1"></i>
                                        Lihat QR
                                    </button>
                                @endif

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
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" role="dialog" aria-labelledby="detailModalTitle" aria-hidden="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 id="detailModalTitle" class="text-xl font-semibold text-gray-800">Detail Donor</h3>
                    <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600" aria-label="Tutup modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="detailContent">
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                        <p class="text-gray-500 mt-2">Memuat detail...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" role="dialog" aria-labelledby="qrModalTitle" aria-hidden="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 id="qrModalTitle" class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-qrcode mr-2 text-purple-600"></i>
                        QR Code Konfirmasi
                    </h3>
                    <button type="button" onclick="closeQRModal()" class="text-gray-400 hover:text-gray-600" aria-label="Tutup modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="text-center">
                    <!-- QR Code Container -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-4">
                        <div id="qrCodeContainer" class="flex justify-center">
                            <div class="text-center py-8">
                                <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                                <p class="text-gray-500 mt-2 text-sm">Memuat QR Code...</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Donor Info -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-4 text-left">
                        <h4 class="font-semibold text-blue-800 mb-2">Informasi Donor:</h4>
                        <div class="space-y-1 text-sm text-blue-700">
                            <div><span class="font-medium">Kode:</span> <span id="qrDonorCode">-</span></div>
                            <div><span class="font-medium">Nama:</span> <span id="qrDonorName">-</span></div>
                            <div><span class="font-medium">Tanggal:</span> <span id="qrDonorDate">-</span></div>
                            <div><span class="font-medium">Status:</span> <span id="qrDonorStatus">-</span></div>
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="bg-green-50 rounded-lg p-4 mb-4">
                        <h4 class="font-semibold text-green-800 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Cara Penggunaan:
                        </h4>
                        <ul class="text-sm text-green-700 text-left space-y-1">
                            <li>• Tunjukkan QR code ini kepada petugas PMI</li>
                            <li>• Pastikan kode dapat terbaca dengan jelas</li>
                            <li>• Bawa juga kartu identitas yang valid</li>
                        </ul>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2 justify-center">
                        <button type="button" onclick="printQRCode()" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-print mr-1"></i>
                            Print
                        </button>
                        <button type="button" onclick="downloadQRCode()" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i>
                            Download
                        </button>
                        <button type="button" onclick="shareQRCode()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-share mr-1"></i>
                            Share
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2" aria-live="polite"></div>

<style>
.qr-image {
    transition: all 0.3s ease;
    max-width: 200px;
    height: auto;
    border-radius: 8px;
}

.qr-image:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Modal animations */
#detailModal, #qrModal {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.fa-spin {
    animation: spin 1s linear infinite;
}

/* Toast styles */
.toast {
    transform: translateX(100%);
    animation: slideIn 0.3s ease-out forwards;
    max-width: 400px;
    word-wrap: break-word;
}

.toast.removing {
    animation: slideOut 0.3s ease-out forwards;
}

@keyframes slideIn {
    to { transform: translateX(0); }
}

@keyframes slideOut {
    to { transform: translateX(100%); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .qr-image {
        max-width: 180px;
    }
    
    #qrModal .bg-white {
        margin: 1rem;
        max-width: calc(100vw - 2rem);
    }
    
    .donor-item {
        padding: 1rem;
    }
    
    .toast {
        max-width: calc(100vw - 2rem);
        margin-right: 1rem;
    }
}

/* Loading state */
.loading {
    pointer-events: none;
    opacity: 0.6;
}

/* Error state */
.error-state {
    color: #dc2626;
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 1rem;
}

/* Success state */
.success-state {
    color: #059669;
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 1rem;
}
</style>

<script>
// Global variables
let currentQRData = {};
let isLoading = false;

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('History page loaded');
    initializeFilters();
    setupEventListeners();
});

// Initialize filters
function initializeFilters() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(filterDonors, 300));
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterDonors);
    }
    
    if (sortFilter) {
        sortFilter.addEventListener('change', sortDonors);
    }
}

// Setup event listeners
function setupEventListeners() {
    // Modal close events
    document.addEventListener('click', function(e) {
        if (e.target.id === 'detailModal') {
            closeDetailModal();
        }
        if (e.target.id === 'qrModal') {
            closeQRModal();
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
            closeQRModal();
        }
    });
}

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

// Filter functions
function filterDonors() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('statusFilter')?.value || '';
    const donorItems = document.querySelectorAll('.donor-item');
    
    let visibleCount = 0;
    
    donorItems.forEach(item => {
        const donorCode = item.dataset.code?.toLowerCase() || '';
        const donorStatus = item.dataset.status || '';
        
        const matchesSearch = donorCode.includes(searchTerm);
        const matchesStatus = !statusFilter || donorStatus === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    updateEmptyState(visibleCount === 0);
}

function sortDonors() {
    const sortValue = document.getElementById('sortFilter')?.value || 'newest';
    const donorList = document.getElementById('donorList');
    const donorItems = Array.from(document.querySelectorAll('.donor-item'));
    
    donorItems.sort((a, b) => {
        switch (sortValue) {
            case 'oldest':
                return parseInt(a.dataset.date) - parseInt(b.dataset.date);
            case 'newest':
                return parseInt(b.dataset.date) - parseInt(a.dataset.date);
            case 'code':
                return a.dataset.code.localeCompare(b.dataset.code);
            default:
                return 0;
        }
    });
    
    // Re-append sorted items
    donorItems.forEach(item => {
        donorList.appendChild(item);
    });
}

function updateEmptyState(show) {
    let emptyState = document.getElementById('emptyStateFiltered');
    
    if (show && !emptyState) {
        emptyState = document.createElement('div');
        emptyState.id = 'emptyStateFiltered';
        emptyState.className = 'bg-white rounded-lg shadow-md p-12 text-center mt-6';
        emptyState.innerHTML = `
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-search text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak Ada Hasil</h3>
            <p class="text-gray-600 mb-6">Tidak ditemukan donor yang sesuai dengan filter Anda</p>
            <button onclick="clearFilters()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-times mr-2"></i>
                Hapus Filter
            </button>
        `;
        document.getElementById('donorList').after(emptyState);
    } else if (!show && emptyState) {
        emptyState.remove();
    }
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortFilter').value = 'newest';
    filterDonors();
    sortDonors();
}

// Modal functions
function showDetailModal(donorId) {
    if (isLoading) return;
    
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    
    if (!modal || !content) {
        showToast('Error: Modal tidak ditemukan', 'error');
        return;
    }
    
    // Show modal with loading state
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    content.innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <p class="text-gray-500 mt-2">Memuat detail donor...</p>
        </div>
    `;
    
    document.body.style.overflow = 'hidden';
    modal.focus();
    isLoading = true;
    
    // Perbaikan URL sesuai dengan route yang ada
    fetch(`/donor/detail/${donorId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.donor) {
            content.innerHTML = generateDetailContent(data.donor);
        } else {
            throw new Error(data.message || 'Data donor tidak ditemukan');
        }
    })
    .catch(error => {
        console.error('Error fetching donor details:', error);
        content.innerHTML = `
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Gagal Memuat Detail</h4>
                <p class="text-gray-600 mb-4">${error.message}</p>
                <div class="space-x-2">
                    <button onclick="showDetailModal(${donorId})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-redo mr-1"></i>
                        Coba Lagi
                    </button>
                    <button onclick="closeDetailModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Tutup
                    </button>
                </div>
            </div>
        `;
        showToast('Gagal memuat detail donor', 'error');
    })
    .finally(() => {
        isLoading = false;
    });
}

function generateDetailContent(donor) {
    // Helper function untuk format status
    const getStatusBadge = (status) => {
        const statusConfig = {
            'pending': { class: 'bg-yellow-100 text-yellow-800', icon: 'fas fa-clock', text: 'Menunggu' },
            'approved': { class: 'bg-green-100 text-green-800', icon: 'fas fa-check-circle', text: 'Disetujui' },
            'rejected': { class: 'bg-red-100 text-red-800', icon: 'fas fa-times-circle', text: 'Ditolak' },
            'completed': { class: 'bg-blue-100 text-blue-800', icon: 'fas fa-heart', text: 'Selesai' }
        };
        
        const config = statusConfig[status] || statusConfig['pending'];
        return `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${config.class}">
                    <i class="${config.icon} mr-1"></i>
                    ${config.text}
                </span>`;
    };

    // Helper function untuk format tanggal
    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    return `
        <div class="space-y-6">
            <!-- Header dengan Kode Donor -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold">${donor.donor_code}</h3>
                        <p class="text-red-100">Kode Donor</p>
                    </div>
                    <div class="text-right">
                        ${getStatusBadge(donor.status)}
                    </div>
                </div>
            </div>

            <!-- Informasi Personal -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="flex items-center text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-user text-blue-500 mr-2"></i>
                    Informasi Personal
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                        <p class="text-gray-900 font-medium">${donor.user.name}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="text-gray-900">${donor.user.email}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Golongan Darah</label>
                        <p class="text-gray-900 font-semibold text-red-600">${donor.blood_type || '-'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Kelayakan</label>
                        <p class="text-gray-900">
                            ${donor.is_eligible ? 
                                '<span class="text-green-600 font-medium"><i class="fas fa-check-circle mr-1"></i>Layak</span>' : 
                                '<span class="text-red-600 font-medium"><i class="fas fa-times-circle mr-1"></i>Tidak Layak</span>'
                            }
                        </p>
                    </div>
                </div>
            </div>

            <!-- Timeline Donor -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="flex items-center text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-history text-purple-500 mr-2"></i>
                    Timeline Donor
                </h4>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <div>
                            <p class="font-medium text-gray-800">Pendaftaran</p>
                            <p class="text-sm text-gray-600">${formatDate(donor.created_at)}</p>
                        </div>
                    </div>
                    
                    ${donor.approved_at ? `
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                            <p class="font-medium text-gray-800">Donor Disetujui</p>
                            <p class="text-sm text-gray-600">${formatDate(donor.approved_at)}</p>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${donor.donation_date ? `
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <div>
                            <p class="font-medium text-gray-800">Donor Dilakukan</p>
                            <p class="text-sm text-gray-600">${formatDate(donor.donation_date)}</p>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${donor.next_eligible_date ? `
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <div>
                            <p class="font-medium text-gray-800">Donor Berikutnya</p>
                            <p class="text-sm text-gray-600">${formatDate(donor.next_eligible_date)}</p>
                        </div>
                    </div>
                    ` : ''}
                </div>
            </div>

            <!-- Catatan -->
            ${donor.notes || donor.rejection_reason ? `
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="flex items-center text-lg font-semibold text-gray-800 mb-3">
                    <i class="fas fa-sticky-note text-yellow-500 mr-2"></i>
                    Catatan
                </h4>
                <div class="bg-white rounded-lg p-3 border-l-4 border-blue-500">
                    <p class="text-gray-700">${donor.notes || donor.rejection_reason}</p>
                </div>
            </div>
            ` : ''}
        </div>
    `;
}


function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = 'auto';
    }
}

// QR Code functions
function showQRModal(donorCode, donorName, donorDate, donorStatus) {
    if (isLoading) return;
    
    const modal = document.getElementById('qrModal');
    if (!modal) {
        showToast('Error: QR Modal tidak ditemukan', 'error');
        return;
    }
    
    // Store current QR data
    currentQRData = {
        code: donorCode,
        name: donorName,
        date: donorDate,
        status: donorStatus
    };
    
    // Show modal
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    
    // Update donor info
    updateQRInfo();
    
    // Generate QR code
    generateQRCode();
    
    // Focus management
    modal.focus();
}

function updateQRInfo() {
    const elements = {
        'qrDonorCode': currentQRData.code,
        'qrDonorName': currentQRData.name,
        'qrDonorDate': currentQRData.date,
        'qrDonorStatus': getStatusText(currentQRData.status)
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    });
}

function getStatusText(status) {
    const statusMap = {
        'completed': '✅ Donor Selesai',
        'approved': '👍 Disetujui - Siap Donor',
        'pending': '⏳ Dalam Proses',
        'rejected': '❌ Ditolak',
        'cancelled': '🚫 Dibatalkan'
    };
    return statusMap[status] || status;
}

function generateQRCode() {
    const container = document.getElementById('qrCodeContainer');
    if (!container) return;
    
    // Show loading
    container.innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <p class="text-gray-500 mt-2 text-sm">Membuat QR Code...</p>
        </div>
    `;
    
    // QR data
    const qrData = {
        donor_code: currentQRData.code,
        name: currentQRData.name,
        date: currentQRData.date,
        status: currentQRData.status,
        timestamp: new Date().toISOString()
    };
    
    const qrString = JSON.stringify(qrData);
    const encodedData = encodeURIComponent(qrString);
    
    // Generate QR code using QR Server API
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodedData}&format=png&ecc=M`;
    
    // Create image element
    const img = new Image();
    img.onload = function() {
        container.innerHTML = `
            <img src="${qrUrl}" 
                 alt="QR Code untuk ${currentQRData.code}" 
                 class="qr-image mx-auto border border-gray-200"
                 id="qrCodeImage">
        `;
        showToast('QR Code berhasil dibuat', 'success');
    };
    
    img.onerror = function() {
        container.innerHTML = `
            <div class="error-state text-center">
                <i class="fas fa-exclamation-triangle text-3xl mb-4"></i>
                <h4 class="text-lg font-semibold mb-2">Gagal Membuat QR Code</h4>
                <p class="mb-4">Terjadi kesalahan saat membuat QR code</p>
                <button onclick="generateQRCode()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-redo mr-1"></i>
                    Coba Lagi
                </button>
            </div>
        `;
        showToast('Gagal membuat QR Code', 'error');
    };
    
    img.src = qrUrl;
}

function closeQRModal() {
    const modal = document.getElementById('qrModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = 'auto';
    }
    currentQRData = {};
}

function printQRCode() {
    const qrImage = document.getElementById('qrCodeImage');
    if (!qrImage) {
        showToast('QR Code belum siap untuk dicetak', 'error');
        return;
    }
    
    const printWindow = window.open('', '_blank');
    if (!printWindow) {
        showToast('Pop-up diblokir. Izinkan pop-up untuk mencetak', 'error');
        return;
    }
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>QR Code - ${currentQRData.code}</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    text-align: center; 
                    padding: 20px;
                    margin: 0;
                }
                .header {
                    margin-bottom: 20px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 15px;
                }
                .qr-container {
                    margin: 20px 0;
                }
                .qr-image {
                    border: 2px solid #333;
                    padding: 10px;
                    background: white;
                }
                .info {
                    margin-top: 20px;
                    text-align: left;
                    max-width: 400px;
                    margin-left: auto;
                    margin-right: auto;
                }
                .info-row {
                    margin: 8px 0;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 5px;
                }
                .label {
                    font-weight: bold;
                    display: inline-block;
                    width: 120px;
                }
                .footer {
                    margin-top: 30px;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #ccc;
                    padding-top: 15px;
                }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>QR Code Konfirmasi Donor</h1>
                <h2>PMI - Palang Merah Indonesia</h2>
            </div>
            
            <div class="qr-container">
                <img src="${qrImage.src}" alt="QR Code" class="qr-image">
            </div>
            
            <div class="info">
                <div class="info-row">
                    <span class="label">Kode Donor:</span>
                    <span>${currentQRData.code}</span>
                </div>
                <div class="info-row">
                    <span class="label">Nama:</span>
                    <span>${currentQRData.name}</span>
                </div>
                <div class="info-row">
                    <span class="label">Tanggal:</span>
                    <span>${currentQRData.date}</span>
                </div>
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span>${getStatusText(currentQRData.status)}</span>
                </div>
                <div class="info-row">
                    <span class="label">Dicetak:</span>
                    <span>${new Date().toLocaleString('id-ID')}</span>
                </div>
            </div>
            
            <div class="footer">
                <p><strong>Petunjuk:</strong></p>
                <p>• Tunjukkan QR code ini kepada petugas PMI</p>
                <p>• Pastikan kode dapat terbaca dengan jelas</p>
                <p>• Bawa juga kartu identitas yang valid</p>
                <p style="margin-top: 15px;">
                    <em>Dokumen ini dicetak pada ${new Date().toLocaleString('id-ID')}</em>
                </p>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    // Wait for image to load before printing
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
        showToast('QR Code berhasil dicetak', 'success');
    }, 500);
}

function downloadQRCode() {
    const qrImage = document.getElementById('qrCodeImage');
    if (!qrImage) {
        showToast('QR Code belum siap untuk diunduh', 'error');
        return;
    }
    
    try {
        // Create canvas to convert image
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        // Set canvas size
        canvas.width = 400;
        canvas.height = 500;
        
        // Fill white background
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Add header text
        ctx.fillStyle = 'black';
        ctx.font = 'bold 18px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('QR Code Donor', canvas.width / 2, 30);
        ctx.fillText(currentQRData.code, canvas.width / 2, 55);
        
        // When image loads, draw it on canvas
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            // Draw QR code
            const qrSize = 200;
            const qrX = (canvas.width - qrSize) / 2;
            const qrY = 80;
            ctx.drawImage(img, qrX, qrY, qrSize, qrSize);
            
            // Add info text
            ctx.font = '14px Arial';
            ctx.textAlign = 'left';
            const infoY = qrY + qrSize + 30;
            const infoX = 50;
            
            ctx.fillText(`Nama: ${currentQRData.name}`, infoX, infoY);
            ctx.fillText(`Tanggal: ${currentQRData.date}`, infoX, infoY + 25);
            ctx.fillText(`Status: ${getStatusText(currentQRData.status)}`, infoX, infoY + 50);
            ctx.fillText(`Diunduh: ${new Date().toLocaleString('id-ID')}`, infoX, infoY + 75);
            
            // Convert to blob and download
            canvas.toBlob(function(blob) {
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `QR_Code_${currentQRData.code}_${new Date().toISOString().split('T')[0]}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
                
                showToast('QR Code berhasil diunduh', 'success');
            }, 'image/png', 0.9);
        };
        
        img.onerror = function() {
            showToast('Gagal mengunduh QR Code', 'error');
        };
        
        img.src = qrImage.src;
        
    } catch (error) {
        console.error('Download error:', error);
        showToast('Gagal mengunduh QR Code', 'error');
    }
}

function shareQRCode() {
    const qrImage = document.getElementById('qrCodeImage');
    if (!qrImage) {
        showToast('QR Code belum siap untuk dibagikan', 'error');
        return;
    }
    
    const shareData = {
        title: `QR Code Donor - ${currentQRData.code}`,
        text: `QR Code konfirmasi donor darah untuk ${currentQRData.name}\nKode: ${currentQRData.code}\nStatus: ${getStatusText(currentQRData.status)}`,
        url: window.location.href
    };
    
    // Check if Web Share API is supported
    if (navigator.share && navigator.canShare && navigator.canShare(shareData)) {
        navigator.share(shareData)
            .then(() => {
                showToast('QR Code berhasil dibagikan', 'success');
            })
            .catch((error) => {
                console.error('Share error:', error);
                fallbackShare();
            });
    } else {
        fallbackShare();
    }
}

function fallbackShare() {
    const shareText = `QR Code Donor - ${currentQRData.code}\n\nNama: ${currentQRData.name}\nKode: ${currentQRData.code}\nStatus: ${getStatusText(currentQRData.status)}\nTanggal: ${currentQRData.date}\n\nLink: ${window.location.href}`;
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(shareText)
            .then(() => {
                showToast('Informasi QR Code disalin ke clipboard', 'success');
            })
            .catch(() => {
                manualCopyFallback(shareText);
            });
    } else {
        manualCopyFallback(shareText);
    }
}

function manualCopyFallback(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    textArea.select();
    
    try {
        document.execCommand('copy');
        showToast('Informasi QR Code disalin ke clipboard', 'success');
    } catch (error) {
        console.error('Copy failed:', error);
        showToast('Gagal menyalin informasi QR Code', 'error');
    } finally {
        document.body.removeChild(textArea);
    }
}

// Utility functions
function formatDate(dateString) {
    if (!dateString) return '-';
    
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (error) {
        return dateString;
    }
}

// Toast notification system (lanjutan)
function showToast(message, type = 'info', duration = 5000) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    const toastId = 'toast_' + Date.now();
    toast.id = toastId;
    
    const typeConfig = {
        success: { icon: 'check-circle', bgColor: 'bg-green-500', textColor: 'text-white' },
        error: { icon: 'exclamation-triangle', bgColor: 'bg-red-500', textColor: 'text-white' },
        warning: { icon: 'exclamation-circle', bgColor: 'bg-yellow-500', textColor: 'text-white' },
        info: { icon: 'info-circle', bgColor: 'bg-blue-500', textColor: 'text-white' }
    };
    
    const config = typeConfig[type] || typeConfig.info;
    
    toast.className = `toast ${config.bgColor} ${config.textColor} px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 mb-2`;
    toast.innerHTML = `
        <i class="fas fa-${config.icon} text-lg flex-shrink-0"></i>
        <span class="flex-1">${message}</span>
        <button onclick="removeToast('${toastId}')" class="ml-2 hover:opacity-75 flex-shrink-0" aria-label="Tutup notifikasi">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after duration
    setTimeout(() => {
        removeToast(toastId);
    }, duration);
    
    // Remove old toasts if too many
    const toasts = container.querySelectorAll('.toast');
    if (toasts.length > 5) {
        removeToast(toasts[0].id);
    }
}

function removeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('removing');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }
}

// Error handling
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    showToast('Terjadi kesalahan pada halaman', 'error');
});

window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled Promise Rejection:', e.reason);
    showToast('Terjadi kesalahan pada sistem', 'error');
});

// Performance monitoring
function measurePerformance(name, fn) {
    const start = performance.now();
    const result = fn();
    const end = performance.now();
    console.log(`${name} took ${end - start} milliseconds`);
    return result;
}

// Accessibility improvements
function setupAccessibility() {
    // Add keyboard navigation for modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            const activeModal = document.querySelector('#detailModal:not(.hidden), #qrModal:not(.hidden)');
            if (activeModal) {
                trapFocus(e, activeModal);
            }
        }
    });
}

function trapFocus(e, modal) {
    const focusableElements = modal.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];
    
    if (e.shiftKey) {
        if (document.activeElement === firstElement) {
            lastElement.focus();
            e.preventDefault();
        }
    } else {
        if (document.activeElement === lastElement) {
            firstElement.focus();
            e.preventDefault();
        }
    }
}

// Initialize accessibility when DOM is ready
document.addEventListener('DOMContentLoaded', setupAccessibility);

// Service Worker registration (optional - for offline support)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('ServiceWorker registration successful');
            })
            .catch(function(error) {
                console.log('ServiceWorker registration failed');
            });
    });
}

// Network status monitoring
function setupNetworkMonitoring() {
    function updateNetworkStatus() {
        if (navigator.onLine) {
            showToast('Koneksi internet tersedia', 'success', 3000);
        } else {
            showToast('Koneksi internet terputus', 'warning', 0); // Don't auto-hide
        }
    }
    
    window.addEventListener('online', updateNetworkStatus);
    window.addEventListener('offline', updateNetworkStatus);
}

// Initialize network monitoring
document.addEventListener('DOMContentLoaded', setupNetworkMonitoring);

// Local storage utilities
const StorageUtils = {
    set: function(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch (error) {
            console.error('LocalStorage set error:', error);
            return false;
        }
    },
    
    get: function(key, defaultValue = null) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (error) {
            console.error('LocalStorage get error:', error);
            return defaultValue;
        }
    },
    
    remove: function(key) {
        try {
            localStorage.removeItem(key);
            return true;
        } catch (error) {
            console.error('LocalStorage remove error:', error);
            return false;
        }
    },
    
    clear: function() {
        try {
            localStorage.clear();
            return true;
        } catch (error) {
            console.error('LocalStorage clear error:', error);
            return false;
        }
    }
};

// Save and restore filter preferences
function saveFilterPreferences() {
    const preferences = {
        search: document.getElementById('searchInput')?.value || '',
        status: document.getElementById('statusFilter')?.value || '',
        sort: document.getElementById('sortFilter')?.value || 'newest'
    };
    StorageUtils.set('donor_history_filters', preferences);
}

function restoreFilterPreferences() {
    const preferences = StorageUtils.get('donor_history_filters');
    if (preferences) {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const sortFilter = document.getElementById('sortFilter');
        
        if (searchInput) searchInput.value = preferences.search || '';
        if (statusFilter) statusFilter.value = preferences.status || '';
        if (sortFilter) sortFilter.value = preferences.sort || 'newest';
        
        // Apply filters
        filterDonors();
        sortDonors();
    }
}

// Auto-save preferences when filters change
document.addEventListener('DOMContentLoaded', function() {
    restoreFilterPreferences();
    
    // Save preferences on filter change
    const filterElements = ['searchInput', 'statusFilter', 'sortFilter'];
    filterElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', saveFilterPreferences);
            element.addEventListener('input', debounce(saveFilterPreferences, 500));
        }
    });
});

// Print utilities
const PrintUtils = {
    printElement: function(elementId, title = 'Print') {
        const element = document.getElementById(elementId);
        if (!element) {
            showToast('Element tidak ditemukan untuk dicetak', 'error');
            return;
        }
        
        const printWindow = window.open('', '_blank');
        if (!printWindow) {
            showToast('Pop-up diblokir. Izinkan pop-up untuk mencetak', 'error');
            return;
        }
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>${title}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    @media print { body { margin: 0; } }
                    .no-print { display: none; }
                </style>
            </head>
            <body>
                ${element.innerHTML}
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
};

// Export utilities
const ExportUtils = {
    exportToCSV: function(data, filename = 'export.csv') {
        const csvContent = this.convertToCSV(data);
        this.downloadFile(csvContent, filename, 'text/csv');
    },
    
    convertToCSV: function(data) {
        if (!data || data.length === 0) return '';
        
        const headers = Object.keys(data[0]);
        const csvRows = [headers.join(',')];
        
        for (const row of data) {
            const values = headers.map(header => {
                const value = row[header];
                return typeof value === 'string' ? `"${value.replace(/"/g, '""')}"` : value;
            });
            csvRows.push(values.join(','));
        }
        
        return csvRows.join('\n');
    },
    
    downloadFile: function(content, filename, contentType) {
        const blob = new Blob([content], { type: contentType });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }
};

// Animation utilities
const AnimationUtils = {
    fadeIn: function(element, duration = 300) {
        element.style.opacity = '0';
        element.style.display = 'block';
        
        const start = performance.now();
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            
            element.style.opacity = progress;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    },
    
    fadeOut: function(element, duration = 300, callback = null) {
        const start = performance.now();
        const initialOpacity = parseFloat(getComputedStyle(element).opacity);
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            
            element.style.opacity = initialOpacity * (1 - progress);
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                element.style.display = 'none';
                if (callback) callback();
            }
        };
        
        requestAnimationFrame(animate);
    },
    
    slideDown: function(element, duration = 300) {
        element.style.height = '0';
        element.style.overflow = 'hidden';
        element.style.display = 'block';
        
        const targetHeight = element.scrollHeight;
        const start = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            
            element.style.height = (targetHeight * progress) + 'px';
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                element.style.height = 'auto';
                element.style.overflow = 'visible';
            }
        };
        
        requestAnimationFrame(animate);
    }
};

// Validation utilities
const ValidationUtils = {
    isEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    isPhone: function(phone) {
        const phoneRegex = /^(\+62|62|0)[0-9]{9,13}$/;
        return phoneRegex.test(phone.replace(/\s+/g, ''));
    },
    
    isNotEmpty: function(value) {
        return value && value.trim().length > 0;
    },
    
    isNumeric: function(value) {
        return !isNaN(value) && !isNaN(parseFloat(value));
    },
    
    isInRange: function(value, min, max) {
        const num = parseFloat(value);
        return !isNaN(num) && num >= min && num <= max;
    }
};

// Date utilities
const DateUtils = {
    formatDate: function(date, format = 'dd/mm/yyyy') {
        if (!date) return '';
        
        const d = new Date(date);
        if (isNaN(d.getTime())) return '';
        
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');
        
        switch (format) {
            case 'dd/mm/yyyy':
                return `${day}/${month}/${year}`;
            case 'yyyy-mm-dd':
                return `${year}-${month}-${day}`;
            case 'dd/mm/yyyy hh:mm':
                return `${day}/${month}/${year} ${hours}:${minutes}`;
            case 'relative':
                return this.getRelativeTime(d);
            default:
                return d.toLocaleDateString('id-ID');
        }
    },
    
    getRelativeTime: function(date) {
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Baru saja';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} menit yang lalu`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} jam yang lalu`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} hari yang lalu`;
        if (diffInSeconds < 31536000) return `${Math.floor(diffInSeconds / 2592000)} bulan yang lalu`;
        return `${Math.floor(diffInSeconds / 31536000)} tahun yang lalu`;
    },
    
    addDays: function(date, days) {
        const result = new Date(date);
        result.setDate(result.getDate() + days);
        return result;
    },
    
    isToday: function(date) {
        const today = new Date();
        const checkDate = new Date(date);
        return checkDate.toDateString() === today.toDateString();
    },
    
    isFuture: function(date) {
        return new Date(date) > new Date();
    }
};

// Initialize all utilities when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('All utilities initialized');
    
    // Show welcome message
    setTimeout(() => {
        showToast('Selamat datang di halaman riwayat donor', 'info', 3000);
    }, 1000);
});

// Cleanup function
window.addEventListener('beforeunload', function() {
    // Save current state
    saveFilterPreferences();
    
    // Clear any pending timeouts
    const highestTimeoutId = setTimeout(() => {}, 0);
    for (let i = 0; i < highestTimeoutId; i++) {
        clearTimeout(i);
    }
});

// Export functions to global scope for inline event handlers
window.showDetailModal = showDetailModal;
window.closeDetailModal = closeDetailModal;
window.showQRModal = showQRModal;
window.closeQRModal = closeQRModal;
window.generateQRCode = generateQRCode;
window.printQRCode = printQRCode;
window.downloadQRCode = downloadQRCode;
window.shareQRCode = shareQRCode;
window.filterDonors = filterDonors;
window.sortDonors = sortDonors;
window.clearFilters = clearFilters;
window.removeToast = removeToast;
window.showToast = showToast;

console.log('Donor history page script loaded successfully');
</script>

@endsection
