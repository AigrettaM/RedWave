{{-- resources/views/informasi/events/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Event - PMI Kota Bandung')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-red-600 to-red-800 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Event PMI Kota Bandung</h1>
            <p class="text-xl md:text-2xl mb-8 text-red-100">
                Bergabunglah dalam berbagai kegiatan kemanusiaan dan sosial bersama PMI Kota Bandung
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.create') }}" 
                   class="bg-white text-red-600 hover:bg-red-50 px-8 py-3 rounded-lg font-semibold transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>
                    Ajukan Event
                </a>
                <a href="#events-list" 
                   class="border-2 border-white text-white hover:bg-white hover:text-red-600 px-8 py-3 rounded-lg font-semibold transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Lihat Event
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Events List -->
<section id="events-list" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Event Mendatang</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Ikuti berbagai kegiatan menarik yang diselenggarakan oleh PMI Kota Bandung
                </p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg mb-8 flex items-center max-w-2xl mx-auto">
                    <i class="fas fa-check-circle mr-3"></i>
                    <div>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Events Grid -->
            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Event Image -->
                        <div class="relative h-48 bg-gray-200">
                            @if($event->image)
                                <img src="{{ asset('storage/events/' . $event->image) }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            
                            <!-- Date Badge -->
                            <div class="absolute top-4 left-4 bg-red-600 text-white px-3 py-2 rounded-lg text-center">
                                <div class="text-xs font-medium">{{ $event->event_date->format('M') }}</div>
                                <div class="text-lg font-bold">{{ $event->event_date->format('d') }}</div>
                            </div>
                        </div>

                        <!-- Event Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2">{{ $event->title }}</h3>
                            
                            <!-- Event Info -->
                            <div class="space-y-2 mb-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-gray-400"></i>
                                    {{ $event->formatted_time }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                    {{ Str::limit($event->location, 30) }}
                                </div>
                                @if($event->max_participants)
                                <div class="flex items-center">
                                    <i class="fas fa-users mr-2 text-gray-400"></i>
                                    Maks. {{ $event->max_participants }} peserta
                                </div>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="text-gray-700 mb-4 line-clamp-3">{{ Str::limit($event->description, 120) }}</p>

                            <!-- Action Button -->
                            <a href="{{ route('events.show', $event) }}" 
                               class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-calendar-alt text-gray-300 text-6xl mb-6"></i>
                        <h3 class="text-xl font-medium text-gray-800 mb-4">Belum Ada Event</h3>
                        <p class="text-gray-600 mb-8">Saat ini belum ada event yang tersedia. Silakan ajukan event Anda atau periksa kembali nanti.</p>
                        <a href="{{ route('events.create') }}" 
                           class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Ajukan Event
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-red-600 text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Punya Ide Event?</h2>
            <p class="text-xl mb-8 text-red-100">
                Ajukan event Anda dan mari bersama-sama membangun kegiatan yang bermanfaat untuk masyarakat
            </p>
            <a href="{{ route('events.create') }}" 
               class="bg-white text-red-600 hover:bg-red-50 px-8 py-4 rounded-lg font-semibold text-lg transition-colors inline-flex items-center">
                <i class="fas fa-lightbulb mr-3"></i>
                Ajukan Event Sekarang
            </a>
        </div>
    </div>
</section>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

@endsection
