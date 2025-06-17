{{-- resources/views/informasi/events/show.blade.php --}}
@extends('layouts.app')

@section('title', $event->title . 'Redwave')

@section('content')
<!-- Breadcrumb -->
<section class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                        <i class="fas fa-home mr-2"></i>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('informasi.events.index') }}" class="text-sm font-medium text-gray-700 hover:text-red-600">Event</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">{{ Str::limit($event->title, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</section>

<!-- Event Detail -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Event Header -->
                    <div>
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Event Disetujui
                            </span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ $event->title }}</h1>
                        <div class="flex flex-wrap items-center text-gray-600 space-x-6 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-red-500"></i>
                                <span class="font-medium">{{ $event->formatted_date }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2 text-red-500"></i>
                                <span>{{ $event->formatted_time }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Event Image -->
                    @if($event->image)
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset('storage/events/' . $event->image) }}" 
                             alt="{{ $event->title }}" 
                             class="w-full h-64 md:h-96 object-cover">
                    </div>
                    @endif

                    <!-- Event Description -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Tentang Event</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed text-lg">{{ $event->description }}</p>
                        </div>
                    </div>

                    <!-- Event Content -->
                    @if($event->content)
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Event</h2>
                        <div class="prose max-w-none">
                            <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $event->content }}</div>
                        </div>
                    </div>
                    @endif

                    <!-- Share Section -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-red-800 mb-4">Bagikan Event Ini</h3>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="shareToWhatsApp()" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                                <i class="fab fa-whatsapp mr-2"></i>
                                WhatsApp
                            </button>
                            <button onclick="shareToFacebook()" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                                <i class="fab fa-facebook mr-2"></i>
                                Facebook
                            </button>
                            <button onclick="shareToTwitter()" 
                                    class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                                <i class="fab fa-twitter mr-2"></i>
                                Twitter
                            </button>
                            <button onclick="copyEventLink()" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                                <i class="fas fa-copy mr-2"></i>
                                Salin Link
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Event Info Card -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">Informasi Event</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-red-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Tanggal</p>
                                    <p class="font-semibold text-gray-800">{{ $event->formatted_date }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-red-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Waktu</p>
                                    <p class="font-semibold text-gray-800">{{ $event->formatted_time }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-red-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Lokasi</p>
                                    <p class="font-semibold text-gray-800">{{ $event->location }}</p>
                                </div>
                            </div>
                            
                            @if($event->max_participants)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-red-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Maksimal Peserta</p>
                                    <p class="font-semibold text-gray-800">{{ $event->max_participants }} orang</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Contact Info -->
                        @if($event->contact_person || $event->contact_phone)
                        <div class="border-t border-gray-200 mt-6 pt-6">
                            <h4 class="font-semibold text-gray-800 mb-4">Kontak</h4>
                            <div class="space-y-3">
                                @if($event->contact_person)
                                <div class="flex items-center">
                                    <i class="fas fa-user text-gray-400 mr-3"></i>
                                    <span class="text-gray-700">{{ $event->contact_person }}</span>
                                </div>
                                @endif
                                
                                @if($event->contact_phone)
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-400 mr-3"></i>
                                    <a href="tel:{{ $event->contact_phone }}" 
                                       class="text-red-600 hover:text-red-700 font-medium">{{ $event->contact_phone }}</a>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="border-t border-gray-200 mt-6 pt-6 space-y-3">
                            @if($event->contact_phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $event->contact_phone) }}?text=Halo, saya tertarik dengan event {{ $event->title }}" 
                               target="_blank"
                               class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Hubungi via WhatsApp
                            </a>
                            @endif
                            
                            <a href="{{ route('informasi.events.index') }}" 
                               class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Event
                            </a>
                        </div>
                    </div>

                    <!-- Related Events -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Event Lainnya</h3>
                        <div class="space-y-4">
                            @php
                                $relatedEvents = App\Models\Event::approved()
                                    ->upcoming()
                                    ->where('id', '!=', $event->id)
                                    ->limit(3)
                                    ->get();
                            @endphp
                            
                            @forelse($relatedEvents as $relatedEvent)
                            <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <h4 class="font-medium text-gray-800 mb-2 line-clamp-2">{{ $relatedEvent->title }}</h4>
                                <div class="text-sm text-gray-600 mb-3">
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                        {{ $relatedEvent->formatted_date }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                        {{ Str::limit($relatedEvent->location, 25) }}
                                    </div>
                                </div>
                                <a href="{{ route('informasi.events.show', $relatedEvent) }}" 
                                   class="text-red-600 hover:text-red-700 text-sm font-medium">
                                    Lihat Detail →
                                </a>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm">Belum ada event lainnya</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Share Functions
function shareToWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(`Lihat event menarik ini: {{ $event->title }}`);
    window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
}

function shareToFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareToTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(`{{ $event->title }} - PMI Kota Bandung`);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
}

function copyEventLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-2"></i>Tersalin!';
        button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        button.classList.add('bg-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-gray-600', 'hover:bg-gray-700');
        }, 2000);
    });
}
</script>

@endsection
