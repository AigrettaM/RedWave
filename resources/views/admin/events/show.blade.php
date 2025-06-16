{{-- resources/views/admin/events/show.blade.php --}}
@extends('dashboardlayout.app')

@section('title', 'Detail Event')
@section('page-title', 'Detail Event')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $event->status_badge }}">
                            {{ $event->status_text }}
                        </span>
                        @if($event->type === 'user')
                            <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-user mr-1"></i>
                                Pengajuan User
                            </span>
                        @endif
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $event->title }}</h1>
                    <div class="flex flex-wrap items-center text-gray-600 space-x-6">
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2 text-gray-400"></i>
                            {{ $event->formatted_date }}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-gray-400"></i>
                            {{ $event->formatted_time }}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                            {{ $event->location }}
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                    @if($event->status === 'pending')
                        <button onclick="approveEvent({{ $event->id }})" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            Setujui
                        </button>
                        <button onclick="showRejectModal({{ $event->id }}, '{{ $event->title }}')" 
                                class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Tolak
                        </button>
                    @endif
                    <a href="{{ route('admin.events.edit', $event) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <a href="{{ route('admin.events.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Image -->
                @if($event->image)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('storage/events/' . $event->image) }}" 
                         alt="{{ $event->title }}" 
                         class="w-full h-64 object-cover">
                </div>
                @endif

                <!-- Event Description -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Deskripsi Event</h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                    </div>
                </div>

                <!-- Event Content -->
                @if($event->content)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Event</h2>
                    <div class="prose max-w-none">
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $event->content }}</div>
                    </div>
                </div>
                @endif

                <!-- Admin Notes -->
                @if($event->admin_notes)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <i class="fas fa-sticky-note text-yellow-500 mr-3 mt-1"></i>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-yellow-800 mb-2">Catatan Admin</h3>
                            <p class="text-yellow-700">{{ $event->admin_notes }}</p>
                            @if($event->approver)
                                <p class="text-sm text-yellow-600 mt-2">
                                    oleh {{ $event->approver->name }} • {{ $event->updated_at->format('d M Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Event Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Event</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-calendar-alt text-gray-400 mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal</p>
                                <p class="font-medium text-gray-800">{{ $event->formatted_date }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-clock text-gray-400 mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-600">Waktu</p>
                                <p class="font-medium text-gray-800">{{ $event->formatted_time }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-600">Lokasi</p>
                                <p class="font-medium text-gray-800">{{ $event->location }}</p>
                            </div>
                        </div>
                        
                        @if($event->max_participants)
                        <div class="flex items-start">
                            <i class="fas fa-users text-gray-400 mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-600">Maksimal Peserta</p>
                                <p class="font-medium text-gray-800">{{ $event->max_participants }} orang</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-gray-400 mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->status_badge }}">
                                    {{ $event->status_text }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                @if($event->contact_person || $event->contact_phone)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Kontak</h3>
                    <div class="space-y-3">
                        @if($event->contact_person)
                        <div class="flex items-center">
                            <i class="fas fa-user text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Narahubung</p>
                                <p class="font-medium text-gray-800">{{ $event->contact_person }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($event->contact_phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Telepon</p>
                                <p class="font-medium text-gray-800">{{ $event->contact_phone }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Submission Info (for user submitted events) -->
                @if($event->type === 'user')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-blue-800 mb-4">
                        <i class="fas fa-user mr-2"></i>
                        Info Pengaju
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-blue-600">Nama</p>
                            <p class="font-medium text-blue-800">{{ $event->submitted_by }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600">Email</p>
                            <p class="font-medium text-blue-800">{{ $event->submitted_email }}</p>
                        </div>
                        @if($event->submitted_phone)
                        <div>
                            <p class="text-sm text-blue-600">Telepon</p>
                            <p class="font-medium text-blue-800">{{ $event->submitted_phone }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-blue-600">Tanggal Pengajuan</p>
                            <p class="font-medium text-blue-800">{{ $event->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Event Meta -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Metadata</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dibuat:</span>
                            <span class="text-gray-800">{{ $event->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Diperbarui:</span>
                            <span class="text-gray-800">{{ $event->updated_at->format('d M Y H:i') }}</span>
                        </div>
                        @if($event->approved_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Disetujui:</span>
                            <span class="text-gray-800">{{ $event->approved_at->format('d M Y H:i') }}</span>
                        </div>
                        @endif
                        @if($event->approver)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Oleh:</span>
                            <span class="text-gray-800">{{ $event->approver->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        @if($event->status === 'approved')
                        <a href="{{ route('admin.events.show', $event) }}" 
                           target="_blank"
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Lihat di Frontend
                        </a>
                        @endif
                        
                        <button onclick="deleteEvent({{ $event->id }}, '{{ $event->title }}')" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Event
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-medium text-gray-900">Tolak Event</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Berikan alasan penolakan untuk event "<span id="eventTitle"></span>":</p>
                    <textarea name="admin_notes" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                              rows="4" 
                              placeholder="Masukkan alasan penolakan..."
                              required></textarea>
                </div>
                <div class="flex items-center justify-end px-6 py-3 bg-gray-50 space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700">
                        Tolak Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Approve Event
function approveEvent(eventId) {
    if (confirm('Yakin ingin menyetujui event ini?')) {
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
}

// Close Reject Modal
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

// Delete Event
function deleteEvent(eventId, eventTitle) {
    if (confirm(`Yakin ingin menghapus event "${eventTitle}"?`)) {
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


// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>

@endsection
