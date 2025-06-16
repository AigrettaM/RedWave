{{-- resources/views/admin/events/index.blade.php --}}
@extends('dashboardlayout.app')

@section('title', 'Manajemen Event')
@section('page-title', 'Manajemen Event')

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

        <!-- Header dengan Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Events -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Total Event</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $events->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Pending Approval -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Menunggu Persetujuan</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</div>
                    </div>
                </div>
            </div>

            <!-- Approved Events -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-check text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Event Disetujui</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $approvedCount }}</div>
                    </div>
                </div>
            </div>

            <!-- Rejected Events -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-times text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Event Ditolak</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $events->where('status', 'rejected')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Actions -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Event</h1>
                    <p class="text-gray-600">Kelola semua event dan pengajuan event dari user</p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <a href="{{ route('admin.events.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Event Baru
                    </a>
                    <button onclick="refreshPage()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex-1 max-w-lg">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-red-500 focus:border-red-500" placeholder="Cari event...">
                    </div>
                </div>
                <div class="flex space-x-2">
                    <select id="statusFilter" class="border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500">
                        <option value="all">Semua Status</option>
                        <option value="pending">Menunggu Persetujuan</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                    <select id="typeFilter" class="border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500">
                        <option value="all">Semua Tipe</option>
                        <option value="admin">Event Admin</option>
                        <option value="user">Pengajuan User</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6">
                    <button onclick="filterEvents('all')" class="filter-tab active border-red-500 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Semua Event
                        <span class="ml-2 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $events->count() }}</span>
                    </button>
                    <button onclick="filterEvents('pending')" class="filter-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Menunggu Persetujuan
                        @if($pendingCount > 0)
                            <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                        @endif
                    </button>
                    <button onclick="filterEvents('approved')" class="filter-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Disetujui
                        <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $approvedCount }}</span>
                    </button>
                    <button onclick="filterEvents('rejected')" class="filter-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Ditolak
                        <span class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $events->where('status', 'rejected')->count() }}</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Events List -->
        <div class="space-y-6" id="eventsList">
            @forelse($events as $event)
            <div class="bg-white rounded-lg shadow-md overflow-hidden event-card hover:shadow-lg transition-shadow duration-200" 
                 data-status="{{ $event->status }}" 
                 data-type="{{ $event->type ?? 'admin' }}"
                 data-title="{{ strtolower($event->title) }}"
                 data-location="{{ strtolower($event->location) }}">
                <div class="md:flex">
                    <!-- Event Image -->
                    <div class="md:w-48 md:flex-shrink-0 relative">
                        @if($event->image)
                            @php
                                $imagePath = 'storage/events/' . $event->image;
                                $fullPath = public_path($imagePath);
                                $imageExists = file_exists($fullPath);
                            @endphp
                            
                            @if($imageExists)
                                <img class="h-48 w-full object-cover md:h-full md:w-48" 
                                     src="{{ asset($imagePath) }}" 
                                     alt="{{ $event->title }}"
                                     loading="lazy">
                            @else
                                <div class="h-48 w-full md:h-full md:w-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                                        <p class="text-gray-500 text-sm font-medium">Gambar Tidak Ditemukan</p>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="h-48 w-full md:h-full md:w-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-calendar-alt text-gray-400 text-4xl mb-2"></i>
                                    <p class="text-gray-500 text-sm font-medium">No Image</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Status Overlay -->
                        <div class="absolute top-2 left-2">
                            @switch($event->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending
                                    </span>
                                    @break
                                @case('approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Approved
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>
                                        Rejected
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <!-- Event Content -->
                    <div class="p-6 flex-1">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Event Type Badge -->
                                <div class="flex items-center mb-3 space-x-2">
                                    @if(($event->type ?? 'admin') === 'user')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-user mr-1"></i>
                                            Pengajuan User
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-crown mr-1"></i>
                                            Event Admin
                                        </span>
                                    @endif
                                    
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $event->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <!-- Event Title -->
                                <h3 class="text-xl font-bold text-gray-900 mb-3 hover:text-red-600 transition-colors">
                                    <a href="{{ route('admin.events.show', $event) }}">{{ $event->title }}</a>
                                </h3>

                                <!-- Event Info -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-2 text-red-400 w-4"></i>
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-red-400 w-4"></i>
                                        @if($event->start_time && $event->end_time)
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                        @elseif($event->event_time)
                                            {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}
                                        @else
                                            Waktu belum ditentukan
                                        @endif
                                    </div>
                                    <div class="flex items-center md:col-span-2">
                                        <i class="fas fa-map-marker-alt mr-2 text-red-400 w-4"></i>
                                        {{ $event->location }}
                                    </div>
                                    @if($event->max_participants)
                                        <div class="flex items-center">
                                            <i class="fas fa-users mr-2 text-red-400 w-4"></i>
                                            Max {{ $event->max_participants }} peserta
                                        </div>
                                    @endif
                                    @if(($event->type ?? 'admin') === 'user')
                                        <div class="flex items-center">
                                            <i class="fas fa-user mr-2 text-red-400 w-4"></i>
                                            {{ $event->submitted_by }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope mr-2 text-red-400 w-4"></i>
                                            {{ $event->submitted_email }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Event Description -->
                                <p class="text-gray-700 mb-4 leading-relaxed">{{ Str::limit($event->description, 200) }}</p>

                                <!-- Admin Notes -->
                                @if($event->admin_notes)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                                        <div class="flex">
                                            <i class="fas fa-sticky-note text-yellow-400 mr-2 mt-0.5 flex-shrink-0"></i>
                                            <div>
                                                <p class="text-sm font-medium text-yellow-800">Catatan Admin:</p>
                                                <p class="text-sm text-yellow-700 mt-1">{{ $event->admin_notes }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.events.show', $event) }}" 
                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Detail
                            </a>

                            <a href="{{ route('admin.events.edit', $event) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </a>

                            @if($event->status === 'pending')
                                <button onclick="approveEvent({{ $event->id }})" 
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                    <i class="fas fa-check mr-2"></i>
                                    Setujui
                                </button>

                                <button onclick="showRejectModal({{ $event->id }}, '{{ addslashes($event->title) }}')" 
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                    <i class="fas fa-times mr-2"></i>
                                    Tolak
                                </button>
                            @endif

                            @if($event->status === 'approved')
                                <a href="{{ route('admin.events.show', $event) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Lihat Public
                                </a>
                            @endif

                            <button onclick="deleteEvent({{ $event->id }}, '{{ addslashes($event->title) }}')" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-calendar-alt text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Event</h3>
                <p class="text-gray-500 mb-6">Mulai dengan membuat event baru atau tunggu pengajuan dari user</p>
                <a href="{{ route('admin.events.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Event Baru
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination (if needed) -->
        @if($events->count() > 10)
            <div class="mt-8 flex justify-center">
                <div class="bg-white px-4 py-3 rounded-lg shadow-md">
                    <p class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $events->count() }}</span> event
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-medium text-gray-900">Tolak Event</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Berikan alasan penolakan untuk event "<span id="eventTitle" class="font-medium"></span>":</p>
                    <textarea name="admin_notes" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                              rows="4" 
                              placeholder="Masukkan alasan penolakan yang jelas..."
                              required></textarea>
                </div>
                <div class="flex items-center justify-end px-6 py-3 bg-gray-50 space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Tolak Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <i class="fas fa-spinner fa-spin text-red-600 text-xl"></i>
            <span class="text-gray-700">Memproses...</span>
        </div>
    </div>
</div>

<script>
// Global variables
let allEvents = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    setupSearch();
});

// Initialize filters
function initializeFilters() {
    allEvents = Array.from(document.querySelectorAll('.event-card'));
    
    // Set first tab as active
    const firstTab = document.querySelector('.filter-tab');
    if (firstTab) {
        firstTab.classList.add('active', 'border-red-500', 'text-red-600');
        firstTab.classList.remove('border-transparent', 'text-gray-500');
    }
}

// Setup search functionality
function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(filterAndSearch, 300));
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterAndSearch);
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', filterAndSearch);
    }
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

// Filter events by status (tab)
function filterEvents(status) {
    const tabs = document.querySelectorAll('.filter-tab');
    
    // Update active tab
    tabs.forEach(tab => {
        tab.classList.remove('active', 'border-red-500', 'text-red-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.classList.add('active', 'border-red-500', 'text-red-600');
    
    // Update status filter dropdown
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.value = status;
    }
    
    filterAndSearch();
}

// Combined filter and search function
function filterAndSearch() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('statusFilter')?.value || 'all';
    const typeFilter = document.getElementById('typeFilter')?.value || 'all';
    
    let visibleCount = 0;
    
    allEvents.forEach(card => {
        const title = card.dataset.title || '';
        const location = card.dataset.location || '';
        const status = card.dataset.status || '';
        const type = card.dataset.type || 'admin';
        
        const matchesSearch = !searchTerm || 
            title.includes(searchTerm) || 
            location.includes(searchTerm);
        
        const matchesStatus = statusFilter === 'all' || status === statusFilter;
        const matchesType = typeFilter === 'all' || type === typeFilter;
        
        if (matchesSearch && matchesStatus && matchesType) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    toggleEmptyState(visibleCount === 0);
}

// Toggle empty state
function toggleEmptyState(show) {
    const eventsList = document.getElementById('eventsList');
    let emptyState = document.getElementById('emptyState');
    
    if (show) {
        if (!emptyState) {
            emptyState = document.createElement('div');
            emptyState.id = 'emptyState';
            emptyState.className = 'bg-white rounded-lg shadow-md p-12 text-center';
            emptyState.innerHTML = `
                <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Event Ditemukan</h3>
                <p class="text-gray-500 mb-6">Coba ubah filter atau kata kunci pencarian</p>
                <button onclick="clearFilters()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Hapus Filter
                </button>
            `;
            eventsList.appendChild(emptyState);
        }
        emptyState.style.display = 'block';
    } else {
        if (emptyState) {
            emptyState.style.display = 'none';
        }
    }
}

// Clear all filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('typeFilter').value = 'all';
    
    // Reset active tab
    const tabs = document.querySelectorAll('.filter-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active', 'border-red-500', 'text-red-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    const firstTab = document.querySelector('.filter-tab');
    if (firstTab) {
        firstTab.classList.add('active', 'border-red-500', 'text-red-600');
        firstTab.classList.remove('border-transparent', 'text-gray-500');
    }
    
    filterAndSearch();
}

// Show loading overlay
function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

// Hide loading overlay
function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

// Approve Event
function approveEvent(eventId) {
    if (confirm('Yakin ingin menyetujui event ini?')) {
        showLoading();
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/events/${eventId}/approve`;
        form.style.display = 'none';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Show Reject Modal
function showRejectModal(eventId, eventTitle) {
    document.getElementById('eventTitle').textContent = eventTitle;
    document.getElementById('rejectForm').action = `/admin/events/${eventId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
    
    // Focus on textarea
    setTimeout(() => {
        document.querySelector('#rejectModal textarea').focus();
    }, 100);
}

// Close Reject Modal
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

// Delete Event
function deleteEvent(eventId, eventTitle) {
    if (confirm(`Yakin ingin menghapus event "${eventTitle}"?\n\nTindakan ini tidak dapat dibatalkan!`)) {
        showLoading();
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/events/${eventId}`;
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

// Refresh Page
function refreshPage() {
    showLoading();
    window.location.reload();
}

// Handle reject form submission
document.getElementById('rejectForm').addEventListener('submit', function(e) {
    const textarea = this.querySelector('textarea');
    if (textarea.value.trim().length < 10) {
        e.preventDefault();
        alert('Alasan penolakan harus minimal 10 karakter!');
        textarea.focus();
        return;
    }
    
    showLoading();
});

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

// Handle escape key for modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('rejectModal').classList.contains('hidden')) {
            closeRejectModal();
        }
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentElement) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentElement) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
});

// Smooth scroll to top when filtering
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Add scroll to top after filtering
const originalFilterAndSearch = filterAndSearch;
filterAndSearch = function() {
    originalFilterAndSearch();
    if (window.scrollY > 200) {
        scrollToTop();
    }
};

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('searchInput').focus();
    }
    
    // Ctrl/Cmd + R to refresh (prevent default browser refresh)
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        refreshPage();
    }
});

// Add tooltips for action buttons
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('button[onclick], a[href]');
    buttons.forEach(button => {
        if (button.textContent.includes('Detail')) {
            button.title = 'Lihat detail lengkap event';
        } else if (button.textContent.includes('Edit')) {
            button.title = 'Edit informasi event';
        } else if (button.textContent.includes('Setujui')) {
            button.title = 'Setujui dan publikasikan event';
        } else if (button.textContent.includes('Tolak')) {
            button.title = 'Tolak event dengan alasan';
        } else if (button.textContent.includes('Hapus')) {
            button.title = 'Hapus event secara permanen';
        } else if (button.textContent.includes('Lihat Public')) {
            button.title = 'Buka halaman public event';
        }
    });
});

// Handle image loading errors
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[src*="storage/events"]');
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.style.display = 'none';
            
            // Show placeholder
            const placeholder = this.nextElementSibling;
            if (placeholder && placeholder.classList.contains('bg-gradient-to-br')) {
                placeholder.style.display = 'flex';
            }
        });
    });
});

// Performance: Lazy load images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Add loading states to buttons
function addLoadingState(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
    button.disabled = true;
    
    return function() {
        button.innerHTML = originalText;
        button.disabled = false;
    };
}

// Enhanced error handling
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    hideLoading();
});

// Handle network errors
window.addEventListener('online', function() {
    console.log('Connection restored');
});

window.addEventListener('offline', function() {
    console.log('Connection lost');
    alert('Koneksi internet terputus. Beberapa fitur mungkin tidak berfungsi.');
});

console.log('Admin Events Management loaded successfully');
</script>

<style>
/* Custom styles for better UX */
.event-card {
    transition: all 0.2s ease-in-out;
}

.event-card:hover {
    transform: translateY(-2px);
}

.filter-tab.active {
    border-color: #dc2626 !important;
    color: #dc2626 !important;
}

/* Loading animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.event-card {
    animation: fadeIn 0.3s ease-out;
}

/* Smooth transitions */
* {
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

/* Focus styles for accessibility */
button:focus, input:focus, textarea:focus, select:focus {
    outline: 2px solid #dc2626;
    outline-offset: 2px;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Print styles */
@media print {
    .bg-white {
        background: white !important;
    }
    
    button, .shadow-md {
        display: none !important;
    }
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .grid {
        gap: 1rem;
    }
    
    .text-3xl {
        font-size: 1.5rem;
    }
}
</style>

@endsection
